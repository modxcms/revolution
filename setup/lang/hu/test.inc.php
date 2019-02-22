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
$_lang['test_db_check_conn'] = 'Check the connection details and try again.';
$_lang['test_db_failed'] = 'Database connection failed!';
$_lang['test_db_setup_create'] = 'Setup will attempt to create the database.';
$_lang['test_dependencies'] = 'Checking PHP for zlib dependency: ';
$_lang['test_dependencies_fail_zlib'] = 'Your PHP installation does not have the "zlib" extension installed. This extension is necessary for MODX to run. Please enable it to continue.';
$_lang['test_directory_exists'] = 'Checking if <span class="mono">[[+dir]]</span> directory exists: ';
$_lang['test_directory_writable'] = 'Checking if <span class="mono">[[+dir]]</span> directory is writable: ';
$_lang['test_memory_limit'] = 'Checking if memory limit is set to at least 24M: ';
$_lang['test_memory_limit_fail'] = 'MODX found your memory_limit setting to be at [[+memory]], below the recommended setting of 24M. MODX attempted to set the memory_limit to 24M, but was unsuccessful. Please set the memory_limit setting in your php.ini file to at least 24M or higher before proceeding. If you are still having trouble (such as getting a blank white screen on install), set to 32M, 64M or higher.';
$_lang['test_memory_limit_success'] = 'Rendben! [[+memory]] beállítva';
$_lang['test_mysql_version_5051'] = 'MODX will have issues on your MySQL version ([[+version]]) because of the many bugs related to the PDO drivers on this version. Please upgrade MySQL to patch these problems. Even if you choose not to use MODX, it is recommended you upgrade to this version for the security and stability of your own website.';
$_lang['test_mysql_version_client_nf'] = 'MySQL kliens verziója nem derült ki!';
$_lang['test_mysql_version_client_nf_msg'] = 'A MODX nem tudta felismerni a MySQL kliens verzióját a mysql_get_client_info() meghívásával. Kérjük, hogy továbblépés előtt győződjön meg róla, hogy a MySQL kliens verziója legalább 4.1.20.';
$_lang['test_mysql_version_client_old'] = 'MODX may have issues because you are using a very old MySQL client version ([[+version]])';
$_lang['test_mysql_version_client_old_msg'] = 'MODX will allow installation using this MySQL client version, but we cannot guarantee all functionality will be available or work properly when using older versions of the MySQL client libraries.';
$_lang['test_mysql_version_client_start'] = 'MySQL kliens verziójának ellenőrzése:';
$_lang['test_mysql_version_fail'] = 'A MySQL [[+version]] fut, és a MODX Revolution a MySQL 4.1.20 vagy újabb változatát igényli. Kérjük, frissítse a MySQL-t legalább 4.1.20-ra.';
$_lang['test_mysql_version_server_nf'] = 'Could not detect MySQL server version!';
$_lang['test_mysql_version_server_nf_msg'] = 'A MODX nem tudta felismerni a MySQL kiszolgáló verzióját a mysql_get_server_info() meghívásával. Kérjük, hogy továbblépés előtt győződjön meg róla, hogy a MySQL kiszolgáló verziója legalább 4.1.20.';
$_lang['test_mysql_version_server_start'] = 'MySQL kiszolgáló verziójának ellenőrzése:';
$_lang['test_mysql_version_success'] = 'Rendben! Fut a [[+version]] verzió';
$_lang['test_nocompress'] = 'Checking if we should disable CSS/JS compression: ';
$_lang['test_nocompress_disabled'] = 'Rendben! Letiltva.';
$_lang['test_nocompress_skip'] = 'Nincs kiválasztva, ellenőrzés kihagyva.';
$_lang['test_php_version_fail'] = 'Jelenleg a PHP [[+version]] verzióját futtatja, de a MODX Revolution számára legalább a PHP [[+required]] verziója szükséges. Kérjük, frissítsen legalább a PHP [[+required]] verziójára. A MODX javaslata, hogy frissítsen a jelenlegi stabil főverzióra [[+recommended]] a biztonság és a jövőbeli támogatás érdekében.';
$_lang['test_php_version_start'] = 'Checking PHP version:';
$_lang['test_php_version_success'] = 'Rendben! Fut a [[+version]] verzió';
$_lang['test_safe_mode_start'] = 'Ellenőrzése annak, hogy a safe_mode kikapcsolt:';
$_lang['test_safe_mode_fail'] = 'A MODX a safe_mode bekapcsolt állapotát érzékeli. A továbblépéshez ezt ki kell kapcsolnia a PHP beállításaiban.';
$_lang['test_sessions_start'] = 'Checking if sessions are properly configured:';
$_lang['test_simplexml'] = 'SimpleXML vizsgálata:';
$_lang['test_simplexml_nf'] = 'SimpleXML nem található!';
$_lang['test_simplexml_nf_msg'] = 'MODX could not find SimpleXML on your PHP environment. Package Management and other functionality will not work without this installed. You may continue with installation, but MODX recommends enabling SimpleXML for advanced features and functionality.';
$_lang['test_suhosin'] = 'Suhosin hibák keresése:';
$_lang['test_suhosin_max_length'] = 'Suhosin GET legnagyobb értéke túl alacsony!';
$_lang['test_suhosin_max_length_err'] = 'Currently, you are using the PHP suhosin extension, and your suhosin.get.max_value_length is set too low for MODX to properly compress JS files in the manager. MODX recommends upping that value to 4096; until then, MODX will automatically set your JS compression (compress_js setting) to 0 to prevent errors.';
$_lang['test_table_prefix'] = 'Checking table prefix `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = 'Table prefix is already in use in this database!';
$_lang['test_table_prefix_inuse_desc'] = 'Setup couldn\'t install into the selected database, as it already contains tables with the prefix you specified. Please choose a new table_prefix, and run Setup again.';
$_lang['test_table_prefix_nf'] = 'Table prefix does not exist in this database!';
$_lang['test_table_prefix_nf_desc'] = 'Setup couldn\'t install into the selected database, as it does not contain existing tables with the prefix you specified to be upgraded. Please choose an existing table_prefix, and run Setup again.';
$_lang['test_zip_memory_limit'] = 'Checking if memory limit is set to at least 24M for zip extensions: ';
$_lang['test_zip_memory_limit_fail'] = 'MODX found your memory_limit setting to be below the recommended setting of 24M. MODX attempted to set the memory_limit to 24M, but was unsuccessful. Please set the memory_limit setting in your php.ini file to 24M or higher before proceeding, so that the zip extensions can work properly.';