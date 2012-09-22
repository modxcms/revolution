<?php
/**
 * Create an action
 *
 * @param string $controller The controller location
 * @param boolean $loadheaders Whether or not to load header templates for the
 * action
 * @param string $namespace The namespace for the action
 * @param string $lang_topics The lexicon topics for the action
 * @param string $assets
 * @param integer $parent (optional) The parent for the action. Defaults to 0.
 *
 * @package modx
 * @subpackage processors.system.action
 */
class modActionCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'modAction';
    public $languageTopics = array('action','menu','namespace');
    public $permission = 'actions';
    public $objectType = 'action';

    public function initialize() {
        $this->setDefaultProperties(array(
            'haslayout' => true,
            'lang_topics' => '',
        ));
        return parent::initialize();
    }

    public function beforeSave() {
        $hasLayout = $this->getProperty('haslayout',true);
        $this->object->set('haslayout',$hasLayout);

        $controller = $this->getProperty('controller');
        if (empty($controller)) {
            $this->addFieldError('controller',$this->modx->lexicon('controller_err_ns'));
        } else {
            $controllerExists = $this->modx->getCount('modAction',array(
                'namespace' => $this->getProperty('namespace'),
                'controller' => $controller,
            )) > 0;
            if ($controllerExists) {
                $this->addFieldError('controller',$this->modx->lexicon('controller_err_ae'));
            }
        }

        /* verify parent */
        $parent = $this->getProperty('parent',null);
        if ($parent == null) {
            $this->addFieldError('parent',$this->modx->lexicon('action_parent_err_ns'));
        } else if (!empty($parent)) {
            $parent = $this->modx->getObject('modAction',$parent);
            if (empty($parent)) {
                $this->addFieldError('parent',$this->modx->lexicon('action_parent_err_nf'));
            }
        }

        /* verify namespace */
        $namespace = $this->getProperty('namespace','');
        if (empty($namespace)) $this->addFieldError('namespace',$this->modx->lexicon('namespace_err_nf'));
        $namespace = $this->modx->getObject('modNamespace',$namespace);
        if (empty($namespace)) {
            $this->addFieldError('namespace',$this->modx->lexicon('namespace_err_nf'));
        }

        return !$this->hasErrors();
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function cleanup() {
        $this->modx->cacheManager->refresh(
            array(
                $this->modx->getOption('cache_action_map_key', null, 'action_map'),
                $this->modx->getOption('cache_menu_key', null, 'menu'),
            )
        );
        return parent::cleanup();
    }
}
return 'modActionCreateProcessor';