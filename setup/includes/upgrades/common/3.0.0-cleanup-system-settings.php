<?php
/**
 * Remove obsolete system settings about compression
 */

/** @var modSystemSetting $compress_js_max_files */
$compress_js_max_files = $modx->getObject('modSystemSetting', [
    'key' => 'compress_js_max_files'
]);
if ($compress_js_max_files) {
    $compress_js_max_files->remove();
}

/** @var modSystemSetting $manager_js_zlib_output_compression */
$manager_js_zlib_output_compression = $modx->getObject('modSystemSetting', [
    'key' => 'manager_js_zlib_output_compression'
]);
if ($manager_js_zlib_output_compression) {
    $manager_js_zlib_output_compression->remove();
}
