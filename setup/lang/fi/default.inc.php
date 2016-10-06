<?php
/**
 * English language files for Revolution 2.0.0 setup
 *
 * @package setup
 */
$_lang['additional_css'] = '';
$_lang['addons'] = 'Lisäosat';
$_lang['advanced_options'] = 'Lisäasetukset';
$_lang['all'] = 'All';
$_lang['app_description'] = 'CMS ja PHP ohjelmisto kehitysympäristö';
$_lang['app_motto'] = 'MODX; Luo ja tee enemmän vähemmällä';
$_lang['back'] = 'Takaisin';
$_lang['base_template'] = 'PerusSivupohja';
$_lang['cache_manager_err'] = 'MODX:n välimuistin hallintaa ei voitu ladata.';
$_lang['choose_language'] = 'Valitse kieli';
$_lang['cleanup_errors_title'] = 'Tärkeä huomautus:';
$_lang['cli_install_failed'] = 'Asennus epäonnistui! Virheet: [[+errors]]';
$_lang['cli_no_config_file'] = 'MODX ei löydy konfiguriontitiedostoa (kuten config.xml) CLI asennuksellesi. MODX asennusohjelman suorittaminen kommentoriviltä vaatii konfigurointitiedostoa. Lisätietoa virallisesta dokumentaatiosta.';
$_lang['cli_tests_failed'] = 'Asennuksen aloitustestit epäonnistuivat! Virheet: [[+errors]]';
$_lang['close'] = 'sulje';
$_lang['config_file_err_w'] = 'Virhe kirjoitettaessa config tiedostoa.';
$_lang['config_file_perms_notset'] = 'Config -tiedoston käyttöoikeuksia ei päivitetty. Haluat ehkä muuttaa config -tiedoston käyttöoikeuksia varmistaaksesi ettei tiedostoa peukaloida.';
$_lang['config_file_perms_set'] = 'Config -tiedoston käyttöoikeudet on päivitetty onnistuneesti.';
$_lang['config_file_written'] = 'Config-tiedosto on kirjoitettu onnistuneesti.';
$_lang['config_key'] = 'MODX kokoonpanoavain';
$_lang['config_key_change'] = 'Jos haluat muuttaa MODX-kokoonpanoavainta <a id="cck-href" href="javascript:void(0);"> klikkaa tästä.</a>';
$_lang['config_key_override'] = 'Jos haluat suorittaa asennusohjelman muulla kuin tämän hetkisellä määritysavaimella hakemistossa setup/includes/config.core.php, määritä se alla.';
$_lang['config_not_writable_err'] = 'You have attempted to change a setting in setup/includes/config.core.php, but the file is not writable. Make the file writable or edit the file manually before continuing.';
$_lang['connection_character_set'] = 'Connection character set:';
$_lang['connection_collation'] = 'Collation:';
$_lang['connection_connection_and_login_information'] = 'Tietokannan yhteyden ja sisäänkirjautumisen tiedot';
$_lang['connection_connection_note'] = 'Please enter the following information to connect to your MODX database. If there is no database yet, the installer will attempt to create it for you. (This may fail if your database configuration or the database user permissions do not allow it.)';
$_lang['connection_database_host'] = 'Tietokantapalvelin:';
$_lang['connection_database_info'] = 'Now please enter the login data for your database.';
$_lang['connection_database_login'] = 'Tietokannan käyttäjätunnus:';
$_lang['connection_database_name'] = 'Tietokannan nimi:';
$_lang['connection_database_pass'] = 'Tietokannan salasana:';
$_lang['connection_database_type'] = 'Tietokannan tyyppi:';
$_lang['connection_default_admin_email'] = 'Ylläpitäjän sähköposti:';
$_lang['connection_default_admin_login'] = 'Ylläpitäjän käyttäjätunnus:';
$_lang['connection_default_admin_note'] = 'Now you\'ll need to enter some details for the main administrator account. You can fill in your own name here, and a password you\'re not likely to forget. You\'ll need these to log into Admin once setup is complete.';
$_lang['connection_default_admin_password'] = 'Ylläpitäjän salasana:';
$_lang['connection_default_admin_password_confirm'] = 'Vahvista salasana:';
$_lang['connection_default_admin_user'] = 'Default Admin User';
$_lang['connection_table_prefix'] = 'Taulun etuliite:';
$_lang['connection_test_connection'] = 'Testaa yhteys';
$_lang['connection_title'] = 'Yhteystiedot';
$_lang['context_connector_options'] = '<strong>Connectors Options</strong> (AJAX connector services)';
$_lang['context_connector_path'] = 'Filesystem path for connectors';
$_lang['context_connector_url'] = 'Liittimien URL';
$_lang['context_installation'] = 'Context Installation';
$_lang['context_manager_options'] = '<strong>Manager Context Options</strong> (back-end administration interface)';
$_lang['context_manager_path'] = 'Filesystem path for mgr context';
$_lang['context_manager_url'] = 'URL for mgr context';
$_lang['context_override'] = 'Leave these disabled to allow the system to auto-determine this information as shown.  By enabling a specific value, regardless if you manually set the value, you are indicating that you want the path to be set explicitly to that value in the configuration.';
$_lang['context_web_options'] = '<strong>Web Context Options</strong> (front-end web site)';
$_lang['context_web_path'] = 'Filesystem path for web context';
$_lang['context_web_url'] = 'URL for web context';
$_lang['continue'] = 'Jatka';
$_lang['dau_err_save'] = 'Error saving the default admin user.';
$_lang['dau_saved'] = 'Created default admin user.';
$_lang['db_check_db'] = 'Checking database:';
$_lang['db_connecting'] = 'Connecting to database server:';
$_lang['db_connected'] = 'Database connection successful!';
$_lang['db_created'] = 'Successfully created the database.';
$_lang['db_err_connect'] = 'Could not connect to the database.';
$_lang['db_err_connect_upgrade'] = 'Could not connect to the existing database for upgrade.  Check the connection properties and try again.';
$_lang['db_err_connect_server'] = 'Could not connect to the database server.  Check the connection properties and try again.';
$_lang['db_err_create'] = 'Error while attempting to create the database.';
$_lang['db_err_create_database'] = 'MODX could not create your database. Please manually create your database and then try again.';
$_lang['db_err_show_charsets'] = 'MODX could not get the available character sets from your MySQL server.';
$_lang['db_err_show_collations'] = 'MODX could not get the available collations from your MySQL server.';
$_lang['db_success'] = 'Onnistui!';
$_lang['db_test_coll_msg'] = 'Create or test selection of your database.';
$_lang['db_test_conn_msg'] = 'Test database server connection and view collations.';
$_lang['default_admin_user'] = 'Default Admin User';
$_lang['delete_setup_dir'] = 'Check this to DELETE the setup directory from the filesystem.';
$_lang['dir'] = 'ltr';
$_lang['email_err_ns'] = 'Virheellinen sähköpostiosoite';
$_lang['err_occ'] = 'Errors have occured!';
$_lang['err_update_table'] = 'Virhe päivitettäessä taulun luokkaa [[+class]]';
$_lang['errors_occurred'] = 'Errors were encountered during core installation.  Please review the installation results below, correct the problems and proceed as directed.';
$_lang['failed'] = 'Epäonnistui!';
$_lang['fatal_error'] = 'FATAL ERROR: MODX Setup cannot continue.';
$_lang['home'] = 'Etusivu';
$_lang['congratulations'] = 'Congratulations!';
$_lang['img_banner'] = 'assets/images/img_banner.gif';
$_lang['img_box'] = 'assets/images/img_box.png';
$_lang['img_splash'] = 'assets/images/img_splash.gif';
$_lang['install'] = 'Asenna';
$_lang['install_packages'] = 'Asenna paketteja';
$_lang['install_packages_desc'] = 'You can choose to install individual add-on packages.  Once you have installed all the optional packages you want, press Finish to complete the process.';
$_lang['install_packages_options'] = 'Package Installation Options';
$_lang['install_success'] = 'Core installation was successful. Click next to complete the installation process.';
$_lang['install_summary'] = 'Asennuksen yhteenveto';
$_lang['install_update'] = 'Asenna/Päivitä';
$_lang['installation_finished'] = 'Installation finished in [[+time]]';
$_lang['license'] = '<p class="title">You must agree to the License before continuing installation.</p>
    <p>Usage of this software is subject to the GPL license. To help you understand
    what the GPL licence is and how it affects your ability to use the software, we
    have provided the following summary:</p>
    <h4>The GNU General Public License is a Free Software license.</h4>
    <p>Like any Free Software license, it grants to you the four following freedoms:</p>
    <ul>
        <li>The freedom to run the program for any purpose. </li>
        <li>The freedom to study how the program works and adapt it to your needs. </li>
        <li>The freedom to redistribute copies so you can help your neighbor. </li>
        <li>The freedom to improve the program and release your improvements to the
        public, so that the whole community benefits. </li>
    </ul>
    <p>You may exercise the freedoms specified here provided that you comply with
    the express conditions of this license. The principal conditions are:</p>
    <ul>
        <li>You must conspicuously and appropriately publish on each copy distributed an
        appropriate copyright notice and disclaimer of warranty and keep intact all the
        notices that refer to this License and to the absence of any warranty; and give
        any other recipients of the Program a copy of the GNU General Public License
        along with the Program. Any translation of the GNU General Public License must
        be accompanied by the GNU General Public License.</li>

        <li>If you modify your copy or copies of the program or any portion of it, or
        develop a program based upon it, you may distribute the resulting work provided
        you do so under the GNU General Public License. Any translation of the GNU
        General Public License must be accompanied by the GNU General Public License. </li>

        <li>If you copy or distribute the program, you must accompany it with the
        complete corresponding machine-readable source code or with a written offer,
        valid for at least three years, to furnish the complete corresponding
        machine-readable source code.</li>

        <li>Any of these conditions can be waived if you get permission from the
        copyright holder.</li>

        <li>Your fair use and other rights are in no way affected by the above.</li>
    </ul>
    <p>The above is a summary of the GNU General Public License. By proceeding, you
    are agreeing to the GNU General Public Licence, not the above. The above is
    simply a summary of the GNU General Public Licence, and its accuracy is not
    guaranteed. It is strongly recommended you read the <a href="http://www.gnu.org/copyleft/gpl.html" onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;">GNU General Public
    License</a> in full before proceeding, which can also be found in the license
    file distributed with this package.</p>
