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
use MODX\Revolution\modNamespace;
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
        $namespace = $this->getProperty('namespace');
        $topic = $this->getProperty('lexiconTopic');
        $language = $this->getProperty('language');

        $namespaceObj = $this->modx->getObject(modNamespace::class, $namespace);

        if (empty($namespace) || empty($topic) || empty($language) || empty($namespaceObj)) {
            $this->modx->log(modX::LOG_LEVEL_WARN, $this->modx->lexicon('lexicon_revert_error'));
            $this->modx->log(modX::LOG_LEVEL_INFO, 'COMPLETED');

            return $this->success();
        }

        $namespacePath = $namespaceObj->getCorePath();

        $this->modx->lexicon->clearCache();
        $c = $this->modx->newQuery(modLexiconEntry::class);
        $c->where([
            'namespace' => $namespace,
            'language' => $language,
            'topic' => $topic,
        ]);
        $entries = $this->modx->getCollection(modLexiconEntry::class, $c);

        $i = 0;
        $names = [];
        /** @var modLexiconEntry $entry */
        foreach ($entries as $entry) {
            $topicPath = str_replace('//', '/', $namespacePath . '/lexicon/' . $language . '/' . $topic . '.inc.php');
            $lex = [];
            $_lang = [];
            if (file_exists($topicPath)) {
                include $topicPath;
                $lex = $_lang;
            }

            if (!empty($lex[$entry->get('name')])) {
                $i++;
                $names[] = $entry->get('name');
                $entry->remove();
            }
        }

        if ($i) {
            $this->modx->log(modX::LOG_LEVEL_WARN, $this->modx->lexicon('lexicon_revert_success', [
                'total' => $i, 'names' => implode(', ', $names)
            ]));
        } else {
            $this->modx->log(modX::LOG_LEVEL_WARN, $this->modx->lexicon('lexicon_revert_error'));
        }
        $this->modx->log(modX::LOG_LEVEL_INFO, 'COMPLETED');

        return $this->success($i);
    }
}
