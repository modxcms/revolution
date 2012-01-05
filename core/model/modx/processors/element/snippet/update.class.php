<?php
require_once (dirname(dirname(__FILE__)).'/update.class.php');
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
 * @param json $propdata (optional) A json array of properties
 *
 * @package modx
 * @subpackage processors.element.snippet
 */
class modSnippetUpdateProcessor extends modElementUpdateProcessor {
    public $classKey = 'modSnippet';
    public $languageTopics = array('snippet','category','element');
    public $permission = 'save_snippet';
    public $objectType = 'snippet';
    public $beforeSaveEvent = 'OnBeforeSnipFormSave';
    public $afterSaveEvent = 'OnSnipFormSave';

    public function cleanup() {
        return $this->success('',array_merge($this->object->get(array('id', 'name', 'description', 'locked', 'category', 'snippet')), array('previous_category' => $this->previousCategory)));
    }
}
return 'modSnippetUpdateProcessor';
