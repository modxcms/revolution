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
$_lang['test_db_failed'] = 'Połączenie z bazą danych nie udało się!';
$_lang['test_db_setup_create'] = 'Instalator spróbuje utworzyć bazę danych.';
$_lang['test_dependencies'] = 'Sprawdzam PHP pod kątem zlib: ';
$_lang['test_dependencies_fail_zlib'] = 'Twoja instalacja PHP nie posiada rozszerzenia "zlib". To rozszerzenie jest niezbędne do działania systemu MODX.  Zainstaluj i włącz rozszerzenie przed kontynuacją.';
$_lang['test_directory_exists'] = 'Checking if <span class="mono">[[+dir]]</span> directory exists: ';
$_lang['test_directory_writable'] = 'Checking if <span class="mono">[[+dir]]</span> directory is writable: ';
$_lang['test_memory_limit'] = 'Sprawdzam czy limit pamięci jest ustawiony na co najmniej 24M: ';
$_lang['test_memory_limit_fail'] = 'MODX found your memory_limit setting to be at [[+memory]], below the recommended setting of 24M. MODX attempted to set the memory_limit to 24M, but was unsuccessful. Please set the memory_limit setting in your php.ini file to at least 24M or higher before proceeding. If you are still having trouble (such as getting a blank white screen on install), set to 32M, 64M or higher.';
$_lang['test_memory_limit_success'] = 'OK! Set to [[+memory]]';
$_lang['test_mysql_version_5051'] = 'MODX może nie działać prawidłowo na Twojej wersji bazy MySQL ([[+version]]), z powodu wielu błędów związanych z obsługą sterowników PDO wykrytych w tej wersji. Proszę uaktualnij bazę MySQL do wersji, która jest wolna od tych błędów. Nawet jeśli zrezygnujesz z używania MODX i tak należy zaktualizować bazę by podnieść poziom bezpieczeństwa i stabilności Twojej strony internetowej.';
$_lang['test_mysql_version_client_nf'] = 'Could not detect MySQL client version!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODX nie może wykryć wersji klienta MySQL poprzez funkcję mysql_get_client_info(). Przed pójściem dalej upewnij się, że Twoja wersja klienta MySQL to 4.1.20 lub wyższa.';
$_lang['test_mysql_version_client_old'] = 'MODX may have issues because you are using a very old MySQL client version ([[+version]])';
$_lang['test_mysql_version_client_old_msg'] = 'MODX will allow installation using this MySQL client version, but we cannot guarantee all functionality will be available or work properly when using older versions of the MySQL client libraries.';
$_lang['test_mysql_version_client_start'] = 'Sprawdzam wersję klienta MySQL:';
$_lang['test_mysql_version_fail'] = 'Twoja baza MySQL jest w wersji [[+version]]. MODX Revolution wymaga wersji MySQL 4.1.20 lub późniejszej. Proszę uaktualnij MySQL przynajmniej do wersji nr 4.1.20.';
$_lang['test_mysql_version_server_nf'] = 'Could not detect MySQL server version!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODX nie potrafi wykryć wersji Twojego serwera MySQL za pomocą funkcji mysql_get_server_info(). Proszę, przed kontynuacją upewnij się, że Twoja wersja serwera MySQL to co najmniej 4.1.20.';
$_lang['test_mysql_version_server_start'] = 'Sprawdzam wersję serwera MySQL:';
$_lang['test_mysql_version_success'] = 'OK! Wersja: [[+version]]';
$_lang['test_nocompress'] = 'Checking if we should disable CSS/JS compression: ';
$_lang['test_nocompress_disabled'] = 'OK! Disabled.';
$_lang['test_nocompress_skip'] = 'Not selected, skipping test.';
$_lang['test_php_version_fail'] = 'You are running on PHP [[+version]], and MODX Revolution requires PHP [[+required]] or later. Please upgrade PHP to at least [[+required]]. MODX recommends upgrading to the current stable branch [[+recommended]] for security reasons and future support.';
$_lang['test_php_version_start'] = 'Checking PHP version:';
$_lang['test_php_version_success'] = 'OK! Wersja: [[+version]]';
$_lang['test_safe_mode_start'] = 'Checking to make sure safe_mode is off:';
$_lang['test_safe_mode_fail'] = 'MODX has found safe_mode to be on. You must disable safe_mode in your PHP configuration to proceed.';
$_lang['test_sessions_start'] = 'Checking if sessions are properly configured:';
$_lang['test_simplexml'] = 'Checking for SimpleXML:';
$_lang['test_simplexml_nf'] = 'Could not find SimpleXML!';
$_lang['test_simplexml_nf_msg'] = 'MODX could not find SimpleXML on your PHP environment. Package Management and other functionality will not work without this installed. You may continue with installation, but MODX recommends enabling SimpleXML for advanced features and functionality.';
$_lang['test_suhosin'] = 'Checking for suhosin issues:';
$_lang['test_suhosin_max_length'] = 'Suhosin GET max value too low!';
$_lang['test_suhosin_max_length_err'] = 'Currently, you are using the PHP suhosin extension, and your suhosin.get.max_value_length is set too low for MODX to properly compress JS files in the manager. MODX recommends upping that value to 4096; until then, MODX will automatically set your JS compression (compress_js setting) to 0 to prevent errors.';
$_lang['test_table_prefix'] = 'Checking table prefix `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = 'Table prefix is already in use in this database!';
$_lang['test_table_prefix_inuse_desc'] = 'Setup couldn\'t install into the selected database, as it already contains tables with the prefix you specified. Please choose a new table_prefix, and run Setup again.';
$_lang['test_table_prefix_nf'] = 'Table prefix does not exist in this database!';
$_lang['test_table_prefix_nf_desc'] = 'Setup couldn\'t install into the selected database, as it does not contain existing tables with the prefix you specified to be upgraded. Please choose an existing table_prefix, and run Setup again.';
$_lang['test_zip_memory_limit'] = 'Checking if memory limit is set to at least 24M for zip extensions: ';
$_lang['test_zip_memory_limit_fail'] = 'MODX found your memory_limit setting to be below the recommended setting of 24M. MODX attempted to set the memory_limit to 24M, but was unsuccessful. Please set the memory_limit setting in your php.ini file to 24M or higher before proceeding, so that the zip extensions can work properly.';