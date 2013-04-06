<?php
/**
 * Setting Italian lexicon topic
 *
 * @language it
 * @package MODX
 * @subpackage lexicon
 */
$_lang['area'] = 'Area';
$_lang['area_authentication'] = 'Autenticazione e Sicurezza';
$_lang['area_caching'] = 'Caching';
$_lang['area_core'] = 'Codice Core';
$_lang['area_editor'] = 'Rich-Text Editor';
$_lang['area_file'] = 'File System';
$_lang['area_filter'] = 'Filtra per area...';
$_lang['area_furls'] = 'URL Semplici (Friendly URL)';
$_lang['area_gateway'] = 'Gateway';
$_lang['area_language'] = 'Lessico e Lingue';
$_lang['area_mail'] = 'Mail';
$_lang['area_manager'] = 'Back-end Manager';
$_lang['area_phpthumb'] = 'phpThumb';
$_lang['area_proxy'] = 'Proxy';
$_lang['area_session'] = 'Sessioni e Cookie';
$_lang['area_lexicon_string'] = 'Denominazione Area';
$_lang['area_lexicon_string_msg'] = 'Inserisci qui la Chiave del termine associato all\'area. Se non c\'è alcuna denominazione corrispondente, verrà mostrata soltanto la Chiave stessa (il nome univoco).<br />Aree Core:<ul><li>authentication</li><li>caching</li><li>file</li><li>furls</li><li>gateway</li><li>language</li><li>manager</li><li>session</li><li>site</li><li>system</li></ul>';
$_lang['area_site'] = 'Sito';
$_lang['area_system'] = 'Sistema e Server';
$_lang['areas'] = 'Aree';
$_lang['charset'] = 'Codifica (Charset)';
$_lang['country'] = 'Stato';
$_lang['description_desc'] = 'Breve descrizione della Impostazione. Puo\' essere un Termine Chiave.';
$_lang['key_desc'] = 'Chiave per la impostazione. potra\' essere richiamata con il placeholder [[++key]].';
$_lang['name_desc'] = 'Nome della Impostazione. Puo\' essere un Termine Chiave.';
$_lang['namespace'] = 'Namespace';
$_lang['namespace_desc'] = 'Namespace a cui questa Impostazione e\' associata. Il Linguaggio di default sara\' caricato per questo Namespace quando affera la Impostazione.';
$_lang['namespace_filter'] = 'Filtra per Namespace...';
$_lang['search_by_key'] = 'Cerca per Chiave...';
$_lang['setting_create'] = 'Crea Nuova Impostazione';
$_lang['setting_err'] = 'Controlla i dati per i seguenti campi: ';
$_lang['setting_err_ae'] = 'Una Impostazione con questa Chiave esiste di già. Specifica una chiave diversa.';
$_lang['setting_err_nf'] = 'Impostazione non trovata.';
$_lang['setting_err_ns'] = 'Impostazione non specificata';
$_lang['setting_err_remove'] = 'Si è verificato un errore durante il tentativo di rimuovere l\'Impostazione.';
$_lang['setting_err_save'] = 'Si è verificato un errore durante il tentativo di salvare l\'Impostazione.';
$_lang['setting_err_startint'] = 'Le Impostazioni non possono cominciare con un numero intero.';
$_lang['setting_err_invalid_document'] = 'Non esiste alcun documento con ID %d. Specificare un documento esistente.';
$_lang['setting_remove'] = 'Rimuovi Impostazione';
$_lang['setting_remove_confirm'] = 'Sei sicuro di voler rimuovere questa Impostazione? Questo potrebbe compromettere la tua installazione di MODX.';
$_lang['setting_update'] = 'Aggiorna Impostazione';
$_lang['settings_after_install'] = 'Dal momento che questa è una nuova installazione, sei pregato di controllare queste impostazioni, puoi cambiare qualsiasi cosa tu voglia. Dopo aver controllato le varie opzioni, clicca su \'Salva\' per aggiornare il database delle Impostazioni.<br /><br />';
$_lang['settings_desc'] = 'Qui puoi impostare le preferenze generali e le configurazioni per l\'interfaccia del manager di Gestione nonché per il funzionamento stesso del tuo sito.  Clicca due volte sulla colonna con il valore della impostazione che vorresti modificare dinamicamente dalla griglia, oppure clicca con il tasto destro su una voce per maggiori opzioni. Puoi anche cliccare sul simbolo "+" per una descrizione della impostazione.';
$_lang['settings_furls'] = 'URLs semplici (Friendly)';
$_lang['settings_misc'] = 'Varie';
$_lang['settings_site'] = 'Sito';
$_lang['settings_ui'] = 'Interfaccia &amp; Funzionalità';
$_lang['settings_users'] = 'Utente';
$_lang['system_settings'] = 'Impostazioni Sistema';
$_lang['usergroup'] = 'Gruppo Utenti';

// user settings
$_lang['setting_access_category_enabled'] = 'Controllare Accessi alle Categorie';
$_lang['setting_access_category_enabled_desc'] = 'Usa questa opzione per abilitare o disabilitare i controlli ACL della Categoria (Category ACL checks) (per Contesto). <strong>NOTA: Se questa opzione è disattivata, allora TUTTI i Permessi di Accesso alle Categorie saranno ignorati!</strong>';

$_lang['setting_access_context_enabled'] = 'Controllare Accessi ai Contesti';
$_lang['setting_access_context_enabled_desc'] = 'Usa questa opzione per abilitare o disabilitare i controlli ACL del Contesto. <strong>NOTA: Se questa opzione è disattivata, allora TUTTI i permessi di Accesso al Contesto saranno ignorati. NON disabilitarla per tutto il sistema o per il Contesto mgr, altrimenti disabiliterai l\'accesso al pannello di controllo di MODX.</strong>';

$_lang['setting_access_resource_group_enabled'] = 'Controllare Accessi ai Gruppi di Risorse';
$_lang['setting_access_resource_group_enabled_desc'] = 'Usa questa opzione per abilitare o disabilitare i controlli ACL dei Gruppi di Risorse (per Contesto). <strong>NOTA: Se questa opzione è disattivata allora TUTTI i permessi di Accesso ai Gruppi di Risorse saranno ignorati!</strong>';

$_lang['setting_allow_mgr_access'] = 'Accesso Pannello Controllo';
$_lang['setting_allow_mgr_access_desc'] = 'Seleziona questa opzione per abilitare o disabilitare l\'accesso al pannello di controllo (manager). <strong>NOTA: Se questa opzione è impostata su "NO" allora l\'utente sarà reindirizzato al Login del Manager o alla Pagina Iniziale del sito.</strong>';

$_lang['setting_failed_login'] = 'Tentativi Login Falliti';
$_lang['setting_failed_login_desc'] = 'Qui puoi inserire il numero di tentativi di login falliti che sono concessi a un utente prima di essere bloccato.';

$_lang['setting_login_allowed_days'] = 'Giorni Consentiti';
$_lang['setting_login_allowed_days_desc'] = 'Seleziona i giorni in cui questo utente è autorizzato a loggarsi.';

$_lang['setting_login_allowed_ip'] = 'Indirizzo IP autorizzato';
$_lang['setting_login_allowed_ip_desc'] = 'Inserisci gli indirizzi IP da cui questo utente è autorizzato ad entrare. <strong>NB: Separa differenti indirizzi IP con una virgola (,)</strong>';

$_lang['setting_login_homepage'] = 'Pagina Iniziale Login';
$_lang['setting_login_homepage_desc'] = 'Inserisci l\'ID del documento a cui reindirizzare l\'utente una volta loggato. <strong>NOTA: assicurati che l\'ID inserito appartenga a un documento esistente, pubblicato  e accessibile da questo utente!</strong>';

// system settings
$_lang['setting_access_policies_version'] = 'Versione Schema Politica di Accesso';
$_lang['setting_access_policies_version_desc'] = 'La versione del sistema della Politica di Accesso. <b>NON MODIFICARE<b>.';

$_lang['setting_allow_forward_across_contexts'] = 'Consenti Reindirizzamento (Forwarding) fra Contesti';
$_lang['setting_allow_forward_across_contexts_desc'] = 'Se abilitato, Symlinks e le chiamate API modX::sendForward() possono inoltrare richieste alle Risorse in altri Contesti.';

$_lang['setting_allow_manager_login_forgot_password'] = 'Permetti "Password Dimenticata" nella schermata di login al manager.';
$_lang['setting_allow_manager_login_forgot_password_desc'] = 'Se impostato su "No" si disabilita la possibilita\' di "Password dimenticata" nella schermata di login al manager.';

$_lang['setting_allow_tags_in_post'] = 'Consenti Tags HTML in POST';
$_lang['setting_allow_tags_in_post_desc'] = 'Se falso "NO", tutte le azioni di tipo POST dentro il manager elimineranno qualsiasi tags. MODX raccomanda di lasciare questa opzione impostata su vero "SI".';

$_lang['setting_archive_with'] = 'Forza Archivi PCLZip';
$_lang['setting_archive_with_desc'] = 'Se vero, sarà usata PCLZip invece di ZipArchive come estensione zip. Abilita questa opzione se riscontri errori di tipo extractTo o se hai problemi con la decompattazione nel Gestore dei Pacchetti.';

$_lang['setting_auto_menuindex'] = 'Indicizzazione Menu di default';
$_lang['setting_auto_menuindex_desc'] = 'Scegli \'Si\' per abilitare di default l\'incremento automatico degli indici del menu.';

$_lang['setting_auto_check_pkg_updates'] = 'Controlla automaticamente gli Aggiornamenti dei Pacchetti';
$_lang['setting_auto_check_pkg_updates_desc'] = 'Se impostato su \'SI\', MODX controllerà automaticamente se sono disponibili aggiornamenti per i pacchetti inseriti nel Gestore Pacchetti. Questo può rallentare il caricamento della griglia.';

