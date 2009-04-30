<br />
<div class="sectionHeader">{$_lang.files_files}</div>
<div class="sectionBody" style="font-size: 11px;">
{literal}
<script type="text/javascript" src="assets/modext/multifile.js"></script>
<script type="text/javascript">
// <![CDATA[
function viewfile(url) {
	$('imageviewer').style.border = '1px solid #000080';
	$('imageviewer').src = url;
}

function setColor(o,state){
	if (!o) return;
	if(state && o.style) o.style.backgroundColor='#eeeeee';
	else if (o.style) o.style.backgroundColor='transparent';
}

function confirmDelete(path,file) {
	if (confirm("{/literal}{$_lang.confirm_delete_file}{literal}")) {
		new Ajax('{/literal}{$_config.connectors_url}{literal}system/filesys.php?action=removeFile',{
			postBody: {
				'path': path,
				'file': file,
			},
			onComplete: function(response) {
				if (response == true) {
					window.location.href = 'index.php?a=system/files/list';
				} else MODx.form.Handler.errorJSON('errormsg',response);
			}
		}).request();
	}
}

function confirmDeleteFolder(path,folder) {
	if (confirm("{/literal}{$_lang.confirm_delete_folder}{literal}")) {
		new Ajax('{/literal}{$_config.connectors_url}{literal}system/filesys.php?action=removeFolder',{
			postBody: {
				'path': path,
				'folder': folder,
			},
			onComplete: function(response) {
				if (response == true) {
					window.location.reload(true);
				} else MODx.form.Handler.errorJSON('errormsg',response);
			}
		}).request();
	}
}

