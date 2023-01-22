<?php
/**
 * Template English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['access'] = 'Hozzáférés';
$_lang['filter_by_category'] = 'Szűrés kategória alapján...';
$_lang['rank'] = 'Rangsor';
$_lang['template'] = 'Sablon';
$_lang['template_assignedtv_tab'] = 'Hozzárendelt sablonváltozók';
$_lang['template_category_desc'] = 'Use to group Templates within the Elements tree.';
$_lang['template_code'] = 'Template Code (HTML)';
$_lang['template_delete_confirm'] = 'Biztos törölni szeretné a sablont?';
$_lang['template_description_desc'] = 'Usage information for this Template shown in search results and as a tooltip in the Elements tree.';
$_lang['template_duplicate_confirm'] = 'Biztosan új példányt hoz létre a sablonból?';
$_lang['template_edit_tab'] = 'Sablon szerkesztése';
$_lang['template_empty'] = '(üres)';
$_lang['template_err_default_template'] = 'Jelenleg ez a sablon az alapértelmezett. Kérjük, válasszon másik alapértelmezett sablont a MODX beállításokban, mielőtt törli ezt a sablont.<br />';
$_lang['template_err_delete'] = 'Hiba történt a sablon törlése közben.';
$_lang['template_err_duplicate'] = 'Hiba történt a sablon másolatának létrehozásakor.';
$_lang['template_err_ae'] = 'Már létezik sablon "[[+name]]" néven.';
$_lang['template_err_in_use'] = 'A sablon használatban van. Kérjük, hogy a sablont használó dokumentumokat kapcsolja másik sablonhoz. Ezt a sablont használó dokumentumok:<br />';
$_lang['template_err_invalid_name'] = 'A sablon neve érvénytelen.';
$_lang['template_err_locked'] = 'A sablon nem szerkeszthető.';
$_lang['template_err_nf'] = 'Sablon nem található!';
$_lang['template_err_ns'] = 'Sablon nincs megadva.';
$_lang['template_err_ns_name'] = 'Kérjük, adja meg a sablon nevét.';
$_lang['template_err_remove'] = 'Hiba történt a sablon törlése közben.';
$_lang['template_err_save'] = 'Hiba történt a sablon mentésekor.';
$_lang['template_icon'] = 'Manager Icon Class';
$_lang['template_icon_desc'] = 'A CSS class to assign an icon (shown in the document trees) for all resources using this template. Font Awesome Free 5 classes such as “fa-home” may be used.';
$_lang['template_lock'] = 'Sablon zárolása szerkesztésre';
$_lang['template_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Template.';
$_lang['template_locked_message'] = 'Ez a sablon zárolt.';
$_lang['template_management_msg'] = 'Itt választhatja ki a módosítani kívánt sablont.';
$_lang['template_name_desc'] = 'Sablon neve.';
$_lang['template_new'] = 'Sablon létrehozása';
$_lang['template_no_tv'] = 'Még nincsenek sablonváltozók rendelve ehhez a sablonhoz.';
$_lang['template_preview'] = 'Preview Image';
$_lang['template_preview_desc'] = 'Used to preview the layout of this Template when creating a new Resource. (Minimum size: 335 x 236)';
$_lang['template_preview_source'] = 'Preview Image Media Source';
$_lang['template_preview_source_desc'] = 'Sets the basePath for this Template’s Preview Image to the one specified in the chosen Media Source. Choose “None” when specifying an absolute or other custom path to the file.';
$_lang['template_properties'] = 'Alaptulajdonságok';
$_lang['template_reset_all'] = 'Az alapértelmezett sablont használó összes oldal visszaállítása';
$_lang['template_reset_specific'] = 'Csak \'%s\' oldalak visszaállítása';
$_lang['template_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Template</em> as well as its content. The content must be HTML, either placed in the <em>Template Code</em> field below or in a static external file, and may include MODX tags. Note that changed or new templates won’t be visible in your site’s cached pages until the cache is emptied; however, you can use the preview function on a page to see the template in action.';
$_lang['template_tv_edit'] = 'A sablonváltozók sorrendjének szerkesztése';
$_lang['template_tv_msg'] = 'Az ehhez a sablonhoz tartozó <abbr title="Template Variables">sablonváltozók</abbr> alább felsorolva.';
$_lang['templates'] = 'Sablonok';
$_lang['tvt_err_nf'] = 'A sablonváltozó nem fér hozzá a megadott sablonhoz.';
$_lang['tvt_err_remove'] = 'Hiba történt a sablonváltozó sablonból való törlése közben.';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['template_desc_category'] = $_lang['template_category_desc'];
$_lang['template_desc_description'] = $_lang['template_description_desc'];
$_lang['template_desc_name'] = $_lang['template_name_desc'];
$_lang['template_lock_msg'] = $_lang['template_lock_desc'];

// --tabs
$_lang['template_msg'] = $_lang['template_tab_general_desc'];