$_lang['setting_auto_check_pkg_updates_cache_expire'] = 'Scadenza Cache per il controllo automatico degli Aggiornamenti dei Pacchetti';
$_lang['setting_auto_check_pkg_updates_cache_expire_desc'] = 'Il numero di minuti che il Gestore dei Pacchetti terrà in cache i risultati del controllo aggiornamenti.';

$_lang['setting_allow_multiple_emails'] = 'Consenti Emails duplicate per gli Utenti';
$_lang['setting_allow_multiple_emails_desc'] = 'Se impostato su "SI", gli Utenti possono condividere lo stesso indirizzo e-mail.';

$_lang['setting_automatic_alias'] = 'Genera automaticamente alias';
$_lang['setting_automatic_alias_desc'] = 'Seleziona \'SI\' per far generare automaticamente al sistema un alias basato sul titolo della Risorsa al momento del salvataggio.';

$_lang['setting_base_help_url'] = 'URL Base per Help';
$_lang['setting_base_help_url_desc'] = 'L\'URL di base da cui costruire i collegamenti della Guida in alto a destra nelle pagine del manager.';

$_lang['setting_blocked_minutes'] = 'Minuti Blocco';
$_lang['setting_blocked_minutes_desc'] = 'Qui puoi inserire il numero di minuti che un utente dovrebbe restare bloccato, dopo aver raggiunto il numero massimo di tentativi di login falliti. Inserisci solo numeri (senza virgole, spazi etc.)';

$_lang['setting_cache_action_map'] = 'Abilita la Cache della "Mappa Azioni"';
$_lang['setting_cache_action_map_desc'] = 'Se impostata su "SI", le azioni (o le mappe dei controlli) saranno inserite in cache per ridurre il tempo di caricamento delle pagine del manager.';

$_lang['setting_cache_alias_map'] = 'Abilita Context Alias Map Cache';
$_lang['setting_cache_alias_map_desc'] = 'Quando abilitato, tutti gli URIs delle Risorse sono inseriti nella cache del Contesto. Abilitalo su siti pi&ugrave; piccoli e disabilitalo su siti pi&ugrave; grandi per avere prestazioni migliori.';

$_lang['setting_cache_context_settings'] = 'Abilita Cache Impostazione dei Contesti';
$_lang['setting_cache_context_settings_desc'] = 'Se impostata su "SI", le impostazioni dei contesti saranno inserite in cache per ridurre il tempo di caricamento.';

$_lang['setting_cache_db'] = 'Abilita Cache Database';
$_lang['setting_cache_db_desc'] = 'Se impostata su "SI", gli oggetti e i set di risultati grezzi delle queries SQL saranno inseriti in cache riducendo significativamente il carico del database.';

$_lang['setting_cache_db_expires'] = 'Scadenza per la Cache del DB';
$_lang['setting_cache_db_expires_desc'] = 'Questo valore (in secondi) imposta la durata temporale dei files della cache per un set di risultati del DB.';

$_lang['setting_cache_db_session'] = 'Enable Database Session Cache';
$_lang['setting_cache_db_session_desc'] = 'When enabled, and cache_db is enabled, database sessions will be cached in the DB result-set cache.';

$_lang['setting_cache_db_session_lifetime'] = 'Expiration Time for DB Session Cache';
$_lang['setting_cache_db_session_lifetime_desc'] = 'This value (in seconds) sets the amount of time cache files last for session entries in the DB result-set cache.';

$_lang['setting_cache_default'] = 'Inserisci in cache(cacheable) di default';
$_lang['setting_cache_default_desc'] = 'Seleziona \'SI\' se vuoi che tutte le nuove Risorse siano inserite di default nella cache.';
$_lang['setting_cache_default_err'] = 'Per favore specifica se vuoi, SI o NO, che i documenti siano inseriti nella cache di default.';

$_lang['setting_cache_disabled'] = 'Disabilita Opzioni di Caching Globale';
$_lang['setting_cache_disabled_desc'] = 'Seleziona \'SI\' per disabilitare tutte le funzioni di caching di MODX. MODX non raccomanda di disabilitare il caching.';
$_lang['setting_cache_disabled_err'] = 'Per favore specifica se vuoi o meno la cache abilitata.';

$_lang['setting_cache_expires'] = 'Scadenza di Default della Cache';
$_lang['setting_cache_expires_desc'] = 'Questo valore (in secondi) imposta la durata temporale di default dei files di cache.';

$_lang['setting_cache_format'] = 'Formato di Caching da Usare';
$_lang['setting_cache_format_desc'] = '0 = PHP, 1 = JSON, 2 = serialize. Uno dei formati';

$_lang['setting_cache_handler'] = 'Classe Gestore Cache';
$_lang['setting_cache_handler_desc'] = 'Il nome della classe del tipo di gestore da usare per il caching.';

$_lang['setting_cache_lang_js'] = 'Cache delle Stringhe Lessicali JS';
$_lang['setting_cache_lang_js_desc'] = 'Se impotato su "SI", verranno usati gli headers del server per inserire in cache le stringhe lessicali caricate nei JavaScript per l\'interfaccia del pannello di controllo.';

$_lang['setting_cache_lexicon_topics'] = 'Cache Argomenti (Topic) Linguaggio';
$_lang['setting_cache_lexicon_topics_desc'] = 'Se abilitato, tutti gli Argomenti (Topic) Linguaggio saranno inseriti in cache in modo da ridurre sostanzialmente il tempo di caricamento delle funzionalità per l\'Internazionalizzazione. MODX raccomanda fortemente di lasciarlo impostato su "SI"';

$_lang['setting_cache_noncore_lexicon_topics'] = 'Abilita Cache Argomenti Linguaggio Non-Core';
$_lang['setting_cache_noncore_lexicon_topics_desc'] = 'Se impotato "NO", gli Archivi Linguaggio Non-Core NON saranno inseriti in cache. Utile durante lo sviluppo di Extras personali.';

$_lang['setting_cache_resource'] = 'Abilita Cache Parziale Risorse';
$_lang['setting_cache_resource_desc'] = 'Il comportamento della cache parziale delle risorse è configurabile dalla risorsa stessa quando questa opzione è abilitata ("SI"). Disabilitando ("NO") questa opzione sarà disabilitata a livello globale.';

$_lang['setting_cache_resource_expires'] = 'Scadenza Cache Parziale delle Risorse';
$_lang['setting_cache_resource_expires_desc'] = 'Questo valore (in secondi) imposta la durata dei files della cache per il caching parziale delle Risorse.';

$_lang['setting_cache_scripts'] = 'Abilita Cache Script';
$_lang['setting_cache_scripts_desc'] = 'Se impostato su "SI", verranno inseriti in cache tutti gli Scripts (Snippets e Plugins) per ridurre i tempi di caricamento. Si raccomanda di lasciare impostato  \'Si\'.';

$_lang['setting_cache_system_settings'] = 'Abilita Cache Impostazioni di Sistema';
$_lang['setting_cache_system_settings_desc'] = 'Se impostato su "SI", le impostazioni di sistema saranno inserite in cache per ridurre i tempi di caricamento. Si raccomanda di lasciare abilitato "SI" questo parametro.';

$_lang['setting_clear_cache_refresh_trees'] = 'Aggiorna Alberi dopo Pulizia Cache Sito';
$_lang['setting_clear_cache_refresh_trees_desc'] = 'Se impostato su "SI", si aggiorneranno gli alberi dopo aver pulito la cache del sito.';

$_lang['setting_compress_css'] = 'Usa Css Compressi';
$_lang['setting_compress_css_desc'] = 'Se impostato su "SI", verrà usata una versione compressa dei fogli di stile css nell\'interfaccia del manager. Questo riduce in modo significativo il tempo di caricamento ed esecuzione dentro il manager. Disabilita ("NO") questa funzione SOLO se stai modificando elementi del core.';

$_lang['setting_compress_js'] = 'Usa Librerie JavaScript Compresse';
$_lang['setting_compress_js_desc'] = 'Se impostato su "SI", verrà usata una versione compressa delle proprie librerie JavaScript. Questo riduce in modo significativo il tempo di caricamento ed esecuzione dentro al manager. Disabilita ("NO") questa funzione SOLO se stai modificando elementi del core.';

$_lang['setting_compress_js_groups'] = 'Usa il Raggruppamento (Grouping) durante la Compressione JavaScript';
$_lang['setting_compress_js_groups_desc'] = 'Raggruppa i JavaScript del core del manager di MODX usando l\'opzione groupsConfig di minify. Imposta su SI se stai usando suhosin o altri fattori limitanti.';

$_lang['setting_compress_js_max_files'] = 'Soglia di Massima Compressione dei JavaScript Files.';
$_lang['setting_compress_js_max_files_desc'] = 'The maximum number of JavaScript files MODX will attempt to compress at once when compress_js is on. Set to a lower number if you are experiencing issues with Google Minify in the manager.';

$_lang['setting_concat_js'] = 'Usa Librerie Javascript Concatenate';
$_lang['setting_concat_js_desc'] = 'Se impostato su "SI", verrà usata una versione concatenata delle librerie Javascript nell\'interfaccia del manager.  Questo riduce in modo significativo il tempo di caricamento e di esecuzione dentro al manager. Disabilita ("NO") questa funzione SOLO se stai modificando elementi del core.';

$_lang['setting_container_suffix'] = 'Suffisso Contenitore';
$_lang['setting_container_suffix_desc'] = 'Il suffisso da aggiungere alla fine nelle Risorse impostate come contenitori quando si sta usando i FURLs.';

$_lang['setting_context_tree_sort'] = 'Abilita Ordinamento dei contesti nell\' Albero Risorse';
$_lang['setting_context_tree_sort_desc'] = 'Se impostato su "SI", i Contesti saranno ordinato alfabeticamente nell\' Albero Risorse a sinistra.';
$_lang['setting_context_tree_sortby'] = 'Campo per ordinamento dei Contesti nell\' Albero Risorse ';
$_lang['setting_context_tree_sortby_desc'] = 'Il Campo da utilizzare per ordinare i Contesti nell\'Albero Risorse a sinistra, se l\' ordinamento e\' abilitato.';
$_lang['setting_context_tree_sortdir'] = 'Direzione dell\' ordinamento dei Contesti nell\' Albero Risorse a sinistra';
$_lang['setting_context_tree_sortdir_desc'] = 'La Direzione (Asc, Des,..) dell\' ordinamento dei Contesti nell\' Albero Risorse, se l\' ordinamento e\' abilitato.';

