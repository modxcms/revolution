
<span class="fakefieldsettitle">{$_lang.page_data_general}</span><br />
<div class="fakefieldset">
<ul class="no_list">
<li>
	<input name="frames" id="r_frames" type="checkbox" checked="checked" disabled="disabled" />
	<label for="r_frames">{$_lang.role_frames}</label>
</li>
<li>
	<input name="home" id="r_home" type="checkbox" checked="checked" disabled="disabled" />
	<label for="r_home">{$_lang.role_home}</label>
</li>
<li>
	<input name="messages" id="r_messages" type="checkbox" {if $role->messages EQ 1}checked="checked"{/if} />
	<label for="r_messages">{$_lang.role_messages}</label>
</li>
<li>
	<input name="logout" id="r_logout" type="checkbox" checked="checked" disabled="disabled" />
	<label for="r_logout">{$_lang.role_logout}</label>
</li>
<li>
	<input name="help" id="r_help" type="checkbox" checked="checked" disabled="disabled" />
	<label for="r_help">{$_lang.role_help}</label>
</li>
<li>
	<input name="action_ok" id="r_action_ok" type="checkbox" checked="checked" disabled="disabled" />
	<label for="r_action_ok">{$_lang.role_actionok}</label>
</li>
<li>
	<input name="error_dialog" id="r_error_dialog" type="checkbox" checked="checked" disabled="disabled" />
	<label for="r_error_dialog">{$_lang.role_errors}</label>
</li>
<li>
	<input name="about" id="r_about" type="checkbox" checked="checked" disabled="disabled" />
	<label for="r_about">{$_lang.role_about}</label>
</li>
<li>
	<input name="credits" id="r_credits" type="checkbox" checked="checked" disabled="disabled" />
	<label for="r_credits">{$_lang.role_credits}</label>
</li>
<li>
	<input name="change_password" id="r_change_password" type="checkbox" {if $role->change_password EQ 1}checked="checked"{/if} />
	<label for="r_change_password">{$_lang.role_change_password}</label>
</li>
<li>
	<input name="save_password" id="r_save_password" type="checkbox" {if $role->save_password EQ 1}checked="checked"{/if} />
	<label for="r_save_password">{$_lang.role_save_password}</label>
</li>
</ul>
</div>
<br /><br />

<span class="fakefieldsettitle">{$_lang.role_content_management}</span><br />
<div class="fakefieldset">
<ul class="no_list">
<li>
	<input name="view_document" id="r_view_document" type="checkbox" checked="checked" disabled="disabled" />
	<label for="r_view_document">{$_lang.role_view_docdata}</label>
</li>
<li>
	<input name="new_document" id="r_new_document" type="checkbox" {if $role->new_document EQ 1}checked="checked"{/if} />
	<label for="r_new_document">{$_lang.role_create_doc}</label>
</li>
<li>
	<input name="edit_document" id="r_edit_document" type="checkbox" {if $role->edit_document EQ 1}checked="checked"{/if} />
	<label for="r_edit_document">{$_lang.role_edit_doc}</label>
</li>
<li>
	<input name="save_document" id="r_save_document" type="checkbox" {if $role->save_document EQ 1}checked="checked"{/if} />
	<label for="r_save_document">{$_lang.role_save_doc}</label>
</li>
<li>
	<input name="publish_document" id="r_publish_document" type="checkbox" {if $role->publish_document EQ 1}checked="checked"{/if} />
	<label for="r_publish_document">{$_lang.role_publish_doc}</label>
</li>
<li>
	<input name="delete_document" id="r_delete_document" type="checkbox" {if $role->delete_document EQ 1}checked="checked"{/if} />
	<label for="r_delete_document">{$_lang.role_delete_doc}</label>
</li>
<li>
	<input name="edit_doc_metatags" id="r_edit_doc_metatags" type="checkbox" {if $role->edit_doc_metatags EQ 1}checked="checked"{/if} />
	<label for="r_edit_doc_metatags">{$_lang.role_edit_doc_metatags}</label>
</li>
<li>
	<input name="empty_cache" id="r_empty_cache" type="checkbox" checked="checked" disabled="disabled" />
	<label for="r_empty_cache">{$_lang.role_cache_refresh}</label>
</li>
<li>
	<input name="view_unpublished" id="r_view_unpublished" type="checkbox" {if $role->view_unpublished EQ 1}checked="checked"{/if} />
	<label for="r_view_unpublished">{$_lang.role_view_unpublished}</label>
</li>
</ul>
</div>
<br /><br />

<span class="fakefieldsettitle">{$_lang.role_template_management}</span><br />
<div class="fakefieldset">
<ul class="no_list">
<li>
	<input name="new_template" id="r_new_template" type="checkbox" {if $role->new_template EQ 1}checked="checked"{/if} />
	<label for="r_new_template">{$_lang.role_create_template}</label>
</li>
<li>
	<input name="edit_template" id="r_edit_template" type="checkbox" {if $role->edit_template EQ 1}checked="checked"{/if} />
	<label for="r_edit_template">{$_lang.role_edit_template}</label>
