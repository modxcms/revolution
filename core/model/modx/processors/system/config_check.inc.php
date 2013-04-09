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
    $config_check_results = '<h4>' . $modx->lexicon('configcheck_notok') . '</h4><ul>';

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
            default :
                $warnings[$i][1] = $modx->lexicon('configcheck_default_msg');
        }

        $config_check_results .= '<li class="fakefieldset">
                            <p><strong>' . $modx->lexicon('configcheck_warning') . '</strong> ' . $warnings[$i][0] . '</p>
                            <p><em>' . $modx->lexicon('configcheck_what') . '</em></p>
                            <p>' . $warnings[$i][1] . ' </p>
                            </li>';
        if ($i != count($warnings) - 1) {
            $config_check_results .= '<br />';
        }
        $config_check_results .= '</ul>';
    }
    return false;
} else {
    $config_check_results = $modx->lexicon('configcheck_ok');
    return true;
}
return true;