$_lang['setting_cultureKey'] = 'Lingua';
$_lang['setting_cultureKey_desc'] = 'Seleziona la lingua per tutti i Contesti "non-manager", compreso web.';

$_lang['setting_date_timezone'] = 'Fuso orario di Default';
$_lang['setting_date_timezone_desc'] = 'Se utilizzato, controlla il fuso orario predefinito per le funzioni PHP date. Se vuoto e il PHP date.timezone.ini non e\' stato settato nel tuo ambiente, sara\' assunto UTC.';

$_lang['setting_debug'] = 'Debug';
$_lang['setting_debug_desc'] = 'Controlla il debugging su on/off in MODX e/o imposta il livello di report degli errori PHP (error_reporting). Valori possibili: \'\' = usa corrente error_reporting, \'0\' = false (error_reporting = 0), \'1\' = true (error_reporting = -1), o qualsiasi valore di error_reporting  valido (come un intero).';

$_lang['setting_default_content_type'] = 'Tipo di Contenuto di Default';
$_lang['setting_default_content_type_desc'] = 'Il tipo di conenuto che si vuole utilizzare quando si creano nuove risorse. Potrai comunque cambiare tipo di conenuto durante la creazioen della risorsa, questa impostazione pre sceglie un tipo per te.';

$_lang['setting_default_duplicate_publish_option'] = 'Opzione per la Pubblicazione delle Risorse Duplicate';
$_lang['setting_default_duplicate_publish_option_desc'] = 'Il valore predefinito di pubblicazione delle Risorse Duplicate. Puo\' essere: "unpublish" per non-pubblicare tutti i duplicati, "publish" per pubblicare tutti i duplicati, oppure "preserve" per mantenere lo stato della pubblicazione usato nellarisorsa duplicata.';

$_lang['setting_default_media_source'] = 'Sorgente media di Dedfault';
$_lang['setting_default_media_source_desc'] = 'La Sorgente media di deafult da caricare.';

$_lang['setting_default_template'] = 'Template Default';
$_lang['setting_default_template_desc'] = 'Seleziona il Template che vorresti usare di default per le nuove Risorse. Puoi sempre selezionare un template diverso nell\'editor della Risorsa, questa impostazione semplicemente pre-seleziona uno dei tuoi Template.';

$_lang['setting_default_per_page'] = 'Default Per Pagina';
$_lang['setting_default_per_page_desc'] = 'Il numero di default dei risultati da mostrare nelle griglie del manager.';

$_lang['setting_editor_css_path'] = 'Percorso del file CSS';
$_lang['setting_editor_css_path_desc'] = 'Inserisci il percorso del tuo file CSS che vorresti usare con editor richtext. Il miglior modo di inserire il percorso è di inserirlo dalla root del tuo server, per esempio:  /assets/site/style.css. Se non vuoi caricare un foglio di stile dentro un editor richtext, lascia questo campo vuoto.';

$_lang['setting_editor_css_selectors'] = 'Selettori CSS per Editor';
$_lang['setting_editor_css_selectors_desc'] = 'Un elenco separato da virgola di selettori CSS per un editor richtext.';

$_lang['setting_emailsender'] = 'Indirizzo E-mail mittente per la Registrazione';
$_lang['setting_emailsender_desc'] = 'Qui puoi specificare l\'indirizzo e-mail da usare quando invii agli Utenti i loro usernames e passwords.';
$_lang['setting_emailsender_err'] = 'Si prega di specificare l\'indirizzo email dell\'amministratore.';

$_lang['setting_emailsubject'] = 'Oggetto della E-mail di Registrazione';
$_lang['setting_emailsubject_desc'] = 'La riga con l\'oggetto di default per la mail inviata quando un Utente si registra.';
$_lang['setting_emailsubject_err'] = 'Si prega di specificare l\'oggetto per la mail di registrazione.';

$_lang['setting_enable_dragdrop'] = 'Abilita Drag/Drop negli Alberi Risorse/Elementi';
$_lang['setting_enable_dragdrop_desc'] = 'Se impostato su \'No\', non sarà effettuabile il dragging e dropping negli Alberi delle Risorse e degli Elementi.';

$_lang['setting_error_page'] = 'Pagina di Errore';
$_lang['setting_error_page_desc'] = 'Inserisci l\'ID della risorsa a cui vuoi indirizzare gli utenti se richiedono un documento non esistente. <strong>NOTA BENE: assicurati che questo ID appartenga a un documento esistente, e che questo sia regolarmente pubblicato!</strong>';
$_lang['setting_error_page_err'] = 'Specifica ID Risorsa da utilizzare come pagina di errore.';

$_lang['setting_extension_packages'] = 'Pacchetti di Estensione';
$_lang['setting_extension_packages_desc'] = 'Un elenco JSON di pacchetti da caricare durante l\'istanziazione di MODX (on MODX instantiation). Nel formato [{"packagename":{path":"path/to/package"},{"anotherpkg":{"path":"path/to/otherpackage"}}]';

$_lang['setting_failed_login_attempts'] = 'Tentativi di Login Falliti';
$_lang['setting_failed_login_attempts_desc'] = 'Il numero di tentativi errati di login concessi a un Utente prima che sia \'bloccato\'.';

$_lang['setting_fe_editor_lang'] = 'Lingua Editor Front-end';
$_lang['setting_fe_editor_lang_desc'] = 'Scegli una lingua da usare nell\'editor quando usato come editor front-end.';

$_lang['setting_feed_modx_news'] = 'MODX News Feed URL';
$_lang['setting_feed_modx_news_desc'] = 'Imposta l\'URL dei feed RSS per il pannello MODX News nel manager.';

$_lang['setting_feed_modx_news_enabled'] = 'MODX News Feed Abilitati';
$_lang['setting_feed_modx_news_enabled_desc'] = 'Se impostato su \'NO\', verrà nascosto il feed delle News nella sezione di benvenuto del manager.';

$_lang['setting_feed_modx_security'] = 'URL Feed Avvisi di Sicurezza MODX';
$_lang['setting_feed_modx_security_desc'] = 'Imposta l\'URL per il feed RSS per il pannello con gli avvisi di Sicurezza nel manager.';

$_lang['setting_feed_modx_security_enabled'] = 'Feed Avvisi di sicurezza MODX Abilitato';
$_lang['setting_feed_modx_security_enabled_desc'] = 'Se impostato su \'NO\', verrà nascosto il feed sulla Sicurezza nella sezione di benvenuto del manager.';

$_lang['setting_filemanager_path'] = 'Percorso del File Manager';
$_lang['setting_filemanager_path_desc'] = 'IIS spesso non completa in modo corretto il valore document_root, che è usato per determinare cosa puoi vedere dal file manager. Se stai incontrando problemi usando il file manager, assicurati che questo percorso punti alla root della tua installazione di MODX.';

$_lang['setting_filemanager_path_relative'] = 'Il percorso del File Manager è Relativo?';
$_lang['setting_filemanager_path_relative_desc'] = 'Se il tuo valore del filemanager_path è relativo rispetto al base_path di MODX, allora imposta questo valore a \'SI\'. Se il tuo filemanager_path è esterno rispetto alla radice dei documenti (docroot), imposta questo su \'NO\'.';

$_lang['setting_filemanager_url'] = 'File Manager Url';
$_lang['setting_filemanager_url_desc'] = 'Opzionale. Specifica questo valore se vuoi impostare un esplicito URL per accedere ai files tramite il file manager di MODX (utile se hai cambiato il valore filemanager_path con un percorso esterno alla radice web (webroot) di MODX). Assicurati che questa sia URL, accessibile da web, del parametro del filemanager_path. Se lasci questo campo vuoto, MODX proverà a calcolarlo automaticamente.';

$_lang['setting_filemanager_url_relative'] = 'L\'URL del File Manager è Relativa?';
$_lang['setting_filemanager_url_relative_desc'] = 'Se il tuo valore del filemanager_url è relativo rispetto alla base_url di MODx, allora imposta questo valore a \'SI\'. Se il tuo filemanager_url è esterno rispetto alla radice web (webroot), imposta questo su \'NO\'.';

$_lang['setting_forgot_login_email'] = 'E-mail per Login Dimenticata';
$_lang['setting_forgot_login_email_desc'] = 'Il template della mail spedita quando un utente dimentica i propri username e/o password.';

$_lang['setting_form_customization_use_all_groups'] = 'Utilizzare Tutte Associazioni Gruppo Utenti per la personalizzazione Form';
$_lang['setting_form_customization_use_all_groups_desc'] = 'Se impostato SI, FC (Form Customization) usera\' *tutti* i settaggi per *tutti* i membri presenti in *tutti* i Gruppi Utenti quando viene applicato il settaggio di FC. Altrimenti , FC usera\' SOLO il settaggio appartenente al Gruppo Utenti Primario. ATTENZIONE: Impostando su SI potrebbero\' crearsi conflitti (BUG) con il settaggio FC.';

$_lang['setting_forward_merge_excludes'] = 'sendForward Campi Esclusi dalle unioni (merge)';
$_lang['setting_forward_merge_excludes_desc'] = 'Un SymLink unisce valori di campi non-vuoti sopra ai valori della Risorsa a cui punta. Questa lista di "esclusi", separata da virgole, evita che specifici campi vengano sovrascritti dal SymLink.';

$_lang['setting_friendly_alias_lowercase_only'] = 'Alias FURL solo minuscoli';
$_lang['setting_friendly_alias_lowercase_only_desc'] = 'Se impostato su "SI", consente SOLO caratteri minuscoli in un alias di Risorsa.';

$_lang['setting_friendly_alias_max_length'] = 'Lunghezza Massima Alias FURL';
$_lang['setting_friendly_alias_max_length_desc'] = 'Se maggiore di zero, indica il numero massimo di caratteri concessi in un alias di Risorsa. Zero "0" equivale a illimitati.';

