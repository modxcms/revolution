<?php
$success= false;
switch (MODX_SETUP_KEY) {
    case '@traditional':
        $success= true;
        break;
    default:
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
}
return $success;
?>