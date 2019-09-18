<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * @var modInstall $install
 * @var modInstallParser $parser
 * @var modInstallRequest $this
 * @package setup
 */
if ($install->isLocked()) {
    return $parser->render('locked.tpl');
}

/* parse language selection */
if (!empty($_POST['proceed'])) {
    $language = 'en';

    $langPath = preg_replace('#[/\\\\]$#', '', dirname(__DIR__) . '/lang/');
    if (isset($_REQUEST['language']) && is_dir($langPath . '/' . $_REQUEST['language'])) {
        $language = $_REQUEST['language'];
    }

    $cookiePath = preg_replace('#[/\\\\]$#', '', dirname($_SERVER['REQUEST_URI'], 2));
    setcookie('modx_setup_language', $language, 0, $cookiePath . '/');

    unset($_POST['proceed']);

    $settings = $install->request->getConfig();
    $settings = array_merge($settings, $_POST);
    $install->settings->store($settings);
    $this->proceed('welcome');
}

$install->settings->erase();

$languages = [];
$install->lexicon->load('languages');
$install->lexicon->load('en:languages');
foreach ($install->lexicon->getLanguageList() as $language) {
    $languages[$language] = [
        'code' => $language,
        'name' => $install->lexicon->get('language_' . $language),
        'native' => ''
    ];
}

// load native language names if exists
array_walk($languages, function(&$row) use($install) {
    if ($install->lexicon->load($row['code'] . ':languages')) {
        $row['native'] = $install->lexicon->get('language_' . $row['code']);
    }
    return $row;
});

$current = $install->lexicon->getLanguage();

$popular = ['en', 'de', 'nl', 'ru', 'fr', 'it', 'es'];
$exists = array_search($current, $popular, true);
array_unshift($popular, $exists ? $popular[$exists] : $current);
$popular = array_slice(array_unique($popular), 0, 7);

foreach ($popular as $key => $code) {
    unset($popular[$key]);
    $popular[$code] = $languages[$code];
}

$parser->set('popular', $popular);
$parser->set('others', array_diff_key($languages, $popular));
$parser->set('current', $current);
$parser->set('restarted', !empty($_REQUEST['restarted']));

return $parser->render('language.tpl');
