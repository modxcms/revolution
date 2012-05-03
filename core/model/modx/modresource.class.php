<?php
/**
 * @package modx
 */
/**
 * Interface for implementation on derivative Resource types. Please define the following methods in your derivative
 * class to properly implement a Custom Resource Type in MODX.
 * 
 * @see modResource
 * @interface
 * @package modx
 */
interface modResourceInterface {
    /**
     * Determine the controller path for this Resource class. Return an absolute path.
     * 
     * @static
     * @param xPDO $modx A reference to the modX object
     * @return string The absolute path to the controller for this Resource class
     */
    public static function getControllerPath(xPDO &$modx);

    /**
     * Use this in your extended Resource class to display the text for the context menu item, if showInContextMenu is
     * set to true. Return in the following format:
     *
     * array(
     *  'text_create' => 'ResourceTypeName',
     *  'text_create_here' => 'Create ResourceTypeName Here',
     * );
     *
     * @return array
     */
    public function getContextMenuText();

    /**
     * Use this in your extended Resource class to return a translatable name for the Resource Type.
     * @return string
     */
    public function getResourceTypeName();

    /**
     * Allows you to manipulate the tree node for a Resource before it is sent
     * @abstract
     * @param array $node
     */
    public function prepareTreeNode(array $node = array());
}
/**
 * Represents a web resource managed by the MODX framework.
 *
 * @property int $id The ID of the Resource
 * @property string $type The type of the resource; document/reference
 * @property string $contentType The content type string of the Resource, such as text/html
 * @property string $pagetitle The page title of the Resource
 * @property string $longtitle The long title of the Resource
 * @property string $description The description of the Resource
 * @property string $alias The FURL alias of the resource
 * @property string $link_attributes Any link attributes for the URL generated for the Resource
 * @property boolean $published Whether or not this Resource is published, or viewable by users without the 'view_unpublished' permission
 * @property int $pub_date The UNIX time that this Resource will be automatically marked as published
 * @property int $unpub_date The UNIX time that this Resource will be automatically marked as unpublished
 * @property int $parent The parent ID of the Resource
 * @property boolean $isfolder Whether or not this Resource is a container
 * @property string $introtext The intro text of this Resource, often used as an excerpt
 * @property string $content The actual content of this Resource
 * @property boolean $richtext Whether or not this Resource is edited with a Rich Text Editor, if installed
 * @property int $template The Template this Resource is tied to, or 0 to use an empty Template
 * @property int $menuindex The menuindex, or rank, that this Resource shows in.
 * @property boolean $searchable Whether or not this Resource should be searchable
 * @property boolean $cacheable Whether or not this Resource should be cacheable
 * @property int $createdby The ID of the User that created this Resource
 * @property int $createdon The UNIX time of when this Resource was created
 * @property int $editedby The ID of the User, if any, that last edited this Resource
 * @property int $editedon The UNIX time, if set, of when this Resource was last edited
 * @property boolean $deleted Whether or not this Resource is marked as deleted
 * @property int $deletedon The UNIX time of when this Resource was deleted
 * @property int $deletedby The User that deleted this Resource
 * @property int $publishedon The UNIX time that this Resource was marked as published
 * @property int $publishedby The User that published this Resource
 * @property string $menutitle The title to show when this Resource is displayed in a menu
 * @property boolean $donthit Deprecated.
 * @property boolean $privateweb Deprecated.
 * @property boolean $privatemgr Deprecated.
 * @property int $content_dispo The type of Content Disposition that is used when displaying this Resource
 * @property boolean $hidemenu Whether or not this Resource should show in menus
 * @property string $class_key The Class Key of this Resource. Useful for derivative Resource types
 * @property string $context_key The Context that this Resource resides in
 * @property int $content_type The Content Type ID of this Resource
 * @property string $uri The generated URI of this Resource
 * @property boolean $uri_override Whether or not this URI is "frozen": where the URI will stay as specified and will not be regenerated
 * @property boolean $hide_children_in_tree Whether or not this Resource should show in the mgr tree any of its children
 * @property boolean $show_in_tree Whether or not this Resource should show in the mgr tree
 * @see modTemplate
 * @see modContentType
 * @package modx
 */
class modResource extends modAccessibleSimpleObject implements modResourceInterface {
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
    /**
     * Indicates if this Resource was generated from a forward.
     * @var boolean
     */
    public $_isForward= false;
    /**
     * An array of Javascript/CSS to be appended to the footer of this Resource
     * @var array $_jscripts
     */
    public $_jscripts = array();
    /**
     * An array of Javascript/CSS to be appended to the HEAD of this Resource
     * @var array $_sjscripts
     */
    public $_sjscripts = array();
    /**
     * All loaded Javascript/CSS that has been calculated to be loaded
     * @var array
     */
    public $_loadedjscripts = array();
    /**
     * Use if extending modResource to state whether or not to show the extended class in the tree context menu
     * @var boolean
     */
    public $showInContextMenu = false;
    /**
     * Use if extending modResource to state whether or not the derivative class can be listed in the class_key
     * dropdown users can change when editing a resource.
     * @var boolean
     */
    public $allowListingInClassKeyDropdown = true;
    /**
     * Whether or not to allow creation of children resources in tree. Can be overridden in a derivative Resource class.
     * @var boolean
     */
    public $allowChildrenResources = true;
    