';
$_lang['license_agree'] = 'I agree to the terms set out in this license.';
$_lang['license_agreement'] = 'Käyttöoikeussopimus';
$_lang['license_agreement_error'] = 'You must agree to the License before continuing installation.';
$_lang['login'] = 'Kirjaudu sisään';
$_lang['modx_class_err_nf'] = 'Could not include the MODX class file.';
$_lang['modx_configuration_file'] = 'MODX konfigurointitiedosto';
$_lang['modx_err_instantiate'] = 'Could not instantiate the MODX class.';
$_lang['modx_err_instantiate_mgr'] = 'Could not initialize the MODX manager context.';
$_lang['modx_footer1'] = '&copy; 2005-[[+current_year]] the <a href="http://www.modx.com/" onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;">MODX</a> Content Management Framework (CMF) project. All rights reserved. MODX is licensed under the GNU GPL.';
$_lang['modx_footer2'] = 'MODX is free software.  We encourage you to be creative and make use of MODX in any way you see fit. Just make sure that if you do make changes and decide to redistribute your modified MODX, that you keep the source code free!';
$_lang['modx_install'] = 'MODX asennus';
$_lang['modx_install_complete'] = 'MODX asennus valmis';
$_lang['modx_object_err'] = 'The MODX object could not be loaded.';
$_lang['next'] = 'Seuraava';
$_lang['none'] = 'Ei mitään';
$_lang['ok'] = 'OK!';
$_lang['options_core_inplace'] = 'Files are already in-place<br /><small>(Recommended for installation on shared servers.)</small>';
$_lang['options_core_inplace_note'] = 'Check this if you are using MODX from Git or extracted it from the full MODX package to the server prior to installation.';
$_lang['options_core_unpacked'] = 'Core Package has been manually unpacked<br /><small>(Recommended for installation on shared servers.)</small>';
$_lang['options_core_unpacked_note'] = 'Check this if you have manually extracted the core package from the file core/packages/core.transport.zip. This will reduce the time it takes for the installation process on systems that do not allow the PHP time_limit and Apache script execution time settings to be altered.';
$_lang['options_install_new_copy'] = 'Install a new copy of ';
$_lang['options_install_new_note'] = 'Please note this option may overwrite any data inside your database.';
$_lang['options_important_upgrade'] = 'Tärkeä päivitys huomautus';
$_lang['options_important_upgrade_note'] = 'Make sure all Manager users <strong>log out before upgrading</strong> to prevent problems (e.g., not being able to access resources). If you have trouble after upgrading, log out of any Manager sessions, clear your browser cache, then log in again.';
$_lang['options_new_file_permissions'] = 'Uudet tiedosto-oikeudet';
$_lang['options_new_file_permissions_note'] = 'You can override the permissions new files created via MODX will use, e.g., 0664 or 0666.';
$_lang['options_new_folder_permissions'] = 'Uudet hakemisto-oikeudet';
$_lang['options_new_folder_permissions_note'] = 'You can override the permissions new folders created via MODX will use, e.g., 0775 or 0777.';
$_lang['options_new_installation'] = 'Uusi asennus';
$_lang['options_nocompress'] = 'Poista CSS/JS pakkaus käytöstä';
$_lang['options_nocompress_note'] = 'Check this if the manager does not work with CSS/JS compression on.';
$_lang['options_send_poweredby_header'] = 'Send X-Powered-By Header';
$_lang['options_send_poweredby_header_note'] = 'When enabled, MODX will send the "X-Powered-By" header to identify this site as built on MODX. This helps tracking global MODX usage through third party trackers inspecting your site. Because this makes it easier to identify what your site is built with, it might pose a slightly increased security risk if a vulnerability is found in MODX.';
$_lang['options_title'] = 'Asennusasetukset';
$_lang['options_upgrade_advanced'] = 'Advanced Upgrade Install<br /><small>(edit database config)</small>';
$_lang['options_upgrade_advanced_note'] = 'For advanced database admins or moving to servers with a different database connection character set. <strong>You will need to know your full database name, user, password and connection/collation details.</strong>';
$_lang['options_upgrade_existing'] = 'Päivitä nykyinen asennus';
$_lang['options_upgrade_existing_note'] = 'Upgrade your current files and database.';
$_lang['package_execute_err_retrieve'] = 'The install failed because MODX could not unpack the [[+path]]packages/core.transport.zip package. Make sure that the [[+path]]packages/core.transport.zip file exists and is writable, and that you have made the [[+path]]packages/ directory writable.';
$_lang['package_err_install'] = 'Could not install package [[+package]].';
$_lang['package_err_nf'] = 'Could not retrieve package [[+package]] installation.';
$_lang['package_installed'] = 'Successfully installed package [[+package]].';
$_lang['password_err_invchars'] = 'Your password may not contain any invalid characters, such as /, \\, &apos;, &quot;, (, ) or {}.';
$_lang['password_err_nomatch'] = 'Does not match password';
$_lang['password_err_ns'] = 'Password is empty';
$_lang['password_err_short'] = 'Your password must be at least 6 characters long.';
$_lang['please_select_login'] = 'Please select the "Login" button to access the management interface.';
$_lang['preinstall_failure'] = 'Problems were detected.  Please review the pre-installation test results below, correct the problems as directed, and then click Test again.';
$_lang['preinstall_success'] = 'Pre-installation tests were successful.  Click Install below to continue.';
$_lang['refresh'] = 'Päivitä';
$_lang['request_handler_err_nf'] = 'Could not load the request handler at [[+path]] Make sure you have uploaded all the necessary files.';
$_lang['restarted_msg'] = 'MODX had to restart the setup process as a security precaution because setup was idle for over 15 minutes. Please re-attempt running setup at this time.';
$_lang['retry'] = 'Yritä uudelleen';
$_lang['security_notice'] = 'Tietoturvailmoitus';
$_lang['select'] = 'Valitse';
$_lang['settings_handler_err_nf'] = 'MODX could not find the modInstallSettings class at: [[+path]]. Please ensure you have uploaded all the files.';
$_lang['setup_err_remove'] = 'An error occurred while trying to remove the setup directory.';
$_lang['setup_err_assets'] = 'Your assets/ directory was not created at: [[+path]] <br />You will need to create this directory and make it writable if you want to use Package Management or 3rd-Party Components.';
$_lang['setup_err_assets_comp'] = 'Your assets/components/ directory was not created at: [[+path]] <br />You will need to create this directory and make it writable if you want to use Package Management or 3rd-Party Components.';
$_lang['setup_err_core_comp'] = 'Your core/components/ directory was not created at: [[+path]] <br />You will need to create this directory and make it writable if you want to use Package Management or 3rd-Party Components.';
$_lang['skip_to_bottom'] = 'scroll to bottom';
$_lang['success'] = 'Success';
$_lang['table_created'] = 'Successfully created table for class [[+class]]';
$_lang['table_err_create'] = 'Error creating table for class [[+class]]';
$_lang['table_updated'] = 'Successfully upgraded table for class [[+class]]';
$_lang['test_class_nf'] = 'Could not find the Install Test class at: [[+path]]<br />Please make sure you\'ve uploaded all the necessary files.';
$_lang['test_version_class_nf'] = 'Could not find the Install Test Versioner class at: [[+path]]<br />Please make sure you\'ve uploaded all the necessary files.';
$_lang['thank_installing'] = 'Thank you for installing ';
$_lang['transport_class_err_load'] = 'Error loading transport class.';
$_lang['toggle'] = 'Vaihda';
$_lang['toggle_success'] = 'Toggle Success Messages';
$_lang['toggle_warnings'] = 'Toggle Warnings';
$_lang['username_err_invchars'] = 'Käyttäjätunnuksesi ei saa sisältää virheellisiä merkkejä, kuten /, \\, &apos;, &quot; tai {}.';
$_lang['username_err_ns'] = 'Käyttäjätunnus on virheellinen';
$_lang['version'] = 'versio';
$_lang['warning'] = 'Varoitus';
$_lang['welcome'] = 'Tervetuloa MODX asennusohjelmaan.';
$_lang['welcome_message'] = '<p>Tämä ohjelma opastaa sinut läpi asennuksen.</p>
<p>Jatka painamalla "Seuraava"-painiketta:</p> ';
$_lang['workspace_err_nf'] = 'Could not find the active workspace.';
$_lang['workspace_err_path'] = 'Error setting the active workspace path.';
$_lang['workspace_path_updated'] = 'Updated the active workspace path.';
$_lang['versioner_err_nf'] = 'Could not find the Install Versioner at: [[+path]]<br />Please make sure you\'ve uploaded all the necessary files.';
$_lang['xpdo_err_ins'] = 'Could not instantiate xPDO.';
$_lang['xpdo_err_nf'] = 'MODX could not find the xPDO class at: [[+path]]. Please make sure it was uploaded correctly.';

