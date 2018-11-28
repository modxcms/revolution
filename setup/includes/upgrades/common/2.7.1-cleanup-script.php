<?php
/**
 * Common upgrade script for 2.7.1 to clean up legacy files for download distributions.
 *
 * @var modX
 *
 * @package setup
 */
$paths = array(
    'assets' => $modx->getOption('assets_path', null, MODX_ASSETS_PATH),
    'base' => $modx->getOption('base_path', null, MODX_BASE_PATH),
    'connectors' => $modx->getOption('connectors_path', null, MODX_CONNECTORS_PATH),
    'core' => $modx->getOption('core_path', null, MODX_CORE_PATH),
    'manager' => $modx->getOption('manager_path', null, MODX_MANAGER_PATH),
    'processors' => $modx->getOption('processors_path', null, MODX_PROCESSORS_PATH),
);

$cleanup = array(
    'assets' => array(),
    'base' => array(),
    'connectors' => array(),
    'core' => array(),
    'manager' => array(),
    'processors' => array(
        'system/config.js.php',
    ),
);

$removedFiles = 0;
$removedDirs = 0;

if (!function_exists('recursiveRemoveDir')) {
    function recursiveRemoveDir($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? recursiveRemoveDir("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }
}

// Loop through legacy files/folders to clean up
foreach ($cleanup as $folder => $files) {
    foreach ($files as $file) {
        $legacyFile = $paths[$folder].$file;
        if (file_exists($legacyFile) === true) {
            if (is_dir($legacyFile) === true) {
                // Remove legacy directory
                recursiveRemoveDir($legacyFile);
                ++$removedDirs;
            } else {
                // Remove legacy file
                unlink($legacyFile);
                ++$removedFiles;
            }
        }
    }
}

$this->runner->addResult(
    modInstallRunner::RESULT_SUCCESS,
    '<p class="ok">'.$this->install->lexicon('legacy_cleanup_complete').
    '<br /><small>'.$this->install->lexicon('legacy_cleanup_count', array('files' => $removedFiles, 'folders' => $removedDirs)).'</small></p>'
);