    /** @var modX $xpdo */
    public $xpdo;

    /**
     * Get a sortable, limitable collection (and total count) of Resource Groups for a given Resource.
     * 
     * @static
     * @param modResource &$resource A reference to the modResource to get the groups from.
     * @param array $sort An array of sort columns in column => direction format.
     * @param int $limit A limit of records to retrieve in the collection.
     * @param int $offset A record offset for a limited collection.
     * @return array An array containing the collection and total.
     */
    public static function listGroups(modResource &$resource, array $sort = array('id' => 'ASC'), $limit = 0, $offset = 0) {
        $result = array('collection' => array(), 'total' => 0);
        $c = $resource->xpdo->newQuery('modResourceGroup');
        $c->leftJoin('modResourceGroupResource', 'ResourceGroupResource', array(
            "ResourceGroupResource.document_group = modResourceGroup.id",
            'ResourceGroupResource.document' => $resource->get('id')
        ));
        $result['total'] = $resource->xpdo->getCount('modResourceGroup',$c);
        $c->select($resource->xpdo->getSelectColumns('modResourceGroup', 'modResourceGroup'));
        $c->select(array("IF(ISNULL(ResourceGroupResource.document),0,1) AS access"));
        foreach ($sort as $sortKey => $sortDir) {
            $c->sortby($resource->xpdo->escape('modResourceGroup') . '.' . $resource->xpdo->escape($sortKey), $sortDir);
        }
        if ($limit > 0) $c->limit($limit, $offset);
        $result['collection'] = $resource->xpdo->getCollection('modResourceGroup', $c);
        return $result;
    }

    /**
     * Retrieve a collection of Template Variables for a Resource.
     *
     * @static
     * @param modResource &$resource A reference to the modResource to retrieve TemplateVars for.
     * @return A collection of modTemplateVar instances for the modResource.
     */
    public static function getTemplateVarCollection(modResource &$resource) {
        $c = $resource->xpdo->newQuery('modTemplateVar');
        $c->query['distinct'] = 'DISTINCT';
        $c->select($resource->xpdo->getSelectColumns('modTemplateVar', 'modTemplateVar'));
        if ($resource->isNew()) {
            $c->select(array(
                'modTemplateVar.default_text AS value',
                '0 AS resourceId'
            ));
        } else {
            $c->select(array(
                'IF(ISNULL(tvc.value),modTemplateVar.default_text,tvc.value) AS value',
                $resource->get('id').' AS resourceId'
            ));
        }
        $c->innerJoin('modTemplateVarTemplate','tvtpl',array(
            'tvtpl.tmplvarid = modTemplateVar.id',
            'tvtpl.templateid' => $resource->get('template'),
        ));
        if (!$resource->isNew()) {
            $c->leftJoin('modTemplateVarResource','tvc',array(
                'tvc.tmplvarid = modTemplateVar.id',
                'tvc.contentid' => $resource->get('id'),
            ));
        }
        $c->sortby('tvtpl.rank,modTemplateVar.rank');
        return $resource->xpdo->getCollection('modTemplateVar', $c);
    }

    /**
     * Refresh Resource URI fields for children of the specified parent.
     *
     * @static
     * @param modX &$modx A reference to a valid modX instance.
     * @param int $parent The id of a Resource parent to start from (default is 0, the root)
     * @param array $options An array of various options for the method:
     *      - resetOverrides: if true, Resources with uri_override set to true will be included
     *      - contexts: an optional array of context keys to limit the refresh scope
     * @return void
     */
    public static function refreshURIs(modX &$modx, $parent = 0, array $options = array()) {
        $resetOverrides = array_key_exists('resetOverrides', $options) ? (boolean) $options['resetOverrides'] : false;
        $contexts = array_key_exists('contexts', $options) ? explode(',', $options['contexts']) : null;
        $criteria = $modx->newQuery('modResource', array('parent' => $parent));
        if (!$resetOverrides) {
            $criteria->where(array('uri_override' => false));
        }
        if (!empty($contexts)) {
            $criteria->where(array('context_key:IN' => $contexts));
        }
        $criteria->sortby('menuindex', 'ASC');
        /** @var modResource $resource */
        foreach ($modx->getIterator('modResource', $criteria) as $resource) {
            $resource->set('refreshURIs', true);
            if ($resetOverrides) {
                $resource->set('uri_override', false);
            }
            if (!$resource->get('uri_override')) {
                $resource->set('uri', '');
            }
            $resource->save();
        }
    }

