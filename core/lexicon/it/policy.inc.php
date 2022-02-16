<?php
/**
 * Access Policy English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['template_group'] = 'Gruppo Template';
$_lang['active_of'] = '[[+active]] di [[+total]]';
$_lang['active_permissions'] = 'Permessi Attivi';
$_lang['no_policy_option'] = ' (Nessuna Policy) ';
$_lang['permission'] = 'Permesso';
$_lang['permission_err_ae'] = 'Permesso già esistente per questa politica.';
$_lang['permission_err_nf'] = 'Permesso non trovato.';
$_lang['permission_err_ns'] = 'Permesso non specificato.';
$_lang['permission_err_remove'] = 'Si è verificato un errore provando a eliminare quest\'autorizzazione.';
$_lang['permission_err_save'] = 'Si è verificato un errore durante il tentativo di salvare questo permesso.';
$_lang['permission_remove_confirm'] = 'Sei sicuro di voler eliminare quest\'autorizzazione?';
$_lang['permissions'] = 'Permessi';
$_lang['permissions_desc'] = 'Qui puoi definire specifichi permessi che questa politica conterrà. Tutti i gruppi di utenti con questa politica erediteranno questi permessi.';
$_lang['policies'] = 'Politiche Accesso';
$_lang['policy'] = 'Politica Accesso';
$_lang['policy_data'] = 'Dati Politica ';
$_lang['policy_desc'] = 'Le Politiche di Accesso sono generiche "istruzioni" che limitano o abilitano determinate azioni di Gestione.';
$_lang['policy_desc_name'] = 'Il nome della Policy di Accesso';
$_lang['policy_desc_description'] = 'Facoltativo. Una descrizione breve della Politica d\'Accesso. Qui puoi anche usare tasti lessicali.';
$_lang['policy_desc_template'] = 'Il Template della Policy usato per questa Policy. Le Policies prendono le loro Liste di Permessi dai loro Template.';
$_lang['policy_desc_lexicon'] = 'Opzionale. Il Topic Lexicon che questa Policy usa per tradurre i permessi di cui è proprietaria.';
$_lang['policy_duplicate_confirm'] = 'Sei sicuro di voler duplicare questa Politica e tutti i suoi dati?';
$_lang['policy_err_ae'] = 'Esiste già una Politica con il nome `[[+name]]`. Scegli un nome diverso.';
$_lang['policy_err_nf'] = 'Politica non trovata.';
$_lang['policy_err_ns'] = 'Politica non specificata.';
$_lang['policy_err_remove'] = 'Si è verificato un errore provando a eliminare la Politica.';
$_lang['policy_err_save'] = 'Si è verificato un errore durante il tentativo di salvataggio della Politica.';
$_lang['policy_import_msg'] = 'Selziona un file XML da cui importare una Policy. Deve essere nel corretto formato XML per le Policy.';
$_lang['policy_management'] = 'Politiche Accesso';
$_lang['policy_management_msg'] = 'Le Politiche di Accesso controllano i permessi per eseguire determinate azioni. Click destro sul nome per ulteriori attività';
$_lang['policy_name'] = 'Nome Politica';
$_lang['policy_property_create'] = 'Crea Proprietà Politica Accesso';
$_lang['policy_property_new'] = 'Crea Proprietà della Politica';
$_lang['policy_property_remove'] = 'Elimina Proprietà Politica Accesso';
$_lang['policy_property_specify_name'] = 'Specifica un nome per la Proprietà della Politica:';
$_lang['policy_remove_confirm'] = 'Sei sicuro di voler eliminare questa Politica d\'Accesso?';
$_lang['policy_remove_multiple_confirm'] = 'Sei sicuro di voler eliminare queste Politiche d\'Accesso? Ciò è irreversibile.';
$_lang['policy_template'] = 'Template Politica';
$_lang['policy_template_desc'] = 'Un Template di Politica definisce quali Permessi saranno mostrati nella griglia dei Permessi durante la modifica di una specifica Politica. Puoi aggiungere o rimuovere Permessi specifici da questo template di seguito. Nota che rimuovendo un Permesso da un Template questo sarà rimosso da qualsiasi altra Politica che usa lo stesso Template.';
$_lang['policy_template_desc_name'] = 'Il nome del Template della Policy di Accesso';
$_lang['policy_template_desc_description'] = 'Facoltativo. Una descrizione breve del Modello della Politica d\'Accesso. Qui puoi anche usare i tasti lessicali.';
$_lang['policy_template_lexicon'] = 'Argomenti Lessicali';
$_lang['policy_template_desc_lexicon'] = 'Opzionale. Il Topic Lexicon che questo Template di Policy userà per tradurre i permessi di cui è proprietario.';
$_lang['policy_template_desc_template_group'] = 'Il Gruppo di Template di Policy da usare. Questo è usato quando si selezionano delle Policies dal menu a tendina; di solito sono filtrate per Gruppi di template. Seleziona un appropriato gruppo per il tuo Template di Policy.';
$_lang['policy_template_duplicate_confirm'] = 'Sei sicuro di voler duplicare questo Template di Politica?';
$_lang['policy_template_err_ae'] = 'Un Template di Politica con il nome `[[+name]]` esiste già. Scegli un nome diverso.';
$_lang['policy_template_err_nf'] = 'Template di Politica non trovato.';
$_lang['policy_template_err_ns'] = 'Template di Politica non specificato.';
$_lang['policy_template_err_remove'] = 'Si è verificato un errore provando a eliminare il Modello della Politica.';
$_lang['policy_template_err_save'] = 'Si è verificato un errore durante il tentativo di salvare il Template di Politica.';
$_lang['policy_template_import_msg'] = 'Seleziona un file XML da cui importare il Template della Policy. Deve essere nel corretto formato XML per il Template delle Policy.';
$_lang['policy_template_remove_confirm'] = 'Sei sicuro di voler eliminare il Modello di questa Politica? Eliminerà anche tutte le Politiche allegate a questo modello, questo potrebbe corrompere la tua installazione di MODX se una Politica attiva è collegata a questo Modello.';
$_lang['policy_template_remove_confirm_in_use'] = 'Sei sicuro di voler eliminare questo Template di Policy? Eliminerà anche tutte le Policies allegate a questo Template - questo potrebbe danneggiare l\'installazione di MODX se alcune Policies attive sono collegate a questo Template.<br><br><strong>Questo modello è utilizzato dalle Policies esistenti ([[+count]] in totale). Sei sicuro di voler eliminare questo modello e tutte le policies allegate?</strong>';
$_lang['policy_template_remove_multiple_confirm'] = 'Sei sicuro di voler eliminare i Modelli di queste Politiche? Eliminerà anche tutte le Politiche allegate a questi Modelli, questo potrebbe corrompere la tua installazione di MODX se una Politica attiva è collegata a questi Modelli.';
$_lang['policy_template_remove_multiple_confirm_in_use'] = 'Sei sicuro di voler eliminare questi Template di Policy? Eliminerà anche tutte le Policies allegate a questi Template - questo potrebbe danneggiare l\'installazione di MODX se alcune Policies attive sono collegate a questi Template.<br><br><strong>Alcuni modelli selezionati sono utilizzati dalle Policies esistenti ([[+count]] in totale). Sei sicuro di voler eliminare questo modello e tutte le policies allegate?</strong>';
$_lang['policy_templates'] = 'Templates della Politica';
$_lang['policy_templates.intro_msg'] = 'Questa è una lista di Templates di Politica, definiscono liste di Permessi che sono selezionati o meno per determinate Politiche.';
$_lang['policy_template_administrator_desc'] = 'Il modello della politica d\'amministrazione del contesto con tutte le autorizzazioni.';
$_lang['policy_template_resource_desc'] = 'Modello della Politica della Risorsa con tutti gli attributi.';
$_lang['policy_template_object_desc'] = 'Modello della Politica dell\'Oggetto con tutti gli attributi.';
$_lang['policy_template_element_desc'] = 'Modello della Politica dell\'Elemento con tutti gli attributi.';
$_lang['policy_template_mediasource_desc'] = 'Modello della Politica della Sorgente Multimediale con tutti gli attributi.';
$_lang['policy_template_context_desc'] = 'Modello della Politica del Contesto con tutti gli attributi.';
$_lang['policy_template_namespace_desc'] = 'Modello della Politica dello Spazio del Nome con tutti gli attributi.';
$_lang['policy_template_group_administrator_desc'] = 'Tutti i modelli della politica d\'amministrazione.';
$_lang['policy_template_group_object_desc'] = 'All Object-based policy templates.';
$_lang['policy_template_group_resource_desc'] = 'All Resource-based policy templates.';
$_lang['policy_template_group_element_desc'] = 'Tutti i modelli della politica basati sull\'Elemento.';
$_lang['policy_template_group_mediasource_desc'] = 'Tutti i modelli della politica basati sulla Sorgente Multimediale.';
$_lang['policy_template_group_namespace_desc'] = 'Tutti i modelli della politica basati sullo Spazio del Nome.';
$_lang['policy_template_group_context_desc'] = 'Tutti i modelli della politica basati sul Contesto.';
$_lang['policy_resource_desc'] = 'Politica della Risorsa MODX con tutti gli attributi.';
$_lang['policy_administrator_desc'] = 'Politica d\'amministrazione del contesto con tutte le autorizzazioni.';
$_lang['policy_load_only_desc'] = 'Una politica minima con le autorizzazioni per caricare un oggetto.';
$_lang['policy_load_list_and_view_desc'] = 'Fornisce solo le autorizzazioni di caricamento, elenco e visualizzazione.';
$_lang['policy_object_desc'] = 'La politica di un Oggetto con tutte le autorizzazioni.';
$_lang['policy_element_desc'] = 'Politica dell\'Elemento di MODX con tutti gli attributi.';
$_lang['policy_content_editor_desc'] = 'Politica di amministrazione del contesto con Autorizzazioni limitate correlate alla modifica del contenuto, ma nessuna pubblicazione.';
$_lang['policy_media_source_admin_desc'] = 'Politica d\'amministrazione della Sorgente Multimediale.';
$_lang['policy_media_source_user_desc'] = 'Politica utente della Sorgente Multimediale, con visualizzazione e uso di base, ma nessuna modifica, delle Sorgenti Multimediali.';
$_lang['policy_developer_desc'] = 'Politica di amministrazione del contesto con gran parte delle Autorizzazioni tranne le funzionalità di Amministrazione e Sicurezza.';
$_lang['policy_context_desc'] = 'Politica del Contesto standard applicabile creando le ACL del Contesto per accesso di lettura/scrittura e view_unpublished di base entro un Contesto.';
$_lang['policy_hidden_namespace_desc'] = 'Politica dello Spazio del Nome nascosta, non mostrerà lo spazio del nome negli elenchi.';
$_lang['policy_count'] = 'Conteggio Policies';
