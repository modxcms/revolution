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
$_lang['template_category_desc'] = 'Ermöglicht die Gruppierung von Templates innerhalb des Elementbaums.';
$_lang['template_code'] = 'Template-Code (HTML)';
$_lang['template_delete_confirm'] = 'Sind Sie sicher, dass Sie dieses Template löschen möchten?';
$_lang['template_description_desc'] = 'Benutzungshinweise für dieses Template in Suchergebnissen und als Tooltip im Elementbaum anzeigen.';
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
$_lang['template_icon'] = 'Manager-Symbol-Klasse';
$_lang['template_icon_desc'] = 'Eine CSS-Klasse, um allen Ressourcen, die diese Vorlage verwenden, ein Symbol zuzuweisen (angezeigt im Ressourcenbaum). Font Awesome Free 5 Klassen wie „fa-home“ können verwendet werden.';
$_lang['template_lock'] = 'Template für die Bearbeitung sperren';
$_lang['template_lock_desc'] = 'Nur Benutzer mit „edit_locked“ Zugriffsberechtigung können dieses Template bearbeiten.';
$_lang['template_locked_message'] = 'Das Template ist gesperrt.';
$_lang['template_management_msg'] = 'Hier können Sie wählen, welches Template Sie bearbeiten möchten.';
$_lang['template_name_desc'] = 'Der Name dieses Templates.';
$_lang['template_new'] = 'Template erstellen';
$_lang['template_no_tv'] = 'Diesem Template wurden bisher keine TVs zugeordnet.';
$_lang['template_preview'] = 'Vorschau-Bild';
$_lang['template_preview_desc'] = 'Dient zur Vorschau des Layouts dieser Vorlage bei der Erstellung einer neuen Ressource. (Mindestgröße: 335 x 236)';
$_lang['template_preview_source'] = 'Medienquelle für das Vorschau-Bild';
$_lang['template_preview_source_desc'] = 'Setzt den basePath für das Vorschaubild dieser Vorlage auf den in der gewählten Medienquelle angegebenen Pfad. Wählen Sie "Keine", wenn Sie einen absoluten oder einen anderen benutzerdefinierten Pfad zu der Datei angeben.';
$_lang['template_properties'] = 'Standardeigenschaften';
$_lang['template_reset_all'] = 'Alle Seiten zurücksetzen, um das Standardtemplate zu verwenden';
$_lang['template_reset_specific'] = 'Nur "%s"-Seiten zurücksetzen';
$_lang['template_tab_general_desc'] = 'Hier können Sie die grundlegenden Attribute für dieses <em>Template</em> sowie seinen Inhalt eingeben. Der Inhalt muss HTML sein, entweder im Feld <em>Template Code</em> unten oder in einer statischen externen Datei, und kann MODX-Tags enthalten. Beachten Sie, dass geänderte oder neue Templates in den Cache-Seiten Ihrer Website erst sichtbar werden, wenn der Cache geleert wird; Sie können jedoch die Vorschau-Funktion auf einer Seite verwenden, um das Template in Aktion zu sehen.';
$_lang['template_tv_edit'] = 'Sortierreihenfolge der TVs bearbeiten';
$_lang['template_tv_msg'] = 'Die diesem Template zugeordneten  <abbr title="Template-Variablen">TVs</abbr> sind unten aufgeführt.';
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
