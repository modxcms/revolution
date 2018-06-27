<?php
/**
 * English Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['mysql_err_ext'] = 'Το MODX χρειάζεται την επέκταση mysql για PHP, ωστόσο αυτή η επέκταση δεν έχει φορτώσει.';
$_lang['mysql_err_pdo'] = 'MODX requires the pdo_mysql driver when native PDO is being used and it does not appear to be loaded.';
$_lang['mysql_version_5051'] = 'Το MODX ενδέχεται να συναντήσει προβλήματα με την MySQL έκδοση την οποία χρησιμοποιείτε ([[+version]]), εξαιτίας των πολλών bug που εμφανίζουν οι οδηγοί PDO της συγκεκριμένης έκδοσης. Παρακαλώ αναβαθμίστε την MySQL σας για να αποφύγετε αυτά τα προβλήματα. Ακόμα και αν δεν χρησιμοποιείσετε το MODX, καλό είναι να αναβαθμίσετε την έκδοση της MySQL έτσι κι αλλιώς, για καλύτερη ασφάλεια και σταθερότητα της ιστοσελίδας σας.';
$_lang['mysql_version_client_nf'] = 'Το MODX δεν μπόρεσε να βρει την έκδοση του client MySQL με την εντολή mysql_get_client_info(). Παρακαλώ βεβαιωθείτε ότι έχετε client MySQL έκδοσης τουλάχιστον 4.1.20 πριν συνεχίσετε.';
$_lang['mysql_version_client_start'] = 'Έλεγχος έκδοσης της MySQL:';
$_lang['mysql_version_client_old'] = 'Χρησιμοποιείτε μία πολύ παλιά έκδοση της MySQL ([[+version]]) και αυτό μπορεί να δημιουργήσει προβλήματα στη λειτουργία του MODX. Το MODX θα συνεχίσει την εγκατάσταση, αλλά δεν μπορούμε να εγγυηθούμε ότι θα έχετε πλήρη λειτουργικότητα και ομαλή λειτουργία.';
$_lang['mysql_version_fail'] = 'Χρησιμοποιείτε MySQL έκδοσης [[+version]], ενώ το MODX Revolution χρειάζεται MySQL έκδοσης τουλάχιστον 4.1.20. Παρακαλώ αναβαθμίστε τη MySQL σε έκδοση 4.1.20 ή πιο πρόσφατη.';
$_lang['mysql_version_server_nf'] = 'Το MODX δεν μπόρεσε να βρει την έκδοση του server MySQL με την εντολή mysql_get_server_info(). Παρακαλώ βεβαιωθείτε ότι έχετε MySQL server έκδοσης τουλάχιστον 4.1.20 πριν συνεχίσετε.';
$_lang['mysql_version_server_start'] = 'Έλεγχος της έκδοσης του server MySQL:';
$_lang['mysql_version_success'] = 'Εντάξει! Χρήση: [[+version]]';

$_lang['sqlsrv_version_success'] = 'OK!';
$_lang['sqlsrv_version_client_success'] = 'OK!';