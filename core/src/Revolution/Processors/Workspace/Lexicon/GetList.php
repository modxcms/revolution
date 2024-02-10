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

/**
 * Gets a list of lexicon entries
 * @param string $namespace (optional) If set, will filter by namespace. Defaults to core.
 * @param integer $topic (optional) If set, will filter by this topic
 * @param string $language (optional) If set, will filter by language. Defaults to en.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @package MODX\Revolution\Processors\Workspace\Lexicon
 */
class GetList extends Processor
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
     * @return bool
     */
    public function initialize()
    {
        $this->setDefaultProperties([
            'start' => 0,
            'limit' => 10,
            'sort' => 'name',
            'dir' => 'ASC',
            'language' => 'en',
            'namespace' => 'core',
            'topic' => 'default',
        ]);
        if ($this->getProperty('language') === '') {
            $this->setProperty('language', 'en');
        }
        if ($this->getProperty('namespace') === '') {
            $this->setProperty('namespace', 'core');
        }
        if ($this->getProperty('topic') === '') {
            $this->setProperty('topic', 'default');
        }
        return true;
    }

    /**
     * @return mixed|string
     */
    public function process()
    {
        $where = [
            'namespace' => $this->getProperty('namespace'),
            'topic' => $this->getProperty('topic'),
            'language' => $this->getProperty('language'),
        ];

        $search = $this->getProperty('search');
        if (!empty($search)) {
            $where[] = [
                'name:LIKE' => '%' . $search . '%',
                'OR:value:LIKE' => '%' . $search . '%',
            ];
        }

        /* setup query for db based lexicons */
        $c = $this->modx->newQuery(modLexiconEntry::class);
        $c->where($where);
        $c->sortby('name');
        $results = $this->modx->getCollection(modLexiconEntry::class, $c);
        $dbEntries = [];
        /** @var modLexiconEntry $r */
        foreach ($results as $r) {
            $dbEntries[$r->get('name')] = $r->toArray();
        }

        /* first get file-based lexicon */
        $entries = $this->modx->lexicon->getFileTopic($this->getProperty('language'), $this->getProperty('namespace'),
            $this->getProperty('topic'));
        $entries = is_array($entries) ? $entries : [];

        /* if searching */
        if (!empty($search)) {
            function parseArray($needle, array $haystack = [])
            {
                if (!is_array($haystack)) {
                    return false;
                }
                $results = [];
                foreach ($haystack as $key => $value) {
                    if (strpos($key, $needle) !== false || strpos($value, $needle) !== false) {
                        $results[$key] = $value;
                    }
                }
                return $results;
            }

            $entries = parseArray($search, $entries);
        }

        /* add in unique entries */
        $es = array_diff(array_keys($dbEntries), array_keys($entries));
        foreach ($es as $n) {
            $entries[$n] = $dbEntries[$n]['value'];
        }
        $count = count($entries);
        ksort($entries);
        $entries = array_slice($entries, $this->getProperty('start'), $this->getProperty('limit'), true);

        /* loop through */
        $list = [];
        foreach ($entries as $name => $value) {
            $entryArray = [
                'name' => $name,
                'value' => $value,
                'namespace' => $this->getProperty('namespace'),
                'topic' => $this->getProperty('topic'),
                'language' => $this->getProperty('language'),
                'createdon' => null,
                'editedon' => null,
                'overridden' => 0,
            ];
            /* if override in db, load */
            if (array_key_exists($name, $dbEntries)) {
                foreach ($dbEntries[$name] as $k => $v) {
                    $entryArray[$k] = $v; // array_merge very slow inside loop, don't use it here
                }

                $entryArray['editedon'] = strtotime($entryArray['editedon']) ?: strtotime($entryArray['createdon']);
                $entryArray['overridden'] = 1;
            }
            $list[] = $entryArray;
        }

        return $this->outputArray($list, $count);
    }
}
