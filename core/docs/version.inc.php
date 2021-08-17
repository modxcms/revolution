<?php

$f_version = 4; // Change this when needed new version for MODX 3S

$v = [];
$v['version']        = '3'; // Current version.
$v['major_version']  = '0'; // Current major version.
$v['minor_version']  = '0'; // Current minor version.
$v['patch_level']    = 's' . $f_version; // Patch level for display in SiteDash
$v['code_name']      = 'Revolution'; // Current codename.
$v['distro']         = '@git@';
$v['full_version']   = $v['version'] . ($v['major_version'] ? ".{$v['major_version']}" : ".0") . ($v['minor_version'] ? ".{$v['minor_version']}" : ".0") . ($v['patch_level'] ? "-{$v['patch_level']}" : "");
$v['full_appname']   = 'MODX' . ($v['code_name'] ? " {$v['code_name']} " : " ") . $v['full_version'] . ' (' . trim($v['distro'], '@') . ')';
$v['modx3s_version'] = 'MODX 3S ' . $f_version; // Version for MODX3s - Sterc fork

return $v;
