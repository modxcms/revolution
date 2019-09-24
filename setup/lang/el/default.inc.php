<?php
/**
 * English language files for Revolution 2.0.0 setup
 *
 * @package setup
 */
$_lang['additional_css'] = '';
$_lang['addons'] = 'Πρόσθετα';
$_lang['advanced_options'] = 'Προχωρημένες Ρυθμίσεις';
$_lang['all'] = 'All';
$_lang['app_description'] = 'CMS and PHP Application Framework';
$_lang['app_motto'] = 'MODX - Δημιουργήστε και κάνετε περισσότερα, με λιγότερα';
$_lang['back'] = 'Επιστροφή';
$_lang['base_template'] = 'BaseTemplate';
$_lang['cache_manager_err'] = 'MODX\'s Cache Manager could not be loaded.';
$_lang['choose_language'] = 'Επιλέξτε γλώσσα';
$_lang['cleanup_errors_title'] = 'Important Note:';
$_lang['cli_install_failed'] = 'Αποτυχία εγκατάστασης! Σφάλματα: [[+errors]]';
$_lang['cli_no_config_file'] = 'MODX could not find a configuration file (such as config.xml) for your CLI install. To run MODX Setup from the command line, you must provide a config xml file. See the official documentation for more information.';
$_lang['cli_tests_failed'] = 'Pre-Install Tests Failed! Errors: [[+errors]]';
$_lang['close'] = 'κλείσιμο';
$_lang['config_file_err_w'] = 'Σφάλμα εγγραφής αρχείου ρυθμίσεων.';
$_lang['config_file_perms_notset'] = 'Config file permissions were not updated. You may want to change the permissions on your config file to secure the file from tampering.';
$_lang['config_file_perms_set'] = 'Config file permissions successfully updated.';
$_lang['config_file_written'] = 'Config file successfully written.';
$_lang['config_key'] = 'MODX Configuration Key';
$_lang['config_key_change'] = 'If you would like to change the MODX configuration key, <a id="cck-href" href="javascript:void(0);">please click here.</a>';
$_lang['config_key_override'] = 'If you wish to run setup on a configuration key other than the one currently specified in your setup/includes/config.core.php, please specify it below.';
$_lang['config_not_writable_err'] = 'You have attempted to change a setting in setup/includes/config.core.php, but the file is not writable. Make the file writable or edit the file manually before continuing.';
$_lang['connection_character_set'] = 'Connection character set:';
$_lang['connection_collation'] = 'Collation:';
$_lang['connection_connection_and_login_information'] = 'Πληροφορίες σύνδεσης βάσης δεδομένων';
$_lang['connection_connection_note'] = 'Please enter the following information to connect to your MODX database. If there is no database yet, the installer will attempt to create it for you. (This may fail if your database configuration or the database user permissions do not allow it.)';
$_lang['connection_database_host'] = 'Database host:';
$_lang['connection_database_info'] = 'Now please enter the login data for your database.';
$_lang['connection_database_login'] = 'Όνομα σύνδεσης βάση δεδομένων:';
$_lang['connection_database_name'] = 'Όνομα βάσης δεδομένων:';
$_lang['connection_database_pass'] = 'Κωδικός πρόσβασης βάσης δεδομένων:';
$_lang['connection_database_type'] = 'Είδος βάσης δεδομένων:';
$_lang['connection_default_admin_email'] = 'Email Διαχειριστή:';
$_lang['connection_default_admin_login'] = 'Όνομα χρήστη διαχειριστή:';
$_lang['connection_default_admin_note'] = 'Now you\'ll need to enter some details for the main administrator account. You can fill in your own name here, and a password you\'re not likely to forget. You\'ll need these to log into Admin once setup is complete.';
$_lang['connection_default_admin_password'] = 'Κωδικός πρόσβασης διαχειριστή:';
$_lang['connection_default_admin_password_confirm'] = 'Επιβεβαίωση κωδικού πρόσβασης:';
$_lang['connection_default_admin_user'] = 'Default Admin User';
$_lang['connection_table_prefix'] = 'Πρόθεμα πίνακα:';
$_lang['connection_test_connection'] = 'Δοκιμή σύνδεσης';
$_lang['connection_title'] = 'Στοιχεία σύνδεσης';
$_lang['context_connector_options'] = '<strong>Connectors Options</strong> (AJAX connector services)';
$_lang['context_connector_path'] = 'Filesystem path for connectors';
$_lang['context_connector_url'] = 'URL for connectors';
$_lang['context_installation'] = 'Context Installation';
$_lang['context_manager_options'] = '<strong>Manager Context Options</strong> (back-end administration interface)';
$_lang['context_manager_path'] = 'Filesystem path for mgr context';
$_lang['context_manager_url'] = 'URL for mgr context';
$_lang['context_override'] = 'Leave these disabled to allow the system to auto-determine this information as shown.  By enabling a specific value, regardless if you manually set the value, you are indicating that you want the path to be set explicitly to that value in the configuration.';
$_lang['context_web_options'] = '<strong>Web Context Options</strong> (front-end web site)';
$_lang['context_web_path'] = 'Filesystem path for web context';
$_lang['context_web_url'] = 'URL for web context';
$_lang['continue'] = 'Συνέχεια';
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
$_lang['db_success'] = 'Επιτυχία!';
$_lang['db_test_coll_msg'] = 'Create or test selection of your database.';
$_lang['db_test_conn_msg'] = 'Test database server connection and view collations.';
$_lang['default_admin_user'] = 'Default Admin User';
$_lang['delete_setup_dir'] = 'Check this to DELETE the setup directory from the filesystem.';
$_lang['dir'] = 'ltr';
$_lang['email_err_ns'] = 'Email address is invalid';
$_lang['err_occ'] = 'Errors have occured!';
$_lang['err_update_table'] = 'Error updating table for class [[+class]]';
$_lang['errors_occurred'] = 'Errors were encountered during core installation.  Please review the installation results below, correct the problems and proceed as directed.';
$_lang['failed'] = 'Failed!';
$_lang['fatal_error'] = 'FATAL ERROR: MODX Setup cannot continue.';
$_lang['home'] = 'Αρχική';
$_lang['congratulations'] = 'Συγχαρητήρια!';
$_lang['img_banner'] = 'assets/images/img_banner.gif';
$_lang['img_box'] = 'assets/images/img_box.png';
$_lang['img_splash'] = 'assets/images/img_splash.gif';
$_lang['install'] = 'Εγκατάσταση';
$_lang['install_packages'] = 'Install Packages';
$_lang['install_packages_desc'] = 'You can choose to install individual add-on packages.  Once you have installed all the optional packages you want, press Finish to complete the process.';
$_lang['install_packages_options'] = 'Package Installation Options';
$_lang['install_success'] = 'Core installation was successful. Click next to complete the installation process.';
$_lang['install_summary'] = 'Περίληψη προόδου εγκατάστασης';
$_lang['install_update'] = 'Install/Update';
$_lang['installation_finished'] = 'Η εγκατάσταση ολοκληρώθηκε μέσα σε [[+time]]';
$_lang['license'] = '<p class="title">Πρέπει να συμφωνήσετε με την άδεια χρήσης, πριν προχωρήσετε με την εγκατάσταση</p>
<p>Η χρήση αυτού του προγράμματος υπόκειται στην άδεια GNU GPL. Παρακάτω παραθέτουμε μία σύντομη περίληψη του τι περιλαμβάνει η άδεια GNU GPL και πώς μπορεί να περιορίσει τη χρήση του λογισμικού:</p>
<h4>Η Γενική Άδεια Δημόσιας Χρήσης (GPL) GNU είναι μια άδεια Ελεύθερου Λογισμικού </h4>
<p>Όπως όλες οι άδειες Ελεύθερου Λογισμικού, σας παρέχει τις παρακάτω τέσσερις ελευθερίες</p>
<ul>
<li>Την δυνατότητα να χρησιμοποιήσετε το πρόγραμμα για οποιοδήποτε σκοπό.</li>
<li>Την δυνατότητα να μελετήσετε το πώς λειτουργεί το πρόγραμμα και να το προσαρμόσετε στις ανάγκες σας.</li>
<li>Την άδεια να αναδιανέμετε το πρόγραμμα.</li>
<li>Την άδεια να τροποποιήσετε ή να βελτιώσετε το πρόγραμμα και να δημοσιοποιήσετε το τροποποιημένο πρόγραμμα</li>
</ul>
<p>Αυτές οι ελευθερίες σας παρέχονται υπό την προϋπόθεση ότι θα συμμορφωθείτε με τους όρους που καθορίζει αυτή η άδεια. Οι κύριες προϋποθέσεις είναι:</p>
<ul>
<li>Κάθε αντίγραφο που αναδιανέμετε πρέπει να συμπεριλαμβάνει μια κατάλληλη ανακοίνωση Πνευματικών Δικαιωμάτων και αποκήρυξη εγγύησης. Το περιεχόμενο της παρούσας Άδειας (συμπεριλαμβανομένης της αποκύρηξης ευθύνης) πρέπει να περιέχονται χωρίς τροποποιήσεις. Όλοι οι αποδέκτες του προγράμματος που διανέμετε θα πρέπει να λαμβάνουν και ένα αντίγραφο της Γενική Άδεια Δημόσιας Χρήσης (GPL) GNU. Οποιαδήποτε ανεπίσημη μετάφραση της Γενική Άδεια Δημόσιας Χρήσης (GPL) GNU θα πρέπει να συνοδεύεται με το αυθεντικό κείμενο της Άδειας.</li>

