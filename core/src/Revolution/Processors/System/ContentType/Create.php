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
use MODX\Revolution\Processors\Model\CreateProcessor;

/**
 * Create a content type
 * @param string $name The new name
 * @param string $description (optional) A short description
 * @param string $mime_type The MIME type for the content type
 * @param string $file_extensions A list of file extensions associated with this type
 * @param string $headers Any headers to be sent with resources with this type
 * @param boolean $binary If true, will be sent as binary data
 * @package MODX\Revolution\Processors\System\ContentType
 */
class Create extends CreateProcessor
{
    public $classKey = modContentType::class;
    public $languageTopics = ['content_type'];
    public $permission = 'content_types';
    public $objectType = 'content_type';

    /**
     * @return bool
     * @throws \xPDO\xPDOException
     */
    public function beforeSave()
    {
        $this->setCheckbox('binary');

        $headers = $this->modx->fromJSON($this->getProperty('headers', '[]'));
        $this->object->set('headers', $headers);

        return parent::beforeSave();
    }
}