function confirmUnzip(url) {
	if (confirm("{/literal}{$_lang.confirm_unzip_file}{literal}") {
		new Ajax('{/literal}{$_config.connectors_url}{literal}system/filesys.php?action=unzipFile',{
			postBody: {
				'path': path,
				'file': file,
			},
			onComplete: function(response) {
				if (response == true) {
					window.location.href = 'index.php?a=system/files/list';
				} else MODx.form.Handler.errorJSON('errormsg',response);
			}
		}).request();
	}
}

function newFolder() {
	f = window.prompt('{/literal}{$_lang.file_folder_enter_new}{literal}:','');
	new Ajax('{/literal}{$_config.connectors_url}{literal}system/filesys.php?action=createFolder',{
		postBody: {
			'name': f,
			'path': '{/literal}{$startpath}{literal}',		
		},
		onComplete: function(response) {
			if (response == true) {
				window.location.reload(true);
			} else MODx.form.Handler.errorJSON('errormsg',response);
		}
	}).request();
	return false;
}
// ]]>
</script>
{/literal}


{$_lang.files_dir_listing}
<strong>{$startpath}</strong>
<br /><br />

{if $files_access_denied}
	{$_lang.files_access_denied}{exit}
{/if}

{if is_writable($startpath)}
	<img src="media/style/{$_config.manager_theme}/images/tree/folder.gif" align="absmiddle" alt="" />
	<a href="#" onclick="return newFolder();">
	   	<strong>{$_lang.add_folder}</strong>
	</a>
	<br />
{/if}


{if $startpath EQ $_config.filemanager_path OR $startpath|cat:'/' EQ $_config.filemanager_path}
	<img src="media/style/{$_config.manager_theme}/images/tree/deletedfolder.gif" align="absmiddle" alt="" />
	<span style="color:#bbb;cursor:default;"><strong>{$_lang.files_top_level}</strong></span>
	<br />
{else}
	<a href="index.php?a=system/files/list&mode=drill&path={$_config.filemanager_path}">
		<img src="media/style/{$_config.manager_theme}/images/tree/folder.gif" align="absmiddle" alt="" />
		<strong>{$_lang.files_top_level}</strong>
	</a>
	<br />
{/if}
{if $startpath EQ $_config.filemanager_path || $startpath|cat:'/' EQ $_config.filemanager_path}
	<img src="media/style/{$_config.manager_theme}/images/tree/deletedfolder.gif" align="absmiddle" alt="" />
	<span style="color:#bbb;cursor:default;"><strong>{$_lang.files_up_level}</strong></span>
	<br />
{else}
	<a href="index.php?a=system/files/list&mode=drill&path={$parentdir}">
		<img src="media/style/{$_config.manager_theme}/images/tree/folder.gif" align="absmiddle" alt="" /> 
		<strong>{$_lang.files_up_level}</strong>
	</a>
	<br />
{/if}
<p id="errormsg"></p>

<table class="standard" style="width: 650px;">
<tbody>
<tr>
	<th style="width: 300px;">{$_lang.files_filename}</th>
	<th style="width: 130px;">{$_lang.files_modified}</th>
	<th style="width: 70px;">{$_lang.files_filesize}</th>
	<th style="widht: 150px;">{$_lang.files_fileoptions}</th>
</tr>
{foreach from=$folders item=folder}
<tr style="cursor:default;" onmouseout="setColor(this,0)" onmouseover="setColor(this,1)">
	<td>
		<img src="media/style/{$_config.manager_theme}/images/tree/folder.gif" align="absmiddle" alt="" />
		<a href="index.php?a=system/files/list&amp;mode=drill&amp;path={$folder.dir}">
	 		<strong>{$folder.file}</strong>
		</a>
	</td>
	<td>{$folder.modified|date_format:'%m/%d/%Y %I:%H:%S %p'}</td>
	<td dir="ltr">{$folder.stats.7|nicesize}</td>
	<td>
		{if is_writable($folder.curpath)}
		<span style="width:20px">
			<a href="javascript:;" onclick="return confirmDeleteFolder('{$folder.curpath}','{$folder.file}');">
				<img src="media/style/{$_config.manager_theme}/images/icons/delete.gif" alt="{$_lang.file_delete_folder}" title="{$_lang.file_delete_folder}" />
			</a>
		</span>
		{/if}
	</td>
</tr>
{/foreach}
{foreach from=$files item=file}
<tr onmouseout="setColor(this,0)" onmouseover="setColor(this,1)">
	<td>
		<img src="media/style/{$_config.manager_theme}/images/tree/page-html.gif" align="absmiddle" alt="" />
		{$file.file}
	</td>
	<td>{$file.modified|date_format:'%m/%d/%Y %I:%H:%S %p'}</td>
	<td dir="ltr">{$file.stats.7|nicesize}</td>
	<td>
		<!-- UNZIP -->
		{if $file.unzip}
		<span style="width:20px;">
			<a href="index.php?a=system/files/list&mode=unzip&path={$file.curpath}&file={$file.file}" onclick="return confirmUnzip();">
				<img src="media/style/{$_config.manager_theme}/images/icons/unzip.gif"
					 align="absmiddle"
					 alt="{$_lang.file_download_unzip}"
					 title="{$_lang.file_download_unzip}"
				/>
			</a>
		</span>
		{/if}
		
		<!-- VIEW -->
		{if $file.view EQ 'inline'}
		<span style="width:20px;">
			<a href="index.php?a=system/files/list&mode=view&path={$file.dir}">
				<img src="media/style/{$_config.manager_theme}/images/icons/context_view.gif"
					 align="absmiddle"
					 alt="{$_lang.file_viewfile}" 
					 title="{$_lang.file_viewfile}" 
				/>
			</a>
		</span>

		{elseif $file.view EQ 'view'}
		<span style="cursor:pointer; width:20px;" onclick="viewfile('{$file.webpath}');">
			<img src="media/style/{$_config.manager_theme}/images/icons/context_view.gif"
				 align="absmiddle"
				 alt="{$_lang.file_viewfile}"
				 title="{$_lang.file_viewfile}" 
			/>
		</span>

		{elseif $file.view EQ 'download'}
		<a href="{$file.webpath}" style="cursor:pointer; width:20px;">
			<img src="media/style/{$_config.manager_theme}/images/misc/ed_save.gif"
				 align="absmiddle"
				 alt="{$_lang.file_download_file}"
				 title="{$_lang.file_download_file}"
			/>
		</a>
		{else}
			<span class="disabledImage">
				<img src="media/style/{$_config.manager_theme}/images/icons/context_view.gif"
					 align="absmiddle" 
					 alt="{$_lang.file_viewfile}"
					 title="{$_lang.file_viewfile}"
				/>
			</span>
		{/if}
		
		
		<!-- EDIT -->
		{if $file.edit}
		<span style="width:20px;">
			<a href="index.php?a=system/files/list&mode=edit&path={$file.dir}">
				<img src="media/style/{$_config.manager_theme}/images/icons/save.gif"
					 align="absmiddle"
					 alt="{$_lang.file_editfile}"
					 title="{$_lang.file_editfile}"
				/>
			</a>
		</span>
		{else}
		<span class="disabledImage">
			<img src="media/style/{$_config.manager_theme}/images/icons/save.gif"
				 align="absmiddle"
				 alt="{$_lang.file_editfile}"
				 title="{$_lang.file_editfile}"
			/>
		</span>
		{/if}
		
		<!-- DELETE -->
		{if $file.delete}
		<span style="width:20px;">
			<a href="javascript:confirmDelete('{$file.curpath}','{$file.file}');">
				<img src="media/style/{$_config.manager_theme}/images/icons/delete.gif"
					 align="absmiddle"
					 alt="{$_lang.file_delete_file}"
					 title="{$_lang.file_delete_file}"
				/>
			</a>
		</span>
		{else}
		<span class="disabledImage">
			<img src="media/style/{$_config.manager_theme}/images/icons/delete.gif"
				 align="absmiddle"
				 alt="{$_lang.file_delete_file}"
				 title="{$_lang.file_delete_file}"
			/>
		</span>
		{/if}
	</td>
</tr>
{/foreach}
{if $folders|@count EQ 0 AND $files|@count EQ 0}
<tr>
	<td colspan="4">
		<img src="media/style/{$_config.manager_theme}/images/tree/deletedfolder.gif" align="absmiddle" />
		<span style="color:#888; cursor:default;">This directory is empty.</span>
	</td>
</tr>
{/if}
</tbody>
</table>

{$_lang.files_directories}: <strong>{$folders|@count}</strong>
<br />
{$_lang.files_files}: <strong>{$files|@count}</strong>
<br />
{$_lang.files_data}: <strong><span dir="ltr">{$filesizes|nicesize}</span></strong>
<br />
{$_lang.files_dirwritable} <strong>{if is_writable($startpath)}{$_lang.yes}{else}{$_lang.no}{/if}.</strong>
<br />

<div align="center"><img src="media/style/{$_config.manager_theme}/images/icons/_tx_.gif" id="imageviewer" /></div>

<br />
<hr />

{if (@ini_get('file_uploads') OR get_cfg_var('file_uploads') EQ 1) AND is_writable($startpath)}
<form action="index.php?a=system/files/list" enctype="multipart/form-data" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="{$upload_maxsize|default:1048576}" />
<input type="hidden" name="path" value="{$startpath}" />

<span style="width:300px;">{$_lang.files_uploadfile_msg}</span>
<input id="file_elem" type="file" name="bogus"  style="height: 19px;" />

<div id="files_list"></div>
<script type="text/javascript">
// <![CDATA[
var multi_selector = new MultiSelector($('files_list'),10);
multi_selector.addElement($('file_elem'));
// ]]>
</script>

<input type="submit" value="{$_lang.files_uploadfile}" />
</form>
{else}{$_lang.files_upload_inhibited_msg}{/if}
</div>



{if $smarty.request.mode EQ 'edit' OR $smarty.request.mode EQ 'view'}
<div class="sectionHeader">{if $smarty.request.mode EQ 'edit'}{$_lang.files_editfile}{else}{$_lang.files_viewfile}{/if}</div>
<div class="sectionBody">
{if $smarty.request.mode EQ 'edit'}
	{actionButtons data=$modActionButtons}
{/if}
<form id="file_edit" action="{$_config.connectors_url}system/filesys.php" method="post">
<input type="hidden" name="path" value="{$smarty.request.path}" />
<table class="standard" width="100%">
<tbody>
<tr>
    <td>
		<textarea name="content" id="content" dir="ltr" style="width:100%; height:370px;">{$buffer}</textarea>
	</td>
</tr>
</tbody>
</table>
</form>
</div>
{/if}
