<?php

namespace MODX\Revolution;

/**
 * Represents a category for organizing modElement instances.
 *
 * @property integer              $parent   The parent category ID, if set. Otherwise defaults to 0.
 * @property string               $category The name of the Category.
 * @property integer              $rank
 *
 * @property modCategory[]        $Children
 * @property modAccessCategory[]  $Acls
 * @property modCategoryClosure[] $Ancestors
 * @property modCategoryClosure[] $Descendants
 *
 * @package MODX\Revolution
 */
class modCategory extends modAccessibleSimpleObject
{
    /**
     * @var boolean Monitors whether parent has been changed.
     * @access protected
     */
    protected $_parentChanged = false;

    /**
     * @var array A list of invalid characters in the name of an Element.
     * @access protected
     */
    protected $_invalidCharacters = [
        '!',
        '@',
        '#',
        '$',
        '%',
        '^',
        '&',
        '*',
        '(',
        ')',
        '+',
        '=',
        '[',
        ']',
        '{',
        '}',
        '\'',
        '"',
        ':',
        ';',
        '\\',
        '/',
        '<',
        '>',
        '?'
        ,
        ',',
        '`',
        '~',
    ];

    /**
     * Overrides xPDOObject::set to strip invalid characters from element names.
     *
     * {@inheritDoc}
     */
    public function set($k, $v = null, $vType = '')
    {
        $set = false;
        switch ($k) {
            case 'category':
                $v = str_replace($this->_invalidCharacters, '', $v);
            default:
                $oldParentId = $this->get('parent');
                $set = parent::set($k, $v, $vType);
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
    public function save($cacheFlag = null)
    {
        $isNew = $this->isNew();

        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnCategoryBeforeSave', [
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'category' => &$this,
                'cacheFlag' => $cacheFlag,
            ]);
        }
        $saved = parent:: save($cacheFlag);

