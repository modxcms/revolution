<?php
/**
 * Sources English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['access'] = 'Permessi d\'Accesso';
$_lang['base_path'] = 'Percorso base';
$_lang['base_path_relative'] = 'Percorso base relativo?';
$_lang['base_url'] = 'URL di base';
$_lang['base_url_relative'] = 'URL di base relativo?';
$_lang['minimum_role'] = 'Ruolo minimo';
$_lang['path_options'] = 'Opzioni di percorso';
$_lang['policy'] = 'Politica';
$_lang['source'] = 'Sorgente Multimediale';
$_lang['source_access_add'] = 'Aggiungi Gruppo Utenti';
$_lang['source_access_remove'] = 'Elimina Accesso';
$_lang['source_access_remove_confirm'] = 'Sei sicuro di voler eliminare l\'Accesso a questa Sorgente per questo Gruppo di Utenti?';
$_lang['source_access_update'] = 'Modifica Accesso';
$_lang['source_description_desc'] = 'Una breve descrizione della Media Source.';
$_lang['source_err_ae_name'] = 'Una Media Source con quel nome esiste già! Si prega di specificare un nuovo nome.';
$_lang['source_err_nf'] = 'Media Source non trovata!';
$_lang['source_err_init'] = 'Non si è riusciti a inizializzare la Sorgente Media "[[+source]]"!';
$_lang['source_err_nfs'] = 'Nessuna Media Source può essere trovata con l\'id: [[+id]].';
$_lang['source_err_ns'] = 'Si prega di specificare la Media Source.';
$_lang['source_err_ns_name'] = 'Si prega di specificare un nome per la Media Source.';
$_lang['source_name_desc'] = 'Il nome della Media Source.';
$_lang['source_properties.intro_msg'] = 'Gestisci le proprietà di questa Media Source qui sotto.';
$_lang['source_remove_confirm'] = 'Sei sicuro di voler eliminare questa Sorgente Multimediale? Questo potrebbe corrompere ogni TV assegnata a questa sorgente.';
$_lang['source_remove_multiple_confirm'] = 'Sei sicuro di voler eliminare queste Media Sources? Questo potrebbe danneggiare qualsiasi TVs che sia stata assegnata a queste Sources.';
$_lang['source_type'] = 'Tipo di Source';
$_lang['source_type_desc'] = 'Il tipo, o driver, della Media Source. L\'origine utilizzerà questo driver per connettersi quando raccoglie i suoi dati. Ad esempio: File System catturerà i file dal file system. S3 otterrà i file da un bucket S3.';
$_lang['source_type.file'] = 'File di Sistema';
$_lang['source_type.file_desc'] = 'Una Media Source basata su filesystem che sfoglia i file del server.';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'Navigata un bucket Amazon S3.';
$_lang['source_type.ftp'] = 'Protocollo di trasferimento file';
$_lang['source_type.ftp_desc'] = 'Naviga un server FTP remoto.';
$_lang['source_types'] = 'Tipi di origine';
$_lang['source_types.intro_msg'] = 'Questo è un elenco di tutti i Tipi di Media Sources installati su questa istanza MODX.';
$_lang['source.access.intro_msg'] = 'Qui è possibile limitare una Media Source a specifici gruppi di utenti e applicare policies per quei gruppi di utenti. Una Media Source senza gruppi utente collegati ad essa è disponibile a tutti gli utenti del manager.';
$_lang['sources'] = 'Sorgenti Multimediali';
$_lang['sources.intro_msg'] = 'Gestisci tutte le tue Media Sources qui.';
$_lang['user_group'] = 'Gruppo Utenti';

/* file source type */
$_lang['allowedFileTypes'] = 'allowedFileTypes';
$_lang['prop_file.allowedFileTypes_desc'] = 'Se impostato, restringerà i file visualizzati alle estensioni specificate. Si prega di specificarle in un elenco separato da virgole, senza il .';
$_lang['basePath'] = 'basePath';
$_lang['prop_file.basePath_desc'] = 'Il percorso del file cui puntare la Sorgente, ad esempio: risorse/immagini/<br>Il percorso potrebbe dipendere dal parametro "basePathRelative"';
$_lang['basePathRelative'] = 'basePathRelative';
$_lang['prop_file.basePathRelative_desc'] = 'Se l\'impostazione del percorso di Base qui sopra non è relativa al percorso di installazione MODX, impostare su No.';
$_lang['baseUrl'] = 'baseUrl';
$_lang['prop_file.baseUrl_desc'] = 'L\'URL da cui è accessibile questa sorgente, ad esempio: risorse/immagini/<br>Il percorso potrebbe dipendere dal parametro "baseUrlRelative"';
$_lang['baseUrlPrependCheckSlash'] = 'baseUrlPrependCheckSlash';
$_lang['prop_file.baseUrlPrependCheckSlash_desc'] = 'Se vero, MODX anteporrà solo il baseUrl se nessuno slash (/) si trova all\'inizio dell\'URL durante il rendering della TV. Utile per l\'impostazione di un valore di TV esterno al baseUrl.';
$_lang['baseUrlRelative'] = 'baseUrlRelative';
$_lang['prop_file.baseUrlRelative_desc'] = 'Se l\'impostazione di Base URL qui sopra non è relativo all\'URL di installazione MODX, impostare su NO.';
$_lang['imageExtensions'] = 'imageExtensions';
$_lang['prop_file.imageExtensions_desc'] = 'Un elenco delimitato da virgole di estensioni di file da utilizzare per le immagini. MODX tenterà di fare le miniature dei file con queste estensioni.';
$_lang['skipFiles'] = 'skipFiles';
$_lang['prop_file.skipFiles_desc'] = 'Un elenco delimitato da virgole. MODX salterà e nasconderà files e cartelle che corrispondono a uno qualsiasi di questi.';
$_lang['thumbnailQuality'] = 'thumbnailQuality';
$_lang['prop_file.thumbnailQuality_desc'] = 'La qualità delle miniature create, in una scala da 0-100.';
$_lang['thumbnailType'] = 'thumbnailType';
$_lang['prop_file.thumbnailType_desc'] = 'Il tipo di immagine da usare per le miniature.';
$_lang['prop_file.visibility_desc'] = 'Visibilità predefinita per nuovi file e cartelle.';
$_lang['no_move_folder'] = 'Il driver della Sorgente Media non supporta lo spostamento delle cartelle in questo momento.';

