<?php
$v= array ();
$v['version']= '2'; // Current version.
$v['major_version']= '0'; // Current major version.
$v['minor_version']= '0'; // Current minor version.
$v['patch_level']= 'rc-2'; // Current patch level.
$v['code_name']= 'Revolution'; // Current codename.
$v['full_version']= $v['version'] . ($v['major_version'] ? ".{$v['major_version']}" : ".0") . ($v['minor_version'] ? ".{$v['minor_version']}" : ".0") . ($v['patch_level'] ? "-{$v['patch_level']}" : "");
$v['full_appname']= 'MODx' . ($v['code_name'] ? " {$v['code_name']} " : " ") . $v['full_version'];
return $v;
?>