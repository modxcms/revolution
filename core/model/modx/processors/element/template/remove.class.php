<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

require_once (dirname(__DIR__).'/remove.class.php');
/**
 * Deletes a template.
 *
 * @param integer $id The ID of the template
 *
 * @package modx
 * @subpackage processors.element.template
 */
class modTemplateRemoveProcessor extends modElementRemoveProcessor {
    public $classKey = 'modTemplate';
    public $languageTopics = array('template','tv');
    public $permission = 'delete_template';
    public $objectType = 'template';
    public $beforeRemoveEvent = 'OnBeforeTempFormDelete';
    public $afterRemoveEvent = 'OnTempFormDelete';

    public $TemplateVarTemplates = array();

    public $staticFile = '';
    public $staticFilePath = '';

    public function beforeRemove() {
        /* check to make sure it doesn't have any resources using it */
        $resources = $this->modx->getCollection('modResource',array(
            'deleted' => 0,
            'template' => $this->object->get('id'),
        ));
        if (count($resources) > 0) {
            $ds = '';
            /** @var modResource $resource */
            foreach ($resources as $resource) {
                $ds .= $resource->get('id').' - '.$resource->get('pagetitle')." <br />\n";
            }
            return $this->modx->lexicon('template_err_in_use').$ds;
        }

        /* make sure isn't default template */
        if ($this->object->get('id') == $this->modx->getOption('default_template',null,1)) {
            return $this->modx->lexicon('template_err_default_template');
        }

        if ($this->object->get('static_file')) {
            $source = $this->modx->getObject('sources.modFileMediaSource', array('id' => $this->object->get('source')));
            if ($source && $source->get('is_stream')) {
                $source->initialize();
                $this->staticFile = $this->object->get('static_file');
                $this->staticFilePath = $source->getBasePath() . $this->object->get('static_file');
            }
        }

        $this->TemplateVarTemplates = $this->object->getMany('TemplateVarTemplates');
        return true;
    }

    public function afterRemove() {
        $this->cleanupStaticFiles();

        /** @var modTemplateVarTemplate $ttv */
        foreach ($this->TemplateVarTemplates as $ttv) {
            if ($ttv->remove() == false) {
                $this->modx->log(modX::LOG_LEVEL_ERROR,$this->modx->lexicon('tvt_err_remove'));
            }
        }
    }
}
return 'modTemplateRemoveProcessor';
