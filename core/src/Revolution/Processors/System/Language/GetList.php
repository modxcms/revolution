<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Language;

use MODX\Revolution\Processors\Processor;

/**
 * Grabs a list of lexicon languages
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults to 10.
 * @package MODX\Revolution\Processors\System\Language
 */
class GetList extends Processor
{
    /**
     * @return mixed
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('languages');
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
            'limit' => 0,
            'namespace' => 'core',
        ]);
        return true;
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $data = $this->getData();
        if (empty($data)) {
            return $this->failure();
        }

        /* loop through */
        $list = [];
        foreach ($data['results'] as $language) {
            $list[] = [
                'name' => $language,
            ];
        }

        return $this->outputArray($list, $data['total']);
    }

    /**
     * Get a collection of languages
     * @return array
     */
    public function getData()
    {
        $data = [];

        $limit = $this->getProperty('limit');

        $data['results'] = $this->modx->lexicon->getLanguageList($this->getProperty('namespace'));
        $data['total'] = count($data['results']);

        // this allows for typeahead filtering in the lexicon topics combobox
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $data['results'] = preg_grep('/' . $query . '/i', $data['results']);
            $data['total'] = count($data['results']);
        }

        if ($limit > 0) {
            $data['results'] = array_slice($data['results'], $this->getProperty('start'), $limit, true);
        }

        return $data;
    }
}