<li>Εάν τροποποιήσετε όλο ή μέρος του λογισμικού και το διανείμετε με τις τροποποιήσεις, ή αν δημιουργήσετε ένα καινούριο λογισμικό βασισμένο στο παρόν, το τροποποιημένο λογισμικό θα πρέπει κι αυτό να καλύπτεται απ\' την Γενική Άδεια Δημόσιας Χρήσης (GPL) GNU. Οποιαδήποτε ανεπίσημη μετάφραση της Γενική Άδεια Δημόσιας Χρήσης (GPL) GNU θα πρέπει να συνοδεύεται με το αυθεντικό κείμενο της Άδειας.</li>

<li>Εάν αντιγράψετε, τροποποιήσετε ή αναδιανείμετε το παρόν λογισμικό, θα πρέπει να συνοδεύετε το αντίγραφό σας με τον πλήρη πηγιαίο κώδικα, ή με μία γραπτή υπόσχεση (με ισχύ τουλάχιστον τρία χρόνια) ότι θα παράσχετε τον πλήρη πηγιαίο κώδικα.</li>

<li>Οποιαδήποτε από τις παραπάνω προϋποθέσεις μπορούν να αναιρεθούν, εάν πάρετε άδεια από τον κάτοχο Πνευματικών Δικαιωμάτων του λογισμικού.</li>

