<p>{$_lang.onlineusers_message}</p>
<br/>
<div id="modx-grid-user-online">
    {if $data.total > 0}
    <div class="table-wrapper">
        <table class="table">
            <thead>
            <tr>
                <th>{$_lang.onlineusers_user}</th>
                <th>{$_lang.onlineusers_lasthit}</th>
                <th>{$_lang.onlineusers_action}</th>
            </tr>
            </thead>
            <tbody>
            {foreach $data.results as $record}
                <tr>
                    <td class="user-with-avatar">
                        <div class="user-avatar">
                            {if $record.photo}
                                <img src="{$record.photo}">
                            {else}
                                <i class="icon icon-user icon-2x"></i>
                            {/if}
                        </div>
                        <div class="user-data">
                            <div class="user-name">{$record.fullname|default:$record.username}</div>
                            <div class="user-group">{$record.group}</div>
                        </div>
                    </td>
                    <td class="occurred">
                        <div class="occurred-date">{$record.occurred|date_format:'%B %d, %Y'}</div>
                        <div class="occurred-time">{$record.occurred|date_format:'%H:%M'}</div>
                    </td>
                    <td>{$record.action}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
    {else}
        <div class="no-results">{$_lang.w_no_data}</div>
    {/if}
    {if $can_view_logs}
        <div class="widget-footer">
            <a href="{$_config.manager_url}?a=system/logs">{$_lang.w_view_all} &rarr;</a>
        </div>
     {/if}
</div>
