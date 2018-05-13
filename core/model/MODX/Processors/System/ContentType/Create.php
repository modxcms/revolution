<?php

namespace MODX\Processors\System\ContentType;

use MODX\Processors\modObjectCreateProcessor;

/**
 * Create a content type
 *
 * @param string $name The new name
 * @param string $description (optional) A short description
 * @param string $mime_type The MIME type for the content type
 * @param string $file_extensions A list of file extensions associated with this
 * type
 * @param string $headers Any headers to be sent with resources with this type
 * @param boolean $binary If true, will be sent as binary data
 *
 * @package modx
 * @subpackage processors.system.contenttype
 */
class Create extends modObjectCreateProcessor
{
    public $classKey = 'modContentType';
    public $languageTopics = ['content_type'];
    public $permission = 'content_types';
    public $objectType = 'content_type';


    public function beforeSave()
    {
        $headers = json_decode($this->getProperty('headers', '[]'), true);
        $this->object->set('headers', $headers);

        $binary = $this->getProperty('binary', null);
        if ($binary !== null) {
            $this->object->set('binary', ($binary == 'true'));
        }

        return parent::beforeSave();
    }
}
