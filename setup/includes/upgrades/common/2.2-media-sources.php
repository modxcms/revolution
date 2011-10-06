<?php
/**
 * @var modX $modx
 * @package modx
 */

/* add sources.modAccessMediaSource to principal_targets */
/** @var modSystemSetting $setting */
$setting = $modx->getObject('modSystemSetting',array(
    'key' => 'principal_targets',
));
if ($setting) {
    $value = $setting->get('value');
    $value = explode(',',$value);
    $value[] = 'sources.modAccessMediaSource';
    $value = array_unique($value);
    $setting->set('value',implode(',',$value));
    $setting->save();
}

/* upgrade filemanager_* and TV-specific baseUrl/basePath settings into custom sources */
$ct = $modx->getCount('sources.modMediaSource',array(
    'name:!=' => 'Filesystem',
));
if ($ct > 0) {
    $modx->loadClass('sources.modMediaSource');
    /** @var modMediaSource $source */
    $source = modMediaSource::getDefaultSource($modx);

    /* if filemanager_* settings are different as system settings, change the default source value to them */
    /** @var modSystemSetting $fileManagerPath */
    $fileManagerPath = $modx->getObject('modSystemSetting',array(
        'key' => 'filemanager_path',
    ));
    /** @var modSystemSetting $fileManagerUrl */
    $fileManagerUrl = $modx->getObject('modSystemSetting',array(
        'key' => 'filemanager_url',
    ));
    if ((!empty($filemanagerPath) && !empty($fileManagerPath->value)) || (!empty($fileManagerUrl) && !empty($fileManagerUrl->value))) {
        /** @var modSystemSetting $fileManagerPathRelative */
        $fileManagerPathRelative = $modx->getObject('modSystemSetting',array(
            'key' => 'filemanager_path_relative',
        ));
        /** @var modSystemSetting $fileManagerUrlRelative */
        $fileManagerUrlRelative = $modx->getObject('modSystemSetting',array(
            'key' => 'filemanager_url_relative',
        ));
        $properties = $source->getDefaultProperties();
        if (!empty($fileManagerPath)) $properties['basePath']['value'] = $fileManagerPath->value;
        if (!empty($fileManagerPathRelative)) $properties['basePathRelative']['value'] = $fileManagerPathRelative->value;
        if (!empty($fileManagerUrl)) $properties['baseUrl']['value'] = $fileManagerUrl->value;
        if (!empty($fileManagerUrlRelative)) $properties['baseUrlRelative']['value'] = $fileManagerUrlRelative->value;
        $source->setProperties($properties);
        $source->save();
    }
}

/* now loop through TVs, creating new sources for each TV that has a custom base path */
$tvs = $modx->getCollection('modTemplateVar',array(
    'input_properties:!=' => '',
    'input_properties:!=' => 'a:0:{}',
    'type:IN' => array('image','file'),
));
/** @var modTemplateVar $tv */
foreach ($tvs as $tv) {
    $tvProperties = $tv->get('input_properties');
    if (empty($tvProperties['basePath']) && empty($tvProperties['baseUrl'])) continue;

    /** @var modFileMediaSource $sourceExists */
    $sourceExists = $modx->getObject('sources.modFileMediaSource',array(
        'name' => $tv->get('name'),
        'class_key' => 'sources.modFileMediaSource',
    ));
    if (empty($sourceExists)) {
        /** @var modFileMediaSource $newSource */
        $newSource = $modx->newObject('sources.modFileMediaSource');
        $newSource->fromArray(array(
            'name' => $tv->get('name'),
            'description' => '',
            'class_key' => 'sources.modFileMediaSource',
        ));
        $properties = $newSource->getDefaultProperties();
        $properties['basePath']['value'] = $tvProperties['basePath'];
        $properties['basePathRelative']['value'] = $tvProperties['basePathRelative'];
        $properties['baseUrl']['value'] = $tvProperties['baseUrl'];
        $properties['baseUrlRelative']['value'] = $tvProperties['baseUrlRelative'];
        $newSource->setProperties($properties);
        $newSource->save();
    }
}