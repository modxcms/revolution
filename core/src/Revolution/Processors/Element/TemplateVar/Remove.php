<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\TemplateVar;


use MODX\Revolution\modTemplateVar;
use MODX\Revolution\modTemplateVarResource;
use MODX\Revolution\modTemplateVarResourceGroup;
use MODX\Revolution\modTemplateVarTemplate;
use MODX\Revolution\Sources\modFileMediaSource;

/**
 * Delete a TV
 *
 * @property integer $id The TV to delete
 *
 * @package MODX\Revolution\Processors\Element\TemplateVar
 */
class Remove extends \MODX\Revolution\Processors\Element\Remove
{
    public $classKey = modTemplateVar::class;
    public $languageTopics = ['tv'];
    public $permission = 'delete_tv';
    public $objectType = 'tv';
    public $beforeRemoveEvent = 'OnBeforeTVFormDelete';
    public $afterRemoveEvent = 'OnTVFormDelete';

    public $TemplateVarTemplates = [];
    public $TemplateVarResources = [];
    public $TemplateVarResourceGroups = [];

    public $staticFile = '';
    public $staticFilePath = '';

    public function beforeRemove()
    {
        /* get tv relational tables */
        $this->TemplateVarTemplates = $this->object->getMany('TemplateVarTemplates');
        $this->TemplateVarResources = $this->object->getMany('TemplateVarResources');
        $this->TemplateVarResourceGroups = $this->object->getMany('TemplateVarResourceGroups');

        if ($this->object->get('static_file')) {
            /** @var modFileMediaSource $source */
            $source = $this->modx->getObject(modFileMediaSource::class, ['id' => $this->object->get('source')]);
            if ($source && $source->get('is_stream')) {
                $source->initialize();
                $this->staticFile = $this->object->get('static_file');
                $this->staticFilePath = $source->getBasePath() . $this->object->get('static_file');
            }
        }

        /* check if any template uses this TV */
        $tvts = $this->object->getMany('TemplateVarTemplates', [
            'tmplvarid' => $this->object->get('id'),
        ]);

        if (count($tvts) > 0) {
            $tids = [];
            foreach ($tvts as $tvt) {
                /** @var modTemplateVarTemplate $tvt */
                $template = $tvt->getOne('Template');
                if ($template) {
                    $tids[] = $template->get('templatename') . ' (' . $tvt->get('templateid') . ')';
                } else {
                    $tids[] = $tvt->get('templateid');
                }
            }

            return $this->modx->lexicon('tv_inuse_template', [
                'templates' => implode(', ', $tids),
            ]);
        } else {
            return true;
        }
    }

    public function afterRemove()
    {
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

