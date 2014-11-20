<?php
/**
 * Create a user setting
 *
 * @param integer $user/$fk The user to create the setting for
 * @param string $key The setting key
 * @param string $value The value of the setting
 * @param string $name The lexicon name for the setting
 * @param string $description The lexicon description for the setting
 * @param string $area The area for the setting
 * @param string $namespace The namespace for the setting
 *
 * @package modx
 * @subpackage processors.context.setting
 */

class modUserSettingCreateProcessor extends modObjectCreateProcessor {
    public $classKey = 'modUserSetting';
    public $languageTopics = array('setting','namespace');
    public $permission = 'settings';
    public $objectType = 'setting';
    public $primaryKeyField = 'key';

    /**
     * Verify the Namespace passed is a valid Namespace
     * @return string|null
     */
    public function verifyNamespace() {
        $namespace = $this->getProperty('namespace', '');
        if (empty($namespace)) {
            $this->addFieldError('namespace', $this->modx->lexicon('namespace_err_ns'));
        }
        $namespace = $this->modx->getObject('modNamespace', $namespace);
        if (!$namespace) {
            $this->addFieldError('namespace', $this->modx->lexicon('namespace_err_nf'));
        }
    }

    /**
     * Validate user setting key
     */
    public function validateSettingKey() {
        /* prevent empty or already existing settings */
        $key = trim($this->getProperty('key', ''));
        if (empty($key)) {
            $this->addFieldError('key', $this->modx->lexicon($this->objectType.'_err_ns'));
        }

        if ($this->doesAlreadyExist(array(
            'key' => $key,
            'user' => $this->getProperty('fk')
        ))) {
            $this->addFieldError('key', $this->modx->lexicon($this->objectType.'_err_ae'));
        }

        /* prevent keys starting with numbers */
        $numbers = explode(',', '1,2,3,4,5,6,7,8,9,0');
        if (in_array(substr($key, 0, 1), $numbers)) {
            $this->addFieldError('key',$this->modx->lexicon($this->objectType.'_err_startint'));
        }
        $this->setProperty('key', $key);
    }

    /**
     * {@inheritdoc}
     * @return bool
     */
    public function beforeSet() {

        $this->checkNamespace();
        $this->validateSettingKey();
        $this->setProperty('user', (int)$this->getProperty('fk', 0));

        return parent::beforeSet();
    }

    /**
     * Create lexicon entry if not exists
     * @param $key
     * @param array $fields
     */
    public function setLexiconEntry($key, array $fields) {
        if (!$this->modx->lexicon->exists($key)) {
            $entry = $this->modx->getObject('modLexiconEntry',array(
                'namespace' => $fields['namespace'],
                'topic' => 'default',
                'name' => $key,
            ));
            if (!$entry) {
                /** @var modLexiconEntry $entry */
                $entry = $this->modx->newObject('modLexiconEntry');
                $entry->set('namespace', $fields['namespace']);
                $entry->set('name', $key);
                $entry->set('value', $fields['name']);
                $entry->set('topic','default');
                $entry->save();
                $entry->clearCache();
            }
        }
    }

    /**
     * Only set name/description lexicon entries if they dont exist for user settings
     * @param array $fields
     * @return void
     */
    public function setLexiconEntries(array $fields) {
        $this->setLexiconEntry($this->objectType.'_'.$fields['key'], $fields);
        $this->setLexiconEntry($this->objectType.'_'.$fields['key'].'_desc', $fields);
    }

    /**
     * {@inheritDoc}
     * @return mixed
     */
    public function afterSave() {
        $this->setLexiconEntries($this->object->toArray());
        $this->modx->reloadConfig();
        return true;
    }
}

return 'modUserSettingCreateProcessor';