</li>
<li>
	<input name="save_template" id="r_save_template" type="checkbox" {if $role->save_template EQ 1}checked="checked"{/if} />
	<label for="r_save_template">{$_lang.role_save_template}</label>
</li>
<li>
	<input name="delete_template" id="r_delete_template" type="checkbox" {if $role->delete_template EQ 1}checked="checked"{/if} />
	<label for="r_delete_template">{$_lang.role_delete_template}</label>
</li>
</ul>
</div>
<br /><br />

<span class="fakefieldsettitle">{$_lang.role_snippet_management}</span><br />
<div class="fakefieldset">
<ul class="no_list">
<li>
	<input name="new_snippet" id="r_new_snippet" type="checkbox" {if $role->new_snippet EQ 1}checked="checked"{/if} />
	<label for="r_new_snippet">{$_lang.role_create_snippet}</label>
</li>
<li>
	<input name="edit_snippet" id="r_edit_snippet" type="checkbox" {if $role->edit_snippet EQ 1}checked="checked"{/if} />
	<label for="r_edit_snippet">{$_lang.role_edit_snippet}</label>
</li>
<li>
	<input name="save_snippet" id="r_save_snippet" type="checkbox" {if $role->save_snippet EQ 1}checked="checked"{/if} />
	<label for="r_save_snippet">{$_lang.role_save_snippet}</label>
</li>
<li>
	<input name="delete_snippet" id="r_delete_snippet" type="checkbox" {if $role->delete_snippet EQ 1}checked="checked"{/if} />
	<label for="r_delete_snippet">{$_lang.role_delete_snippet}</label>
</li>
</ul>
</div>
<br /><br />

<span class="fakefieldsettitle">{$_lang.role_chunk_management}</span><br />
<div class="fakefieldset">
<ul class="no_list">
<li>
	<input name="new_chunk" id="r_new_chunk" type="checkbox" {if $role->new_chunk EQ 1}checked="checked"{/if} />
	<label for="r_new_chunk">{$_lang.role_create_chunk}</label>
</li>
<li>
	<input name="edit_chunk" id="r_edit_chunk" type="checkbox" {if $role->edit_chunk EQ 1}checked="checked"{/if} />
	<label for="r_edit_chunk">{$_lang.role_edit_chunk}</label>
</li>
<li>
	<input name="save_chunk" id="r_save_chunk" type="checkbox" {if $role->save_chunk EQ 1}checked="checked"{/if} />
	<label for="r_save_chunk">{$_lang.role_save_chunk}</label>
</li>
<li>
	<input name="delete_chunk" id="r_delete_chunk" type="checkbox" {if $role->delete_chunk EQ 1}checked="checked"{/if} />
	<label for="r_delete_chunk">{$_lang.role_delete_chunk}</label>
</li>
</ul>
</div>
<br /><br />

<span class="fakefieldsettitle">{$_lang.role_plugin_management}</span><br />
<div class="fakefieldset">
<ul class="no_list">
<li>
	<input name="new_plugin" id="r_new_plugin" type="checkbox" {if $role->new_plugin EQ 1}checked="checked"{/if} />
	<label for="r_new_plugin">{$_lang.role_create_plugin}</label>
</li>
<li>
	<input name="edit_plugin" id="r_edit_plugin" type="checkbox" {if $role->edit_plugin EQ 1}checked="checked"{/if} />
	<label for="r_edit_plugin">{$_lang.role_edit_plugin}</label>
</li>
<li>
	<input name="save_plugin" id="r_save_plugin" type="checkbox" {if $role->save_plugin EQ 1}checked="checked"{/if} />
	<label for="r_save_plugin">{$_lang.role_save_plugin}</label>
</li>
<li>
	<input name="delete_plugin" id="r_delete_plugin" type="checkbox" {if $role->delete_plugin EQ 1}checked="checked"{/if} />
	<label for="r_delete_plugin">{$_lang.role_delete_plugin}</label>
</li>
</ul>
</div>
<br /><br />

<span class="fakefieldsettitle">{$_lang.role_eventlog_management}</span><br />
<div class="fakefieldset">
<ul class="no_list">
<li>
	<input name="view_eventlog" id="r_view_eventlog" type="checkbox" {if $role->view_eventlog EQ 1}checked="checked"{/if} />
	<label for="r_view_eventlog">{$_lang.role_view_eventlog}</label>
</li>
<li>
	<input name="delete_eventlog" id="r_delete_eventlog" type="checkbox" {if $role->delete_eventlog EQ 1}checked="checked"{/if} />
	<label for="r_delete_eventlog">{$_lang.role_delete_eventlog}</label>
</li>
</ul>
</div>
<br /><br />

<span class="fakefieldsettitle">{$_lang.role_user_management}</span><br />
<div class="fakefieldset">
<ul class="no_list">
<li>
	<input name="new_user" id="r_new_user" type="checkbox" {if $role->new_user EQ 1}checked="checked"{/if} />
	<label for="r_new_user">{$_lang.role_new_user}</label>
</li>
<li>
	<input name="edit_user" id="r_edit_user" type="checkbox" {if $role->edit_user EQ 1}checked="checked"{/if} />
	<label for="r_edit_user">{$_lang.role_edit_user}</label>
