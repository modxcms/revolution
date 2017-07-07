<?php
/**
 * Runs a config check
 *
 * @package modx
 * @subpackage processors.system
 */
$warnings = array();

if (!function_exists('ini_get_bool')) {
    /**
     * Return a proper boolean from an ini_get value.
     *
     * @param string $key The ini setting key to check.
     * @return bool TRUE if the ini_get value evaluates to a positive boolean value for the specified key.
     */
    function ini_get_bool($key) {
        $value = ini_get($key);

        switch (strtolower($value)) {
            case 'on':
            case 'yes':
            case 'true':
                $value = 'assert.active' !== $key;
                break;
            case 'stdout':
            case 'stderr':
                $value = 'display_errors' === $key;
                break;
            default:
                $value = (int) $value;
                break;
        }
        return (bool) $value;
    }
}

/* bypasses policy check to check for absolute existence */
function getResourceBypass(modX &$modx,$criteria) {
    $resource = null;
    $c = $modx->newQuery('modResource');
    $c->select(array('id','published'));
    $c->where($criteria);
    $c->prepare();
    $sql = $c->toSql();

    $stmt = $modx->query($sql);
    if ($stmt && $stmt instanceof PDOStatement) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $resource = $row;
        }
        $stmt->closeCursor();
    }
    return $resource;
}

/* Check if the core folder is still web accessible */
$real_base = realpath( MODX_BASE_PATH );
$real_core = realpath( MODX_CORE_PATH );

$core_name = ltrim(str_replace( $real_base, '', $real_core ), '/');

if (substr( $real_core, 0,  strlen($real_base)) == $real_base) {
    $modx->log(modX::LOG_LEVEL_INFO, "[configcheck] core has not been moved outside web root!");

    /* currently only checking one file. But it may be a good e.g. to also check more sensitive files */
    $checkFiles = array(
        $core_name.'/docs/changelog.txt',
        $core_name.'/cache/logs/error.log'
    );

    $checkUrl = MODX_SITE_URL . $checkFiles[0];

    /* Check if the core folder (content) is accessible via web */
    if ( function_exists( 'curl_init' )) {
        /* try to call curl with minimal footprint - we don't want to wait everytime we access the dashboard */
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HEADER, false);
        /* returntransfer must be true */
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        /* use small timeouts: we are on localhost somehow */
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, 1000);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS, 1000);
        /* do not verify ssl - we are not interested in this and
            verifying would cause errors e.g. for self-signed certs
        */
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($curl, CURLOPT_VERBOSE, false);

        /* follow rewrites, e.g. http to https rewrites */
        /* if a sites rewrites the checkurl to 200 page, this method fails.
            if open_basedir is in effect, this will throw a warning in the log */

        $safeMode = @ini_get('safe_mode');
        $openBasedir = @ini_get('open_basedir');
        //$modx->log(modX::LOG_LEVEL_DEBUG, "[configcheck] open_basedir:'".$openBasedir."', safe_mode:'".$safeMode."'");

        if (empty($safeMode) && empty($openBasedir)) {
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_REDIR_PROTOCOLS, CURLPROTO_HTTP + CURLPROTO_HTTPS);
        } else {
            $modx->log(modX::LOG_LEVEL_DEBUG, "[configcheck] open_basedir restriction in effect. May not follow redirects.");
            /* TODO: implement manual redirect algo here */
        }

        /* do not download anything */
        curl_setopt($curl, CURLOPT_RANGE, '0-0');
        curl_setopt($curl, CURLOPT_URL, $checkUrl);

        //$modx->log(modX::LOG_LEVEL_DEBUG, "[configcheck] trying to access core file: ".$checkUrl);
        $curl_result = curl_exec($curl);

        if (!curl_errno($curl)) {
            $ch_info = curl_getinfo($curl);
            $http_code = $ch_info['http_code'];

            $modx->log(modX::LOG_LEVEL_DEBUG, "[configcheck] access check http return code: ".$http_code);

            /* check the response code */
            if (($http_code >= 200 && $http_code <= 210)) {
                $warnings[] = array($modx->lexicon('configcheck_htaccess'));
                $modx->log(modX::LOG_LEVEL_WARN, "[configcheck] Core folder is accessible by web!");
            } else {
                $modx->log(modX::LOG_LEVEL_DEBUG,"[configcheck] Core folder is not accessible by web this time.");
            }
        } else {
            /* do not log an error if curl error was 28/timeout - in fact that means core is not accessible too. */
            if (curl_errno($curl) != 28) {
                $modx->log(modX::LOG_LEVEL_WARN, "[configcheck] check core lockdown curl err: " . curl_errno($curl) . ": " . curl_error($curl));
            }
        }
        curl_close($curl);
    } else {
        /*
            TODO: should we warn, if we cannot use curl and therefore fail to make this test?
            - use of socket solution
        */
    }
} else {
    $modx->log(MODX_LOG_LEVEL_INFO, "[configcheck] core moved and considered as save.");
}

/* check php version */
$phprequire = $modx->getOption('configcheck_min_phpversion', null, '5.3');
$compare = version_compare( phpversion(), $phprequire );
if ($compare == -1) {
    $warnings[] = array( $modx->lexicon('configcheck_phpversion' ) );
}

