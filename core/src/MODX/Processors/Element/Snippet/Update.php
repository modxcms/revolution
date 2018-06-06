<?php

namespace MODX\Processors\Element\Snippet;

/**
 * Update a snippet
 *
 * @param integer $id The ID of the snippet
 * @param string $name The name of the snippet
 * @param string $snippet The code of the snippet.
 * @param string $description (optional) A brief description.
 * @param integer $category (optional) The category to assign to. Defaults to no
 * category.
 * @param boolean $locked (optional) If true, can only be accessed by
 * administrators. Defaults to false.
 * @param string $propdata (optional) A json array of properties
 *
 * @package modx
 * @subpackage processors.element.snippet
 */
class Update extends \MODX\Processors\Element\Update
{
    public $classKey = 'modSnippet';
    public $languageTopics = ['snippet', 'category', 'element'];
    public $permission = 'save_snippet';
    public $objectType = 'snippet';
    public $beforeSaveEvent = 'OnBeforeSnipFormSave';
    public $afterSaveEvent = 'OnSnipFormSave';


    public function beforeSave()
    {
        $isStatic = intval($this->getProperty('static', 0));

        if ($isStatic == 1) {
            $staticFile = $this->getProperty('static_file');

            if (empty($staticFile)) {
                $this->addFieldError('static_file', $this->modx->lexicon('static_file_ns'));
            }
        }

        return parent::beforeSave();
    }


    public function cleanup()
    {
        return $this->success('', array_merge(
            $this->object->get(['id', 'name', 'description', 'locked', 'category', 'snippet']),
            ['previous_category' => $this->previousCategory]
        ));
    }
}
