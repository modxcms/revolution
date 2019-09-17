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

    $cookiePath = preg_replace('#[/\\\\]$#', '', dirname(dirname($_SERVER['REQUEST_URI'])));
    setcookie('modx_setup_language', $language, 0, $cookiePath . '/');

    unset($_POST['proceed']);
    $settings = $install->request->getConfig();
    $settings = array_merge($settings,$_POST);
    $install->settings->store($settings);
    $this->proceed('welcome');
}

$install->settings->erase();

$langs = $install->lexicon->getLanguageList();
$parser->set('langs', $langs);

$actualLanguage = $install->lexicon->getLanguage();
$languages = '';
foreach ($langs as $language) {
    $languages .= '<option value="'.$language.'"'
        .($language == $actualLanguage ? ' selected="selected"' : '')
        .'>' . $language . '</option>' . "\n";
}
$parser->set('languages',$languages);

$parser->set('restarted', !empty($_REQUEST['restarted']));

return $parser->render('language.tpl');