$_lang['setting_friendly_alias_restrict_chars'] = 'Metodo Restrizione Caratteri Alias FURL';
$_lang['setting_friendly_alias_restrict_chars_desc'] = 'Il metodo (pattern,legal,alpha,alphanumeric) usato per limitare i caratteri usati in un Alias di una risorsa. "pattern" consente di fornire un capione-modello RegEx, "legal" consente qualsiasi carattere legale per gli URL, "alpha" consente soltanto lettere dell\'alfabeto, e "alphanumeric" consente soltanto lettere e numeri.';

$_lang['setting_friendly_alias_restrict_chars_pattern'] = '"Pattern" Restrizione Caratteri  Alias FURL';
$_lang['setting_friendly_alias_restrict_chars_pattern_desc'] = 'Un campione RegEx valido per limitare i caratteri usati negli Alias delle Risorse.';

$_lang['setting_friendly_alias_strip_element_tags'] = 'Rimuovere Elementi Tags Alias FURL';
$_lang['setting_friendly_alias_strip_element_tags_desc'] = 'Determina se i tags degli Elementi devono essere rimossi(tolti) da un alias di una Risorsa.';

$_lang['setting_friendly_alias_translit'] = 'Translitterazione Alias FURL';
$_lang['setting_friendly_alias_translit_desc'] = 'Il metodo di translitterazione da usare su un alias specificato per una Risorsa. Vuoto o "none" è il valore di defalut che salta la translitterazione. Altri valori possibili sono "iconv" (se disponibile) o una tabella di translitterazione fornita da una classe di servizio translitterazione personale.';

$_lang['setting_friendly_alias_translit_class'] = 'Classe Servizio Translitterazione Alias FURL';
$_lang['setting_friendly_alias_translit_class_desc'] = ' Opzionale. Una classe di servizio per fornire servizi di translitterazione per la generazione/filtraggio di Alias FURL.';

$_lang['setting_friendly_alias_translit_class_path'] = 'Percorso Classe Servizio Translitterazione Alias FURL';
$_lang['setting_friendly_alias_translit_class_path_desc'] = 'La posizione del pacchetto formato modello da cui sarà caricata la Classe del Servizio per la Translitterazione Alias FURL.';

$_lang['setting_friendly_alias_trim_chars'] = 'Tagliare (Trim) Caratteri Alias FURL';
$_lang['setting_friendly_alias_trim_chars_desc'] = 'Caratteri che verranno tagliati(tolti) dalla fine di un Alias di Risorsa fornito.';

$_lang['setting_friendly_alias_word_delimiter'] = 'Delimitatore Parola Alias FURL ';
$_lang['setting_friendly_alias_word_delimiter_desc'] = 'Il delimitatore di parola preferito per gli alias friendly URL finali (slugs). Es. se scegliete "-" un alias "alias di prova" verrà salvato come "alias-di-prova" ';

$_lang['setting_friendly_alias_word_delimiters'] = 'Delimitatori Parola Alias FURL';
$_lang['setting_friendly_alias_word_delimiters_desc'] = 'I caratteri che formano i delimitatori finali (slugs) di parola durante l\'elaborazione degli slugs di alias degli URL amichevoli. Questi caratteri saranno convertiti e consolidati al delimitatore di parola predefinito per gli alias FURL.';

$_lang['setting_friendly_urls'] = 'Utilizza URLs semplici (friendly URL)';
$_lang['setting_friendly_urls_desc'] = 'Questo consente ("SI") di usare gli URLs semplici per i motori di ricerca, cioè mostrare nell\'indirizzo il nome (alias) e non la stringa in php id=... <b>Nota</b>, questo funziona soltanto per le installazioni che girano su Apache, inoltre dovrai scrivere un file .htaccess per farli funzionare. Controlla il file .htaccess incluso nella installazione per maggiori informazioni.';
$_lang['setting_friendly_urls_err'] = 'Per favore specifica se vuoi usare o no gli URLs semplici (URL).';

$_lang['setting_friendly_urls_strict'] = 'Utilizzare Strict Friendly URLs';
$_lang['setting_friendly_urls_strict_desc'] = 'When friendly URLs are enabled, this option forces non-canonical requests that match a Resource to 301 redirect to the canonical URI for that Resource. WARNING: Do not enable if you use custom rewrite rules which do not match at least the beginning of the canonical URI. For example, a canonical URI of foo/ with custom rewrites for foo/bar.html would work, but attempts to rewrite bar/foo.html as foo/ would force a redirect to foo/ with this option enabled.';

$_lang['setting_global_duplicate_uri_check'] = 'Controlla duplicati URIs Attraverso tutti i Contesti';
$_lang['setting_global_duplicate_uri_check_desc'] = 'Seleziona \'Si\' per fare includere tutti i contesti nella ricerca di controllo di duplicati URI. Altrimenti soltanto il Contesto corrente delle Risorse in cui stai salvando sarà controllato.';

$_lang['setting_hidemenu_default'] = 'Nascondi di Default dai Menu';
$_lang['setting_hidemenu_default_desc'] = 'Seleziona \'SI\' per impostare di default che tutte le nuove risorse non siano mostrate nei menu del sito (e.g. utilizzando wayfinder).';

$_lang['setting_inline_help'] = 'Visualizza il Testo di Aiuto Inline per i vari Campi.';
$_lang['setting_inline_help_desc'] = 'Se \'SI\', i campi avranno il testo di aiuto direttamente sotto il campo stesso. Se\'NO\', tutti i campi avranno il testo aiuto nel tooltip-based.';

$_lang['setting_link_tag_scheme'] = 'Schema Generazione URL';
$_lang['setting_link_tag_scheme_desc'] = 'Lo schema di generazione URL per i tag [[~id]]. Opzioni disponibili: <a href="http://api.modxcms.com/modx/modX.html#makeUrl">http://api.modxcms.com/modx/modX.html#makeUrl</a>';

$_lang['setting_locale'] = 'Localizzazione';
$_lang['setting_locale_desc'] = 'Scegli la localizzazione per il sistema (set locale: it_IT.UTF-8). Lascia in bianco per le impostazioni di default. Guarda la <a href="http://php.net/setlocale" target="_blank">documentazione PHP</a> per maggiori informazioni.';

$_lang['setting_lock_ttl'] = 'Tempo di Vita Blocco';
$_lang['setting_lock_ttl_desc'] = 'Numero di secondi che un blocco su una risorsa rimarra\' attivo quano l\' utente è inattivo.';

$_lang['setting_log_level'] = 'Livello Resoconti(log)';
$_lang['setting_log_level_desc'] = 'Livello dei Resoconti(log) Predefinito; minor livello, minori messaggi registrati. Le opzioni disponibili sono: 0 (FATAL), 1 (ERROR), 2 (WARN), 3 (INFO), e 4 (DEBUG).';

$_lang['setting_log_target'] = 'Target Resoconti (log)';
$_lang['setting_log_target_desc'] = 'Il Target dei Resoconti dove i messaggi di log sono scritti. Le opzioni disponibili sono: \'FILE\', \'HTML\', o \'ECHO\'. Se non specificato il valore di default e\'  \'FILE\' .';

$_lang['setting_mail_charset'] = 'Charset Mail';
$_lang['setting_mail_charset_desc'] = 'Il charset di default da utilizzare per le e-mails, e.g. \'iso-8859-1\' or \'utf-8\'';

$_lang['setting_mail_encoding'] = 'Codifica Mail';
$_lang['setting_mail_encoding_desc'] = 'Imposta la Codifica dei messaggi. Le Opzioni possibili sono "8bit", "7bit", "binary", "base64", e "quoted-printable".';

$_lang['setting_mail_use_smtp'] = 'Usa SMTP';
$_lang['setting_mail_use_smtp_desc'] = 'Se \'SI\', si proverà ad usare protocollo SMTP nelle funzioni delle mail.';

$_lang['setting_mail_smtp_auth'] = 'Autenticazione SMTP';
$_lang['setting_mail_smtp_auth_desc'] = 'Imposta l\'autenticazione SMTP. Se la attivi "SI" devi completare le impostazioni mail_smtp_user e mail_smtp_pass.';

$_lang['setting_mail_smtp_helo'] = 'Messaggio Helo SMTP';
$_lang['setting_mail_smtp_helo_desc'] = 'Imposta l\'SMTP HELO del messaggio (Predefinito è hostname).';

$_lang['setting_mail_smtp_hosts'] = 'Hosts SMTP';
$_lang['setting_mail_smtp_hosts_desc'] = 'Imposta gli hosts SMTP.  Tutti gli hosts devono essere separati da un punto e virgola. Puoi anche specificare una porta differente per ogni host con questo formato: [hostname:port] (e.g. "smtp1.example.com:25;smtp2.example.com"). Gli Hosts saranno provati in ordine.';

$_lang['setting_mail_smtp_keepalive'] = 'Mantieni Viva SMTP';
$_lang['setting_mail_smtp_keepalive_desc'] = 'Evita che la connessione SMTP si chiuda dopo l\'invio di ogni mail. Non raccomandato, meglio lasciare "NO".';

$_lang['setting_mail_smtp_pass'] = 'Password SMTP';
$_lang['setting_mail_smtp_pass_desc'] = 'La password per autenticatsi con SMTP.';

$_lang['setting_mail_smtp_port'] = 'Porta SMTP';
$_lang['setting_mail_smtp_port_desc'] = 'Imposta la porta di default del server SMTP.';

$_lang['setting_mail_smtp_prefix'] = 'Prefisso Connessione SMTP';
$_lang['setting_mail_smtp_prefix_desc'] = 'Imposta il prefisso della connessione. Le Opzioni sono "", "ssl" or "tls"';

$_lang['setting_mail_smtp_single_to'] = 'SMTP Invio Singolo A:';
$_lang['setting_mail_smtp_single_to_desc'] = 'Abilita l\'invio selettivo ai singoli destinatari "A:" delle mails, invece di inviare all\'intero elenco A: dei destinatari.';