        /* if a new board */
        if ($saved && $isNew) {
            $this->buildClosure();
        }
        /* if parent changed on existing object, rebuild closure table */
        if (!$isNew && $this->_parentChanged) {
            $this->rebuildClosure();
        }

        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnCategorySave', [
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'category' => &$this,
                'cacheFlag' => $cacheFlag,
            ]);
        }

        return $saved;
    }

    /**
     * Overrides xPDOObject::remove to reset all Element categories back to 0
     * and fire modX-specific events.
     *
     * {@inheritDoc}
     */
    public function remove(array $ancestors = [])
    {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnCategoryBeforeRemove', [
                'category' => &$this,
                'ancestors' => $ancestors,
            ]);
        }
        $removed = parent:: remove($ancestors);

        if ($removed && $this->xpdo instanceof modX) {
            $elementClasses = [
                modChunk::class,
                modPlugin::class,
                modSnippet::class,
                modTemplate::class,
                modTemplateVar::class,
            ];
            foreach ($elementClasses as $classKey) {
                $elements = $this->xpdo->getCollection($classKey, ['category' => $this->get('id')]);
                /** @var modElement $element */
                foreach ($elements as $element) {
                    $element->set('category', 0);
                    $element->save();
                }
            }

            $this->xpdo->invokeEvent('OnCategoryRemove', [
                'category' => &$this,
                'ancestors' => $ancestors,
            ]);
        }

        return $removed;
    }

    /**
     * Loads the access control policies applicable to this category.
     *
     * {@inheritdoc}
     */
    public function findPolicy($context = '')
    {
        $policy = [];
        $enabled = true;
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if ($context === $this->xpdo->context->get('key')) {
            $enabled = (boolean)$this->xpdo->getOption('access_category_enabled', null, true);
        } elseif ($this->xpdo->getContext($context)) {
            $enabled = (boolean)$this->xpdo->contexts[$context]->getOption('access_category_enabled', true);
        }
        if ($enabled) {
            if (empty($this->_policies) || !isset($this->_policies[$context])) {
                $aclSelectColumns = $this->xpdo->getSelectColumns(modAccessCategory::class, 'modAccessCategory', '',
                    ['id', 'target', 'principal', 'authority', 'policy']);
                $c = $this->xpdo->newQuery(modAccessCategory::class);
                $c->select($aclSelectColumns);
                $c->select($this->xpdo->getSelectColumns(modAccessPolicy::class, 'Policy', '', ['data']));
                $c->leftJoin(modAccessPolicy::class, 'Policy');
                $c->innerJoin(modCategoryClosure::class, 'CategoryClosure', [
                    'CategoryClosure.descendant:=' => $this->get('id'),
                    'modAccessCategory.principal_class:=' => modUserGroup::class,
                    'CategoryClosure.ancestor = modAccessCategory.target',
                    [
                        'modAccessCategory.context_key:=' => $context,
                        'OR:modAccessCategory.context_key:IS' => null,
                        'OR:modAccessCategory.context_key:=' => '',
                    ],
                ]);
                $c->groupby($aclSelectColumns);
                $c->sortby($this->xpdo->getSelectColumns(modCategoryClosure::class, 'CategoryClosure', '',
                        ['depth']) . ' DESC, ' . $this->xpdo->getSelectColumns(modAccessCategory::class, 'modAccessCategory',
                        '', ['authority']) . ' ASC', '');
                $acls = $this->xpdo->getIterator(modAccessCategory::class, $c);

                foreach ($acls as $acl) {
                    $policy[modAccessCategory::class][$acl->get('target')][] = [
                        'principal' => $acl->get('principal'),
                        'authority' => $acl->get('authority'),
                        'policy' => $acl->get('data') ? $this->xpdo->fromJSON($acl->get('data'), true) : [],
                    ];
                }
                $this->_policies[$context] = $policy;
            } else {
                $policy = $this->_policies[$context];
            }
        }

        return $policy;
    }

    /**
     * Build the closure table for this instance.
     *
     * @return boolean True unless building the closure fails and instance is removed.
     */
    public function buildClosure()
    {
        $id = $this->get('id');
        $parent = $this->get('parent');

        /* create self closure */
        $cl = $this->xpdo->newObject(modCategoryClosure::class);
        $cl->set('ancestor', $id);
        $cl->set('descendant', $id);
        if ($cl->save() === false) {
            $this->remove();

            return false;
        }

        /* create closures and calculate rank */
        $c = $this->xpdo->newQuery(modCategoryClosure::class);
        $c->where([
            'descendant' => $parent,
        ]);
        $c->sortby('depth', 'DESC');
        $gparents = $this->xpdo->getCollection(modCategoryClosure::class, $c);
        $cgps = count($gparents);
        $i = $cgps - 1;
        $gps = [];
        /** @var modCategoryClosure $gparent */
        foreach ($gparents as $gparent) {
            $depth = 0;
            $ancestor = $gparent->get('ancestor');
            if ($ancestor != 0) {
                $depth = $i;
            }
            $obj = $this->xpdo->newObject(modCategoryClosure::class);
            $obj->set('ancestor', $ancestor);
            $obj->set('descendant', $id);
            $obj->set('depth', $depth);
            $obj->save();
            $i--;
            $gps[] = $ancestor;
        }

        /* handle 0 ancestor closure */
        $rootcl = $this->xpdo->getObject(modCategoryClosure::class, [
            'ancestor' => 0,
            'descendant' => $id,
        ]);
        if (!$rootcl) {
            $rootcl = $this->xpdo->newObject(modCategoryClosure::class);
            $rootcl->set('ancestor', 0);
            $rootcl->set('descendant', $id);
            $rootcl->set('depth', 0);
            $rootcl->save();
        }

        return true;
    }

    /**
     * Rebuild closure table records for this instance, i.e. parent changed.
     */
    public function rebuildClosure()
    {
        /* first remove old tree path */
        $this->xpdo->removeCollection(modCategoryClosure::class, [
            'descendant' => $this->get('id'),
            'ancestor:!=' => $this->get('id'),
        ]);

        /* now create new tree path from new parent */
        $newParentId = $this->get('parent');
        $c = $this->xpdo->newQuery(modCategoryClosure::class);
        $c->where([
            'descendant' => $newParentId,
        ]);
        $c->sortby('depth', 'DESC');
        $ancestors = $this->xpdo->getCollection(modCategoryClosure::class, $c);
        $grandParents = [];
        /** @var modCategoryClosure $ancestor */
        foreach ($ancestors as $ancestor) {
            $depth = $ancestor->get('depth');
            $grandParentId = $ancestor->get('ancestor');
            /* if already has a depth, inc by 1 */
            if ($depth > 0) {
                $depth++;
            }
            /* if is the new parent node, set depth to 1 */
            if ($grandParentId == $newParentId && $newParentId != 0) {
                $depth = 1;
            }
            if ($grandParentId != 0) {
                $grandParents[] = $grandParentId;
            }

            $cl = $this->xpdo->newObject(modCategoryClosure::class);
            $cl->set('ancestor', $ancestor->get('ancestor'));
            $cl->set('descendant', $this->get('id'));
            $cl->set('depth', $depth);
            $cl->save();
        }
        /* if parent is root, make sure to set the root closure */
        if ($newParentId == 0) {
            $cl = $this->xpdo->newObject(modCategoryClosure::class);
            $cl->set('ancestor', 0);
            $cl->set('descendant', $this->get('id'));
            $cl->save();
        }
    }
}
