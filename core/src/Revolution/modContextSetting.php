<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOObject;
use xPDO\xPDO;

/**
 * Represents a context-specific configuration setting.
 *
 * These settings are loaded and will be merged with the already loaded system
 * level settings, then merged again with user level settings for authenticated
 * users.
 *
 * @property string    $context_key The key of the Context this Setting applies to
 * @property string    $key         The key of the Setting
 * @property string    $value       The value of the Setting
 * @property string    $xtype       The xtype that is used to render the Setting input in the manager
 * @property string    $namespace   The Namespace of the setting
 * @property string    $area        The area of the Setting
 * @property string    $editedon    The last edited on time of this Setting
 *
 * @property modX|xPDO $xpdo
 *
 * @package MODX\Revolution
 */
class modContextSetting extends xPDOObject
{
    /**
     * Updates the Lexicon Entry translation for this Context Setting
     *
     * @param string $key     The key of the setting
     * @param string $value   The new value of the setting
     * @param array  $options An array of options related to the setting
     *
     * @return bool
     */
    public function updateTranslation($key, $value = '', array $options = [])
    {
        if (!is_array($options) || empty($options)) {
            return false;
        }

        $options['namespace'] = $this->xpdo->getOption('namespace', $options, 'core');
        $options['cultureKey'] = $this->xpdo->getOption('cultureKey', $options, 'en');
        $options['topic'] = $options['namespace'] == 'core' ? 'setting' : 'default';
        $saved = false;

        $entries = $this->xpdo->lexicon->getFileTopic($options['cultureKey'], $options['namespace'], $options['topic']);
        /** @var modLexiconEntry $entry */
        $entry = $this->xpdo->getObject(modLexiconEntry::class, [
            'name' => $key,
            'namespace' => $options['namespace'],
            'language' => $options['cultureKey'],
            'topic' => $options['topic'],
        ]);
        if ((!empty($entries[$key]) && $entries[$key] == $value) || strcmp($key, $value) == 0 || empty($value)) {
            if ($entry) {
                $saved = $entry->remove();
                $this->xpdo->lexicon->clearCache($options['cultureKey'] . '/' . $options['namespace'] . '/' . $options['topic'] . '.cache.php');
            }
        } else {
            if ($entry == null) {
                $entry = $this->xpdo->newObject(modLexiconEntry::class);
                $entry->set('name', $key);
                $entry->set('namespace', $options['namespace']);
                $entry->set('language', $options['cultureKey']);
                $entry->set('topic', $options['topic']);
            }
            $entry->set('value', $value);
            $saved = $entry->save();
            $entry->clearCache();
        }

        return $saved;
    }
}
