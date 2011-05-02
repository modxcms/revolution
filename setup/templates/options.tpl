<form id="options" action="?action=options" method="post">
<h2>{$_lang.options_title}</h2>

<hr />

<table class="options">
<tbody>
<tr>
    <th style="width: 43%;">
        <img src="assets/images/im_new_inst.gif" width="32" height="32" alt="" />

        <label>
            <input type="radio" name="installmode" id="installmode0" value="0" {if $installmode EQ 0} checked="checked"{/if} />
            {$_lang.options_new_installation}
        </label>
    </th>
    <td>
        {$_lang.options_install_new_copy} {$app_name} -
        <strong>{$_lang.options_install_new_note}</strong>
    </td>
</tr>
<tr>
    <th>
        <img src="assets/images/im_inst_upgrade.gif" width="32" height="32" alt=""/>

        <label>
            <input type="radio" name="installmode" id="installmode1" value="1"{if $installmode LT 1} disabled="disabled"{/if}{if $installmode EQ 1} checked="checked"{/if} />
            {$_lang.options_upgrade_existing}
        </label>
    </th>
    <td>{$_lang.options_upgrade_existing_note}</td>
</tr>
{if $installmode GT 0}
<tr>
    <th>&nbsp;</th>
    <td style="background: #fffdbb; padding:0 1em; border:2px solid #CBD499">
        <h3>{$_lang.options_important_upgrade}</h3>
        <p>{$_lang.options_important_upgrade_note}</p>
    </td>
</tr>
{/if}
<tr>
    <th>
        <img src="assets/images/im_inst_upgrade.gif" width="32" height="32" alt="" />
        <label>
            <input type="radio" name="installmode" id="installmode3" value="3"{if $installmode LT 1} disabled="disabled"{/if}{if $installmode EQ 3} checked="checked"{/if} />
            {$_lang.options_upgrade_advanced}
        </label>
    </th>
    <td>{$_lang.options_upgrade_advanced_note}</td>
</tr>
</tbody>
</table>

{if $installmode EQ 0}
<hr />
<h3>{$_lang.advanced_options}</h3>

<table class="options">
<tbody>
<tr>
    <th style="padding-top: 1em;">
        <label>
            <input type="text" name="new_folder_permissions" id="new_folder_permissions" value="{$new_folder_permissions}" size="5" maxlength="4" />
            {$_lang.options_new_folder_permissions}
        </label>
    </th>
    <td style="padding-top: 1em;">{$_lang.options_new_folder_permissions_note}</td>
</tr>
<tr>
    <th style="padding-top: 2em;">
        <label>
            <input type="text" name="new_file_permissions" id="new_file_permissions" value="{$new_file_permissions}" size="5" maxlength="4" />
            {$_lang.options_new_file_permissions}
        </label>
    </th>
    <td style="padding-top: 2em;">{$_lang.options_new_file_permissions_note}</td>
</tr>
</tbody>
</table>
{/if}
{if $smarty.const.MODX_SETUP_KEY EQ '@traditional@' AND $unpacked EQ 1 AND $files_exist EQ 1}
<input type="hidden" name="unpacked" id="unpacked" value="1" />
{else}
{if $installmode NE 0}
<hr />
<h3>{$_lang.advanced_options}</h3>
{/if}
<table class="options">
<tbody>
<tr>
    <th style="padding-top: 2em;">
        <label>
            <input type="checkbox" name="unpacked" id="unpacked" value="1"{if $unpacked EQ 0} disabled="disabled"{/if}{if $unpacked EQ 1} checked="checked"{/if} />
            {$_lang.options_core_unpacked}
        </label>
    </th>
    <td style="padding-top: 2em;">{$_lang.options_core_unpacked_note}</td>
</tr>
<tr>
    <th>
        <label>
            <input type="checkbox" name="inplace" id="inplace" value="1"{if $files_exist EQ 0} disabled="disabled"{/if}{if $files_exist EQ 1} checked="checked"{/if} />
            {$_lang.options_core_inplace}
        </label>
    </th>
    <td>{$_lang.options_core_inplace_note}</td>
</tr>
</tbody>
</table>
<br />
{/if}


<div class="setup_navbar">
    <input type="submit" name="proceed" value="{$_lang.next}" />
    <input type="button" onclick="MODx.go('welcome');" value="{$_lang.back}" />
</div>
</form>
