<?php
/**
 * Common upgrade script for 3.0 to clean up files removed since 2.x
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
    'manager' => array(
        'min/README.txt',
        'min/groupsConfig.php',
        'min/ht.access',
        'min/index.php',
        'min/lib/FirePHP.php',
        'min/lib/HTTP/ConditionalGet.php',
        'min/lib/HTTP/Encoder.php',
        'min/lib/JSMin.php',
        'min/lib/JSMinPlus.php',
        'min/lib/Minify.php',
        'min/lib/Minify/Build.php',
        'min/lib/Minify/CSS.php',
        'min/lib/Minify/CSS/Compressor.php',
        'min/lib/Minify/CSS/UriRewriter.php',
        'min/lib/Minify/Cache/APC.php',
        'min/lib/Minify/Cache/File.php',
        'min/lib/Minify/Cache/Memcache.php',
        'min/lib/Minify/Cache/ZendPlatform.php',
        'min/lib/Minify/CommentPreserver.php',
        'min/lib/Minify/Controller/Base.php',
        'min/lib/Minify/Controller/Files.php',
        'min/lib/Minify/Controller/Groups.php',
        'min/lib/Minify/Controller/MinApp.php',
        'min/lib/Minify/Controller/Page.php',
        'min/lib/Minify/Controller/Version1.php',
        'min/lib/Minify/DebugDetector.php',
        'min/lib/Minify/HTML.php',
        'min/lib/Minify/HTML/Helper.php',
        'min/lib/Minify/ImportProcessor.php',
        'min/lib/Minify/JS/ClosureCompiler.php',
        'min/lib/Minify/Lines.php',
        'min/lib/Minify/Logger.php',
        'min/lib/Minify/Packer.php',
        'min/lib/Minify/Source.php',
        'min/lib/Minify/YUI/CssCompressor.java',
        'min/lib/Minify/YUI/CssCompressor.php',
        'min/lib/Minify/YUICompressor.php',
        'min/lib/MrClay/Cli.php',
        'min/lib/MrClay/Cli/Arg.php',
        'min/utils.php',
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
