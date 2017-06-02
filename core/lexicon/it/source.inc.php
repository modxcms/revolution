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
$_lang['policy'] = 'Policy';
$_lang['source'] = 'Media Source';
$_lang['source_access_add'] = 'Aggiungi Gruppo Utenti';
$_lang['source_access_remove'] = 'Rimuovere l\'accesso';
$_lang['source_access_remove_confirm'] = 'Sei sicuro di voler rimuovere l\'accesso a questa Source per questo gruppo utenti?';
$_lang['source_access_update'] = 'Aggiornamento accesso';
$_lang['source_create'] = 'Creare una nuova Media Source';
$_lang['source_description_desc'] = 'Una breve descrizione della Media Source.';
$_lang['source_duplicate'] = 'Duplica la Media Source';
$_lang['source_err_ae_name'] = 'Una Media Source con quel nome esiste già! Si prega di specificare un nuovo nome.';
$_lang['source_err_nf'] = 'Media Source non trovata!';
$_lang['source_err_nfs'] = 'Nessuna Media Source può essere trovata con l\'id: [[+ id]].';
$_lang['source_err_ns'] = 'Si prega di specificare la Media Source.';
$_lang['source_err_ns_name'] = 'Si prega di specificare un nome per la Media Source.';
$_lang['source_name_desc'] = 'Il nome della Media Source.';
$_lang['source_properties.intro_msg'] = 'Gestisci le proprietà di questa Media Source qui sotto.';
$_lang['source_remove'] = 'Eliminare la Media Source';
$_lang['source_remove_confirm'] = 'Sei sicuro di voler eliminare questa Media Source? Questo potrebbe danneggiare qualsiasi TVs che sia stata assegnata a questa Source.';
$_lang['source_remove_multiple'] = 'Eliminare più Media Sources';
$_lang['source_remove_multiple_confirm'] = 'Sei sicuro di voler eliminare queste Media Sources? Questo potrebbe danneggiare qualsiasi TVs che sia stata assegnata a queste Sources.';
$_lang['source_update'] = 'Aggiorna Media Source';
$_lang['source_type'] = 'Tipo di Source';
$_lang['source_type_desc'] = 'Il tipo, o driver, della Media Source. L\'origine utilizzerà questo driver per connettersi quando raccoglie i suoi dati. Ad esempio: File System catturerà i file dal file system. S3 otterrà i file da un bucket S3.';
$_lang['source_type.file'] = 'File System';
$_lang['source_type.file_desc'] = 'Una Media Source basata su filesystem che sfoglia i file del server.';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'Navigata un bucket Amazon S3.';
$_lang['source_types'] = 'Tipi di origine';
$_lang['source_types.intro_msg'] = 'Questo è un elenco di tutti i Tipi di Media Sources installati su questa istanza MODX.';
$_lang['source.access.intro_msg'] = 'Qui è possibile limitare una Media Source a specifici gruppi di utenti e applicare policies per quei gruppi di utenti. Una Media Source senza gruppi utente collegati ad essa è disponibile a tutti gli utenti del manager.';
$_lang['sources'] = 'Media Sources';
$_lang['sources.intro_msg'] = 'Gestisci tutte le tue Media Sources qui.';
$_lang['user_group'] = 'Gruppo Utenti';

/* file source type */
$_lang['allowedFileTypes'] = 'allowedFileTypes';
$_lang['prop_file.allowedFileTypes_desc'] = 'Se impostato, restringerà i file visualizzati alle estensioni specificate. Si prega di specificarle in un elenco separato da virgole, senza il .';
$_lang['basePath'] = 'basePath';
$_lang['prop_file.basePath_desc'] = 'Il percorso del file a cui far puntare l\'Origine.';
$_lang['basePathRelative'] = 'basePathRelative';
$_lang['prop_file.basePathRelative_desc'] = 'Se l\'impostazione del percorso di Base qui sopra non è relativa al percorso di installazione MODX, impostare su No.';
$_lang['baseUrl'] = 'baseUrl';
$_lang['prop_file.baseUrl_desc'] = 'L\'URL da cui questa origine può essere accessibile.';
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

/* s3 source type */
$_lang['bucket'] = 'Bucket';
$_lang['prop_s3.bucket_desc'] = 'Il bucket di S3 da cui caricare i tuoi dati.';
$_lang['prop_s3.key_desc'] = 'La chiave di Amazon per l\'autenticazione al bucket.';
$_lang['prop_s3.imageExtensions_desc'] = 'Un elenco delimitato da virgole di estensioni di file da utilizzare per le immagini. MODX tenterà di fare le miniature dei file con queste estensioni.';
$_lang['prop_s3.secret_key_desc'] = 'La chiave segreta di Amazon per l\'autenticazione al bucket.';
$_lang['prop_s3.skipFiles_desc'] = 'Un elenco delimitato da virgole. MODX salterà e nasconderà files e cartelle che corrispondono a uno qualsiasi di questi.';
$_lang['prop_s3.thumbnailQuality_desc'] = 'La qualità delle miniature create, in una scala da 0-100.';
$_lang['prop_s3.thumbnailType_desc'] = 'Il tipo di immagine da usare per le miniature.';
$_lang['prop_s3.url_desc'] = 'L\'URL dell\'istanza Amazon S3.';
$_lang['s3_no_move_folder'] = 'Il driver S3 non supporta lo spostamento delle cartelle in questo momento.';
$_lang['prop_s3.region_desc'] = 'Region of the bucket. Example: us-west-1';

/* file type */
$_lang['PNG'] = 'PNG';
$_lang['JPG'] = 'JPG';
$_lang['GIF'] = 'GIF';
