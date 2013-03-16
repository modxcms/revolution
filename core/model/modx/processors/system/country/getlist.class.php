<?php
/**
 * Gets a list of country codes
 *
 * @package modx
 * @subpackage processors.system.country
 */
class modCountryGetListProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('countries');
    }

    public function process() {
        $countryList = $this->getCountryList();
        if (empty($countryList)) return $this->failure();

        $countries = array();
        foreach ($countryList as $country) {
            $countries[] = array(
                'value' => $country,
            );
        }

        return $this->outputArray($countries);
    }

    public function getCountryList() {
        $_country_lang = array();
        include $this->modx->getOption('core_path').'lexicon/country/en.inc.php';
        if ($this->modx->getOption('manager_language') != 'en' && file_exists($this->modx->getOption('core_path').'lexicon/country/'.$this->modx->getOption('manager_language').'.inc.php')) {
            include_once $this->modx->getOption('core_path').'lexicon/country/'.$this->modx->getOption('manager_language').'.inc.php';
        }
        asort($_country_lang);
        $search = $this->getProperty('query','');
        if (!empty($search)) {
            foreach ($_country_lang as $key => $value) {
                if (!stristr($value, $search)) {
                    unset($_country_lang[$key]);
                }
            }
        }
        return $_country_lang;
    }
}
return 'modCountryGetListProcessor';