if (is_writable($modx->getOption('core_path',null,MODX_CORE_PATH).'config/'.MODX_CONFIG_KEY.'.inc.php')) {
    /* Warn if world writable */
    if (@ fileperms($modx->getOption('core_path').'config/'.MODX_CONFIG_KEY.'.inc.php') & 0x0002) {
        $warnings[] = array($modx->lexicon('configcheck_configinc'));
    }
}

if (is_dir($modx->getOption('base_path',null,MODX_BASE_PATH).'setup/')) {
    $warnings[] = array($modx->lexicon('configcheck_installer'));
}

$cachePath= $modx->getCachePath();
if (!is_writable($cachePath)) {
    $warnings[] = array (
        $modx->lexicon('configcheck_cache')
    );
}

/* changed to use ini_get_bool function, which can handle yes, 1 and true values */
if (@ini_get_bool('register_globals') == true) {
    $warnings[] = array (
        $modx->lexicon('configcheck_register_globals')
    );
}

$unapage = getResourceBypass($modx,$modx->getOption('unauthorized_page',null,1));
if ($unapage == null || !is_array($unapage)) {
    $warnings[] = array (
        $modx->lexicon('configcheck_unauthorizedpage_unavailable')
    );
} else if ($unapage['published'] == 0) {
    $warnings[] = array (
        $modx->lexicon('configcheck_unauthorizedpage_unpublished')
    );
}

$errpage = getResourceBypass($modx,$modx->getOption('error_page',null,1));
if ($errpage == null || !is_array($errpage)) {
    $warnings[] = array (
        $modx->lexicon('configcheck_errorpage_unavailable')
    );
} else if ($errpage['published'] == 0) {
    $warnings[] = array (
        $modx->lexicon('configcheck_errorpage_unpublished')
    );
}

/* warn if allow_tags_in_post is true in System Settings OR Context Settings other than mgr */
$allowTagsInPostSystem = $modx->getCount('modSystemSetting', array('key' => 'allow_tags_in_post', 'value' => '1'));
if ($allowTagsInPostSystem > 0) {
    $warnings[] = array(
        $modx->lexicon('configcheck_allowtagsinpost_system_enabled')
    );
}
$allowTagsInPostContext = $modx->getCount('modContextSetting', array('context_key:!=' => 'mgr', 'key' => 'allow_tags_in_post', 'value' => '1'));
if ($allowTagsInPostContext > 0) {
    $warnings[] = array(
        $modx->lexicon('configcheck_allowtagsinpost_context_enabled')
    );
}

/* clear file info cache */
clearstatcache();
if (!empty($warnings)) {
    $config_check_results = '<h4>' . $modx->lexicon('configcheck_notok') . '</h4><ul class="configcheck">';

    for ($i = 0; $i < count($warnings); $i++) {
        switch ($warnings[$i][0]) {
            case $modx->lexicon('configcheck_configinc');
                $warnings[$i][1] = $modx->lexicon('configcheck_configinc_msg',array(
                    'path' => $modx->getOption('core_path',null,MODX_CORE_PATH).'config/'.MODX_CONFIG_KEY.'.inc.php',
                ));
                break;
            case $modx->lexicon('configcheck_installer') :
                $warnings[$i][1] = $modx->lexicon('configcheck_installer_msg',array(
                    'path' => $modx->getOption('base_path',null,MODX_BASE_PATH).'setup/',
                ));
                break;
            case $modx->lexicon('configcheck_cache') :
                $warnings[$i][1] = $modx->lexicon('configcheck_cache_msg');
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
            case $modx->lexicon('configcheck_allowtagsinpost_context_enabled') :
                $warnings[$i][1] = $modx->lexicon('configcheck_allowtagsinpost_context_enabled_msg');
                break;
            case $modx->lexicon('configcheck_allowtagsinpost_system_enabled') :
                $warnings[$i][1] = $modx->lexicon('configcheck_allowtagsinpost_system_enabled_msg');
                break;
            case $modx->lexicon('configcheck_phpversion') :
                $warnings[$i][1] = $modx->lexicon( 'configcheck_phpversion_msg', array(
                    'phpversion' => phpversion(),
                    'phprequired' => $phprequire
                ));
                break;
            case $modx->lexicon('configcheck_htaccess') :
                $warnings[$i][1] = $modx->lexicon( 'configcheck_htaccess_msg', array(
                    'checkUrl' => MODX_SITE_URL . $core_name . '/docs/changelog.txt',   // this is the real path which was checked before
                    'fileLocation' => MODX_CORE_PATH
                ));
                break;
            default :
                $warnings[$i][1] = $modx->lexicon('configcheck_default_msg');
        }

        $config_check_results .= '<li class="">
                            <h5 class="warn">' . $warnings[$i][0] . '</h5>
                            <p><i class="icon icon-info-circle"></i> ' . $warnings[$i][1] . ' </p>
                            </li>';
    }
    $config_check_results .= '</ul>';
    return false;
} else {
    $config_check_results = $modx->lexicon('configcheck_ok');
    return true;
}
