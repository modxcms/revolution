<?php
/**
 * Template English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['access'] = 'Přístup';
$_lang['filter_by_category'] = 'Filtrovat podle kategorie...';
$_lang['rank'] = 'Pořadí';
$_lang['template'] = 'Šablona';
$_lang['template_assignedtv_tab'] = 'Assigned TVs';
$_lang['template_category_desc'] = 'Use to group Templates within the Elements tree.';
$_lang['template_code'] = 'Template Code (HTML)';
$_lang['template_delete_confirm'] = 'Opravdu chcete odstranit tuto šablonu?';
$_lang['template_description_desc'] = 'Usage information for this Template shown in search results and as a tooltip in the Elements tree.';
$_lang['template_duplicate_confirm'] = 'Opravdu chcete zkopírovat tuto šablonu?';
$_lang['template_edit_tab'] = 'Upravit šablonu';
$_lang['template_empty'] = '(žádný)';
$_lang['template_err_default_template'] = 'Tato šablona je nastavena jako základní. Nejdříve nastavte v konfiguraci systému jinou šablonu jako základní, následně budete mít možnost tuto šablonu odstranit.<br />';
$_lang['template_err_delete'] = 'An error occurred while trying to delete the template.';
$_lang['template_err_duplicate'] = 'Nastala chyba při kopírování šablony.';
$_lang['template_err_ae'] = 'Šablona s názvem "[[+name]]" již existuje.';
$_lang['template_err_in_use'] = 'Tato šablona je používaná. Nastavte dokumentům jinou šablonu:<br />';
$_lang['template_err_invalid_name'] = 'Neplatný název šablony.';
$_lang['template_err_locked'] = 'Šablona je uzamčena.';
$_lang['template_err_nf'] = 'Šablona nenalezena!';
$_lang['template_err_ns'] = 'Nespecifikovaná šablona.';
$_lang['template_err_ns_name'] = 'Zadejte název šablony.';
$_lang['template_err_remove'] = 'An error occurred while trying to delete the template.';
$_lang['template_err_save'] = 'Nastala chyba při ukládání šablony.';
$_lang['template_icon'] = 'Manager Icon Class';
$_lang['template_icon_desc'] = 'A CSS class to assign an icon (shown in the document trees) for all resources using this template. Font Awesome Free 5 classes such as “fa-home” may be used.';
$_lang['template_lock'] = 'Uzamknout šablonu pro úpravy';
$_lang['template_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Template.';
$_lang['template_locked_message'] = 'Tato šablona je uzamčena.';
$_lang['template_management_msg'] = 'V této části si můžete vybrat šablonu, kterou chcete upravit.';
$_lang['template_name_desc'] = 'Název šablony.';
$_lang['template_new'] = 'Create Template';
$_lang['template_no_tv'] = 'No TVs have been assigned to this template yet.';
$_lang['template_preview'] = 'Náhled obrázku';
$_lang['template_preview_desc'] = 'Used to preview the layout of this Template when creating a new Resource. (Minimum size: 335 x 236)';
$_lang['template_preview_source'] = 'Preview Image Media Source';
$_lang['template_preview_source_desc'] = 'Sets the basePath for this Template’s Preview Image to the one specified in the chosen Media Source. Choose “None” when specifying an absolute or other custom path to the file.';
$_lang['template_properties'] = 'Výchozí vlastnosti';
$_lang['template_reset_all'] = 'Resetovat všechny stránky na základní šablonu';
$_lang['template_reset_specific'] = 'Resetovat jenom "%s" stránky';
$_lang['template_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Template</em> as well as its content. The content must be HTML, either placed in the <em>Template Code</em> field below or in a static external file, and may include MODX tags. Note that changed or new templates won’t be visible in your site’s cached pages until the cache is emptied; however, you can use the preview function on a page to see the template in action.';
$_lang['template_tv_edit'] = 'Edit the sort order of the TVs';
$_lang['template_tv_msg'] = 'The <abbr title="Template Variables">TVs</abbr> assigned to this template are listed below.';
$_lang['templates'] = 'Šablony';
$_lang['tvt_err_nf'] = 'TV does not have access to the specified Template.';
$_lang['tvt_err_remove'] = 'An error occurred while trying to delete the TV from the template.';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['template_desc_category'] = $_lang['template_category_desc'];
$_lang['template_desc_description'] = $_lang['template_description_desc'];
$_lang['template_desc_name'] = $_lang['template_name_desc'];
$_lang['template_lock_msg'] = $_lang['template_lock_desc'];

// --tabs
$_lang['template_msg'] = $_lang['template_tab_general_desc'];
