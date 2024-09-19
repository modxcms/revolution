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
                    <td><span class="updates-available">{$modx.latest.version}</span></td>
                    <td>
                        <a 
                            href="javascript:;"
                            data-download-id="{$modx.latest.downloadId}"
                            class="dashboard-button modx"
                            target="_blank"
                        >
                            {$_lang.download}
                        </a>
                    </td>
                {else}
                    <td><span class="updates-ok">{$_lang.updates_ok}</span></td>
                    <td><button class="dashboard-button modx" disabled>{$_lang.download}</button></td>
                {/if}
            </tr>
            <tr>
                {if $extras.updateable}
                    <td>
                        <span class="updates-title">{$_lang.updates_extras}</span>
                        <span class="updates-updateable">
                            {if $extras.updateable > 10}10+{else}{$extras.updateable}{/if}
                        </span>
                    </td>
                    <td><span class="updates-available">{$_lang.updates_available}</span></td>
                    <td>
                        <a href="{$_config.manager_url}?a=workspaces"
                           class="dashboard-button package">{$_lang.updates_update}</a>
                    </td>
                {else}
                    <td><span class="updates-title">{$_lang.updates_extras}</span></td>
                    <td><span class="updates-ok">{$_lang.updates_ok}</span></td>
                    <td><button class="dashboard-button package" disabled>{$_lang.updates_update}</button></td>
                {/if}
            </tr>
            </tbody>
        </table>
    </div>
</div>

{literal}
    <script>
        const
            updatesGrid = document.getElementById('modx-grid-updates'),
            modxLinks = document.querySelectorAll('.dashboard-button.modx')
        ;
        modxLinks.forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const updatesMask = new Ext.LoadMask(updatesGrid, {
                    msg: _('downloading')
                });
                updatesMask.show();
                MODx.Ajax.request({
                    url: MODx.config.connector_url,
                    params: {
                        action: 'SoftwareUpdate/GetFile',
                        downloadId: link.dataset.downloadId
                    },
                    scope: this,
                    listeners: {
                        success: {
                            fn: function(response) {
                                const url = response.object?.zip;
                                if (!Ext.isEmpty(url)) {
                                    window.location.assign(url);
                                    setTimeout(() => {
                                        updatesMask.hide();
                                    }, 1000);
                                }
                            },
                            scope:this
                        },
                        failure: {
                            fn: function(response) {
                                updatesMask.hide();
                            },
                            scope:this
                        }
                    }
                });
            });
        });
    </script>
    {/literal}