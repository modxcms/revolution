<?php
/**
 * @package setup
 */
/* parse language selection */
if (!empty($_POST['proceed'])) {
    $language= 'en';
    if (isset ($_REQUEST['language'])) {
        $language= $_REQUEST['language'];
    }
    setcookie('modx_setup_language', $language, 0, dirname(dirname($_SERVER['REQUEST_URI'])) . '/');
    unset($_POST['proceed']);

    $settings = $install->getConfig();
    $settings = array_merge($settings,$_POST);
    $install->settings->store($settings);
    $this->proceed('welcome');
}

$install->settings->erase();

$langs = array();
$path = dirname(dirname(__FILE__)).'/lang/';
if ($handle = dir($path)) {
	while (false !== ($file = $handle->read())) {
		if (!in_array($file, array('.', '..','.htaccess','.svn')) && is_dir($path.$file)) {
			if (file_exists($path.$file.'/default.inc.php')) {
				$langs[] = $file;
			}
		}
	}
	@closedir($handle);
}
sort($langs);
$this->parser->assign('langs', $langs);
unset($path,$file,$handle);

$languages = '';
foreach ($langs as $language) {
    $languages .= '<option value="'.$language.'"'
        .($language == 'en' ? ' selected="selected"' : '')
        .'>' . $language . '</option>' . "\n";
}
$this->parser->assign('languages',$languages);

return $this->parser->fetch('language.tpl');