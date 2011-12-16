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

$langs = array();
$path = dirname(dirname(__FILE__)).'/lang/';
/** @var DirectoryIterator $file */
foreach (new DirectoryIterator($path) as $file) {
    $basename = $file->getFilename();
	if (!in_array($basename, array('.', '..','.htaccess','.svn','.git')) && $file->isDir()) {
		if (file_exists($file->getPathname().'/default.inc.php')) {
			$langs[] = $basename;
		}
	}
}
sort($langs);
$parser->set('langs', $langs);
unset($path,$file,$handle);

$actualLanguage = 'en';
if (!empty($_COOKIE['modx_setup_language']) && ($_COOKIE['modx_setup_language'] != 'en')) {
    $actualLanguage = $_COOKIE['modx_setup_language'];
}
$languages = '';
foreach ($langs as $language) {
    $languages .= '<option value="'.$language.'"'
        .($language == $actualLanguage ? ' selected="selected"' : '')
        .'>' . $language . '</option>' . "\n";
}
$parser->set('languages',$languages);

if (!empty($_REQUEST['restarted'])) {
    $parser->set('restarted',true);
}

return $parser->render('language.tpl');
