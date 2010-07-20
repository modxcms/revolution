<?php
/**
 * Default System Settings for MODx Revolution
 *
 * @package modx
 * @subpackage build
 */
$settings = array();
$settings['allow_tags_in_post']= $xpdo->newObject('modSystemSetting');
$settings['allow_tags_in_post']->fromArray(array (
  'key' => 'allow_tags_in_post',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['allow_multiple_emails']= $xpdo->newObject('modSystemSetting');
$settings['allow_multiple_emails']->fromArray(array (
  'key' => 'allow_multiple_emails',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['auto_menuindex']= $xpdo->newObject('modSystemSetting');
$settings['auto_menuindex']->fromArray(array (
  'key' => 'auto_menuindex',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['auto_check_pkg_updates']= $xpdo->newObject('modSystemSetting');
$settings['auto_check_pkg_updates']->fromArray(array (
  'key' => 'auto_check_pkg_updates',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['auto_check_pkg_updates_cache_expire']= $xpdo->newObject('modSystemSetting');
$settings['auto_check_pkg_updates_cache_expire']->fromArray(array (
  'key' => 'auto_check_pkg_updates_cache_expire',
  'value' => 15,
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['automatic_alias']= $xpdo->newObject('modSystemSetting');
$settings['automatic_alias']->fromArray(array (
  'key' => 'automatic_alias',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['blocked_minutes']= $xpdo->newObject('modSystemSetting');
$settings['blocked_minutes']->fromArray(array (
  'key' => 'blocked_minutes',
  'value' => '60',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['cache_action_map']= $xpdo->newObject('modSystemSetting');
$settings['cache_action_map']->fromArray(array (
  'key' => 'cache_action_map',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_context_settings']= $xpdo->newObject('modSystemSetting');
$settings['cache_context_settings']->fromArray(array (
  'key' => 'cache_context_settings',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_db']= $xpdo->newObject('modSystemSetting');
$settings['cache_db']->fromArray(array (
  'key' => 'cache_db',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_db_expires']= $xpdo->newObject('modSystemSetting');
$settings['cache_db_expires']->fromArray(array (
  'key' => 'cache_db_expires',
  'value' => '0',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_default']= $xpdo->newObject('modSystemSetting');
$settings['cache_default']->fromArray(array (
  'key' => 'cache_default',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_disabled']= $xpdo->newObject('modSystemSetting');
$settings['cache_disabled']->fromArray(array (
  'key' => 'cache_disabled',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_expires']= $xpdo->newObject('modSystemSetting');
$settings['cache_expires']->fromArray(array (
  'key' => 'cache_expires',
  'value' => '0',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_json']= $xpdo->newObject('modSystemSetting');
$settings['cache_json']->fromArray(array (
  'key' => 'cache_json',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_json_expires']= $xpdo->newObject('modSystemSetting');
$settings['cache_json_expires']->fromArray(array (
  'key' => 'cache_json_expires',
  'value' => '0',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_handler']= $xpdo->newObject('modSystemSetting');
$settings['cache_handler']->fromArray(array (
  'key' => 'cache_handler',
  'value' => 'xPDOFileCache',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_lang_js']= $xpdo->newObject('modSystemSetting');
$settings['cache_lang_js']->fromArray(array (
  'key' => 'cache_lang_js',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_lexicon_topics']= $xpdo->newObject('modSystemSetting');
$settings['cache_lexicon_topics']->fromArray(array (
  'key' => 'cache_lexicon_topics',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_noncore_lexicon_topics']= $xpdo->newObject('modSystemSetting');
$settings['cache_noncore_lexicon_topics']->fromArray(array (
  'key' => 'cache_noncore_lexicon_topics',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_resource']= $xpdo->newObject('modSystemSetting');
$settings['cache_resource']->fromArray(array (
  'key' => 'cache_resource',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_resource_expires']= $xpdo->newObject('modSystemSetting');
$settings['cache_resource_expires']->fromArray(array (
  'key' => 'cache_resource_expires',
  'value' => '0',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_scripts']= $xpdo->newObject('modSystemSetting');
$settings['cache_scripts']->fromArray(array (
  'key' => 'cache_scripts',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_system_settings']= $xpdo->newObject('modSystemSetting');
$settings['cache_system_settings']->fromArray(array (
  'key' => 'cache_system_settings',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['compress_css']= $xpdo->newObject('modSystemSetting');
$settings['compress_css']->fromArray(array (
  'key' => 'compress_css',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['compress_js']= $xpdo->newObject('modSystemSetting');
$settings['compress_js']->fromArray(array (
  'key' => 'compress_js',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['concat_js']= $xpdo->newObject('modSystemSetting');
$settings['concat_js']->fromArray(array (
  'key' => 'concat_js',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['container_suffix']= $xpdo->newObject('modSystemSetting');
$settings['container_suffix']->fromArray(array (
  'key' => 'container_suffix',
  'value' => '/',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['cultureKey']= $xpdo->newObject('modSystemSetting');
$settings['cultureKey']->fromArray(array (
  'key' => 'cultureKey',
  'value' => 'en',
  'xtype' => 'modx-combo-language',
  'namespace' => 'core',
  'area' => 'language',
  'editedon' => null,
), '', true, true);
$settings['custom_resource_classes']= $xpdo->newObject('modSystemSetting');
$settings['custom_resource_classes']->fromArray(array (
  'key' => 'custom_resource_classes',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['default_template']= $xpdo->newObject('modSystemSetting');
$settings['default_template']->fromArray(array (
  'key' => 'default_template',
  'value' => '1',
  'xtype' => 'modx-combo-template',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['editor_css_path']= $xpdo->newObject('modSystemSetting');
$settings['editor_css_path']->fromArray(array (
  'key' => 'editor_css_path',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'editor',
  'editedon' => null,
), '', true, true);
$settings['editor_css_selectors']= $xpdo->newObject('modSystemSetting');
$settings['editor_css_selectors']->fromArray(array (
  'key' => 'editor_css_selectors',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'editor',
  'editedon' => null,
), '', true, true);
$settings['emailsender']= $xpdo->newObject('modSystemSetting');
$settings['emailsender']->fromArray(array (
  'key' => 'emailsender',
  'value' => 'email@example.com',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['emailsubject']= $xpdo->newObject('modSystemSetting');
$settings['emailsubject']->fromArray(array (
  'key' => 'emailsubject',
  'value' => 'Your login details',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['error_page']= $xpdo->newObject('modSystemSetting');
$settings['error_page']->fromArray(array (
  'key' => 'error_page',
  'value' => '1',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['failed_login_attempts']= $xpdo->newObject('modSystemSetting');
$settings['failed_login_attempts']->fromArray(array (
  'key' => 'failed_login_attempts',
  'value' => '5',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['fe_editor_lang']= $xpdo->newObject('modSystemSetting');
$settings['fe_editor_lang']->fromArray(array (
  'key' => 'fe_editor_lang',
  'value' => 'en',
  'xtype' => 'modx-combo-language',
  'namespace' => 'core',
  'area' => 'language',
  'editedon' => null,
), '', true, true);
$settings['feed_modx_news']= $xpdo->newObject('modSystemSetting');
$settings['feed_modx_news']->fromArray(array (
  'key' => 'feed_modx_news',
  'value' => 'http://feeds.feedburner.com/modx-announce',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['feed_modx_news_enabled']= $xpdo->newObject('modSystemSetting');
$settings['feed_modx_news_enabled']->fromArray(array (
  'key' => 'feed_modx_news_enabled',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['feed_modx_security']= $xpdo->newObject('modSystemSetting');
$settings['feed_modx_security']->fromArray(array (
  'key' => 'feed_modx_security',
  'value' => 'http://feeds.feedburner.com/modxsecurity',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['feed_modx_security_enabled']= $xpdo->newObject('modSystemSetting');
$settings['feed_modx_security_enabled']->fromArray(array (
  'key' => 'feed_modx_security_enabled',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['filemanager_path']= $xpdo->newObject('modSystemSetting');
$settings['filemanager_path']->fromArray(array (
  'key' => 'filemanager_path',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['forgot_login_email']= $xpdo->newObject('modSystemSetting');
$settings['forgot_login_email']->fromArray(array (
  'key' => 'forgot_login_email',
  'value' => '<p>Hello [[+username]],</p>
<p>A request for a password reset has been issued for your MODx user. If you sent this, you may follow this link and use this password to login. If you did not send this request, please ignore this email.</p>

<p>
    <strong>Activation Link:</strong> [[+url_scheme]][[+http_host]][[+manager_url]]?modahsh=[[+hash]]<br />
    <strong>Username:</strong> [[+username]]<br />
    <strong>Password:</strong> [[+password]]<br />
</p>

<p>After you log into the MODx Manager, you can change your password again, if you wish.</p>

<p>Regards,<br />Site Administrator</p>',
  'xtype' => 'textarea',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_lowercase_only']= $xpdo->newObject('modSystemSetting');
$settings['friendly_alias_lowercase_only']->fromArray(array (
  'key' => 'friendly_alias_lowercase_only',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_max_length']= $xpdo->newObject('modSystemSetting');
$settings['friendly_alias_max_length']->fromArray(array (
  'key' => 'friendly_alias_max_length',
  'value' => '0',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_restrict_chars']= $xpdo->newObject('modSystemSetting');
$settings['friendly_alias_restrict_chars']->fromArray(array (
  'key' => 'friendly_alias_restrict_chars',
  'value' => 'pattern',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_restrict_chars_pattern']= $xpdo->newObject('modSystemSetting');
$settings['friendly_alias_restrict_chars_pattern']->fromArray(array (
  'key' => 'friendly_alias_restrict_chars_pattern',
  'value' => '/[\0\x0B\t\n\r\f\a&=+%#<>"~`@\?\[\]\{\}\|\^\'\\\\]/',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_strip_element_tags']= $xpdo->newObject('modSystemSetting');
$settings['friendly_alias_strip_element_tags']->fromArray(array (
  'key' => 'friendly_alias_strip_element_tags',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_translit']= $xpdo->newObject('modSystemSetting');
$settings['friendly_alias_translit']->fromArray(array (
  'key' => 'friendly_alias_translit',
  'value' => 'none',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_translit_class']= $xpdo->newObject('modSystemSetting');
$settings['friendly_alias_translit_class']->fromArray(array (
  'key' => 'friendly_alias_translit_class',
  'value' => 'translit.modTransliterate',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_trim_chars']= $xpdo->newObject('modSystemSetting');
$settings['friendly_alias_trim_chars']->fromArray(array (
  'key' => 'friendly_alias_trim_chars',
  'value' => '/.-_',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_urls']= $xpdo->newObject('modSystemSetting');
$settings['friendly_alias_urls']->fromArray(array (
  'key' => 'friendly_alias_urls',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_word_delimiter']= $xpdo->newObject('modSystemSetting');
$settings['friendly_alias_word_delimiter']->fromArray(array (
  'key' => 'friendly_alias_word_delimiter',
  'value' => '-',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_word_delimiters']= $xpdo->newObject('modSystemSetting');
$settings['friendly_alias_word_delimiters']->fromArray(array (
  'key' => 'friendly_alias_word_delimiters',
  'value' => '-_',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_urls']= $xpdo->newObject('modSystemSetting');
$settings['friendly_urls']->fromArray(array (
  'key' => 'friendly_urls',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['mail_charset']= $xpdo->newObject('modSystemSetting');
$settings['mail_charset']->fromArray(array (
  'key' => 'mail_charset',
  'value' => 'UTF-8',
  'xtype' => 'modx-combo-charset',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_encoding']= $xpdo->newObject('modSystemSetting');
$settings['mail_encoding']->fromArray(array (
  'key' => 'mail_encoding',
  'value' => '8bit',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_use_smtp']= $xpdo->newObject('modSystemSetting');
$settings['mail_use_smtp']->fromArray(array (
  'key' => 'mail_use_smtp',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_smtp_auth']= $xpdo->newObject('modSystemSetting');
$settings['mail_smtp_auth']->fromArray(array (
  'key' => 'mail_smtp_auth',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_smtp_helo']= $xpdo->newObject('modSystemSetting');
$settings['mail_smtp_helo']->fromArray(array (
  'key' => 'mail_smtp_helo',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_smtp_hosts']= $xpdo->newObject('modSystemSetting');
$settings['mail_smtp_hosts']->fromArray(array (
  'key' => 'mail_smtp_hosts',
  'value' => 'localhost',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_smtp_keepalive']= $xpdo->newObject('modSystemSetting');
$settings['mail_smtp_keepalive']->fromArray(array (
  'key' => 'mail_smtp_keepalive',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_smtp_pass']= $xpdo->newObject('modSystemSetting');
$settings['mail_smtp_pass']->fromArray(array (
  'key' => 'mail_smtp_pass',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_smtp_port']= $xpdo->newObject('modSystemSetting');
$settings['mail_smtp_port']->fromArray(array (
  'key' => 'mail_smtp_port',
  'value' => '587',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_smtp_prefix']= $xpdo->newObject('modSystemSetting');
$settings['mail_smtp_prefix']->fromArray(array (
  'key' => 'mail_smtp_prefix',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_smtp_single_to']= $xpdo->newObject('modSystemSetting');
$settings['mail_smtp_single_to']->fromArray(array (
  'key' => 'mail_smtp_single_to',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_smtp_timeout']= $xpdo->newObject('modSystemSetting');
$settings['mail_smtp_timeout']->fromArray(array (
  'key' => 'mail_smtp_timeout',
  'value' => '10',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_smtp_user']= $xpdo->newObject('modSystemSetting');
$settings['mail_smtp_user']->fromArray(array (
  'key' => 'mail_smtp_user',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['manager_date_format']= $xpdo->newObject('modSystemSetting');
$settings['manager_date_format']->fromArray(array (
  'key' => 'manager_date_format',
  'value' => 'Y-m-d',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['manager_time_format']= $xpdo->newObject('modSystemSetting');
$settings['manager_time_format']->fromArray(array (
  'key' => 'manager_time_format',
  'value' => 'g:i a',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['manager_direction']= $xpdo->newObject('modSystemSetting');
$settings['manager_direction']->fromArray(array (
  'key' => 'manager_direction',
  'value' => 'ltr',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'language',
  'editedon' => null,
), '', true, true);
$settings['manager_lang_attribute']= $xpdo->newObject('modSystemSetting');
$settings['manager_lang_attribute']->fromArray(array (
  'key' => 'manager_lang_attribute',
  'value' => 'en',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'language',
  'editedon' => null,
), '', true, true);
$settings['manager_language']= $xpdo->newObject('modSystemSetting');
$settings['manager_language']->fromArray(array (
  'key' => 'manager_language',
  'value' => 'en',
  'xtype' => 'modx-combo-language',
  'namespace' => 'core',
  'area' => 'language',
  'editedon' => null,
), '', true, true);
$settings['manager_theme']= $xpdo->newObject('modSystemSetting');
$settings['manager_theme']->fromArray(array (
  'key' => 'manager_theme',
  'value' => 'default',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['manager_use_tabs']= $xpdo->newObject('modSystemSetting');
$settings['manager_use_tabs']->fromArray(array (
  'key' => 'manager_use_tabs',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['modx_charset']= $xpdo->newObject('modSystemSetting');
$settings['modx_charset']->fromArray(array (
  'key' => 'modx_charset',
  'value' => 'UTF-8',
  'xtype' => 'modx-combo-charset',
  'namespace' => 'core',
  'area' => 'language',
  'editedon' => null,
), '', true, true);
$settings['proxy_auth_type']= $xpdo->newObject('modSystemSetting');
$settings['proxy_auth_type']->fromArray(array (
  'key' => 'proxy_auth_type',
  'value' => 'BASIC',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'proxy',
  'editedon' => null,
), '', true, true);
$settings['proxy_host']= $xpdo->newObject('modSystemSetting');
$settings['proxy_host']->fromArray(array (
  'key' => 'proxy_host',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'proxy',
  'editedon' => null,
), '', true, true);
$settings['proxy_password']= $xpdo->newObject('modSystemSetting');
$settings['proxy_password']->fromArray(array (
  'key' => 'proxy_password',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'proxy',
  'editedon' => null,
), '', true, true);
$settings['proxy_port']= $xpdo->newObject('modSystemSetting');
$settings['proxy_port']->fromArray(array (
  'key' => 'proxy_port',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'proxy',
  'editedon' => null,
), '', true, true);
$settings['proxy_username']= $xpdo->newObject('modSystemSetting');
$settings['proxy_username']->fromArray(array (
  'key' => 'proxy_username',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'proxy',
  'editedon' => null,
), '', true, true);
$settings['password_generated_length']= $xpdo->newObject('modSystemSetting');
$settings['password_generated_length']->fromArray(array (
  'key' => 'password_generated_length',
  'value' => '8',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['password_min_length']= $xpdo->newObject('modSystemSetting');
$settings['password_min_length']->fromArray(array (
  'key' => 'password_min_length',
  'value' => '8',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);

$settings['phpthumb_cache_maxage']= $xpdo->newObject('modSystemSetting');
$settings['phpthumb_cache_maxage']->fromArray(array (
  'key' => 'phpthumb_cache_maxage',
  'value' => 30, // 30 days
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_cache_maxsize']= $xpdo->newObject('modSystemSetting');
$settings['phpthumb_cache_maxsize']->fromArray(array (
  'key' => 'phpthumb_cache_maxsize',
  'value' => 100 * 1024 * 1024, // 100MB
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_cache_maxfiles']= $xpdo->newObject('modSystemSetting');
$settings['phpthumb_cache_maxfiles']->fromArray(array (
  'key' => 'phpthumb_cache_maxfiles',
  'value' => 10000, // 10k files
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_cache_source_enabled']= $xpdo->newObject('modSystemSetting');
$settings['phpthumb_cache_source_enabled']->fromArray(array (
  'key' => 'phpthumb_cache_source_enabled',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_zoomcrop']= $xpdo->newObject('modSystemSetting');
$settings['phpthumb_zoomcrop']->fromArray(array (
  'key' => 'phpthumb_zoomcrop',
  'value' => '0',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_far']= $xpdo->newObject('modSystemSetting');
$settings['phpthumb_far']->fromArray(array (
  'key' => 'phpthumb_far',
  'value' => 'C',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);

$settings['publish_default']= $xpdo->newObject('modSystemSetting');
$settings['publish_default']->fromArray(array (
  'key' => 'publish_default',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['rb_base_dir']= $xpdo->newObject('modSystemSetting');
$settings['rb_base_dir']->fromArray(array (
  'key' => 'rb_base_dir',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['rb_base_url']= $xpdo->newObject('modSystemSetting');
$settings['rb_base_url']->fromArray(array (
  'key' => 'rb_base_url',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['request_controller']= $xpdo->newObject('modSystemSetting');
$settings['request_controller']->fromArray(array (
  'key' => 'request_controller',
  'value' => 'index.php',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'gateway',
  'editedon' => null,
), '', true, true);
$settings['request_param_alias']= $xpdo->newObject('modSystemSetting');
$settings['request_param_alias']->fromArray(array (
  'key' => 'request_param_alias',
  'value' => 'q',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'gateway',
  'editedon' => null,
), '', true, true);
$settings['request_param_id']= $xpdo->newObject('modSystemSetting');
$settings['request_param_id']->fromArray(array (
  'key' => 'request_param_id',
  'value' => 'id',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'gateway',
  'editedon' => null,
), '', true, true);
$settings['resolve_hostnames']= $xpdo->newObject('modSystemSetting');
$settings['resolve_hostnames']->fromArray(array (
  'key' => 'resolve_hostnames',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['richtext_default']= $xpdo->newObject('modSystemSetting');
$settings['richtext_default']->fromArray(array (
  'key' => 'richtext_default',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['search_default']= $xpdo->newObject('modSystemSetting');
$settings['search_default']->fromArray(array (
  'key' => 'search_default',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['server_offset_time']= $xpdo->newObject('modSystemSetting');
$settings['server_offset_time']->fromArray(array (
  'key' => 'server_offset_time',
  'value' => '0',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['server_protocol']= $xpdo->newObject('modSystemSetting');
$settings['server_protocol']->fromArray(array (
  'key' => 'server_protocol',
  'value' => 'http',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['session_cookie_lifetime']= $xpdo->newObject('modSystemSetting');
$settings['session_cookie_lifetime']->fromArray(array (
  'key' => 'session_cookie_lifetime',
  'value' => '604800',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'session',
  'editedon' => null,
), '', true, true);
$settings['session_cookie_secure']= $xpdo->newObject('modSystemSetting');
$settings['session_cookie_secure']->fromArray(array (
  'key' => 'session_cookie_secure',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'session',
  'editedon' => null,
), '', true, true);
$settings['session_handler_class']= $xpdo->newObject('modSystemSetting');
$settings['session_handler_class']->fromArray(array (
  'key' => 'session_handler_class',
  'value' => 'modSessionHandler',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'session',
  'editedon' => null,
), '', true, true);
$settings['set_header']= $xpdo->newObject('modSystemSetting');
$settings['set_header']->fromArray(array (
  'key' => 'set_header',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['signupemail_message']= $xpdo->newObject('modSystemSetting');
$settings['signupemail_message']->fromArray(array (
  'key' => 'signupemail_message',
  'value' => '<p>Hello [[+uid]],</p>
    <p>Here are your login details for the [[+sname]] MODx Manager:</p>

    <p>
        <strong>Username:</strong> [[+uid]]<br />
        <strong>Password:</strong> [[+pwd]]<br />
    </p>

    <p>Once you log into the MODx Manager at [[+surl]], you can change your password.</p>

    <p>Regards,<br />Site Administrator</p>',
  'xtype' => 'textarea',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['site_name']= $xpdo->newObject('modSystemSetting');
$settings['site_name']->fromArray(array (
  'key' => 'site_name',
  'value' => 'MODx Revolution',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['site_start']= $xpdo->newObject('modSystemSetting');
$settings['site_start']->fromArray(array (
  'key' => 'site_start',
  'value' => '1',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['site_status']= $xpdo->newObject('modSystemSetting');
$settings['site_status']->fromArray(array (
  'key' => 'site_status',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['site_unavailable_message']= $xpdo->newObject('modSystemSetting');
$settings['site_unavailable_message']->fromArray(array (
  'key' => 'site_unavailable_message',
  'value' => 'The site is currently unavailable',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['site_unavailable_page']= $xpdo->newObject('modSystemSetting');
$settings['site_unavailable_page']->fromArray(array (
  'key' => 'site_unavailable_page',
  'value' => '0',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['strip_image_paths']= $xpdo->newObject('modSystemSetting');
$settings['strip_image_paths']->fromArray(array (
  'key' => 'strip_image_paths',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['tree_root_id']= $xpdo->newObject('modSystemSetting');
$settings['tree_root_id']->fromArray(array (
  'key' => 'tree_root_id',
  'value' => '0',
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['udperms_allowroot']= $xpdo->newObject('modSystemSetting');
$settings['udperms_allowroot']->fromArray(array (
  'key' => 'udperms_allowroot',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['unauthorized_page']= $xpdo->newObject('modSystemSetting');
$settings['unauthorized_page']->fromArray(array (
  'key' => 'unauthorized_page',
  'value' => '1',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['upload_files']= $xpdo->newObject('modSystemSetting');
$settings['upload_files']->fromArray(array (
  'key' => 'upload_files',
  'value' => 'txt,php,html,htm,xml,js,css,cache,zip,gz,rar,z,tgz,tar,htaccess,mp3,mp4,aac,wav,au,wmv,avi,mpg,mpeg,pdf,doc,xls,txt',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['upload_flash']= $xpdo->newObject('modSystemSetting');
$settings['upload_flash']->fromArray(array (
  'key' => 'upload_flash',
  'value' => 'swf,fla',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['upload_images']= $xpdo->newObject('modSystemSetting');
$settings['upload_images']->fromArray(array (
  'key' => 'upload_images',
  'value' => 'jpg,jpeg,png,gif,psd,ico,bmp',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['upload_maxsize']= $xpdo->newObject('modSystemSetting');
$settings['upload_maxsize']->fromArray(array (
  'key' => 'upload_maxsize',
  'value' => '1048576',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['upload_media']= $xpdo->newObject('modSystemSetting');
$settings['upload_media']->fromArray(array (
  'key' => 'upload_media',
  'value' => 'mp3,wav,au,wmv,avi,mpg,mpeg',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['use_alias_path']= $xpdo->newObject('modSystemSetting');
$settings['use_alias_path']->fromArray(array (
  'key' => 'use_alias_path',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['use_browser']= $xpdo->newObject('modSystemSetting');
$settings['use_browser']->fromArray(array (
  'key' => 'use_browser',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['use_editor']= $xpdo->newObject('modSystemSetting');
$settings['use_editor']->fromArray(array (
  'key' => 'use_editor',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'editor',
  'editedon' => null,
), '', true, true);
$settings['use_multibyte']= $xpdo->newObject('modSystemSetting');
$settings['use_multibyte']->fromArray(array (
  'key' => 'use_multibyte',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'language',
  'editedon' => null,
), '', true, true);
$settings['webpwdreminder_message']= $xpdo->newObject('modSystemSetting');
$settings['webpwdreminder_message']->fromArray(array (
  'key' => 'webpwdreminder_message',
  'value' => "<p>Hello [[+uid]],</p>

    <p>To activate your new password click the following link:</p>

    <p>[[+surl]]</p>

    <p>If successful you can use the following password to login:</p>

    <p><strong>Password:</strong> [[+pwd]]</p>

    <p>If you did not request this email then please ignore it.</p>

    <p>Regards,<br />
    Site Administrator</p>",
  'xtype' => 'textarea',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['websignupemail_message']= $xpdo->newObject('modSystemSetting');
$settings['websignupemail_message']->fromArray(array (
  'key' => 'websignupemail_message',
  'value' => '<p>Hello [[+uid]],</p>

    <p>Here are your login details for [[+sname]]:</p>

    <p><strong>Username:</strong> [[+uid]]<br />
    <strong>Password:</strong> [[+pwd]]</p>

    <p>Once you log into [[+sname]] at [[+surl]], you can change your password.</p>

    <p>Regards,<br />
    Site Administrator</p>',
  'xtype' => 'textarea',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['welcome_screen']= $xpdo->newObject('modSystemSetting');
$settings['welcome_screen']->fromArray(array (
  'key' => 'welcome_screen',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['welcome_screen_url']= $xpdo->newObject('modSystemSetting');
$settings['welcome_screen_url']->fromArray(array (
  'key' => 'welcome_screen_url',
  'value' => 'http://assets.modxcms.com/revolution/welcome.20.html',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['which_editor']= $xpdo->newObject('modSystemSetting');
$settings['which_editor']->fromArray(array (
  'key' => 'which_editor',
  'value' => '',
  'xtype' => 'modx-combo-rte',
  'namespace' => 'core',
  'area' => 'editor',
  'editedon' => null,
), '', true, true);
$settings['which_element_editor']= $xpdo->newObject('modSystemSetting');
$settings['which_element_editor']->fromArray(array (
  'key' => 'which_element_editor',
  'value' => '',
  'xtype' => 'modx-combo-rte',
  'namespace' => 'core',
  'area' => 'editor',
  'editedon' => null,
), '', true, true);
return $settings;