<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'Checking if <span class="mono">[[+file]]</span> exists and is writable: ';
$_lang['test_config_file_nw'] = 'Για εγκατάσταση σε περιβάλλον Linux/Unix, πρέπει να δημιουργήσετε ένα κενό αρχείο με όνομα <span class="mono">[[+key]].inc.php</span> στο φάκελο του MODX core <span class="mono">config/</span>. Αυτό το αρχείο πρέπει να έχει δικαιώματα εγγραφής από την PHP.';
$_lang['test_db_check'] = 'Creating connection to the database: ';
$_lang['test_db_check_conn'] = 'Check the connection details and try again.';
$_lang['test_db_failed'] = 'Database connection failed!';
$_lang['test_db_setup_create'] = 'Setup will attempt to create the database.';
$_lang['test_dependencies'] = 'Checking PHP for zlib dependency: ';
$_lang['test_dependencies_fail_zlib'] = 'Η εγκατάσταση PHP σας δεν περιλαμβάνει το extension "zlib". Αυτό το extension είναι απαραίτητο για τη λειτουργία του MODX. Πριν συνεχίσετε, ενεργοποιήστε το "zlib".';
$_lang['test_directory_exists'] = 'Checking if <span class="mono">[[+dir]]</span> directory exists: ';
$_lang['test_directory_writable'] = 'Checking if <span class="mono">[[+dir]]</span> directory is writable: ';
$_lang['test_memory_limit'] = 'Checking if memory limit is set to at least 24M: ';
$_lang['test_memory_limit_fail'] = 'Έχετε όριο μνήμης memory_limit στα [[+memory]], το οποίο είναι λιγότερο από τα 24M που συστήνει το MODX. Το MODX δεν κατάφερε να τροποποιήσει το memory_limit στα 24M. Παρακαλώ καθορίστε memory_limit στο αρχείο php.ini να είναι τουλάχιστον 24M, πριν συνεχίσετε με την εγκατάσταση. Εάν εξακολουθείτε να έχετε δυσκολίες κατά τη διάρκεια της εγκατάστασης, ή και μετά (πχ, κενή οθόνη), ίσως πρέπει να καθορίσετε τη μνήμη στα 32M, 64M, ή και περισσότερο.';
$_lang['test_memory_limit_success'] = 'Εντάξει! Καθορίστηκε στα [[+memory]]';
$_lang['test_mysql_version_5051'] = 'Το MODX ενδέχεται να συναντήσει προβλήματα με την MySQL έκδοση την οποία χρησιμοποιείτε ([[+version]]), εξαιτίας των πολλών bug που εμφανίζουν οι οδηγοί PDO της συγκεκριμένης έκδοσης. Παρακαλώ αναβαθμίστε την MySQL σας για να αποφύγετε αυτά τα προβλήματα. Ακόμα και αν δεν χρησιμοποιείσετε το MODX, καλό είναι να αναβαθμίσετε την έκδοση της MySQL έτσι κι αλλιώς, για καλύτερη ασφάλεια και σταθερότητα της ιστοσελίδας σας.';
$_lang['test_mysql_version_client_nf'] = 'Αδυναμία εντοπισμού της έκδοσης του client της MySQL!';
$_lang['test_mysql_version_client_nf_msg'] = 'Το MODX δεν μπόρεσε να βρει την έκδοση του client MySQL με την εντολή mysql_get_client_info(). Παρακαλώ βεβαιωθείτε ότι έχετε client MySQL έκδοσης τουλάχιστον 4.1.20 πριν συνεχίσετε.';
$_lang['test_mysql_version_client_old'] = 'Μπορεί να παρουσιαστούν προβλήματα στην εγκατάσταση, ή στη λειτουργία του MODX, λόγω του ότι χρησιμοποιείτε μια πολύ παλιά έκδοση του client της MySQL ([[+version]])';
$_lang['test_mysql_version_client_old_msg'] = 'Το MODX θα συνεχίσει την εγκατάσταση, αλλά, λόγω του ότι η έκδοση του client της MySQL σας είναι παλιά, δεν μπορούμε να εγγυηθούμε ότι θα έχετε πλήρη λειτουργικότητα και σωστή λειτουργία.';
$_lang['test_mysql_version_client_start'] = 'Έλεγχος έκδοσης της MySQL:';
$_lang['test_mysql_version_fail'] = 'Χρησιμοποιείτε MySQL έκδοσης [[+version]], ενώ το MODX Revolution χρειάζεται MySQL έκδοσης τουλάχιστον 4.1.20. Παρακαλώ αναβαθμίστε τη MySQL σε έκδοση 4.1.20 ή πιο πρόσφατη.';
$_lang['test_mysql_version_server_nf'] = 'Αδυναμία εντοπισμού της έκδοσης του server της MySQL!';
$_lang['test_mysql_version_server_nf_msg'] = 'Το MODX δεν μπόρεσε να βρει την έκδοση του server MySQL με την εντολή mysql_get_server_info(). Παρακαλώ βεβαιωθείτε ότι έχετε MySQL server έκδοσης τουλάχιστον 4.1.20 πριν συνεχίσετε.';
$_lang['test_mysql_version_server_start'] = 'Έλεγχος της έκδοσης του server MySQL:';
$_lang['test_mysql_version_success'] = 'Εντάξει! Χρήση: [[+version]]';
$_lang['test_nocompress'] = 'Έλεγχος του αν ζητήθηκε συμπίεση αρχείων CSS/JS: ';
$_lang['test_nocompress_disabled'] = 'Εντάξει! Απενεργοποιήθηκε.';
$_lang['test_nocompress_skip'] = 'Δεν επιλέχτηκε, παράλειψη ελέγχου.';
$_lang['test_php_version_fail'] = 'Η έκδοση PHP που χρησιμοποιείτε είναι: [[+version]] και το MODX Revolution χρειάζεται έκδοση [[+required]] τουλάχιστον. Παρακαλούμε αναβαθμίστε την PHP στην έκδοση [[+required]] το λιγότερο. Σας συνιστούμε να την αναβαθμίσετε σε έκδοση [[+recommended]] για λόγους ασφαλείας και για μελλοντική συμβατότητα.';
$_lang['test_php_version_start'] = 'Checking PHP version:';
$_lang['test_php_version_success'] = 'Εντάξει! Χρήση: [[+version]]';
$_lang['test_safe_mode_start'] = 'Έλεγχος ότι έχει απενεργοποιηθεί η ασφαλής λειτουργία safe_mode:';
$_lang['test_safe_mode_fail'] = 'Η ασφαλής λειτουργία της PHP είναι ενεργοποιημένη. Πρέπει να απενεργοποιήσετε το safe_mode στις ρυθμίσεις PHP για να μπορέσετε να συνεχίσετε.';
$_lang['test_sessions_start'] = 'Checking if sessions are properly configured:';
$_lang['test_simplexml'] = 'Αναζήτηση SimpleXML:';
$_lang['test_simplexml_nf'] = 'Δεν βρέθηκε το SimpleXML!';
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