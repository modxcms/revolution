<?php
/**
 * Gets a list of country codes
 *
 * @package modx
 * @subpackage processors.system.country
 */
if (!$modx->hasPermission('countries')) return $modx->error->failure($modx->lexicon('permission_denied'));

$_country_lang = array();
include $modx->getOption('core_path').'lexicon/country/en.inc.php';
if ($modx->getOption('manager_language') != 'en' && file_exists($modx->getOption('core_path').'lexicon/country/'.$modx->getOption('manager_language').'.inc.php')) {
    include_once $modx->getOption('core_path').'lexicon/country/'.$modx->getOption('manager_language').'.inc.php';
}
asort($_country_lang);

$countries = array();
foreach ($_country_lang as $country) {
    $countries[] = array(
        'value' => $country,
    );
}

return $this->outputArray($countries);