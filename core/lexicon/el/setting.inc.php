<?php
/**
 * Setting English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['area'] = 'Area';
$_lang['area_authentication'] = 'Authentication and Security';
$_lang['area_caching'] = 'Caching';
$_lang['area_core'] = 'Core Code';
$_lang['area_editor'] = 'Rich-Text Editor';
$_lang['area_file'] = 'File System';
$_lang['area_filter'] = 'Filter by area...';
$_lang['area_furls'] = 'Friendly URL';
$_lang['area_gateway'] = 'Gateway';
$_lang['area_language'] = 'Lexicon and Language';
$_lang['area_mail'] = 'Mail';
$_lang['area_manager'] = 'Back-end Manager';
$_lang['area_phpthumb'] = 'phpThumb';
$_lang['area_proxy'] = 'Proxy';
$_lang['area_session'] = 'Session and Cookie';
$_lang['area_static_elements'] = 'Static Elements';
$_lang['area_static_resources'] = 'Static Resources';
$_lang['area_lexicon_string'] = 'Area Lexicon Entry';
$_lang['area_lexicon_string_msg'] = 'Enter the key of the lexicon entry for the area here. If there is no lexicon entry, it will just display the area key.<br />Core Areas: authentication, caching, file, furls, gateway, language, manager, session, site, system';
$_lang['area_site'] = 'Site';
$_lang['area_system'] = 'System and Server';
$_lang['areas'] = 'Areas';
$_lang['charset'] = 'Charset';
$_lang['country'] = 'Country';
$_lang['description_desc'] = 'A short description of the Setting. This can be a Lexicon Entry based on the key, following the format "setting_" + key + "_desc".';
$_lang['key_desc'] = 'The key for the Setting. It will be available in your content via the [[++key]] placeholder.';
$_lang['name_desc'] = 'A Name for the Setting. This can be a Lexicon Entry based on the key, following the format "setting_" + key.';
$_lang['namespace'] = 'Namespace (ονοματόχωρος)';
$_lang['namespace_desc'] = 'The Namespace that this Setting is associated with. The default Lexicon Topic will be loaded for this Namespace when grabbing Settings.';
$_lang['namespace_filter'] = 'Filter by namespace...';
$_lang['setting_err'] = 'Please check your data for the following fields: ';
$_lang['setting_err_ae'] = 'Setting with that key already exists. Please specify another key name.';
$_lang['setting_err_nf'] = 'Setting not found.';
$_lang['setting_err_ns'] = 'Setting not specified';
$_lang['setting_err_not_editable'] = 'This setting can\'t be edited in the grid. Please use the gear/context menu to edit the value!';
$_lang['setting_err_remove'] = 'An error occurred while trying to delete the setting.';
$_lang['setting_err_save'] = 'An error occurred while trying to save the setting.';
$_lang['setting_err_startint'] = 'Settings may not start with an integer.';
$_lang['setting_err_invalid_document'] = 'There is no document with ID %d. Please specify an existing document.';
$_lang['setting_remove_confirm'] = 'Are you sure you want to delete this setting? This might break your MODX installation.';
$_lang['settings_after_install'] = 'As this is a new install, you are required to control these settings, and change any that you may wish to. After you\'ve controlled the settings, press \'Save\' to update the settings database.<br /><br />';
$_lang['settings_desc'] = 'Here you can set general preferences and configuration settings for the MODX manager interface, as well as how your MODX site runs. <b>Each setting will be available via the [[++key]] placeholder.</b><br />Double-click on the value column for the setting you\'d like to edit to dynamically edit via the grid, or right-click on a setting for more options. You can also click the "+" sign for a description of the setting.';
$_lang['settings_furls'] = 'Friendly URLs';
$_lang['settings_misc'] = 'Miscellaneous';
$_lang['settings_site'] = 'Site';
$_lang['settings_ui'] = 'Interface &amp; Features';
$_lang['settings_users'] = 'Χρήστης';
$_lang['system_settings'] = 'Ρυθμίσεις συστήματος';
$_lang['usergroup'] = 'User Group';

// user settings
$_lang['setting_access_category_enabled'] = 'Check Category Access';
$_lang['setting_access_category_enabled_desc'] = 'Use this to enable or disable Category ACL checks (per Context). <strong>NOTE: If this option is set to no, then ALL Category Access Permissions will be ignored!</strong>';

$_lang['setting_access_context_enabled'] = 'Check Context Access';
$_lang['setting_access_context_enabled_desc'] = 'Use this to enable or disable Context ACL checks. <strong>NOTE: If this option is set to no, then ALL Context Access Permissions will be ignored. DO NOT disable this system-wide or for the mgr Context or you will disable access to the manager interface.</strong>';

$_lang['setting_access_resource_group_enabled'] = 'Check Resource Group Access';
$_lang['setting_access_resource_group_enabled_desc'] = 'Use this to enable or disable Resource Group ACL checks (per Context). <strong>NOTE: If this option is set to no, then ALL Resource Group Access Permissions will be ignored!</strong>';

$_lang['setting_allow_mgr_access'] = 'Manager Interface Access';
$_lang['setting_allow_mgr_access_desc'] = 'Select this option to enable or disable access to the manager interface. <strong>NOTE: If this option is set to no, then the user will be redirected to the Manager Login Startup or to the Site Start web page.</strong>';

$_lang['setting_failed_login'] = 'Failed Login Attempts';
$_lang['setting_failed_login_desc'] = 'Here you can enter the number of failed login attempts that are allowed before a user is blocked.';

$_lang['setting_login_allowed_days'] = 'Allowed Days';
$_lang['setting_login_allowed_days_desc'] = 'Select the days that this user is allowed to login.';

$_lang['setting_login_allowed_ip'] = 'Allowed IP Address';
$_lang['setting_login_allowed_ip_desc'] = 'Enter the IP addresses that this user is allowed to log in from. <strong>NOTE: Separate multiple IP addresses with a comma (,)</strong>';

$_lang['setting_login_homepage'] = 'Login Home Page';
$_lang['setting_login_homepage_desc'] = 'Enter the ID of the document you want to send the user to after he/she has logged in. <strong>NOTE: Make sure the ID you enter belongs to an existing document, and that it has been published and is accessible by this user!</strong>';

// system settings
$_lang['setting_access_policies_version'] = 'Access Policy Schema Version';
$_lang['setting_access_policies_version_desc'] = 'The version of the Access Policy system. DO NOT CHANGE.';

$_lang['setting_allow_forward_across_contexts'] = 'Allow Forwarding Across Contexts';
$_lang['setting_allow_forward_across_contexts_desc'] = 'When true, Symlinks and modX::sendForward() API calls can forward requests to Resources in other Contexts.';

$_lang['setting_allow_manager_login_forgot_password'] = 'Allow Forgot Password in Manager Login Screen';
$_lang['setting_allow_manager_login_forgot_password_desc'] = 'Setting this to "No" will disable the forgot password ability on the manager login screen.';

$_lang['setting_allow_tags_in_post'] = 'Allow Tags in POST';
$_lang['setting_allow_tags_in_post_desc'] = 'If false, all POST variables will be stripped of HTML script tags, numeric entities, and MODX tags. MODX recommends to leave this set to false for Contexts other than mgr, where it is set to true by default.';

$_lang['setting_allow_tv_eval'] = 'Enable eval in TV bindings';
$_lang['setting_allow_tv_eval_desc'] = 'Select this option to enable or disable eval in TV bindings. If this option is set to no, the code/value will just be handled as regular text.';

$_lang['setting_anonymous_sessions'] = 'Ανώνυμη συνεδρία';
$_lang['setting_anonymous_sessions_desc'] = 'Εάν η επιλογή δεν είναι ενεργοποιημένη, πρόσβαση σε συνεδρίες PHP θα έχουν μόνο οι συνδεδεμένοι χρήστες. Αυτό μπορεί να μειώσει το χρόνο φόρτωσης μιας ιστοσελίδας MODX για τους ανώνυμους χρήστες, εφ\' όσον δεν θα χρειάζονται εξατομικευμένη πρόσβαση στην PHP. Εάν είναι απενεργοποιημένη η επιλογή session_enabled, τότε αυτή η ρύθμιση δεν παίζει κανένα ρόλο, καθώς συνεδρίες PHP δεν θα είναι διαθέσιμες έτσι κι αλλιώς.';

$_lang['setting_archive_with'] = 'Force PCLZip Archives';
$_lang['setting_archive_with_desc'] = 'If true, will use PCLZip instead of ZipArchive as the zip extension. Turn this on if you are getting extractTo errors or are having problems with unzipping in Package Management.';

$_lang['setting_auto_menuindex'] = 'Menu indexing default';
$_lang['setting_auto_menuindex_desc'] = 'Select \'Yes\' to turn on automatic menu index incrementing by default.';

$_lang['setting_auto_check_pkg_updates'] = 'Automatic Check for Package Updates';
$_lang['setting_auto_check_pkg_updates_desc'] = 'If \'Yes\', MODX will automatically check for updates for packages in Package Management. This may slow the loading of the grid.';

$_lang['setting_auto_check_pkg_updates_cache_expire'] = 'Cache Expiration Time for Automatic Package Updates Check';
$_lang['setting_auto_check_pkg_updates_cache_expire_desc'] = 'The number of minutes that Package Management will cache the results for checking for package updates.';

$_lang['setting_allow_multiple_emails'] = 'Allow Duplicate Emails for Users';
$_lang['setting_allow_multiple_emails_desc'] = 'If enabled, Users may share the same email address.';

$_lang['setting_automatic_alias'] = 'Automatically generate alias';
$_lang['setting_automatic_alias_desc'] = 'Select \'Yes\' to have the system automatically generate an alias based on the Resource\'s page title when saving.';

$_lang['setting_automatic_template_assignment'] = 'Automatic Template Assignment';
$_lang['setting_automatic_template_assignment_desc'] = 'Choose how templates are assigned to new Resources on creation. Options include: system (default template from system settings), parent (inherits the parent template), or sibling (inherits the most used sibling template)';

$_lang['setting_base_help_url'] = 'Base Help URL';
$_lang['setting_base_help_url_desc'] = 'The base URL by which to build the Help links in the top right of pages in the manager.';

$_lang['setting_blocked_minutes'] = 'Blocked Minutes';
$_lang['setting_blocked_minutes_desc'] = 'Here you can enter the number of minutes that a user will be blocked for if they reach their maximum number of allowed failed login attempts. Please enter this value as numbers only (no commas, spaces etc.)';

$_lang['setting_cache_alias_map'] = 'Enable Context Alias Map Cache';
$_lang['setting_cache_alias_map_desc'] = 'When enabled, all Resource URIs are cached into the Context. Enable on smaller sites and disable on larger sites for better performance.';

$_lang['setting_use_context_resource_table'] = 'Use the context resource table';
$_lang['setting_use_context_resource_table_desc'] = 'When enabled, context refreshes use the context_resource table. This enables you to programmatically have one resource in multiple contexts. If you do not use those multiple resource contexts via the API, you can set this to false. On large sites you will get a potential performance boost in the manager then.';

$_lang['setting_cache_context_settings'] = 'Enable Context Setting Cache';
$_lang['setting_cache_context_settings_desc'] = 'When enabled, context settings will be cached to reduce load times.';

$_lang['setting_cache_db'] = 'Enable Database Cache';
$_lang['setting_cache_db_desc'] = 'When enabled, objects and raw result sets from SQL queries are cached to significantly reduce database loads.';

$_lang['setting_cache_db_expires'] = 'Expiration Time for DB Cache';
$_lang['setting_cache_db_expires_desc'] = 'This value (in seconds) sets the amount of time cache files last for DB result-set caching.';

$_lang['setting_cache_db_session'] = 'Enable Database Session Cache';
$_lang['setting_cache_db_session_desc'] = 'When enabled, and cache_db is enabled, database sessions will be cached in the DB result-set cache.';

$_lang['setting_cache_db_session_lifetime'] = 'Expiration Time for DB Session Cache';
$_lang['setting_cache_db_session_lifetime_desc'] = 'This value (in seconds) sets the amount of time cache files last for session entries in the DB result-set cache.';

$_lang['setting_cache_default'] = 'Cacheable default';
$_lang['setting_cache_default_desc'] = 'Select \'Yes\' to make all new Resources cacheable by default.';
$_lang['setting_cache_default_err'] = 'Please state whether or not you want documents to be cached by default.';

$_lang['setting_cache_expires'] = 'Expiration Time for Default Cache';
$_lang['setting_cache_expires_desc'] = 'This value (in seconds) sets the amount of time cache files last for default caching.';

$_lang['setting_cache_resource_clear_partial'] = 'Clear Partial Resource Cache for provided contexts';
$_lang['setting_cache_resource_clear_partial_desc'] = 'When enabled, MODX refresh will only clear resource cache for the provided contexts.';

$_lang['setting_cache_format'] = 'Caching Format to Use';
$_lang['setting_cache_format_desc'] = '0 = PHP, 1 = JSON, 2 = serialize. One of the formats';

$_lang['setting_cache_handler'] = 'Caching Handler Class';
$_lang['setting_cache_handler_desc'] = 'The class name of the type handler to use for caching.';

$_lang['setting_cache_lang_js'] = 'Cache Lexicon JS Strings';
$_lang['setting_cache_lang_js_desc'] = 'If set to true, this will use server headers to cache the lexicon strings loaded into JavaScript for the manager interface.';

$_lang['setting_cache_lexicon_topics'] = 'Cache Lexicon Topics';
$_lang['setting_cache_lexicon_topics_desc'] = 'When enabled, all Lexicon Topics will be cached so as to greatly reduce load times for Internationalization functionality. MODX strongly recommends leaving this set to \'Yes\'.';

$_lang['setting_cache_noncore_lexicon_topics'] = 'Cache Non-Core Lexicon Topics';
$_lang['setting_cache_noncore_lexicon_topics_desc'] = 'When disabled, non-core Lexicon Topics will be not be cached. This is useful to disable when developing your own Extras.';

$_lang['setting_cache_resource'] = 'Enable Partial Resource Cache';
$_lang['setting_cache_resource_desc'] = 'Partial resource caching is configurable by resource when this feature is enabled.  Disabling this feature will disable it globally.';

$_lang['setting_cache_resource_expires'] = 'Expiration Time for Partial Resource Cache';
$_lang['setting_cache_resource_expires_desc'] = 'This value (in seconds) sets the amount of time cache files last for partial Resource caching.';

$_lang['setting_cache_scripts'] = 'Enable Script Cache';
$_lang['setting_cache_scripts_desc'] = 'When enabled, MODX will cache all Scripts (Snippets and Plugins) to file to reduce load times. MODX recommends leaving this set to \'Yes\'.';

$_lang['setting_cache_system_settings'] = 'Enable System Setting Cache';
$_lang['setting_cache_system_settings_desc'] = 'When enabled, system settings will be cached to reduce load times. MODX recommends leaving this on.';

$_lang['setting_clear_cache_refresh_trees'] = 'Refresh Trees on Site Cache Clear';
$_lang['setting_clear_cache_refresh_trees_desc'] = 'When enabled, will refresh the trees after clearing the site cache.';

$_lang['setting_compress_css'] = 'Use Compressed CSS';
$_lang['setting_compress_css_desc'] = 'Όταν η επιλογή αυτή είναι ενεργοποιημένη, τα CSS που χρησιμοποιούνται στο manager του MODX θα είναι συμπιεσμένα.';

$_lang['setting_compress_js'] = 'Use Compressed JavaScript Libraries';
$_lang['setting_compress_js_desc'] = 'Όταν η επιλογή αυτή είναι ενεργοποιημένη, το MODX θα διαθέτει τις δέσμες ενεργειών σε συμπιεσμένη μορφή.';

$_lang['setting_compress_js_groups'] = 'Use Grouping When Compressing JavaScript';
$_lang['setting_compress_js_groups_desc'] = 'Group the core MODX manager JavaScript using minify\'s groupsConfig. Set to Yes if using suhosin or other limiting factors.';

$_lang['setting_concat_js'] = 'Use Concatenated Javascript Libraries';
$_lang['setting_concat_js_desc'] = 'When this is enabled, MODX will use a concatenated version of its common JavaScript libraries in the manager interface. This greatly reduces load and execution time within the manager. Disable only if you are modifying core elements.';

$_lang['setting_confirm_navigation'] = 'Confirm Navigation with unsaved changes';
$_lang['setting_confirm_navigation_desc'] = 'When this is enabled, the user will be prompted to confirm their intention if there are unsaved changes.';

$_lang['setting_container_suffix'] = 'Container Suffix';
$_lang['setting_container_suffix_desc'] = 'The suffix to append to Resources set as containers when using FURLs.';

$_lang['setting_context_tree_sort'] = 'Enable Sorting of Contexts in Resource Tree';
$_lang['setting_context_tree_sort_desc'] = 'If set to Yes, Contexts will be alphanumerically sorted in the left-hand Resources tree.';
$_lang['setting_context_tree_sortby'] = 'Sort Field of Contexts in Resource Tree';
$_lang['setting_context_tree_sortby_desc'] = 'The field to sort Contexts by in the Resources tree, if sorting is enabled.';
$_lang['setting_context_tree_sortdir'] = 'Sort Direction of Contexts in Resource Tree';
$_lang['setting_context_tree_sortdir_desc'] = 'The direction to sort Contexts in the Resources tree, if sorting is enabled.';

$_lang['setting_cultureKey'] = 'Γλώσσα';
$_lang['setting_cultureKey_desc'] = 'Select the language for all non-manager Contexts, including web.';

$_lang['setting_date_timezone'] = 'Default Time Zone';
$_lang['setting_date_timezone_desc'] = 'Controls the default timezone setting for PHP date functions, if not empty. If empty and the PHP date.timezone ini setting is not set in your environment, UTC will be assumed.';

$_lang['setting_debug'] = 'Debug';
$_lang['setting_debug_desc'] = 'Controls turning debugging on/off in MODX and/or sets the PHP error_reporting level. \'\' = use current error_reporting, \'0\' = false (error_reporting = 0), \'1\' = true (error_reporting = -1), or any valid error_reporting value (as an integer).';

$_lang['setting_default_content_type'] = 'Default Content Type';
$_lang['setting_default_content_type_desc'] = 'Select the default Content Type you wish to use for new Resources. You can still select a different Content Type in the Resource editor; this setting just pre-selects one of your Content Types for you.';

$_lang['setting_default_duplicate_publish_option'] = 'Default Duplicate Resource Publishing Option';
$_lang['setting_default_duplicate_publish_option_desc'] = 'The default selected option when duplicating a Resource. Can be either "unpublish" to unpublish all duplicates, "publish" to publish all duplicates, or "preserve" to preserve the publish state based on the duplicated Resource.';

$_lang['setting_default_media_source'] = 'Default Media Source';
$_lang['setting_default_media_source_desc'] = 'The default Media Source to load.';

$_lang['setting_default_media_source_type'] = 'Default Media Source Type';
$_lang['setting_default_media_source_type_desc'] = 'The default selected Media Source Type when creating a new Media Source.';

$_lang['setting_photo_profile_source'] = 'User Profile Photo Source';
$_lang['setting_photo_profile_source_desc'] = 'Specifies the Media Source to use for storing and retrieving profile photos/avatars. If not specified, the default Media Source will be used.';

$_lang['setting_default_template'] = 'Default Template';
$_lang['setting_default_template_desc'] = 'Select the default Template you wish to use for new Resources. You can still select a different template in the Resource editor, this setting just pre-selects one of your Templates for you.';

$_lang['setting_default_per_page'] = 'Default Per Page';
$_lang['setting_default_per_page_desc'] = 'The default number of results to show in grids throughout the manager.';

$_lang['setting_emailsender'] = 'Registration Email From Address';
$_lang['setting_emailsender_desc'] = 'Here you can specify the email address used when sending Users their usernames and passwords.';
$_lang['setting_emailsender_err'] = 'Please state the administration email address.';

$_lang['setting_enable_dragdrop'] = 'Enable Drag/Drop in Resource/Element Trees';
$_lang['setting_enable_dragdrop_desc'] = 'If off, will prevent dragging and dropping in Resource and Element trees.';

$_lang['setting_enable_template_picker_in_tree'] = 'Enable the Template Picker in Resource Trees';
$_lang['setting_enable_template_picker_in_tree_desc'] = 'Enable this to use the template picker modal window when creating a new resource in the tree.';

$_lang['setting_error_page'] = 'Error Page';
$_lang['setting_error_page_desc'] = 'Enter the ID of the document you want to send users to if they request a document which doesn\'t actually exist (404 Page Not Found). <strong>NOTE: make sure this ID you enter belongs to an existing document, and that it has been published!</strong>';
$_lang['setting_error_page_err'] = 'Please specify a document ID that is the error page.';

$_lang['setting_ext_debug'] = 'ExtJS debug';
$_lang['setting_ext_debug_desc'] = 'Whether or not to load ext-all-debug.js to help debug your ExtJS code.';

$_lang['setting_extension_packages'] = 'Extension Packages';
$_lang['setting_extension_packages_desc'] = 'A JSON array of packages to load on MODX instantiation. In the format [{"packagename":{"path":"path/to/package"}},{"anotherpackagename":{"path":"path/to/otherpackage"}}]';

$_lang['setting_enable_gravatar'] = 'Enable Gravatar';
$_lang['setting_enable_gravatar_desc'] = 'If enabled, Gravatar will be used as a profile image (if user do not have profile photo uploaded).';

$_lang['setting_failed_login_attempts'] = 'Failed Login Attempts';
$_lang['setting_failed_login_attempts_desc'] = 'The number of failed login attempts a User is allowed before becoming \'blocked\'.';

$_lang['setting_feed_modx_news'] = 'MODX News Feed URL';
$_lang['setting_feed_modx_news_desc'] = 'Set the URL for the RSS feed for the MODX News panel in the manager.';

$_lang['setting_feed_modx_news_enabled'] = 'MODX News Feed Enabled';
$_lang['setting_feed_modx_news_enabled_desc'] = 'If \'No\', MODX will hide the News feed in the welcome section of the manager.';

$_lang['setting_feed_modx_security'] = 'MODX Security Notices Feed URL';
$_lang['setting_feed_modx_security_desc'] = 'Set the URL for the RSS feed for the MODX Security Notices panel in the manager.';

$_lang['setting_feed_modx_security_enabled'] = 'MODX Security Feed Enabled';
$_lang['setting_feed_modx_security_enabled_desc'] = 'If \'No\', MODX will hide the Security feed in the welcome section of the manager.';

$_lang['setting_form_customization_use_all_groups'] = 'Use All User Group Memberships for Form Customization';
$_lang['setting_form_customization_use_all_groups_desc'] = 'If set to true, FC will use *all* Sets for *all* User Groups a member is in when applying Form Customization Sets. Otherwise, it will only use the Set belonging to the User\'s Primary Group. Note: setting this to Yes might cause bugs with conflicting FC Sets.';

$_lang['setting_forward_merge_excludes'] = 'sendForward Exclude Fields on Merge';
$_lang['setting_forward_merge_excludes_desc'] = 'A Symlink merges non-empty field values over the values in the target Resource; using this comma-delimited list of excludes prevents specified fields from being overridden by the Symlink.';

$_lang['setting_friendly_alias_lowercase_only'] = 'FURL Lowercase Aliases';
$_lang['setting_friendly_alias_lowercase_only_desc'] = 'Determines whether to allow only lowercase characters in a Resource alias.';

$_lang['setting_friendly_alias_max_length'] = 'FURL Alias Maximum Length';
$_lang['setting_friendly_alias_max_length_desc'] = 'If greater than zero, the maximum number of characters to allow in a Resource alias. Zero equals unlimited.';

$_lang['setting_friendly_alias_realtime'] = 'FURL Alias Real-Time';
$_lang['setting_friendly_alias_realtime_desc'] = 'Determines whether a resource alias should be created on the fly when typing the pagetitle or if this should happen when the resource is saved (automatic_alias needs to be enabled for this to have an effect).';

$_lang['setting_friendly_alias_restrict_chars'] = 'FURL Alias Character Restriction Method';
$_lang['setting_friendly_alias_restrict_chars_desc'] = 'The method used to restrict characters used in a Resource alias. "pattern" allows a RegEx pattern to be provided, "legal" allows any legal URL characters, "alpha" allows only letters of the alphabet, and "alphanumeric" allows only letters and numbers.';

$_lang['setting_friendly_alias_restrict_chars_pattern'] = 'FURL Alias Character Restriction Pattern';
$_lang['setting_friendly_alias_restrict_chars_pattern_desc'] = 'A valid RegEx pattern for restricting characters used in a Resource alias.';

$_lang['setting_friendly_alias_strip_element_tags'] = 'FURL Alias Strip Element Tags';
$_lang['setting_friendly_alias_strip_element_tags_desc'] = 'Determines if Element tags should be stripped from a Resource alias.';

$_lang['setting_friendly_alias_translit'] = 'FURL Alias Transliteration';
$_lang['setting_friendly_alias_translit_desc'] = 'The method of transliteration to use on an alias specified for a Resource. Empty or "none" is the default which skips transliteration. Other possible values are "iconv" (if available) or a named transliteration table provided by a custom transliteration service class.';

$_lang['setting_friendly_alias_translit_class'] = 'FURL Alias Transliteration Service Class';
$_lang['setting_friendly_alias_translit_class_desc'] = 'An optional service class to provide named transliteration services for FURL Alias generation/filtering.';

$_lang['setting_friendly_alias_translit_class_path'] = 'FURL Alias Transliteration Service Class Path';
$_lang['setting_friendly_alias_translit_class_path_desc'] = 'The model package location where the FURL Alias Transliteration Service Class will be loaded from.';

$_lang['setting_friendly_alias_trim_chars'] = 'FURL Alias Trim Characters';
$_lang['setting_friendly_alias_trim_chars_desc'] = 'Characters to trim from the ends of a provided Resource alias.';

$_lang['setting_friendly_alias_word_delimiter'] = 'FURL Alias Word Delimiter';
$_lang['setting_friendly_alias_word_delimiter_desc'] = 'The preferred word delimiter for friendly URL alias slugs.';

$_lang['setting_friendly_alias_word_delimiters'] = 'FURL Alias Word Delimiters';
$_lang['setting_friendly_alias_word_delimiters_desc'] = 'Characters which represent word delimiters when processing friendly URL alias slugs. These characters will be converted and consolidated to the preferred FURL alias word delimiter.';

$_lang['setting_friendly_urls'] = 'Use Friendly URLs';
$_lang['setting_friendly_urls_desc'] = 'This allows you to use search engine friendly URLs with MODX. Please note, this only works for MODX installations running on Apache, and you\'ll need to write an .htaccess file for this to work. See the .htaccess file included in the distribution for more info.';
$_lang['setting_friendly_urls_err'] = 'Please state whether or not you want to use friendly URLs.';

$_lang['setting_friendly_urls_strict'] = 'Use Strict Friendly URLs';
$_lang['setting_friendly_urls_strict_desc'] = 'When friendly URLs are enabled, this option forces non-canonical requests that match a Resource to 301 redirect to the canonical URI for that Resource. WARNING: Do not enable if you use custom rewrite rules which do not match at least the beginning of the canonical URI. For example, a canonical URI of foo/ with custom rewrites for foo/bar.html would work, but attempts to rewrite bar/foo.html as foo/ would force a redirect to foo/ with this option enabled.';

$_lang['setting_global_duplicate_uri_check'] = 'Check for Duplicate URIs Across All Contexts';
$_lang['setting_global_duplicate_uri_check_desc'] = 'Select \'Yes\' to make duplicate URI checks include all Contexts in the search. Otherwise, only the Context the Resource is being saved in is checked.';

$_lang['setting_hidemenu_default'] = 'Hide From Menus Default';
$_lang['setting_hidemenu_default_desc'] = 'Select \'Yes\' to make all new resources hidden from menus by default.';

$_lang['setting_inline_help'] = 'Show Inline Help Text for Fields';
$_lang['setting_inline_help_desc'] = 'If \'Yes\', then fields will display their help text directly below the field. If \'No\', all fields will have tooltip-based help.';

$_lang['setting_link_tag_scheme'] = 'URL Generation Scheme';
$_lang['setting_link_tag_scheme_desc'] = 'URL generation scheme for tag [[~id]]. Available options <a href="http://api.modx.com/revolution/2.2/db_core_model_modx_modx.class.html#\modX::makeUrl()" target="_blank">here</a>.';

$_lang['setting_locale'] = 'Locale';
$_lang['setting_locale_desc'] = 'Set the locale for the system. Leave blank to use the default. See <a href="http://php.net/setlocale" target="_blank">the PHP documentation</a> for more information.';

$_lang['setting_lock_ttl'] = 'Lock Time-to-Live';
$_lang['setting_lock_ttl_desc'] = 'The number of seconds a lock on a Resource will remain for if the user is inactive.';

$_lang['setting_log_level'] = 'Logging Level';
$_lang['setting_log_level_desc'] = 'The default logging level; the lower the level, the fewer messages that are logged. Available options: 0 (FATAL), 1 (ERROR), 2 (WARN), 3 (INFO), and 4 (DEBUG).';

$_lang['setting_log_target'] = 'Logging Target';
$_lang['setting_log_target_desc'] = 'The default logging target where log messages are written. Available options: \'FILE\', \'HTML\', or \'ECHO\'. Default is \'FILE\' if not specified.';

$_lang['setting_log_deprecated'] = 'Log Deprecated Functions';
$_lang['setting_log_deprecated_desc'] = 'Enable to receive notices in your error log when deprecated functions are used.';

$_lang['setting_mail_charset'] = 'Mail Charset';
$_lang['setting_mail_charset_desc'] = 'The default charset for emails, e.g., \'iso-8859-1\' or \'utf-8\'';

$_lang['setting_mail_encoding'] = 'Mail Encoding';
$_lang['setting_mail_encoding_desc'] = 'Sets the Encoding of the message. Options for this are "8bit", "7bit", "binary", "base64", and "quoted-printable".';

$_lang['setting_mail_use_smtp'] = 'Use SMTP';
$_lang['setting_mail_use_smtp_desc'] = 'If true, MODX will attempt to use SMTP in mail functions.';

$_lang['setting_mail_smtp_auth'] = 'SMTP Authentication';
$_lang['setting_mail_smtp_auth_desc'] = 'Sets SMTP authentication. Utilizes the mail_smtp_user and mail_smtp_pass settings.';

$_lang['setting_mail_smtp_helo'] = 'SMTP Helo Message';
$_lang['setting_mail_smtp_helo_desc'] = 'Sets the SMTP HELO of the message (Defaults to the hostname).';

$_lang['setting_mail_smtp_hosts'] = 'SMTP Hosts';
$_lang['setting_mail_smtp_hosts_desc'] = 'Sets the SMTP hosts.  All hosts must be separated by a semicolon.  You can also specify a different port for each host by using this format: [hostname:port] (e.g., "smtp1.example.com:25;smtp2.example.com"). Hosts will be tried in order.';

$_lang['setting_mail_smtp_keepalive'] = 'SMTP Keep-Alive';
$_lang['setting_mail_smtp_keepalive_desc'] = 'Prevents the SMTP connection from being closed after each mail sending. Not recommended.';

$_lang['setting_mail_smtp_pass'] = 'SMTP Password';
$_lang['setting_mail_smtp_pass_desc'] = 'The password to authenticate to SMTP against.';

$_lang['setting_mail_smtp_port'] = 'SMTP Port';
$_lang['setting_mail_smtp_port_desc'] = 'Sets the default SMTP server port.';

$_lang['setting_mail_smtp_secure'] = 'SMTP Secure';
$_lang['setting_mail_smtp_secure_desc'] = 'Ορίζει τον τύπο ασφαλούς κρυπτογράφησης SMTP. Οι επιλογές είναι "", "ssl" ή "tls"';

$_lang['setting_mail_smtp_autotls'] = 'SMTP Auto TLS';
$_lang['setting_mail_smtp_autotls_desc'] = 'Αν θα γίνεται αυτόματη ενεργοποίηση της κρυπτογράφησης TLS αν ένας διακομιστής το υποστηρίζει, ακόμη και αν το "SMTP Secure" δεν έχει οριστεί σε "tls"';

$_lang['setting_mail_smtp_single_to'] = 'SMTP Single To';
$_lang['setting_mail_smtp_single_to_desc'] = 'Provides the ability to have the TO field process individual emails, instead of sending to entire TO addresses.';

$_lang['setting_mail_smtp_timeout'] = 'SMTP Timeout';
$_lang['setting_mail_smtp_timeout_desc'] = 'Sets the SMTP server timeout in seconds. This function will not work in win32 servers.';

$_lang['setting_mail_smtp_user'] = 'SMTP User';
$_lang['setting_mail_smtp_user_desc'] = 'The user to authenticate to SMTP against.';

$_lang['setting_main_nav_parent'] = 'Main menu parent';
$_lang['setting_main_nav_parent_desc'] = 'The container used to pull all records for the main menu.';

$_lang['setting_manager_direction'] = 'Manager Text Direction';
$_lang['setting_manager_direction_desc'] = 'Choose the direction that the text will be rendered in the Manager, left to right or right to left.';

$_lang['setting_manager_date_format'] = 'Manager Date Format';
$_lang['setting_manager_date_format_desc'] = 'The format string, in PHP date() format, for the dates represented in the manager.';

$_lang['setting_manager_favicon_url'] = 'Manager Favicon URL';
$_lang['setting_manager_favicon_url_desc'] = 'If set, will load this URL as a favicon for the MODX manager. Must be a relative URL to the manager/ directory, or an absolute URL.';

$_lang['setting_manager_login_url_alternate'] = 'Alternate Manager Login URL';
$_lang['setting_manager_login_url_alternate_desc'] = 'An alternate URL to send an unauthenticated user to when they need to login to the manager. The login form there must login the user to the "mgr" context to work.';

$_lang['setting_manager_tooltip_enable'] = 'Enable Manager Tooltips';
$_lang['setting_manager_tooltip_delay'] = 'Delay Time for Manager Tooltips';

$_lang['setting_login_background_image'] = 'Login Background Image';
$_lang['setting_login_background_image_desc'] = 'The background image to use in the manager login. This will automatically stretch to fill the screen.';

$_lang['setting_login_logo'] = 'Login Logo';
$_lang['setting_login_logo_desc'] = 'The logo to show in the top left of the manager login. When left empty, it will show the MODX logo.';

$_lang['setting_login_help_button'] = 'Show Help Button';
$_lang['setting_login_help_button_desc'] = 'When enabled you will find a help button on the login screen. It\'s possible to customize the information shown with the following lexicon entries in core/login: login_help_button_text, login_help_title, and login_help_text.';

$_lang['setting_manager_login_start'] = 'Manager Login Startup';
$_lang['setting_manager_login_start_desc'] = 'Enter the ID of the document you want to send the user to after he/she has logged into the manager. <strong>NOTE: make sure the ID you\'ve entered belongs to an existing document, and that it has been published and is accessible by this user!</strong>';

$_lang['setting_manager_theme'] = 'Manager Theme';
$_lang['setting_manager_theme_desc'] = 'Select the Theme for the Content Manager.';

$_lang['setting_manager_logo'] = 'Manager Logo';
$_lang['setting_manager_logo_desc'] = 'The logo to show in the Content Manager header.';

$_lang['setting_manager_time_format'] = 'Manager Time Format';
$_lang['setting_manager_time_format_desc'] = 'The format string, in PHP date() format, for the time settings represented in the manager.';

$_lang['setting_manager_use_tabs'] = 'Use Tabs in Manager Layout';
$_lang['setting_manager_use_tabs_desc'] = 'If true, the manager will use tabs for rendering the content panes. Otherwise, it will use portals.';

$_lang['setting_manager_week_start'] = 'Week start';
$_lang['setting_manager_week_start_desc'] = 'Define the day starting the week. Use 0 (or leave empty) for sunday, 1 for monday and so on...';

$_lang['setting_mgr_tree_icon_context'] = 'Context tree icon';
$_lang['setting_mgr_tree_icon_context_desc'] = 'Define a CSS class here to be used to display the context icon in the tree. You can use this setting on each context to customize the icon per context.';

$_lang['setting_mgr_source_icon'] = 'Media Source icon';
$_lang['setting_mgr_source_icon_desc'] = 'Indicate a CSS class to be used to display the Media Sources icons in the files tree. Defaults to "icon-folder-open-o"';

$_lang['setting_modRequest.class'] = 'Request Handler Class';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_browser_tree_hide_files'] = 'Media Browser Tree Hide Files';
$_lang['setting_modx_browser_tree_hide_files_desc'] = 'If true the files inside folders are not displayed in the Media Browser source tree.';

$_lang['setting_modx_browser_tree_hide_tooltips'] = 'Media Browser Tree Hide Tooltips';
$_lang['setting_modx_browser_tree_hide_tooltips_desc'] = 'If true, no image preview tooltips are shown when hovering over a file in the Media Browser tree. Defaults to true.';

$_lang['setting_modx_browser_default_sort'] = 'Media Browser Default Sort';
$_lang['setting_modx_browser_default_sort_desc'] = 'The default sort method when using the Media Browser in the manager. Available values are: name, size, lastmod (last modified).';

$_lang['setting_modx_browser_default_viewmode'] = 'Media Browser Default View Mode';
$_lang['setting_modx_browser_default_viewmode_desc'] = 'The default view mode when using the Media Browser in the manager. Available values are: grid, list.';

$_lang['setting_modx_charset'] = 'Character encoding';
$_lang['setting_modx_charset_desc'] = 'Please select which character encoding you wish to use. Please note that MODX has been tested with a number of these encodings, but not all of them. For most languages, the default setting of UTF-8 is preferable.';

$_lang['setting_new_file_permissions'] = 'New File Permissions';
$_lang['setting_new_file_permissions_desc'] = 'When uploading a new file in the File Manager, the File Manager will attempt to change the file permissions to those entered in this setting. This may not work on some setups, such as IIS, in which case you will need to manually change the permissions.';

$_lang['setting_new_folder_permissions'] = 'New Folder Permissions';
$_lang['setting_new_folder_permissions_desc'] = 'When creating a new folder in the File Manager, the File Manager will attempt to change the folder permissions to those entered in this setting. This may not work on some setups, such as IIS, in which case you will need to manually change the permissions.';

$_lang['setting_parser_recurse_uncacheable'] = 'Delay Uncacheable Parsing';
$_lang['setting_parser_recurse_uncacheable_desc'] = 'If disabled, uncacheable elements may have their output cached inside cacheable element content. Disable this ONLY if you are having problems with complex nested parsing which stopped working as expected.';

$_lang['setting_password_generated_length'] = 'Password Auto-Generated Length';
$_lang['setting_password_generated_length_desc'] = 'The length of the auto-generated password for a User.';

$_lang['setting_password_min_length'] = 'Minimum Password Length';
$_lang['setting_password_min_length_desc'] = 'The minimum length for a password for a User.';

$_lang['setting_preserve_menuindex'] = 'Διατήρηση του ευρετηρίου πλοήγησης όταν διπλασιάζονται Πηγές';
$_lang['setting_preserve_menuindex_desc'] = 'Όταν διπλασιάζετε κάποιες Πηγές, το ευρετηρίο πλοήγησης θα διατηρείται το ίδιο.';

$_lang['setting_principal_targets'] = 'ACL Targets to Load';
$_lang['setting_principal_targets_desc'] = 'Customize the ACL targets to load for MODX Users.';

$_lang['setting_proxy_auth_type'] = 'Proxy Authentication Type';
$_lang['setting_proxy_auth_type_desc'] = 'Supports either BASIC or NTLM.';

$_lang['setting_proxy_host'] = 'Proxy Host';
$_lang['setting_proxy_host_desc'] = 'If your server is using a proxy, set the hostname here to enable MODX features that might need to use the proxy, such as Package Management.';

$_lang['setting_proxy_password'] = 'Proxy Password';
$_lang['setting_proxy_password_desc'] = 'The password required to authenticate to your proxy server.';

$_lang['setting_proxy_port'] = 'Proxy Port';
$_lang['setting_proxy_port_desc'] = 'The port for your proxy server.';

$_lang['setting_proxy_username'] = 'Proxy Username';
$_lang['setting_proxy_username_desc'] = 'The username to authenticate against with your proxy server.';

$_lang['setting_phpthumb_allow_src_above_docroot'] = 'phpThumb Allow src Above Document Root';
$_lang['setting_phpthumb_allow_src_above_docroot_desc'] = 'Indicates if the src path is allowed outside the document root. This is useful for multi-context deployments with multiple virtual hosts.';

$_lang['setting_phpthumb_cache_maxage'] = 'phpThumb Max Cache Age';
$_lang['setting_phpthumb_cache_maxage_desc'] = 'Delete cached thumbnails that have not been accessed in more than X days.';

$_lang['setting_phpthumb_cache_maxsize'] = 'phpThumb Max Cache Size';
$_lang['setting_phpthumb_cache_maxsize_desc'] = 'Delete least-recently-accessed thumbnails when cache grows bigger than X megabytes in size.';

$_lang['setting_phpthumb_cache_maxfiles'] = 'phpThumb Max Cache Files';
$_lang['setting_phpthumb_cache_maxfiles_desc'] = 'Delete least-recently-accessed thumbnails when cache has more than X files.';

$_lang['setting_phpthumb_cache_source_enabled'] = 'phpThumb Cache Source Files';
$_lang['setting_phpthumb_cache_source_enabled_desc'] = 'Whether or not to cache source files as they are loaded. Recommended to off.';

$_lang['setting_phpthumb_document_root'] = 'PHPThumb Document Root';
$_lang['setting_phpthumb_document_root_desc'] = 'Set this if you are experiencing issues with the server variable DOCUMENT_ROOT, or getting errors with OutputThumbnail or !is_resource. Set it to the absolute document root path you would like to use. If this is empty, MODX will use the DOCUMENT_ROOT server variable.';

$_lang['setting_phpthumb_error_bgcolor'] = 'phpThumb Error Background Color';
$_lang['setting_phpthumb_error_bgcolor_desc'] = 'A hex value, without the #, indicating a background color for phpThumb error output.';

$_lang['setting_phpthumb_error_fontsize'] = 'phpThumb Error Font Size';
$_lang['setting_phpthumb_error_fontsize_desc'] = 'An em value indicating a font size to use for text appearing in phpThumb error output.';

$_lang['setting_phpthumb_error_textcolor'] = 'phpThumb Error Font Color';
$_lang['setting_phpthumb_error_textcolor_desc'] = 'A hex value, without the #, indicating a font color for text appearing in phpThumb error output.';

$_lang['setting_phpthumb_far'] = 'phpThumb Force Aspect Ratio';
$_lang['setting_phpthumb_far_desc'] = 'The default far setting for phpThumb when used in MODX. Defaults to C to force aspect ratio toward the center.';

$_lang['setting_phpthumb_imagemagick_path'] = 'phpThumb ImageMagick Path';
$_lang['setting_phpthumb_imagemagick_path_desc'] = 'Optional. Set an alternative ImageMagick path here for generating thumbnails with phpThumb, if it is not in the PHP default.';

$_lang['setting_phpthumb_nohotlink_enabled'] = 'phpThumb Hotlinking Disabled';
$_lang['setting_phpthumb_nohotlink_enabled_desc'] = 'Remote servers are allowed in the src parameter unless you disable hotlinking in phpThumb.';

$_lang['setting_phpthumb_nohotlink_erase_image'] = 'phpThumb Hotlinking Erase Image';
$_lang['setting_phpthumb_nohotlink_erase_image_desc'] = 'Indicates if an image generated from a remote server should be erased when not allowed.';

$_lang['setting_phpthumb_nohotlink_text_message'] = 'phpThumb Hotlinking Not Allowed Message';
$_lang['setting_phpthumb_nohotlink_text_message_desc'] = 'A message that is rendered instead of the thumbnail when a hotlinking attempt is rejected.';

$_lang['setting_phpthumb_nohotlink_valid_domains'] = 'phpThumb Hotlinking Valid Domains';
$_lang['setting_phpthumb_nohotlink_valid_domains_desc'] = 'A comma-delimited list of hostnames that are valid in src URLs.';

$_lang['setting_phpthumb_nooffsitelink_enabled'] = 'phpThumb Offsite Linking Disabled';
$_lang['setting_phpthumb_nooffsitelink_enabled_desc'] = 'Disables the ability for others to use phpThumb to render images on their own sites.';

$_lang['setting_phpthumb_nooffsitelink_erase_image'] = 'phpThumb Offsite Linking Erase Image';
$_lang['setting_phpthumb_nooffsitelink_erase_image_desc'] = 'Indicates if an image linked from a remote server should be erased when not allowed.';

$_lang['setting_phpthumb_nooffsitelink_require_refer'] = 'phpThumb Offsite Linking Require Referrer';
$_lang['setting_phpthumb_nooffsitelink_require_refer_desc'] = 'If enabled, any offsite linking attempts will be rejected without a valid referrer header.';

$_lang['setting_phpthumb_nooffsitelink_text_message'] = 'phpThumb Offsite Linking Not Allowed Message';
$_lang['setting_phpthumb_nooffsitelink_text_message_desc'] = 'A message that is rendered instead of the thumbnail when an offsite linking attempt is rejected.';

$_lang['setting_phpthumb_nooffsitelink_valid_domains'] = 'phpThumb Offsite Linking Valid Domains';
$_lang['setting_phpthumb_nooffsitelink_valid_domains_desc'] = 'A comma-delimited list of hostnames that are valid referrers for offsite linking.';

$_lang['setting_phpthumb_nooffsitelink_watermark_src'] = 'phpThumb Offsite Linking Watermark Source';
$_lang['setting_phpthumb_nooffsitelink_watermark_src_desc'] = 'Optional. A valid file system path to a file to use as a watermark source when your images are rendered offsite by phpThumb.';

$_lang['setting_phpthumb_zoomcrop'] = 'phpThumb Zoom-Crop';
$_lang['setting_phpthumb_zoomcrop_desc'] = 'The default zc setting for phpThumb when used in MODX. Defaults to 0 to prevent zoom cropping.';

$_lang['setting_publish_default'] = 'Published default';
$_lang['setting_publish_default_desc'] = 'Select \'Yes\' to make all new resources published by default.';
$_lang['setting_publish_default_err'] = 'Please state whether or not you want documents to be published by default.';

$_lang['setting_quick_search_in_content'] = 'Allow search in content';
$_lang['setting_quick_search_in_content_desc'] = 'If \'Yes\', then the content of the element (resource, template, chunk, etc.) will also be available for quick search.';

$_lang['setting_quick_search_result_max'] = 'Number of items in search result';
$_lang['setting_quick_search_result_max_desc'] = 'Maximum number of elements for each type (resource, template, chunk, etc.) in the quick search result.';

$_lang['setting_request_controller'] = 'Request Controller Filename';
$_lang['setting_request_controller_desc'] = 'The filename of the main request controller from which MODX is loaded. Most users can leave this as index.php.';

$_lang['setting_request_method_strict'] = 'Strict Request Method';
$_lang['setting_request_method_strict_desc'] = 'If enabled, requests via the Request ID Parameter will be ignored with FURLs enabled, and those via Request Alias Parameter will be ignored without FURLs enabled.';

$_lang['setting_request_param_alias'] = 'Request Alias Parameter';
$_lang['setting_request_param_alias_desc'] = 'The name of the GET parameter to identify Resource aliases when redirecting with FURLs.';

$_lang['setting_request_param_id'] = 'Request ID Parameter';
$_lang['setting_request_param_id_desc'] = 'The name of the GET parameter to identify Resource IDs when not using FURLs.';

$_lang['setting_resource_tree_node_name'] = 'Resource Tree Node Field';
$_lang['setting_resource_tree_node_name_desc'] = 'Specify the Resource field to use when rendering the nodes in the Resource Tree. Defaults to pagetitle, although any Resource field can be used, such as menutitle, alias, longtitle, etc.';

$_lang['setting_resource_tree_node_name_fallback'] = 'Resource Tree Node Fallback Field';
$_lang['setting_resource_tree_node_name_fallback_desc'] = 'Specify the Resource field to use as fallback when rendering the nodes in the Resource Tree. This will be used if the resource has an empty value for the configured Resource Tree Node Field.';

$_lang['setting_resource_tree_node_tooltip'] = 'Resource Tree Tooltip Field';
$_lang['setting_resource_tree_node_tooltip_desc'] = 'Specify the Resource field to use when rendering the nodes in the Resource Tree. Any Resource field can be used, such as menutitle, alias, longtitle, etc. If blank, will be the longtitle with a description underneath.';

$_lang['setting_richtext_default'] = 'Richtext Default';
$_lang['setting_richtext_default_desc'] = 'Select \'Yes\' to make all new Resources use the Richtext Editor by default.';

$_lang['setting_search_default'] = 'Searchable Default';
$_lang['setting_search_default_desc'] = 'Select \'Yes\' to make all new resources searchable by default.';
$_lang['setting_search_default_err'] = 'Please specify whether or not you want documents to be searchable by default.';

$_lang['setting_server_offset_time'] = 'Server offset time';
$_lang['setting_server_offset_time_desc'] = 'Select the number of hours time difference between where you are and where the server is.';

$_lang['setting_session_cookie_domain'] = 'Session Cookie Domain';
$_lang['setting_session_cookie_domain_desc'] = 'Use this setting to customize the session cookie domain. Leave blank to use the current domain.';

$_lang['setting_session_cookie_samesite'] = 'Session Cookie Samesite';
$_lang['setting_session_cookie_samesite_desc'] = 'Choose Lax or Strict.';

$_lang['setting_session_cookie_lifetime'] = 'Session Cookie Lifetime';
$_lang['setting_session_cookie_lifetime_desc'] = 'Use this setting to customize the session cookie lifetime in seconds.  This is used to set the lifetime of a client session cookie when they choose the \'remember me\' option on login.';

$_lang['setting_session_cookie_path'] = 'Session Cookie Path';
$_lang['setting_session_cookie_path_desc'] = 'Use this setting to customize the cookie path for identifying site specific session cookies. Leave blank to use MODX_BASE_URL.';

$_lang['setting_session_cookie_secure'] = 'Session Cookie Secure';
$_lang['setting_session_cookie_secure_desc'] = 'Enable this setting to use secure session cookies. This requires your site to be accessible over https, otherwise your site and/or manager will become inaccessible.';

$_lang['setting_session_cookie_httponly'] = 'Session Cookie HttpOnly';
$_lang['setting_session_cookie_httponly_desc'] = 'Use this setting to set the HttpOnly flag on session cookies.';

$_lang['setting_session_gc_maxlifetime'] = 'Session Garbage Collector Max Lifetime';
$_lang['setting_session_gc_maxlifetime_desc'] = 'Επιτρέπει την προσαρμογή της ρύθμισης PHP session.gc_maxlifetime όταν χρησιμοποιείτε \'MODX\\Revolution\\modSessionHandler\'.';

$_lang['setting_session_handler_class'] = 'Session Handler Class Name';
$_lang['setting_session_handler_class_desc'] = 'Για συνεδρίες που διαχειρίζεται η βάση δεδομένων, χρησιμοποιήστε το \'MODX\\Revolution\\modSessionHandler\'. Αφήστε το κενό για να χρησιμοποιήσετε την τυπική διαχείριση συνεδρίας PHP.';

$_lang['setting_session_name'] = 'Session Name';
$_lang['setting_session_name_desc'] = 'Use this setting to customize the session name used for the sessions in MODX. Leave blank to use the default PHP session name.';

$_lang['setting_settings_version'] = 'Settings Version';
$_lang['setting_settings_version_desc'] = 'The current installed version of MODX.';

$_lang['setting_settings_distro'] = 'Settings Distribution';
$_lang['setting_settings_distro_desc'] = 'The current installed distribution of MODX.';

$_lang['setting_set_header'] = 'Set HTTP Headers';
$_lang['setting_set_header_desc'] = 'When enabled, MODX will attempt to set the HTTP headers for Resources.';

$_lang['setting_send_poweredby_header'] = 'Αποστολή X-Powered-By Header';
$_lang['setting_send_poweredby_header_desc'] = 'Όταν η επιλογή αυτή είναι ενεργοποιημένη, το MODX θα προσθέτει στον header "X-Powered-By", υποδηλώνοντας ότι αυτό το site έχει φτιαχτεί με MODX. Με αυτόν τον τρόπο συμβάλλετε στην καταμέτρηση της χρήσης του MODX. Ωστόσο, στην περίπτωση που βρεθουν προβλήματα ασφαλείας στο MODX, πιθανώς αυτό να επηρεάσει και την δική σας ασφάλεια, μιας και δηλώνετε στους header της ιστοσελίδας σας ότι χρησιμοποιείτε MODX.';

$_lang['setting_show_tv_categories_header'] = 'Show "Categories" Tabs Header with TVs';
$_lang['setting_show_tv_categories_header_desc'] = 'If "Yes", MODX will show the "Categories" header above the first category tab when editing TVs in a Resource.';

$_lang['setting_signupemail_message'] = 'Sign-up email';
$_lang['setting_signupemail_message_desc'] = 'Here you can set the message sent to your users when you create an account for them and let MODX send them an email containing their username and password. <br /><strong>Note:</strong> The following placeholders are replaced by the Content Manager when the message is sent: <br /><br />[[+sname]] - Name of your web site, <br />[[+saddr]] - Your web site email address, <br />[[+surl]] - Your site URL, <br />[[+uid]] - User\'s login name or id, <br />[[+pwd]] - User\'s password, <br />[[+ufn]] - User\'s full name. <br /><br /><strong>Leave the [[+uid]] and [[+pwd]] in the email, or else the username and password won\'t be sent in the mail and your users won\'t know their username or password!</strong>';
$_lang['setting_signupemail_message_default'] = 'Hello [[+uid]] \n\nHere are your login details for [[+sname]] Content Manager:\n\nUsername: [[+uid]]\nPassword: [[+pwd]]\n\nOnce you log into the Content Manager ([[+surl]]), you can change your password.\n\nRegards,\nSite Administrator';

$_lang['setting_site_name'] = 'Site name';
$_lang['setting_site_name_desc'] = 'Enter the name of your site here.';
$_lang['setting_site_name_err']  = 'Please enter a site name.';

$_lang['setting_site_start'] = 'Site start';
$_lang['setting_site_start_desc'] = 'Enter the ID of the Resource you want to use as homepage here. <strong>NOTE: make sure this ID you enter belongs to an existing Resource, and that it has been published!</strong>';
$_lang['setting_site_start_err'] = 'Please specify a Resource ID that is the site start.';

$_lang['setting_site_status'] = 'Site status';
$_lang['setting_site_status_desc'] = 'Select \'Yes\' to publish your site on the web. If you select \'No\', your visitors will see the \'Site unavailable message\', and won\'t be able to browse the site.';
$_lang['setting_site_status_err'] = 'Please select whether or not the site is online (Yes) or offline (No).';

$_lang['setting_site_unavailable_message'] = 'Site unavailable message';
$_lang['setting_site_unavailable_message_desc'] = 'Message to show when the site is offline or if an error occurs. <strong>Note: This message will only be displayed if the Site unavailable page option is not set.</strong>';

$_lang['setting_site_unavailable_page'] = 'Site unavailable page';
$_lang['setting_site_unavailable_page_desc'] = 'Enter the ID of the Resource you want to use as an offline page here. <strong>NOTE: make sure this ID you enter belongs to an existing Resource, and that it has been published!</strong>';
$_lang['setting_site_unavailable_page_err'] = 'Please specify the document ID for the site unavailable page.';

$_lang['setting_static_elements_automate_templates'] = 'Automate static elements for templates?';
$_lang['setting_static_elements_automate_templates_desc'] = 'This will automate the handling of static files, such as creating and deleting static files for templates.';

$_lang['setting_static_elements_automate_tvs'] = 'Automate static elements for TVs?';
$_lang['setting_static_elements_automate_tvs_desc'] = 'This will automate the handling of static files, such as creating and deleting static files for TVs.';

$_lang['setting_static_elements_automate_chunks'] = 'Automate static elements for chunks?';
$_lang['setting_static_elements_automate_chunks_desc'] = 'This will automate the handling of static files, such as creating and deleting static files for chunks.';

$_lang['setting_static_elements_automate_snippets'] = 'Automate static elements for snippets?';
$_lang['setting_static_elements_automate_snippets_desc'] = 'This will automate the handling of static files, such as creating and deleting static files for snippets.';

$_lang['setting_static_elements_automate_plugins'] = 'Automate static elements for plugins?';
$_lang['setting_static_elements_automate_plugins_desc'] = 'This will automate the handling of static files, such as creating and deleting static files for plugins.';

$_lang['setting_static_elements_default_mediasource'] = 'Static elements default mediasource';
$_lang['setting_static_elements_default_mediasource_desc'] = 'Specify a default mediasource where you want to store the static elements in.';

$_lang['setting_static_elements_default_category'] = 'Static elements default category';
$_lang['setting_static_elements_default_category_desc'] = 'Specify a default category for creating new static elements.';

$_lang['setting_static_elements_basepath'] = 'Static elements basepath';
$_lang['setting_static_elements_basepath_desc'] = 'Basepath of where to store the static elements files.';

$_lang['setting_resource_static_allow_absolute'] = 'Allow absolute static resource path';
$_lang['setting_resource_static_allow_absolute_desc'] = 'This setting enables users to enter a fully qualified absolute path to any readable file on the server as the content of a static resource. Important: enabling this setting may be considered a significant security risk! It\'s strongly recommended to keep this setting disabled, unless you fully trust every single manager user.';

$_lang['setting_resource_static_path'] = 'Static resource base path';
$_lang['setting_resource_static_path_desc'] = 'When resource_static_allow_absolute is disabled, static resources are restricted to be within the absolute path provided here.  Important: setting this too wide may allow users to read files they shouldn\'t! It is strongly recommended to limit users to a specific directory such as {core_path}static/ or {assets_path} with this setting.';

$_lang['setting_symlink_merge_fields'] = 'Merge Resource Fields in Symlinks';
$_lang['setting_symlink_merge_fields_desc'] = 'If set to Yes, will automatically merge non-empty fields with target resource when forwarding using Symlinks.';

$_lang['setting_syncsite_default'] = 'Empty Cache default';
$_lang['setting_syncsite_default_desc'] = 'Select \'Yes\' to empty the cache after you save a resource by default.';
$_lang['setting_syncsite_default_err'] = 'Please state whether or not you want to empty the cache after saving a resource by default.';

$_lang['setting_topmenu_show_descriptions'] = 'Show Descriptions in Main Menu';
$_lang['setting_topmenu_show_descriptions_desc'] = 'If set to \'No\', MODX will hide the descriptions from main menu items in the manager.';

$_lang['setting_tree_default_sort'] = 'Resource Tree Default Sort Field';
$_lang['setting_tree_default_sort_desc'] = 'The default sort field for the Resource tree when loading the manager.';

$_lang['setting_tree_root_id'] = 'Tree Root ID';
$_lang['setting_tree_root_id_desc'] = 'Set this to a valid ID of a Resource to start the left Resource tree at below that node as the root. The user will only be able to see Resources that are children of the specified Resource.';

$_lang['setting_tvs_below_content'] = 'Move TVs Below Content';
$_lang['setting_tvs_below_content_desc'] = 'Set this to Yes to move TVs below the Content when editing Resources.';

$_lang['setting_ui_debug_mode'] = 'UI Debug Mode';
$_lang['setting_ui_debug_mode_desc'] = 'Set this to Yes to output debug messages when using the UI for the default manager theme. You must use a browser that supports console.log.';

$_lang['setting_unauthorized_page'] = 'Unauthorized page';
$_lang['setting_unauthorized_page_desc'] = 'Enter the ID of the Resource you want to send users to if they have requested a secured or unauthorized Resource. <strong>NOTE: Make sure the ID you enter belongs to an existing Resource, and that it has been published and is publicly accessible!</strong>';
$_lang['setting_unauthorized_page_err'] = 'Please specify a Resource ID for the unauthorized page.';

$_lang['setting_upload_files'] = 'Uploadable File Types';
$_lang['setting_upload_files_desc'] = 'Here you can enter a list of files that can be uploaded into \'assets/files/\' using the Resource Manager. Please enter the extensions for the filetypes, seperated by commas.';

$_lang['setting_upload_file_exists'] = 'Check if uploaded file exists';
$_lang['setting_upload_file_exists_desc'] = 'When enabled an error will be shown when uploading a file that already exists with the same name. When disabled, the existing file will be quietly replaced with the new file.';

$_lang['setting_upload_images'] = 'Uploadable Image Types';
$_lang['setting_upload_images_desc'] = 'Here you can enter a list of files that can be uploaded into \'assets/images/\' using the Resource Manager. Please enter the extensions for the image types, separated by commas.';

$_lang['setting_upload_maxsize'] = 'Maximum upload size';
$_lang['setting_upload_maxsize_desc'] = 'Enter the maximum file size that can be uploaded via the file manager. Upload file size must be entered in bytes. <strong>NOTE: Large files can take a very long time to upload!</strong>';

$_lang['setting_upload_media'] = 'Uploadable Media Types';
$_lang['setting_upload_media_desc'] = 'Here you can enter a list of files that can be uploaded into \'assets/media/\' using the Resource Manager. Please enter the extensions for the media types, separated by commas.';

$_lang['setting_upload_translit'] = 'Transliterate names of uploaded files?';
$_lang['setting_upload_translit_desc'] = 'If this option is enabled, the name of an uploaded file will be transliterated according to the global transliteration rules.';

$_lang['setting_use_alias_path'] = 'Use Friendly Alias Path';
$_lang['setting_use_alias_path_desc'] = 'Setting this option to \'yes\' will display the full path to the Resource if the Resource has an alias. For example, if a Resource with an alias called \'child\' is located inside a container Resource with an alias called \'parent\', then the full alias path to the Resource will be displayed as \'/parent/child.html\'.<br /><strong>NOTE: When setting this option to \'Yes\' (turning on alias paths), reference items (such as images, CSS, JavaScripts, etc.) use the absolute path, e.g., \'/assets/images\' as opposed to \'assets/images\'. By doing so you will prevent the browser (or web server) from appending the relative path to the alias path.</strong>';

$_lang['setting_use_editor'] = 'Enable Rich Text Editor';
$_lang['setting_use_editor_desc'] = 'Do you want to enable the rich text editor? If you\'re more comfortable writing HTML, then you can turn the editor off using this setting. Note that this setting applies to all documents and all users!';
$_lang['setting_use_editor_err'] = 'Please state whether or not you want to use an RTE editor.';

$_lang['setting_use_frozen_parent_uris'] = 'Use Frozen Parent URIs';
$_lang['setting_use_frozen_parent_uris_desc'] = 'When enabled, the URI for children resources will be relative to the frozen URI of one of its parents, ignoring the aliases of resources high in the tree.';

$_lang['setting_use_multibyte'] = 'Use Multibyte Extension';
$_lang['setting_use_multibyte_desc'] = 'Set to true if you want to use the mbstring extension for multibyte characters in your MODX installation. Only set to true if you have the mbstring PHP extension installed.';

$_lang['setting_use_weblink_target'] = 'Use WebLink Target';
$_lang['setting_use_weblink_target_desc'] = 'Set to true if you want to have MODX link tags and makeUrl() generate links as the target URL for WebLinks. Otherwise, the internal MODX URL will be generated by link tags and the makeUrl() method.';

$_lang['setting_user_nav_parent'] = 'User menu parent';
$_lang['setting_user_nav_parent_desc'] = 'The container used to pull all records for the user menu.';

$_lang['setting_welcome_screen'] = 'Show Welcome Screen';
$_lang['setting_welcome_screen_desc'] = 'If set to true, the welcome screen will show on the next successful loading of the welcome page, and then not show after that.';

$_lang['setting_welcome_screen_url'] = 'Διεύθυνση URL της σελίδας Καλωσορίσματος';
$_lang['setting_welcome_screen_url_desc'] = 'The URL for the welcome screen that loads on first load of MODX Revolution.';

$_lang['setting_welcome_action'] = 'Welcome Action';
$_lang['setting_welcome_action_desc'] = 'The default controller to load when accessing the manager when no controller is specified in the URL.';

$_lang['setting_welcome_namespace'] = 'Welcome Namespace';
$_lang['setting_welcome_namespace_desc'] = 'The namespace the Welcome Action belongs to.';

$_lang['setting_which_editor'] = 'Editor to use';
$_lang['setting_which_editor_desc'] = 'Here you can select which Rich Text Editor you wish to use. You can download and install additional Rich Text Editors from Package Management.';

$_lang['setting_which_element_editor'] = 'Editor to use for Elements';
$_lang['setting_which_element_editor_desc'] = 'Here you can select which Rich Text Editor you wish to use when editing Elements. You can download and install additional Rich Text Editors from Package Management.';

$_lang['setting_xhtml_urls'] = 'XHTML URLs';
$_lang['setting_xhtml_urls_desc'] = 'If set to true, all URLs generated by MODX will be XHTML-compliant, including encoding of the ampersand character.';

$_lang['setting_default_context'] = 'Default Context';
$_lang['setting_default_context_desc'] = 'Select the default Context you wish to use for new Resources.';

$_lang['setting_auto_isfolder'] = 'Set container automatically';
$_lang['setting_auto_isfolder_desc'] = 'If set to yes, container property will be changed automatically.';

$_lang['setting_default_username'] = 'Default username';
$_lang['setting_default_username_desc'] = 'Default username for an unauthenticated user.';

$_lang['setting_manager_use_fullname'] = 'Show fullname in manager header ';
$_lang['setting_manager_use_fullname_desc'] = 'If set to yes, the content of the "fullname" field will be shown in manager instead of "loginname"';

$_lang['setting_log_snippet_not_found'] = 'Log snippets not found';
$_lang['setting_log_snippet_not_found_desc'] = 'If set to yes, snippets that are called but not found will be logged to the error log.';

$_lang['setting_error_log_filename'] = 'Error log filename';
$_lang['setting_error_log_filename_desc'] = 'Customize the filename of the MODX error log file (includes file extension).';

$_lang['setting_error_log_filepath'] = 'Error log path';
$_lang['setting_error_log_filepath_desc'] = 'Optionally set a absolute path the a custom error log location. You might use placehodlers like {cache_path}.';

$_lang['setting_passwordless_activated'] = 'Activate passwordless login';
$_lang['setting_passwordless_activated_desc'] = 'When enabled, users will enter their email address to receive a one-time login link, rather than entering a username and password.';

$_lang['setting_passwordless_expiration'] = 'Passwordless login expiration';
$_lang['setting_passwordless_expiration_desc'] = 'How long a one-time login link is valid in seconds.';

$_lang['setting_static_elements_html_extension'] = 'Static elements html extension';
$_lang['setting_static_elements_html_extension_desc'] = 'The extension for files used by static elements with HTML content.';
