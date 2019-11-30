<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\ContentType;

use MODX\Revolution\modContentType;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modResource;

/**
 * Update a content type from the grid. Sent through JSON-encoded 'data'
 * parameter.
 * @param integer $id The ID of the content type
 * @param string $name The new name
 * @param string $description (optional) A short description
 * @param string $mime_type The MIME type for the content type
 * @param string $file_extensions A list of file extensions associated with this type
 * @param string $headers Any headers to be sent with resources with this type
 * @param boolean $binary If true, will be sent as binary data
 * @package MODX\Revolution\Processors\System\ContentType
 */
class UpdateFromGrid extends Processor
{
    /** @var array $records */
    public $records;

    protected $record;

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('content_types');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['content_type'];
    }

    /**
     * @return bool|string|null
     * @throws \xPDO\xPDOException
     */
    public function initialize()
    {
        $data = $this->getProperty('data');
        if (empty($data)) {
            return $this->modx->lexicon('invalid_data');
        }
        $this->record = $this->modx->fromJSON($data);
        if (empty($this->record)) {
            return $this->modx->lexicon('invalid_data');
        }
        return true;
    }

    public function process()
    {
        $refresh = [];
        $field = $this->record;
        if (empty($field['id'])) {
            return $this->failure($this->modx->lexicon('content_type_err_ns'));
        }
        /** @var modContentType $contentType */
        $contentType = $this->modx->getObject(modContentType::class, $field['id']);
        if (!$contentType) {
            return $this->failure($this->modx->lexicon('content_type_err_nfs', ['id', $field['id']]));
        }

        $this->setCheckbox('binary');

        $contentType->fromArray($field);

        $refresh[] = $contentType->isDirty('file_extensions')
            && $this->modx->getCount(modResource::class, ['content_type' => $contentType->get('id')]);

        if ($contentType->save() === false) {
            $msg = $this->modx->error->checkValidation($contentType);
            return $this->failure(empty($msg) ? $this->modx->lexicon('content_type_err_save') : $msg);
        }

        /* log manager action */
        $this->modx->logManagerAction('content_type_save', modContentType::class, $contentType->get('id'));

        if (in_array(true, $refresh, true)) {
            $this->modx->call(modResource::class, 'refreshURIs', [&$this->modx]);
        }

        return $this->success();
    }
}
