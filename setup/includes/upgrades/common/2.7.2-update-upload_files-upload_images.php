<?php
/**
 * Common upgrade script for modify upload_files, upload_images System Setting
 *
 * @var modX $modx
 * @package setup
 */

/** @var modSystemSetting $upload_files */
$upload_files = $modx->getObject('modSystemSetting', array('key' => 'upload_files'));
if ($upload_files) {
    $upload_files->set('value', 'txt,html,htm,xml,js,css,zip,gz,rar,z,tgz,tar,mp3,mp4,aac,wav,au,wmv,avi,mpg,mpeg,pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,tiff,svg,svgz,gif,psd,ico,bmp,odt,ods,odp,odb,odg,odf,md,ttf,woff,eot,scss,less,css.map,webp');
    $upload_files->save();
}

/** @var modSystemSetting $upload_images */
$upload_images = $modx->getObject('modSystemSetting', array('key' => 'upload_images'));
if ($upload_images) {
    $upload_images->set('value', 'jpg,jpeg,png,gif,psd,ico,bmp,tiff,svg,svgz,webp');
    $upload_images->save();
}