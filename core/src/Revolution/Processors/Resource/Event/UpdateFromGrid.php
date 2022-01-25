<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Resource\Event;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modResource;

/**
 * Update a resource from the site schedule grid.
 *
 * @param json $data A JSON array of data to update with.
 */
class UpdateFromGrid extends Processor
{
    /** @var modResource $resource */
    public $resource;

    public function checkPermissions()
    {
        return $this->modx->hasPermission('save_document');
    }

    public function getLanguageTopics()
    {
        return ['resource'];
    }

    public function initialize()
    {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('resource_err_ns');
        $data = $this->modx->fromJSON($data);
        if (empty($data) || empty($data['id'])) return $this->modx->lexicon('resource_err_ns');

        $this->setProperties($data);

        $this->resource = $this->modx->getObject(modResource::class, $data['id']);
        if (empty($this->resource)) return $this->modx->lexicon('resource_err_nf');
        return true;
    }

    public function process()
    {
        if (!$this->validate()) {
            return $this->failure();
        }
        $this->resource->fromArray($this->getProperties());

        if ($this->resource->save() === false) {
            return $this->failure($this->modx->lexicon('resource_err_save'));
        }

        return $this->success();
    }

    public function validate()
    {
        $publishDate = $this->getProperty('pub_date');
        if (!empty($publishDate)) {
            $this->setProperty('pub_date', date('Y-m-d H:i', strtotime($publishDate)));
        }

        $unPublishDate = $this->getProperty('unpub_date');
        if (!empty($unPublishDate)) {
            $this->setProperty('unpub_date', date('Y-m-d H:i', strtotime($unPublishDate)));
        }
        return true;
    }
}
