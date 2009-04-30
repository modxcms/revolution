<?php
/**
 * @package setup
 */
$output= '';
$mode= isset ($_POST['installmode']) ? intval($_POST['installmode']) : MODX_INSTALL_MODE_NEW;
/* validate database settings */
require_once MODX_CORE_PATH . 'xpdo/xpdo.class.php';
if (isset ($_REQUEST['search']) && $_REQUEST['search']) {
    $id= 0;
    $results= array();
    $searchString= $_REQUEST['search'];
    $filepart= '';
    if ($searchString{strlen($searchString)-1} != '/') {
        $filepart= basename(trim($searchString, '/'));
        $searchString= strtr(dirname($searchString), '\\', '/');
    } else {
        $searchString= strtr($searchString, '\\', '/');
    }
    $searchString= trim($searchString, '/');
    $docroot= $_SERVER['DOCUMENT_ROOT'];
    $dirname= $docroot . '/' . $searchString;

    if ($handle= @ opendir($dirname)) {
        while (false !== ($file= @ readdir($handle))) {
            if ($file != '.' && $file != '..') { /* Ignore . and .. */
                $path= $dirname . '/' . $file;
                $result= '/' . trim(str_replace($docroot, '', $path), '/') . '/';
                if (is_dir($path) && (!$filepart || (strpos($file, $filepart) === 0))) {
                    $results[]= array (
                        'id' => $result,
                        'value' => $result
                    );
                }
            }
        }
        $output= xPDO :: toJSON($results);
    }
}
if (empty ($output) || $output == '[]') {
    $output= "[]";
}
header('Content-Type:text/javascript');
echo '{"success": true, "results":' . $output . '}';
exit();

$this->error->success($output);