<?php
/**
 * Resource English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['access'] = 'Hozzáférés';
$_lang['cache_output'] = 'Gyorsítótár kimenete';
$_lang['changes'] = 'Változások';
$_lang['class_key'] = 'Osztálykulcs';
$_lang['context'] = 'Környezet';
$_lang['document'] = 'Dokumentum';
$_lang['document_create'] = 'Dokumentum létrehozása';
$_lang['document_create_here'] = 'Dokumentum';
$_lang['document_new'] = 'Dokumentum létrehozása';
$_lang['documents'] = 'Dokumentumok';
$_lang['duplicate_uri_found'] = 'Resource [[+id]] is already using the URI [[+uri]]. Please enter a unique alias or use Freeze URI to manually override it.';
$_lang['empty_template'] = '(üres)';
$_lang['general'] = 'Általános';
$_lang['markup'] = 'Markup/Structure';
$_lang['none'] = 'Egyik sem';
$_lang['page_settings'] = 'Beállítások';
$_lang['preview'] = 'Előnézet';
$_lang['resource_access_message'] = 'Here you can select which Resource Groups this Resource belongs to.';
$_lang['resource_add_children_access_denied'] = 'You do not have permission to create a Resource in this location.';
$_lang['resource_alias'] = 'Resource Alias';
$_lang['resource_alias_help'] = 'Az erőforrás hivatkozási neve. Ez által az erőforrás elérhető így:<br /><br />http://yourserver/alias<br /><br /><strong>Fontos</strong> Csak keresőbarát URL-ek használata esetén működik.';
$_lang['resource_alias_visible'] = 'Használja a jelenlegi hivatkozási nevet a hivatkozási útvonalban';
$_lang['resource_alias_visible_help'] = 'Ennek a forrásnak a hivatkozási neve bekerült a Barátságos URL hivatkozási útvonalba';
$_lang['resource_change_template_confirm'] = 'Are you sure you want to change the Template? <br /><br />WARNING: This will <b>only temporarily store</b> your prior changes and reload the page; ensure you are ready to do so before proceeding. After the page has reloaded, you will need to save when you are ready to save the Template change.';
$_lang['resource_cacheable'] = 'Gyorsítótárazható';
$_lang['resource_cacheable_help'] = 'When enabled, the resource will be saved to the cache.';
$_lang['resource_cancel_dirty_confirm'] = 'You have changes pending; are you sure you want to cancel?';
$_lang['resource_class_key_help'] = 'This is the class key of the resource, showing its MODX type.';
$_lang['resource_content'] = 'Tartalom';
$_lang['resource_contentdispo'] = 'Content Disposition';
$_lang['resource_contentdispo_help'] = 'Use the content disposition field to specify how this resource will be handled by the web browser. For file downloads select the Attachment option.';
$_lang['resource_content_type'] = 'Tartalomtípus';
$_lang['resource_content_type_help'] = 'The content type for this resource. If you\'re not sure which content type the resource should have, just leave it as text/html.';
$_lang['resource_create_access_denied'] = 'You do not have permission to create a Resource.';
$_lang['resource_create_here'] = 'Erőforrás';
$_lang['resource_createdby'] = 'Létrehozta';
$_lang['resource_createdon'] = 'Létrehozás dátuma';
$_lang['resource_delete'] = 'Törlés';
$_lang['resource_delete_confirm'] = 'Biztosan törli a(z) "[[+resource]]" erőforrást?<br />FONTOS: Minden alárendelt erőforrás is törlődik!';
$_lang['resource_description'] = 'Leírás';
$_lang['resource_description_help'] = 'This is an optional description of the resource.';
$_lang['resource_duplicate'] = 'Kettőzés';
$_lang['resource_edit'] = 'Szerkeszt';
$_lang['resource_editedby'] = 'Szerkesztette';
$_lang['resource_editedon'] = 'Szerkesztve:';
$_lang['resource_err_change_parent_to_folder'] = 'An error occurred while attempting to change the resource\'s parent to a folder.';
$_lang['resource_err_class'] = 'The resource is not a valid [[+class]].';
$_lang['resource_err_create'] = 'An error occurred while trying to create the resource.';
$_lang['resource_err_delete'] = 'An error occurred while trying to delete the resource.';
$_lang['resource_err_delete_children'] = 'Hiba történt az erőforrás alárendelt elemeinek törlése közben.';
$_lang['resource_err_delete_container_sitestart'] = 'The resource you are trying to delete is a container containing resource [[+id]]. This resource is registered as the \'Site start\' resource, and cannot be deleted. Please assign another resource as your \'Site start\' resource and try again.';
$_lang['resource_err_delete_container_siteunavailable'] = 'The resource you are trying to delete is a folder containing resource [[+id]]. This resource is registered as the \'Site unavailable page\' resource, and cannot be deleted. Please assign another resource as your \'Site unavailable page\' resource and try again.';
$_lang['resource_err_delete_sitestart'] = 'The resource is \'Site start\' and cannot be deleted!';
$_lang['resource_err_delete_siteunavailable'] = 'The resource is used as the \'Site unavailable page\' and cannot be deleted!';
$_lang['resource_err_duplicate'] = 'An error occurred while duplicating the resource.';
$_lang['resource_err_move_to_child'] = 'You cannot move a Resource to below one of its own children.';
$_lang['resource_err_move_sitestart'] = 'The resource is linked to the site_start variable and cannot be moved to another context!';
$_lang['resource_err_nf'] = 'Erőforrás nem található.';
$_lang['resource_err_nfs'] = '[[+id]] azonosítójú erőforrás nem található';
$_lang['resource_err_ns'] = 'Erőforrás nincs megadva.';
$_lang['resource_err_own_parent'] = 'The resource cannot be its own parent.';
$_lang['resource_err_publish']  = 'An error occurred while trying to publish the resource.';
$_lang['resource_err_new_parent_nf'] = 'New parent resource with id [[+id]] not found.';
$_lang['resource_err_remove'] = 'An error occurred while trying to delete the resource.';
$_lang['resource_err_save'] = 'An error occurred while trying to save the resource.';
$_lang['resource_err_select_parent'] = 'Please select a parent resource.';
$_lang['resource_err_symlink_target_invalid'] = 'A szimbolikus link célja nem tartalmaz egy egész értéket.';
$_lang['resource_err_symlink_target_nf'] = 'Nem hivatkozhat szimbolikus link egy nem létező erőforrásra.';
$_lang['resource_err_symlink_target_self'] = 'A szimbolikus link nem mutathat magára.';
$_lang['resource_err_undelete'] = 'An error occurred while trying to undelete the resource.';
$_lang['resource_err_undelete_children'] = 'An error occurred while trying to undelete the children of the resource.';
$_lang['resource_err_unpublish'] = 'An error occurred while trying to unpublish the resource.';
$_lang['resource_err_unpublish_sitestart'] = 'The resource is linked to the site_start variable and cannot be unpublished!';
$_lang['resource_err_unpublish_sitestart_dates'] = 'The resource is linked to the site_start variable and cannot have publish or unpublish dates set!';
$_lang['resource_err_weblink_target_nf'] = 'Nem mutathat webes hivatkozás egy nem létező erőforrásra.';
$_lang['resource_err_weblink_target_self'] = 'A webes hivatkozás nem mutathat magára.';
$_lang['resource_folder'] = 'Tároló';
$_lang['resource_folder_help'] = 'Check this to make the Resource also act as a Container for other Resources. A \'Container\' is like a folder, only it can also have content.';
$_lang['resource_hide_children_in_tree'] = 'Alárendelt elemek elrejtése';
$_lang['resource_hide_children_in_tree_help'] = 'Jelölje be az alárendelt erőforrások elrejtéséhez az erőforrások fában.';
$_lang['resource_show_in_tree'] = 'Mutassa a faszerkezetben';
$_lang['resource_show_in_tree_help'] = 'Törölje a kijelölést az erőforrás elrejtéséhez a fában. <b>Figyelem!</b> Ezután ezt az erőforrást nem tudja az erőforrásfán keresztül szerkeszteni, csak közvetlenül megnyitva.';
$_lang['resource_group_resource_err_ae'] = 'Az erőforrás már része ennek az erőforráscsoportnak.';
$_lang['resource_group_resource_err_nf'] = 'Az erőforrás nem része ennek az erőforráscsoportnak.';
$_lang['resource_hide_from_menus'] = 'Elrejtés a menükből';
$_lang['resource_hide_from_menus_help'] = 'When enabled, the resource will <b>not</b> be available for use inside a web menu. Please note that some Menu Builders might choose to ignore this option.';
$_lang['resource_link_attributes'] = 'Link Attributes';
$_lang['resource_link_attributes_help'] = 'Attributes for the link for this resource, such as target= or rel=.';
$_lang['resource_locked_by'] = 'Locked by [[+user]]';
$_lang['resource_longtitle'] = 'Hosszú cím';
$_lang['resource_longtitle_help'] = 'This is a longer title for your resource. It is handy for search engines, and might be more descriptive for the resource.';
$_lang['resource_menuindex'] = 'Menü mutatója';
$_lang['resource_menuindex_help'] = 'This is the order of the resource in the tree. It is usually used for ordering purposes in displaying resources dynamically. Some components might choose to ignore this setting.';
$_lang['resource_menutitle'] = 'Menü címe';
$_lang['resource_menutitle_help'] = 'Menu title is a field you can use to display a short title for the resource inside your menu snippet(s).';
$_lang['resource_new'] = 'Erőforrás létrehozása';
$_lang['resource_notcached'] = 'This resource has not (yet) been cached.';
$_lang['resource_pagetitle'] = 'Cím';
$_lang['resource_pagetitle_help'] = 'The name/title of the resource. Try to avoid using backslashes in the name!';
$_lang['resource_parent'] = 'Fölérendelt erőforrás';
$_lang['resource_parent_help'] = 'The parent resource\'s ID number.';
$_lang['resource_parent_select_node'] = 'Please select a node in the tree to the left.';
$_lang['resource_publish'] = 'Közzétesz';
$_lang['resource_publish_confirm'] = 'Ha most teszi közzé az erőforrást, minden korábban beállított közzétételi vagy visszavonási dátumot töröl. Ha szeretné beállítani vagy megtartani ezeket a dátumokat, válassza a közzététel helyett az erőforrás szerkesztését.<br /><br />Folytatja?';
$_lang['resource_publishdate'] = 'Közzététel dátuma';
$_lang['resource_publishdate_help'] = 'If you set a publish date, the resource will be published as soon as the publish date is reached. Click on the calendar icon to select a date, or leave it blank to set it so the resource is never automatically published.';
$_lang['resource_published'] = 'Közzétéve';
$_lang['resource_published_help'] = 'When published, the resource is available to the public immediately after saving it.';
$_lang['resource_publishedby'] = 'Közzétette';
$_lang['resource_publishedon'] = 'Közzétéve';
$_lang['resource_publishedon_help'] = 'The date the resource was published.';
$_lang['resource_refresh'] = 'Frissítés';
$_lang['resource_richtext'] = 'Formázott szöveg';
$_lang['resource_richtext_help'] = 'When enabled, MODX will use the rich text editor for editing resources. If your resources contain JavaScript and forms, uncheck this to edit in HTML-only mode so the editor won\'t mess your resources up.';
$_lang['resource_searchable'] = 'Kereshető';
$_lang['resource_searchable_help'] = 'When enabled, the resource is able to be searched. This setting can also be used for other purposes in your snippets.';
$_lang['resource_settings'] = 'Erőforrásbeállítások';
$_lang['resource_status'] = 'Állapot';
$_lang['resource_status_help'] = 'If published, the resource is available to the public immediately after saving it. Otherwise, it is hidden from the public site.';
$_lang['resource_summary'] = 'Summary (introtext)';
$_lang['resource_summary_help'] = 'A brief summary of the resource.';
$_lang['resource_syncsite'] = 'Gyorsítótár ürítése';
$_lang['resource_syncsite_help'] = 'When enabled, this will make MODX empty the cache after you save the resource. This way your visitors will not see an older version of the resource.';
$_lang['resource_template'] = 'Uses Template';
$_lang['resource_template_help'] = 'The template in use by the resource.';
$_lang['resource_undelete'] = 'Törlés visszavonása';
$_lang['resource_unpublish'] = 'Közzététel visszavonása';
$_lang['resource_unpublish_confirm'] = 'Ha most vonja vissza az erőforrást, minden korábban beállított közzétételi vagy visszavonási dátumot töröl. Ha szeretné beállítani vagy megtartani ezeket a dátumokat, válassza a visszavonás helyett az erőforrás szerkesztését.<br /><br />Folytatja?';
$_lang['resource_unpublishdate'] = 'Közzététel visszavonásának napja';
$_lang['resource_unpublishdate_help'] = 'If you set an unpublish date, the resource will be unpublished as soon as the unpublish date is reached. Click on the calendar icon to select a date, or leave it blank to set it so the resource is never automatically unpublished.';
$_lang['resource_unpublished'] = 'Közzétételből visszavont';
$_lang['resource_untitled'] = 'Névtelen erőforrás';
$_lang['resource_uri'] = 'URI';
$_lang['resource_uri_help'] = 'The full relative URL for this Resource.';
$_lang['resource_uri_override'] = 'Freeze URI';
$_lang['resource_uri_override_help'] = 'Checking this will allow you to freeze the URI for this Resource at the value in the textbox below.';
$_lang['resource_with_id_not_found'] = 'Resource with ID %s not found!';
$_lang['show_sort_options'] = 'Rendezési beállítások mutatása';
$_lang['site_schedule'] = 'Ütemezés';
$_lang['site_schedule_desc'] = 'This shows the current resources that are scheduled to publish or unpublished on specified dates. You may toggle the current view by clicking on the toolbar button.';
$_lang['source'] = 'Forrás';
$_lang['static_resource'] = 'Állandó erőforrás';
$_lang['static_resource_create_here'] = 'Állandó erőforrás';
$_lang['static_resource_new'] = 'Állandó erőforrás létrehozása';
$_lang['status'] = 'Állapot';
$_lang['symlink'] = 'Symlink';
$_lang['symlink_create'] = 'Create Symlink';
$_lang['symlink_create_here'] = 'Symlink';
$_lang['symlink_help'] = 'The address of the object you wish to reference with this Symlink. If you want to point to an existing MODX Resource, enter the ID here.';
$_lang['symlink_message'] = 'A symlink is a symbolic link to another resource in your site which is forwarded to without changing the URL.';
$_lang['symlink_new'] = 'Create Symlink';
$_lang['template_variables'] = 'Sablonváltozók';
$_lang['untitled_resource'] = 'Névtelen erőforrás';
$_lang['weblink'] = 'Webes hivatkozás';
$_lang['weblink_create'] = 'Webes hivatkozás létrehozása';
$_lang['weblink_create_here'] = 'Webes hivatkozás';
$_lang['weblink_help'] = 'Az objektum teljes URL címe, amelyiket ezzel a hivatkozással kíván elérni ( https://example.com/page ). Ha egy létező MODX erőforrásra akar hivatkozni, adja meg itt az ID-t.';
$_lang['weblink_message'] = 'Egy webhivatkozás egy világhálón elérhető címre mutat. Ez lehet egy dokumentum a MODX-en belül, egy oldal egy másik webhelyen, vagy egy kép, vagy más állomány az világhálón.<p>';
$_lang['weblink_new'] = 'Webes hivatkozás létrehozása';
$_lang['weblink_response_code'] = 'Válaszkód';
$_lang['weblink_response_code_help'] = 'The HTTP response code that should be sent for the weblink.';

$_lang['resource_right_top_title'] = 'Közzététel';
$_lang['resource_right_middle_title'] = 'Sablon';
$_lang['resource_right_bottom_title'] = 'Viselkedés a menüben';
