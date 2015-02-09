<?php
$classes = array(
    'modAccessNamespace',
);

if (!empty($classes)) {
    $this->createTable($classes);
}

/** @var modSystemSetting $setting */
$setting = $modx->getObject('modSystemSetting',array(
    'key' => 'principal_targets',
));
if ($setting) {
    $value = $setting->get('value');
    $value = explode(',',$value);
    $value[] = 'modAccessNamespace';
    $value = array_unique($value);
    $setting->set('value',implode(',',$value));
    $setting->save();
}