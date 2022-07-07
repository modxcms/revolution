<?php
/**
 * Template English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['access'] = 'Accesso';
$_lang['filter_by_category'] = 'Filtra per Categoria...';
$_lang['rank'] = 'Rango';
$_lang['template'] = 'Modello';
$_lang['template_assignedtv_tab'] = 'TV Assegnate';
$_lang['template_category_desc'] = 'Use to group Templates within the Elements tree.';
$_lang['template_code'] = 'Template Code (HTML)';
$_lang['template_delete_confirm'] = 'Sei sicuro di voler eliminare questo template?';
$_lang['template_description_desc'] = 'Usage information for this Template shown in search results and as a tooltip in the Elements tree.';
$_lang['template_duplicate_confirm'] = 'Sei sicuro di voler duplicare questo template?';
$_lang['template_edit_tab'] = 'Modifica Template';
$_lang['template_empty'] = '(vuoto)';
$_lang['template_err_default_template'] = 'Questo template è impostato come  template di default predefinito. Scegli un diverso template di default nella configurazione di sistema prima di eliminare questo.<br />';
$_lang['template_err_delete'] = 'Si è verificato un errore provando a eliminare il modello.';
$_lang['template_err_duplicate'] = 'Si è verificato un errore durante la duplicazione del template.';
$_lang['template_err_ae'] = 'Esiste già un template con nome "[[+name]]".';
$_lang['template_err_in_use'] = 'Questo template è attualmente in uso. Assegna un template diverso alle risorse che lo stanno usando. Elenco Risorse che stanno usando questo template:<br />';
$_lang['template_err_invalid_name'] = 'Nome del modello non valido.';
$_lang['template_err_locked'] = 'Il Template è bloccato e non può essere modificato.';
$_lang['template_err_nf'] = 'Template non trovato!';
$_lang['template_err_ns'] = 'Template non specificato.';
$_lang['template_err_ns_name'] = 'Specifica un nome per il template.';
$_lang['template_err_remove'] = 'Si è verificato un errore provando a eliminare il modello.';
$_lang['template_err_save'] = 'Si è verificato un errore durante il salvataggio del template.';
$_lang['template_icon'] = 'Manager Icon Class';
$_lang['template_icon_desc'] = 'A CSS class to assign an icon (shown in the document trees) for all resources using this template. Font Awesome Free 5 classes such as “fa-home” may be used.';
$_lang['template_lock'] = 'Blocca Modifiche Template';
$_lang['template_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Template.';
$_lang['template_locked_message'] = 'Questo template è bloccato.';
$_lang['template_management_msg'] = 'Da qui puoi scegliere il template da modificare.';
$_lang['template_name_desc'] = 'Il nome di questo Template.';
$_lang['template_new'] = 'Crea Modello';
$_lang['template_no_tv'] = 'Nessuna TV ancora assegnata a questo modello.';
$_lang['template_preview'] = 'Preview Image';
$_lang['template_preview_desc'] = 'Used to preview the layout of this Template when creating a new Resource. (Minimum size: 335 x 236)';
$_lang['template_preview_source'] = 'Preview Image Media Source';
$_lang['template_preview_source_desc'] = 'Sets the basePath for this Template’s Preview Image to the one specified in the chosen Media Source. Choose “None” when specifying an absolute or other custom path to the file.';
$_lang['template_properties'] = 'Proprietà di default';
$_lang['template_reset_all'] = 'Reimposta tutte le pagine all\' utilizzo del template di default';
$_lang['template_reset_specific'] = 'Reimposta soltanto \'%s\' pagine che dovranno utilizare il template di default';
$_lang['template_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Template</em> as well as its content. The content must be HTML, either placed in the <em>Template Code</em> field below or in a static external file, and may include MODX tags. Note that changed or new templates won’t be visible in your site’s cached pages until the cache is emptied; however, you can use the preview function on a page to see the template in action.';
$_lang['template_tv_edit'] = 'Modifica l\'ordine delle TV';
$_lang['template_tv_msg'] = 'Le <abbr title="Template Variables">TV</abbr> assegnate a questo modello sono elencate sotto.';
$_lang['templates'] = 'Modelli';
$_lang['tvt_err_nf'] = 'La TV non ha accesso al Modello specificato.';
$_lang['tvt_err_remove'] = 'Si è verificato un errore provando a eliminare la TV dal modello.';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['template_desc_category'] = $_lang['template_category_desc'];
$_lang['template_desc_description'] = $_lang['template_description_desc'];
$_lang['template_desc_name'] = $_lang['template_name_desc'];
$_lang['template_lock_msg'] = $_lang['template_lock_desc'];

// --tabs
$_lang['template_msg'] = $_lang['template_tab_general_desc'];
