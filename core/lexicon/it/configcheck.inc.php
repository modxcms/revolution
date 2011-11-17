<?php
/**
 * Config Check Italian lexicon topic
 *
 * @language it
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'Contatta un amministratore di sistema e avvisalo di questo messaggio!';
$_lang['configcheck_cache'] = 'Directory cache non scrivibile';
$_lang['configcheck_cache_msg'] = 'MODX non può scrivere nella directory cache. MODX funzionerà regolarmente, ma senza inserire in cache. Per risolvere il problema, assegna i permessi di scrittura alla directory /_cache/';
$_lang['configcheck_configinc'] = '<b>File Config ancora scrivibile!</b>';
$_lang['configcheck_configinc_msg'] = 'Il tuo sito è molto vulnerabile: gli hackers possono danneggiarlo pesantemente. Setta il file config in sola lettura! Se non sei l\'amministratore del sito, contatta un amministratore di sistema e avvisalo di questo messaggio! Il file in questione è il seguente: [[+path]]';
$_lang['configcheck_default_msg'] = 'C\'è un problema non meglio specificato. Molto strano.';
$_lang['configcheck_errorpage_unavailable'] = 'La <b>Pagina di errore</b> del tuo sito non è raggiungibile';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Questo significa che la pagina di errore non esiste o non è accessibile ai normali navigatori. Questo può mandare in loop una condizione ricorsiva e far segnare parecchi errori nei logs del sito. Assicurati che non siano assegnati gruppi di webuser alla pagina.';
$_lang['configcheck_errorpage_unpublished'] = '<b>La pagina di errore non è pubblicata o non esiste.</b>';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Questo significa che la pagina di errore non è accessibile al pubblico. Pubblica la pagina o assicurati che sia assegnata ad un documento esistente nel sito dal menu Sistema &gt; Configurazione Sistema.';
$_lang['configcheck_images'] = '<b>La directory delle immagini non è scrivibile.</b>';
$_lang['configcheck_images_msg'] = 'La directory delle immagini non è scrivibile o non esiste. Ciò significa che le funzioni del Gestore Immagini non saranno disponibili!';
$_lang['configcheck_installer'] = '<b>Files di installazione ancora presenti.</b>';
$_lang['configcheck_installer_msg'] = 'La cartella setup/ contiene i files di installazione di MODX. Prova ad immaginare cosa accadrebbe se un malintenzionato la trovasse ed eseguisse l\'installazione nuovamente! Probabilmente non andrebbe molto lontano perché dovrebbe inserire gli accessi del database...ma è comunque auspicabile rimuovere tale directory dal server. It is located at: [[+path]]';
$_lang['configcheck_lang_difference'] = '<b>Il numero di termini nel file della lingua non è corretto.</b>';
$_lang['configcheck_lang_difference_msg'] = 'La lingua attualmente selezionata ha un numero di termini diverso da quello della lingua di default. Non è necessariamente un problema, ma la cosa può significare che si debba aggiornare il file.';
$_lang['configcheck_notok'] = '<span style="color:#990000">ERRATI uno o più dettagli di configurazione: </span>';
$_lang['configcheck_ok'] = '<span style="color:#009900">Tutti i controlli OK - nessun avviso da riportare.</span>';
$_lang['configcheck_register_globals'] = '<b>register_globals è settato ad ON nel file di configurazione php.ini.</b>';
$_lang['configcheck_register_globals_msg'] = 'Questa configurazione rende il tuo sito più esposto ad attacchi XSS (Cross Site Scripting). Dovresti chiedere al tuo provider come puoi disabilitare (OFF) questa impostazione.';
$_lang['configcheck_title'] = 'Controllo configurazione';
$_lang['configcheck_unauthorizedpage_unavailable'] = '<b>La pagina  \'Non Autorizzato\' non è pubblicata o non esiste.</b>';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'Questo significa che la pagina  \'Non Autorizzato\' non è accessibile ai normali navigatori o non esiste. Questo può mandare in loop una condizione ricorsiva e far segnare parecchi errori nei logs del sito. Assicurati che non siano assegnati gruppi di webuser alla pagina.';
$_lang['configcheck_unauthorizedpage_unpublished'] = '<b>La pagina \'Non Autorizzato\' definita nella configurazione del sito non è pubblicata.</b>';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'Questo significa che la pagina \'Non Autorizzato\' non è accessibile al pubblico. Pubblica la pagina o assicurati che sia assegnata ad un documento esistente nel sito tramite il menu Sistema &gt; Configurazione Sistema.';
$_lang['configcheck_warning'] = 'Avviso di configurazione:';
$_lang['configcheck_what'] = 'Cosa significa?';