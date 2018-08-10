<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Represents a content element that serves as a resource template.
 *
 * @property int $id The ID of the Template
 * @property string $templatename The name of the Template
 * @property string $description A user-provided description of the Template
 * @property int $editor_type Deprecated
 * @property int $category The Category this Template resides in
 * @property string $icon Deprecated
 * @property int $template_type Deprecated
 * @property string $content The content of the Template
 * @property boolean $locked Whether or not this Template can only be edited by Administrators
 * @property array $properties An array of default properties for the Template
 * @package modx
 */
class modTemplate extends modElement {
    /**
     * Get a sortable, limitable list and total record count of Template Variables.
     *
     * This list includes an access field indicating their relationship to a modTemplate.
     *
     * @static
     * @param modTemplate &$template A modTemplate instance.
     * @param array $sort An array of criteria for sorting the list.
     * @param int $limit An optional limit to apply to the list.
     * @param array $conditions
     * @param int $offset An optional offset to apply to the list.
     * @return array An array with the list collection and total records in the collection.
     */
    public static function listTemplateVars(modTemplate &$template, array $sort = array('name' => 'ASC'), $limit = 0, $offset = 0,array $conditions = array()) {
        return array('collection' => array(), 'total' => 0);
    }

    /**
     * @param xPDO $xpdo A reference to the xPDO|modX instance
     */
    function __construct(xPDO & $xpdo) {
        parent :: __construct($xpdo);
        $this->setCacheable(false);
    }

    /**
     * Overrides modElement::save to add custom error logging and fire
     * modX-specific events.
     *
     * {@inheritdoc}
     */
    public function save($cacheFlag = null) {
        $isNew = $this->isNew();
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnTemplateBeforeSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'template' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        }

        $saved = parent::save($cacheFlag);

        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnTemplateSave',array(
                'mode' => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'template' => &$this,
                'cacheFlag' => $cacheFlag,
            ));
        } else if (!$saved && !empty($this->xpdo->lexicon)) {
            $msg = $isNew ? $this->xpdo->lexicon('template_err_create') : $this->xpdo->lexicon('template_err_save');
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$msg.$this->toArray());
        }

        return $saved;
    }

    /**
     * Overrides modElement::remove to add custom error logging.
     *
     * {@inheritdoc}
     */
    public function remove(array $ancestors= array ()) {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnTemplateBeforeRemove',array(
                'template' => &$this,
                'ancestors' => $ancestors,
            ));
        }
        $removed = parent :: remove($ancestors);

        if ($removed && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnTemplateRemove',array(
                'template' => &$this,
                'ancestors' => $ancestors,
            ));
        } else if (!$removed && !empty($this->xpdo->lexicon)) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,$this->xpdo->lexicon('template_err_remove').$this->toArray());
        }

        return $removed;
    }

    /**
     * Process the template content and return the output.
     *
     * {@inheritdoc}
     */
    public function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if (!$this->_processed) {
            $this->_output= $this->_content;

            if (is_string($this->_output) && !empty($this->_output)) {
                /* turn the processed properties into placeholders */
                $this->xpdo->toPlaceholders($this->_properties, '', '.', true);

                /* collect element tags in the content and process them */
                $maxIterations= intval($this->xpdo->getOption('parser_max_iterations',null,10));
                $this->xpdo->parser->processElementTags($this->_tag, $this->_output, false, false, '[[', ']]', array(), $maxIterations);
            }
            $this->filterOutput();
            $this->_processed= true;
        }
        $this->xpdo->parser->setProcessingElement(false);
        return $this->_output;
    }

    /**
     * Gets a collection of objects related by aggregate or composite relations.
     *
     * {@inheritdoc}
     *
     * Includes special handling for related objects with alias {@link
     * modTemplateVar}, respecting framework security unless specific criteria
     * are provided.
     */
    public function & getMany($alias, $criteria= null, $cacheFlag= true) {
        $collection= array ();
        if (($alias === 'TemplateVars' || $alias === 'modTemplateVar') && ($criteria === null || strtolower($criteria) === 'all')) {
            $c = $this->xpdo->newQuery('modTemplateVar');
            $c->query['distinct'] = 'DISTINCT';
            $c->select($this->xpdo->getSelectColumns('modTemplateVar'));
            $c->select($this->xpdo->getSelectColumns('modTemplateVarTemplate', 'tvtpl', '', array('rank')));
            $c->select(array('value' => $this->xpdo->getSelectColumns('modTemplateVar', 'modTemplateVar', '', array('default_text'))));
            $c->innerJoin('modTemplateVarTemplate','tvtpl',array(
                'tvtpl.tmplvarid = modTemplateVar.id',
                'tvtpl.templateid' => $this->get('id'),
            ));
            $c->sortby('tvtpl.rank,modTemplateVar.rank');

            $collection = $this->xpdo->getCollection('modTemplateVar', $c, $cacheFlag);
        } else {
            $collection= parent :: getMany($alias, $criteria, $cacheFlag);
        }
        return $collection;
    }

    /**
     * Grabs an array of Template Variables associated with this Template,
     * bypassing the many-to-many relationship.
     *
     * @access public
     * @return array An array of TVs.
     */
    public function getTemplateVars() {
        $c = $this->xpdo->newQuery('modTemplateVar');
        $c->innerJoin('modTemplateVarTemplate','TemplateVarTemplates');
        $c->where(array(
            'TemplateVarTemplates.templateid' => $this->get('id'),
        ));
        $c->sortby('TemplateVarTemplates.rank','ASC');
        return $this->xpdo->getCollection('modTemplateVar',$c);
    }

    /**
     * Get a list of Template Variables and if they are currently associated to this template.
     *
     * This is a sortable, scrollable list.
     *
     * @param array $sort An array of criteria for sorting the list.
     * @param integer $limit An optional limit to apply to the list.
     * @param integer $offset An optional offset to apply to the list.
     * @param array $conditions
     * @return array An array containing the collection and total.
     */
    public function getTemplateVarList(array $sort = array('name' => 'ASC'), $limit = 0, $offset = 0,array $conditions = array()) {
        return $this->xpdo->call('modTemplate', 'listTemplateVars', array(&$this, $sort, $limit, $offset,$conditions));
    }

    /**
     * Check to see if this Template is assigned the specified Template Var
     *
     * @param mixed $tvPk Either the ID, name or object of the Template Var
     * @return boolean True if the TV is assigned to this Template
     */
    public function hasTemplateVar($tvPk) {
        if (!is_int($tvPk) && !is_object($tvPk)) {
            $tv = $this->xpdo->getObject('modTemplateVar',array('name' => $tvPk));
            if (empty($tv) || !is_object($tv) || !($tv instanceof modTemplateVar)) {
                $this->xpdo->log(modX::LOG_LEVEL_ERROR,'modTemplate::hasTemplateVar - No TV: '.$tvPk);
                return false;
            }
        } else {
            $tv =& $tvPk;
        }
        $templateVarTemplate = $this->xpdo->getObject('modTemplateVarTemplate',array(
            'tmplvarid' => is_object($tv) ? $tv->get('id') : $tv,
            'templateid' => $this->get('id'),
        ));
        return !empty($templateVarTemplate) && is_object($templateVarTemplate);
    }
}