$_lang['preload_err_cache'] = 'Make sure your [[+path]]cache directory exists and is writable by the PHP process.';
$_lang['preload_err_core_path'] = 'Make sure you have specified a valid MODX_CORE_PATH in your setup/includes/config.core.php file; this must point to a working MODX core.';
$_lang['preload_err_mysql'] = 'MODX requires the mysql extension when using PHP without native PDO and it does not appear to be loaded.';
$_lang['preload_err_pdo'] = 'MODX requires the PDO extension when native PDO is being used and it does not appear to be loaded.';
$_lang['preload_err_pdo_mysql'] = 'MODX requires the pdo_mysql driver when native PDO is being used and it does not appear to be loaded.';

$_lang['test_config_file'] = 'Checking if <span class="mono">[[+file]]</span> exists and is writable: ';
$_lang['test_config_file_nw'] = 'For new Linux/Unix installs, please create a blank file named <span class="mono">[[+file]].inc.php</span> in your MODX core <span class="mono">config/</span> directory with permissions set to be writable by PHP.';
$_lang['test_db_check'] = 'Luodaan yhteys tietokantaan: ';
$_lang['test_db_check_conn'] = 'Tarkista yhteystiedot ja yritä uudelleen.';
$_lang['test_db_failed'] = 'Tietokantayhteys epäonnistui!';
$_lang['test_db_setup_create'] = 'Setup will attempt to create the database.';
$_lang['test_dependencies'] = 'Checking PHP for zlib dependency: ';
$_lang['test_dependencies_fail_zlib'] = 'Your PHP installation does not have the "zlib" extension installed. This extension is necessary for MODX to run. Please enable it to continue.';
$_lang['test_directory_exists'] = 'Checking if <span class="mono">[[+dir]]</span> directory exists: ';
$_lang['test_directory_writable'] = 'Checking if <span class="mono">[[+dir]]</span> directory is writable: ';
$_lang['test_memory_limit'] = 'Checking if memory limit is set to at least 24M: ';
$_lang['test_memory_limit_fail'] = 'MODX found your memory_limit setting to be below the recommended setting of 24M. MODX attempted to set the memory_limit to 24M, but was unsuccessful. Please set the memory_limit setting in your php.ini file to 24M or higher before proceeding.';
$_lang['test_php_version_fail'] = 'You are running on PHP [[+version]], and MODX Revolution requires PHP 4.3.0 or later';
$_lang['test_php_version_sn'] = 'While MODX will work on your PHP version ([[+version]]), usage of MODX on this version is not recommended. Your version of PHP is vulnerable to numerous security holes. Please upgrade to PHP version is 4.3.11 or higher, which patches these holes. It is recommended you upgrade to this version for the security of your own website.';
$_lang['test_php_version_start'] = 'Tarkistetaan PHP versio:';
$_lang['test_sessions_start'] = 'Checking if sessions are properly configured:';
$_lang['test_table_prefix'] = 'Checking table prefix `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = 'Table prefix is already in use in this database!';
$_lang['test_table_prefix_inuse_desc'] = 'Setup couldn\'t install into the selected database, as it already contains tables with the prefix you specified. Please choose a new table_prefix, and run Setup again.';
$_lang['test_table_prefix_nf'] = 'Table prefix does not exist in this database!';
$_lang['test_table_prefix_nf_desc'] = 'Setup couldn\'t install into the selected database, as it does not contain existing tables with the prefix you specified to be upgraded. Please choose an existing table_prefix, and run Setup again.';
$_lang['test_zip_memory_limit'] = 'Checking if memory limit is set to at least 24M for zip extensions: ';
$_lang['test_zip_memory_limit_fail'] = 'MODX found your memory_limit setting to be below the recommended setting of 24M. MODX attempted to set the memory_limit to 24M, but was unsuccessful. Please set the memory_limit setting in your php.ini file to 24M or higher before proceeding, so that the zip extensions can work properly.';
