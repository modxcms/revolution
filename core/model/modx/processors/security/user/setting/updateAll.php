<?php
/**
 * @package modx
 * @subpackage processors.security.user.setting
 * @deprecated
 */
$modx->lexicon->load('user');

if (!$modx->hasPermission('save_user')) return $modx->error->failure($modx->lexicon('permission_denied'));

/* array of post values to ignore in this function */
$ignore = array (
	'id',
	'oldusername',
	'oldemail',
	'newusername',
	'fullname',
	'newpassword',
	'password',
	'passwordgenmethod',
	'passwordnotifymethod',
	'specifiedpassword',
	'confirmpassword',
	'email',
	'phone',
	'mobilephone',
	'fax',
	'dob',
	'country',
	'state',
	'zip',
	'gender',
	'photo',
	'comment',
	'role',
	'failedlogincount',
	'blocked',
	'blockeduntil',
	'blockedafter',
	'user_groups',
	'mode',
	'blockedmode',
	'stay',
	'save',
	'theme_refresher'
);

// determine which settings can be saved blank (based on 'default_{settingname}' POST checkbox values)
$allowBlanks = array (
	'upload_images',
	'upload_media',
	'upload_flash',
	'upload_files'
);

// get user setting field names
$settings= array ();
foreach ($_POST as $n => $v) {
	if (!in_array($n, $ignore))
		$settings[] = $n;
}

$exclude= array ();
foreach ($allowBlanks as $k) {
	if (isset ($_POST["default_{$k}"]) && $_POST["default_{$k}"] == '1') {
		$exclude[] = $k;
	}
	unset ($_POST["default_{$k}"]);
}

$usersettings = $modx->getCollection('modUserSetting',array('user' => $user->id));
foreach ($usersettings as $us) {
	if (!$us->remove()) return $modx->error->failure($modx->lexicon('user_setting_err_remove'));
}

for ($i = 0; $i < count($settings); $i++) {
	if (in_array($settings[$i],$exclude)) continue;
	$name = $settings[$i];
	$value = $_POST[$name];
	if (is_array($value))
		$value = implode(',',$value);
	if (trim($value) != '' || in_array($name, $allowBlanks)) {
		$us = $modx->newObject('modUserSetting');
		$us->set('user',$user->id);
		$us->set('setting_name',$name);
		$us->set('setting_value',$value);
		if (!$us->save()) return $modx->error->failure($modx->lexicon('user_setting_err_save'));

	}
}

return $modx->error->success();