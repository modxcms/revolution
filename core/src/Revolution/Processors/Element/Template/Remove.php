<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Element\Template;


use MODX\Revolution\modResource;
use MODX\Revolution\modTemplate;
use MODX\Revolution\modTemplateVarTemplate;
use MODX\Revolution\modX;
use MODX\Revolution\Sources\modFileMediaSource;

/**
 * Deletes a template.
 *
 * @property integer $id The ID of the template
 *
 * @package MODX\Revolution\Processors\Element\Template
 */
class Remove extends \MODX\Revolution\Processors\Element\Remove
{
    public $classKey = modTemplate::class;
    public $languageTopics = ['template', 'tv'];
    public $permission = 'delete_template';
    public $objectType = 'template';
    public $beforeRemoveEvent = 'OnBeforeTempFormDelete';
    public $afterRemoveEvent = 'OnTempFormDelete';

    public $TemplateVarTemplates = [];

    public $staticFile = '';
    public $staticFilePath = '';

    public function beforeRemove()
    {
        /* check to make sure it doesn't have any resources using it */
        $resources = $this->modx->getCollection(modResource::class, [
            'deleted' => 0,
            'template' => $this->object->get('id'),
        ]);
        if (count($resources) > 0) {
            $ds = '';
            /** @var modResource $resource */
            foreach ($resources as $resource) {
                $ds .= $resource->get('id') . ' - ' . $resource->get('pagetitle') . " <br />\n";
            }

            return $this->modx->lexicon('template_err_in_use') . $ds;
        }

        /* make sure isn't default template */
        if ($this->object->get('id') == $this->modx->getOption('default_template', null, 1)) {
            return $this->modx->lexicon('template_err_default_template');
        }

        if ($this->object->get('static_file')) {
            /** @var modFileMediaSource $source */
            $source = $this->modx->getObject(modFileMediaSource::class, ['id' => $this->object->get('source')]);
            if ($source && $source->get('is_stream')) {
                $source->initialize();
                $this->staticFile = $this->object->get('static_file');
                $this->staticFilePath = $source->getBasePath() . $this->object->get('static_file');
            }
        }

        $this->TemplateVarTemplates = $this->object->getMany('TemplateVarTemplates');

        return true;
    }

    public function afterRemove()
    {
        $this->cleanupStaticFiles();

        /** @var modTemplateVarTemplate $ttv */
        foreach ($this->TemplateVarTemplates as $ttv) {
            if ($ttv->remove() == false) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, $this->modx->lexicon('tvt_err_remove'));
            }
        }
    }
}
