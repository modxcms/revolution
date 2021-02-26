<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System\Country;

use MODX\Revolution\Processors\Processor;

/**
 * Gets a list of country codes
 * @package MODX\Revolution\Processors\System\Country
 */
class GetList extends Processor
{
    /**
     * @return mixed
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('countries');
    }

    /**
     * @return mixed|string
     */
    public function process()
    {
        $countryList = $this->getCountryList();

        $countries = [];
        foreach ($countryList as $iso => $country) {
            $countries[] = [
                'iso' => strtoupper($iso),
                'country' => $country,
                'value' => $country, // Deprecated (available for BC)
            ];
        }

        return $this->outputArray($countries);
    }

    /**
     * @return array
     */
    public function getCountryList()
    {
        $_country_lang = [];
        include $this->modx->getOption('core_path') . 'lexicon/country/en.inc.php';
        $ml = $this->modx->getOption('manager_language', $_SESSION, $this->modx->getOption('cultureKey', null, 'en'));
        if ($ml !== 'en' && file_exists($this->modx->getOption('core_path') . 'lexicon/country/' . $ml . '.inc.php')) {
            include $this->modx->getOption('core_path') . 'lexicon/country/' . $ml . '.inc.php';
        }
        asort($_country_lang);
        $search = $this->getProperty('query', '');
        if (!empty($search)) {
            foreach ($_country_lang as $key => $value) {
                if (false === stripos($value, $search)) {
                    unset($_country_lang[$key]);
                }
            }
            return $_country_lang;
        }
        $iso = $this->getProperty('iso', '');
        if (!empty($iso)) {
            foreach ($_country_lang as $key => $value) {
                if ($key !== strtolower($iso)) {
                    unset($_country_lang[$key]);
                }
            }
            return $_country_lang;
        }
        if ($this->getProperty('combo')) {
            array_unshift($_country_lang, ['']);
        }

        return $_country_lang;
    }
}