/* s3 source type */
$_lang['bucket'] = 'Secchio';
$_lang['prop_s3.bucket_desc'] = 'Il bucket di S3 da cui caricare i tuoi dati.';
$_lang['prop_s3.key_desc'] = 'La chiave di Amazon per l\'autenticazione al bucket.';
$_lang['prop_s3.imageExtensions_desc'] = 'Un elenco delimitato da virgole di estensioni di file da utilizzare per le immagini. MODX tenterà di fare le miniature dei file con queste estensioni.';
$_lang['prop_s3.secret_key_desc'] = 'La chiave segreta di Amazon per l\'autenticazione al bucket.';
$_lang['prop_s3.skipFiles_desc'] = 'Un elenco delimitato da virgole. MODX salterà e nasconderà files e cartelle che corrispondono a uno qualsiasi di questi.';
$_lang['prop_s3.thumbnailQuality_desc'] = 'La qualità delle miniature create, in una scala da 0-100.';
$_lang['prop_s3.thumbnailType_desc'] = 'Il tipo di immagine da usare per le miniature.';
$_lang['prop_s3.url_desc'] = 'L\'URL dell\'istanza Amazon S3.';
$_lang['prop_s3.endpoint_desc'] = 'URL dell\'endpoint alternativo compatibile con S3, es., "https://s3.<region>.example.com". Revisiona la documentazione del tuo fornitore compatibile con S3 per la posizione dell\'endpoint. Lascia vuoto per Amazon S3';
$_lang['prop_s3.region_desc'] = 'Regione del secchio. Esempio: us-west-1';
$_lang['prop_s3.prefix_desc'] = 'Prefisso di percorso/cartella facoltativo';
$_lang['s3_no_move_folder'] = 'Il driver S3 non supporta lo spostamento delle cartelle in questo momento.';

/* ftp source type */
$_lang['prop_ftp.host_desc'] = 'Hostname server o indirizzo IP';
$_lang['prop_ftp.username_desc'] = 'Nome utente per l\'autenticazione. Può essere "anonimo".';
$_lang['prop_ftp.password_desc'] = 'Password dell\'utente. Lasciare vuoto per utente anonimo.';
$_lang['prop_ftp.url_desc'] = 'Se questo FTP è ha un URL pubblico, è possibile immettere l\'indirizzo di http pubblico qui. Ciò consentirà anche le anteprime di immagini nel browser multimediale.';
$_lang['prop_ftp.port_desc'] = 'Porta del server, la predefinita è 21.';
$_lang['prop_ftp.root_desc'] = 'La cartella radice, verrà aperta dopo la connessione';
$_lang['prop_ftp.passive_desc'] = 'Attivare o disattivare la modalità ftp passiva';
$_lang['prop_ftp.ssl_desc'] = 'Abilitare o disabilitare la connessione ssl';
$_lang['prop_ftp.timeout_desc'] = 'Timeout per la connessione in secondi.';

/* file type */
$_lang['PNG'] = 'PNG';
$_lang['JPG'] = 'JPG';
$_lang['GIF'] = 'GIF';
