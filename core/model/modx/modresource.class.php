<?php
/**
 * Represents a web resource managed by the modX framework.
 *
 * @package modx
 */
class modResource extends modAccessibleSimpleObject {
    /**
     * Represents the cacheable content for a resource.
     *
     * Note that this is not the raw source content, but the content that is the
     * result of processing cacheable tags within the raw source content.
     * @var string
     */
    public $_content= '';
    /**
     * Represents the output the resource produces.
     * @var string
     */
    public $_output= '';
    /**
     * The context the resource is requested from.
     *
     * Note that this is different than the context_key field that describes a
     * primary context for the resource.
     * @var string
     */
    public $_contextKey= null;
    /**
     * Indicates if the resource has already been processed.
     * @var boolean
     */
    protected $_processed= false;
    /**
     * The cache filename for the resource in the context.
     * @var string
     */
    public $_cacheKey= null;
    /**
     * Indicates if the site cache should be refreshed when saving changes.
     * @var boolean
     */
    protected $_refreshCache= true;

    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->_contextKey= isset ($this->xpdo->context) ? $this->xpdo->context->get('key') : 'web';
        $this->_cacheKey= "[contextKey]/resources/[id]";
    }

    /**
     * Process a resource, transforming source content to output.
     *
     * @return string The processed cacheable content of a resource.
     */
    public function process() {
        if (!$this->get('cacheable') || !$this->_processed || !$this->_content) {
            $this->_content= '';
            $this->_output= '';
            $this->xpdo->getParser();
            if ($baseElement= $this->getOne('Template')) {
                if ($baseElement->process()) {
                    $this->_content= $baseElement->_output;
                    $this->_processed= true;
                }
            } else {
                $this->_content= $this->getContent();
                $maxIterations= intval($this->xpdo->getOption('parser_max_iterations',null,10));
                $this->xpdo->parser->processElementTags('', $this->_content, false, false, '[[', ']]', array(), $maxIterations);
                $this->_processed= true;
            }
        }
        $this->mergeKeywords();
        $this->mergeMetatags();
        return $this->_content;
    }

    /**
     * Gets the raw, unprocessed source content for a resource.
     *
     * @param array $options An array of options implementations can use to
     * accept language, revision identifiers, or other information to alter the
     * behavior of the method.
     * @return string The raw source content for the resource.
     */
    public function getContent(array $options = array()) {
        $content = '';
        if (isset($options['content'])) {
            $content = $options['content'];
        } else {
            $content = $this->get('content');
        }
        return $content;
    }

    /**
     * Set the raw source content for this element.
     *
     * @param mixed $content The source content; implementations can decide if
     * it can only be a string, or some other source from which to retrieve it.
     * @param array $options An array of options implementations can use to
     * accept language, revision identifiers, or other information to alter the
     * behavior of the method.
     * @return boolean True indicates the content was set.
     */
    public function setContent($content, array $options = array()) {
        return $this->set('content', $content);
    }

    /**
     * Merge in Keywords into content.
     *
     * @access public
     * @deprecated 2.0
     */
    public function mergeKeywords() {
        if ($this->get('haskeywords')) {
            $keywords = implode(", ",$this->xpdo->getKeywords());
            $metas = "<meta name=\"keywords\" content=\"{$keywords}\" />\n";
            $this->_content = preg_replace("/(<head>)/i", "\\1\n".$metas, $this->_content);
        }
    }

    /**
     * Merge in META tags to content.
     *
     * @access public
     * @deprecated 2.0
     */
    public function mergeMetaTags() {
        if ($this->get('hasmetatags')) {
            if ($tags = $this->xpdo->getMETATags()) {
                foreach ($tags as $n=>$col) {
                    $tag = strtolower($col['tag']);
                    $tagvalue = $col['tagvalue'];
                    $tagstyle = $col['http_equiv'] ? 'http-equiv':'name';
                    $metas.= "\t<meta $tagstyle=\"$tag\" content=\"$tagvalue\" />\n";
                }
                $this->_content = preg_replace("/(<head>)/i", "\\1\n".$metas, $this->_content);
            }
        }
    }

    /**
     * Returns the cache key for this instance in the current context.
     *
     * @return string The cache key.
     */
    public function getCacheKey() {
        $id = $this->get('id') ? (string) $this->get('id') : '0';
        $context = $this->_contextKey ? $this->_contextKey : 'web';
        if (strpos($this->_cacheKey, '[') !== false) {
            $this->_cacheKey= str_replace('[contextKey]', $context, $this->_cacheKey);
            $this->_cacheKey= str_replace('[id]', $id, $this->_cacheKey);
        }
        return $this->_cacheKey;
    }

    /**
     * Gets a collection of objects related by aggregate or composite relations.
     *
     * {@inheritdoc}
     *
     * Includes special handling for related objects with alias {@link
     * modTemplateVar}, respecting framework security unless specific criteria
     * are provided.
     *
     * @todo Refactor to use the new ABAC security model.
     */
    public function & getMany($alias, $criteria= null, $cacheFlag= false) {
        $collection= array ();
        if ($alias === 'TemplateVars' || $alias === 'modTemplateVar' && ($criteria === null || strtolower($criteria) === 'all')) {
            $c = $this->xpdo->newQuery('modTemplateVar');
            $c->select('
                DISTINCT modTemplateVar.*,
                IF(ISNULL(tvc.value),modTemplateVar.default_text,tvc.value) AS value,
                IF(ISNULL(tvc.value),0,'.$this->id.') AS resourceId
            ');
            $c->innerJoin('modTemplateVarTemplate','tvtpl',array(
                '`tvtpl`.`tmplvarid` = `modTemplateVar`.`id`',
                '`tvtpl`.templateid' => $this->template,
            ));
            $c->leftJoin('modTemplateVarResource','tvc',array(
                '`tvc`.`tmplvarid` = `modTemplateVar`.`id`',
                '`tvc`.contentid' => $this->id,
            ));
            $c->sortby('`tvtpl`.`rank`,`modTemplateVar`.`rank`');

            $collection = $this->xpdo->getCollection('modTemplateVar', $c);
        } else {
            $collection= parent :: getMany($alias, $criteria, $cacheFlag);
        }
        return $collection;
    }

    /**
     * Set a field value by the field key or name.
     *
     * {@inheritdoc}
     *
     * Additional logic added for the following fields:
     * 	-alias: Applies {@link modResource::cleanAlias()}
     *  -contentType: Calls {@link modResource::addOne()} to sync contentType
     *  -content_type: Sets the contentType field appropriately
     */
    public function set($k, $v= null, $vType= '') {
        $rt= false;
        switch ($k) {
            case 'alias' :
                $v= $this->cleanAlias($v);
                break;
            case 'contentType' :
                if ($v !== $this->get('contentType')) {
                    if ($contentType= $this->xpdo->getObject('modContentType', array ('mime_type' => $v))) {
                        if ($contentType->get('mime_type') != $this->get('contentType')) {
                            $this->addOne($contentType, 'ContentType');
                        }
                    }
                }
                break;
            case 'content_type' :
                if ($v !== $this->get('content_type')) {
                    if ($contentType= $this->xpdo->getObject('modContentType', $v)) {
                        if ($contentType->get('mime_type') != $this->get('contentType')) {
                            $this->_fields['contentType']= $contentType->get('mime_type');
                            $this->_dirty['contentType']= 'contentType';
                        }
                    }
                }
                break;
        }
        return parent :: set($k, $v, $vType);
    }

    /**
     * Adds an object related to this modResource by a foreign key relationship.
     *
     * {@inheritdoc}
     *
     * Adds legacy support for keeping the existing contentType field in sync
     * when a modContentType is set using this function.
     */
    public function addOne(& $obj, $alias= '') {
        $added= parent :: addOne($obj, $alias);
        if ($obj instanceof modContentType && $alias= 'ContentType') {
            $this->_fields['contentType']= $obj->get('mime_type');
            $this->_dirty['contentType']= 'contentType';
        }
        return $added;
    }

    /**
     * Sanitizes a string to form a valid URL representation.
     *
     * @todo This needs a full code and concept review, as well as
     * regression testing with current 0.9.6.x branch.
     *
     * @param string $alias A string to sanitize.
     * @return string The sanitized string.
     */
    public function cleanAlias($alias) {
        $charset = $this->xpdo->getOption('modx_charset',null,'UTF-8');
        if (!empty($charset) || strtoupper($charset) == 'UTF-8') {
            $alias= utf8_decode($alias);
        }
        $alias= strtr($alias, array (chr(196) => 'Ae', chr(214) => 'Oe', chr(220) => 'Ue', chr(228) => 'ae', chr(246) => 'oe', chr(252) => 'ue', chr(223) => 'ss'));

        $alias= strip_tags($alias);
        //$alias = strtolower($alias);
        $alias= preg_replace('/&.+?;/', '', $alias); // kill entities
        $alias= preg_replace('/[^\.%A-Za-z0-9 _-]/', '', $alias);
        $alias= preg_replace('/\s+/', '-', $alias);
        $alias= preg_replace('|-+|', '-', $alias);
        $alias= trim($alias);
        return $alias;
    }

    /**
     * Persist new or changed modResource instances to the database container.
     *
     * {@inheritdoc}
     *
     * If the modResource is new, the createdon and createdby fields will be set
     * using the current time and user authenticated in the context.
     */
    public function save($cacheFlag= null) {
        if ($this->_new) {
            if (!$this->get('createdon')) $this->set('createdon', time());
            if (!$this->get('createdby') && $this->xpdo instanceof modX) $this->set('createdby', $this->xpdo->getLoginUserID());
        }
        $rt= parent :: save($cacheFlag);
        return $rt;
    }

    /**
     * Return whether or not the resource has been processed.
     *
     * @access public
     */
    public function getProcessed() {
        return $this->_processed;
    }

    /**
     * Adds a lock on the Resource
     *
     * @access public
     * @param integer $user
     * @param array $options An array of options for the lock.
     * @return boolean True if the lock was successful.
     */
    public function addLock($user = 0, array $options = array()) {
        $locked = false;
        if ($this->xpdo instanceof modX) {
            if (!$user) {
                $user = $this->xpdo->user->get('id');
            }
            $lockedBy = $this->getLock();
            if (empty($lockedBy) || ($lockedBy == $user)) {
                $this->xpdo->registry->locks->subscribe('/resource/');
                $this->xpdo->registry->locks->send('/resource/', array(md5($this->get('id')) => $user), array('ttl' => $this->xpdo->getOption('lock_ttl', $options, 360)));
                $locked = true;
            } elseif ($lockedBy != $user) {
                $locked = $lockedBy;
            }
        }
        return $locked;
    }

    /**
     * Gets the lock on the Resource.
     *
     * @access public
     * @return int
     */
    public function getLock() {
        $lock = 0;
        if ($this->xpdo instanceof modX) {
            if ($this->xpdo->getService('registry', 'registry.modRegistry')) {
                $this->xpdo->registry->addRegister('locks', 'registry.modDbRegister', array('directory' => 'locks'));
                $this->xpdo->registry->locks->connect();
                $this->xpdo->registry->locks->subscribe('/resource/' . md5($this->get('id')));
                if ($msgs = $this->xpdo->registry->locks->read(array('remove_read' => false, 'poll_limit' => 1))) {
                    $msg = reset($msgs);
                    $lock = intval($msg);
                }
            }
        }
        return $lock;
    }

    /**
     * Removes all locks on a Resource.
     *
     * @access public
     * @return boolean True if locks were removed.
     */
    public function removeLock($user = 0) {
        $removed = false;
        if ($this->xpdo instanceof modX) {
            if (!$user) {
                $user = $this->xpdo->user->get('id');
            }
            if ($this->xpdo->getService('registry', 'registry.modRegistry')) {
                $this->xpdo->registry->addRegister('locks', 'registry.modDbRegister', array('directory' => 'locks'));
                $this->xpdo->registry->locks->connect();
                $this->xpdo->registry->locks->subscribe('/resource/' . md5($this->get('id')));
                $this->xpdo->registry->locks->read(array('remove_read' => true, 'poll_limit' => 1));
                $removed = true;
            }
        }
        return $removed;
    }

    /**
     * Loads the access control policies applicable to this resource.
     *
     * {@inheritdoc}
     */
    public function findPolicy($context = '') {
        $policy = array();
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if (empty($this->_policies) || !isset($this->_policies[$context])) {
            $accessTable = $this->xpdo->getTableName('modAccessResourceGroup');
            $policyTable = $this->xpdo->getTableName('modAccessPolicy');
            $resourceGroupTable = $this->xpdo->getTableName('modResourceGroupResource');
            $sql = "SELECT acl.target, acl.principal, acl.authority, acl.policy, p.data FROM {$accessTable} acl " .
                    "LEFT JOIN {$policyTable} p ON p.id = acl.policy " .
                    "JOIN {$resourceGroupTable} rg ON acl.principal_class = 'modUserGroup' " .
                    "AND (acl.context_key = :context OR acl.context_key IS NULL OR acl.context_key = '') " .
                    "AND rg.document = :resource " .
                    "AND rg.document_group = acl.target " .
                    "GROUP BY acl.target, acl.principal, acl.authority, acl.policy";
            $bindings = array(
                ':resource' => $this->get('id'),
                ':context' => $context
            );
            $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
            if ($query->stmt && $query->stmt->execute()) {
                while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $policy['modAccessResourceGroup'][$row['target']][] = array(
                        'principal' => $row['principal'],
                        'authority' => $row['authority'],
                        'policy' => $row['data'] ? xPDO :: fromJSON($row['data'], true) : array(),
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
     * Checks to see if the Resource has children or not. Returns the number of
     * children.
     *
     * @access public
     * @return integer The number of children of the Resource
     */
    public function hasChildren() {
        $c = $this->xpdo->newQuery('modResource');
        $c->where(array(
            'parent' => $this->get('id'),
        ));
        return $this->xpdo->getCount('modResource',$c);
    }

    /**
     * Gets the value of a TV for the Resource.
     *
     * @access public
     * @param mixed $pk Either the ID of the TV, or the name of the TV.
     * @return null/mixed The value of the TV for the Resource, or null if the
     * TV is not found.
     */
    public function getTVValue($pk) {
        if (is_string($pk)) {
            $pk = array('name' => $pk);
        }
        $tv = $this->xpdo->getObject('modTemplateVar',$pk);
        return $tv == null ? null : $tv->renderOutput($this->get('id'));
    }
}