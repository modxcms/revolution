<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 *
 * @package modx-test
 */
namespace MODX\Revolution\Tests\Build;

use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__, 3) . '/lexicon/checklexicon.class.php';

/**
 * @group Build
 * @group Lexicon
 * @group CheckLexicon
 */
class CheckLexiconDuplicateTest extends TestCase
{
    public function testFindCrossTopicDuplicatesSplitsIdenticalAndConflict()
    {
        $topics = [
            'chunk' => [
                'chunk' => 'Chunk',
                'chunk_err_nf' => 'Chunk not found!',
            ],
            'default' => [
                'chunk' => 'Chunk',
                'access' => 'Access',
            ],
            'resource' => [
                'access' => 'Access Permissions',
            ],
        ];

        $result = \CheckLexicon::findCrossTopicDuplicates($topics);

        $this->assertArrayHasKey('chunk', $result['identical']);
        $this->assertSame(['chunk', 'default'], $result['identical']['chunk']['topics']);
        $this->assertArrayHasKey('access', $result['conflict']);
        $this->assertSame(['default', 'resource'], $result['conflict']['access']['topics']);
        $this->assertArrayNotHasKey('chunk_err_nf', $result['identical']);
        $this->assertArrayNotHasKey('chunk_err_nf', $result['conflict']);
    }

    public function testLoadLexiconTopicsReadsEnglishCoreTopics()
    {
        $path = dirname(__DIR__, 4) . '/core/lexicon/en/';
        $topics = \CheckLexicon::loadLexiconTopics($path);

        $this->assertIsArray($topics);
        $this->assertArrayHasKey('default', $topics);
        $this->assertArrayHasKey('chunk', $topics);
        $this->assertArrayHasKey('chunk', $topics['default']);
        $this->assertSame('Chunk', $topics['default']['chunk']);
    }

    public function testElementTypeLabelsAreNotDuplicatedInTopicFiles()
    {
        $path = dirname(__DIR__, 4) . '/core/lexicon/en/';
        $topics = \CheckLexicon::loadLexiconTopics($path);
        $duplicates = \CheckLexicon::findCrossTopicDuplicates($topics);

        foreach (['chunk', 'chunks', 'snippet', 'snippets', 'plugin', 'plugins', 'template', 'templates'] as $key) {
            $this->assertArrayHasKey($key, $topics['default'], "default must keep {$key}");
            $this->assertArrayNotHasKey(
                $key,
                $duplicates['identical'],
                "{$key} should no longer be an identical cross-topic duplicate"
            );
        }

        $this->assertArrayNotHasKey('chunk', $topics['chunk']);
        $this->assertArrayNotHasKey('chunks', $topics['chunk']);
        $this->assertArrayNotHasKey('snippet', $topics['snippet']);
        $this->assertArrayNotHasKey('snippets', $topics['snippet']);
        $this->assertArrayNotHasKey('plugin', $topics['plugin']);
        $this->assertArrayNotHasKey('plugins', $topics['plugin']);
        $this->assertArrayNotHasKey('template', $topics['template']);
        $this->assertArrayNotHasKey('templates', $topics['template']);
    }
}
