<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Workspace\Lexicon;

use MODX\Revolution\modLexiconEntry;
use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modX;

/**
 * Regenerates strings from the base lexicon files, resetting any customizations.
 * @package MODX\Revolution\Processors\Workspace\Lexicon
 */
class ReloadFromBase extends Processor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('lexicons');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['lexicon'];
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $language = 'en';
        $namespace = 'core';
        $corePath = $this->modx->getOption('core_path', null, MODX_CORE_PATH);

        $this->modx->lexicon->clearCache();
        $c = $this->modx->newQuery(modLexiconEntry::class);
        $c->where([
            'namespace' => $namespace,
            'language' => $language,
        ]);
        $entries = $this->modx->getCollection(modLexiconEntry::class, $c);

        $currentTopic = '';
        $i = 0;
        /** @var modLexiconEntry $entry */
        foreach ($entries as $entry) {
            if ($currentTopic !== $entry->get('topic')) {
                $currentTopic = $entry->get('topic');

                $topicPath = str_replace('//', '/',
                    $corePath . '/lexicon/' . $language . '/' . $currentTopic . '.inc.php');
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

        $this->modx->log(modX::LOG_LEVEL_WARN, $this->modx->lexicon('reload_success', ['total' => $i]));
        $this->modx->log(modX::LOG_LEVEL_INFO, 'COMPLETED');

        return $this->success($i);
    }
}
