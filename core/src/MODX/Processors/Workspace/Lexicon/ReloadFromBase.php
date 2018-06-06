<?php

namespace MODX\Processors\Workspace\Lexicon;

use MODX\modLexiconEntry;
use MODX\MODX;
use MODX\Processors\modProcessor;

/**
 * Regenerates strings from the base lexicon files, resetting any customizations.
 *
 * @package modx
 * @subpackage processors.workspace.lexicon
 */
class ReloadFromBase extends modProcessor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('lexicons');
    }


    public function getLanguageTopics()
    {
        return ['lexicon'];
    }


    public function process()
    {
        $language = 'en';
        $namespace = 'core';
        $corePath = $this->modx->getOption('core_path', null, MODX_CORE_PATH);

        $this->modx->lexicon->clearCache();
        $c = $this->modx->newQuery('modLexiconEntry');
        $c->where([
            'namespace' => $namespace,
            'language' => $language,
        ]);
        $entries = $this->modx->getCollection('modLexiconEntry', $c);

        $currentTopic = '';
        $lex = [];
        $i = 0;
        /** @var modLexiconEntry $entry */
        foreach ($entries as $entry) {
            if ($currentTopic != $entry->get('topic')) {
                $currentTopic = $entry->get('topic');

                $topicPath = str_replace('//', '/', $corePath . '/lexicon/' . $language . '/' . $currentTopic . '.inc.php');
                $lex = [];
                $_lang = [];
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

        $this->modx->log(MODX::LOG_LEVEL_WARN, $this->modx->lexicon('reload_success', [
            'total' => $i,
        ]));
        $this->modx->log(MODX::LOG_LEVEL_INFO, 'COMPLETED');

        return $this->success(intval($i));
    }
}