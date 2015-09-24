<?php

/**
 * Common upgrade script for 2.4 User Profile country name to ISO code conversion
 *
 * @var modX $modx
 * @package setup
 */
$list = $modx->runProcessor('system/country/getlist');
$countries = $modx->fromJSON($list->getResponse());
$updateCount = 0;

foreach ($countries['results'] as $country) {
    // Ensure valid ISO code exists for conversion (e.g skip numeric legacy keys)
    if (is_int($country['iso']) === false) {
        $updateCount += $modx->updateCollection(
            'modUserProfile',
            array('country' => strtoupper($country['iso'])),
            array('country' => $country['country'])
        );
    }
};

$this->runner->addResult(
    modInstallRunner::RESULT_SUCCESS,
    '<p class="ok">'.$this->install->lexicon('iso_country_code_converted').
    '<br /><small>'.$updateCount.' '.strtolower($modx->lexicon('updated')).'</small></p>'
);
