<?php
/**
 * Template English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['access'] = 'Adgang';
$_lang['filter_by_category'] = 'Filtrer efter kategori...';
$_lang['rank'] = 'Rang';
$_lang['template'] = 'Skabelon';
$_lang['template_assignedtv_tab'] = 'Assigned TVs';
$_lang['template_category_desc'] = 'Use to group Templates within the Elements tree.';
$_lang['template_code'] = 'Template Code (HTML)';
$_lang['template_delete_confirm'] = 'Are you sure you want to delete this template?';
$_lang['template_description_desc'] = 'Usage information for this Template shown in search results and as a tooltip in the Elements tree.';
$_lang['template_duplicate_confirm'] = 'Are you sure you want to duplicate this template?';
$_lang['template_edit_tab'] = 'Rediger skabelon';
$_lang['template_empty'] = '(tom)';
$_lang['template_err_default_template'] = 'This template is set as the default template. Please choose a different default template in the MODX configuration before deleting this template.<br />';
$_lang['template_err_delete'] = 'An error occurred while trying to delete the template.';
$_lang['template_err_duplicate'] = 'An error occured while duplicating the template.';
$_lang['template_err_ae'] = 'A template already exists with the name "[[+name]]".';
$_lang['template_err_in_use'] = 'This template is in use. Please set the documents using the template to another template. Documents using this template:<br />';
$_lang['template_err_invalid_name'] = 'Template name is invalid.';
$_lang['template_err_locked'] = 'Template is locked from editing.';
$_lang['template_err_nf'] = 'Template not found!';
$_lang['template_err_ns'] = 'Template not specified.';
$_lang['template_err_ns_name'] = 'Please specify a name for the template.';
$_lang['template_err_remove'] = 'An error occurred while trying to delete the template.';
$_lang['template_err_save'] = 'An error occurred while saving the template.';
$_lang['template_icon'] = 'Manager Icon Class';
$_lang['template_icon_desc'] = 'A CSS class to assign an icon (shown in the document trees) for all resources using this template. Font Awesome Free 5 classes such as “fa-home” may be used.';
$_lang['template_lock'] = 'Lock template for editing';
$_lang['template_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Template.';
$_lang['template_locked_message'] = 'This template is locked.';
$_lang['template_management_msg'] = 'Here you can choose which template you wish to edit.';
$_lang['template_name_desc'] = 'The name of this Template.';
$_lang['template_new'] = 'Create Template';
$_lang['template_no_tv'] = 'No TVs have been assigned to this template yet.';
$_lang['template_preview'] = 'Preview Image';
$_lang['template_preview_desc'] = 'Used to preview the layout of this Template when creating a new Resource. (Minimum size: 335 x 236)';
$_lang['template_preview_source'] = 'Preview Image Media Source';
$_lang['template_preview_source_desc'] = 'Sets the basePath for this Template’s Preview Image to the one specified in the chosen Media Source. Choose “None” when specifying an absolute or other custom path to the file.';
$_lang['template_properties'] = 'Standardegenskaber';
$_lang['template_reset_all'] = 'Reset all pages to use Default template';
$_lang['template_reset_specific'] = 'Reset only \'%s\' pages';
$_lang['template_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Template</em> as well as its content. The content must be HTML, either placed in the <em>Template Code</em> field below or in a static external file, and may include MODX tags. Note that changed or new templates won’t be visible in your site’s cached pages until the cache is emptied; however, you can use the preview function on a page to see the template in action.';
$_lang['template_tv_edit'] = 'Edit the sort order of the TVs';
$_lang['template_tv_msg'] = 'The <abbr title="Template Variables">TVs</abbr> assigned to this template are listed below.';
$_lang['templates'] = 'Skabeloner';
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
