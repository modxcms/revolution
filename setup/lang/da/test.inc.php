<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'Checking if <span class="mono">[[+file]]</span> exists and is writable: ';
$_lang['test_config_file_nw'] = 'For nye Linux/Unix installationer, skal du oprette en tom fil med navnet <span class="mono">[[+nøgle]].inc.php</span> i din MODX core <span class="mono">config/</span>-mappe med rettigheder indstillet til at være skrivbar af PHP.';
$_lang['test_db_check'] = 'Creating connection to the database: ';
$_lang['test_db_check_conn'] = 'Check the connection details and try again.';
$_lang['test_db_failed'] = 'Database connection failed!';
$_lang['test_db_setup_create'] = 'Setup will attempt to create the database.';
$_lang['test_dependencies'] = 'Checking PHP for zlib dependency: ';
$_lang['test_dependencies_fail_zlib'] = 'Your PHP installation does not have the "zlib" extension installed. This extension is necessary for MODX to run. Please enable it to continue.';
$_lang['test_directory_exists'] = 'Checking if <span class="mono">[[+dir]]</span> directory exists: ';
$_lang['test_directory_writable'] = 'Checking if <span class="mono">[[+dir]]</span> directory is writable: ';
$_lang['test_memory_limit'] = 'Checking if memory limit is set to at least 24M: ';
$_lang['test_memory_limit_fail'] = 'MODX fandt din "memory_limit" indstilling til at være på [[+memory]], under den anbefalede indstilling på 24M. MODX forsøgte at sætte "memory_limit" til 24M, men det lykkedes ikke. Angiv venligst indstillingen "memory_limit" i din php.ini til mindst 24M eller højere, før du fortsætter. Hvis du stadig har problemer (såsom at få en tom hvid skærm ved installation), så prøv at indstille "memory_limit" til 32M, 64M eller højere.';
$_lang['test_memory_limit_success'] = 'OK! Hukommelse indstillet til [[+memory]]';
$_lang['test_mysql_version_5051'] = 'MODX vil have problemer på din MySQL version ([[+version]]), på grund af de mange fejl relateret til PDO driverne på denne version. Opgrader venligst MySQL for at rette disse problemer. Selv hvis du vælger ikke at bruge MODX, anbefales det, at du har opgraderet til denne version for sikkerheden og stabiliteten af din egen hjemmeside.';
$_lang['test_mysql_version_client_nf'] = 'Kunne ikke registrere MySQL klientversion!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODX kunne ikke registrere din MySQL klientversion via mysql_get_client_info(). Sørg venligst manuelt for at din MySQL klientversion er mindst 4.1.20 inden du fortsætter.';
$_lang['test_mysql_version_client_old'] = 'MODX kan have problemer, fordi du bruger en meget gammel MySQL klientversion ([[+version]])';
$_lang['test_mysql_version_client_old_msg'] = 'MODX vil tillade installation med denne MySQL klientversion, men vi kan ikke garantere, at alle funktioner vil være tilgængelige eller virke ordentligt ved brug af ældre versioner af MySQL klienter.';
$_lang['test_mysql_version_client_start'] = 'Kontrollerer MySQL klientversion:';
$_lang['test_mysql_version_fail'] = 'Du kører på MySQL [[+version]], og MODX Revolution har brug for MySQL 4.1.20 eller senere. Opgrader venligst MySQL til mindst 4.1.20.';
$_lang['test_mysql_version_server_nf'] = 'Kunne ikke registrere MySQL serverversion!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODX kunne ikke registrere din MySQL serverversion via mysql_get_server_info(). Sørg venligst manuelt for at din MySQL serverversion er mindst 4.1.20 inden du fortsætter.';
$_lang['test_mysql_version_server_start'] = 'Kontrollerer MySQL serverversion:';
$_lang['test_mysql_version_success'] = 'OK! Kører: [[+version]]';
$_lang['test_nocompress'] = 'Kontrollerer om vi skal slå CSS/JS komprimering fra: ';
$_lang['test_nocompress_disabled'] = 'OK! Slået fra.';
$_lang['test_nocompress_skip'] = 'Ikke valgt, springer testen over.';
$_lang['test_php_version_fail'] = 'Du kører PHP [[+version]], og MODX Revolution kræver PHP 5.1.1 eller senere. Opgrader venligst PHP til mindst 5.1.1. MODX anbefaler opgradering til mindst 5.3.2+.';
$_lang['test_php_version_start'] = 'Checking PHP version:';
$_lang['test_php_version_success'] = 'OK! Kører: [[+version]]';
$_lang['test_session_gc'] = 'Checking if <a href="https://www.php.net/manual/en/session.configuration.php#ini.session.gc-probability" onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;">sessions garbage collector</a> are properly configured: ';
$_lang['test_session_gc_fail'] = 'The sessions garbage collector does not start! The current configuration "session.gc_probability" is set to [[+gc_probability]] and "session.gc_divisor" is set to [[+gc_divisor]]. <br>By default, MODX stores sessions in the database, so misconfiguration of these options can cause the session table to grow in size.';
$_lang['test_session_gc_success'] = 'OK! The current configuration "session.gc_probability" is set to [[+gc_probability]] and "session.gc_divisor" is set to [[+gc_divisor]]. <br>By default, MODX stores sessions in the database, so misconfiguration of these options can cause the session table to grow in size.';
$_lang['test_simplexml'] = 'Kontrol for SimpleXML:';
$_lang['test_simplexml_nf'] = 'Kunne ikke finde SimpleXML!';
$_lang['test_simplexml_nf_msg'] = 'MODX kunne ikke finde SimpleXML i dit PHP miljø. Package Management og andre funktioner vil ikke fungere uden denne installeret. Du kan fortsætte med installationen, men MODX anbefaler at aktivere SimpleXML for avancerede funktioner og funktionalitet.';
$_lang['test_suhosin'] = 'Kontrollerer suhosin problemer:';
$_lang['test_suhosin_max_length'] = 'Suhosin GET max værdi for lav!';
$_lang['test_suhosin_max_length_err'] = 'I øjeblikket bruger du PHP suhosin udvidelsen, og din suhosin.get.max_value_length er indstillet for lavt til, at MODX kan komprimere JS-filer ordenligt i Manageren. MODX anbefaler at sætte værdien op til 4096; indtil da vil MODX automatisk indstille din JS komprimering (indstillingen "compress_js") til 0 for at forhindre fejl.';
$_lang['test_table_prefix'] = 'Checking table prefix `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = 'Table prefix is already in use in this database!';
$_lang['test_table_prefix_inuse_desc'] = 'Setup couldn\'t install into the selected database, as it already contains tables with the prefix you specified. Please choose a new table_prefix, and run Setup again.';
$_lang['test_table_prefix_nf'] = 'Table prefix does not exist in this database!';
$_lang['test_table_prefix_nf_desc'] = 'Setup couldn\'t install into the selected database, as it does not contain existing tables with the prefix you specified to be upgraded. Please choose an existing table_prefix, and run Setup again.';
$_lang['test_zip_memory_limit'] = 'Checking if memory limit is set to at least 24M for zip extensions: ';
$_lang['test_zip_memory_limit_fail'] = 'MODX found your memory_limit setting to be below the recommended setting of 24M. MODX attempted to set the memory_limit to 24M, but was unsuccessful. Please set the memory_limit setting in your php.ini file to 24M or higher before proceeding, so that the zip extensions can work properly.';
