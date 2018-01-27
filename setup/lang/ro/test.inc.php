<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'Checking if <span class="mono">[[+file]]</span> exists and is writable: ';
$_lang['test_config_file_nw'] = 'For new Linux/Unix installs, please create a blank file named <span class="mono">[[+key]].inc.php</span> in your MODX core <span class="mono">config/</span> directory with permissions set to be writable by PHP.';
$_lang['test_db_check'] = 'Creating connection to the database: ';
$_lang['test_db_check_conn'] = 'Verificați datele legate de conexiune și încercați încă o dată.';
$_lang['test_db_failed'] = 'Database connection failed!';
$_lang['test_db_setup_create'] = 'Setup will attempt to create the database.';
$_lang['test_dependencies'] = 'Se verifică extensia PHP zlib: ';
$_lang['test_dependencies_fail_zlib'] = 'Your PHP installation does not have the "zlib" extension installed. This extension is necessary for MODX to run. Please enable it to continue.';
$_lang['test_directory_exists'] = 'Se verifică dacă dosarul <span class="mono">[[+dir]]</span> există: ';
$_lang['test_directory_writable'] = 'Se verifică în dosarul  <span class="mono">[[+dir]]</span> este permisă scrierea: ';
$_lang['test_memory_limit'] = 'Se verifică dacă limita de memorie este peste 24M: ';
$_lang['test_memory_limit_fail'] = 'MODX found your memory_limit setting to be at [[+memory]], below the recommended setting of 24M. MODX attempted to set the memory_limit to 24M, but was unsuccessful. Please set the memory_limit setting in your php.ini file to at least 24M or higher before proceeding. If you are still having trouble (such as getting a blank white screen on install), set to 32M, 64M or higher.';
$_lang['test_memory_limit_success'] = 'OK! Are valoarea [[+memory]]';
$_lang['test_mysql_version_5051'] = 'MODX will have issues on your MySQL version ([[+version]]) because of the many bugs related to the PDO drivers on this version. Please upgrade MySQL to patch these problems. Even if you choose not to use MODX, it is recommended you upgrade to this version for the security and stability of your own website.';
$_lang['test_mysql_version_client_nf'] = 'Could not detect MySQL client version!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODX could not detect your MySQL client version via mysql_get_client_info(). Please manually make sure that your MySQL client version is at least 4.1.20 before proceeding.';
$_lang['test_mysql_version_client_old'] = 'MODX may have issues because you are using a very old MySQL client version ([[+version]])';
$_lang['test_mysql_version_client_old_msg'] = 'MODX will allow installation using this MySQL client version, but we cannot guarantee all functionality will be available or work properly when using older versions of the MySQL client libraries.';
$_lang['test_mysql_version_client_start'] = 'Se verifică versiunea clientului MySQL:';
$_lang['test_mysql_version_fail'] = 'You are running on MySQL [[+version]], and MODX Revolution requires MySQL 4.1.20 or later. Please upgrade MySQL to at least 4.1.20.';
$_lang['test_mysql_version_server_nf'] = 'Could not detect MySQL server version!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODX could not detect your MySQL server version via mysql_get_server_info(). Please manually make sure that your MySQL server version is at least 4.1.20 before proceeding.';
$_lang['test_mysql_version_server_start'] = 'Se verifică versiunea serverului MySQL:';
$_lang['test_mysql_version_success'] = 'OK! Rulează: [[+version]]';
$_lang['test_nocompress'] = 'Se verifică dacă trebuie de oprit compresia CSS/JS: ';
$_lang['test_nocompress_disabled'] = 'OK! Disabled.';
$_lang['test_nocompress_skip'] = 'Not selected, skipping test.';
$_lang['test_php_version_fail'] = 'You are running on PHP [[+version]], and MODX Revolution requires PHP [[+required]] or later. Please upgrade PHP to at least [[+required]]. MODX recommends upgrading to the current stable branch [[+recommended]] for security reasons and future support.';
$_lang['test_php_version_start'] = 'Se verifică versiunea PHP:';
$_lang['test_php_version_success'] = 'OK! Rulează: [[+version]]';
$_lang['test_safe_mode_start'] = 'Se verifică dacă opțiunea PHP safe_mode are valoarea off:';
$_lang['test_safe_mode_fail'] = 'MODX has found safe_mode to be on. You must disable safe_mode in your PHP configuration to proceed.';
$_lang['test_sessions_start'] = 'Se verifică dacă sesiunile sunt configurate adecvat:';
$_lang['test_simplexml'] = 'Se verifică extensia PHP SimpleXML:';
$_lang['test_simplexml_nf'] = 'SimpleXML nu a fost găsit!';
$_lang['test_simplexml_nf_msg'] = 'MODX could not find SimpleXML on your PHP environment. Package Management and other functionality will not work without this installed. You may continue with installation, but MODX recommends enabling SimpleXML for advanced features and functionality.';
$_lang['test_suhosin'] = 'Se verifică dacă există probleme legate de sistemul (de securitate) suhosin:';
$_lang['test_suhosin_max_length'] = 'Suhosin GET max value too low!';
$_lang['test_suhosin_max_length_err'] = 'Currently, you are using the PHP suhosin extension, and your suhosin.get.max_value_length is set too low for MODX to properly compress JS files in the manager. MODX recommends upping that value to 4096; until then, MODX will automatically set your JS compression (compress_js setting) to 0 to prevent errors.';
$_lang['test_table_prefix'] = 'Se verifică prefixul (pentru numele tabelelor)  `[[+prefix]]`:';
$_lang['test_table_prefix_inuse'] = 'Table prefix is already in use in this database!';
$_lang['test_table_prefix_inuse_desc'] = 'Setup couldn\'t install into the selected database, as it already contains tables with the prefix you specified. Please choose a new table_prefix, and run Setup again.';
$_lang['test_table_prefix_nf'] = 'Table prefix does not exist in this database!';
$_lang['test_table_prefix_nf_desc'] = 'Setup couldn\'t install into the selected database, as it does not contain existing tables with the prefix you specified to be upgraded. Please choose an existing table_prefix, and run Setup again.';
$_lang['test_zip_memory_limit'] = 'Se verifică dacă limita de memorie este peste 24M: ';
$_lang['test_zip_memory_limit_fail'] = 'MODX found your memory_limit setting to be below the recommended setting of 24M. MODX attempted to set the memory_limit to 24M, but was unsuccessful. Please set the memory_limit setting in your php.ini file to 24M or higher before proceeding, so that the zip extensions can work properly.';