    /**
     * Updates the Context of all Children recursively to that of the parent.
     *
     * @static
     * @param modX &$modx A reference to an initialized modX instance.
     * @param modResource $parent The parent modResource instance.
     * @param array $options An array of options.
     * @return int The number of children updated.
     */
    public static function updateContextOfChildren(modX &$modx, $parent, array $options = array()) {
        $count = 0;
        /** @var modResource $child */
        foreach ($parent->getIterator('Children') as $child) {
            $child->set('context_key', $parent->get('context_key'));
            if ($child->save()) {
                $count++;
            } else {
                $modx->log(modX::LOG_LEVEL_ERROR, "Could not change Context of child resource {$child->get('id')}", '', __METHOD__, __FILE__, __LINE__);
            }
        }
        return $count;
    }

    /**
     * @param xPDO $xpdo A reference to the xPDO|modX instance
     */
    function __construct(xPDO & $xpdo) {
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
            /** @var modTemplate $baseElement */
            if ($baseElement= $this->getOne('Template')) {
                if ($baseElement->process()) {
                    $this->_content= $baseElement->_output;
                    $this->_processed= true;
                }
            } else {
                $this->_content= $this->getContent();
                $maxIterations= intval($this->xpdo->getOption('parser_max_iterations',10));
                $this->xpdo->parser->processElementTags('', $this->_content, false, false, '[[', ']]', array(), $maxIterations);
                $this->_processed= true;
            }
        }
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
            $collection= $this->getTemplateVars();
        } else {
            $collection= parent :: getMany($alias, $criteria, $cacheFlag);
        }
        return $collection;
    }

    /**
     * Get a collection of the Template Variable values for the Resource.
     *
     * @return array A collection of TemplateVar values for this Resource.
     */
    public function getTemplateVars() {
        return $this->xpdo->call('modResource', 'getTemplateVarCollection', array(&$this));
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
                    /** @var modContentType $contentType */
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
     *
     * @param xPDOObject $obj
     * @param string $alias
     * @return boolean
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
     * Transforms a string to form a valid URL representation.
     *
     * @param string $alias A string to transform into a valid URL representation.
     * @param array $options Options to append to or override configuration settings.
     * @return string The transformed string.
     */
    public function cleanAlias($alias, array $options = array()) {
        /* setup the various options */
        $iconv = function_exists('iconv');
        $mbext = function_exists('mb_strlen') && (boolean) $this->xpdo->getOption('use_multibyte', false);
        $charset = strtoupper((string) $this->xpdo->getOption('modx_charset', $options, 'UTF-8'));
        $delimiter = $this->xpdo->getOption('friendly_alias_word_delimiter', $options, '-');
        $delimiters = $this->xpdo->getOption('friendly_alias_word_delimiters', $options, '-_');
        $maxlength = (integer) $this->xpdo->getOption('friendly_alias_max_length', $options, 0);
        $stripElementTags = (boolean) $this->xpdo->getOption('friendly_alias_strip_element_tags', $options, true);
        $trimchars = $this->xpdo->getOption('friendly_alias_trim_chars', $options, '/.' . $delimiters);
        $restrictchars = $this->xpdo->getOption('friendly_alias_restrict_chars', $options, 'pattern');
        $restrictcharspattern = $this->xpdo->getOption('friendly_alias_restrict_chars_pattern', $options, '/[\0\x0B\t\n\r\f\a&=+%#<>"~`@\?\[\]\{\}\|\^\'\\\\]/');
        $lowercase = (boolean) $this->xpdo->getOption('friendly_alias_lowercase_only', $options, true);
        $translit = $this->xpdo->getOption('friendly_alias_translit', $options, $iconv ? 'iconv' : 'none');
        $translitClass = $this->xpdo->getOption('friendly_alias_translit_class', $options, 'translit.modTransliterate');

        /* strip html and optionally MODX element tags (stripped by default) */
        if ($this->xpdo instanceof modX) {
            $alias = $this->xpdo->stripTags($alias, '', $stripElementTags ? array() : null);
        }

        /* replace &nbsp; with the specified word delimiter */
        $alias = str_replace('&nbsp;', $delimiter, $alias);

        /* decode named entities to the appropriate character for the character set */
        $alias = html_entity_decode($alias, ENT_QUOTES, $charset);

        /* replace any remaining & with a lexicon value if available */
        if ($this->xpdo instanceof modX && $this->xpdo->getService('lexicon','modLexicon')) {
            $alias = str_replace('&', $this->xpdo->lexicon('and') ? ' ' . $this->xpdo->lexicon('and') . ' ' : ' and ', $alias);
        }

        /* apply transliteration as configured */
        switch ($translit) {
            case '':
            case 'none':
                /* no transliteration */
                break;
            case 'iconv':
                /* if iconv is available, use the built-in transliteration it provides */
                $alias = iconv($mbext ? mb_detect_encoding($alias) : $charset, $charset . '//TRANSLIT//IGNORE', $alias);
                break;
            default:
                /* otherwise look for a transliteration service class that will accept named transliteration tables */
                if ($this->xpdo instanceof modX) {
                    $translitClassPath = $this->xpdo->getOption('friendly_alias_translit_class_path', $options, $this->xpdo->getOption('core_path', $options, MODX_CORE_PATH) . 'components/');
                    if ($this->xpdo->getService('translit', $translitClass, $translitClassPath, $options)) {
                        $alias = $this->xpdo->translit->translate($alias, $translit);
                    }
                }
                break;
        }

        /* restrict characters as configured */
        switch ($restrictchars) {
            case 'alphanumeric':
                /* restrict alias to alphanumeric characters only */
                $alias = preg_replace('/[^\.%A-Za-z0-9 _-]/', '', $alias);
                break;
            case 'alpha':
                /* restrict alias to alpha characters only */
                $alias = preg_replace('/[^\.%A-Za-z _-]/', '', $alias);
                break;
            case 'legal':
                /* restrict alias to legal URL characters only */
                $alias = preg_replace('/[\0\x0B\t\n\r\f\a&=+%#<>"~`@\?\[\]\{\}\|\^\'\\\\]/', '', $alias);
                break;
            case 'pattern':
            default:
                /* restrict alias using regular expression pattern configured (same as legal by default) */
                if (!empty($restrictcharspattern)) {
                    $alias = preg_replace($restrictcharspattern, '', $alias);
                }
        }

        /* replace one or more space characters with word delimiter */
        $alias = preg_replace('/\s+/u', $delimiter, $alias);

        /* replace one or more instances of word delimiters with word delimiter */
        $delimiterTokens = array();
        for ($d = 0; $d < strlen($delimiters); $d++) {
            $delimiterTokens[] = $delimiters{$d};
        }
        $delimiterPattern = '/[' . implode('|', $delimiterTokens) . ']+/';
        $alias = preg_replace($delimiterPattern, $delimiter, $alias);

        /* unless lowercase_only preference is explicitly off, change case to lowercase */
        if ($lowercase) {
            if ($mbext) {
                /* if the mb extension is available use it to protect multi-byte chars */
                $alias = mb_convert_case($alias, MB_CASE_LOWER, $charset);
            } else {
                /* otherwise, just use strtolower */
                $alias = strtolower($alias);
            }
        }
        /* trim specified chars from both ends of the alias */
        $alias = trim($alias, $trimchars);

        /* get the strlen of the alias (use mb extension if available) */
        $length = $mbext ? mb_strlen($alias, $charset) : strlen($alias);

        /* if maxlength is specified and exceeded, return substr with additional trim applied */
        if ($maxlength > 0 && $length > $maxlength) {
            $alias = substr($alias, 0, $maxlength);
            $alias = trim($alias, $trimchars);
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "alias after maxlength applied = {$alias}");
        }

        return $alias;
    }

    /**
     * Persist new or changed modResource instances to the database container.
     *
     * If the modResource is new, the createdon and createdby fields will be set
     * using the current time and user authenticated in the context.
     *
     * If uri is empty or uri_overridden is not set and something has been changed which
     * might affect the Resource's uri, it is (re-)calculated using getAliasPath(). This
     * can be forced recursively by setting refreshURIs to true before calling save().
     *
     * @param boolean $cacheFlag
     * @return boolean
     */
    public function save($cacheFlag= null) {
        if ($this->isNew()) {
            if (!$this->get('createdon')) $this->set('createdon', time());
            if (!$this->get('createdby') && $this->xpdo instanceof modX) $this->set('createdby', $this->xpdo->getLoginUserID());
        }
        $refreshChildURIs = false;
        if ($this->xpdo instanceof modX && $this->xpdo->getOption('friendly_urls')) {
            $refreshChildURIs = ($this->get('refreshURIs') || $this->isDirty('alias') || $this->isDirty('parent') || $this->isDirty('context_key'));
            if ($this->get('uri') == '' || (!$this->get('uri_override') && ($this->isDirty('uri_override') || $this->isDirty('content_type') || $this->isDirty('isfolder') || $refreshChildURIs))) {
                $this->set('uri', $this->getAliasPath($this->get('alias')));
            }
        }
        $changeContext = false;
        if ($this->xpdo instanceof modX) {
            $changeContext = $this->isDirty('context_key');
        }
        $rt= parent :: save($cacheFlag);
        if ($rt && $refreshChildURIs) {
            $this->xpdo->call('modResource', 'refreshURIs', array(
                &$this->xpdo,
                $this->get('id'),
            ));
        }
        if ($rt && $changeContext) {
            $this->xpdo->call($this->_class, 'updateContextOfChildren', array(&$this->xpdo, $this));
        }
        return $rt;
    }

    /**
     * Return whether or not the resource has been processed.
     *
     * @access public
     * @return boolean
     */
    public function getProcessed() {
        return $this->_processed;
    }

    /**
     * Set the field indicating the resource has been processed.
     *
     * @param boolean $processed Pass true to indicate the Resource has been processed.
     */
    public function setProcessed($processed) {
        $this->_processed= (boolean) $processed;
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
     * @param int $user
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
        $enabled = true;
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if ($context === $this->xpdo->context->get('key')) {
            $enabled = (boolean) $this->xpdo->getOption('access_resource_group_enabled', null, true);
        } elseif ($this->xpdo->getContext($context)) {
            $enabled = (boolean) $this->xpdo->contexts[$context]->getOption('access_resource_group_enabled', true);
        }
        if ($enabled) {
            if (empty($this->_policies) || !isset($this->_policies[$context])) {
                $accessTable = $this->xpdo->getTableName('modAccessResourceGroup');
                $policyTable = $this->xpdo->getTableName('modAccessPolicy');
                $resourceGroupTable = $this->xpdo->getTableName('modResourceGroupResource');
                $sql = "SELECT Acl.target, Acl.principal, Acl.authority, Acl.policy, Policy.data FROM {$accessTable} Acl " .
                        "LEFT JOIN {$policyTable} Policy ON Policy.id = Acl.policy " .
                        "JOIN {$resourceGroupTable} ResourceGroup ON Acl.principal_class = 'modUserGroup' " .
                        "AND (Acl.context_key = :context OR Acl.context_key IS NULL OR Acl.context_key = '') " .
                        "AND ResourceGroup.document = :resource " .
                        "AND ResourceGroup.document_group = Acl.target " .
                        "GROUP BY Acl.target, Acl.principal, Acl.authority, Acl.policy";
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
                            'policy' => $row['data'] ? $this->xpdo->fromJSON($row['data'], true) : array(),
                        );
                    }
                }
                $this->_policies[$context] = $policy;
            } else {
                $policy = $this->_policies[$context];
            }
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
        $byName = false;
        if (is_string($pk)) {
            $byName = true;
        }
        /** @var modTemplateVar $tv */
        if ($byName && $this->xpdo instanceof modX) {
            $tv = $this->xpdo->getParser()->getElement('modTemplateVar', $pk);
        } else {
            $tv = $this->xpdo->getObject('modTemplateVar', $byName ? array('name' => $pk) : $pk);
        }
        return $tv == null ? null : $tv->renderOutput($this->get('id'));
    }

    /**
     * Sets a value for a TV for this Resource
     * 
     * @param mixed $pk The TV name or ID to set
     * @param string $value The value to set for the TV
     * @return bool Whether or not the TV saved successfully
     */
    public function setTVValue($pk,$value) {
        $success = false;
        if (is_string($pk)) {
            $pk = array('name' => $pk);
        }
        /** @var modTemplateVar $tv */
        $tv = $this->xpdo->getObject('modTemplateVar',$pk);
        if ($tv) {
            $tv->setValue($this->get('id'),$value);
            $success = $tv->save();
        }
        return $success;
    }

    /**
     * Get the Resource's full alias path.
     *
     * @param string $alias Optional. The alias to check. If not set, will
     * then build it from the pagetitle if automatic_alias is set to true.
     * @param array $fields Optional. An array of field values to use instead of
     * using the current modResource fields.
     * @return string
     */
    public function getAliasPath($alias = '',array $fields = array()) {
        if (empty($fields)) $fields = $this->toArray();
        $workingContext = $this->xpdo->getContext($fields['context_key']);
        if (empty($fields['uri_override']) || empty($fields['uri'])) {
            /* auto assign alias if using automatic_alias */
            if (empty($alias) && $workingContext->getOption('automatic_alias', false)) {
                $alias = $this->cleanAlias($fields['pagetitle']);
            } elseif (empty($alias) && isset($fields['id']) && !empty($fields['id'])) {
                $alias = $this->cleanAlias($fields['id']);
            } else {
                $alias = $this->cleanAlias($alias);
            }

            $fullAlias= $alias;
            $isHtml= true;
            $extension= '';
            $containerSuffix= $workingContext->getOption('container_suffix', '');
            /* @var modContentType $contentType process content type */
            if (!empty($fields['content_type']) && $contentType= $this->xpdo->getObject('modContentType', $fields['content_type'])) {
                $extension= $contentType->getExtension();
                $isHtml= (strpos($contentType->get('mime_type'), 'html') !== false);
            }
            /* set extension to container suffix if Resource is a folder, HTML content type, and the container suffix is set */
            if (!empty($fields['isfolder']) && $isHtml && !empty ($containerSuffix)) {
                $extension= $containerSuffix;
            }
            $aliasPath= '';
            /* if using full alias paths, calculate here */
            if ($workingContext->getOption('use_alias_path', false)) {
                $pathParentId= $fields['parent'];
                $parentResources= array ();
                $query = $this->xpdo->newQuery('modResource');
                $query->select($this->xpdo->getSelectColumns('modResource', '', '', array('parent', 'alias')));
                $query->where("{$this->xpdo->escape('id')} = ?");
                $query->prepare();
                $query->stmt->execute(array($pathParentId));
                $currResource= $query->stmt->fetch(PDO::FETCH_ASSOC);
                while ($currResource) {
                    $parentAlias= $currResource['alias'];
                    if (empty ($parentAlias)) {
                        $parentAlias= "{$pathParentId}";
                    }
                    $parentResources[]= "{$parentAlias}";
                    $pathParentId= $currResource['parent'];
                    $query->stmt->execute(array($pathParentId));
                    $currResource= $query->stmt->fetch(PDO::FETCH_ASSOC);
                }
                $aliasPath= !empty ($parentResources) ? implode('/', array_reverse($parentResources)) : '';
                if (strlen($aliasPath) > 0 && $aliasPath[strlen($aliasPath) - 1] !== '/') $aliasPath .= '/';
            }
            $fullAlias= $aliasPath . $fullAlias . $extension;
        } else {
            $fullAlias= $fields['uri'];
        }
        return $fullAlias;
    }

    /**
     * Tests to see if an alias is a duplicate.
     *
     * @param string $aliasPath The current full alias path. If none is passed,
     * will build it from the Resource's currently set alias.
     * @param string $contextKey The context to search for a duplicate alias in.
     * @return mixed The ID of the Resource using the alias, if a duplicate, otherwise false.
     */
    public function isDuplicateAlias($aliasPath = '', $contextKey = '') {
        if (empty($aliasPath)) $aliasPath = $this->getAliasPath($this->get('alias'));
        $criteria = $this->xpdo->newQuery('modResource');
        $where = array(
            'id:!=' => $this->get('id'),
            'uri' => $aliasPath,
            'deleted' => false,
            'published' => true
        );
        if (!empty($contextKey)) {
            $where['context_key'] = $contextKey;
        }
        $criteria->select('id');
        $criteria->where($where);
        $criteria->prepare();
        $duplicate = $this->xpdo->getValue($criteria->stmt);
        return $duplicate > 0 ? (integer) $duplicate : false;
    }

    /**
     * Duplicate the Resource.
     *
     * @param array $options An array of options.
     * @return mixed Returns either an error message, or the newly created modResource object.
     */
    public function duplicate(array $options = array()) {
        if (!($this->xpdo instanceof modX)) return false;

        /* duplicate resource */
        $prefixDuplicate = !empty($options['prefixDuplicate']) ? true : false;
        $newName = !empty($options['newName']) ? $options['newName'] : ($prefixDuplicate ? $this->xpdo->lexicon('duplicate_of', array(
            'name' => $this->get('pagetitle'),
        )) : $this->get('pagetitle'));
        /** @var modResource $newResource */
        $newResource = $this->xpdo->newObject($this->get('class_key'));
        $newResource->fromArray($this->toArray('', true), '', false, true);
        $newResource->set('pagetitle', $newName);

        /* do published status preserving */
        $publishedMode = $this->getOption('publishedMode',$options,'preserve');
        switch ($publishedMode) {
            case 'unpublish':
                $newResource->set('published',false);
                $newResource->set('publishedon',0);
                $newResource->set('publishedby',0);
                break;
            case 'publish':
                $newResource->set('published',true);
                $newResource->set('publishedon',time());
                $newResource->set('publishedby',$this->xpdo->user->get('id'));
                break;
            case 'preserve':
            default:
                $newResource->set('published',$this->get('published'));
                $newResource->set('publishedon',$this->get('publishedon'));
                $newResource->set('publishedby',$this->get('publishedby'));
                break;
        }

        /* allow overrides for every item */
        if (!empty($options['overrides']) && is_array($options['overrides'])) {
            $newResource->fromArray($options['overrides']);
        }
        $newResource->set('id',0);

        /* make sure children get assigned to new parent */
        $newResource->set('parent',isset($options['parent']) ? $options['parent'] : $this->get('parent'));
        $newResource->set('createdby',$this->xpdo->user->get('id'));
        $newResource->set('createdon',time());
        $newResource->set('editedby',0);
        $newResource->set('editedon',0);

        /* get new alias */
        $alias = $newResource->cleanAlias($newName);
        if ($this->xpdo->getOption('friendly_urls', $options, false)) {
            /* auto assign alias */
            $aliasPath = $newResource->getAliasPath($newName);
            $dupeContext = $this->xpdo->getOption('global_duplicate_uri_check', $options, false) ? '' : $newResource->get('context_key');
            if ($newResource->isDuplicateAlias($aliasPath, $dupeContext)) {
                $alias = '';
                if ($newResource->get('uri_override')) {
                    $newResource->set('uri_override', false);
                }
            }
        }
        $newResource->set('alias',$alias);

        /* set new menuindex */
        $childrenCount = $this->xpdo->getCount('modResource',array('parent' => $this->get('parent')));
        $newResource->set('menuindex',$childrenCount);

        /* save resource */
        if (!$newResource->save()) {
            return $this->xpdo->lexicon('resource_err_duplicate');
        }

        $tvds = $this->getMany('TemplateVarResources');
        /** @var modTemplateVarResource $oldTemplateVarResource */
        foreach ($tvds as $oldTemplateVarResource) {
            /** @var modTemplateVarResource $newTemplateVarResource */
            $newTemplateVarResource = $this->xpdo->newObject('modTemplateVarResource');
            $newTemplateVarResource->set('contentid',$newResource->get('id'));
            $newTemplateVarResource->set('tmplvarid',$oldTemplateVarResource->get('tmplvarid'));
            $newTemplateVarResource->set('value',$oldTemplateVarResource->get('value'));
            $newTemplateVarResource->save();
        }

        $groups = $this->getMany('ResourceGroupResources');
        /** @var modResourceGroupResource $oldResourceGroupResource */
        foreach ($groups as $oldResourceGroupResource) {
            /** @var modResourceGroupResource $newResourceGroupResource */
            $newResourceGroupResource = $this->xpdo->newObject('modResourceGroupResource');
            $newResourceGroupResource->set('document_group',$oldResourceGroupResource->get('document_group'));
            $newResourceGroupResource->set('document',$newResource->get('id'));
            $newResourceGroupResource->save();
        }

        /* duplicate resource, recursively */
        $duplicateChildren = isset($options['duplicateChildren']) ? $options['duplicateChildren'] : true;
        if ($duplicateChildren) {
            if (!$this->checkPolicy('add_children')) return $newResource;
            
            $children = $this->getMany('Children');
            if (is_array($children) && count($children) > 0) {
                /** @var modResource $child */
                foreach ($children as $child) {
                    $child->duplicate(array(
                        'duplicateChildren' => true,
                        'parent' => $newResource->get('id'),
                        'prefixDuplicate' => $prefixDuplicate,
                        'overrides' => !empty($options['overrides']) ? $options['overrides'] : false,
                        'publishedMode' => $publishedMode,
                    ));
                }
            }
        }
        return $newResource;
    }

    /**
     * Joins a Resource to a Resource Group
     *
     * @access public
     * @param mixed $resourceGroupPk Either the ID, name or object of the Resource Group
     * @return boolean True if successful.
     */
    public function joinGroup($resourceGroupPk) {
        if (!is_object($resourceGroupPk) && !($resourceGroupPk instanceof modResourceGroup)) {
            $c = array(
                is_int($resourceGroupPk) ? 'id' : 'name' => $resourceGroupPk,
            );
            /** @var modResourceGroup $resourceGroup */
            $resourceGroup = $this->xpdo->getObject('modResourceGroup',$c);
            if (empty($resourceGroup) || !is_object($resourceGroup) || !($resourceGroup instanceof modResourceGroup)) {
                $this->xpdo->log(modX::LOG_LEVEL_ERROR,'modResource::joinGroup - No resource group: '.$resourceGroupPk);
                return false;
            }
        } else {
            $resourceGroup =& $resourceGroupPk;
        }
        $resourceGroupResource = $this->xpdo->getObject('modResourceGroupResource',array(
            'document' => $this->get('id'),
            'document_group' => $resourceGroup->get('id'),
        ));
        if ($resourceGroupResource) {
            $this->xpdo->log(modX::LOG_LEVEL_ERROR,'modResource::joinGroup - Resource '.$this->get('id').' already in resource group: '.$resourceGroupPk);
            return false;
        }
        /** @var modResourceGroupResource $resourceGroupResource */
        $resourceGroupResource = $this->xpdo->newObject('modResourceGroupResource');
        $resourceGroupResource->set('document',$this->get('id'));
        $resourceGroupResource->set('document_group',$resourceGroup->get('id'));
        return $resourceGroupResource->save();
    }

    /**
     * Removes a Resource from a Resource Group
     *
     * @access public
     * @param int|string|modResourceGroup $resourceGroupPk Either the ID, name or object of the Resource Group
     * @return boolean True if successful.
     */
    public function leaveGroup($resourceGroupPk) {
        if (!is_object($resourceGroupPk) && !($resourceGroupPk instanceof modResourceGroup)) {
            $c = array(
                is_int($resourceGroupPk) ? 'id' : 'name' => $resourceGroupPk,
            );
            /** @var modResourceGroup $resourceGroup */
            $resourceGroup = $this->xpdo->getObject('modResourceGroup',$c);
            if (empty($resourceGroup) || !is_object($resourceGroup) || !($resourceGroup instanceof modResourceGroup)) {
                $this->xpdo->log(modX::LOG_LEVEL_ERROR,'modResource::leaveGroup - No resource group: '.(is_object($resourceGroupPk) ? $resourceGroupPk->get('name') : $resourceGroupPk));
                return false;
            }
        } else {
            $resourceGroup =& $resourceGroupPk;
        }
        /** @var modResourceGroupResource $resourceGroupResource */
        $resourceGroupResource = $this->xpdo->getObject('modResourceGroupResource',array(
            'document' => $this->get('id'),
            'document_group' => $resourceGroup->get('id'),
        ));
        if (!$resourceGroupResource) {
            $this->xpdo->log(modX::LOG_LEVEL_ERROR,'modResource::leaveGroup - Resource '.$this->get('id').' is not in resource group: '.(is_object($resourceGroupPk) ? $resourceGroupPk->get('name') : $resourceGroupPk));
            return false;
        }
        return $resourceGroupResource->remove();
    }

    /**
     * Gets a sortable, limitable collection (and total count) of Resource Groups for the Resource.
     *
     * @param array $sort An array of sort columns in column => direction format.
     * @param int $limit A limit of records to retrieve in the collection.
     * @param int $offset A record offset for a limited collection.
     * @return array An array containing the collection and total.
     */
    public function getGroupsList(array $sort = array('id' => 'ASC'), $limit = 0, $offset = 0) {
        return $this->xpdo->call('modResource', 'listGroups', array(&$this, $sort, $limit, $offset));
    }

    /**
     * Determine the controller path for this Resource class
     * @static
     * @param xPDO $modx A reference to the modX object
     * @return string The absolute path to the controller for this Resource class
     */
    public static function getControllerPath(xPDO &$modx) {
        $theme = $modx->getOption('manager_theme',null,'default');
        $controllersPath = $modx->getOption('manager_path',null,MODX_MANAGER_PATH).'controllers/'.$theme.'/';
        return $controllersPath.'resource/';
    }

    /**
     * Use this in your extended Resource class to display the text for the context menu item, if showInContextMenu is
     * set to true.
     * @return array
     */
    public function getContextMenuText() {
        return array(
            'text_create' => $this->xpdo->lexicon('resource'),
            'text_create_here' => $this->xpdo->lexicon('resource_create_here'),
        );
    }

    /**
     * Use this in your extended Resource class to return a translatable name for the Resource Type.
     * @return string
     */
    public function getResourceTypeName() {
        $className = $this->_class;
        if ($className == 'modDocument') $className = 'document';
        return $this->xpdo->lexicon($className);
    }

    /**
     * Use this in your extended Resource class to modify the tree node contents
     * @param array $node
     * @return array
     */
    public function prepareTreeNode(array $node = array()) {
        return $node;
    }

    /**
     * Get a namespaced property for the Resource
     * @param string $key
     * @param string $namespace
     * @param null $default
     * @return null
     */
    public function getProperty($key,$namespace = 'core',$default = null) {
        $properties = $this->get('properties');
        $properties = !empty($properties) ? $properties : array();
        return array_key_exists($namespace,$properties) && array_key_exists($key,$properties[$namespace]) ? $properties[$namespace][$key] : $default;
    }
    /**
     * Get the properties for the specific namespace for the Resource
     * @param string $namespace
     * @return array
     */
    public function getProperties($namespace = 'core') {
        $properties = $this->get('properties');
        $properties = !empty($properties) ? $properties : array();
        return array_key_exists($namespace,$properties) ? $properties[$namespace] : array();
    }

    /**
     * Set a namespaced property for the Resource
     * @param string $key
     * @param mixed $value
     * @param string $namespace
     * @return bool
     */
    public function setProperty($key,$value,$namespace = 'core') {
        $properties = $this->get('properties');
        $properties = !empty($properties) ? $properties : array();
        if (!array_key_exists($namespace,$properties)) $properties[$namespace] = array();
        $properties[$namespace][$key] = $value;
        return $this->set('properties',$properties);
    }

    /**
     * Set properties for a namespace on the Resource, optionally merging them with existing ones.
     * @param array $newProperties
     * @param string $namespace
     * @param bool $merge
     * @return boolean
     */
    public function setProperties(array $newProperties,$namespace = 'core',$merge = true) {
        $properties = $this->get('properties');
        $properties = !empty($properties) ? $properties : array();
        if (!array_key_exists($namespace,$properties)) $properties[$namespace] = array();
        $properties[$namespace] = $merge ? array_merge($properties[$namespace],$newProperties) : $newProperties;
        return $this->set('properties',$properties);
    }
}
