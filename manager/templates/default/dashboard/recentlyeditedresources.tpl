<p>{$_lang.activity_message}</p>
<br/>
<div id="modx-grid-user-recent-resource">
    {if $data.total > 0}
        <div class="table-wrapper">
            <table class="table">
                <thead>
                <tr>
                    <th>{$_lang.id}</th>
                    <th>{$_lang.pagetitle}</th>
                    <th>{$_lang.editedon}</th>
                    <th>{$_lang.user}</th>
                    <th>{$_lang.actions}</th>
                </tr>
                </thead>
                <tbody>
                {foreach $data.results as $record}
                    <tr>
                        <td>{$record.id}</td>
                        <td class="resource">
                            <div class="{if !$record.published}unpublished{elseif $record.deleted}deleted{else}title{/if}">{$record.pagetitle}</div>
                            {if !empty($record.action)}
                                <div>
                                    <small>{$record.action}</small>
                                </div>
                            {/if}
                        </td>
                        <td class="occurred">
                            {if $record.editedon}
                                <div class="occurred-date">{$record.editedon_date}</div>
                                <div class="occurred-time">{$record.editedon_time}</div>
                            {elseif $record.createdon}
                                <div class="occurred-date">{$record.createdon_date}</div>
                                <div class="occurred-time">{$record.createdon_time}</div>
                            {/if}
                        </td>
                        <td class="user-with-avatar">
                            <div class="user-avatar">
                                {if $record.photo}
                                    <img src="{$record.photo}">
                                {else}
                                    <i class="icon icon-user icon-2x"></i>
                                {/if}
                            </div>
                            <div class="user-data">
                                <div class="user-name">{$record.fullname}</div>
                                <div class="user-group">{$record.group}</div>
                            </div>
                        </td>
                        <td class="widget-actions">
                            {foreach $record.menu as $menu}
                                {if empty($menu.text) || $menu.text == '-' || !isset($menu.params)}
                                    {continue}
                                {/if}

                                {if $menu.params.type == 'view'}
                                    {$icon='icon icon-eye'}
                                {elseif $menu.params.type == 'edit'}
                                    {$icon='icon icon-edit'}
                                {elseif $menu.params.type == 'open'}
                                    {$icon='icon icon-external-link'}
                                {else}
                                    {$icon=null}
                                {/if}

                                {if !empty($menu.params.a) && !empty($menu.params.id)}
                                    <a href="{$_config.manager_url}?a={$menu.params.a}&id={$menu.params.id}"
                                       title="{$menu.text}">
                                        {if $icon}<i class="{$icon}"></i>{else}{$menu.text}{/if}
                                    </a>
                                {elseif $menu.params.url}
                                    <a href="{$menu.params.url}" title="{$menu.text}" target="_blank">
                                        {if $icon}<i class="{$icon}"></i>{else}{$menu.text}{/if}
                                    </a>
                                {/if}
                            {/foreach}
                        </td>
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
