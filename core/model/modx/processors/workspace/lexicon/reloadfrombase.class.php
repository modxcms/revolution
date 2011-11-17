<?php
/**
 * Regenerates strings from the base lexicon files, resetting any customizations.
 * @package modx
 * @subpackage processors.workspace.lexicon
 */
class modLexiconReloadFromBaseProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('lexicons');
    }
    public function getLanguageTopics() {
        return array('lexicon');
    }

    public function process() {
        $language = 'en';
        $namespace = 'core';
        $corePath = $this->modx->getOption('core_path',null,MODX_CORE_PATH);

        $this->modx->lexicon->clearCache();
        $c = $this->modx->newQuery('modLexiconEntry');
        $c->where(array(
            'namespace' => $namespace,
            'language' => $language,
        ));
        $entries = $this->modx->getCollection('modLexiconEntry',$c);

        $currentTopic = '';
        $lex = array();
        $i = 0;
        /** @var modLexiconEntry $entry */
        foreach ($entries as $entry) {
            if ($currentTopic != $entry->get('topic')) {
                $currentTopic = $entry->get('topic');

                $topicPath = str_replace('//','/',$corePath.'/lexicon/'.$language.'/'.$currentTopic.'.inc.php');
                $lex = array();
                $_lang = array();
                if (file_exists($topicPath)) {
                    include $topicPath;
                    $lex = $_lang;
                }
            }

            if (!empty($lex[$entry->get('name')])) {
                $i++;
                $entry->remove();
            }
        }

        $this->modx->log(modX::LOG_LEVEL_WARN,$this->modx->lexicon('reload_success',array(
            'total' => $i,
        )));
        $this->modx->log(modX::LOG_LEVEL_INFO,'COMPLETED');
        return $this->success(intval($i));
    }
}
return 'modLexiconReloadFromBaseProcessor';