</li>
<li>
	<input name="save_user" id="r_save_user" type="checkbox" {if $role->save_user EQ 1}checked="checked"{/if} />
	<label for="r_save_user">{$_lang.role_save_user}</label>
</li>
<li>
	<input name="delete_user" id="r_delete_user" type="checkbox" {if $role->delete_user EQ 1}checked="checked"{/if} />
	<label for="r_delete_user">{$_lang.role_delete_user}</label>
</li>
</ul>
</div>
<br /><br />

<span class="fakefieldsettitle">{$_lang.role_web_user_management}</span><br />
<div class="fakefieldset">
<ul class="no_list">
<li>
	<input name="new_web_user" id="r_new_web_user" type="checkbox" {if $role->new_web_user EQ 1}checked="checked"{/if} />
	<label for="r_new_web_user">{$_lang.role_new_web_user}</label>
</li>
<li>
	<input name="edit_web_user" id="r_edit_web_user" type="checkbox" {if $role->edit_web_user EQ 1}checked="checked"{/if} />
	<label for="r_edit_web_user">{$_lang.role_edit_web_user}</label>
</li>
<li>
	<input name="save_web_user" id="r_save_web_user" type="checkbox" {if $role->save_web_user EQ 1}checked="checked"{/if} />
	<label for="r_save_web_user">{$_lang.role_save_web_user}</label>
</li>
<li>
	<input name="delete_web_user" id="r_delete_web_user" type="checkbox" {if $role->delete_web_user EQ 1}checked="checked"{/if} />
	<label for="r_delete_web_user">{$_lang.role_delete_web_user}</label>
</li>
</ul>
</div>
<br /><br />

<span class="fakefieldsettitle">{$_lang.role_udperms}</span><br />
<div class="fakefieldset">
<ul class="no_list">
<li>
	<input name="access_permissions" id="r_access_permissions" type="checkbox" {if $role->access_permissions EQ 1}checked="checked"{/if} />
	<label for="r_access_permissions">{$_lang.role_access_persmissions}</label>
</li>
<li>
	<input name="web_access_permissions" id="r_web_access_permissions" type="checkbox" {if $role->web_access_permissions EQ 1}checked="checked"{/if} />
	<label for="r_web_access_permissions">{$_lang.role_web_access_persmissions}</label>
</li>
</ul>
</div>
<br /><br />

<span class="fakefieldsettitle">{$_lang.role_role_management}</span><br />
<div class="fakefieldset">
<ul class="no_list">
<li>
	<input name="new_role" id="r_new_role" type="checkbox" {if $role->new_role EQ 1}checked="checked"{/if} />
	<label for="r_new_role">{$_lang.role_new_role}</label>
</li>
<li>
	<input name="edit_role" id="r_edit_role" type="checkbox" {if $role->edit_role EQ 1}checked="checked"{/if} />
	<label for="r_edit_role">{$_lang.role_edit_role}</label>
</li>
<li>
	<input name="save_role" id="r_save_role" type="checkbox" {if $role->save_role EQ 1}checked="checked"{/if} />
	<label for="r_save_role">{$_lang.role_save_role}</label>
</li>
<li>
	<input name="delete_role" id="r_delete_role" type="checkbox" {if $role->delete_role EQ 1}checked="checked"{/if} />
	<label for="r_delete_role">{$_lang.role_delete_role}</label>
</li>
</ul>
</div>
<br /><br />

<span class="fakefieldsettitle">{$_lang.role_config_management}</span><br />
<div class="fakefieldset">
<ul class="no_list">
<li>
	<input name="logs" id="r_logs" type="checkbox" {if $role->logs EQ 1}checked="checked"{/if} />
	<label for="r_logs">{$_lang.role_view_logs}</label>
</li>
<li>
	<input name="settings" id="r_settings" type="checkbox" {if $role->settings EQ 1}checked="checked"{/if} />
	<label for="r_settings">{$_lang.role_edit_settings}</label>
</li>
<li>
	<input name="file_manager" id="r_file_manager" type="checkbox" {if $role->file_manager EQ 1}checked="checked"{/if} />
	<label for="r_file_manager">{$_lang.role_file_manager}</label>
</li>
<li>
	<input name="bk_manager" id="r_bk_manager" type="checkbox" {if $role->bk_manager EQ 1}checked="checked"{/if} />
	<label for="r_bk_manager">{$_lang.role_bk_manager}</label>
</li>
<li>
	<input name="manage_metatags" id="r_manage_metatags" type="checkbox" {if $role->manage_metatags EQ 1}checked="checked"{/if} />
	<label for="r_manage_metatags">{$_lang.role_manage_metatags}</label>
</li>
<li>
	<input name="import" id="r_import" type="checkbox" {if $role->import_static EQ 1}checked="checked"{/if} />
	<label for="r_import">{$_lang.role_import_static}</label>
</li>
<li>
	<input name="export" id="r_export" type="checkbox" {if $role->export_static EQ 1}checked="checked"{/if} />
	<label for="r_export">{$_lang.role_export_static}</label>
</li>
</ul>
</div>
<br /><br />
