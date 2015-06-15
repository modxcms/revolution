<?php
/**
 * @package modx
 */
/**
/**
 * Represents a user group setting which overrides system and context settings.
 *
 *
 * @property int $group The ID of the Group
 * @property string $key The key of the Setting
 * @property string $value The value of the Setting
 * @property string $xtype The xtype that is used to render the Setting input in the manager
 * @property string $namespace The Namespace of the setting
 * @property string $area The area of the Setting
 * @property string $editedon The last edited on time of this Setting
 *
 * @package modx
 */
class modUserGroupSetting extends xPDOObject {
    /**
     * Update the translation for the setting
     *
     * @param string $key The key of the Setting to update
     * @param string $value The value of the Setting to update
     * @param array $options An array of options for the update
     * @return bool
     */
    public function updateTranslation($key,$value = '',array $options = array()) {
        if (!is_array($options) || empty($options)) return false;

        $options['namespace'] = $this->xpdo->getOption('namespace',$options,'core');
        $options['cultureKey'] = $this->xpdo->getOption('cultureKey',$options,'en');
        $options['topic'] = $options['namespace'] == 'core' ? 'setting' : 'default';
        $saved = false;

        $entries = $this->xpdo->lexicon->getFileTopic($options['cultureKey'],$options['namespace'],$options['topic']);
        $entry = $this->xpdo->getObject('modLexiconEntry',array(
            'name' => $key,
            'namespace' => $options['namespace'],
            'language' => $options['cultureKey'],
            'topic' => $options['topic'],
        ));
        if ((!empty($entries[$key]) && $entries[$key] == $value) || strcmp($key,$value) == 0 || empty($value)) {
            if ($entry) {
                $saved = $entry->remove();
                $this->xpdo->lexicon->clearCache($options['cultureKey'].'/'.$options['namespace'].'/'.$options['topic'].'.cache.php');
            }
        } else {
            if ($entry == null) {
                $entry = $this->xpdo->newObject('modLexiconEntry');
                $entry->set('name',$key);
                $entry->set('namespace',$options['namespace']);
                $entry->set('language',$options['cultureKey']);
                $entry->set('topic',$options['topic']);
            }
            $entry->set('value',$value);
            $saved = $entry->save();
            $entry->clearCache();
        }
        return $saved;
    }
}
