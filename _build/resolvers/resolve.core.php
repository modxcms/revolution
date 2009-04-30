<?php
$success= false;
if ($cacheManager= $transport->xpdo->getCacheManager()) {
    if ($targetFile= @ eval($fileMeta['target'])) {
        $configContent= "<?php\n"
            . "define('MODX_CORE_PATH', '" . MODX_CORE_PATH . "');\n"
            . "define('MODX_CONFIG_KEY', '" . MODX_CONFIG_KEY . "');\n"
            . "?>";
        $written= $cacheManager->writeFile($targetFile, $configContent);
        $success= $written !== false ? true : false;
    }
}
return $success;
?>