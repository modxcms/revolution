<?php
/**
 * Gets a list of country codes
 *
 * @package modx
 * @subpackage processors.system.country
 */
$_country_lang = array();
include_once $modx->config['core_path'].'lexicon/country/en.inc.php';
if ($modx->config['manager_language'] != 'en' && file_exists($modx->config['core_path'].'lexicon/country/'.$modx->config['manager_language'].'.inc.php')) {
    include_once $modx->config['core_path'].'lexicon/country/'.$modx->config['manager_language'].'.inc.php';
}

$countries = array();
foreach ($_country_lang as $country) {
    $countries[] = array(
        'value' => $country,
    );
}

return $this->outputArray($countries);