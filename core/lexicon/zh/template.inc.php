<?php
/**
 * Template English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['access'] = '访问';
$_lang['filter_by_category'] = 'Filter by Category...';
$_lang['rank'] = '排名';
$_lang['template'] = '模板';
$_lang['template_assignedtv_tab'] = 'Assigned TVs';
$_lang['template_category_desc'] = 'Use to group Templates within the Elements tree.';
$_lang['template_code'] = 'Template Code (HTML)';
$_lang['template_delete_confirm'] = '确定要删除此模板?';
$_lang['template_description_desc'] = 'Usage information for this Template shown in search results and as a tooltip in the Elements tree.';
$_lang['template_duplicate_confirm'] = '确定要复制此模板？';
$_lang['template_edit_tab'] = '编辑模板';
$_lang['template_empty'] = '(空)';
$_lang['template_err_default_template'] = '此模板已被设为默认模板，请在删除此模板前，先在MODX设置中设置其他的模板为默认模板。';
$_lang['template_err_delete'] = 'An error occurred while trying to delete the template.';
$_lang['template_err_duplicate'] = '复制模板时出错。';
$_lang['template_err_ae'] = '名为"[[+name]]"的模板已存在。';
$_lang['template_err_in_use'] = '此模板正在使用中。请将正在使用此模板的文档设为其他的模板。以下是正在使用此模板的文档：<br />';
$_lang['template_err_invalid_name'] = 'Template name is invalid.';
$_lang['template_err_locked'] = '模板处于锁定状态，无法编辑。';
$_lang['template_err_nf'] = '找不到模板 ！';
$_lang['template_err_ns'] = '未指定模板。';
$_lang['template_err_ns_name'] = '请指定模板的名称。';
$_lang['template_err_remove'] = 'An error occurred while trying to delete the template.';
$_lang['template_err_save'] = '保存模板时出错。';
$_lang['template_icon'] = 'Manager Icon Class';
$_lang['template_icon_desc'] = 'A CSS class to assign an icon (shown in the document trees) for all resources using this template. Font Awesome Free 5 classes such as “fa-home” may be used.';
$_lang['template_lock'] = '锁定模板';
$_lang['template_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Template.';
$_lang['template_locked_message'] = '此模板已被锁定。';
$_lang['template_management_msg'] = '您可在此选择要编辑的模板。';
$_lang['template_name_desc'] = '此模板的名称。';
$_lang['template_new'] = 'Create Template';
$_lang['template_no_tv'] = 'No TVs have been assigned to this template yet.';
$_lang['template_preview'] = 'Preview Image';
$_lang['template_preview_desc'] = 'Used to preview the layout of this Template when creating a new Resource. (Minimum size: 335 x 236)';
$_lang['template_preview_source'] = 'Preview Image Media Source';
$_lang['template_preview_source_desc'] = 'Sets the basePath for this Template’s Preview Image to the one specified in the chosen Media Source. Choose “None” when specifying an absolute or other custom path to the file.';
$_lang['template_properties'] = '默认属性';
$_lang['template_reset_all'] = '重置所有网页使用默认模板';
$_lang['template_reset_specific'] = '只重置\'%s\'网页';
$_lang['template_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Template</em> as well as its content. The content must be HTML, either placed in the <em>Template Code</em> field below or in a static external file, and may include MODX tags. Note that changed or new templates won’t be visible in your site’s cached pages until the cache is emptied; however, you can use the preview function on a page to see the template in action.';
$_lang['template_tv_edit'] = 'Edit the sort order of the TVs';
$_lang['template_tv_msg'] = 'The <abbr title="Template Variables">TVs</abbr> assigned to this template are listed below.';
$_lang['templates'] = '模板';
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