$_lang['setting_mail_smtp_timeout'] = 'Timeout SMTP';
$_lang['setting_mail_smtp_timeout_desc'] = 'Imposta il timeout del server SMTP in secondi. NOTA: Non funziona nei servers win32.';

$_lang['setting_mail_smtp_user'] = 'Utente SMTP';
$_lang['setting_mail_smtp_user_desc'] = 'Il Nome Utente per autenticarsi con SMTP.';

$_lang['setting_manager_direction'] = 'Direzione Testo Manager';
$_lang['setting_manager_direction_desc'] = 'Scegli la direzione con cui sarà mostrato il testo, da sinistra a destra "ltr" o da destra a sinistra "rtl".';

$_lang['setting_manager_date_format'] = 'Formato Data Manager';
$_lang['setting_manager_date_format_desc'] = 'La stringa, nel formato PHP date(), per le date visualizzate nel manager. (e.g. d M Y)';

$_lang['setting_manager_favicon_url'] = 'Favicon URL Manager';
$_lang['setting_manager_favicon_url_desc'] = 'Se impostata, caricherà questa URL come favicon per il manager di MODX. Deve essere una URL relativa alla directory manager/ , o un URL assoluto.';

$_lang['setting_manager_html5_cache'] = 'Usa la Cache locale di  HTML5 nel Manager';
$_lang['setting_manager_html5_cache_desc'] = 'SPERIMENTALE. Usa il caching locale di HTML5 per il Manager. Uso raccomandato solo se si usa il manager con Broswer "recenti".';

$_lang['setting_manager_js_cache_file_locking'] = 'Abilita il File Locking per la Cache JS/CSS del Manager';
$_lang['setting_manager_js_cache_file_locking_desc'] = 'Blocco File Cache. Setta a  NO se il filesytem e\' NFS.';
$_lang['setting_manager_js_cache_max_age'] = 'Durata della Compressione della Cache JS/CSS del Manager';
$_lang['setting_manager_js_cache_max_age_desc'] = 'Durata Massima espressa in secondi della cache della compressione CSS/JS del manager per il browser. Dopo questo periodo, il browser mandera\' un altro GET condizionale. Usa una "durata" piu\' alta per un traffico minore.';
$_lang['setting_manager_js_document_root'] = 'Radice Documenti per Compressione JS/CSS del Manager';
$_lang['setting_manager_js_document_root_desc'] = 'NON MODIFICARE se non sai cosa stai facendo! Se il tuo server non gestisce la variabile DOCUMENT_ROOT, impostala esplicitamente qui per abilitare la compressione CSS/JS del manager.';
$_lang['setting_manager_js_zlib_output_compression'] = 'Abilita Output Compressione zlib per JS/CSS Manager';
$_lang['setting_manager_js_zlib_output_compression_desc'] = 'SI RACCOMANDA di lasciare off (NO).Stabilisce se abilitare o meno l\'output compresso zlib per i file compressi CSS/JS del manager. Non abilitarlo a meno che tu non sia sicuro che la variabile di PGP zlib.output_compression possa essere impostata su 1.';

$_lang['setting_manager_lang_attribute'] = 'Gestione Attributi Lingue per HTML e XML';
$_lang['setting_manager_lang_attribute_desc'] = 'Inserisci il codice della lingua che soddisfa meglio la tua scelta della lingua del manager, questo assicura che il browser mostri il contenuto nel miglior formato per la tua lingua. Verrà inserito per xml:lamg=".." nell\'headers. ';

$_lang['setting_manager_language'] = 'Gestione Lingua';
$_lang['setting_manager_language_desc'] = 'Seleziona la lingua per il Content Manager.';

$_lang['setting_manager_login_url_alternate'] = 'URL di accesso Alternativo al Manager';
$_lang['setting_manager_login_url_alternate_desc'] = 'Un URL alternativo per inviare un utente non autenticato a quando hanno bisogno di accedere al manager. Il modulo di login ci deve login dell\'utente al contesto "mgr" al lavoro';

$_lang['setting_manager_login_start'] = 'Pagina Iniziale Manager';
$_lang['setting_manager_login_start_desc'] = 'Inserisci l\'ID del documento che vuoi mostrare all\'utente dopo che questo si è loggato dentro il manager. <strong>NOTA: assicurati che l\'ID che inserisci appartenga a un documento esistente, pubblicato e accessibile dall\'utente!</strong>';

$_lang['setting_manager_theme'] = 'Tema  Manager';
$_lang['setting_manager_theme_desc'] = 'Seleziona il Tema da utilizzare per il Manager.';

$_lang['setting_manager_time_format'] = 'Formato Ora Manager';
$_lang['setting_manager_time_format_desc'] = 'La stringa, nel formato PHP date(), per la visualizzazione degli orari nel manager. (e.g. H:i)';

$_lang['setting_manager_use_tabs'] = 'Utilizza Schede nel Layout del Manager';
$_lang['setting_manager_use_tabs_desc'] = 'Se \'SI\', il manager utilizzerà le schede(tabs) per disporre i contenuti dei pannelli. Altrimenti verranno utilizzati dei portali (portals).';

$_lang['setting_manager_week_start'] = 'Inizio Settimana';
$_lang['setting_manager_week_start_desc'] = 'Scegliere il giorno di inizio della Settimana. Usare 0 (o lasciare vuoto) per la domenica, 1 per lunedi\' e cosi via...';

$_lang['setting_modRequest.class'] = 'Request Handler Class';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_browser_default_sort'] = 'Ordinamento Default File Browser';
$_lang['setting_modx_browser_default_sort_desc'] = 'Metodo di ordinamento di defult quando si usa il popup File Browser nel manager. Possibili valori: name, size, lastmod (last modified).';

$_lang['setting_modx_charset'] = 'Codifica Caratteri';
$_lang['setting_modx_charset_desc'] = 'Seleziona quale codifica dei caratteri vorresti usare. Tieni presente che MODX è stato testato con diverse di queste codifiche, ma non con tutte. Per la maggior parte delle lingue, l\'impostazione di default UTF-8 è da preferirsi.';

$_lang['setting_new_file_permissions'] = 'Permessi Nuovi File';
$_lang['setting_new_file_permissions_desc'] = 'Quando carichi un nuovo file nel File Manager, il Manager proverà a modificare i permesse con quelli inseriti qui. Questo potrebbe non funzionare su alcune installazioni, come con IIS, in questi casi dovrai cambiare manualmente i permessi.';

$_lang['setting_new_folder_permissions'] = 'Permessi Nuova Cartella';
$_lang['setting_new_folder_permissions_desc'] = 'Quando crei una nuova cartella nel File Manager, il manager proverà a cambiare i permessi della cartella con quelli inseriti qui. Questo potrebbe non funzionare su alcune installazioni, come con IIS, in questi casi dovrai cambiare manualmente i permessi.';

$_lang['setting_password_generated_length'] = 'Lunghezza Password Auto-Generata';
$_lang['setting_password_generated_length_desc'] = 'La lunghezza della password auto-generata dal gestore per un utente.';

$_lang['setting_password_min_length'] = 'Lunghezza minima password';
$_lang['setting_password_min_length_desc'] = 'La lunghezza minima per la password che deve utilizzare un utente.';

$_lang['setting_principal_targets'] = 'Targets ACL da caricare';
$_lang['setting_principal_targets_desc'] = 'Personalizza i targets ACL da caricare per gli utenti di MODX.';

$_lang['setting_proxy_auth_type'] = 'Tipo Autenticazione Proxy';
$_lang['setting_proxy_auth_type_desc'] = 'Supporta o BASIC o NTLM.';

$_lang['setting_proxy_host'] = 'Host Proxy';
$_lang['setting_proxy_host_desc'] = 'Se il tuo server sta usando un proxy, imposta qui l\'hostname  per abilitare le funzionalità che potrebbero utilizzarlo, come il Gestore Pacchetti.';

$_lang['setting_proxy_password'] = 'Password Proxy ';
$_lang['setting_proxy_password_desc'] = 'La password richiesta per autenticarsi con il server proxy che, eventualmente, hai inserito.';

$_lang['setting_proxy_port'] = 'Porta Proxy';
$_lang['setting_proxy_port_desc'] = 'La porta per il server proxy che, eventualmente, hai inserito.';

$_lang['setting_proxy_username'] = 'Username Proxy ';
$_lang['setting_proxy_username_desc'] = 'Username per autenticarsi con il server proxy che, eventualmente, hai inserito.';

$_lang['setting_phpthumb_allow_src_above_docroot'] = 'Consenti Percorso Esterno alla Root Documenti phpThumb';
$_lang['setting_phpthumb_allow_src_above_docroot_desc'] = 'Se "SI", il percorso origine "src" può essere esterno alla radice dei documenti. Questo è utile per gli sviluppi di multi-contesti con multipli hosts virtuali.';

$_lang['setting_phpthumb_cache_maxage'] = 'Durata Massima Cache phpThumb';
$_lang['setting_phpthumb_cache_maxage_desc'] = 'Cancella le miniature (thumbnails) inserite in cache che non sono state visualizzate per più di X giorni.';

$_lang['setting_phpthumb_cache_maxsize'] = 'Dimensione Massima Cache phpThumb';
$_lang['setting_phpthumb_cache_maxsize_desc'] = 'Elimina le miniature con gli accessi meno recenti qualora la cache diventi più grande di X megabytes.';

$_lang['setting_phpthumb_cache_maxfiles'] = 'Numero Massimo Files Cache phpThumb';
$_lang['setting_phpthumb_cache_maxfiles_desc'] = 'Elimina le miniature con gli accessi meno recenti quando in cache sono inseriti più di X files.';

$_lang['setting_phpthumb_cache_source_enabled'] = 'Cache Files Origine phpThumb';
$_lang['setting_phpthumb_cache_source_enabled_desc'] = 'Se inserire o meno in cache i files sorgente appena sono caricati. Si raccomanda di lasciare impostato su \'NO\'.';

