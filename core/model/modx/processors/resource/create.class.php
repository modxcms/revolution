<?php
/**
 * Creates a resource.
 *
 * @param string $pagetitle The page title.
 * @param string $content The HTML content. Used in conjunction with $ta.
 * @param integer $template (optional) The modTemplate to use with this
 * resource. Defaults to 0, or a blank template.
 * @param integer $parent (optional) The parent resource ID. Defaults to 0.
 * @param string $class_key (optional) The class key. Defaults to modDocument.
 * @param integer $menuindex (optional) The menu order. Defaults to 0.
 * @param string $variablesmodified (optional) A collection of modified TVs.
 * Along with $tv1, $tv2, etc.
 * @param string $context_key (optional) The context in which this resource is
 * located. Defaults to web.
 * @param string $alias (optional) The alias for FURLs that this resource is
 * designated to.
 * @param integer $content_type (optional) The content type. Defaults to
 * text/html.
 * @param boolean $published (optional) The published status.
 * @param string $pub_date (optional) The date on which this resource should
 * become published.
 * @param string $unpub_date (optional) The date on which this resource should
 * become unpublished.
 * @param string $publishedon (optional) The date this resource was published.
 * Defaults to time()
 * @param integer $publishedby (optional) The modUser who published this
 * resource. Defaults to the current user.
 * @param json $resource_groups (optional) A JSON array of resource groups to
 * assign this resource to.
 * @param boolean $hidemenu (optional) If true, The resource will not show up in
 * menu builders.
 * @param boolean $isfolder (optional) Whether or not the resource is a
 * container of resources.
 * @param boolean $richtext (optional) If true, MODX will render the available
 * RTE for editing this resource.
 * @param boolean $donthit (optional) (deprecated) If true, MODX will not log
 * visits on this resource.
 * @param boolean $cacheable (optional) If false, the resource will not be
 * cached.
 * @param boolean $searchable (optional) If false, the resource will not appear
 * in searches.
 * @param boolean $syncsite (optional) If false, will not empty the cache on
 * save.
 *
 * @package modx
 * @subpackage processors.resource
 */
class modResourceCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'modResource';
    public $languageTopics = array('resource');
    public $permission = 'new_document';
    public $objectType = 'resource';
    public $beforeSaveEvent = 'OnBeforeDocFormSave';
    public $afterSaveEvent = 'OnDocFormSave';

    /** @var modResource $parentResource */
    public $parentResource;
    /** @var modContext $this->workingContext */
    public $workingContext;
    /** @var modTemplate $template */
    public $template;
    /** @var modResource $object */
    public $object;

    /**
     * Allow for Resources to use derivative classes for their processors
     *
     * @static
     * @param modX $modx
     * @param $className
     * @param array $properties
     * @return modProcessor
     */
    public static function getInstance(modX &$modx,$className,$properties = array()) {
        $classKey = !empty($properties['class_key']) ? $properties['class_key'] : 'modDocument';
        $object = $modx->newObject($classKey);

        if (!in_array($classKey,array('modDocument','modResource',''))) {
            $className = $classKey.'CreateProcessor';
            if (!class_exists($className)) {
                $className = 'modResourceCreateProcessor';
            }
        }
        /** @var modProcessor $processor */
        $processor = new $className($modx,$properties);
        return $processor;
    }

    /**
     * Create the modResource object for manipulation
     * @return string|modResource
     */
    public function initialize() {
        /* get the class_key to determine classKey and resourceDir */
        $classKey = $this->getProperty('class_key','modDocument');
        $this->classKey = !empty($classKey) ? $classKey : 'modDocument';
        $initialized = parent::initialize();
        if (!$initialized) return $this->modx->lexicon('resource_err_create');
        if (!$this->object instanceof $this->classKey) return $this->modx->lexicon('resource_err_class',array('class' => $this->classKey));

        return $initialized;
    }

    /**
     * Process the form and create the Resource
     *
     * {@inheritDoc}
     *
     * @return array|string
     */
    public function beforeSet() {
        /* default settings */
        $this->prepareParent();
        if ($this->getProperty('parent') === (int) $this->modx->getOption('tree_root_id') && !$this->modx->hasPermission('new_document_in_root')) {
            return $this->modx->lexicon('permission_denied');
        }
        $set = $this->checkParentPermissions();
        if ($set !== true) return $set;

        $this->getWorkingContext();
        if (!$this->workingContext) {
            return $this->modx->lexicon('access_denied');
        }

        $set = $this->setFieldDefaults();
        if ($set !== true) return $set;

        $this->preparePageTitle();
        $this->prepareAlias();
        $this->handleResourceProperties();

        $this->object->set('template',$this->getProperty('template',0));
        $templateVariables = $this->addTemplateVariables();
        if (!empty($templateVariables)) {
            $this->object->addMany($templateVariables);
        }
        return parent::beforeSet();
    }

    /**
     * Handle any properties-specific fields
     */
    public function handleResourceProperties() {
        if ($this->object->get('class_key') == 'modWebLink') {
            $responseCode = $this->getProperty('responseCode');
            if (!empty($responseCode)) {
                $this->object->setProperty('responseCode',$responseCode);
            }
        }
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function beforeSave() {
        if (!$this->object->get('class_key')) {
            $this->object->set('class_key',$this->classKey);
        }
        $this->setMenuIndex();

        $reloaded = (boolean)$this->getProperty('reloaded',false);
        if ($reloaded && !$this->hasErrors() && !$this->checkForAllowableCreateToken()) {
            return $this->modx->lexicon('resource_err_save');
        }
        return parent::beforeSave();
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function afterSave() {
        $this->object->addLock();
        $this->setParentToContainer();
        $this->saveResourceGroups();
        $this->checkIfSiteStart();
        return parent::afterSave();
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function cleanup() {
        $this->object->removeLock();
        $this->clearCache();
        return $this->success('', array('id' => $this->object->get('id')));
    }

    /**
     * Set defaults for the fields if values are not passed
     * @return mixed
     */
    public function setFieldDefaults() {
        $scriptProperties = $this->getProperties();
        $scriptProperties['template'] = !isset($scriptProperties['template']) ? (integer) $this->workingContext->getOption('default_template', 0) : (integer) $scriptProperties['template'];
        $scriptProperties['hidemenu'] = !isset($scriptProperties['hidemenu']) ? (integer) $this->workingContext->getOption('hidemenu_default', 0) : (empty($scriptProperties['hidemenu']) ? 0 : 1);
        $scriptProperties['isfolder'] = empty($scriptProperties['isfolder']) ? 0 : 1;
        $scriptProperties['richtext'] = !isset($scriptProperties['richtext']) ? (integer) $this->workingContext->getOption('richtext_default', 1) : (empty($scriptProperties['richtext']) ? 0 : 1);
        $scriptProperties['donthit'] = empty($scriptProperties['donthit']) ? 0 : 1;
        $scriptProperties['published'] = !isset($scriptProperties['published']) ? (integer) $this->workingContext->getOption('publish_default', 0) : (empty($scriptProperties['published']) ? 0 : 1);
        $scriptProperties['cacheable'] = !isset($scriptProperties['cacheable']) ? (integer) $this->workingContext->getOption('cache_default', 1) : (empty($scriptProperties['cacheable']) ? 0 : 1);
        $scriptProperties['searchable'] = !isset($scriptProperties['searchable']) ? (integer) $this->workingContext->getOption('search_default', 1) : (empty($scriptProperties['searchable']) ? 0 : 1);
        $scriptProperties['content_type'] = !isset($scriptProperties['content_type']) ? (integer) $this->workingContext->getOption('default_content_type',1) : (integer)$scriptProperties['content_type'];
        $scriptProperties['syncsite'] = empty($scriptProperties['syncsite']) ? 0 : 1;
        $scriptProperties['menuindex'] = empty($scriptProperties['menuindex']) ? 0 : $scriptProperties['menuindex'];
        $scriptProperties['deleted'] = empty($scriptProperties['deleted']) ? 0 : 1;
        $scriptProperties['uri_override'] = empty($scriptProperties['uri_override']) ? 0 : 1;

        if(empty($scriptProperties['createdon'])){
            $scriptProperties['createdon'] = strftime('%Y-%m-%d %H:%M:%S');
        }

        if(empty($scriptProperties['createdby'])){
            $scriptProperties['createdby'] = $this->modx->user->get('id');
        }

        /* publish and unpublish dates */
        $now = time();
        if (isset($scriptProperties['pub_date'])) {
            if (empty($scriptProperties['pub_date'])) {
                $scriptProperties['pub_date'] = 0;
            } else {
                $scriptProperties['pub_date'] = strtotime($scriptProperties['pub_date']);
                if ($scriptProperties['pub_date'] < $now) $scriptProperties['published'] = 1;
                if ($scriptProperties['pub_date'] > $now) $scriptProperties['published'] = 0;
            }
        }

        if (isset($scriptProperties['unpub_date'])) {
            if (empty($scriptProperties['unpub_date'])) {
                $scriptProperties['unpub_date'] = 0;
            } else {
                $scriptProperties['unpub_date'] = strtotime($scriptProperties['unpub_date']);
                if ($scriptProperties['unpub_date'] < $now) $scriptProperties['published'] = 0;
            }
        }

        /* modDocument content is posted as ta */
        if (isset($scriptProperties['ta'])) $scriptProperties['content'] = $scriptProperties['ta'];

        /* deny publishing if not permitted */
        if (!$this->modx->hasPermission('publish_document')) {
            $scriptProperties['publishedon'] = 0;
            $scriptProperties['publishedby'] = 0;
            $scriptProperties['pub_date'] = 0;
            $scriptProperties['unpub_date'] = 0;
            $scriptProperties['published'] = 0;
        }

        if (isset($scriptProperties['published'])) {
            if (empty($scriptProperties['publishedon'])) {
                $scriptProperties['publishedon'] = $scriptProperties['published'] ? time() : 0;
            } else {
                $scriptProperties['publishedon'] = strtotime($scriptProperties['publishedon']);
            }
            $scriptProperties['publishedby'] = $scriptProperties['published'] ? !empty($scriptProperties['publishedby']) ? $scriptProperties['publishedby'] : $this->modx->user->get('id') : 0;
        }

        $this->setProperties($scriptProperties);
        return true;
    }

    /**
     * Handle if parent is a context
     */
    public function checkIfParentIsContext() {
        $parent = $this->getProperty('parent');
        if (!empty($parent) && !is_numeric($parent)) {
            $ctxCnt = $this->modx->getCount('modContext',array('key' => $parent));
            if ($ctxCnt > 0) {
                $this->setProperty('context_key',$parent);
            }
            $this->setProperty('parent',0);
        }
    }

    /**
     * Set and prepare the parent field
     * @return int
     */
    public function prepareParent() {
        $this->checkIfParentIsContext();

        $parent = $this->getProperty('parent');
        $parent = empty($parent) ? 0 : intval($parent);
        $this->setProperty('parent',$parent);
        return $parent;
    }

    /**
     * Make sure parent exists and user can add_children to the parent
     * @return boolean|string
     */
    public function checkParentPermissions() {
        $parent = null;
        $parentId = intval($this->getProperty('parent'));
        if ($parentId > 0) {
            $this->parentResource = $this->modx->getObject('modResource',$parentId);
            if ($this->parentResource) {
                if (!$this->parentResource->checkPolicy('add_children')) {
                    return $this->modx->lexicon('resource_add_children_access_denied');
                }
            } else {
                return $this->modx->lexicon('resource_err_nfs', array('id' => $parentId));
            }
        } elseif (!$this->modx->hasPermission('new_document_in_root')) {
            return $this->modx->lexicon('resource_add_children_access_denied');
        }
        return true;
    }

    /**
     * Prepare the pagetitle for insertion
     * @return string
     */
    public function preparePageTitle() {
        $pageTitle = $this->getProperty('pagetitle','');
        if (!empty($pageTitle)) {
            $pageTitle = trim($pageTitle);
        }

        /* default pagetitle if not reloading template */
        if (!$this->getProperty('reloadOnly',false)) {
            if ($pageTitle === '') {
                $pageTitle = $this->modx->lexicon('resource_untitled');
            }
        }
        $this->setProperty('pagetitle',$pageTitle);
        return $pageTitle;
    }

    /**
     * Get the working Context for the Resource
     *
     * @return modContext
     */
    public function getWorkingContext() {
        $contextKey = $this->getProperty('context_key','');
        if (empty($contextKey)) {
            $contextKey = $this->parentResource ? $this->parentResource->get('context_key') : 'web';
            $this->setProperty('context_key',$contextKey);
        }
        $this->workingContext = $this->modx->getContext($contextKey);
        return $this->workingContext;
    }

    /**
     * Clean and prepare the alias, automatically generating it if the option is set
     * @return string
     */
    public function prepareAlias() {
        // The user submitted alias & page title
        $alias = $this->getProperty('alias');
        $pageTitle = $this->getProperty('pagetitle');
        $autoGenerated = false;

        // If we don't have an alias passed, and automatic_alias is enabled, we generate one from the pagetitle.
        if (empty($alias) && $this->workingContext->getOption('automatic_alias', false)) {
            $alias = $this->object->cleanAlias($pageTitle);
            $autoGenerated = true;
        }

        $friendlyUrlsEnabled = $this->workingContext->getOption('friendly_urls', false) && (!$this->getProperty('reloadOnly',false) || !empty($pageTitle));

        // Check for duplicates
        $duplicateContext = $this->workingContext->getOption('global_duplicate_uri_check', false) ? '' : $this->getProperty('context_key');
        $aliasPath = $this->object->getAliasPath($alias,$this->getProperties());
        $duplicateId = $this->object->isDuplicateAlias($aliasPath, $duplicateContext);
        // We have a duplicate!
        if ($duplicateId) {
            // If friendly urls is enabled, we throw an error about the alias
            if ($friendlyUrlsEnabled) {
                $err = $this->modx->lexicon('duplicate_uri_found', array(
                    'id' => $duplicateId,
                    'uri' => $aliasPath,
                ));
                $this->addFieldError('uri', $err);
                if ($this->getProperty('uri_override',0) !== 1) {
                    $this->addFieldError('alias', $err);
                }
            }
            // If friendly urls is not enabled, and we automatically generated the alias, then we just unset it
            elseif ($autoGenerated) {
                $alias = '';
            }
        }

        // If the alias is empty yet friendly urls is enabled, add an error to the alias field
        if (empty($alias) && $friendlyUrlsEnabled) {
            $this->addFieldError('alias', $this->modx->lexicon('field_required'));
        }

        // Set the new alias and return it, too.
        $this->setProperty('alias',$alias);
        return $alias;
    }

    /**
     * Check if new resource token posted from manager
     * @return boolean
     */
    public function checkForAllowableCreateToken() {
        $tokenPassed = true;
        $token = $this->getProperty('create-resource-token',false);
        if (!empty($token)) {
            $tokenPassed = false;
            if (!is_null($token)) {
                if (isset($_SESSION['newResourceTokens']) && !is_null($_SESSION['newResourceTokens'])) {
                    $search = array_search($token, $_SESSION['newResourceTokens']);
                    if ($search !== false) {
                        unset($_SESSION['newResourceTokens'][$search]);
                        $tokenPassed = true;
                    }
                }
            }
        }
        return $tokenPassed;
    }

    /**
     * Add Template Variables to the Resource object
     * @return array
     */
    public function addTemplateVariables() {
        $addedTemplateVariables = array();
        $templateKey = $this->getProperty('template',0);
        $this->template = $this->modx->getObject('modTemplate',$templateKey);
        if (!empty($templateKey) && $this->template) {
            $templateVars = $this->object->getTemplateVars();

            /** @var modTemplateVar $tv */
            foreach ($templateVars as $tv) {
                $tvKey = 'tv'.$tv->get('id');
                $value = $this->getProperty($tvKey,$tv->get('default_text'));

                switch ($tv->get('type')) {
                    /* ensure tag types trim whitespace from tags */
                    case 'tag':
                    case 'autotag':
                        $tags = explode(',',$value);
                        $newTags = array();
                        foreach ($tags as $tag) {
                            $newTags[] = trim($tag);
                        }
                        $value = implode(',',$newTags);
                        break;
                    case 'date':
                        $value = empty($value) ? '' : strftime('%Y-%m-%d %H:%M:%S',strtotime($value));
                        break;
                    case 'url':
                        if ($this->getProperty($tvKey. '_prefix','--') != '--') {
                            $value = str_replace(array('ftp://','http://'),'', $value);
                            $value = $this->getProperty($tvKey.'_prefix','').$value;
                        }
                        break;
                    default:
                        /* handles checkboxes & multiple selects elements */
                        if (is_array($value)) {
                            $featureInsert = array();
                            while (list($featureValue, $featureItem) = each($value)) {
                                $featureInsert[count($featureInsert)] = $featureItem;
                            }
                            $value = implode('||',$featureInsert);
                        }
                        break;
                }

                /* save value if it was modified */
                $default = $tv->processBindings($tv->get('default_text'),0);

                if (strcmp($value,$default) != 0) {
                    /** @var modTemplateVarResource $templateVarResource */
                    $templateVarResource = $this->modx->newObject('modTemplateVarResource');
                    $templateVarResource->set('tmplvarid',$tv->get('id'));
                    $templateVarResource->set('value',$value);
                    $addedTemplateVariables[] = $templateVarResource;
                }
            }
        }
        return $addedTemplateVariables;
    }

    /**
     * Set the menu index on the Resource, incrementing if not set
     * @return int
     */
    public function setMenuIndex() {
        $autoMenuIndex = $this->workingContext->getOption('auto_menuindex', true);
        $menuIndex = $this->getProperty('menuindex',0);
        if (!empty($autoMenuIndex) && empty($menuIndex)) {
            $menuIndex = $this->modx->getCount('modResource',array(
                'parent' => $this->object->get('parent'),
                'context_key' => $this->object->get('context_key'),
            ));
        }
        $this->object->set('menuindex',$menuIndex);
        return $menuIndex;
    }

    /**
     * Invoke OnBeforeDocFormSave event, and allow non-empty responses to prevent save
     * @return boolean
     */
    public function fireBeforeSaveEvent() {
        /** @var array $OnBeforeDocFormSave */
        $OnBeforeDocFormSave = $this->modx->invokeEvent('OnBeforeDocFormSave',array(
            'mode' => modSystemEvent::MODE_NEW,
            'id' => 0,
            'resource' => &$this->object,
            'reloadOnly' => $this->getProperty('reloadOnly',false),
        ));
        if (is_array($OnBeforeDocFormSave)) {
            $canSave = false;
            foreach ($OnBeforeDocFormSave as $msg) {
                if (!empty($msg)) {
                    $canSave .= $msg."\n";
                }
            }
        } else {
            $canSave = $OnBeforeDocFormSave;
        }
        return $canSave;
    }

    /**
     * Save the Resource Groups on the object
     *
     * @return void
     */
    public function saveResourceGroups() {
        $resourceGroups = $this->getProperty('resource_groups',null);
        if ($resourceGroups !== null) {
            $resourceGroups = $this->modx->fromJSON($resourceGroups);
            if (is_array($resourceGroups)) {
                foreach ($resourceGroups as $id => $resourceGroupAccess) {
                    /* prevent adding records for non-existing groups */
                    $resourceGroup = $this->modx->getObject('modResourceGroup',$resourceGroupAccess['id']);
                    if (empty($resourceGroup)) continue;

                    /* if assigning to group */
                    if (!empty($resourceGroupAccess['access'])) {
                        /** @var modResourceGroupResource $resourceGroupResource */
                        $resourceGroupResource = $this->modx->getObject('modResourceGroupResource',array(
                            'document_group' => $resourceGroupAccess['id'],
                            'document' => $this->object->get('id'),
                        ));
                        if (empty($resourceGroupResource)) {
                            $resourceGroupResource = $this->modx->newObject('modResourceGroupResource');
                        }
                        $resourceGroupResource->set('document_group',$resourceGroupAccess['id']);
                        $resourceGroupResource->set('document',$this->object->get('id'));
                        if ($resourceGroupResource->save()) {
                            $this->modx->invokeEvent('OnResourceAddToResourceGroup',array(
                                'mode' => 'resource-create',
                                'resource' => &$this->object,
                                'resourceGroup' => &$resourceGroup,
                            ));
                        }

                    /* if removing access to group */
                    } else {
                        $resourceGroupResource = $this->modx->getObject('modResourceGroupResource',array(
                            'document_group' => $resourceGroupAccess['id'],
                            'document' => $this->object->get('id'),
                        ));
                        if ($resourceGroupResource instanceof modResourceGroupResource) {
                            if ($resourceGroupResource->remove()) {
                                $this->modx->invokeEvent('OnResourceRemoveFromResourceGroup',array(
                                    'mode' => 'resource-create',
                                    'resource' => &$this->object,
                                    'resourceGroup' => &$resourceGroup,
                                ));
                            }
                        }
                    }
                } /* end foreach */
            } /* end if is_array */
        }
    }

    /**
     * Invoke OnDocFormSave event
     * @return void
     */
    public function fireAfterSaveEvent() {
        $this->modx->invokeEvent('OnDocFormSave', array(
            'mode' => modSystemEvent::MODE_NEW,
            'id' => $this->object->get('id'),
            'resource' => &$this->object,
            'reloadOnly' => $this->getProperty('reloadOnly',false),
        ));
    }

    /**
     * Update parent to be a container if user has save permission
     *
     * @return boolean
     */
    public function setParentToContainer() {
        $saved = false;
        $autoIsFolder = $this->modx->getOption('auto_isfolder', null, true);

        if ($autoIsFolder && $this->parentResource && $this->parentResource instanceof modResource && $this->parentResource->checkPolicy('save')) {
            $this->parentResource->set('isfolder', true);
            $saved = $this->parentResource->save();
        }
        return $saved;
    }

    /**
     * Quick check to make sure it's not site_start, if so, publish if not published to prevent site error
     *
     * @return boolean
     */
    public function checkIfSiteStart() {
        $saved = false;
        if ($this->object->get('id') == $this->workingContext->getOption('site_start') && !$this->object->get('published')) {
            $this->object->set('published',true);
            $saved = $this->object->save();
        }
        return $saved;
    }

    /**
     * Clear the cache if specified
     * @return boolean
     */
    public function clearCache() {
        $clear = $this->getProperty('syncsite',false) || $this->getProperty('clearCache',false);
        if ($clear) {
            $this->modx->cacheManager->refresh(array(
                'db' => array(),
                'auto_publish' => array('contexts' => array($this->workingContext->get('key'))),
                'context_settings' => array('contexts' => array($this->workingContext->get('key'))),
                'resource' => array('contexts' => array($this->workingContext->get('key'))),
            ));
        }
        return $clear;
    }
}
return 'modResourceCreateProcessor';
