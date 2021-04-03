<div id="modx-grid-updates" class="updates-widget">
    <div class="table-wrapper">
        <table class="table">
            <thead>
            <tr>
                <th>{$_lang.updates_type}</th>
                <th>{$_lang.updates_status}</th>
                <th>{$_lang.updates_action}</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><span class="updates-title">MODX</span></td>
                {if $modx.updateable}
                    <td><span class="updates-available">{$modx.full_version}</span></td>
                    <td><a href="https://modx.com/download" class="dashboard-button"
                           target="_blank">{$_lang.updates_update}</a></td>
                {else}
                    <td><span class="updates-ok">{$_lang.updates_ok}</span></td>
                    <td><button class="dashboard-button" disabled>{$_lang.updates_update}</button></td>
                {/if}
            </tr>
            <tr>
                {if $packages.updateable}
                    <td>
                        <span class="updates-title">{$_lang.updates_extras}</span>
                        <span class="updates-updateable">
                            {if $packages.updateable > 10}10+{else}{$packages.updateable}{/if}
                        </span>
                    </td>
                    <td><span class="updates-available">{$_lang.updates_available}</span></td>
                    <td>
                        <a href="{$_config.manager_url}?a=workspaces"
                           class="dashboard-button">{$_lang.updates_update}</a>
                    </td>
                {else}
                    <td><span class="updates-title">{$_lang.updates_extras}</span></td>
                    <td><span class="updates-ok">{$_lang.updates_ok}</span></td>
                    <td><button class="dashboard-button" disabled>{$_lang.updates_update}</button></td>
                {/if}
            </tr>
            </tbody>
        </table>
    </div>
</div>
