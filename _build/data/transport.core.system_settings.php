<?php
$collection['0']= $xpdo->newObject('modSystemSetting');
$collection['0']->fromArray(array (
  'key' => 'allow_tags_in_post',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => NULL,
), '', true, true);
$collection['1']= $xpdo->newObject('modSystemSetting');
$collection['1']->fromArray(array (
  'key' => 'auto_menuindex',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => NULL,
), '', true, true);
$collection['2']= $xpdo->newObject('modSystemSetting');
$collection['2']->fromArray(array (
  'key' => 'blocked_minutes',
  'value' => '60',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => NULL,
), '', true, true);
$collection['3']= $xpdo->newObject('modSystemSetting');
$collection['3']->fromArray(array (
  'key' => 'cache_db',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => NULL,
), '', true, true);
$collection['4']= $xpdo->newObject('modSystemSetting');
$collection['4']->fromArray(array (
  'key' => 'cache_db_expires',
  'value' => '0',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => NULL,
), '', true, true);
$collection['5']= $xpdo->newObject('modSystemSetting');
$collection['5']->fromArray(array (
  'key' => 'cache_default',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => NULL,
), '', true, true);
$collection['6']= $xpdo->newObject('modSystemSetting');
$collection['6']->fromArray(array (
  'key' => 'cache_disabled',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => NULL,
), '', true, true);
$collection['7']= $xpdo->newObject('modSystemSetting');
$collection['7']->fromArray(array (
  'key' => 'cache_json',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => NULL,
), '', true, true);
$collection['8']= $xpdo->newObject('modSystemSetting');
$collection['8']->fromArray(array (
  'key' => 'cache_json_expires',
  'value' => '0',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => NULL,
), '', true, true);
$collection['9']= $xpdo->newObject('modSystemSetting');
$collection['9']->fromArray(array (
  'key' => 'cache_resource',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => NULL,
), '', true, true);
$collection['10']= $xpdo->newObject('modSystemSetting');
$collection['10']->fromArray(array (
  'key' => 'cache_resource_expires',
  'value' => '0',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => NULL,
), '', true, true);
$collection['11']= $xpdo->newObject('modSystemSetting');
$collection['11']->fromArray(array (
  'key' => 'compress_js',
  'value' => '',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => NULL,
), '', true, true);
$collection['12']= $xpdo->newObject('modSystemSetting');
$collection['12']->fromArray(array (
  'key' => 'container_suffix',
  'value' => '/',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => NULL,
), '', true, true);
$collection['13']= $xpdo->newObject('modSystemSetting');
$collection['13']->fromArray(array (
  'key' => 'default_template',
  'value' => '1',
  'xtype' => 'modx-combo-template',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => NULL,
), '', true, true);
$collection['14']= $xpdo->newObject('modSystemSetting');
$collection['14']->fromArray(array (
  'key' => 'editor_css_path',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => NULL,
), '', true, true);
$collection['15']= $xpdo->newObject('modSystemSetting');
$collection['15']->fromArray(array (
  'key' => 'editor_css_selectors',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => NULL,
), '', true, true);
$collection['16']= $xpdo->newObject('modSystemSetting');
$collection['16']->fromArray(array (
  'key' => 'emailsender',
  'value' => 'email@example.com',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => NULL,
), '', true, true);
$collection['17']= $xpdo->newObject('modSystemSetting');
$collection['17']->fromArray(array (
  'key' => 'emailsubject',
  'value' => 'Your login details',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => NULL,
), '', true, true);
$collection['18']= $xpdo->newObject('modSystemSetting');
$collection['18']->fromArray(array (
  'key' => 'error_page',
  'value' => '1',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => NULL,
), '', true, true);
$collection['19']= $xpdo->newObject('modSystemSetting');
$collection['19']->fromArray(array (
  'key' => 'failed_login_attempts',
  'value' => '5',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => NULL,
), '', true, true);
$collection['20']= $xpdo->newObject('modSystemSetting');
$collection['20']->fromArray(array (
  'key' => 'feed_modx_news',
  'value' => 'http://feeds.feedburner.com/modx-announce',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => NULL,
), '', true, true);
$collection['21']= $xpdo->newObject('modSystemSetting');
$collection['21']->fromArray(array (
  'key' => 'feed_modx_security',
  'value' => 'http://feeds.feedburner.com/modxsecurity',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => NULL,
), '', true, true);
$collection['22']= $xpdo->newObject('modSystemSetting');
$collection['22']->fromArray(array (
  'key' => 'fe_editor_lang',
  'value' => 'en',
  'xtype' => 'modx-combo-language',
  'namespace' => 'core',
  'area' => 'language',
  'editedon' => NULL,
), '', true, true);
$collection['23']= $xpdo->newObject('modSystemSetting');
$collection['23']->fromArray(array (
  'key' => 'filemanager_path',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => NULL,
), '', true, true);
$collection['24']= $xpdo->newObject('modSystemSetting');
$collection['24']->fromArray(array (
  'key' => 'friendly_alias_urls',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => NULL,
), '', true, true);
$collection['25']= $xpdo->newObject('modSystemSetting');
$collection['25']->fromArray(array (
  'key' => 'friendly_urls',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => NULL,
), '', true, true);
$collection['26']= $xpdo->newObject('modSystemSetting');
$collection['26']->fromArray(array (
  'key' => 'friendly_url_prefix',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => NULL,
), '', true, true);
$collection['27']= $xpdo->newObject('modSystemSetting');
$collection['27']->fromArray(array (
  'key' => 'friendly_url_suffix',
  'value' => '.html',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => NULL,
), '', true, true);
$collection['28']= $xpdo->newObject('modSystemSetting');
$collection['28']->fromArray(array (
  'key' => 'mail_check_timeperiod',
  'value' => '60',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => NULL,
), '', true, true);
$collection['29']= $xpdo->newObject('modSystemSetting');
$collection['29']->fromArray(array (
  'key' => 'manager_direction',
  'value' => 'ltr',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'language',
  'editedon' => NULL,
), '', true, true);
$collection['30']= $xpdo->newObject('modSystemSetting');
$collection['30']->fromArray(array (
  'key' => 'manager_language',
  'value' => 'en',
  'xtype' => 'modx-combo-language',
  'namespace' => 'core',
  'area' => 'language',
  'editedon' => NULL,
), '', true, true);
$collection['31']= $xpdo->newObject('modSystemSetting');
$collection['31']->fromArray(array (
  'key' => 'manager_lang_attribute',
  'value' => 'en',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'language',
  'editedon' => NULL,
), '', true, true);
$collection['32']= $xpdo->newObject('modSystemSetting');
$collection['32']->fromArray(array (
  'key' => 'manager_layout',
  'value' => '4',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => NULL,
), '', true, true);
$collection['33']= $xpdo->newObject('modSystemSetting');
$collection['33']->fromArray(array (
  'key' => 'manager_theme',
  'value' => 'default',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => NULL,
), '', true, true);
$collection['34']= $xpdo->newObject('modSystemSetting');
$collection['34']->fromArray(array (
  'key' => 'modx_charset',
  'value' => 'UTF-8',
  'xtype' => 'modx-combo-charset',
  'namespace' => 'core',
  'area' => 'language',
  'editedon' => NULL,
), '', true, true);
$collection['35']= $xpdo->newObject('modSystemSetting');
$collection['35']->fromArray(array (
  'key' => 'new_file_permissions',
  'value' => '0644',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => NULL,
), '', true, true);
$collection['36']= $xpdo->newObject('modSystemSetting');
$collection['36']->fromArray(array (
  'key' => 'new_folder_permissions',
  'value' => '0755',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => NULL,
), '', true, true);
$collection['37']= $xpdo->newObject('modSystemSetting');
$collection['37']->fromArray(array (
  'key' => 'number_of_messages',
  'value' => '30',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => NULL,
), '', true, true);
$collection['38']= $xpdo->newObject('modSystemSetting');
$collection['38']->fromArray(array (
  'key' => 'number_of_results',
  'value' => '20',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => NULL,
), '', true, true);
$collection['39']= $xpdo->newObject('modSystemSetting');
$collection['39']->fromArray(array (
  'key' => 'publish_default',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => NULL,
), '', true, true);
$collection['40']= $xpdo->newObject('modSystemSetting');
$collection['40']->fromArray(array (
  'key' => 'rb_base_dir',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => NULL,
), '', true, true);
$collection['41']= $xpdo->newObject('modSystemSetting');
$collection['41']->fromArray(array (
  'key' => 'rb_base_url',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => NULL,
), '', true, true);
$collection['42']= $xpdo->newObject('modSystemSetting');
$collection['42']->fromArray(array (
  'key' => 'request_param_alias',
  'value' => 'q',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => NULL,
), '', true, true);
$collection['43']= $xpdo->newObject('modSystemSetting');
$collection['43']->fromArray(array (
  'key' => 'request_param_id',
  'value' => 'id',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => NULL,
), '', true, true);
$collection['44']= $xpdo->newObject('modSystemSetting');
$collection['44']->fromArray(array (
  'key' => 'resolve_hostnames',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => NULL,
), '', true, true);
$collection['45']= $xpdo->newObject('modSystemSetting');
$collection['45']->fromArray(array (
  'key' => 'search_default',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => NULL,
), '', true, true);
$collection['46']= $xpdo->newObject('modSystemSetting');
$collection['46']->fromArray(array (
  'key' => 'server_offset_time',
  'value' => '0',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => NULL,
), '', true, true);
$collection['47']= $xpdo->newObject('modSystemSetting');
$collection['47']->fromArray(array (
  'key' => 'server_protocol',
  'value' => 'http',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => NULL,
), '', true, true);
$collection['48']= $xpdo->newObject('modSystemSetting');
$collection['48']->fromArray(array (
  'key' => 'session_cookie_domain',
  'value' => 'localhost',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'session',
  'editedon' => NULL,
), '', true, true);
$collection['49']= $xpdo->newObject('modSystemSetting');
$collection['49']->fromArray(array (
  'key' => 'session_cookie_lifetime',
  'value' => '604800',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'session',
  'editedon' => NULL,
), '', true, true);
$collection['50']= $xpdo->newObject('modSystemSetting');
$collection['50']->fromArray(array (
  'key' => 'session_cookie_path',
  'value' => '/',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'session',
  'editedon' => NULL,
), '', true, true);
$collection['51']= $xpdo->newObject('modSystemSetting');
$collection['51']->fromArray(array (
  'key' => 'session_cookie_secure',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'session',
  'editedon' => NULL,
), '', true, true);
$collection['52']= $xpdo->newObject('modSystemSetting');
$collection['52']->fromArray(array (
  'key' => 'session_handler_class',
  'value' => 'modSessionHandler',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'session',
  'editedon' => NULL,
), '', true, true);
$collection['53']= $xpdo->newObject('modSystemSetting');
$collection['53']->fromArray(array (
  'key' => 'session_name',
  'value' => 'modxcmssession',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'session',
  'editedon' => NULL,
), '', true, true);
$collection['54']= $xpdo->newObject('modSystemSetting');
$collection['54']->fromArray(array (
  'key' => 'set_header',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => NULL,
), '', true, true);
$collection['55']= $xpdo->newObject('modSystemSetting');
$collection['55']->fromArray(array (
  'key' => 'show_preview',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => NULL,
), '', true, true);
$collection['56']= $xpdo->newObject('modSystemSetting');
$collection['56']->fromArray(array (
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
  'editedon' => NULL,
), '', true, true);
$collection['57']= $xpdo->newObject('modSystemSetting');
$collection['57']->fromArray(array (
  'key' => 'site_id',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'system',
  'editedon' => NULL,
), '', true, true);
$collection['58']= $xpdo->newObject('modSystemSetting');
$collection['58']->fromArray(array (
  'key' => 'site_name',
  'value' => 'MODx Revolution',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => NULL,
), '', true, true);
$collection['59']= $xpdo->newObject('modSystemSetting');
$collection['59']->fromArray(array (
  'key' => 'site_start',
  'value' => '1',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => NULL,
), '', true, true);
$collection['60']= $xpdo->newObject('modSystemSetting');
$collection['60']->fromArray(array (
  'key' => 'site_status',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => NULL,
), '', true, true);
$collection['61']= $xpdo->newObject('modSystemSetting');
$collection['61']->fromArray(array (
  'key' => 'site_unavailable_message',
  'value' => 'The site is currently unavailable',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => NULL,
), '', true, true);
$collection['62']= $xpdo->newObject('modSystemSetting');
$collection['62']->fromArray(array (
  'key' => 'strip_image_paths',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => NULL,
), '', true, true);
$collection['63']= $xpdo->newObject('modSystemSetting');
$collection['63']->fromArray(array (
  'key' => 'tinymce_custom_buttons1',
  'value' => 'undo,redo,selectall,separator,pastetext,pasteword,separator,search,replace,separator,nonbreaking,hr,charmap,separator,image,link,unlink,anchor,media,separator,cleanup,removeformat,separator,fullscreen,print,code,help',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => '',
  'editedon' => NULL,
), '', true, true);
$collection['64']= $xpdo->newObject('modSystemSetting');
$collection['64']->fromArray(array (
  'key' => 'tinymce_custom_buttons2',
  'value' => 'bold,italic,underline,strikethrough,sub,sup,separator,bullist,numlist,outdent,indent,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,styleselect,formatselect,separator,styleprops',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => '',
  'editedon' => NULL,
), '', true, true);
$collection['65']= $xpdo->newObject('modSystemSetting');
$collection['65']->fromArray(array (
  'key' => 'tinymce_custom_plugins',
  'value' => 'style,advimage,advlink,searchreplace,print,contextmenu,paste,fullscreen,noneditable,nonbreaking,xhtmlxtras,visualchars,media',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => '',
  'editedon' => NULL,
), '', true, true);
$collection['66']= $xpdo->newObject('modSystemSetting');
$collection['66']->fromArray(array (
  'key' => 'tinymce_editor_theme',
  'value' => 'editor',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => '',
  'editedon' => NULL,
), '', true, true);
$collection['67']= $xpdo->newObject('modSystemSetting');
$collection['67']->fromArray(array (
  'key' => 'udperms_allowroot',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => NULL,
), '', true, true);
$collection['68']= $xpdo->newObject('modSystemSetting');
$collection['68']->fromArray(array (
  'key' => 'unauthorized_page',
  'value' => '1',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => NULL,
), '', true, true);
$collection['69']= $xpdo->newObject('modSystemSetting');
$collection['69']->fromArray(array (
  'key' => 'upload_files',
  'value' => 'txt,php,html,htm,xml,js,css,cache,zip,gz,rar,z,tgz,tar,htaccess,mp3,mp4,aac,wav,au,wmv,avi,mpg,mpeg,pdf,doc,xls,txt',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => NULL,
), '', true, true);
$collection['70']= $xpdo->newObject('modSystemSetting');
$collection['70']->fromArray(array (
  'key' => 'upload_flash',
  'value' => 'swf,fla',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => NULL,
), '', true, true);
$collection['71']= $xpdo->newObject('modSystemSetting');
$collection['71']->fromArray(array (
  'key' => 'upload_images',
  'value' => 'jpg,jpeg,png,gif,psd,ico,bmp',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => NULL,
), '', true, true);
$collection['72']= $xpdo->newObject('modSystemSetting');
$collection['72']->fromArray(array (
  'key' => 'upload_maxsize',
  'value' => '1048576',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => NULL,
), '', true, true);
$collection['73']= $xpdo->newObject('modSystemSetting');
$collection['73']->fromArray(array (
  'key' => 'upload_media',
  'value' => 'mp3,wav,au,wmv,avi,mpg,mpeg',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => NULL,
), '', true, true);
$collection['74']= $xpdo->newObject('modSystemSetting');
$collection['74']->fromArray(array (
  'key' => 'use_alias_path',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => NULL,
), '', true, true);
$collection['75']= $xpdo->newObject('modSystemSetting');
$collection['75']->fromArray(array (
  'key' => 'use_browser',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'file',
  'editedon' => NULL,
), '', true, true);
$collection['76']= $xpdo->newObject('modSystemSetting');
$collection['76']->fromArray(array (
  'key' => 'use_editor',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => NULL,
), '', true, true);
$collection['77']= $xpdo->newObject('modSystemSetting');
$collection['77']->fromArray(array (
  'key' => 'use_udperms',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => NULL,
), '', true, true);
$collection['78']= $xpdo->newObject('modSystemSetting');
$collection['78']->fromArray(array (
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
  'editedon' => NULL,
), '', true, true);
$collection['79']= $xpdo->newObject('modSystemSetting');
$collection['79']->fromArray(array (
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
  'editedon' => NULL,
), '', true, true);
$collection['80']= $xpdo->newObject('modSystemSetting');
$collection['80']->fromArray(array (
  'key' => 'which_editor',
  'value' => 'TinyMCE',
  'xtype' => 'modx-combo-rte',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => NULL,
), '', true, true);
$collection['81']= $xpdo->newObject('modSystemSetting');
$collection['81']->fromArray(array (
  'key' => 'cache_lang_js',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => NULL,
), '', true, true);
$collection['82']= $xpdo->newObject('modSystemSetting');
$collection['82']->fromArray(array (
  'key' => 'cache_handler',
  'value' => 'xPDOFileCache',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => NULL,
), '', true, true);
$collection['83']= $xpdo->newObject('modSystemSetting');
$collection['83']->fromArray(array (
  'key' => 'cache_lexicon_topics',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => NULL,
), '', true, true);
$collection['84']= $xpdo->newObject('modSystemSetting');
$collection['84']->fromArray(array (
  'key' => 'cache_system_settings',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => NULL,
), '', true, true);
$collection['85']= $xpdo->newObject('modSystemSetting');
$collection['85']->fromArray(array (
  'key' => 'cache_context_settings',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => NULL,
), '', true, true);
$collection['86']= $xpdo->newObject('modSystemSetting');
$collection['86']->fromArray(array (
  'key' => 'cache_scripts',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => NULL,
), '', true, true);
$collection['87']= $xpdo->newObject('modSystemSetting');
$collection['87']->fromArray(array (
  'key' => 'cache_expires',
  'value' => '0',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => NULL,
), '', true, true);
$collection['88']= $xpdo->newObject('modSystemSetting');
$collection['88']->fromArray(array (
  'key' => 'cache_action_map',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'caching',
  'editedon' => NULL,
), '', true, true);
$collection['89']= $xpdo->newObject('modSystemSetting');
$collection['89']->fromArray(array (
  'key' => 'request_controller',
  'value' => 'index.php',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => '',
  'editedon' => NULL,
), '', true, true);
$collection['90']= $xpdo->newObject('modSystemSetting');
$collection['90']->fromArray(array (
  'key' => 'manager_use_tabs',
  'value' => '1',
  'xtype' => 'combo-boolean',
  'namespace' => 'core',
  'area' => 'manager',
  'editedon' => null,
), '', true, true);
$collection['91']= $xpdo->newObject('modSystemSetting');
$collection['91']->fromArray(array (
  'key' => 'site_unavailable_page',
  'value' => '0',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'site',
  'editedon' => NULL,
), '', true, true);
$collection['92']= $xpdo->newObject('modSystemSetting');
$collection['92']->fromArray(array (
  'key' => 'password_generated_length',
  'value' => '8',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$collection['93']= $xpdo->newObject('modSystemSetting');
$collection['93']->fromArray(array (
  'key' => 'password_min_length',
  'value' => '8',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'authentication',
  'editedon' => null,
), '', true, true);
$collection['94']= $xpdo->newObject('modSystemSetting');
$collection['94']->fromArray(array (
  'key' => 'automatic_alias',
  'value' => '0',
  'xtype' => 'textfield',
  'namespace' => 'core',
  'area' => 'furls',
  'editedon' => null,
), '', true, true);