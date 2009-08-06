<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'Checking if <span class="mono">%s</span> exists and is writable: ';
$_lang['test_config_file_nw'] = 'For new Linux/Unix installs, please create a blank file named <span class="mono">%s.inc.php</span> in your MODx core <span class="mono">config/</span> directory with permissions set to be writable by PHP.';
$_lang['test_db_check'] = 'Creating connection to the database: ';
$_lang['test_db_check_conn'] = 'Check the connection details and try again.';
$_lang['test_db_failed'] = 'Database connection failed!';
$_lang['test_db_setup_create'] = 'Setup will attempt to create the database.';
$_lang['test_dependencies'] = 'Checking PHP for zlib dependency: ';
$_lang['test_dependencies_fail_zlib'] = 'Your PHP installation does not have the "zlib" extension installed. This extension is necessary for MODx to run. Please enable it to continue.';
$_lang['test_directory_exists'] = 'Checking if <span class="mono">%s</span> directory exists: ';
$_lang['test_directory_writable'] = 'Checking if <span class="mono">%s</span> directory is writable: ';
$_lang['test_memory_limit'] = 'Checking if memory limit is set to at least 64M: ';
$_lang['test_memory_limit_fail'] = 'MODx found your memory_limit setting to be below the recommended setting of 64M. MODx attempted to set the memory_limit to 128M, but was unsuccessful. Please set the memory_limit setting in your php.ini file to 64M or higher before proceeding. MODx recommends 128M.';
$_lang['test_php_version_fail'] = 'You are running on PHP %s, and MODx Revolution requires PHP 4.3.0 or later';
$_lang['test_php_version_sn'] = 'While MODx will work on your PHP version (%s), usage of MODx on this version is not recommended. Your version of PHP is vulnerable to numerous security holes. Please upgrade to PHP version is 4.3.11 or higher, which patches these holes. It is recommended you upgrade to this version for the security of your own website.';
$_lang['test_php_version_start'] = 'Checking PHP version:';
$_lang['test_sessions_start'] = 'Checking if sessions are properly configured:';
$_lang['test_table_prefix'] = 'Checking table prefix `%s`: ';
$_lang['test_table_prefix_inuse'] = 'Table prefix is already in use in this database!';
$_lang['test_table_prefix_inuse_desc'] = 'Setup couldn\'t install into the selected database, as it already contains tables with the prefix you specified. Please choose a new table_prefix, and run Setup again.';
$_lang['test_table_prefix_nf'] = 'Table prefix does not exist in this database!';
$_lang['test_table_prefix_nf_desc'] = 'Setup couldn\'t install into the selected database, as it does not contain existing tables with the prefix you specified to be upgraded. Please choose an existing table_prefix, and run Setup again.';
$_lang['test_zip_memory_limit'] = 'Checking if memory limit is set to at least 64M for zip extensions: ';
$_lang['test_zip_memory_limit_fail'] = 'MODx found your memory_limit setting to be below the recommended setting of 64M. MODx attempted to set the memory_limit to 64M, but was unsuccessful. Please set the memory_limit setting in your php.ini file to 64M or higher before proceeding, so that the zip extensions can work properly.';