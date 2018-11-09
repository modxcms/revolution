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
 * Delete a TV
 *
 * @param integer $id The TV to delete
 *
 * @package modx
 * @subpackage processors.element.tv
 */
class modTemplateVarRemoveProcessor extends modElementRemoveProcessor {
    public $classKey = 'modTemplateVar';
    public $languageTopics = array('tv');
    public $permission = 'delete_tv';
    public $objectType = 'tv';
    public $beforeRemoveEvent = 'OnBeforeTVFormDelete';
    public $afterRemoveEvent = 'OnTVFormDelete';

    public $TemplateVarTemplates = array();
    public $TemplateVarResources = array();
    public $TemplateVarResourceGroups = array();

    public $staticFile = '';
    public $staticFilePath = '';

    public function beforeRemove() {
        /* get tv relational tables */
        $this->TemplateVarTemplates = $this->object->getMany('TemplateVarTemplates');
        $this->TemplateVarResources = $this->object->getMany('TemplateVarResources');
        $this->TemplateVarResourceGroups = $this->object->getMany('TemplateVarResourceGroups');

        if ($this->object->get('static_file')) {
            $source = $this->modx->getObject('sources.modFileMediaSource', array('id' => $this->object->get('source')));
            if ($source && $source->get('is_stream')) {
                $source->initialize();
                $this->staticFile = $this->object->get('static_file');
                $this->staticFilePath = $source->getBasePath() . $this->object->get('static_file');
            }
        }

        /* check if any template uses this TV */
        $tvts = $this->object->getMany('TemplateVarTemplates', array(
            'tmplvarid' => $this->object->get('id')
        ));

        if (count($tvts) > 0) {
            $tids = array();
            foreach ($tvts as $tvt) {
                /** @var modTemplateVarTemplate $tvt */
                $template = $tvt->getOne('Template');
                if ($template) {
                    $tids[] = $template->get('templatename') . ' (' . $tvt->get('templateid') . ')';
                } else {
                    $tids[] = $tvt->get('templateid');
                }
            }
            return $this->modx->lexicon('tv_inuse_template', array(
                'templates' => implode(', ', $tids)
            ));
        } else {
            return true;
        }
    }

    public function afterRemove() {
        $this->cleanupStaticFiles();

        /** @var modTemplateVarResource $tvd */
        foreach ($this->TemplateVarResources as $tvd) {
            if ($tvd->remove() == false) {
                return $this->modx->error->failure($this->modx->lexicon('tvd_err_remove'));
            }
        }

        /** @var modTemplateVarResourceGroup $tvdg */
        foreach ($this->TemplateVarResourceGroups as $tvdg) {
            if ($tvdg->remove() == false) {
                return $this->modx->error->failure($this->modx->lexicon('tvdg_err_remove'));
            }
        }

        /* delete variable's access permissions */
        /** @var modTemplateVarTemplate $tvt */
        foreach ($this->TemplateVarTemplates as $tvt) {
            if ($tvt->remove() == false) {
                return $this->modx->error->failure($this->modx->lexicon('tvt_err_remove'));
            }
        }
        return true;
    }
}
return 'modTemplateVarRemoveProcessor';