$_lang['setting_phpthumb_document_root'] = 'Radice Documenti PHPThumb';
$_lang['setting_phpthumb_document_root_desc'] = 'Imposta questo parametro se stai incontrando problemi con la variabile del server DOCUMENT_ROOT, o se riscontri errori con OutputThumbnail o !is_resource. Impostalo al percorso assoluto della radice dei documenti che vorresti usare. Se vuoto, MODX userà la variabile DOCUMENT_ROOT del server.';

$_lang['setting_phpthumb_error_bgcolor'] = 'Colore Sfondo Errori phpThumb';
$_lang['setting_phpthumb_error_bgcolor_desc'] = 'Valore in esadecimanle, senza il #, che indica un colore di sfondo (background) per eventuali messaggi di errore in phpThumb.';

$_lang['setting_phpthumb_error_fontsize'] = 'Dimensione Carattere Errori phpThumb';
$_lang['setting_phpthumb_error_fontsize_desc'] = 'Un valore in "em" per impostare la dimensione dei caratteri del testo che appare per eventuali messaggi di errore in phpThumb.';

$_lang['setting_phpthumb_error_textcolor'] = 'Colore Caratteri Errori phpThumb';
$_lang['setting_phpthumb_error_textcolor_desc'] = 'Valore in esadecimale, senza il #, che indica il colore dei caratteri del testo che appare per eventuali messaggi di errore in phpThumb.';

$_lang['setting_phpthumb_far'] = 'Forza Proporzioni phpThumb';
$_lang['setting_phpthumb_far_desc'] = 'L\'impostazione predefinita che userà phpThumb dentro il gestore. Impostare "C" per forzare la vista tagliata (crop) verso il Centro. Altri valori: "T", "B", "L", "R", "TL", "TR", "BL", "BR" composizione fatta con Top(alto)/Left(sin)/Bottom(basso)/Right(des) per eseguire tagli di conseguenza(Zoom-crop)<b> Nota Bene:</b> richiede ImageMagick per impostazioni diverse da "C".';

$_lang['setting_phpthumb_imagemagick_path'] = 'Percorso ImageMagick phpThumb';
$_lang['setting_phpthumb_imagemagick_path_desc'] = 'Opzionale. Imposta un percorso alternativo per ImageMagick per generare miniature con phpThumb, se non è predefinito in PHP.';

$_lang['setting_phpthumb_nohotlink_enabled'] = 'Hotlinking phpThumb Disabilitato';
$_lang['setting_phpthumb_nohotlink_enabled_desc'] = 'I Servers remoti sono autorizzati nel parametro src a meno che non si disattivi hotlinking in phpThumb. "SI" disattiva, quindi no server remoti, "NO" attiva, quindi si permetterebbero server remoti per parametro src.';

$_lang['setting_phpthumb_nohotlink_erase_image'] = 'Cancellare Immagini Hotlinking phpThumb';
$_lang['setting_phpthumb_nohotlink_erase_image_desc'] = 'Specifica se un\'immagine generata da un server remoto dovrebbe essere cancellata quando non autorizzata dalla impostazione sopra "Hotlinking Disabilitato".';

$_lang['setting_phpthumb_nohotlink_text_message'] = 'Messaggio di Hotlinking phpThumb Non Permesso';
$_lang['setting_phpthumb_nohotlink_text_message_desc'] = 'Il messaggio mostrato al posto della miniatura  quando un tentativo di hotlinking è rifiutato.';

$_lang['setting_phpthumb_nohotlink_valid_domains'] = 'Domini Validi Hotlinking phpThumb';
$_lang['setting_phpthumb_nohotlink_valid_domains_desc'] = 'Un elenco separato da virgola degli hosts a cui è permesso intervenire nel parametro URLs src.';

$_lang['setting_phpthumb_nooffsitelink_enabled'] = 'Linking phpThumb FuoriSito (Offsite) Disabilitato';
$_lang['setting_phpthumb_nooffsitelink_enabled_desc'] = 'Se "SI" si Disattiva la possibilità per esterni di utilizzare phpThumb per il rendering di immagini sui loro siti.';

$_lang['setting_phpthumb_nooffsitelink_erase_image'] = 'Elimina Immagini con Collegamento FuoriSito (Offsite) phpThumb';
$_lang['setting_phpthumb_nooffsitelink_erase_image_desc'] = 'Se "SI"  un\'immagine linkata da un server remoto viene cancellata quando non autorizzata dal parametro sopra Linking FuoriSito.';

$_lang['setting_phpthumb_nooffsitelink_require_refer'] = 'Richiesto Riferimento Collegamento FuoriSito phpThumb';
$_lang['setting_phpthumb_nooffsitelink_require_refer_desc'] = 'Se abilitato "SI", qualsiasi tentativo di collegamento esterno sarà rifiutato senza una valida intestazione di riferimento (header referrer).';

$_lang['setting_phpthumb_nooffsitelink_text_message'] = 'Messaggio per Collegamento phpThumb FuoriSito non Autorizzato';
$_lang['setting_phpthumb_nooffsitelink_text_message_desc'] = 'Il messaggio mostrato al posto della miniatura quando un tentativo di collegamento esterno viene rifiutato.';

$_lang['setting_phpthumb_nooffsitelink_valid_domains'] = 'Domini Validi Collegamento phpThumb FuoriSito (Offsite)';
$_lang['setting_phpthumb_nooffsitelink_valid_domains_desc'] = 'Un elenco separato da virgola degli hosts validati a cui è permesso il collegamento esterno.';

$_lang['setting_phpthumb_nooffsitelink_watermark_src'] = 'Origine Watermark Collegamento FuoriSito phpThumb';
$_lang['setting_phpthumb_nooffsitelink_watermark_src_desc'] = 'Opzionale. Un percorso di file system valido per un file da usare come sorgente watermark quando le tue immagini sono renderizzate esternamente (offsite) da phpThumb.';

$_lang['setting_phpthumb_zoomcrop'] = 'Zoom-Crop phpThumb';
$_lang['setting_phpthumb_zoomcrop_desc'] = 'L\'impostazione predefinita di zc (zoom-crop) per phpThumb quando usato nel gestionale. Impostare a 0 per non eseguire il Zoom-crop, impostare ad 1 per abilitarlo.';

$_lang['setting_publish_default'] = 'Pubblicato di Default';
$_lang['setting_publish_default_desc'] = 'Scegli \'SI\' per impostare che tutte le nuove risorse siano "pubblicate di default".';
$_lang['setting_publish_default_err'] = 'Per favore scegli se vuoi o no che i nuovi documenti siano pubblicati di default.';

$_lang['setting_rb_base_dir'] = 'Percorso Risorse';
$_lang['setting_rb_base_dir_desc'] = 'Inserisci il percorso fisico alla directory delle Risorse. Questa impostazione di solito è generata automaticamente. Se stai usando IIS, tuttavia, MODX potrebbe non riuscire ad impostare il percorso corretto, costringendo il browser delle Risorse a mostrare un errore. In tal caso, qui puoi correggere il percorso alla cartella delle immagini (il percorso che vedete in Windows Explorer). <strong>NOTA:</strong> La directory delle Risorse deve contenere le sottocartelle images, files, flash e media affinché funzioni correttamente con il Browser di Risorse.';
$_lang['setting_rb_base_dir_err'] = 'Per favore inserisci la directory base per il browser delle risorse.';
$_lang['setting_rb_base_dir_err_invalid'] = 'Questa directory o non esiste o non è accessibile. Per favore inserisci una directory valida o controlla i permessi di questa cartella.';

$_lang['setting_rb_base_url'] = 'URL Risorse';
$_lang['setting_rb_base_url_desc'] = 'Inserisci il percorso virtuale alla directory delle risorse. Questa impostazione di solito è generata automaticamente. Se stai usando IIS, tuttavia, MODX potrebbe non riuscire a determinare l\'URL da solo, costringendo il browser delle Risorse a mostrare un errore. In quel caso, qui puoi inserire l\'URL della cartella delle immagini (il percorso come lo scriveresti in Internet Explorer).';
$_lang['setting_rb_base_url_err'] = 'Per favore specifica l\'URL base del browser delle risorse.';

$_lang['setting_request_controller'] = 'Nome File Controllore Richieste';
$_lang['setting_request_controller_desc'] = 'Il nome del file del controllore delle richieste con cui è caricato MODX. La maggior parte degli utenti possono lasciare index.php.';

$_lang['setting_request_method_strict'] = 'Metodo Richieste Strict';
$_lang['setting_request_method_strict_desc'] = 'Se abilitato, le richieste tramite parametro ID saranno ignorate quando sono abilitati i FURLs, e le richieste tramite parametro ALIAS sranno ignorate senza i FURLs abilitati.';

$_lang['setting_request_param_alias'] = 'Parametro Alias Richieste';
$_lang['setting_request_param_alias_desc'] = 'Il nome del parametro GET per identificare gli aliases delle Risorse durante il reindirizzamento con FURLs.';

$_lang['setting_request_param_id'] = 'Parametro Richiesta ID';
$_lang['setting_request_param_id_desc'] = 'Il nome del parametro GET per identificare gli IDs delle Risorse quando non vengono usati FURLs.';

$_lang['setting_resolve_hostnames'] = 'Identifica Nomi Hosts';
$_lang['setting_resolve_hostnames_desc'] = 'Vuoi che MODX provi ad identificare i nome degli hosts dei tuoi visitatori quando visitano il tuo sito? Tale azione può causare un po\' di lavoro extra per il caricamento del server, anche se i tuoi visitatori non si accorgeranno in alcun modo di questo.';

$_lang['setting_resource_tree_node_name'] = 'Campo Risorsa Nodi Albero';
$_lang['setting_resource_tree_node_name_desc'] = 'Specifica quale campo della Risorsa visualizzare come nome per i nodi dell\'Albero delle Risorse. Di default è il titolo della pagina (pagetitle), ma può essere usato qualsiasi campo, come il titolo del menu, alias, titolo esteso, ecc.';

