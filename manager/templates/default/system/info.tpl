<div id="tabs_div">
    <!-- server -->
    <div class="padding x-hide-display" id="modx-tab-server">
        <h2>{$_lang.view_sysinfo}</h2>
    
        <table class="classy" style="width: 100%;">
		<thead>
		    <tr>
			    <th width="20%">{$_lang.modx_version}</th>
				<th>{$_lang.version_codename}</th>
				<th>phpInfo()</th>
				<th>{$_lang.servertime}</th>
				<th>{$_lang.localtime}</th>
				<th>{$_lang.serveroffset}</th>
			</tr>
		</thead>
        <tbody>
        <tr>
            <td><strong>{$version}</strong></td>
			<td><strong>{$code_name}</strong></td>
            <td><strong><a href="javascript:;" onclick="viewPHPInfo();return false;">{$_lang.view}</a></strong></td>
			<td><strong>{$servertime}</strong></td>
			<td><strong>{$localtime}</strong></td>
			<td><strong>{$serveroffset}</strong> h</td>
        </tr>
        </tbody>
		</table>
		<br />
		<table class="classy" style="width: 100%;">
		<thead>
		    <tr>
			    <th>{$_lang.database_type}</th>
				<th>{$_lang.database_version}</th>
				<th>{$_lang.database_charset}</th>
				<th>{$_lang.database_name}</th>
				<th>{$_lang.database_server}</th>
				<th>{$_lang.table_prefix}</th>
			</tr>
		</thead>
		<tbody>
        <tr>
            <td><strong>{$database_type}</strong></td>
			<td><strong>{$database_version}</strong></td>
			<td><strong>{$database_charset}</strong></td>
			<td><strong>{$database_name}</strong></td>
			<td><strong>{$database_server}</strong></td>
			<td><strong>{$_config.table_prefix}</strong></td>
        </tr>
        </tbody>
        </table>
    </div>
    
    <!-- recent documents -->
    <div class="padding x-hide-display" id="modx-tab-resources">
        <h2>{$_lang.recent_docs}</h2>
        <p>{$_lang.sysinfo_activity_message}</p>
        <br />
        <div id="modx-grid-resource-active-div" style="overflow:hidden; width:100%;"></div>
    </div>
    
    <!-- database -->
    <div class="padding x-hide-display" id="modx-tab-database">
	    <h2>{$_lang.db_header}</h2>
		<p>{$_lang.db_info}</p>
		<br />
        <div id="modx-grid-databasetables-div" style="overflow:hidden; width:100%;"></div>       
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