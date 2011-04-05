{if $showHidden}
<script type="text/javascript">MODx.showHidden = true;</script>
{/if}
<script type="text/javascript" src="assets/js/sections/database.js"></script>
<form id="install" action="?action=database" method="post">
<h2>{$_lang.connection_title}</h2>

<h3>{$_lang.connection_connection_and_login_information}</h3>

<p>{$_lang.connection_connection_note}</p>

<p class="error">{$error_message}</p>

<div class="labelHolder">
    <label for="database-type">{$_lang.connection_database_type}</label>
    <select id="database-type" value="{$config.database_type}" name="database_type">
        <option value="mysql"{if $config.database_type EQ "mysql"} selected="selected"{/if}>mysql</option>
        <option value="sqlsrv"{if $config.database_type EQ "sqlsrv"} selected="selected"{/if}>sqlsrv</option>
    </select>
    &nbsp;<span class="version-msg" id="database-type-error"></span>
</div>
<div class="labelHolder">
    <label for="database-server">{$_lang.connection_database_host}</label>
    <input id="database-server" value="{$config.database_server}" name="database_server" />
    &nbsp;<span class="field_error" id="database-server-error"></span>
</div>
<div class="labelHolder">
    <label for="database-user">{$_lang.connection_database_login}</label>
    <input id="database-user" name="database_user" value="{$config.database_user}" />
    &nbsp;<span class="field_error" id="database-user-error"></span>
</div>
<div class="labelHolder">
    <label for="database-password">{$_lang.connection_database_pass}</label>
    <input id="database-password" type="password" name="database_password"  value="{$config.database_password}" />
    &nbsp;<span class="field_error" id="database-password-error"></span>
</div>
<div class="labelHolder">
    <label for="dbase">{$_lang.connection_database_name}</label>
    <input id="dbase" value="{$config.dbase}" name="dbase" />
    &nbsp;<span class="field_error" id="dbase-error"></span>
</div>
<div class="labelHolder">
    <label for="table-prefix">{$_lang.connection_table_prefix}</label>
    <input id="table-prefix" value="{$config.table_prefix}" name="table_prefix" />
    &nbsp;<span class="field_error" id="tableprefix_error"></span>
</div>
<p>&rarr;&nbsp;<a href="javascript:void(0);" id="modx-testconn">{$_lang.db_test_conn_msg}</a></p>

<div id="modx-db-step1-msg" class="modx-hidden2">
    <span>{$_lang.db_connecting}</span>&nbsp;<span class="connect-msg"></span>
</div>
<p id="modx-db-info">
    <br />- {$_lang.mysql_version_server_start}<span id="modx-db-server-version"></span>
    <br />- {$_lang.mysql_version_client_start}<span id="modx-db-client-version"></span>
    <hr />
</p>
<div id="modx-db-step2" class="modx-hidden2">
{if $config.database_type EQ "mysql"}
<div class="labelHolder">
    <label for="database-connection-charset">{$_lang.connection_character_set}</label>
    <select id="database-connection-charset" value="{$config.database_connection_charset}" name="database_connection_charset"></select>
    &nbsp;<span class="field_error" id="database_connection_charset_error"></span>
</div>
{if $installmode EQ 0}
<div class="labelHolder">
    <label for="database-collation">{$_lang.connection_collation}</label>
    <select id="database-collation" value="{$config.database_collation}" name="database_collation"></select>
    &nbsp;<span class="field_error" id="database_collation_error"></span>
</div>
{/if}
{/if}
<br />
<p>&rarr;&nbsp;<a href="javascript:void(0);" id="modx-testcoll">{$_lang.db_test_coll_msg}</a></p>

<p id="modx-db-step2-msg" class="modx-hidden2"><span>{$_lang.db_check_db}</span>&nbsp;<span class="result"></span></p>
</div>
{if $installmode EQ 0}
<div id="modx-db-step3" class="modx-hidden">
    <p class="title">{$_lang.connection_default_admin_user}</p>
    <p>{$_lang.connection_default_admin_note}</p>

    <div class="labelHolder">
        <label for="cmsadmin">{$_lang.connection_default_admin_login}</label>
        <input type="text" name="cmsadmin" id="cmsadmin" value="{$config.cmsadmin}" />
        &nbsp;<span class="field_error" id="cmsadmin_error">{$error_cmsadmin}</span>
    </div>
    <div class="labelHolder">
        <label for="cmsadminemail">{$_lang.connection_default_admin_email}</label>
        <input type="text" name="cmsadminemail" id="cmsadminemail" value="{$config.cmsadminemail}" />
        &nbsp;<span class="field_error" id="cmsadminemail_error">{$error_cmsadminemail}</span>
    </div>
    <div class="labelHolder">
        <label for="cmspassword">{$_lang.connection_default_admin_password}</label>
        <input type="password" id="cmspassword" name="cmspassword" value="{$config.cmspassword}" />
        &nbsp;<span class="field_error" id="cmspassword_error">{$error_cmspassword}</span>
    </div>
    <div class="labelHolder">
        <label for="cmspasswordconfirm">{$_lang.connection_default_admin_password_confirm}</label>
        <input type="password" id="cmspasswordconfirm" name="cmspasswordconfirm" value="{$config.cmspasswordconfirm}" />
        &nbsp;<span class="field_error" id="cmspasswordconfirm_error">{$error_cmspasswordconfirm}</span>
    </div>
</div>
{/if}
<br />

{if $config.unpacked EQ 1}
<input type="hidden" id="unpacked" name="unpacked" value="1" />
{/if}
{if $config.inplace EQ 1}
<input type="hidden" id="inplace" name="inplace" value="1" />
{/if}
<div class="setup_navbar">
    <input type="submit" name="proceed" id="modx-next" class="modx-hidden" value="{$_lang.next}" />
    <input type="button" onclick="MODx.go('options');" value="{$_lang.back}" />
</div>
</form>