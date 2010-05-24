<?php
/**
 * Represents a category for organizing modElement instances.
 *
 * @package modx
 */
class modCategory extends modAccessibleSimpleObject {
    /**
     *  @var boolean Monitors whether parent has been changed.
     *  @access protected
     */
    protected $_parentChanged = false;

    /**
     * @var array A list of invalid characters in the name of an Element.
     * @access protected
     */
    protected $_invalidCharacters = array('!','@','#','$','%','^','&','*',
    '(',')','+','=','[',']','{','}','\'','"',':',';','\\','/','<','>','?'
    ,',','`','~');

    /**
     * Overrides xPDOObject::set to strip invalid characters from element names.
     *
     * {@inheritDoc}
     */
    public function set($k, $v= null, $vType= '') {
        $set = false;
        switch ($k) {
            case 'category':
                $v = str_replace($this->_invalidCharacters,'',$v);
            default:
                $oldParentId = $this->get('parent');
                $set = parent::set($k,$v,$vType);
                if ($set && $k == 'parent' && $v != $oldParentId && !$this->isNew()) {
                    $this->_parentChanged = true;
                }
        }
        return $set;
    }

    /**
     * Overrides xPDOObject::save to fire modX-specific events
     *
     * {@inheritDoc}
     */
    public function save($cacheFlag = null) {
        $isNew = $this->isNew();
        
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnCategoryBeforeSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'category' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        }
        $saved = parent :: save($cacheFlag);

