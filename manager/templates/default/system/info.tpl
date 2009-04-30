<div id="tabs_div">
    <!-- server -->
    <div class="padding x-hide-display" id="modx-tab-server">
        <h2>{$_lang.view_sysinfo}</h2>
    
        <table class="classy">
        <tbody>
        <tr>
            <th width="20%">{$_lang.modx_version}</th>
            <td><strong>{$version}</strong></td>
        </tr>
        <tr>
            <th>{$_lang.version_codename}</th>
            <td><strong>{$code_name}</strong></td>
        </tr>
        <tr>
            <th>phpInfo()</th>
            <td><strong><a href="javascript:;" onclick="viewPHPInfo();return false;">{$_lang.view}</a></strong></td>
        </tr>
        <tr>
            <th>{$_lang.access_permissions}</th>
            <td><strong>{if $_config.use_udperms EQ 1}{$_lang.enabled}{else}{$_lang.disabled}{/if}</strong></td>
        </tr>
        <tr>
            <th>{$_lang.servertime}</th>
            <td><strong>{$servertime}</strong></td>
        </tr>
        <tr>
            <th>{$_lang.localtime}</th>
            <td><strong>{$localtime}</strong></td>
        </tr>
        <tr>
            <th>{$_lang.serveroffset}</th>
            <td><strong>{$serveroffset}</strong> h</td>
        </tr>
        <tr>
            <th>{$_lang.database_type}</th>
            <td><strong>{$database_type}</strong></td>
        </tr>
        <tr>
            <th>{$_lang.database_version}</th>
            <td><strong>{$database_version}</strong></td>
        </tr>
        <tr>
            <th>{$_lang.database_charset}</th>
            <td><strong>{$database_charset}</strong></td>
        </tr>
        <tr>
            <th>{$_lang.database_name}</th>
            <td><strong>{$database_name}</strong></td>
        </tr>
        <tr>
            <th>{$_lang.database_server}</th>
            <td><strong>{$database_server}</strong></td>
        </tr>
        <tr>
            <th>{$_lang.table_prefix}</th>
            <td><strong>{$_config.table_prefix}</strong></td>
        </tr>
        </tbody>
        </table>
    </div>
    
    <!-- recent documents -->
    <div class="padding x-hide-display" id="modx-tab-resources">
        <h2>{$_lang.recent_docs}</h2>
        <p>{$_lang.sysinfo_activity_message}</p>
        <div id="modx-grid-resource-active" style="overflow:hidden; width:100%;"></div>
    </div>
    
    <!-- database -->
    <div class="x-hide-display" id="modx-tab-database">
        <div id="modx-grid-databasetables" style="overflow:hidden; width:100%;"></div>       
    </div>
    
    <!-- online users -->
    <div class="padding x-hide-display" id="modx-tab-users">
        <h2>{$_lang.onlineusers_title}</h2>
        
        <p>{$_lang.onlineusers_message}<strong>{$now}</strong>)</p>
        <br />
        
        <table class="classy" style="width: 100%;">
        <thead>
        <tr>
            <th>{$_lang.onlineusers_user}</th>
            <th>{$_lang.onlineusers_userid}</th>
            <th>{$_lang.onlineusers_lasthit}</th>
            <th>{$_lang.onlineusers_action}</th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$ausers item=user}
        <tr class="{cycle values=',odd'}">
            <th class="left">{$user->username}</th>
            <td>
                &nbsp;{$user->user}
            </td>
            <td>{$user->lasthit}</td>
            <td class="right">{$user->action}</td>
        </tr>
        {foreachelse}
            <tr><td colspan="6">No active users found.</td></tr>
        {/foreach}
        </tbody>
        </table>
    </div>
</div>