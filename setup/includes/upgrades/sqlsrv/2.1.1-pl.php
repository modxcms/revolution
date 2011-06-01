<?php
/**
 * Specific upgrades for Revolution 2.1.1-pl
 *
 * @package setup
 * @subpackage upgrades
 */
/* handle new class creation */
$classes = array(
);
if (!empty($classes)) {
    $this->createTable($classes);
}
unset($classes);

/* change certain settings xtypes to password */
$setting = $modx->getObject('modSystemSetting',array('key' => 'mail_smtp_pass'));
if ($setting) {
    $setting->set('xtype','text-password');
    $setting->save();
}
$setting = $modx->getObject('modSystemSetting',array('key' => 'proxy_password'));
if ($setting) {
    $setting->set('xtype','text-password');
    $setting->save();
}