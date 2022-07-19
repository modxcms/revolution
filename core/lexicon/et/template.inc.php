<?php
/**
 * Template English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['access'] = 'Juurdepääs';
$_lang['filter_by_category'] = 'Filtreeri Kategooria järgi...';
$_lang['rank'] = 'Rank';
$_lang['template'] = 'Template';
$_lang['template_assignedtv_tab'] = 'Assigned TVs';
$_lang['template_category_desc'] = 'Use to group Templates within the Elements tree.';
$_lang['template_code'] = 'Template Code (HTML)';
$_lang['template_delete_confirm'] = 'Olete kindel, et soovite kustutada selle template?';
$_lang['template_description_desc'] = 'Usage information for this Template shown in search results and as a tooltip in the Elements tree.';
$_lang['template_duplicate_confirm'] = 'Olete kindel, et soovite dubleerida selle template?';
$_lang['template_edit_tab'] = 'Edit Template';
$_lang['template_empty'] = '(tühi)';
$_lang['template_err_default_template'] = 'See template on määratud vaikimisi templateks. Palun valige teine vaikimisi template MODX-i konfiguratioonist, ennem kui kustutate selle template.<br />';
$_lang['template_err_delete'] = 'An error occurred while trying to delete the template.';
$_lang['template_err_duplicate'] = 'Tekkis viga template dubleerimsiel.';
$_lang['template_err_ae'] = 'Template nimega "[[+name]]" juba eksisteerib.';
$_lang['template_err_in_use'] = 'See template on juba kasutusel. Palun määrake dokumentides teine template. Dokumentid, mis kasutavad seda templatet:<br />';
$_lang['template_err_invalid_name'] = 'Template name is invalid.';
$_lang['template_err_locked'] = 'Template on lukusustatud.';
$_lang['template_err_nf'] = 'Templatet ei leitud!';
$_lang['template_err_ns'] = 'Templatet ei olnud määratud.';
$_lang['template_err_ns_name'] = 'Palun määrake template nimi.';
$_lang['template_err_remove'] = 'An error occurred while trying to delete the template.';
$_lang['template_err_save'] = 'Tekkis viga template salvestamisel.';
$_lang['template_icon'] = 'Manager Icon Class';
$_lang['template_icon_desc'] = 'A CSS class to assign an icon (shown in the document trees) for all resources using this template. Font Awesome Free 5 classes such as “fa-home” may be used.';
$_lang['template_lock'] = 'Lukusta template';
$_lang['template_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Template.';
$_lang['template_locked_message'] = 'See template on lukustatud.';
$_lang['template_management_msg'] = 'Siit saate valida, millist templatet soovite muuta.';
$_lang['template_name_desc'] = 'The name of this Template.';
$_lang['template_new'] = 'Create Template';
$_lang['template_no_tv'] = 'No TVs have been assigned to this template yet.';
$_lang['template_preview'] = 'Preview Image';
$_lang['template_preview_desc'] = 'Used to preview the layout of this Template when creating a new Resource. (Minimum size: 335 x 236)';
$_lang['template_preview_source'] = 'Preview Image Media Source';
$_lang['template_preview_source_desc'] = 'Sets the basePath for this Template’s Preview Image to the one specified in the chosen Media Source. Choose “None” when specifying an absolute or other custom path to the file.';
$_lang['template_properties'] = 'Tava Omadused';
$_lang['template_reset_all'] = 'Reseti kõik lehed kasutamaks Vaikimisi templatet';
$_lang['template_reset_specific'] = 'Reseti ainult \'%s\' lehed';
$_lang['template_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Template</em> as well as its content. The content must be HTML, either placed in the <em>Template Code</em> field below or in a static external file, and may include MODX tags. Note that changed or new templates won’t be visible in your site’s cached pages until the cache is emptied; however, you can use the preview function on a page to see the template in action.';
$_lang['template_tv_edit'] = 'Edit the sort order of the TVs';
$_lang['template_tv_msg'] = 'The <abbr title="Template Variables">TVs</abbr> assigned to this template are listed below.';
$_lang['templates'] = 'Templated';
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