<li>Τα δικαιώματά σας για δίκαιη χρήση του λογισμικού δεν επηρεάζονται απ\' τους παραπάνω όρους.</li>
</ul>
<p>Το παραπάνω κείμενο είναι μία μεταφρασμένη περίληψη της Γενική Άδεια Δημόσιας Χρήσης (GPL) GNU. Εάν συνεχίσετε με την εγκατάσταση, συμφωνείτε με την Γενική Άδεια Δημόσιας Χρήσης (GPL) GNU, και όχι με τα παραπάνω. Το παραπάνω είναι απλώς μία μετάφραση της περίληψης της Γενική Άδεια Δημόσιας Χρήσης (GPL) GNU που συνοδεύει το MODX και δεν εγγυούμαστε για την εγκυρότητά της. Συνιστούμε θερμά να διαβάσετε <a href="http://www.gnu.org/copyleft/gpl.html" onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;">το πλήρες κείμενο της Γενική Άδεια Δημόσιας Χρήσης (GPL) GNU</a> πριν προχωρήσετε. Το πλήρες κείμενο μπορεί επίσης να βρεθεί στα περιεχόμενα αυτού του προγράμματος.</p>
';
$_lang['license_agree'] = 'I agree to the terms set out in this license.';
$_lang['license_agreement'] = 'Συμφωνία Άδειας Χρήσης';
$_lang['license_agreement_error'] = 'Πρέπει να συμφωνήσετε με την άδεια πριν προχωρήσετε στην εγκατάσταση.';
$_lang['locked'] = 'MODX Setup is locked!';
$_lang['locked_message'] = '<p>You will need to remove the setup/.locked/ directory in order to proceed.</p>';
$_lang['login'] = 'Σύνδεση';
$_lang['modx_class_err_nf'] = 'Could not include the MODX class file.';
$_lang['modx_configuration_file'] = 'MODX configuration file';
$_lang['modx_err_instantiate'] = 'Could not instantiate the MODX class.';
$_lang['modx_err_instantiate_mgr'] = 'Could not initialize the MODX manager context.';
$_lang['modx_footer1'] = '&copy; 2005-[[+current_year]] από το Πλαίσιο Διαχείρισης Περιεχομένου (CMF) <a href="http://www.modx.com/" onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;">MODX</a>. Με την επιφύλαξη όλων των δικαιωμάτων. Το MODX καλύπτεται από την άδεια GNU GPL.';
$_lang['modx_footer2'] = 'MODX is free software.  We encourage you to be creative and make use of MODX in any way you see fit. Just make sure that if you do make changes and decide to redistribute your modified MODX, that you keep the source code free!';
$_lang['modx_install'] = 'Εγκατάσταση του MODX';
$_lang['modx_install_complete'] = 'Η εγκατάσταση του MODX έχει ολοκληρωθεί';
$_lang['modx_object_err'] = 'The MODX object could not be loaded.';
$_lang['next'] = 'Next';
$_lang['none'] = 'Δεν έχει οριστεί';
$_lang['ok'] = 'OK!';
$_lang['options_core_inplace'] = 'Τα αρχεία βρίσκονται ήδη στην θέση τους<br /><small>(Προτείνεται για εγκατάσταση σε shared servers.)</small>';
$_lang['options_core_inplace_note'] = 'Επιλέξτε αυτό εάν χρησιμοποιείτε το MODX από Git ή εάν, πριν την εγκατάσταση, αποσυμπιέσατε τα αρχεία απ\' το πλήρες πακέτο του MODX στον server.';
$_lang['options_core_unpacked'] = 'Το πακέτο Core έχει ήδη αποσυμπιεστεί<br /><small>(Προτείνεται για εγκατάσταση σε shared servers.)</small>';
$_lang['options_core_unpacked_note'] = 'Επιλέξτε αυτό εάν αποσυμπιέσατε το πακέτο Core απ\' το αρχείο: "core/packages/core.transport.zip". Έτσι θα μπορέσουμε να μειώσουμε το χρόνο εγκατάστασης σε συστήματα που δεν επιτρέπουν αλλαγές στο PHP time_limit και στις ρυθμίσεις χρόνου των δεσμών ενεργειών του Apache.';
$_lang['options_install_new_copy'] = 'Install a new copy of ';
$_lang['options_install_new_note'] = 'Please note this option may overwrite any data inside your database.';
$_lang['options_important_upgrade'] = 'Important Upgrade Note';
$_lang['options_important_upgrade_note'] = 'Make sure all Manager users <strong>log out before upgrading</strong> to prevent problems (e.g., not being able to access resources). If you have trouble after upgrading, log out of any Manager sessions, clear your browser cache, then log in again.';
$_lang['options_new_file_permissions'] = 'New file permissions';
$_lang['options_new_file_permissions_note'] = 'You can override the permissions new files created via MODX will use, e.g., 0664 or 0666.';
$_lang['options_new_folder_permissions'] = 'New folder permissions';
$_lang['options_new_folder_permissions_note'] = 'You can override the permissions new folders created via MODX will use, e.g., 0775 or 0777.';
$_lang['options_new_installation'] = 'Νέα εγκατάσταση';
$_lang['options_nocompress'] = 'Disable CSS/JS compression';
$_lang['options_nocompress_note'] = 'Check this if the manager does not work with CSS/JS compression on.';
$_lang['options_send_poweredby_header'] = 'Αποστολή X-Powered-By Header';
$_lang['options_send_poweredby_header_note'] = 'Όταν η επιλογή αυτή είναι ενεργοποιημένη, το MODX θα προσθέτει στον header "X-Powered-By", υποδηλώνοντας ότι αυτό το site έχει φτιαχτεί με MODX. Με αυτόν τον τρόπο συμβάλλετε στην καταμέτρηση της χρήσης του MODX. Ωστόσο, στην περίπτωση που βρεθουν προβλήματα ασφαλείας στο MODX, πιθανώς αυτό να επηρεάσει και την δική σας ασφάλεια, μιας και δηλώνετε στους header της ιστοσελίδας σας ότι χρησιμοποιείτε MODX.';
$_lang['options_title'] = 'Install Options';
$_lang['options_upgrade_advanced'] = 'Advanced Upgrade Install<br /><small>(edit database config)</small>';
$_lang['options_upgrade_advanced_note'] = 'For advanced database admins or moving to servers with a different database connection character set. <strong>You will need to know your full database name, user, password and connection/collation details.</strong>';
$_lang['options_upgrade_existing'] = 'Upgrade Existing Install';
$_lang['options_upgrade_existing_note'] = 'Upgrade your current files and database.';
$_lang['package_execute_err_retrieve'] = 'The install failed because MODX could not unpack the [[+path]]packages/core.transport.zip package. Make sure that the [[+path]]packages/core.transport.zip file exists and is writable, and that you have made the [[+path]]packages/ directory writable.';
$_lang['package_err_install'] = 'Could not install package [[+package]].';
$_lang['package_err_nf'] = 'Δεν ήταν δυνατή η ανάκτηση των αρχείων εγκατάστασης του πακέτου [[+package]].';
$_lang['package_installed'] = 'Successfully installed package [[+package]].';
$_lang['password_err_invchars'] = 'Your password may not contain any invalid characters, such as /, \\, &apos;, &quot;, (, ) or {}.';
$_lang['password_err_nomatch'] = 'Does not match password';
$_lang['password_err_ns'] = 'Password is empty';
$_lang['password_err_short'] = 'Your password must be at least [[+length]] characters long.';
$_lang['please_select_login'] = 'Please select the "Login" button to access the management interface.';
$_lang['preinstall_failure'] = 'Παρουσιάστηκαν προβλήματα. Παρακάτω παραθέτουμε τα αποτελέσματα των τεστ εγκατάσης. Διορθώστε τα προβλήματα και πατήστε ξανά "Test".';
$_lang['preinstall_success'] = 'Τα τεστ εγκατάστασης ήταν επιτυχή. Πατήστε "Εγκατάσταση" για να συνεχίσετε.';
$_lang['refresh'] = 'Ανανέωση';
$_lang['request_handler_err_nf'] = 'Could not load the request handler at [[+path]] Make sure you have uploaded all the necessary files.';
$_lang['restarted_msg'] = 'MODX had to restart the setup process as a security precaution because setup was idle for over 15 minutes. Please re-attempt running setup at this time.';
$_lang['retry'] = 'Retry';
$_lang['security_notice'] = 'Security Notice';
$_lang['select'] = 'Select';
$_lang['settings_handler_err_nf'] = 'MODX could not find the modInstallSettings class at: [[+path]]. Please ensure you have uploaded all the files.';
$_lang['setup_err_lock'] = 'An error occurred while trying lock setup. Could not create the .locked subdirectory inside the setup directory.';
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
$_lang['toggle'] = 'Toggle';
$_lang['toggle_success'] = 'Toggle Success Messages';
$_lang['toggle_warnings'] = 'Toggle Warnings';
$_lang['username_err_invchars'] = 'Your username may not contain any invalid characters, such as /, \\, &apos;, &quot;, or {}.';
$_lang['username_err_ns'] = 'Username is invalid';
$_lang['version'] = 'version';
$_lang['warning'] = 'Warning';
$_lang['welcome'] = 'Καλως ήρθατε στο πρόγραμμα εγκατάστασης του MODX.';
$_lang['welcome_message'] = '<p>Βρίσκεστε στον οδηγό εγκατάστασης του MODX</p>
<p>Επιλέξτε "Επόμενο" για να συνεχίσετε:</p>
';
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
$_lang['test_db_check'] = 'Creating connection to the database: ';
$_lang['test_db_check_conn'] = 'Check the connection details and try again.';
$_lang['test_db_failed'] = 'Database connection failed!';
$_lang['test_db_setup_create'] = 'Setup will attempt to create the database.';
$_lang['test_dependencies'] = 'Checking PHP for zlib dependency: ';
$_lang['test_dependencies_fail_zlib'] = 'Η εγκατάσταση PHP σας δεν περιλαμβάνει το extension "zlib". Αυτό το extension είναι απαραίτητο για τη λειτουργία του MODX. Πριν συνεχίσετε, ενεργοποιήστε το "zlib".';
$_lang['test_directory_exists'] = 'Checking if <span class="mono">[[+dir]]</span> directory exists: ';
$_lang['test_directory_writable'] = 'Checking if <span class="mono">[[+dir]]</span> directory is writable: ';
$_lang['test_memory_limit'] = 'Checking if memory limit is set to at least 24M: ';
$_lang['test_memory_limit_fail'] = 'MODX found your memory_limit setting to be below the recommended setting of 24M. MODX attempted to set the memory_limit to 24M, but was unsuccessful. Please set the memory_limit setting in your php.ini file to 24M or higher before proceeding.';
$_lang['test_php_version_fail'] = 'You are running on PHP [[+version]], and MODX Revolution requires PHP 4.3.0 or later';
$_lang['test_php_version_sn'] = 'While MODX will work on your PHP version ([[+version]]), usage of MODX on this version is not recommended. Your version of PHP is vulnerable to numerous security holes. Please upgrade to PHP version is 4.3.11 or higher, which patches these holes. It is recommended you upgrade to this version for the security of your own website.';
$_lang['test_php_version_start'] = 'Checking PHP version:';
$_lang['test_sessions_start'] = 'Checking if sessions are properly configured:';
$_lang['test_table_prefix'] = 'Checking table prefix `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = 'Table prefix is already in use in this database!';
$_lang['test_table_prefix_inuse_desc'] = 'Setup couldn\'t install into the selected database, as it already contains tables with the prefix you specified. Please choose a new table_prefix, and run Setup again.';
$_lang['test_table_prefix_nf'] = 'Table prefix does not exist in this database!';
$_lang['test_table_prefix_nf_desc'] = 'Setup couldn\'t install into the selected database, as it does not contain existing tables with the prefix you specified to be upgraded. Please choose an existing table_prefix, and run Setup again.';
$_lang['test_zip_memory_limit'] = 'Checking if memory limit is set to at least 24M for zip extensions: ';
$_lang['test_zip_memory_limit_fail'] = 'MODX found your memory_limit setting to be below the recommended setting of 24M. MODX attempted to set the memory_limit to 24M, but was unsuccessful. Please set the memory_limit setting in your php.ini file to 24M or higher before proceeding, so that the zip extensions can work properly.';
