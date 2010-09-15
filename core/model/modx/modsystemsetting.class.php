<?php
/**
 * Represents a system configuration setting.
 *
 * @package modx
 */
class modSystemSetting extends xPDOObject {
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