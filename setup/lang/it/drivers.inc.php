<?php
/**
 * Italian Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['mysql_err_ext'] = 'MODx richiede l\'estensione mysql per PHP e non sembra essere caricata.';
$_lang['mysql_err_pdo'] = 'MODx richiede il driver pdo_mysql quando PDO nativo viene usato e non sembra essere caricato.';
$_lang['mysql_version_5051'] = 'MODx avra\' problemi con la tua versione MySQL ([[+version]]), dovuti ai numerosi bugs dei drivers PDO con questa versione. Per favore aggiorna MySQL per risolvere questi problemi. Anche se scegli di non usare MODx, e\' raccomandato l\'aggiornamento di questa versione per la sicurezza e la stabilita\' del tuo stesso sito.';
$_lang['mysql_version_client_nf'] = 'MODx non ha potuto rilevare la versione del tuo client MySQL tramite mysql_get_client_info(). Per favore assicurati personalmente che la versione del tuo client MySQL sia almeno la 4.1.20 prima di procedere.';
$_lang['mysql_version_client_start'] = 'Controllo versione client MySQL:';
$_lang['mysql_version_client_old'] = 'MODx pu\' avere problemi perche\' stai usando una versione di client MySQL molto vecchia ([[+version]]). MODx consentira\' l\'installazione con questa versione di client MySQL, ma non possiamo garantire che tutte le funzionalita\' saranno disponibili o che funzioneranno correttamente quando saranno usate vecchie versioni delle librerie del client MySQL.';
$_lang['mysql_version_fail'] = 'Stai usando MySQL [[+version]], e MODx Revolution richiede MySQL 4.1.20 o successivo. Per favore aggiorna MySQL ad almeno 4.1.20.';
$_lang['mysql_version_server_nf'] = 'MODx non ha potuto rilevare la versione del tuo server MySQL tramite mysql_get_server_info(). Per favore assicurati personalmente che la versione del tuo server MySQL sia almeno la  4.1.20 prima di procedere.';
$_lang['mysql_version_server_start'] = 'Controllo versione server MySQL:';
$_lang['mysql_version_success'] = 'OK! Sta girando: [[+version]]';

$_lang['sqlsrv_version_success'] = 'OK!';
$_lang['sqlsrv_version_client_success'] = 'OK!';