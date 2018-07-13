<?php
if (!isset($argv[1])) {
    echo 'ERROR: No zip file specified.';
    return 255;
}

$zipFileName = realpath($argv[1]);
if (!file_exists($zipFileName) || !is_readable($zipFileName)) {
    echo 'ERROR: Specified file does not exist or is not readable.';
    return 255;
}
if (!is_writable($zipFileName)) {
    echo 'ERROR: Specified file is not writable.';
    return 255;
}

$zip = new ZipArchive();
$zip->open($zipFileName);

for ($i = 0; $i < $zip->count(); $i++) {
    $zip->setExternalAttributesIndex($i, 0, 0);
}

$zip->close();

return 0;
