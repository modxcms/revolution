/**
 * Displays a dropdown list of modTransportProviders
 *
 * @class MODx.combo.Provider
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of options.
 * @xtype combo-provider
 */
MODx.combo.Provider = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'provider'
        ,hiddenName: 'provider'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Workspace/Providers/GetList'
            ,combo: true
        }
        ,editable: false
        ,pageSize: 20
    });
    MODx.combo.Provider.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Provider,MODx.combo.ComboBox);
Ext.reg('modx-combo-provider',MODx.combo.Provider);

/**
 * Format a lexicon string that uses [[+name]] for the active provider.
 *
 * @param {String} key
 * @param {String} name
 * @returns {String}
 */
MODx.formatProviderLexicon = (key, name) => _(key).split('[[+name]]').join(name || '');

/**
 * Refresh chrome that displays the active provider name.
 */
MODx.updateProviderUI = () => {
    const btn = Ext.getCmp('modx-package-provider-btn');
    if (btn) {
        btn.setText(MODx.formatProviderLexicon('provider_with_name', MODx.providerName));
    }
};

/**
 * Set the active package provider. When persisting, writes default_provider first,
 * then updates in-memory state and UI so a failed save leaves the previous provider.
 *
 * @param {String|Number} id
 * @param {String} name
 * @param {Object} options
 * @param {Boolean} [options.persist=true]
 * @param {Function} [options.callback]
 * @param {Object} [options.scope]
 */
MODx.setActiveProvider = (id, name, options = {}) => {
    const providerId = String(id);
    const providerName = name || '';
    const scope = options.scope || window;

    const applyLocal = () => {
        MODx.provider = providerId;
        MODx.providerName = providerName;
        MODx.defaultProvider = providerId;
        if (MODx.config) {
            MODx.config.default_provider = providerId;
        }

        const tree = Ext.getCmp('modx-package-browser-tree');
        if (tree && tree.setProvider) {
            tree.setProvider(providerId);
        }

        MODx.updateProviderUI();
    };

    const finish = () => {
        if (typeof options.callback === 'function') {
            options.callback.call(scope);
        }
    };

    if (options.persist === false) {
        applyLocal();
        finish();
        return;
    }

    MODx.Ajax.request({
        url: MODx.config.connector_url
        ,params: {
            action: 'Workspace/Providers/SetDefault'
            ,id: providerId
        }
        ,listeners: {
            success: {
                fn: () => {
                    applyLocal();
                    finish();
                }
                ,scope: scope
            }
            ,failure: {
                fn: (r) => {
                    MODx.msg.alert(_('error'), (r && r.message) ? r.message : _('provider_err_save'));
                }
                ,scope: scope
            }
        }
    });
};
