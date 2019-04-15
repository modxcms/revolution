{if $showHidden|default}
<script type="text/javascript">
    MODx.showHidden = true;
</script>
{/if}
<script type="text/javascript" src="assets/js/sections/database.js"></script>
<form id="install" action="?action=database" method="post">

    <h2 class="title">{$_lang.connection_connection_and_login_information}</h2>

    <p>{$_lang.connection_connection_note}</p>

    <p class="error">{$error_message|default}</p>

    <div class="labelHolder">
        <div class="col">
            <label for="database-type">{$_lang.connection_database_type}</label>
        </div>
        <div class="col">
            <select id="database-type" name="database_type" autofocus="autofocus">
                <option value="mysql" {if $config.database_type|default EQ "mysql" } selected="selected" {/if}>mysql </option>
                <option value="sqlsrv" {if $config.database_type|default EQ "sqlsrv" } selected="selected" {/if}>sqlsrv </option>
            </select>
            &nbsp;<span class="version-msg" id="database-type-error"></span>
        </div>
    </div>
    <div class="labelHolder">
        <div class="col">
            <label for="database-server">{$_lang.connection_database_host}</label>
        </div>
        <div class="col">
            <input type="text" id="database-server" value="{$config.database_server|default|escape}" name="database_server" />
            &nbsp;<span class="field_error" id="database-server-error"></span>
        </div>
    </div>
    <div class="labelHolder">
        <div class="col">
            <label for="database-user">{$_lang.connection_database_login}</label>
        </div>
        <div class="col">
            <input type="text" id="database-user" name="database_user" value="" />
            &nbsp;<span class="field_error" id="database-user-error"></span>
        </div>
    </div>
    <div class="labelHolder">
        <div class="col">
            <label for="database-password">{$_lang.connection_database_pass}</label>
        </div>
        <div class="col">
            <input type="text" id="database-password" type="password" name="database_password" value="" />
            &nbsp;<span class="field_error" id="database-password-error"></span>
        </div>
    </div>
    <div class="labelHolder">
        <div class="col">
            <label for="dbase">{$_lang.connection_database_name}</label>
        </div>
        <div class="col">
            <input type="text" id="dbase" value="" name="dbase" />
            &nbsp;<span class="field_error" id="dbase-error"></span>
        </div>
    </div>
    <div class="labelHolder">
        <div class="col">
            <label for="table-prefix">{$_lang.connection_table_prefix}</label>
        </div>
        <div class="col">
            <input type="text" id="table-prefix" value="{$config.table_prefix|default|escape}" name="table_prefix" />
            &nbsp;<span class="field_error" id="tableprefix_error"></span>
        </div>
    </div>

    <p>&rarr;&nbsp;<a href="javascript:void(0);" id="modx-testconn">{$_lang.db_test_conn_msg}</a></p>

    <div id="modx-db-step1-msg" class="modx-hidden2">
        <span class="connect-title">{$_lang.db_connecting}</span>
        <span class="connect-msg"></span>
    </div>
    <p id="modx-db-info">
        <span>- {$_lang.mysql_version_server_start}<span id="modx-db-server-version"></span></span>
        <span>- {$_lang.mysql_version_client_start}<span id="modx-db-client-version"></span></span>
    </p>
    <div id="modx-db-step2" class="modx-hidden2">
        {if $config.database_type|default EQ "mysql"}
        <div class="labelHolder">
            <div class="col">
                <label for="database-connection-charset">{$_lang.connection_character_set}</label>
            </div>
            <div class="col">
                <select id="database-connection-charset" value="{$config.database_connection_charset|escape}" name="database_connection_charset"></select>
                &nbsp;<span class="field_error" id="database_connection_charset_error"></span>
            </div>
        </div>
        {if $installmode EQ 0}
        <div class="labelHolder">
            <div class="col">
                <label for="database-collation">{$_lang.connection_collation}</label>
            </div>
            <div class="col">
                <select id="database-collation" value="{$config.database_collation|default|escape}" name="database_collation"></select>
                &nbsp;<span class="field_error" id="database_collation_error"></span>
            </div>
        </div>
        {/if}
        {/if}
        <br />
        <p>&rarr;&nbsp;<a href="javascript:void(0);" id="modx-testcoll">{$_lang.db_test_coll_msg}</a></p>

        <p id="modx-db-step2-msg" class="modx-hidden2"><span>{$_lang.db_check_db}</span>&nbsp;<span class="result"></span></p>
    </div>
    {if $installmode EQ 0}
    <div id="modx-db-step3" class="modx-hidden">
        <h2 class="title">{$_lang.connection_default_admin_user}</h2>
        <p>{$_lang.connection_default_admin_note}</p>

        <div class="labelHolder">
            <div class="col">
                <label for="cmsadmin">{$_lang.connection_default_admin_login}</label>
            </div>
            <div class="col">
                <input type="text" name="cmsadmin" id="cmsadmin" value="{$config.cmsadmin|default|escape}" />
                &nbsp;<span class="field_error" id="cmsadmin_error">{$error_cmsadmin|default}</span>
            </div>
        </div>
        <div class="labelHolder">
            <div class="col">
                <label for="cmsadminemail">{$_lang.connection_default_admin_email}</label>
            </div>
            <div class="col">
                <input type="text" name="cmsadminemail" id="cmsadminemail" value="{$config.cmsadminemail|default|escape}" />
                &nbsp;<span class="field_error" id="cmsadminemail_error">{$error_cmsadminemail|default}</span>
            </div>
        </div>
        <div class="labelHolder">
            <div class="col">
                <label for="cmspassword">{$_lang.connection_default_admin_password}</label>
            </div>
            <div class="col">
                <input type="password" id="cmspassword" name="cmspassword" value="{$config.cmspassword|default|escape}" />
                &nbsp;<span class="field_error" id="cmspassword_error">{$error_cmspassword|default}</span>
            </div>
        </div>
        <div class="labelHolder">
            <div class="col">
                <label for="cmspasswordconfirm">{$_lang.connection_default_admin_password_confirm}</label>
            </div>
            <div class="col">
                <input type="password" id="cmspasswordconfirm" name="cmspasswordconfirm" value="{$config.cmspasswordconfirm|default|escape}" />
                &nbsp;<span class="field_error" id="cmspasswordconfirm_error">{$error_cmspasswordconfirm|default}</span>
            </div>
        </div>
    </div>
    {/if}
    <br />

    {if $config.unpacked|default EQ 1}
    <input type="hidden" id="unpacked" name="unpacked" value="1" />
    {/if}
    {if $config.inplace|default EQ 1}
    <input type="hidden" id="inplace" name="inplace" value="1" />
    {/if}
    <div class="setup_navbar">
        <input type="button" onclick="MODx.go('options');" value="< {$_lang.back}" id="modx-back" class="button" />
        <input type="submit" name="proceed" id="modx-next" class="modx-hidden button" value="{$_lang.next} >" id="modx-next" class="button" />
    </div>
</form>