        /* if a new board */
        if ($saved && $isNew) {
            $this->buildClosure();
        }
        /* if parent changed on existing object, rebuild closure table */
        if (!$isNew && $this->_parentChanged) {
            $this->rebuildClosure();
        }

        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnCategorySave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'category' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        }

        return $saved;
    }

    /**
     * Overrides xPDOObject::remove to reset all Element categories back to 0
     * and fire modX-specific events.
     *
     * {@inheritDoc}
     */
    public function remove(array $ancestors = array()) {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnCategoryBeforeRemove',array(
                'category' => &$this,
                'ancestors' => $ancestors,
            ));
        }
        $removed = parent :: remove($ancestors);

        if ($removed && $this->xpdo instanceof modX) {
            $elementClasses = array(
                'modChunk',
                'modPlugin',
                'modSnippet',
                'modTemplate',
                'modTemplateVar',
            );
            foreach ($elementClasses as $classKey) {
                $elements = $this->xpdo->getCollection($classKey,array('category' => $this->get('id')));
                foreach ($elements as $element) {
                    $element->set('category',0);
                    $element->save();
                }
            }

            $this->xpdo->invokeEvent('OnCategoryRemove',array(
                'category' => &$this,
                'ancestors' => $ancestors,
            ));
        }

        return $removed;
    }

    /**
     * Loads the access control policies applicable to this category.
     *
     * {@inheritdoc}
     */
    public function findPolicy($context = '') {
        $policy = array();
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if (empty($this->_policies) || !isset($this->_policies[$context])) {
            $accessTable = $this->xpdo->getTableName('modAccessCategory');
            $policyTable = $this->xpdo->getTableName('modAccessPolicy');
            $categoryClosureTable = $this->xpdo->getTableName('modCategoryClosure');
            $sql = "SELECT `Acl`.`target`, `Acl`.`principal`, `Acl`.`authority`, `Acl`.`policy`, `Policy`.`data` FROM {$accessTable} `Acl` " .
                    "LEFT JOIN {$policyTable} `Policy` ON `Policy`.`id` = `Acl`.`policy` " .
                    "JOIN {$categoryClosureTable} `CategoryClosure` ON `CategoryClosure`.`descendant` = :category " .
                    "AND `Acl`.`principal_class` = 'modUserGroup' " .
                    "AND `CategoryClosure`.`ancestor` = `Acl`.`target` " .
                    "AND (`Acl`.`context_key` = :context OR `Acl`.`context_key` IS NULL OR `Acl`.`context_key` = '') " .
                    "GROUP BY `target`, `principal`, `authority`, `policy` " .
                    "ORDER BY `CategoryClosure`.`depth` DESC, `authority` ASC";
            $bindings = array(
                ':category' => $this->get('id'),
                ':context' => $context,
            );
            $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
            if ($query->stmt && $query->stmt->execute()) {
                while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $policy['modAccessCategory'][$row['target']][] = array(
                        'principal' => $row['principal'],
                        'authority' => $row['authority'],
                        'policy' => $row['data'] ? $this->xpdo->fromJSON($row['data'], true) : array(),
                    );
                }
            }
            $this->_policies[$context] = $policy;
        } else {
            $policy = $this->_policies[$context];
        }
        return $policy;
    }

    /**
     * Build the closure table for this instance.
     *
     * @return boolean True unless building the closure fails and instance is removed.
     */
    public function buildClosure() {
        $id = $this->get('id');
        $parent = $this->get('parent');

        /* create self closure */
        $cl = $this->xpdo->newObject('modCategoryClosure');
        $cl->set('ancestor',$id);
        $cl->set('descendant',$id);
        if ($cl->save() === false) {
            $this->remove();
            return false;
        }

        /* create closures and calculate rank */
        $tableName = $this->xpdo->getTableName('modCategoryClosure');
        $c = $this->xpdo->newQuery('modCategoryClosure');
        $c->where(array(
            'descendant' => $parent,
        ));
        $c->sortby('depth','DESC');
        $gparents = $this->xpdo->getCollection('modCategoryClosure',$c);
        $cgps = count($gparents);
        $i = $cgps - 1;
        $gps = array();
        foreach ($gparents as $gparent) {
            $depth = 0;
            $ancestor = $gparent->get('ancestor');
            if ($ancestor != 0) $depth = $i;
            $obj = $this->xpdo->newObject('modCategoryClosure');
            $obj->set('ancestor',$ancestor);
            $obj->set('descendant',$id);
            $obj->set('depth',$depth);
            $obj->save();
            $i--;
            $gps[] = $ancestor;
        }

        /* handle 0 ancestor closure */
        $rootcl = $this->xpdo->getObject('modCategoryClosure',array(
            'ancestor' => 0,
            'descendant' => $id,
        ));
        if (!$rootcl) {
            $rootcl = $this->xpdo->newObject('modCategoryClosure');
            $rootcl->set('ancestor',0);
            $rootcl->set('descendant',$id);
            $rootcl->set('depth',0);
            $rootcl->save();
        }
        return true;
    }

    /**
     * Rebuild closure table records for this instance, i.e. parent changed.
     */
    public function rebuildClosure() {
        /* first remove old tree path */
        $this->xpdo->removeCollection('modCategoryClosure',array(
            'descendant' => $this->get('id'),
            'ancestor:!=' => $this->get('id'),
        ));

        /* now create new tree path from new parent */
        $newParentId = $this->get('parent');
        $c = $this->xpdo->newQuery('modCategoryClosure');
        $c->where(array(
            'descendant' => $newParentId,
        ));
        $c->sortby('depth','DESC');
        $ancestors= $this->xpdo->getCollection('modCategoryClosure',$c);
        $grandParents = array();
        foreach ($ancestors as $ancestor) {
            $depth = $ancestor->get('depth');
            $grandParentId = $ancestor->get('ancestor');
            /* if already has a depth, inc by 1 */
            if ($depth > 0) $depth++;
            /* if is the new parent node, set depth to 1 */
            if ($grandParentId == $newParentId && $newParentId != 0) { $depth = 1; }
            if ($grandParentId != 0) {
                $grandParents[] = $grandParentId;
            }

            $cl = $this->xpdo->newObject('modCategoryClosure');
            $cl->set('ancestor',$ancestor->get('ancestor'));
            $cl->set('descendant',$this->get('id'));
            $cl->set('depth',$depth);
            $cl->save();
        }
        /* if parent is root, make sure to set the root closure */
        if ($newParentId == 0) {
            $cl = $this->xpdo->newObject('modCategoryClosure');
            $cl->set('ancestor',0);
            $cl->set('descendant',$this->get('id'));
            $cl->save();
        }
    }
}