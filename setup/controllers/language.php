<?php
/**
 * @var modInstall $install
 * @var modInstallParser $parser
 * @var modInstallRequest $this
 * @package setup
 */
/* parse language selection */
if (!empty($_POST['proceed'])) {
    $language= 'en';
    if (isset ($_REQUEST['language'])) {
        $language= $_REQUEST['language'];
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
