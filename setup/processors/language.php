<?php
/**
 * @package setup
 */
$language= 'en';
if (isset ($_REQUEST['language'])) {
    $language= $_REQUEST['language'];
}
setcookie('modx_setup_language', $language, 0, dirname(dirname($_SERVER['REQUEST_URI'])) . '/');
$this->error->success('welcome');
