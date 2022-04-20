<?php
/**
 * Template English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['access'] = 'Åtkomst';
$_lang['filter_by_category'] = 'Filtrera efter kategori...';
$_lang['rank'] = 'Rang';
$_lang['template'] = 'Mall';
$_lang['template_assignedtv_tab'] = 'Tilldelade mallvariabler';
$_lang['template_category_desc'] = 'Use to group Templates within the Elements tree.';
$_lang['template_code'] = 'Template Code (HTML)';
$_lang['template_delete_confirm'] = 'Är du säker på att du vill ta bort denna mall?';
$_lang['template_description_desc'] = 'Usage information for this Template shown in search results and as a tooltip in the Elements tree.';
$_lang['template_duplicate_confirm'] = 'Är du säker på att du vill duplicera denna mall?';
$_lang['template_edit_tab'] = 'Redigera mall';
$_lang['template_empty'] = '(tom)';
$_lang['template_err_default_template'] = 'Denna mall är angiven som standardmall. Ange en ny standardmall i MODX inställningar innan du tar bort denna mall.<br />';
$_lang['template_err_delete'] = 'Ett fel inträffade när mallen skulle tas bort.';
$_lang['template_err_duplicate'] = 'Ett fel inträffade när mallen skulle dupliceras.';
$_lang['template_err_ae'] = 'Det finns redan en mall med namnet "[[+name]]".';
$_lang['template_err_in_use'] = 'Denna mall används. Ange en ny mall för de dokument som använder mallen. Dokument som använder mallen:<br />';
$_lang['template_err_invalid_name'] = 'Mallnamnet är ogiltigt.';
$_lang['template_err_locked'] = 'Mallen är låst för redigering.';
$_lang['template_err_nf'] = 'Mallen kunde inte hittas!';
$_lang['template_err_ns'] = 'Ingen mall angiven.';
$_lang['template_err_ns_name'] = 'Ange ett namn på mallen.';
$_lang['template_err_remove'] = 'Ett fel inträffade när mallen skulle tas bort.';
$_lang['template_err_save'] = 'Ett fel inträffade när mallen skulle sparas.';
$_lang['template_icon'] = 'Manager Icon Class';
$_lang['template_icon_desc'] = 'A CSS class to assign an icon (shown in the document trees) for all resources using this template. Font Awesome Free 5 classes such as “fa-home” may be used.';
$_lang['template_lock'] = 'Lås mall för redigering';
$_lang['template_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Template.';
$_lang['template_locked_message'] = 'Denna mall är låst.';
$_lang['template_management_msg'] = 'Här kan du skapa en ny mall eller välja en redan befintlig för redigering.';
$_lang['template_name_desc'] = 'Mallens namn.';
$_lang['template_new'] = 'Skapa mall';
$_lang['template_no_tv'] = 'Inga mallvariabler har tilldelats den här mallen än.';
$_lang['template_preview'] = 'Preview Image';
$_lang['template_preview_desc'] = 'Used to preview the layout of this Template when creating a new Resource. (Minimum size: 335 x 236)';
$_lang['template_preview_source'] = 'Preview Image Media Source';
$_lang['template_preview_source_desc'] = 'Sets the basePath for this Template’s Preview Image to the one specified in the chosen Media Source. Choose “None” when specifying an absolute or other custom path to the file.';
$_lang['template_properties'] = 'Standardegenskaper';
$_lang['template_reset_all'] = 'Återställ alla sidor så de använder standardmallen';
$_lang['template_reset_specific'] = 'Återställ endast "%s" sidor';
$_lang['template_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Template</em> as well as its content. The content must be HTML, either placed in the <em>Template Code</em> field below or in a static external file, and may include MODX tags. Note that changed or new templates won’t be visible in your site’s cached pages until the cache is emptied; however, you can use the preview function on a page to see the template in action.';
$_lang['template_title'] = 'Skapa/redigera mallar';
$_lang['template_tv_edit'] = 'Redigera mallvariablernas sorteringsordning';
$_lang['template_tv_msg'] = 'Mallvariablerna som tilldelats den här mallen visas nedan.';
$_lang['template_untitled'] = 'Namnlös mall';
$_lang['templates'] = 'Mallar';
$_lang['tvt_err_nf'] = 'Mallvariabeln har inte tillgång till den angivna mallen.';
$_lang['tvt_err_remove'] = 'Ett fel inträffade när mallvariabeln skulle tas bort från mallen.';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['template_desc_category'] = $_lang['template_category_desc'];
$_lang['template_desc_description'] = $_lang['template_description_desc'];
$_lang['template_desc_name'] = $_lang['template_name_desc'];
$_lang['template_lock_msg'] = $_lang['template_lock_desc'];

// --tabs
$_lang['template_msg'] = $_lang['template_tab_general_desc'];
