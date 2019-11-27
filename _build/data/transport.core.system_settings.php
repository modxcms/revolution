<?php
/**
 * Default System Settings for MODX Revolution
 *
 * @package modx
 * @subpackage build
 */

use MODX\Revolution\modSessionHandler;
use MODX\Revolution\modSystemSetting;

$settings = array();
$settings['access_category_enabled']= $xpdo->newObject(modSystemSetting::class);
$settings['access_category_enabled']->fromArray(array (
  'key' => 'access_category_enabled',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['access_context_enabled']= $xpdo->newObject(modSystemSetting::class);
$settings['access_context_enabled']->fromArray(array (
  'key' => 'access_context_enabled',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['access_resource_group_enabled']= $xpdo->newObject(modSystemSetting::class);
$settings['access_resource_group_enabled']->fromArray(array (
  'key' => 'access_resource_group_enabled',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['allow_forward_across_contexts']= $xpdo->newObject(modSystemSetting::class);
$settings['allow_forward_across_contexts']->fromArray(array (
  'key' => 'allow_forward_across_contexts',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['allow_manager_login_forgot_password']= $xpdo->newObject(modSystemSetting::class);
$settings['allow_manager_login_forgot_password']->fromArray(array (
  'key' => 'allow_manager_login_forgot_password',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['allow_multiple_emails']= $xpdo->newObject(modSystemSetting::class);
$settings['allow_multiple_emails']->fromArray(array (
  'key' => 'allow_multiple_emails',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['allow_tags_in_post']= $xpdo->newObject(modSystemSetting::class);
$settings['allow_tags_in_post']->fromArray(array (
  'key' => 'allow_tags_in_post',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['archive_with']= $xpdo->newObject(modSystemSetting::class);
$settings['archive_with']->fromArray(array (
  'key' => 'archive_with',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['auto_menuindex']= $xpdo->newObject(modSystemSetting::class);
$settings['auto_menuindex']->fromArray(array (
  'key' => 'auto_menuindex',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['auto_check_pkg_updates']= $xpdo->newObject(modSystemSetting::class);
$settings['auto_check_pkg_updates']->fromArray(array (
  'key' => 'auto_check_pkg_updates',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['auto_check_pkg_updates_cache_expire']= $xpdo->newObject(modSystemSetting::class);
$settings['auto_check_pkg_updates_cache_expire']->fromArray(array (
  'key' => 'auto_check_pkg_updates_cache_expire',
  'value' => 15,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['automatic_alias']= $xpdo->newObject(modSystemSetting::class);
$settings['automatic_alias']->fromArray(array (
  'key' => 'automatic_alias',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['automatic_template_assignment']= $xpdo->newObject(modSystemSetting::class);
$settings['automatic_template_assignment']->fromArray(array (
    'key' => 'automatic_template_assignment',
    'value' => 'sibling',
    'xtype' => 'textfield',
    'namespace' => 'core',
    'area' => 'site',
    'editedon' => null,
), '', true, true);
$settings['base_help_url']= $xpdo->newObject(modSystemSetting::class);
$settings['base_help_url']->fromArray(array (
  'key' => 'base_help_url',
  'value' => '//docs.modx.com/display/revolution20/',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['blocked_minutes']= $xpdo->newObject(modSystemSetting::class);
$settings['blocked_minutes']->fromArray(array (
  'key' => 'blocked_minutes',
  'value' => 60,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['cache_action_map']= $xpdo->newObject(modSystemSetting::class);
$settings['cache_action_map']->fromArray(array (
  'key' => 'cache_action_map',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_alias_map']= $xpdo->newObject(modSystemSetting::class);
$settings['cache_alias_map']->fromArray(array (
    'key' => 'cache_alias_map',
    'value' => true,
    'xtype' => 'combo-boolean',
    'namespace' => 'core',
    'area' => 'caching',
    'editedon' => null,
), '', true, true);
$settings['use_context_resource_table']= $xpdo->newObject(modSystemSetting::class);
$settings['use_context_resource_table']->fromArray(array (
    'key' => 'use_context_resource_table',
    'value' => true,
    'xtype' => 'combo-boolean',
    'namespace' => 'core',
    'area' => 'caching',
    'editedon' => null,
), '', true, true);
$settings['cache_context_settings']= $xpdo->newObject(modSystemSetting::class);
$settings['cache_context_settings']->fromArray(array (
  'key' => 'cache_context_settings',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_db']= $xpdo->newObject(modSystemSetting::class);
$settings['cache_db']->fromArray(array (
  'key' => 'cache_db',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_db_expires']= $xpdo->newObject(modSystemSetting::class);
$settings['cache_db_expires']->fromArray(array (
  'key' => 'cache_db_expires',
  'value' => 0,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_db_session']= $xpdo->newObject(modSystemSetting::class);
$settings['cache_db_session']->fromArray(array (
  'key' => 'cache_db_session',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_db_session_lifetime']= $xpdo->newObject(modSystemSetting::class);
$settings['cache_db_session_lifetime']->fromArray(array (
  'key' => 'cache_db_session_lifetime',
  'value' => '',
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_default']= $xpdo->newObject(modSystemSetting::class);
$settings['cache_default']->fromArray(array (
  'key' => 'cache_default',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_expires']= $xpdo->newObject(modSystemSetting::class);
$settings['cache_expires']->fromArray(array (
  'key' => 'cache_expires',
  'value' => 0,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_format']= $xpdo->newObject(modSystemSetting::class);
$settings['cache_format']->fromArray(array (
  'key' => 'cache_format',
  'value' => 0,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_handler']= $xpdo->newObject(modSystemSetting::class);
$settings['cache_handler']->fromArray(array (
  'key' => 'cache_handler',
  'value' => 'xPDO\Cache\xPDOFileCache',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_lang_js']= $xpdo->newObject(modSystemSetting::class);
$settings['cache_lang_js']->fromArray(array (
  'key' => 'cache_lang_js',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_lexicon_topics']= $xpdo->newObject(modSystemSetting::class);
$settings['cache_lexicon_topics']->fromArray(array (
  'key' => 'cache_lexicon_topics',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_noncore_lexicon_topics']= $xpdo->newObject(modSystemSetting::class);
$settings['cache_noncore_lexicon_topics']->fromArray(array (
  'key' => 'cache_noncore_lexicon_topics',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_resource']= $xpdo->newObject(modSystemSetting::class);
$settings['cache_resource']->fromArray(array (
  'key' => 'cache_resource',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_resource_expires']= $xpdo->newObject(modSystemSetting::class);
$settings['cache_resource_expires']->fromArray(array (
  'key' => 'cache_resource_expires',
  'value' => 0,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['cache_resource_clear_partial']= $xpdo->newObject(modSystemSetting::class);
$settings['cache_resource_clear_partial']->fromArray(array (
    'key' => 'cache_resource_clear_partial',
    'value' => false,
    'xtype' => 'combo-boolean',
    'namespace' => 'core',
    'area' => 'caching',
    'editedon' => null,
), '', true, true);
$settings['cache_scripts']= $xpdo->newObject(modSystemSetting::class);
$settings['cache_scripts']->fromArray(array (
  'key' => 'cache_scripts',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['clear_cache_refresh_trees']= $xpdo->newObject(modSystemSetting::class);
$settings['clear_cache_refresh_trees']->fromArray(array (
  'key' => 'clear_cache_refresh_trees',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => null,
), '', true, true);
$settings['compress_css']= $xpdo->newObject(modSystemSetting::class);
$settings['compress_css']->fromArray(array (
  'key' => 'compress_css',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['compress_js']= $xpdo->newObject(modSystemSetting::class);
$settings['compress_js']->fromArray(array (
  'key' => 'compress_js',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['confirm_navigation']= $xpdo->newObject(modSystemSetting::class);
$settings['confirm_navigation']->fromArray(array (
  'key' => 'confirm_navigation',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['container_suffix']= $xpdo->newObject(modSystemSetting::class);
$settings['container_suffix']->fromArray(array (
  'key' => 'container_suffix',
  'value' => '/',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['context_tree_sort']= $xpdo->newObject(modSystemSetting::class);
$settings['context_tree_sort']->fromArray(array (
  'key' => 'context_tree_sort',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['context_tree_sortby']= $xpdo->newObject(modSystemSetting::class);
$settings['context_tree_sortby']->fromArray(array (
  'key' => 'context_tree_sortby',
  'value' => 'rank',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['context_tree_sortdir']= $xpdo->newObject(modSystemSetting::class);
$settings['context_tree_sortdir']->fromArray(array (
  'key' => 'context_tree_sortdir',
  'value' => 'ASC',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['cultureKey']= $xpdo->newObject(modSystemSetting::class);
$settings['cultureKey']->fromArray(array (
  'key' => 'cultureKey',
  'value' => 'en',
  'xtype' => 'modx-combo-language',
  'namespace' => 'core',
  'area' => 'language',
  'editedon' => null,
), '', true, true);
$settings['date_timezone']= $xpdo->newObject(modSystemSetting::class);
$settings['date_timezone']->fromArray(array (
  'key' => 'date_timezone',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['debug']= $xpdo->newObject(modSystemSetting::class);
$settings['debug']->fromArray(array (
  'key' => 'debug',
  'value' => '',
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['default_duplicate_publish_option']= $xpdo->newObject(modSystemSetting::class);
$settings['default_duplicate_publish_option']->fromArray(array (
  'key' => 'default_duplicate_publish_option',
  'value' => 'preserve',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['default_media_source']= $xpdo->newObject(modSystemSetting::class);
$settings['default_media_source']->fromArray(array (
  'key' => 'default_media_source',
  'value' => 1,
  'xtype' => 'modx-combo-source',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['default_media_source_type']= $xpdo->newObject(modSystemSetting::class);
$settings['default_media_source_type']->fromArray(array (
    'key' => 'default_media_source_type',
    'value' => MODX\Revolution\Sources\modFileMediaSource::class,
    'xtype' => 'modx-combo-source-type',
    'namespace' => 'core',
    'area' => 'manager',
    'editedon' => null,
), '', true, true);
$settings['default_per_page']= $xpdo->newObject(modSystemSetting::class);
$settings['default_per_page']->fromArray(array (
  'key' => 'default_per_page',
  'value' => '20',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['default_context']= $xpdo->newObject(modSystemSetting::class);
$settings['default_context']->fromArray(array (
  'key' => 'default_context',
  'value' => 'web',
  'xtype' => 'modx-combo-context',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['default_template']= $xpdo->newObject(modSystemSetting::class);
$settings['default_template']->fromArray(array (
  'key' => 'default_template',
  'value' => 1,
  'xtype' => 'modx-combo-template',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['default_content_type']= $xpdo->newObject(modSystemSetting::class);
$settings['default_content_type']->fromArray(array (
  'key' => 'default_content_type',
  'value' => 1,
  'xtype' => 'modx-combo-content-type',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['editor_css_path']= $xpdo->newObject(modSystemSetting::class);
$settings['editor_css_path']->fromArray(array (
  'key' => 'editor_css_path',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'editor',
  'editedon' => null,
), '', true, true);
$settings['editor_css_selectors']= $xpdo->newObject(modSystemSetting::class);
$settings['editor_css_selectors']->fromArray(array (
  'key' => 'editor_css_selectors',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'editor',
  'editedon' => null,
), '', true, true);
$settings['emailsender']= $xpdo->newObject(modSystemSetting::class);
$settings['emailsender']->fromArray(array (
  'key' => 'emailsender',
  'value' => 'email@example.com',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['enable_dragdrop']= $xpdo->newObject(modSystemSetting::class);
$settings['enable_dragdrop']->fromArray(array (
  'key' => 'enable_dragdrop',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['error_page']= $xpdo->newObject(modSystemSetting::class);
$settings['error_page']->fromArray(array (
  'key' => 'error_page',
  'value' => 1,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['failed_login_attempts']= $xpdo->newObject(modSystemSetting::class);
$settings['failed_login_attempts']->fromArray(array (
  'key' => 'failed_login_attempts',
  'value' => 5,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['fe_editor_lang']= $xpdo->newObject(modSystemSetting::class);
$settings['fe_editor_lang']->fromArray(array (
  'key' => 'fe_editor_lang',
  'value' => 'en',
  'xtype' => 'modx-combo-language',
  'namespace' => 'core',
  'area' => 'language',
  'editedon' => null,
), '', true, true);
$settings['feed_modx_news']= $xpdo->newObject(modSystemSetting::class);
$settings['feed_modx_news']->fromArray(array (
  'key' => 'feed_modx_news',
  'value' => 'https://feeds.feedburner.com/modx-announce',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['feed_modx_news_enabled']= $xpdo->newObject(modSystemSetting::class);
$settings['feed_modx_news_enabled']->fromArray(array (
  'key' => 'feed_modx_news_enabled',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['feed_modx_security']= $xpdo->newObject(modSystemSetting::class);
$settings['feed_modx_security']->fromArray(array (
  'key' => 'feed_modx_security',
  'value' => 'https://forums.modx.com/board.xml?board=294',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['feed_modx_security_enabled']= $xpdo->newObject(modSystemSetting::class);
$settings['feed_modx_security_enabled']->fromArray(array (
  'key' => 'feed_modx_security_enabled',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['filemanager_path']= $xpdo->newObject(modSystemSetting::class);
$settings['filemanager_path']->fromArray(array (
  'key' => 'filemanager_path',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['filemanager_path_relative']= $xpdo->newObject(modSystemSetting::class);
$settings['filemanager_path_relative']->fromArray(array (
  'key' => 'filemanager_path_relative',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['filemanager_url']= $xpdo->newObject(modSystemSetting::class);
$settings['filemanager_url']->fromArray(array (
  'key' => 'filemanager_url',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['filemanager_url_relative']= $xpdo->newObject(modSystemSetting::class);
$settings['filemanager_url_relative']->fromArray(array (
  'key' => 'filemanager_url_relative',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['form_customization_use_all_groups']= $xpdo->newObject(modSystemSetting::class);
$settings['form_customization_use_all_groups']->fromArray(array (
  'key' => 'form_customization_use_all_groups',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['forward_merge_excludes']= $xpdo->newObject(modSystemSetting::class);
$settings['forward_merge_excludes']->fromArray(array (
  'key' => 'forward_merge_excludes',
  'value' => 'type,published,class_key',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_lowercase_only']= $xpdo->newObject(modSystemSetting::class);
$settings['friendly_alias_lowercase_only']->fromArray(array (
  'key' => 'friendly_alias_lowercase_only',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_max_length']= $xpdo->newObject(modSystemSetting::class);
$settings['friendly_alias_max_length']->fromArray(array (
  'key' => 'friendly_alias_max_length',
  'value' => 0,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_realtime']= $xpdo->newObject(modSystemSetting::class);
$settings['friendly_alias_realtime']->fromArray(array (
  'key' => 'friendly_alias_realtime',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_restrict_chars']= $xpdo->newObject(modSystemSetting::class);
$settings['friendly_alias_restrict_chars']->fromArray(array (
  'key' => 'friendly_alias_restrict_chars',
  'value' => 'pattern',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_restrict_chars_pattern']= $xpdo->newObject(modSystemSetting::class);
$settings['friendly_alias_restrict_chars_pattern']->fromArray(array (
  'key' => 'friendly_alias_restrict_chars_pattern',
  'value' => '/[\0\x0B\t\n\r\f\a&=+%#<>"~:`@\?\[\]\{\}\|\^\'\\\\]/',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_strip_element_tags']= $xpdo->newObject(modSystemSetting::class);
$settings['friendly_alias_strip_element_tags']->fromArray(array (
  'key' => 'friendly_alias_strip_element_tags',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_translit']= $xpdo->newObject(modSystemSetting::class);
$settings['friendly_alias_translit']->fromArray(array (
  'key' => 'friendly_alias_translit',
  'value' => 'none',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_translit_class']= $xpdo->newObject(modSystemSetting::class);
$settings['friendly_alias_translit_class']->fromArray(array (
  'key' => 'friendly_alias_translit_class',
  'value' => 'translit.modTransliterate',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_translit_class_path']= $xpdo->newObject(modSystemSetting::class);
$settings['friendly_alias_translit_class_path']->fromArray(array (
  'key' => 'friendly_alias_translit_class_path',
  'value' => '{core_path}components/',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_trim_chars']= $xpdo->newObject(modSystemSetting::class);
$settings['friendly_alias_trim_chars']->fromArray(array (
  'key' => 'friendly_alias_trim_chars',
  'value' => '/.-_',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_word_delimiter']= $xpdo->newObject(modSystemSetting::class);
$settings['friendly_alias_word_delimiter']->fromArray(array (
  'key' => 'friendly_alias_word_delimiter',
  'value' => '-',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_alias_word_delimiters']= $xpdo->newObject(modSystemSetting::class);
$settings['friendly_alias_word_delimiters']->fromArray(array (
  'key' => 'friendly_alias_word_delimiters',
  'value' => '-_',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_urls']= $xpdo->newObject(modSystemSetting::class);
$settings['friendly_urls']->fromArray(array (
  'key' => 'friendly_urls',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['friendly_urls_strict']= $xpdo->newObject(modSystemSetting::class);
$settings['friendly_urls_strict']->fromArray(array (
  'key' => 'friendly_urls_strict',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['use_frozen_parent_uris']= $xpdo->newObject(modSystemSetting::class);
$settings['use_frozen_parent_uris']->fromArray(array (
  'key' => 'use_frozen_parent_uris',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['global_duplicate_uri_check']= $xpdo->newObject(modSystemSetting::class);
$settings['global_duplicate_uri_check']->fromArray(array (
  'key' => 'global_duplicate_uri_check',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['hidemenu_default']= $xpdo->newObject(modSystemSetting::class);
$settings['hidemenu_default']->fromArray(array (
  'key' => 'hidemenu_default',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['inline_help']= $xpdo->newObject(modSystemSetting::class);
$settings['inline_help']->fromArray(array (
  'key' => 'inline_help',
  'value' => 1,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['locale']= $xpdo->newObject(modSystemSetting::class);
$settings['locale']->fromArray(array (
  'key' => 'locale',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'language',
  'editedon' => null,
), '', true, true);
$settings['log_level']= $xpdo->newObject(modSystemSetting::class);
$settings['log_level']->fromArray(array (
  'key' => 'log_level',
  'value' => 1,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['log_target']= $xpdo->newObject(modSystemSetting::class);
$settings['log_target']->fromArray(array (
  'key' => 'log_target',
  'value' => 'FILE',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['log_deprecated']= $xpdo->newObject(modSystemSetting::class);
$settings['log_deprecated']->fromArray(array (
  'key' => 'log_deprecated',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['link_tag_scheme']= $xpdo->newObject(modSystemSetting::class);
$settings['link_tag_scheme']->fromArray(array (
  'key' => 'link_tag_scheme',
  'value' => -1,
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['lock_ttl']= $xpdo->newObject(modSystemSetting::class);
$settings['lock_ttl']->fromArray(array (
  'key' => 'lock_ttl',
  'value' => 360,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['mail_charset']= $xpdo->newObject(modSystemSetting::class);
$settings['mail_charset']->fromArray(array (
  'key' => 'mail_charset',
  'value' => 'UTF-8',
  'xtype' => 'modx-combo-charset',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_encoding']= $xpdo->newObject(modSystemSetting::class);
$settings['mail_encoding']->fromArray(array (
  'key' => 'mail_encoding',
  'value' => '8bit',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_use_smtp']= $xpdo->newObject(modSystemSetting::class);
$settings['mail_use_smtp']->fromArray(array (
  'key' => 'mail_use_smtp',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_smtp_auth']= $xpdo->newObject(modSystemSetting::class);
$settings['mail_smtp_auth']->fromArray(array (
  'key' => 'mail_smtp_auth',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_smtp_helo']= $xpdo->newObject(modSystemSetting::class);
$settings['mail_smtp_helo']->fromArray(array (
  'key' => 'mail_smtp_helo',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_smtp_hosts']= $xpdo->newObject(modSystemSetting::class);
$settings['mail_smtp_hosts']->fromArray(array (
  'key' => 'mail_smtp_hosts',
  'value' => 'localhost',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_smtp_keepalive']= $xpdo->newObject(modSystemSetting::class);
$settings['mail_smtp_keepalive']->fromArray(array (
  'key' => 'mail_smtp_keepalive',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_smtp_pass']= $xpdo->newObject(modSystemSetting::class);
$settings['mail_smtp_pass']->fromArray(array (
  'key' => 'mail_smtp_pass',
  'value' => '',
  'xtype' => 'text-password',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_smtp_port']= $xpdo->newObject(modSystemSetting::class);
$settings['mail_smtp_port']->fromArray(array (
  'key' => 'mail_smtp_port',
  'value' => 587,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_smtp_prefix']= $xpdo->newObject(modSystemSetting::class);
$settings['mail_smtp_prefix']->fromArray(array (
  'key' => 'mail_smtp_prefix',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_smtp_single_to']= $xpdo->newObject(modSystemSetting::class);
$settings['mail_smtp_single_to']->fromArray(array (
  'key' => 'mail_smtp_single_to',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_smtp_timeout']= $xpdo->newObject(modSystemSetting::class);
$settings['mail_smtp_timeout']->fromArray(array (
  'key' => 'mail_smtp_timeout',
  'value' => 10,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['mail_smtp_user']= $xpdo->newObject(modSystemSetting::class);
$settings['mail_smtp_user']->fromArray(array (
  'key' => 'mail_smtp_user',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'mail',
  'editedon' => null,
), '', true, true);
$settings['manager_date_format']= $xpdo->newObject(modSystemSetting::class);
$settings['manager_date_format']->fromArray(array (
  'key' => 'manager_date_format',
  'value' => 'Y-m-d',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['manager_favicon_url']= $xpdo->newObject(modSystemSetting::class);
$settings['manager_favicon_url']->fromArray(array (
  'key' => 'manager_favicon_url',
  'value' => 'favicon.ico',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['manager_js_cache_file_locking']= $xpdo->newObject(modSystemSetting::class);
$settings['manager_js_cache_file_locking']->fromArray(array (
  'key' => 'manager_js_cache_file_locking',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['manager_js_cache_max_age']= $xpdo->newObject(modSystemSetting::class);
$settings['manager_js_cache_max_age']->fromArray(array (
  'key' => 'manager_js_cache_max_age',
  'value' => 3600,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['manager_js_document_root']= $xpdo->newObject(modSystemSetting::class);
$settings['manager_js_document_root']->fromArray(array (
  'key' => 'manager_js_document_root',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['manager_time_format']= $xpdo->newObject(modSystemSetting::class);
$settings['manager_time_format']->fromArray(array (
  'key' => 'manager_time_format',
  'value' => 'H:i',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['manager_direction']= $xpdo->newObject(modSystemSetting::class);
$settings['manager_direction']->fromArray(array (
  'key' => 'manager_direction',
  'value' => 'ltr',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'language',
  'editedon' => null,
), '', true, true);
$settings['manager_login_url_alternate']= $xpdo->newObject(modSystemSetting::class);
$settings['manager_login_url_alternate']->fromArray(array (
  'key' => 'manager_login_url_alternate',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['manager_tooltip_enable']= $xpdo->newObject(modSystemSetting::class);
$settings['manager_tooltip_enable']->fromArray(array (
  'namespace' => 'core',
  'key' => 'manager_tooltip_enable',
  'value' => true,
  'xtype' => 'combo-boolean',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['manager_tooltip_delay']= $xpdo->newObject(modSystemSetting::class);
$settings['manager_tooltip_delay']->fromArray(array (
  'key' => 'manager_tooltip_delay',
  'value' => 2300,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['login_background_image']= $xpdo->newObject(modSystemSetting::class);
$settings['login_background_image']->fromArray(array (
  'key' => 'login_background_image',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['login_logo']= $xpdo->newObject(modSystemSetting::class);
$settings['login_logo']->fromArray(array (
  'key' => 'login_logo',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['login_help_button']= $xpdo->newObject(modSystemSetting::class);
$settings['login_help_button']->fromArray(array (
  'key' => 'login_help_button',
  'value' => '',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['manager_theme']= $xpdo->newObject(modSystemSetting::class);
$settings['manager_theme']->fromArray(array (
  'key' => 'manager_theme',
  'value' => 'default',
  'xtype' => 'modx-combo-manager-theme',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['manager_logo']= $xpdo->newObject(modSystemSetting::class);
$settings['manager_logo']->fromArray(array (
    'key' => 'manager_logo',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'core',
    'area' => 'manager',
    'editedon' => null,
), '', true, true);
$settings['manager_week_start']= $xpdo->newObject(modSystemSetting::class);
$settings['manager_week_start']->fromArray(array (
  'key' => 'manager_week_start',
  'value' => 0,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['modx_browser_tree_hide_files']= $xpdo->newObject(modSystemSetting::class);
$settings['modx_browser_tree_hide_files']->fromArray(array (
  'key' => 'modx_browser_tree_hide_files',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['modx_browser_tree_hide_tooltips']= $xpdo->newObject(modSystemSetting::class);
$settings['modx_browser_tree_hide_tooltips']->fromArray(array (
  'key' => 'modx_browser_tree_hide_tooltips',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['modx_browser_default_sort']= $xpdo->newObject(modSystemSetting::class);
$settings['modx_browser_default_sort']->fromArray(array (
  'key' => 'modx_browser_default_sort',
  'value' => 'name',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['modx_browser_default_viewmode']= $xpdo->newObject(modSystemSetting::class);
$settings['modx_browser_default_viewmode']->fromArray(array (
  'key' => 'modx_browser_default_viewmode',
  'value' => 'grid',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['modx_charset']= $xpdo->newObject(modSystemSetting::class);
$settings['modx_charset']->fromArray(array (
  'key' => 'modx_charset',
  'value' => 'UTF-8',
  'xtype' => 'modx-combo-charset',
  'namespace' => 'core',
  'area' => 'language',
  'editedon' => null,
), '', true, true);
$settings['principal_targets']= $xpdo->newObject(modSystemSetting::class);
$settings['principal_targets']->fromArray(array (
  'key' => 'principal_targets',
  'value' => 'modAccessContext,modAccessResourceGroup,modAccessCategory,sources.modAccessMediaSource,modAccessNamespace',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['proxy_auth_type']= $xpdo->newObject(modSystemSetting::class);
$settings['proxy_auth_type']->fromArray(array (
  'key' => 'proxy_auth_type',
  'value' => 'BASIC',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'proxy',
  'editedon' => null,
), '', true, true);
$settings['proxy_host']= $xpdo->newObject(modSystemSetting::class);
$settings['proxy_host']->fromArray(array (
  'key' => 'proxy_host',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'proxy',
  'editedon' => null,
), '', true, true);
$settings['proxy_password']= $xpdo->newObject(modSystemSetting::class);
$settings['proxy_password']->fromArray(array (
  'key' => 'proxy_password',
  'value' => '',
  'xtype' => 'text-password',
  'namespace' => 'core',
  'area' => 'proxy',
  'editedon' => null,
), '', true, true);
$settings['proxy_port']= $xpdo->newObject(modSystemSetting::class);
$settings['proxy_port']->fromArray(array (
  'key' => 'proxy_port',
  'value' => '',
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'proxy',
  'editedon' => null,
), '', true, true);
$settings['proxy_username']= $xpdo->newObject(modSystemSetting::class);
$settings['proxy_username']->fromArray(array (
  'key' => 'proxy_username',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'proxy',
  'editedon' => null,
), '', true, true);
$settings['password_generated_length']= $xpdo->newObject(modSystemSetting::class);
$settings['password_generated_length']->fromArray(array (
  'key' => 'password_generated_length',
  'value' => 10,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$settings['password_min_length']= $xpdo->newObject(modSystemSetting::class);
$settings['password_min_length']->fromArray(array (
  'key' => 'password_min_length',
  'value' => 8,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);

$settings['phpthumb_allow_src_above_docroot']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_allow_src_above_docroot']->fromArray(array (
  'key' => 'phpthumb_allow_src_above_docroot',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_cache_maxage']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_cache_maxage']->fromArray(array (
  'key' => 'phpthumb_cache_maxage',
  'value' => 30, // 30 days
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_cache_maxsize']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_cache_maxsize']->fromArray(array (
  'key' => 'phpthumb_cache_maxsize',
  'value' => 100, // 100MB
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_cache_maxfiles']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_cache_maxfiles']->fromArray(array (
  'key' => 'phpthumb_cache_maxfiles',
  'value' => 10000, // 10k files
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_cache_source_enabled']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_cache_source_enabled']->fromArray(array (
  'key' => 'phpthumb_cache_source_enabled',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_document_root']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_document_root']->fromArray(array (
  'key' => 'phpthumb_document_root',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_error_bgcolor']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_error_bgcolor']->fromArray(array (
  'key' => 'phpthumb_error_bgcolor',
  'value' => 'CCCCFF',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_error_textcolor']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_error_textcolor']->fromArray(array (
  'key' => 'phpthumb_error_textcolor',
  'value' => 'FF0000',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_error_fontsize']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_error_fontsize']->fromArray(array (
  'key' => 'phpthumb_error_fontsize',
  'value' => 1,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_far']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_far']->fromArray(array (
  'key' => 'phpthumb_far',
  'value' => 'C',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_imagemagick_path']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_imagemagick_path']->fromArray(array (
  'key' => 'phpthumb_imagemagick_path',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_nohotlink_enabled']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_nohotlink_enabled']->fromArray(array (
  'key' => 'phpthumb_nohotlink_enabled',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_nohotlink_erase_image']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_nohotlink_erase_image']->fromArray(array (
  'key' => 'phpthumb_nohotlink_erase_image',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_nohotlink_valid_domains']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_nohotlink_valid_domains']->fromArray(array (
  'key' => 'phpthumb_nohotlink_valid_domains',
  'value' => '{http_host}',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_nohotlink_text_message']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_nohotlink_text_message']->fromArray(array (
  'key' => 'phpthumb_nohotlink_text_message',
  'value' => 'Off-server thumbnailing is not allowed',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_nooffsitelink_enabled']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_nooffsitelink_enabled']->fromArray(array (
  'key' => 'phpthumb_nooffsitelink_enabled',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_nooffsitelink_erase_image']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_nooffsitelink_erase_image']->fromArray(array (
  'key' => 'phpthumb_nooffsitelink_erase_image',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_nooffsitelink_require_refer']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_nooffsitelink_require_refer']->fromArray(array (
  'key' => 'phpthumb_nooffsitelink_require_refer',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_nooffsitelink_text_message']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_nooffsitelink_text_message']->fromArray(array (
  'key' => 'phpthumb_nooffsitelink_text_message',
  'value' => 'Off-server linking is not allowed',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_nooffsitelink_valid_domains']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_nooffsitelink_valid_domains']->fromArray(array (
  'key' => 'phpthumb_nooffsitelink_valid_domains',
  'value' => '{http_host}',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_nooffsitelink_watermark_src']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_nooffsitelink_watermark_src']->fromArray(array (
  'key' => 'phpthumb_nooffsitelink_watermark_src',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);
$settings['phpthumb_zoomcrop']= $xpdo->newObject(modSystemSetting::class);
$settings['phpthumb_zoomcrop']->fromArray(array (
  'key' => 'phpthumb_zoomcrop',
  'value' => '0',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'phpthumb',
  'editedon' => null,
), '', true, true);

$settings['publish_default']= $xpdo->newObject(modSystemSetting::class);
$settings['publish_default']->fromArray(array (
  'key' => 'publish_default',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['rb_base_dir']= $xpdo->newObject(modSystemSetting::class);
$settings['rb_base_dir']->fromArray(array (
  'key' => 'rb_base_dir',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['rb_base_url']= $xpdo->newObject(modSystemSetting::class);
$settings['rb_base_url']->fromArray(array (
  'key' => 'rb_base_url',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['request_controller']= $xpdo->newObject(modSystemSetting::class);
$settings['request_controller']->fromArray(array (
  'key' => 'request_controller',
  'value' => 'index.php',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'gateway',
  'editedon' => null,
), '', true, true);
$settings['request_method_strict']= $xpdo->newObject(modSystemSetting::class);
$settings['request_method_strict']->fromArray(array (
  'key' => 'request_method_strict',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'gateway',
  'editedon' => null,
), '', true, true);
$settings['request_param_alias']= $xpdo->newObject(modSystemSetting::class);
$settings['request_param_alias']->fromArray(array (
  'key' => 'request_param_alias',
  'value' => 'q',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'gateway',
  'editedon' => null,
), '', true, true);
$settings['request_param_id']= $xpdo->newObject(modSystemSetting::class);
$settings['request_param_id']->fromArray(array (
  'key' => 'request_param_id',
  'value' => 'id',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'gateway',
  'editedon' => null,
), '', true, true);
$settings['resolve_hostnames']= $xpdo->newObject(modSystemSetting::class);
$settings['resolve_hostnames']->fromArray(array (
  'key' => 'resolve_hostnames',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['resource_tree_node_name']= $xpdo->newObject(modSystemSetting::class);
$settings['resource_tree_node_name']->fromArray(array (
  'key' => 'resource_tree_node_name',
  'value' => 'pagetitle',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['resource_tree_node_name_fallback']= $xpdo->newObject(modSystemSetting::class);
$settings['resource_tree_node_name_fallback']->fromArray(array (
  'key' => 'resource_tree_node_name_fallback',
  'value' => 'alias',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['resource_tree_node_tooltip']= $xpdo->newObject(modSystemSetting::class);
$settings['resource_tree_node_tooltip']->fromArray(array (
  'key' => 'resource_tree_node_tooltip',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['richtext_default']= $xpdo->newObject(modSystemSetting::class);
$settings['richtext_default']->fromArray(array (
  'key' => 'richtext_default',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['search_default']= $xpdo->newObject(modSystemSetting::class);
$settings['search_default']->fromArray(array (
  'key' => 'search_default',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['server_offset_time']= $xpdo->newObject(modSystemSetting::class);
$settings['server_offset_time']->fromArray(array (
  'key' => 'server_offset_time',
  'value' => 0,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['server_protocol']= $xpdo->newObject(modSystemSetting::class);
$settings['server_protocol']->fromArray(array (
  'key' => 'server_protocol',
  'value' => 'http',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['session_cookie_domain']= $xpdo->newObject(modSystemSetting::class);
$settings['session_cookie_domain']->fromArray(array (
  'key' => 'session_cookie_domain',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'session',
  'editedon' => null,
), '', true, true);
$settings['default_username']= $xpdo->newObject(modSystemSetting::class);
$settings['default_username']->fromArray(array (
  'key' => 'default_username',
  'value' => '(anonymous)',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'session',
  'editedon' => null,
), '', true, true);
$settings['anonymous_sessions']= $xpdo->newObject(modSystemSetting::class);
$settings['anonymous_sessions']->fromArray(array (
  'key' => 'anonymous_sessions',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'session',
  'editedon' => null,
), '', true, true);
$settings['session_cookie_lifetime']= $xpdo->newObject(modSystemSetting::class);
$settings['session_cookie_lifetime']->fromArray(array (
  'key' => 'session_cookie_lifetime',
  'value' => 604800,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'session',
  'editedon' => null,
), '', true, true);
$settings['session_cookie_path']= $xpdo->newObject(modSystemSetting::class);
$settings['session_cookie_path']->fromArray(array (
  'key' => 'session_cookie_path',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'session',
  'editedon' => null,
), '', true, true);
$settings['session_cookie_secure']= $xpdo->newObject(modSystemSetting::class);
$settings['session_cookie_secure']->fromArray(array (
  'key' => 'session_cookie_secure',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'session',
  'editedon' => null,
), '', true, true);
$settings['session_cookie_httponly']= $xpdo->newObject(modSystemSetting::class);
$settings['session_cookie_httponly']->fromArray(array (
  'key' => 'session_cookie_httponly',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'session',
  'editedon' => null,
), '', true, true);
$settings['session_gc_maxlifetime']= $xpdo->newObject(modSystemSetting::class);
$settings['session_gc_maxlifetime']->fromArray(array (
  'key' => 'session_gc_maxlifetime',
  'value' => '604800',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'session',
  'editedon' => null,
), '', true, true);
$settings['session_handler_class']= $xpdo->newObject(modSystemSetting::class);
$settings['session_handler_class']->fromArray(array (
  'key' => 'session_handler_class',
  'value' => modSessionHandler::class,
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'session',
  'editedon' => null,
), '', true, true);
$settings['session_name']= $xpdo->newObject(modSystemSetting::class);
$settings['session_name']->fromArray(array (
  'key' => 'session_name',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'session',
  'editedon' => null,
), '', true, true);
$settings['set_header']= $xpdo->newObject(modSystemSetting::class);
$settings['set_header']->fromArray(array (
  'key' => 'set_header',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => null,
), '', true, true);
$settings['send_poweredby_header']= $xpdo->newObject(modSystemSetting::class);
$settings['send_poweredby_header']->fromArray(array (
    'key' => 'send_poweredby_header',
    'value' => false,
    'xtype' => 'combo-boolean',
    'namespace' => 'core',
    'area' => 'system',
    'editedon' => null,
), '', true, true);
$settings['show_tv_categories_header']= $xpdo->newObject(modSystemSetting::class);
$settings['show_tv_categories_header']->fromArray(array (
  'key' => 'show_tv_categories_header',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['site_name']= $xpdo->newObject(modSystemSetting::class);
$settings['site_name']->fromArray(array (
  'key' => 'site_name',
  'value' => 'MODX Revolution',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['site_start']= $xpdo->newObject(modSystemSetting::class);
$settings['site_start']->fromArray(array (
  'key' => 'site_start',
  'value' => 1,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['site_status']= $xpdo->newObject(modSystemSetting::class);
$settings['site_status']->fromArray(array (
  'key' => 'site_status',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['site_unavailable_message']= $xpdo->newObject(modSystemSetting::class);
$settings['site_unavailable_message']->fromArray(array (
  'key' => 'site_unavailable_message',
  'value' => 'The site is currently unavailable',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['site_unavailable_page']= $xpdo->newObject(modSystemSetting::class);
$settings['site_unavailable_page']->fromArray(array (
  'key' => 'site_unavailable_page',
  'value' => 0,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['static_elements_automate_templates']= $xpdo->newObject(modSystemSetting::class);
$settings['static_elements_automate_templates']->fromArray(array (
  'key' => 'static_elements_automate_templates',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'static_elements',
  'editedon' => null,
), '', true, true);
$settings['static_elements_automate_tvs']= $xpdo->newObject(modSystemSetting::class);
$settings['static_elements_automate_tvs']->fromArray(array (
  'key' => 'static_elements_automate_tvs',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'static_elements',
  'editedon' => null,
), '', true, true);
$settings['static_elements_automate_chunks']= $xpdo->newObject(modSystemSetting::class);
$settings['static_elements_automate_chunks']->fromArray(array (
  'key' => 'static_elements_automate_chunks',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'static_elements',
  'editedon' => null,
), '', true, true);
$settings['static_elements_automate_snippets']= $xpdo->newObject(modSystemSetting::class);
$settings['static_elements_automate_snippets']->fromArray(array (
  'key' => 'static_elements_automate_snippets',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'static_elements',
  'editedon' => null,
), '', true, true);
$settings['static_elements_automate_plugins']= $xpdo->newObject(modSystemSetting::class);
$settings['static_elements_automate_plugins']->fromArray(array (
  'key' => 'static_elements_automate_plugins',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'static_elements',
  'editedon' => null,
), '', true, true);
$settings['static_elements_default_mediasource']= $xpdo->newObject(modSystemSetting::class);
$settings['static_elements_default_mediasource']->fromArray(array (
  'key' => 'static_elements_default_mediasource',
  'value' => 0,
  'xtype' => 'modx-combo-source',
  'namespace' => 'core',
  'area' => 'static_elements',
  'editedon' => null,
), '', true, true);
$settings['static_elements_default_category']= $xpdo->newObject(modSystemSetting::class);
$settings['static_elements_default_category']->fromArray(array (
  'key' => 'static_elements_default_category',
  'value' => 0,
  'xtype' => 'modx-combo-category',
  'namespace' => 'core',
  'area' => 'static_elements',
  'editedon' => null,
), '', true, true);
$settings['static_elements_basepath']= $xpdo->newObject(modSystemSetting::class);
$settings['static_elements_basepath']->fromArray(array (
  'key' => 'static_elements_basepath',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'static_elements',
  'editedon' => null,
), '', true, true);
$settings['strip_image_paths']= $xpdo->newObject(modSystemSetting::class);
$settings['strip_image_paths']->fromArray(array (
  'key' => 'strip_image_paths',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['symlink_merge_fields']= $xpdo->newObject(modSystemSetting::class);
$settings['symlink_merge_fields']->fromArray(array (
  'key' => 'symlink_merge_fields',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['syncsite_default']= $xpdo->newObject(modSystemSetting::class);
$settings['syncsite_default']->fromArray(array (
    'key' => 'syncsite_default',
    'value' => true,
    'xtype' => 'combo-boolean',
    'namespace' => 'core',
    'area' => 'caching',
    'editedon' => null,
), '', true, true);
$settings['topmenu_show_descriptions']= $xpdo->newObject(modSystemSetting::class);
$settings['topmenu_show_descriptions']->fromArray(array (
  'key' => 'topmenu_show_descriptions',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['tree_default_sort']= $xpdo->newObject(modSystemSetting::class);
$settings['tree_default_sort']->fromArray(array (
  'key' => 'tree_default_sort',
  'value' => 'menuindex',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['tree_root_id']= $xpdo->newObject(modSystemSetting::class);
$settings['tree_root_id']->fromArray(array (
  'key' => 'tree_root_id',
  'value' => 0,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['tvs_below_content']= $xpdo->newObject(modSystemSetting::class);
$settings['tvs_below_content']->fromArray(array (
  'key' => 'tvs_below_content',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['unauthorized_page']= $xpdo->newObject(modSystemSetting::class);
$settings['unauthorized_page']->fromArray(array (
  'key' => 'unauthorized_page',
  'value' => 1,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['upload_files']= $xpdo->newObject(modSystemSetting::class);
$settings['upload_files']->fromArray(array (
  'key' => 'upload_files',
  'value' => 'txt,html,htm,xml,js,css,zip,gz,rar,z,tgz,tar,mp3,mp4,aac,wav,au,wmv,avi,mpg,mpeg,pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,tiff,svg,svgz,gif,psd,ico,bmp,odt,ods,odp,odb,odg,odf,md,ttf,woff,eot,scss,less,css.map,webp',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['upload_images']= $xpdo->newObject(modSystemSetting::class);
$settings['upload_images']->fromArray(array (
  'key' => 'upload_images',
  'value' => 'jpg,jpeg,png,gif,psd,ico,bmp,tiff,svg,svgz,webp',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['upload_maxsize']= $xpdo->newObject(modSystemSetting::class);
$settings['upload_maxsize']->fromArray(array (
  'key' => 'upload_maxsize',
  'value' => 1048576,
  'xtype' => 'numberfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['upload_media']= $xpdo->newObject(modSystemSetting::class);
$settings['upload_media']->fromArray(array (
  'key' => 'upload_media',
  'value' => 'mp3,wav,au,wmv,avi,mpg,mpeg',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['use_alias_path']= $xpdo->newObject(modSystemSetting::class);
$settings['use_alias_path']->fromArray(array (
  'key' => 'use_alias_path',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);
$settings['use_browser']= $xpdo->newObject(modSystemSetting::class);
$settings['use_browser']->fromArray(array (
  'key' => 'use_browser',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => null,
), '', true, true);
$settings['use_editor']= $xpdo->newObject(modSystemSetting::class);
$settings['use_editor']->fromArray(array (
  'key' => 'use_editor',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'editor',
  'editedon' => null,
), '', true, true);
$settings['use_multibyte']= $xpdo->newObject(modSystemSetting::class);
$settings['use_multibyte']->fromArray(array (
  'key' => 'use_multibyte',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'language',
  'editedon' => null,
), '', true, true);
$settings['use_weblink_target']= $xpdo->newObject(modSystemSetting::class);
$settings['use_weblink_target']->fromArray(array (
  'key' => 'use_weblink_target',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['welcome_screen']= $xpdo->newObject(modSystemSetting::class);
$settings['welcome_screen']->fromArray(array (
  'key' => 'welcome_screen',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['welcome_screen_url']= $xpdo->newObject(modSystemSetting::class);
$settings['welcome_screen_url']->fromArray(array (
  'key' => 'welcome_screen_url',
  'value' => '//misc.modx.com/revolution/welcome.27.html ',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['welcome_action']= $xpdo->newObject(modSystemSetting::class);
$settings['welcome_action']->fromArray(array (
  'key' => 'welcome_action',
  'value' => 'welcome',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['welcome_namespace']= $xpdo->newObject(modSystemSetting::class);
$settings['welcome_namespace']->fromArray(array (
  'key' => 'welcome_namespace',
  'value' => 'core',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['which_editor']= $xpdo->newObject(modSystemSetting::class);
$settings['which_editor']->fromArray(array (
  'key' => 'which_editor',
  'value' => '',
  'xtype' => 'modx-combo-rte',
  'namespace' => 'core',
  'area' => 'editor',
  'editedon' => null,
), '', true, true);
$settings['which_element_editor']= $xpdo->newObject(modSystemSetting::class);
$settings['which_element_editor']->fromArray(array (
  'key' => 'which_element_editor',
  'value' => '',
  'xtype' => 'modx-combo-rte',
  'namespace' => 'core',
  'area' => 'editor',
  'editedon' => null,
), '', true, true);
$settings['xhtml_urls']= $xpdo->newObject(modSystemSetting::class);
$settings['xhtml_urls']->fromArray(array (
  'key' => 'xhtml_urls',
  'value' => true,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => null,
), '', true, true);
$settings['enable_gravatar']= $xpdo->newObject(modSystemSetting::class);
$settings['enable_gravatar']->fromArray(array (
  'key' => 'enable_gravatar',
  'value' => false,
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['mgr_tree_icon_context']= $xpdo->newObject(modSystemSetting::class);
$settings['mgr_tree_icon_context']->fromArray(array (
  'key' => 'mgr_tree_icon_context',
  'value' => 'tree-context',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['mgr_source_icon']= $xpdo->newObject(modSystemSetting::class);
$settings['mgr_source_icon']->fromArray(array (
  'key' => 'mgr_source_icon',
  'value' => 'icon-folder-open-o',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['main_nav_parent']= $xpdo->newObject(modSystemSetting::class);
$settings['main_nav_parent']->fromArray(array (
  'key' => 'main_nav_parent',
  'value' => 'topnav',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['user_nav_parent']= $xpdo->newObject(modSystemSetting::class);
$settings['user_nav_parent']->fromArray(array (
  'key' => 'user_nav_parent',
  'value' => 'usernav',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$settings['auto_isfolder']= $xpdo->newObject(modSystemSetting::class);
$settings['auto_isfolder']->fromArray(array (
    'key' => 'auto_isfolder',
    'value' => true,
    'xtype' => 'combo-boolean',
    'namespace' => 'core',
    'area' => 'site',
    'editedon' => null,
), '', true, true);
$settings['manager_use_fullname']= $xpdo->newObject(modSystemSetting::class);
$settings['manager_use_fullname']->fromArray(array (
    'key' => 'manager_use_fullname',
    'value' => false,
    'xtype' => 'combo-boolean',
    'namespace' => 'core',
    'area' => 'manager',
    'editedon' => null,
), '', true, true);
$settings['parser_recurse_uncacheable']= $xpdo->newObject(modSystemSetting::class);
$settings['parser_recurse_uncacheable']->fromArray(array (
    'key' => 'parser_recurse_uncacheable',
    'value' => true,
    'xtype' => 'combo-boolean',
    'namespace' => 'core',
    'area' => 'system',
    'editedon' => null,
), '', true, true);
$settings['preserve_menuindex']= $xpdo->newObject(modSystemSetting::class);
$settings['preserve_menuindex']->fromArray(array (
    'key' => 'preserve_menuindex',
    'value' => false,
    'xtype' => 'combo-boolean',
    'namespace' => 'core',
    'area' => 'manager',
    'editedon' => null,
), '', true, true);
$settings['log_snippet_not_found']= $xpdo->newObject(modSystemSetting::class);
$settings['log_snippet_not_found']->fromArray(array (
    'key' => 'log_snippet_not_found',
    'value' => true,
    'xtype' => 'combo-boolean',
    'namespace' => 'core',
    'area' => 'site',
    'editedon' => null,
), '', true, true);
$settings['error_log_filename']= $xpdo->newObject(modSystemSetting::class);
$settings['error_log_filename']->fromArray(array (
    'key' => 'error_log_filename',
    'value' => 'error.log',
    'xtype' => 'textfield',
    'namespace' => 'core',
    'area' => 'system',
    'editedon' => null,
), '', true, true);
$settings['error_log_filepath']= $xpdo->newObject(modSystemSetting::class);
$settings['error_log_filepath']->fromArray(array (
    'key' => 'error_log_filepath',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'core',
    'area' => 'system',
    'editedon' => null,
), '', true, true);
$settings['passwordless_activated']= $xpdo->newObject(modSystemSetting::class);
$settings['passwordless_activated']->fromArray(array (
    'key' => 'passwordless_activated',
    'value' => true,
    'xtype' => 'combo-boolean',
    'namespace' => 'core',
    'area' => 'authentication',
    'editedon' => null,
), '', true, true);
$settings['passwordless_expiration']= $xpdo->newObject(modSystemSetting::class);
$settings['passwordless_expiration']->fromArray(array (
    'key' => 'passwordless_expiration',
    'value' => '3600',
    'xtype' => 'textfield',
    'namespace' => 'core',
    'area' => 'authentication',
    'editedon' => null,
), '', true, true);
$settings['passwordless_activated']= $xpdo->newObject(modSystemSetting::class);
$settings['passwordless_activated']->fromArray(array (
    'key' => 'passwordless_activated',
    'value' => false,
    'xtype' => 'combo-boolean',
    'namespace' => 'core',
    'area' => 'authentication',
    'editedon' => null,
), '', true, true);
$settings['passwordless_expiration']= $xpdo->newObject(modSystemSetting::class);
$settings['passwordless_expiration']->fromArray(array (
    'key' => 'passwordless_expiration',
    'value' => '3600',
    'xtype' => 'textfield',
    'namespace' => 'core',
    'area' => 'authentication',
    'editedon' => null,
), '', true, true);

return $settings;
