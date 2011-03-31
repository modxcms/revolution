<?php
/**
 * Config Check Italian lexicon topic
 *
 * @language it
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'Contatta un amministratore di sistema e avvisalo di questo messaggio';
$_lang['configcheck_cache'] = 'Directory cache non scrivibile';
$_lang['configcheck_cache_msg'] = 'MODx non puo\' scrivere nella directory cache. MODx funzionera\' regolarmente, ma senza inserire in cache. Per risolvere il problema, assegna i permessi di scrittura alla directory /_cache/';
$_lang['configcheck_configinc'] = '<b>File Config ancora scrivibile!</b>';
$_lang['configcheck_configinc_msg'] = 'Il tuo sito e\' molto vulnerabile: gli hackers possono danneggiarlo pesantemente. Setta il file config in sola lettura! Se non sei l\'amministratore del sito, contatta un amministratore di sistema e avvisalo di questo messaggio! Il file in questione e\' il seguente: core/config/config.inc.php';
$_lang['configcheck_default_msg'] = 'C\'e\' un problema non meglio specificato. Molto strano.';
$_lang['configcheck_errorpage_unavailable'] = '<b>Pagina di errore non raggiungibile.</b>';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Questo significa che la pagina di errore non esiste o non e\' accessibile ai normali navigatori. Questo puo\' mandare in loop una condizione ricorsiva e fa segnare parecchi errori nei log del sito. Assicurati che non siano assegnati gruppi di webuser alla pagina.';
$_lang['configcheck_errorpage_unpublished'] = '<b>La pagina di errore non e\' pubblicata o non esiste.</b>';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Questo significa che la pagina di errore non e\' accessibile al pubblico. Pubblica la pagina o assicurati che sia assegnata ad un documento esistente nel sito via menu System &gt; System Settings.';
$_lang['configcheck_images'] = '<b>La directory delle immagini non e\' scrivibile.</b>';
$_lang['configcheck_images_msg'] = 'La directory delle immagini non e\' scrivibile o non esiste. Cio\' significa che le funzioni dell\'Image Manager non saranno disponibili!';
$_lang['configcheck_installer'] = '<b>Files di installazzione ancora presenti.</b>';
$_lang['configcheck_installer_msg'] = 'La cartella setup/ contiene i files di installazione di Modx. Prova ad immaginare cosa accadrebbe se un malintenzionato la trovasse ed eseguisse l\'installazione nuovamente! Probabilmente non andrebbe molto lontano perche\' dovrebbe inserire gli accessi del database, ma e\' comunque auspicabile rimuovere tale directory dal server.';
$_lang['configcheck_lang_difference'] = '<b>Il numero di termini nel file di linguaggio non e\' corretto.</b>';
$_lang['configcheck_lang_difference_msg'] = 'Il linguaggio attualmente selezionato ha un numero di termini diverso da quello del linguaggio di default. Non e\' necessariamente un problema, ma la cosa puo\' significare che si debba aggiornare il file.';
$_lang['configcheck_notok'] = '<span style="color:#990000">ERRATI uno o piu\' dettagli di configurazione: </span>';
$_lang['configcheck_ok'] = '<span style="color:#009900">Tutti i controlli OK - nessun avviso da riportare.</span>';
$_lang['configcheck_register_globals'] = '<b>register_globals e\' settato ad ON nel file di configurazione php.ini.</b>';
$_lang['configcheck_register_globals_msg'] = 'Questa configurazione rende il tuo sito piu\' esposto ad attacchi XSS (Cross Site Scripting). Dovresti chiedere al tuo provider come puoi disabilitare (OFF) questo settaggio.';
$_lang['configcheck_title'] = 'Controllo configurazione';
$_lang['configcheck_unauthorizedpage_unavailable'] = '<b>La pagina  \'Non Autorizzato\' non e\' pubblicata o non esiste.</b>';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'Questo significa che la pagina  \'Non Autorizzato\' non e\' accessibile ai normali navigatori o non esiste. Questo puo\' mandare in loop una condizione ricorsiva e fa segnare parecchi errori nei log del sito. Assicurati che non siano assegnati gruppi di webuser alla pagina.';
$_lang['configcheck_unauthorizedpage_unpublished'] = '<b>La pagina \'Non Autorizzato\' definita nella configurazione del sito non e\' pubblicata.</b>';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'Questo significa che la pagina \'Non Autorizzato\' non e\' accessibile al pubblico. Pubblica la pagina o assicurati che sia assegnata ad un documento esistente nel sito tramite il menu Sistema -> Settaggi di Sistema.';
$_lang['configcheck_warning'] = 'Avviso di configurazione:';
$_lang['configcheck_what'] = 'Cosa significa?';