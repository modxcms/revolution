<?php
/**
 * Config Check English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'Contatta un amministratore di sistema e avvisalo di questo messaggio!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'allow_tags_in_post Impostazione del Contesto abilitata fuori da `mgr`';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'L\'impostazione del Contesto allow_tags_in_post &egrave; abilitata per la tua installazione al di fuori del Contesto mgr. MODX raccomanda che questa impostazione sia disabilitata a meno che tu non abbia bisogno di permettere esplicitamente agli utenti di inviare tags di MODX, entities numeriche, o tags di script HTML tramite il metodo POST a un form del tuo sito. Questo dovrebber generalmente essere disabilitato eccetto che nel contesto mgr.';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'allow_tags_in_post Impostazione di Sistema Abilitata';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'L\'impostazione di sistema allow_tags_in_post &egrave; abilitata per la tua installazione. MODX raccomanda che questa impostazione sia disabilitata a meno che tu non abbia bisogno di permettere esplicitamente agli utenti di inviare tags di MODX, entities numeriche, o tags di script HTML tramite il metodo POST a un form del tuo sito. Se proprio devi &egrave; meglio abilitare questa opzione tramite le impostazioni dei Contesti per Contesti specifici.';
$_lang['configcheck_cache'] = 'Directory cache non scrivibile';
$_lang['configcheck_cache_msg'] = 'MODX non può scrivere nella directory cache. MODX funzionerà regolarmente, ma senza inserire in cache. Per risolvere il problema, assegna i permessi di scrittura alla directory /_cache/';
$_lang['configcheck_configinc'] = '<b>File Config ancora scrivibile!</b>';
$_lang['configcheck_configinc_msg'] = 'Il tuo sito è molto vulnerabile: gli hackers possono danneggiarlo pesantemente. Setta il file config in sola lettura! Se non sei l\'amministratore del sito, contatta un amministratore di sistema e avvisalo di questo messaggio! Il file in questione è il seguente: [[+path]]';
$_lang['configcheck_default_msg'] = 'C\'è un problema non meglio specificato. Molto strano.';
$_lang['configcheck_errorpage_unavailable'] = 'La <b>Pagina di errore</b> del tuo sito non è raggiungibile';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Questo significa che la pagina di errore non esiste o non è accessibile ai normali navigatori. Questo può mandare in loop una condizione ricorsiva e far segnare parecchi errori nei logs del sito. Assicurati che non siano assegnati gruppi di webuser alla pagina.';
$_lang['configcheck_errorpage_unpublished'] = '<b>La pagina di errore non è pubblicata o non esiste.</b>';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Questo significa che la pagina di errore non è accessibile al pubblico. Pubblica la pagina o assicurati che sia assegnata ad un documento esistente nel sito dal menu Sistema &gt; Configurazione Sistema.';
$_lang['configcheck_htaccess'] = 'La cartella "core" è accessibile da web';
$_lang['configcheck_htaccess_msg'] = 'MODX ha rilevato che la cartella "core" è (parzialmente) accessibile al pubblico. <strong>Questo non è consigliato ed è un rischio per la sicurezza.</strong> Se l\'installazione di MODX sta girando su un webserver Apache sarebbe opportuno almeno impostare la copia del file. htaccess che si trova nella cartella "core" stessa <em>[[+ fileLocation]]</em>. Questo può essere fatto facilmente rinominando il file di esempio ht.access esistente in. htaccess. <p>Ci sono altri metodi e webserver che potresti usare, si prega di leggere la <a href="https://rtfm.modx.com/revolution/2.x/administering-your-site/security/hardening-modx-revolution"> Guida per rendere sicuro MODX</a> per ulteriori informazioni sulla protezione del sito.</p> Se avete impostato tutto correttamente, navigando ad esempio su <a href="[[+checkUrl]]" target="_blank"> Changelog</a> dovrebbe apparire un errore 403 (autorizzazione negata) o meglio un 404 (non trovato). Se si può vedere il changelog tramite browser, qualcosa deve essere ancora sistemato e devi riconfigurare le impostazioni o rivolgerti a un esperto per risolvere questo problema.';
$_lang['configcheck_images'] = '<b>La directory delle immagini non è scrivibile.</b>';
$_lang['configcheck_images_msg'] = 'La directory delle immagini non è scrivibile o non esiste. Ciò significa che le funzioni del Gestore Immagini non saranno disponibili!';
$_lang['configcheck_installer'] = '<b>Files di installazione ancora presenti.</b>';
$_lang['configcheck_installer_msg'] = 'La cartella setup/ contiene i files di installazione di MODX. Prova ad immaginare cosa accadrebbe se un malintenzionato la trovasse ed eseguisse l\'installazione nuovamente! Probabilmente non andrebbe molto lontano perché dovrebbe inserire gli accessi del database...ma è comunque auspicabile rimuovere tale directory dal server. It is located at: [[+path]]';
$_lang['configcheck_lang_difference'] = '<b>Il numero di termini nel file della lingua non è corretto.</b>';
$_lang['configcheck_lang_difference_msg'] = 'La lingua attualmente selezionata ha un numero di termini diverso da quello della lingua di default. Non è necessariamente un problema, ma la cosa può significare che si debba aggiornare il file.';
$_lang['configcheck_notok'] = '<span style="color:#990000">ERRATI uno o più dettagli di configurazione: </span>';
$_lang['configcheck_ok'] = '<span style="color:#009900">Tutti i controlli OK - nessun avviso da riportare.</span>';
$_lang['configcheck_phpversion'] = 'La versione di PHP è obsoleta';
$_lang['configcheck_phpversion_msg'] = 'La versione di PHP in uso [[+ phpversion]] non è più mantenuta dagli sviluppatori PHP, questo significa che non vengono rilasciati gli aggiornamenti di sicurezza. È anche probabile che MODX o un pacchetto aggiuntivo ora o nel prossimo futuro non supporterà più questa versione. Si consiglia di aggiornare PHP almeno alla versione [[+ phprequired]] appena possibile per proteggere il vostro sito.';
$_lang['configcheck_register_globals'] = '<b>register_globals è settato ad ON nel file di configurazione php.ini.</b>';
$_lang['configcheck_register_globals_msg'] = 'Questa configurazione rende il tuo sito più esposto ad attacchi XSS (Cross Site Scripting). Dovresti chiedere al tuo provider come puoi disabilitare (OFF) questa impostazione.';
$_lang['configcheck_title'] = 'Controllo configurazione';
$_lang['configcheck_unauthorizedpage_unavailable'] = '<b>La pagina  \'Non Autorizzato\' non è pubblicata o non esiste.</b>';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'Questo significa che la pagina  \'Non Autorizzato\' non è accessibile ai normali navigatori o non esiste. Questo può mandare in loop una condizione ricorsiva e far segnare parecchi errori nei logs del sito. Assicurati che non siano assegnati gruppi di webuser alla pagina.';
$_lang['configcheck_unauthorizedpage_unpublished'] = '<b>La pagina \'Non Autorizzato\' definita nella configurazione del sito non è pubblicata.</b>';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'Questo significa che la pagina \'Non Autorizzato\' non è accessibile al pubblico. Pubblica la pagina o assicurati che sia assegnata ad un documento esistente nel sito tramite il menu Sistema &gt; Configurazione Sistema.';
$_lang['configcheck_warning'] = 'Avviso di configurazione:';
$_lang['configcheck_what'] = 'Cosa significa?';