$_lang['setting_resource_tree_node_tooltip'] = 'Campo Tooltip Albero Risorse';
$_lang['setting_resource_tree_node_tooltip_desc'] = 'Specifica il campo della Risorsa da usare durante il rendering dei nodi nell\'albero delle Risorse. Qualsiasi campo della risorsa puo\' essere usato, come il titolo del menu, alias, titolo esteso. ecc. Se lasciato bianco, sara\' usato il titolo esteso con una descrizione sotto.';

$_lang['setting_richtext_default'] = 'Richtext Default';
$_lang['setting_richtext_default_desc'] = 'Seleziona \'SI\' per impostare di default l\'utilizzo del Richtext Editor per tutte le nuove risorse.';

$_lang['setting_search_default'] = 'Ricercabile di Default';
$_lang['setting_search_default_desc'] = 'Seleziona \'SI\' per rendere tutte le nuove risorse ricercabili di default.';
$_lang['setting_search_default_err'] = 'Per favore specifica se vuoi o no che i documenti siano ricercabili di default.';

$_lang['setting_server_offset_time'] = 'Offset Orario Server';
$_lang['setting_server_offset_time_desc'] = 'Seleziona il numero di ore di differenza (+/-) fra l\'orario di dove ti trovi e quello dove si trova il server.';

$_lang['setting_server_protocol'] = 'Tipo Server';
$_lang['setting_server_protocol_desc'] = 'Se il tuo sito usa una connessione https, specificalo qui.';
$_lang['setting_server_protocol_err'] = 'Per favore specifica se il tuo sito usa una connessione sicura (https) o no (http).';
$_lang['setting_server_protocol_http'] = 'http';
$_lang['setting_server_protocol_https'] = 'https';

$_lang['setting_session_cookie_domain'] = 'Dominio Cookie Sessione';
$_lang['setting_session_cookie_domain_desc'] = 'Usa questa impostazione per personalizzare la voce: dominio dei cookie della sessione.';

$_lang['setting_session_cookie_lifetime'] = 'Durata Cookie Sessione';
$_lang['setting_session_cookie_lifetime_desc'] = 'Usa questa impostazione per personalizzare la voce: durata in secondi dei cookie della sessione.  Così si imposta la durata del cookie della sessione di un cliente che abbia scelto l\'opzione \'ricordami\' al momento del login.';

$_lang['setting_session_cookie_path'] = 'Percorso Cookie Sessione';
$_lang['setting_session_cookie_path_desc'] = 'Con questa impostazione puoi personalizzare il percorso del cookie per identificare specifiche sessioni del sito. Lascia vuoto per usare MODX_BASE_URL.';

$_lang['setting_session_cookie_secure'] = 'Sicurezza Cookie Sessione';
$_lang['setting_session_cookie_secure_desc'] = 'Abilita questa opzione per usare cookies per sessioni sicure.';

$_lang['setting_session_cookie_httponly'] = 'Session Cookie HttpOnly';
$_lang['setting_session_cookie_httponly_desc'] = 'Utilizza questa opzione per spuntare il flag HttpOnly sui cookies della sessione.';

$_lang['setting_session_gc_maxlifetime'] = 'Session Garbage Collector Max Lifetime';
$_lang['setting_session_gc_maxlifetime_desc'] = 'Allows customization of the session.gc_maxlifetime PHP ini setting when using \'modSessionHandler\'.';

$_lang['setting_session_handler_class'] = 'Nome Classe Gestore Sessione';
$_lang['setting_session_handler_class_desc'] = 'Per sessioni gestite dal database, usa \'modSessionHandler\'.  Lascia il campo vuoto per usare il gestore standard PHP delle sessioni.';

$_lang['setting_session_name'] = 'Nome Sessione';
$_lang['setting_session_name_desc'] = 'Usa questa impostazione per personalizzare il nome della sessione usato quando si è nel gestore di MODX. Lascia il campo vuoto per usare il nome standard delle sessioni PHP.';

$_lang['setting_settings_version'] = 'Versione Corrente';
$_lang['setting_settings_version_desc'] = 'La versione correntemente installata di MODX.';

$_lang['setting_settings_distro'] = 'Impostazioni Distribuzione';
$_lang['setting_settings_distro_desc'] = 'La distribuzione correntemente installata di MODX, traditional o advantage.';

$_lang['setting_set_header'] = 'Imposta Headers HTTP';
$_lang['setting_set_header_desc'] = 'Se abilitato "SI", MODX proverà a impostare gli headers HTTP per le Risorse.';

$_lang['setting_show_tv_categories_header'] = 'Mostra schede intestazione "Categorie" con le TV';
$_lang['setting_show_tv_categories_header_desc'] = 'Se "SI", MODX mostrera\' l\'intestazione "Categorie" sopra la prima scheda di categoria quando si modifica una Risorsa.';

$_lang['setting_signupemail_message'] = 'E-mail Registrazione';
$_lang['setting_signupemail_message_desc'] = 'Qui puoi impostare il messaggio da spedire agli utenti quando crei loro un account e scegli di far mandare loro direttamente da MODX una mail con lo username e la password. <br /><strong>Nota:</strong> I seguenti identificatori saranno sostituiti coi relativi valori dal Manager quando viene inviato un messaggio: <br /><br />[[+sname]] - Nome del tuo sito, <br />[[+saddr]] - L\'indirizzo email del tuo sito, <br />[[+surl]] - L\'url del tuo sito, <br />[[+uid]] - Nome Login o id utente, <br />[[+pwd]] - Password Utente, <br />[[+ufn]] - Nome completo Utente. <br /><br /><strong>Lascia [[+uid]] e [[+pwd]] nella e-mail, o lo username e la password non saranno inviati nella mail e i tuoi utenti non conosceranno i propri username e password!</strong>';
$_lang['setting_signupemail_message_default'] = 'Ciao [[+uid]] \n\nDi seguito trovi i dettagli del login per il Pannello di Controllo di: [[+sname]]\n\nUsername: [[+uid]]\nPassword: [[+pwd]]\n\nUna volta loggato nel contente Manager ([[+surl]]), potra cambiare la tua password.\n\\Cordiali saluti,\nl\'amministratore del sito';

$_lang['setting_site_name'] = 'Nome Sito';
$_lang['setting_site_name_desc'] = 'Inserisci qui il nome del tuo sito.';
$_lang['setting_site_name_err'] = 'Inserisci un nome per il sito.';

$_lang['setting_site_start'] = 'Pagina Iniziale Sito';
$_lang['setting_site_start_desc'] = 'Inserisci l\'ID della Risorsa che vuoi usare come homepage. <strong>NOTA: assicurati che questo ID appartenga a una Risorsa esistente, pubblicata e accessibile dagli utenti!</strong>';
$_lang['setting_site_start_err'] = 'Specifica l\'ID della Risorsa da usare come pagina iniziale.';

$_lang['setting_site_status'] = 'Stato Sito OnLine';
$_lang['setting_site_status_desc'] = 'Scegli \'SI\' per pubblciare il tuo sito sul web. Se scegli \'NO\', i tuoi visitatori vedranno l\'avviso di \'Sito non disponibile\', e non potranno navigare all\'interno del sito stesso.';
$_lang['setting_site_status_err'] = 'Per favore specifica se vuoi mettere il sito online (SI) o offline (NO).';

$_lang['setting_site_unavailable_message'] = 'Messaggio Sito Non disponibile';
$_lang['setting_site_unavailable_message_desc'] = 'Il messaggio che vuoi mostrare quando il sito è offline o si verifica un errore. <strong>Nota: Questo messaggio verrà mostrato soltanto se l\'opzione relativa alla pagina di Sito non disponibile non è impostata.</strong>';

$_lang['setting_site_unavailable_page'] = 'Pagina Sito Non disponibile';
$_lang['setting_site_unavailable_page_desc'] = 'Inserisci l\'ID della Risorsa che vuoi usare come pagina offline. <strong>NOTA: assicurati che questo ID appartenga a una Risorsa esistente, pubblicata e accessibile dagli utenti!</strong> <b> Inserendo 0 NON si utilizza nessuna Risorsa come pagina Indisponibile ma SOLO il messaggio impostato nell\'apposito parametro </b>';
$_lang['setting_site_unavailable_page_err'] = 'Per favore specifica l\'ID della Risorsa da usare come pagina "Sito Indisponibile".';

$_lang['setting_strip_image_paths'] = 'Riscrivi Percorsi Browser?';
$_lang['setting_strip_image_paths_desc'] = 'Se impostato su \'NO\', MODX scriverà le origini dei files delle risorse src(immagini, files, flash, etc.) come URLs assoluti. Gli URLs relativi sono utili per spostare la tua installazione di MODX, e.g., da un server di test a un server di produzione. Se non hai idea di cosa significhi, sarebbe opportuno lasciare semplicemente impostato su \'SI\'.';

$_lang['setting_symlink_merge_fields'] = 'Unisci Campi Risorsa nei Symlinks';
$_lang['setting_symlink_merge_fields_desc'] = 'Se impostato su Si, unirà automaticamente i campi non-vuoti con la risorsa target in caso di reindirizzamento con Symlinks.';

$_lang['setting_topmenu_show_descriptions'] = 'Mostra Descrizioni Top Menu';
$_lang['setting_topmenu_show_descriptions_desc'] = 'Se impostato su \'NO\', MODX nasconderà le descrizioni dagli oggetti del top menu del manager.';

$_lang['setting_tree_default_sort'] = 'Campo per Ordinamento Predefinito Albero Risorse';
$_lang['setting_tree_default_sort_desc'] = 'Quale campo della Risorsa usare per l\'ordinamento predefinito dell\'albero delle risorse durante il caricamento del manager. Normalmente menuindex';

$_lang['setting_tree_root_id'] = 'ID Radice Albero';
$_lang['setting_tree_root_id_desc'] = 'Imposta un ID valido di una Risorsa. Il nodo della Risorsa diventerà la radice dell\'Albero a sinistra. L\'utente potrà vedere soltanto le Risorse che sono figli della Risorsa specificata. Impostando 0 sarà possibile vedere TUTTO';

