<?php
/**
 * Runs a config check
 *
 * @package modx
 * @subpackage processors.system
 */
$warningspresent = false;
$warnings = array();

if (is_writable(MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php')) {
    /* Warn if world writable */
    if (@ fileperms(MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php') & 0x0002) {
        $warningspresent = true;
        $warnings[] = array($modx->lexicon('configcheck_configinc'));
    }
}

if (is_dir(MODX_BASE_PATH.'setup/')) {
    $warningspresent = true;
    $warnings[] = array($modx->lexicon('configcheck_installer'));
}

$cachePath= $modx->getCachePath();
if (!is_writable($cachePath)) {
    $warningspresent = true;
    $warnings[] = array (
        $modx->lexicon('configcheck_cache')
    );
}

if (@ ini_get('register_globals') == true) {
    $warningspresent = true;
    $warnings[] = array (
        $modx->lexicon('configcheck_register_globals')
    );
}

$unapage = $modx->getObject('modResource',$modx->config['unauthorized_page']);
if ($unapage == null || $unapage->get('published') == 0) {
    $warningspresent = true;
    $warnings[] = array (
        $modx->lexicon('configcheck_unauthorizedpage_unpublished')
    );
}

$errpage = $modx->getObject('modResource',$modx->config['error_page']);
if ($errpage == null || $errpage->get('published') == 0) {
    $warningspresent = true;
    $warnings[] = array (
        $modx->lexicon('configcheck_errorpage_unpublished')
    );
}

if ($unapage == null || $unapage->get('privateweb') == 1) {
    $warningspresent = true;
    $warnings[] = array (
        $modx->lexicon('configcheck_unauthorizedpage_unavailable')
    );
}

if ($errpage == null || $errpage->get('privateweb') == 1) {
    $warningspresent = true;
    $warnings[] = array (
        $modx->lexicon('configcheck_errorpage_unavailable')
    );
}

/* clear file info cache */
clearstatcache();
if ($warningspresent === true) {

    $config_check_results = '<h4>' . $modx->lexicon('configcheck_notok') . '</h4>';

    for ($i = 0; $i < count($warnings); $i++) {
        switch ($warnings[$i][0]) {
            case $modx->lexicon('configcheck_configinc');
                $warnings[$i][1] = $modx->lexicon('configcheck_configinc_msg');
                if (isset ($_SESSION['mgrConfigCheck']) && !$_SESSION['mgrConfigCheck'])
                    $modx->logEvent(0, 2, $warnings[$i][1], $modx->lexicon('configcheck_configinc'));
                break;
            case $modx->lexicon('configcheck_installer') :
                $warnings[$i][1] = $modx->lexicon('configcheck_installer_msg');
                if (isset ($_SESSION['mgrConfigCheck']) && !$_SESSION['mgrConfigCheck'])
                    $modx->logEvent(0, 2, $warnings[$i][1], $modx->lexicon('configcheck_installer'));
                break;
            case $modx->lexicon('configcheck_cache') :
                $warnings[$i][1] = $modx->lexicon('configcheck_cache_msg');
                if (isset ($_SESSION['mgrConfigCheck']) && !$_SESSION['mgrConfigCheck'])
                    $modx->logEvent(0, 2, $warnings[$i][1], $modx->lexicon('configcheck_cache'));
                break;
                break;
            case $modx->lexicon('configcheck_lang_difference') :
                $warnings[$i][1] = $modx->lexicon('configcheck_lang_difference_msg');
                break;
            case $modx->lexicon('configcheck_register_globals') :
                $warnings[$i][1] = $modx->lexicon('configcheck_register_globals_msg');
                break;
            case $modx->lexicon('configcheck_unauthorizedpage_unpublished') :
                $warnings[$i][1] = $modx->lexicon('configcheck_unauthorizedpage_unpublished_msg');
                break;
            case $modx->lexicon('configcheck_errorpage_unpublished') :
                $warnings[$i][1] = $modx->lexicon('configcheck_errorpage_unpublished_msg');
                break;
            case $modx->lexicon('configcheck_unauthorizedpage_unavailable') :
                $warnings[$i][1] = $modx->lexicon('configcheck_unauthorizedpage_unavailable_msg');
                break;
            case $modx->lexicon('configcheck_errorpage_unavailable') :
                $warnings[$i][1] = $modx->lexicon('configcheck_errorpage_unavailable_msg');
                break;
            default :
                $warnings[$i][1] = $modx->lexicon('configcheck_default_msg');
        }

        $admin_warning = $_SESSION['mgrRole'] != 1 ? $modx->lexicon('configcheck_admin') : '';
        $config_check_results .= '
                            <div class="fakefieldset">
                            <p><strong>' . $modx->lexicon('configcheck_warning') . '</strong> ' . $warnings[$i][0] . '</p>
                            <p style="padding-left:1em"><em>' . $modx->lexicon('configcheck_what') . '</em><br />
                            ' . $warnings[$i][1] . ' ' . $admin_warning . '</p>
                            </div>
                    ';
        if ($i != count($warnings) - 1) {
            $config_check_results .= '<br />';
        }
    }
    $_SESSION['mgrConfigCheck'] = true;
} else {
    $config_check_results = $modx->lexicon('configcheck_ok');
}