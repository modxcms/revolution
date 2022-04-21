<?php
/**
 * Template English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['access'] = 'Zugriff';
$_lang['filter_by_category'] = 'Nach Kategorie filtern …';
$_lang['rank'] = 'Rang';
$_lang['template'] = 'Template';
$_lang['template_assignedtv_tab'] = 'Zugewiesene TVs';
$_lang['template_category_desc'] = 'Use to group Templates within the Elements tree.';
$_lang['template_code'] = 'Template Code (HTML)';
$_lang['template_delete_confirm'] = 'Sind Sie sicher, dass Sie dieses Template löschen möchten?';
$_lang['template_description_desc'] = 'Usage information for this Template shown in search results and as a tooltip in the Elements tree.';
$_lang['template_duplicate_confirm'] = 'Sind Sie sicher, dass Sie dieses Template duplizieren möchten?';
$_lang['template_edit_tab'] = 'Template bearbeiten';
$_lang['template_empty'] = '(leer)';
$_lang['template_err_default_template'] = 'Dieses Template wurde als Standardtemplate festgelegt. Bitte wählen Sie ein anderes Standardtemplate in der MODX-Konfiguration, bevor Sie dieses Template löschen.<br />';
$_lang['template_err_delete'] = 'Beim Versuch, das Template zu löschen, ist ein Fehler aufgetreten.';
$_lang['template_err_duplicate'] = 'Beim Duplizieren des Templates ist ein Fehler aufgetreten.';
$_lang['template_err_ae'] = 'Ein Template mit dem Namen "[[+name]]" existiert bereits.';
$_lang['template_err_in_use'] = 'Dieses Template ist in Benutzung. Bitte wählen Sie für die Dokumente, die das Template verwenden, ein anderes Template. Dokumente, die dieses Template verwenden:<br />';
$_lang['template_err_invalid_name'] = 'Der Template-Name ist ungültig.';
$_lang['template_err_locked'] = 'Das Template ist für die Bearbeitung gesperrt.';
$_lang['template_err_nf'] = 'Template nicht gefunden!';
$_lang['template_err_ns'] = 'Template nicht angegeben.';
$_lang['template_err_ns_name'] = 'Bitte geben Sie einen Namen für das Template an.';
$_lang['template_err_remove'] = 'Beim Versuch, das Template zu löschen, ist ein Fehler aufgetreten.';
$_lang['template_err_save'] = 'Beim Speichern des Templates ist ein Fehler aufgetreten.';
$_lang['template_icon'] = 'Manager Icon Class';
$_lang['template_icon_desc'] = 'A CSS class to assign an icon (shown in the document trees) for all resources using this template. Font Awesome Free 5 classes such as “fa-home” may be used.';
$_lang['template_lock'] = 'Template für die Bearbeitung sperren';
$_lang['template_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Template.';
$_lang['template_locked_message'] = 'Das Template ist gesperrt.';
$_lang['template_management_msg'] = 'Hier können Sie wählen, welches Template Sie bearbeiten möchten.';
$_lang['template_name_desc'] = 'Der Name dieses Templates.';
$_lang['template_new'] = 'Template erstellen';
$_lang['template_no_tv'] = 'Diesem Template wurden bisher keine TVs zugeordnet.';
$_lang['template_preview'] = 'Preview Image';
$_lang['template_preview_desc'] = 'Used to preview the layout of this Template when creating a new Resource. (Minimum size: 335 x 236)';
$_lang['template_preview_source'] = 'Preview Image Media Source';
$_lang['template_preview_source_desc'] = 'Sets the basePath for this Template’s Preview Image to the one specified in the chosen Media Source. Choose “None” when specifying an absolute or other custom path to the file.';
$_lang['template_properties'] = 'Standardeigenschaften';
$_lang['template_reset_all'] = 'Alle Seiten zurücksetzen, um das Standardtemplate zu verwenden';
$_lang['template_reset_specific'] = 'Nur "%s"-Seiten zurücksetzen';
$_lang['template_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Template</em> as well as its content. The content must be HTML, either placed in the <em>Template Code</em> field below or in a static external file, and may include MODX tags. Note that changed or new templates won’t be visible in your site’s cached pages until the cache is emptied; however, you can use the preview function on a page to see the template in action.';
$_lang['template_title'] = 'Template erstellen/bearbeiten';
$_lang['template_tv_edit'] = 'Sortierreihenfolge der TVs bearbeiten';
$_lang['template_tv_msg'] = 'Die diesem Template zugeordneten  <abbr title="Template-Variablen">TVs</abbr> sind unten aufgeführt.';
$_lang['template_untitled'] = 'Unbenanntes Template';
$_lang['templates'] = 'Templates';
$_lang['tvt_err_nf'] = 'Die TV hat keinen Zugriff auf das angegebene Template.';
$_lang['tvt_err_remove'] = 'Beim Versuch, die TV vom Template zu entfernen, ist ein Fehler aufgetreten.';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['template_desc_category'] = $_lang['template_category_desc'];
$_lang['template_desc_description'] = $_lang['template_description_desc'];
$_lang['template_desc_name'] = $_lang['template_name_desc'];
$_lang['template_lock_msg'] = $_lang['template_lock_desc'];

// --tabs
$_lang['template_msg'] = $_lang['template_tab_general_desc'];