$_lang['setting_tvs_below_content'] = 'Sposta le  TVs sotto il Contenuto (Content)';
$_lang['setting_tvs_below_content_desc'] = 'Impostato su "SI" sposta le Variabili di Template sotto il contenuto (content) durante la modifica di una Risorsa.';

$_lang['setting_ui_debug_mode'] = 'UI Debug Mode (Interfaccia Utente)';
$_lang['setting_ui_debug_mode_desc'] = 'Impostare su "SI" per i messaggi di debug in uscita quando si usa UI (Interfaccia Utente) per il tema di default del manager. E\' necessario utilizzare un browser che supporti console.log.';

$_lang['setting_udperms_allowroot'] = 'Consenti in Radice';
$_lang['setting_udperms_allowroot_desc'] = 'Vuoi permettere ai tuoi utenti di creare nuove risorse nella radice principale del sito? ';

$_lang['setting_unauthorized_page'] = 'Pagina Non Autorizzata';
$_lang['setting_unauthorized_page_desc'] = 'Inserisci l\'ID della Risorsa che vuoi mostrare agli utenti se questi provano ad accedere a una pagina non autorizzata o di Sicurezza. <strong>NOTA: assicurati che questo ID appartenga a una Risorsa esistente, pubblicata e pubblicamente accessibile!</strong>';
$_lang['setting_unauthorized_page_err'] = 'Specifica l\'ID di una Risorsa da mostare come pagina senza autorizzazione.';

$_lang['setting_upload_files'] = 'Tipi File Caricabili';
$_lang['setting_upload_files_desc'] = 'Qui puoi inserire una lista di tipi di files che possono essere caricati dentro \'assets/files/\' tramite il Manager delle Risorse. Inserisci le estensioni per i tipi di files, separate da virgola.';

$_lang['setting_upload_flash'] = 'Tipi Flash Caricabili';
$_lang['setting_upload_flash_desc'] = 'Qui puoi inserire una lista di tipi di files flash che possono essere caricati dentro  \'assets/flash/\' usando il Manager delle Risorse. Inserisci le estensioni per i tipi di file flash, separate da virgola.';

$_lang['setting_upload_images'] = 'Tipi Immagini Caricabili';
$_lang['setting_upload_images_desc'] = 'Qui puoi inserire una lista di tipi di files immagini che possono essere caricati dentro  \'assets/images/\' usando il Manager delle Risorse. Inserisci le estensioni per i tipi di immagini, separate da virgola.';

$_lang['setting_upload_maxsize'] = 'Dimensione Massima Caricabile';
$_lang['setting_upload_maxsize_desc'] = 'Inserisci la dimensione massima (bytes) di un file che può essere caricato tramite il file manager. La dimensione deve essere inserita in bytes. <strong>NOTA: Files grandi possono impiegare molto tempo per essere caricati!</strong>';

$_lang['setting_upload_media'] = 'Tipi Media Caricabili';
$_lang['setting_upload_media_desc'] = 'Qui puoi inserire una lista di tipi di files media (video,audio) che possono essere caricati dentro  \'assets/media/\' usando il Manager delle Risorse. Inserisci le estensioni per i tipi di media, separate da virgola.';

$_lang['setting_use_alias_path'] = 'Usa Percorsi Semplici Alias (Completi)';
$_lang['setting_use_alias_path_desc'] = 'Impostando questa opzione su \'SI\' verrà mostrato il percorso completo della Risorsa, se la Risorsa ha un alias. Per esempio, se una Risorsa con un alias chiamato \'figlio\' si trova nella cartella con alias \'genitore\', allora il percorso dell\'alias sarà \'/genitore/figlio.html\'.<br /><strong>NOTA: Se impostato su \'SI\' (attivando il percorso alias), si deve far riferimento agli oggetti (come immagini, css, javascripts, ecc) usando il percorso assoluto: es., \'/assets/images\' invece di \'assets/images\'. In questo modo si evita che il browser (o il web-server) aggiunga il percorso relativo al percorso dell\'alias.</strong>';

$_lang['setting_use_browser'] = 'Abilita Browser Risorse';
$_lang['setting_use_browser_desc'] = 'Seleziona \'SI\' per abilitare il browser delle risorse. Questo consentirà ai tuoi utenti di navigare e caricare risorse come immagini, flash e files multimediali sul server.';
$_lang['setting_use_browser_err'] = 'Specifica se consentire o no l\'utilizzo del browser delle risorse.';

$_lang['setting_use_editor'] = 'Abilita Editor Rich Text';
$_lang['setting_use_editor_desc'] = 'Vuoi abilitare l\'editor rich text per le Risorse? Se sei abituato a scrivere direttamente in HTML, allora puoi disabilitare l\'editor da qui impostando "NO". Nota che questa opzione si applica a tutti i documenti e a tutti gli utenti!';
$_lang['setting_use_editor_err'] = 'Specifica se vuoi usare un Editor Rich Text.';

$_lang['setting_use_multibyte'] = 'Usa Estensione Multibyte';
$_lang['setting_use_multibyte_desc'] = 'Imposta su \'Si\' se vuoi usare l\'estensione mbstring per i caratteri multibyte nella tua installazione MODX. Imposta su \'SI\' SOLO se hai l\'estensione mbstring di PHP installata.';

$_lang['setting_use_weblink_target'] = 'Usa WebLink Target';
$_lang['setting_use_weblink_target_desc'] = 'Imposta su "SI" se vuoi che i tags link di MODX e makeUrl() generino links come URL bersagli per WebLinks. Altrimenti l\'URL interno di MODX sara\' generato dal metodo tags link e makeUrl().';

$_lang['setting_webpwdreminder_message'] = 'E-mail Recupero Dati Accesso';
$_lang['setting_webpwdreminder_message_desc'] = 'Inserisci il messaggio da inviare tramite mail, quando gli utenti chiedono una nuova password via email. Il Manager invierà loro una e-mail contenente la nuova password e le informazioni di attivazione. <br /><strong>Nota:</strong> I seguenti identificatori saranno sostituiti coi relativi valori dal Manager quando il messaggio verrà inviato:<br /><br /> [[+sname]] - Nome del sito, <br />[[+saddr]] - Indirizzo email del sito, <br />[[+surl]] - Url del sito, <br />[[+uid]] - Login o id dell\'utente, <br />[[+pwd]] - Password utente, <br />[[+ufn]] - Nome completo dell\'utente.<br /><br /><strong>Lasciate [[+uid]] e [[+pwd]] nella e-mail, altrimenti il nome utente e la password non verranno inviati!</strong>';
$_lang['setting_webpwdreminder_message_default'] = 'Salve [[+uid]]\n\nPer attivare la tua password clicca sul link sottostante:\n\n[[+surl]]\n\nIn seguito potrai usare la seguente password per eseguire il login:\n\nPassword:[[+pwd]]\n\nSe non hai richiesto questa mail, ignorala.\n\nCordiali Saluti, l\'amministratore del Sito';

$_lang['setting_websignupemail_message'] = 'E-mail Registrazione';
$_lang['setting_websignupemail_message_desc'] = 'Qui puoi impostare il messaggio da spedire agli utenti quando crei loro un account web e scegli di far mandare loro direttamente da MODX una mail con lo username e la password. <br /><strong>Nota:</strong> I seguenti identificativi saranno sostituiti coi relativi valori dal Manager al momento dell\'invio del messaggio: <br /><br />[[+sname]] - Nome del tuo sito, <br />[[+saddr]] - L\'indirizzo email del tuo sito, <br />[[+surl]] - L\'url del tuo sito, <br />[[+uid]] - Nome Login o id utente, <br />[[+pwd]] - Password Utente, <br />[[+ufn]] - Nome completo Utente. <br /><br /><strong>Lascia [[+uid]] e [[+pwd]] nella e-mail, o lo username e la password non saranno inviati nella mail e i tuoi utenti non conosceranno i propri username e password!</strong>';
$_lang['setting_websignupemail_message_default'] = 'Ciao [[+uid]] \n\nDi seguito trovi i dettagli per il tuo login su: [[+sname]]\n\nUsername: [[+uid]]\nPassword: [[+pwd]]\n\nUna volta loggato in [[+sname]] ([[+surl]]), potrai cambiare la tua password.\n\\Cordiali saluti,\nl\'amministratore del sito';

$_lang['setting_welcome_screen'] = 'Mostra Schermata Benvenuto';
$_lang['setting_welcome_screen_desc'] = 'Se impostato su \'SI\', la schermata di benvenuto verrà mostrata al prossimo caricamento della pagina di benvenuto, e non verrà mostrato successivamente.';

$_lang['setting_welcome_screen_url'] = 'Indirizzo Schermata Benvenuto';
$_lang['setting_welcome_screen_url_desc'] = 'L\'URL della schermata di benvenuto da caricare al primo avvio di MODX Revolution.';

$_lang['setting_which_editor'] = 'Editor da Usare';
$_lang['setting_which_editor_desc'] = 'Qui puoi selezionare quale Editor Rich Text vorresti usare. Puoi scaricare e installare ulteriori Editors Rich Text dal Gestore dei Pacchetti.';

$_lang['setting_which_element_editor'] = 'Editor Utilizzato per Elementi';
$_lang['setting_which_element_editor_desc'] = 'Qui puoi specificare quale Editor Rich Text vorresti usare durante la modifica degli Elementi (chunk,snippet,ecc.ecc.). Puoi scaricare e installare ulteriori Editors Rich Text dal Gestore dei Pacchetti.';

$_lang['setting_xhtml_urls'] = 'XHTML URLs';
$_lang['setting_xhtml_urls_desc'] = 'Se impostato su \'SI\', tutti gli URLs generati da MODX saranno XHTML-compliant, compresa la codifica della e commerciale (ampersand (&)).';

$_lang['setting_default_context'] = 'Contesto di Default';
$_lang['setting_default_context_desc'] = 'Seleziona il Contesto di default che vuoi sia utilizzato per le nuove Risorse.';