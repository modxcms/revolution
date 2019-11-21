<?php
/** Sets the default_media_source_type system setting to a correct class */

use MODX\Revolution\modSystemSetting;

$defaultMediaSourceType = $modx->getObject(modSystemSetting::class, [
  'key' => 'default_media_source_type'
]);

if ($defaultMediaSourceType) {
    switch($defaultMediaSourceType->get('value')) {
        case 'sources.modFileMediaSource':
            $defaultMediaSourceType->set('value', 'MODX\Revolution\Sources\modFileMediaSource');
            break;
        case 'sources.modS3MediaSource':
        $defaultMediaSourceType->set('value', 'MODX\Revolution\Sources\modS3MediaSource');
            break;
    }

    $defaultMediaSourceType->save();
}
