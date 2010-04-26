<?php
/**
 * @package setup
 */
$output= '';
$mode= isset ($_POST['installmode']) ? intval($_POST['installmode']) : modInstall::MODE_NEW;
if (function_exists('json_encode') && isset ($_REQUEST['search']) && $_REQUEST['search']) {
    $id= 0;
    $results= array();
    $searchString= $_REQUEST['search'];
    $dirname= strtr(realpath(dirname($searchString)), '\\', '/') . '/';
    $filepart= basename($searchString);
    if ($handle= @ opendir($dirname)) {
        while (false !== ($file= @ readdir($handle))) {
            if ($file != '.' && $file != '..') { /* Ignore . and .. */
                /* if ($filepart && strpos($file, $filepart) === false) continue; */
                $path= $dirname . $file;
                if (is_dir($path) && (!$filepart || strpos($file, $filepart) !== false)) {
                    $results[]= array (
                        'id' => $id++,
                        'value' => $path . '/',
                    );
                }
            }
        }
        $output= json_encode($results);
    }
}
if (empty ($output) || $output == '[]') {
    $output= "[]";
}
header('Content-Type:text/javascript');
echo '{"success": true, "results":' . $output . '}';
exit();
