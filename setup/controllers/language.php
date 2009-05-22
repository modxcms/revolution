<?php
/**
 * @package setup
 */
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
	closedir($handle);
}
sort($langs);
$this->parser->assign('langs', $langs);
unset($path,$file,$handle);

$navbar= '
<p class="title">'.$install->lexicon['choose_language'].':
<select name="language">
';
foreach ($langs as $language) {
    $navbar .= '<option value="'.$language.'"'
        .($language == 'en' ? ' selected="selected"' : '')
        .'>' . $language . '</option>' . "\n";
}
$navbar .= '</select></p>
<button name="cmdnext" onclick="return doAction(\'language\');">'.$install->lexicon['select'].'</button>
';
$this->parser->assign('navbar', $navbar);

$this->parser->display('language.tpl');