<?php
/**
 * Update the xtype's for system settings
 */

$xtypeSettingsMap = [
    [
        'key' => 'auto_check_pkg_updates_cache_expire',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'blocked_minutes',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'cache_db_expires',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'cache_db_session_lifetime',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'cache_expires',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'cache_format',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'cache_resource_expires',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'debug',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'error_page',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'failed_login_attempts',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'friendly_alias_max_length',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'log_level',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'lock_ttl',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'mail_smtp_port',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'mail_smtp_timeout',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'manager_js_cache_max_age',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'manager_week_start',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'proxy_port',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'password_generated_length',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'password_min_length',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'phpthumb_cache_maxage',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'phpthumb_cache_maxsize',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'phpthumb_cache_maxfiles',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'phpthumb_error_fontsize',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'server_offset_time',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'session_cookie_lifetime',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'site_start',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'site_unavailable_page',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'unauthorized_page',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
    [
        'key' => 'upload_maxsize',
        'old_xtype' => 'textfield',
        'new_xtype' => 'numberfield',
    ],
];

$messageTemplate = '<p class="%s">%s</p>';

foreach ($xtypeSettingsMap as $setting) {
    /** @var modSystemSetting $systemSetting */
    $systemSetting = $modx->getObject('modSystemSetting', [
        'key' => $setting['key'],
        'xtype' => $setting['old_xtype']
    ]);
    if ($systemSetting instanceof modSystemSetting) {
        $systemSetting->set('xtype', $setting['new_xtype']);
        if ($systemSetting->save()) {
            $this->runner->addResult(modInstallRunner::RESULT_SUCCESS,
                sprintf($messageTemplate, 'ok', $this->install->lexicon('system_setting_update_xtype_success', $setting)));
        } else {
            $this->runner->addResult(modInstallRunner::RESULT_ERROR,
                sprintf($messageTemplate, 'error', $this->install->lexicon('system_setting_update_xtype_failure', $setting)));
        }
    }
}
