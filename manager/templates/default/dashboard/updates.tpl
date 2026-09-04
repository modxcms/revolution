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
            {if $extras.updateable}
                <tr>
                    <td>
                        <span class="updates-title">{$_lang.updates_extras}</span>
                        <span class="updates-updateable">
                            {if $extras.updateable > 10}10+{else}{$extras.updateable}{/if}
                        </span>
                        {if $extras.names}
                            <div class="updates-package-names">
                                {foreach $extras.names as $pkgName name="pkgList"}
                                    {if $pkgList@index < 10}{$pkgName|escape}{if $pkgList@index < 9}, {/if}{/if}
                                {/foreach}
                                {if $extras.updateable > 10} ...{/if}
                            </div>
                        {/if}
                    </td>
                    <td><span class="updates-available">{$_lang.updates_available}</span></td>
                    <td>
                        <a href="{$_config.manager_url}?a=workspaces"
                           class="dashboard-button package">{$_lang.updates_update}</a>
                    </td>
                </tr>
            {else}
                <tr>
                    <td><span class="updates-title">{$_lang.updates_extras}</span></td>
                    <td><span class="updates-ok">{$_lang.updates_ok}</span></td>
                    <td><button class="dashboard-button package" disabled>{$_lang.updates_update}</button></td>
                </tr>
            {/if}
            <tr>
                <td><span class="updates-title">MODX</span></td>
                {if $modx.updateable}
                    <td><span class="updates-available">{$modx.latest.version}</span></td>
                    <td>
                        <a href="javascript:;"
                            data-download-id="{$modx.latest.downloadId}"
                            class="dashboard-button modx modx-upgrade-btn">
                            {$_lang.updates_upgrade_modx}
                        </a>
                        <a href="javascript:;"
                            data-download-id="{$modx.latest.downloadId}"
                            class="dashboard-button modx modx-download-link">
                            {$_lang.download}
                        </a>
                    </td>
                {else}
                    <td><span class="updates-ok">{$_lang.updates_ok}</span></td>
                    <td><button class="dashboard-button modx" disabled>{$_lang.download}</button></td>
                {/if}
            </tr>
            </tbody>
        </table>
    </div>
</div>

{literal}
    <script>
        (function() {
            var grid = document.getElementById('modx-grid-updates');
            var upgradeBtn = grid && grid.querySelector('.modx-upgrade-btn');
            var downloadLink = grid && grid.querySelector('.modx-download-link');

            function requestModxUpdate(btn, action, msgKey, onSuccess) {
                var downloadId = btn.getAttribute('data-download-id');
                if (!downloadId) return;
                var mask = new Ext.LoadMask(grid, { msg: _(msgKey) });
                mask.show();
                MODx.Ajax.request({
                    url: MODx.config.connector_url,
                    params: { action: action, downloadId: downloadId },
                    listeners: {
                        success: function(response) {
                            onSuccess(response, mask);
                        },
                        failure: function() {
                            mask.hide();
                        }
                    }
                });
            }

            if (upgradeBtn) {
                upgradeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    requestModxUpdate(this, 'SoftwareUpdate/UpgradeCore', 'updates_upgrading', function(response, mask) {
                        var url = response.object && response.object.redirect_url;
                        if (url) {
                            window.location.href = url;
                        } else {
                            mask.hide();
                        }
                    });
                });
            }

            if (downloadLink) {
                downloadLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    requestModxUpdate(this, 'SoftwareUpdate/GetFile', 'downloading', function(response, mask) {
                        var url = response.object && response.object.zip;
                        if (url) {
                            window.location.assign(url);
                            setTimeout(function() { mask.hide(); }, 1000);
                        } else {
                            mask.hide();
                        }
                    });
                });
            }
        })();
    </script>
    {/literal}
