<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'Controllo che <span class="mono">[[+file]]</span> esista e sia scrivibile: ';
$_lang['test_config_file_nw'] = 'Per le nuove installazioni Linux/Unix: creare un file vuoto con nome <span class="mono">[[+key]].inc.php</span> nella directory <span class="mono">config/</span> dentro a MODX core, con i permessi di scrittura per PHP.';
$_lang['test_db_check'] = 'Creazione della connessione al database: ';
$_lang['test_db_check_conn'] = 'Controlla i dettagli della connessione e prova ancora.';
$_lang['test_db_failed'] = 'Connessione al database fallita!';
$_lang['test_db_setup_create'] = 'Il programma di setup tenter&agrave; di creare il database.';
$_lang['test_dependencies'] = 'Controllo le dipedendenze di PHP per zlib: ';
$_lang['test_dependencies_fail_zlib'] = 'La tua installazione di PHP non ha l\'estensione "zlib" installata. Questa estensione &egrave; necessaria per far girare MODX. Abilitala per poter continuare.';
$_lang['test_directory_exists'] = 'Controllo che la directory <span class="mono">[[+dir]]</span> esista: ';
$_lang['test_directory_writable'] = 'Controllo che la directory <span class="mono">[[+dir]]</span> sia scrivibile: ';
$_lang['test_memory_limit'] = 'Controllo che il valore memory limit sia almeno 24M: ';
$_lang['test_memory_limit_fail'] = 'MODX ha riscontrato che il tuo valore memory_limit risulta [[+memory]], sotto al limite raccomandato di 24M. MODX ha tentato di impostare il memory_limit a 24M, ma senza successo. Per favore imposta questo valore del memory_limit nel tuo file php.ini file ad almeno 24M prima di procedere. Se continui a riscontrare problemi (come una schermata bianca durante l\'installazione), impostalo a 32M, 64M o pi&ugrave; alto.';
$_lang['test_memory_limit_success'] = 'OK! Impostato a [[+memory]]';
$_lang['test_mysql_version_5051'] = 'MODX avr&agrave; problemi con la tua versione di MySQL ([[+version]]), a causa dei numerosi bugs collegati ai drivers PDO di questa versione. Si prega di aggiornare MySQL per risolvere questi problemi. Anche se scegli di non usare MODX, &egrave; raccomandabile che tu aggiorni questa versione per la sicurezza e la stabilit&agrave; del tuo stesso sito.';
$_lang['test_mysql_version_client_nf'] = 'Impossibile determinare la versione del client MySQL!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODX non ha potuto determinare la versione del tuo client MySQL via mysql_get_client_info(). Per favore assicurati personalmente che la versione del tuo client MySQL sia almeno 4.1.20 prima di procedere.';
$_lang['test_mysql_version_client_old'] = 'MODX pu&ograve; avere problemi perch&eacute; stai usando una versione molto vecchia di client MySQL ([[+version]])';
$_lang['test_mysql_version_client_old_msg'] = 'MODX consentir&agrave; l\'installazione con questa versione di client MySQL, ma non possiamo garantire che tutte le funzionalit&agrave; saranno disponibili o che si comporteranno correttamente quando saranno utilizzate librerie pi&ugrave; vecchie del client MySQL.';
$_lang['test_mysql_version_client_start'] = 'Controllo della versione del client MySQL:';
$_lang['test_mysql_version_fail'] = 'Stai girando su MySQL [[+version]], e MODX Revolution richiede MySQL 4.1.20 o successivo. Per favore aggiorna MySQL ad almeno la versione 4.1.20.';
$_lang['test_mysql_version_server_nf'] = 'Impossibile determinare la versione del server MySQL!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODX non ha potuto determinare la versione del tuo server MySQL via mysql_get_server_info(). Per favore assicurati personalmente che la versione del tuo server MySQL server sia almeno la 4.1.20 prima di procedere.';
$_lang['test_mysql_version_server_start'] = 'Controllo della versione del server MySQL:';
$_lang['test_mysql_version_success'] = 'OK! Sta girando: [[+version]]';
$_lang['test_php_version_fail'] = 'Stai girando su PHP [[+version]], e MODX Revolution richiede PHP 5.1.1 o successivo. Per favore aggiorna PHP ad almeno la 5.1.1. MODX raccomanda di aggiornare ad almeno la 5.3.2+.';
$_lang['test_php_version_516'] = 'MODX avr&agrave; problemi con la tua versione PHP ([[+version]]), a causa dei numerosi bugs relativi ai drivers PDO di questa versione. Per favore aggiorna PHP alla versione 5.3.0 o una successiva, che risolva questi problemi. MODX raccomanda di aggiornare ad almeno la 5.3.2+. Anche se scegli di non usare MODX, &egrave; raccomandabile che tu aggiorni questa versione di PHP per la sicurezza e la stabilit&agrave; del tuo stesso sito.';
$_lang['test_php_version_520'] = 'MODX avr&agrave; problemi con la tua versione PHP ([[+version]]), a causa dei numerosi bugs relativi ai drivers PDO di questa versione. Per favore aggiorna PHP alla versione 5.3.0 o una successiva, che risolva questi problemi. MODX raccomanda di aggiornare ad almeno la 5.3.2+. Anche se scegli di non usare MODX, &egrave; raccomandabile che tu aggiorni questa versione di PHP per la sicurezza e la stabilit&agrave; del tuo stesso sito.';
$_lang['test_php_version_start'] = 'Controllo della versione PHP :';
$_lang['test_php_version_success'] = 'OK! Sta girando: [[+version]]';
$_lang['test_safe_mode_start'] = 'Controllo che safe_mode sia off:';
$_lang['test_safe_mode_fail'] = 'MODX ha trovato il parametro safe_mode impostato su on. Devi disabilitare safe_mode nella tua configurazione PHP per procedere.';
$_lang['test_sessions_start'] = 'Controllo che le sessioni siano correttamente configurate:';
$_lang['test_simplexml'] = 'Controllo SimpleXML:';
$_lang['test_simplexml_nf'] = 'Impossibile trovare SimpleXML!';
$_lang['test_simplexml_nf_msg'] = 'MODX non ha potuto trovare SimpleXML nel tuo ambiente PHP. Il Gestore dei Pacchetti e altre funzionalit&agrave; non funzioneranno senza che questo sia installato. Puoi continuare con l\'installazione, ma MODX raccomanda di abilitare SimpleXML per le funzioni e caratteristiche avanzate.';
$_lang['test_suhosin'] = 'Controllo di problemi suhosin:';
$_lang['test_suhosin_max_length'] = 'Suhosin GET max value troppo basso!';
$_lang['test_suhosin_max_length_err'] = 'Attualmente, stai usando l\'estensione di PHP suhosin, e il tuo parametro suhosin.get.max_value_length &egrave; impostato troppo basso perch&eacute; MODX comprima correttamente i files JS nel manager. MODX raccomanda di alzare questo valore a 4096; in caso contrario, MODX imposter&agrave; automaticamente la compressione JS (compress_js setting) a 0 per prevenire errori.';
$_lang['test_table_prefix'] = 'Controllo prefisso tabella `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = 'Il prefisso della tabella &egrave; gi&agrave; in uso in questo database!';
$_lang['test_table_prefix_inuse_desc'] = 'Il setup non ha potuto installare nel database selezionato, dal momento che contiente gi&agrave; tabelle con il prefisso specificato. Per favore scegli un nuovo table_prefix, ed esegui il Setup nuovamente.';
$_lang['test_table_prefix_nf'] = 'Il prefisso della tabella non esiste in questo database!';
$_lang['test_table_prefix_nf_desc'] = 'Il programma di setup non ha potuto installare nel database selezionato, dal momento che non contiene tabelle con il prefisso specificato da essere aggiornate. Per favore scegli un table_prefix esistente, ed esegui il Setup nuovamente.';
$_lang['test_zip_memory_limit'] = 'Controllo che il limite della memoria sia almeno 24M per le estensioni zip: ';
$_lang['test_zip_memory_limit_fail'] = 'MODX ha trovato la tua impostazione del your memory_limit sotto al valore raccomandato di 24M. MODX ha provato a impostare il memory_limit a 24M, ma senza successo. Per favore imposta questo valore del memory_limit nel tuo file php.ini file ad almeno 24M prima di procedere, in modo che le estensioni zip funzionino correttamente.';