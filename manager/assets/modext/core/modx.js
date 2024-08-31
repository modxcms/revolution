Ext.namespace('MODx');
Ext.apply(Ext,{
    isFirebug: (window.console && window.console.firebug)
});

/*
    Note that currently (07/2022) it is practically impossible to
    force Chrome to turn autocomplete off. Other browsers will comply
    with autocomplete="off". Creating a global value here so we can easily
    manage the attribute in one place.

    The old trick of setting autocomplete="new-password" for non-password
    fields appears to no longer work (Chrome)
*/
const globalAutoCompleteSetting = 'off';

/* work around IE9 createContextualFragment bug
   http://www.sencha.com/forum/showthread.php?125869-Menu-shadow-probolem-in-IE9
 */
if ((typeof Range !== "undefined") && !Range.prototype.createContextualFragment)
{
	Range.prototype.createContextualFragment = function(html)
	{
		var frag = document.createDocumentFragment(),
		div = document.createElement("div");
		frag.appendChild(div);
		div.outerHTML = html;
		return frag;
	};
}
/**
 * @class MODx
 * @extends Ext.Component
 * @param {Object} config An object of config properties
 * @xtype modx
 */
MODx = function(config) {
    config = config || {};
    MODx.superclass.constructor.call(this,config);
    this.config = config;
    this.startup();
};
Ext.extend(MODx,Ext.Component,{
    config: {}
    ,util:{},window:{},panel:{},tree:{},form:{},grid:{},combo:{},toolbar:{},page:{},msg:{}
    ,expandHelp: true
    ,defaultState: []

    /**
     * Tracks our custom non click event blocking 'pseudo' modals; should contain
     * an object for each currently open modal containing at minimum a reference to
     * the modal window’s id (itemId).
     */
    ,openPseudoModals: []

    /**
     * An Ext.Element object containing the page mask created by pseudo modals.
     */
    ,mask: {}

    ,startup: function() {
        this.initQuickTips();
        this.initMarkRequiredFields();
        this.request = this.getURLParameters();
        this.Ajax = this.load({ xtype: 'modx-ajax' });
        Ext.override(Ext.form.Field, {
            defaultAutoCreate: {
                tag: 'input',
                type: 'text',
                size: '20',
                autocomplete: globalAutoCompleteSetting,
                msgTarget: 'under'
            }
        });
        Ext.override(Ext.form.TextArea,{
            onRender: function(ct, position) {
                if (!this.el){
                    this.defaultAutoCreate = {
                        tag: 'textarea',
                        style: 'width:100px;height:60px;',
                        autocomplete: globalAutoCompleteSetting
                    };
                }
                Ext.form.TextArea.superclass.onRender.call(this, ct, position);
                if (this.grow){
                    this.textSizeEl = Ext.DomHelper.append(document.body, {
                        tag: 'pre', cls: 'x-form-grow-sizer'
                    });
                    if(this.preventScrollbars){
                        this.el.setStyle('overflow', 'hidden');
                    }
                    this.el.setHeight(this.growMin);
                }
            }
        });

        Ext.Ajax.on('requestexception',this.onAjaxException,this);
        Ext.menu.Menu.prototype.enableScrolling = false;
        this.addEvents({
            beforeClearCache: true
            ,beforeLogout: true
            ,beforeReleaseLocks: true
            ,beforeLoadPage: true
            ,afterClearCache: true
            ,afterLogout: true
            ,afterReleaseLocks: true
            ,ready: true
        });
    }

    /**
     * Add the given component to the modx-content container
     *
     * @param {String|Object} cmp Either a component xtype (string) or an object/configuration
     *
     * @return void
     */
    ,add: function(cmp) {
        if (typeof cmp === 'string') {
            cmp = { xtype: cmp }
        }
        var ctr = Ext.getCmp('modx-content');
        if (ctr) {
            ctr.removeAll();
            ctr.add(cmp);
            ctr.doLayout();
        }
    }

    ,load: function() {
        var a = arguments, l = a.length;
        var os = [];
        for(var i=0;i<l;i=i+1) {
            if (!a[i].xtype || a[i].xtype === '') {
                return false;
            }
            os.push(Ext.ComponentMgr.create(a[i]));
        }
        return (os.length === 1) ? os[0] : os;
    }

    ,initQuickTips: function() {
        Ext.QuickTips.init();
        Ext.apply(Ext.QuickTips.getQuickTip(), {
            dismissDelay: 2300
            ,interceptTitles: true
        });
    }

    ,initMarkRequiredFields: function() {

        const markerEl = '<span class=\"field-required-mark\">*</span>';

        const MarkRequiredFieldPlugin = function (config) {
            config = config || {};
            Ext.apply(config, {
                init: function(cmp) {

                    if (cmp.allowBlank !== false) return;

                    const cmpLabel = cmp.fieldLabel;

                    if (cmpLabel) {
                        cmp.fieldLabel = cmpLabel + markerEl;
                    } else {
                        let labelEl = null;
                        if (cmp.caption && document.getElementById(cmp.caption)) {
                            labelEl = document.getElementById(cmp.caption);
                        } else {
                            const id = cmp.itemId;
                            if (id && id.match(/^tv[\d]*$/i)) {
                                labelEl = document.getElementById(`${id}-caption`);
                            }
                        }
                        if (labelEl) {
                            labelEl.innerHTML = labelEl.innerHTML + markerEl;
                        }
                    }
                }
            });
            MarkRequiredFieldPlugin.superclass.constructor.call(this, config);
        }
        Ext.extend(MarkRequiredFieldPlugin, Ext.BoxComponent);
        Ext.ComponentMgr.registerPlugin('markrequiredfields',MarkRequiredFieldPlugin);

        if (!Array.isArray(Ext.form.Field.prototype.plugins)) {
            Ext.form.Field.prototype.plugins = [];
        }
        var plugins = Ext.form.Field.prototype.plugins;
        Ext.form.Field.prototype.plugins = Ext.form.Field.prototype.plugins.concat(['markrequiredfields'], plugins);
    }

    ,getURLParameters: function() {
        var arg = {};
        var href = window.location.search;

        if (href.indexOf('?') !== -1) {
            var params = href.split('?')[1];
            var param = params.split('&');
            for (var i=0; i<param.length;i=i+1) {
                arg[param[i].split('=')[0]] = param[i].split('=')[1];
            }
        }
        return arg;
    }

    ,onAjaxException: function(conn,r,opt,e) {
        try {
            r = Ext.decode(r.responseText);
        } catch (e) {
            var text, matched = r.responseText.match(/<body[^>]*>([\w|\W]*)<\/body>/im);
            if (typeof(matched[1] !== 'undefined')) {
                text = '<p>'+e.message+':</p>'+matched[1];
            } else {
                text = e.message+': '+ r.responseText;
            }
            Ext.MessageBox.show({
                title: _('error')
                ,msg: text
                ,buttons: Ext.MessageBox.OK
                ,cls: 'modx-js-parse-error'
                ,minWidth: 600
                ,maxWidth: 750
                ,modal: false
                ,width: 600
            });
        }
        if (r && (r.code == 401 || (r.object && r.object.code == 401))) {
            if (!MODx.loginWindow) {
                MODx.loginWindow = MODx.load({
                    xtype: 'modx-window-login'
                    ,username: MODx.user.username
                });
            }
            MODx.loginWindow.show();
        }
    }

    ,loadAccordionPanels: function() { return []; }

    ,clearCache: function() {
        if (!this.fireEvent('beforeClearCache')) { return false; }

        var topic = '/clearcache/';
        this.console = MODx.load({
           xtype: 'modx-console'
           ,register: 'mgr'
           ,topic: topic
           ,clear: true
           ,show_filename: 0
           ,listeners: {
                'shutdown': {fn:function() {
                    if (this.fireEvent('afterClearCache')) {
                        if (MODx.config.clear_cache_refresh_trees == 1) {
                            Ext.getCmp('modx-layout').refreshTrees();
                        }
                    }
                },scope:this}
           }
        });

        this.console.show(Ext.getBody());

        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'System/ClearCache'
                ,register: 'mgr'
                ,topic: topic
                ,media_sources: true
                ,menu: true
                ,action_map: true
            }
            ,listeners: {
                'success':{fn:function() {
                    this.console.fireEvent('complete');
                },scope:this}
            }
        });
        return true;
    }

    ,refreshURIs: function() {
        var topic = '/refreshuris/';
        MODx.msg.status({
            title: _('please_wait'),
            message: _('refreshuris_desc'),
            dontHide: true
        });
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'System/RefreshUris'
                ,register: 'mgr'
                ,topic: topic
                ,menu: true
            }
            ,listeners: {
                'success':{fn:function(r) {
                    MODx.msg.status({
                        title: _('success')
                        ,message: r.message || _('refresh_success')
                        ,dontHide: false
                    });
                    this.clearCache();
                },scope:this}
            }
        });
        return true;
    }
    ,releaseLock: function(id) {
        if (this.fireEvent('beforeReleaseLocks')) {
            MODx.Ajax.request({
                url: MODx.config.connector_url
                ,params: {
                    action: 'Resource/Locks/Release'
                    ,id: id
                }
                ,listeners: {
                    'success':{fn:function(r) { this.fireEvent('afterReleaseLocks',r); },scope:this}
                }
            });
        }
    }
    ,removeLocks: function(id) {
		MODx.msg.confirm({
			title: _('remove_locks')
			,text: _('confirm_remove_locks')
			,url: MODx.config.connectors_url
			,params: {
				action: 'System/RemoveLocks'
			}
			,listeners: {
				'success': {
					fn:function() {
						var tree = Ext.getCmp("modx-resource-tree");

						if (tree && tree.rendered) {
							tree.refresh();
						}

						var cmp = Ext.getCmp("modx-panel-resource");

						if (cmp) {
							Ext.getCmp('modx-abtn-locked').hide();
							Ext.getCmp('modx-abtn-save').show();
						}
					},
					scope:this
				}
			}
		});
    }

    ,sleep: function(ms) {
        var s = new Date().getTime();
        for (var i=0;i < 1e7;i++) {
            if ((new Date().getTime() - s) > ms){
                break;
            }
        }
    }

    ,logout: function() {
        if (this.fireEvent('beforeLogout')) {
            MODx.msg.confirm({
                title: _('logout')
                ,text: _('logout_confirm')
                ,url: MODx.config.connector_url
                ,params: {
                    action: 'Security/Logout'
                    ,login_context: 'mgr'
                }
                ,listeners: {
                    success: {
                        fn: function(r) {
                            MODx.maskConfig.destroySessionConfig();
                            if (this.fireEvent('afterLogout', r)) {
                                window.location.href = './';
                            }
                        },
                        scope: this
                    }
                }
            });
        }
    }

    ,getPageStructure: function(v,c) {
        c = c || {};
        Ext.applyIf(c,{
            xtype: 'modx-tabs'
            ,itemId: 'tabs'
            ,items: v
			,cls: 'structure-tabs'
        });
        return c;
    }

    ,setStaticElementPath: function(type) {
        var category   = '',
            path       = '',
            name       = '',
            nameField  = 'name',
            typePlural = type + "s";

        if (type === "template") {
            nameField = 'templatename';
        }

        if (MODx.config["static_elements_automate_" + typePlural] == 1) {
            category = Ext.getCmp("modx-" + type + "-category").getValue();
            if (category > 0) {
                Ext.Ajax.request({
                    url: MODx.config.connector_url,
                    params: {
                        action: 'Element/Category/GetList',
                        id: category,
                        limit: 0,
                    },
                    success: function (response) {
                        var data = Ext.decode(response.responseText);
                        categoryText = (data && data.success && data.results) ? data.results[0].name : '';
                        if (categoryText) {
                            name = Ext.getCmp("modx-" + type + "-" + nameField).getValue();
                            path = MODx.getStaticElementsPath(name, categoryText, typePlural);
                            Ext.getCmp("modx-" + type + "-static-file").setValue(path);
                        }
                    },
                });
            } else {
                name = Ext.getCmp("modx-" + type + "-" + nameField).getValue();
                path = MODx.getStaticElementsPath(name, '', typePlural);
                Ext.getCmp("modx-" + type + "-static-file").setValue(path);
            }
        }
    }

    ,setStaticElementsConfig: function (config, type) {
        var typePlural = type + 's';

        if (MODx.request.a === 'element/' + type + '/create' && MODx.config['static_elements_automate_' + typePlural] == 1) {
            config.record['static'] = 1;
            config.record['static_file'] = MODx.config.static_elements_basepath + typePlural + '/';
            config.record['category'] = MODx.config.static_elements_default_category;

            if (MODx.config.static_elements_default_mediasource) {
                config.record['source'] = MODx.config.static_elements_default_mediasource;
            }
        }

        return config;
    }

    ,getStaticElementsPath: function(name, category, type) {

        let path = MODx.config.static_elements_basepath,
            ext  = '';
        const htmlExtension = MODx.config.static_elements_html_extension || '.tpl';
        // console.log('cat before: ',category);
        category = category.length > 0 ? MODx.util.Format.staticElementPathFragment(category, true) : '/' ;
        // console.log('cat after: ',category);
        // Remove trailing slash.
        path = path.replace(/\/$/, '');

        switch(type) {
            case 'templates':
                ext = `.template${htmlExtension}`;
                break;
            case 'tvs':
                ext = `.tv${htmlExtension}`;
                break;
            case 'chunks':
                ext = `.chunk${htmlExtension}`;
                break;
            case 'snippets':
                ext = '.snippet.php';
                break;
            case 'plugins':
                ext = '.plugin.php';
                break;
        }

        name = MODx.util.Format.staticElementPathFragment(name);
        path += '/' + type + category;
        path += name.length > 0 ? name + ext : '' ;

        return path;
    }

    ,helpUrl: false

    ,loadHelpPane: function(b) {
        var url = MODx.helpUrl || MODx.config.help_url || '';
        if (!url || !url.length) { return false; }

        if (url.substring(0, 4) !== 'http') {
            url = MODx.config.base_help_url + url;
        }

        MODx.helpWindow = new Ext.Window({
            title: _('help')
            ,width: 850
            ,height: 500
            ,resizable: true
            ,maximizable: true
            ,modal: false
            ,layout: 'fit'
			,bodyStyle : 'padding: 0;'
            ,items: [{
	        	xtype: 'container',
				layout: {
	            	type: 'vbox',
					align: 'stretch'
				},
				width: '100%',
				height: '100%',
				items:[{
					autoEl: {
		                tag: 'iframe',
		                src: url,
		                width: '100%',
						height: '100%',
						frameBorder: 0
					}
				}]
			}]
        });
        MODx.helpWindow.show(b);
        return true;
    }

    /**
     * Adds a new tab to the specified panel; method called from code inserted via modActionDom (modactiondom.class.php)
     *
     * @param {String} panelId - Text id of the tabPanel the new tab will be added to
     * @param {Object} newTabConfig - The base configuration for the new tab
     * @return {void}
     */
    ,addTab: function(panelId, newTabConfig) {
        const tabPanel = Ext.getCmp(panelId);
        if (tabPanel) {
            Ext.applyIf(newTabConfig,{
                id: 'modx-' + Ext.id() + '-tab'
                ,layout: 'form'
                ,labelAlign: 'top'
                ,cls: 'modx-resource-tab'
                ,bodyStyle: 'padding: 15px;'
                ,autoHeight: true
                ,defaults: {
                    border: false
                    ,msgTarget: 'side'
                    ,width: 400
                }
            });
            tabPanel.add(newTabConfig);
            tabPanel.doLayout();
        }
    }
    ,hiddenTabs: []

    ,hideTab: function(ct, tab) {
        this.hideRegion(ct, tab);
    }

    /**
     * Hides a region or tab; method called from code inserted via modActionDom (modactiondom.class.php)
     * and from MODX.hideTab (above)
     *
     * @param {String} containerId - Text id of the region/tab's container
     * @param {String} regionId - Text id of the region/tab to hide
     * @return {void}
     */
    ,hideRegion: function(containerId, regionId) {
        const tabPanel = Ext.getCmp(containerId);
        if (tabPanel) {
            const tabObj = tabPanel.getItem(regionId);
            if (tabObj) {
                tabPanel.hideTabStripItem(regionId);
                MODx.hiddenTabs.push(regionId);
                tabPanel.setActiveTab(this._getNextActiveTab(tabPanel, regionId));
            } else {
                const region = Ext.getCmp(regionId);
                if (region) {
                    region.hide();
                }
            }
        }
    }

    ,_getNextActiveTab: function(tp,tab) {
        let id;
        if (MODx.hiddenTabs.indexOf(tab) != -1) {
            for (var i=0;i<tp.items.items.length;i++) {
                 id = tp.items.items[i].id;
                if (MODx.hiddenTabs.indexOf(id) == -1) { break; }
            }
        } else { id = tab; }
        return id;
    }

    /**
     * Moves a TV to the specified a region or tab; method called from code inserted via modActionDom (modactiondom.class.php)
     * and from MODX.hideTab (above)
     *
     * @param {String} tvId - Text id of the TV to move
     * @param {String} targetId - Text id of the TV's destination (region/tab)
     * @return {void}
     */
    ,moveTV: function(tvId, targetId) {
        const   sourcePanel = Ext.getCmp('modx-panel-resource-tv'),
                sourceItem = Ext.get(tvId + '-tr'),
                target = Ext.getCmp(targetId)
        ;
        if (!sourcePanel || !sourceItem || !target) {
            return;
        }

        target.add({
            html: '',
            width: '100%',
            id: 'tv-tr-out-' + tvId,
            cls: 'modx-tv-out'
        });
        target.doLayout();

        const targetItem = Ext.get('tv-tr-out-' + tvId);
        if (targetItem) {
            targetItem.replaceWith(sourceItem);
        } else {
            const id = tvId.replace('tv', '');
            console.warn('Attempted to move the TV named "'
                + sourceItem.dom.innerText + '" (id #' + id + ') to the panel with the id "'
                + target.id + '" but the original TV field could not be found. '
                + 'It is likely a conflicting customization rule has already tried to move this TV.'
            );
        }
    }

    ,hideTV: function(tvs) {
        if (!Ext.isArray(tvs)) {
            tvs = [tvs];
        }
        this.hideTVs(tvs);
    }

    ,hideTVs: function(tvs) {
        if (!Ext.isArray(tvs)) {
            tvs = [tvs];
        }
        let el;
        for (let i=0; i<tvs.length; i++) {
            el = Ext.get(tvs[i]+'-tr');
            if (el) {
                el.setVisibilityMode(Ext.Element.DISPLAY);
                el.hide();
            }
        }
    }

    /**
     * Changes the label of the specified field; method called from code inserted via modActionDom (modactiondom.class.php)
     *
     * @param {String} containerId - Text id of the field's/container's container
     * @param {String} fieldId - Text id or name of field/container whose label/title being renamed
     * @param {String} newLabel - The replacement label text
     * @return {void}
     */
    ,renameLabel: function(containerId, fieldId, newLabel) {
        if (fieldId.indexOf('modx-resource-content') !== -1) {
            const contentCmp = Ext.getCmp('ta');
            if (contentCmp) {
                contentCmp.label.update(newLabel);
            }
        } else {
            const container = Ext.getCmp(containerId);
            if (container) {
                container.setLabel(fieldId, newLabel);
            }
        }
    }

    /**
     * Renames a form tab; method called from code inserted via modActionDom (modactiondom.class.php)
     *
     * @param {String} tabId - Text id of tab being renamed
     * @param {String} newTitle - The replacement title text
     * @return {void}
     */
    ,renameTab: function(tabId, newTitle) {
        const tab = Ext.getCmp(tabId);
        if (tab) {
            tab.setTitle(newTitle);
        }
    }

    /**
     * Hides a field in the specified container; method called from code inserted via modActionDom (modactiondom.class.php)
     *
     * @param {String} containerId - Text id of the field's container
     * @param {String} fieldId - Text id or name of field being hidden
     * @return {void}
     */
    ,hideField: function(containerId, fieldId) {
        const container = Ext.getCmp(containerId);
        if (container) {
            container.hideField(fieldId);
        }
    }

    ,preview: function() {
        var url = MODx.config.site_url;
        if (MODx.config.default_site_url) {
            url = MODx.config.default_site_url;
        }
        window.open(url);
    }
    ,makeDroppable: function(fld,h,p) {
        if (!fld) return false;
        h = h || Ext.emptyFn;
        if (fld.getEl) {
            var el = fld.getEl();
        } else if (fld) {
            el = fld;
        }
        if (el) {
            new MODx.load({
                xtype: 'modx-treedrop'
                ,target: fld
                ,targetEl: el.dom
                ,onInsert: h
                ,panel: p || 'modx-panel-resource'
            });
        }
        return true;
    }
    ,debug: function(msg) {
        if (MODx.config.ui_debug_mode == 1) {
            console.log(msg);
        }
    }

    ,isEmpty: function(v) {
        return Ext.isEmpty(v) || v === false || v === 'false' || v === 'FALSE' || v === '0' || v === 0;
    }

    ,createResource: function(record) {
        if (MODx.createResourceWindow) {
            MODx.createResourceWindow.destroy();
        }

        MODx.createResourceWindow = MODx.load({
            xtype: 'modx-window-create-resource',
            record: record,
            closeAction: 'close',
            listeners: {
                'success': {
                    fn: function(r) {
                        MODx.loadPage('?a=resource/update&id=' + r.a.result.object.id);
                    },
                    scope: this
                },
                'failure': {
                    fn: function(data, data2) {
                        console.log('failure');
                        console.log(data);
                        console.log(data2);
                    },
                    scope: this
                }
            }
        });

        MODx.createResourceWindow.setValues(record);
        MODx.createResourceWindow.show();
    }

    ,switchLanguage: function(lang) {
        var params = {
            switch: lang
        };
        Ext.iterate(MODx.request, function (key, value) {
            params['target_' + key] = value;
        });
        MODx.loadPage('language', params);
    }
});
Ext.reg('modx',MODx);

/**
 * An override class for Ext.Ajax, which adds success/failure events.
 *
 * @class MODx.Ajax
 * @extends Ext.Component
 * @param {Object} config An object of config properties
 * @xtype modx-ajax
 */
MODx.Ajax = function(config) {
    config = config || {};
    MODx.Ajax.superclass.constructor.call(this, config);
};
Ext.extend(MODx.Ajax,Ext.Component,{
    request: function(config) {
        Ext.apply(config,{
            success: function(r,o) {
                r = Ext.decode(r.responseText);
                if (!r) {
                    return false;
                }
                r.options = o;
                if (r.success) {
                    if (config.listeners.success && config.listeners.success.fn) {
                        this._runCallback(config.listeners.success, [r]);
                    }
                } else if (config.listeners.failure && config.listeners.failure.fn) {
                    this._runCallback(config.listeners.failure, [r]);
                    MODx.form.Handler.errorJSON(r);
                }
                return true;
            }
            ,failure: function(r, o) {
                r = Ext.decode(r.responseText);
                if (!r) {
                    return false;
                }
                r.options = o;
                if (config.listeners.failure && config.listeners.failure.fn) {
                    this._runCallback(config.listeners.failure, [r]);
                    MODx.form.Handler.errorJSON(r);
                }
                return true;
            }
            ,scope: this
            ,headers: {
                'Powered-By': 'MODx'
                ,'modAuth': config.auth
            }
        });
        Ext.Ajax.request(config);
    }
    /**
     * Execute the listener callback
     *
     * @param {Object} config - The listener configuration (ie.failure/success)
     * @param {Array} args - An array of arguments to pass to the callback
     */
    ,_runCallback: function(config, args) {
        var scope = window
            ,fn = config.fn;

        if (config.scope) {
            scope = config.scope;
        }
        fn.apply(scope || window, args);
    }
});
Ext.reg('modx-ajax',MODx.Ajax);


MODx = new MODx();

/**
 * Used to fetch and control window and modal backdrops, as well as grid masks.
 * Note: This class is instantiated after the full MODx config has been loaded (currently in header.tpl)
 * @param {Object} config 
 */
MODx.MaskManager = function(config = {}) {
    this.settingsKeys = {
        modal: {
            disabled: 'mask_disabled_modal',
            color: 'mask_color_modal',
            opacity: 'mask_opacity_modal'
        },
        pseudomodal: {
            disabled: 'mask_disabled_pseudomodal',
            color: 'mask_color_pseudomodal',
            opacity: 'mask_opacity_pseudomodal'
        }
    };
    this.settingsXtypes = {
        disabled: 'combo-boolean',
        color: 'textfield',
        opacity: 'numberfield'
    };
    Ext.apply(config, {
        attributes: {
            modal: {
                disabled: MODx.util.Types.castToBoolean(MODx.config.mask_disabled_modal),
                color: MODx.config.mask_color_modal || '#ffffff',
                opacity: parseInt(MODx.config.mask_opacity_modal) / 100 || 0.5
            },
            pseudomodal: {
                disabled: MODx.util.Types.castToBoolean(MODx.config.mask_disabled_pseudomodal),
                color: MODx.config.mask_color_pseudomodal || '#0d141d',
                opacity: parseInt(MODx.config.mask_opacity_pseudomodal) / 100 || 0.5
            },
            grid: {
                disabled: false,
                color: MODx.config.mask_color_grid || '#ffffff',
                opacity: parseInt(MODx.config.mask_opacity_grid) / 100 || 0.5
            }
        }
    });
    this.config = config;
    MODx.MaskManager.superclass.constructor.call(this, config);
    this.addEvents({
        actionsReady: false,
        actionsDone: false,
        actionsFail: false
    });
    this.on({
        actionsReady: function() {
            // console.log('Continuing ... writing changes ... this:', this);
            this.commitSettingsChanges();
        },
        actionsDone: function() {
            // MODx.msg.status({
            //     title: 'Action Complete',
            //     message: 'Updates to your mask configuration settings were successful!'
            // });
            console.log('actionsDone :: this', this);
            if (this.saveStatus) {
                this.saveStatus.exit();
            }
        },
        actionsFail: function(response) {
            // MODx.msg.status({
            //     title: 'Action Complete',
            //     message: 'Updates to your mask configuration settings were successful!'
            // });
            console.log('actionsFail :: response', response);
            if (this.saveStatus) {
                this.saveStatus.exit();
            }
        },
        /*
            @ NEW SESSION:
                - MODx.config will have all correct settings vals

            @ GRID SETTINGS CHANGE:
                If to User...
                    - This one's easy, as User has top precedence; update session and 
                      cache with new value without additional checks
                If to Usergroup...
                    - If no matching key exists for User
                        ? Is User in multiple groups (which takes precedence?)
                            Y - Check for key in higher precedence group, if any
                            N - Update session data and overrides obj
                        ? How to check for current User keys
                            1 - Always via db query, OR
                            2 - Query db at session start, add key(s) if any to
                                session LS history, then add/remove as needed on
                                change (session overrides obj, no db queries needed)

            ## TRACK KEY EXISTENCE at User and Usergroup levels with overrides obj
                overrides: {
                    user: ['key1', 'key2', ...],
                    groups: {
                        // obj keys correspond to usergroup id
                        1: ['key1', 'key2', ...],
                        3: ['key1', 'key2', ...],
                        ...
                    }
                }
        */
        /**
         * Fired after direct changes (via settings grids) to mask configuration
         * are made. Triggers update of the mask session values as needed
         * to ensure changes are immediately reflected in the UI (without reloading)
         * @param {Object} response The post-save response data
         */
        syncSettingFromGrid: function(response) {
            console.log('createSettingFromGrid :: response', response);
            const { action, data, gridType } = response;
            if (typeof data?.key?.length) {
                const { type, attribute } = this.getMaskPropNamesFromKey(data.key);
                console.log(`
                    syncSettingFromGrid ::
                    Action: ${action}
                    Mask type: ${type}
                    Mask attr: ${attribute}
                    Grid Type: ${gridType}
                `);
                if (type && attribute) {
                    /*
                        Note that for the session values, we want the decimal opacity
                        value (for direct use in css) instead of the whole number equivalent
                        which is used for the setting value itself
                    */
                        const 
                        value = action === 'remove' ? this.getMaskAttribute(type, attribute, true) : data.value,
                        rawValue = attribute === 'opacity' && value > 1 ? value / 100 : value, 
                        newValue = this.prepareSettingValue(data.xtype, rawValue)
                    ;
                    if (this.hasSessionConfig) {
                        const overridesData = {
                            action: action,
                            gridType: gridType,
                            key: data.key
                        };
                        switch(gridType) {
                            case 'user':
                            case 'system':
                                // do something
                                break;
                            case 'usergroup':
                                if (data?.group && MODx.config.user_usergroups.includes(data.group)) {
                                    console.log(`Sync settings for usergroup ${data.group}`);
                                    overridesData.groupId = data.group
                                } 
                                break;
                            // no default
                        }
                        this.updateSessionConfig(type, {
                            [attribute]: newValue
                        }, true, overridesData);
                    }
                    /*
                        When enabling previously disabled mask via the settings grids,
                        the mask element will need to be created here to ensure it
                        appears on subsequent window openings (without reloading page)
                    */
                   /*
                    let mask = document.querySelector(`.ext-el-mask.${type}`);
                    if (!mask) {
                        const referenceEl = Ext.getBody().last();
                        // console.log('Trying to create mask before this el:', referenceEl);
                        mask = MODx.maskConfig.createMask(referenceEl, type);
                    }
                    if (attribute === 'color') {
                        mask.style.backgroundColor = newValue;
                    }
                    */
                }
            }
        },
        createSettingFromGrid: function(data) {
            console.log('createSettingFromGrid :: data', data);
        },
        
        updateSettingFromGrid: function(data) {
            console.log('updateSettingFromGrid :: data', data);
            if (typeof data?.key?.length) {
                const { type, attribute } = this.getMaskPropNamesFromKey(data.key);
                console.log(`
                    updateSettingFromGrid ::
                    Mask type: ${type}
                    Mask attr: ${attribute}
                `);
                if (type && attribute) {
                    /*
                        Note that for the session values, we want the decimal opacity
                        value (for direct use in css) instead of the whole number equivalent
                        which is used for the setting value itself
                    */
                    const 
                        rawValue = attribute === 'opacity' && data.value > 1 ? data.value / 100 : data.value, 
                        newValue = this.prepareSettingValue(data.xtype, rawValue)
                    ;
                    if (this.hasSessionConfig) {
                        this.updateSessionConfig(type, {
                            [attribute]: newValue
                        });
                    }
                    /*
                        When enabling previously disabled mask via the settings grids,
                        the mask element will need to be created here to ensure it
                        appears on subsequent window openings (without reloading page)
                    */
                    let mask = document.querySelector(`.ext-el-mask.${type}`);
                    if (!mask) {
                        const referenceEl = Ext.getBody().last();
                        // console.log('Trying to create mask before this el:', referenceEl);
                        mask = MODx.maskConfig.createMask(referenceEl, type);
                    }
                    if (attribute === 'color') {
                        mask.style.backgroundColor = newValue;
                    }
                }
            }
        },
        /**
         * Upon setting removal, updates the session mask config (if present)
         * with the appropriate fallback value for the removed setting;
         * @param {Object} data 
         */
        removeSettingFromGrid: function(data) {
            console.log('removeSettingFromGrid :: data', data);
            const record = data.record;
            if (typeof record?.key?.length) {
                const { type, attribute } = this.getMaskPropNamesFromKey(record.key);
                console.log(`
                    removeSettingFromGrid ::
                    Mask type: ${type}
                    Mask attr: ${attribute}
                `);
                if (type && attribute) {
                    /*
                        Note that for the session values, we want the decimal opacity
                        value (for direct use in css) instead of the whole number equivalent
                        which is used for the setting value itself
                    */
                    const 
                        // rawValue = attribute === 'opacity' && data.value > 1 ? data.value / 100 : data.value,
                        value = this.getMaskAttribute(type, attribute, true),
                        rawValue = attribute === 'opacity' && value > 1 ? value / 100 : value, 
                        newValue = this.prepareSettingValue(record.xtype, rawValue)
                    ;
                    if (this.hasSessionConfig) {
                        this.updateSessionConfig(type, {
                            [attribute]: newValue
                        });
                    }
                    /*
                        When enabling previously disabled mask via the settings grids,
                        the mask element will need to be created here to ensure it
                        appears on subsequent window openings (without reloading page)
                    */
                    let mask = document.querySelector(`.ext-el-mask.${type}`);
                    if (!mask) {
                        const referenceEl = Ext.getBody().last();
                        // console.log('Trying to create mask before this el:', referenceEl);
                        mask = MODx.maskConfig.createMask(referenceEl, type);
                    }
                    if (attribute === 'color') {
                        mask.style.backgroundColor = newValue;
                    }
                }
            }
        }
    });
};
Ext.extend(MODx.MaskManager, Ext.Component, {
    sessionMaskKey: 'sessionMaskConfig',
    cache: {},
    hasSessionConfig: false,
    saveStatus: null,
    /**
     * 
     * @param {Ext.Element} reference The element this mask should be inserted before
     * @param {String} type The window type
     * @param {String} event 
     * @param {*} returnMask 
     * @returns 
     */
    createMask: function(reference, type = 'pseudomodal', event = 'render', returnMask = true) {
        let ready;
        // Note that window reference components will have an el property 
        // while other general elements will not
        const insertBefore = reference?.el?.dom || reference.dom;
        if (type === 'pseudomodal') {
            ready = event === 'render'
                ? MODx.openPseudoModals.length === 0
                : MODx.openPseudoModals.length >= 1
            ;
            if (ready && MODx.util.isEmptyObject(MODx.mask)) {
                MODx.mask = Ext.getBody().createChild({ cls: 'ext-el-mask pseudomodal' }, insertBefore);
                MODx.mask.setStyle('background-color', MODx.maskConfig.getMaskAttribute('pseudomodal', 'color'));
                MODx.mask.hide();
                if (returnMask) {
                    return MODx.mask;
                }
            }
        }
    },
    /**
     * Get a mask's css value (or disabled status) based on its window type and attribute
     * @param {String} type The window type (modal, pseudomodal)
     * @param {String} attribute The mask attribute to get (color, opacity, disabled)
     * @returns The current value of the requested attribute
     */
    getMaskAttribute: function(type, attribute, getFallback = false) {
        const sessionBranch = getFallback ? 'fallback' : 'current' ;
        // if (!getFallback && !MODx.util.isEmptyObject(this.cache)) {
        //     console.log(`Getting attr from cache (${sessionBranch} branch) ...`, this.cache);
        //     return this.cache.attributes[type][attribute];
        // }
        if (!MODx.util.isEmptyObject(this.cache)) {
            console.log(`Getting attr from cache (${sessionBranch} branch) ...`, this.cache);
            return this.cache[sessionBranch][type][attribute];
        }
        const sessionConfig = this.getSessionConfig();
        if (!sessionConfig) {
            console.log(`Getting ${type} ${attribute} from initial config: (${typeof this.attributes[type][attribute]}) ${this.attributes[type][attribute]}; MODx config val = (${typeof MODx.config[this.settingsKeys[type][attribute]]}) ${MODx.config[this.settingsKeys[type][attribute]]}`);
            return this.attributes[type][attribute];
        }
        console.log(`Getting attr from session storage (${sessionBranch} branch) ...`, this.cache);
        // return sessionConfig?.attributes[type][attribute];
        return sessionConfig[sessionBranch][type][attribute];
    },
    createSessionConfig: function() {
        console.log('Initial MODx config:', MODx.config);
        const config = {
            current: this.config.attributes,
            fallback: this.config.attributes,
            overrides: {
                user: [],
                groups: new Map()
            }
        };
        MODx.config.user_usergroups.forEach(groupId => {
            config.overrides.groups.set(groupId, []);
        });

        console.log('session config skel:', config);
        this.saveSessionConfig(config);
        this.hasSessionConfig = true;
    },
    saveSessionConfig: function(config) {
        this.cache = config;
        localStorage.setItem(this.sessionMaskKey, JSON.stringify(config, MODx.util.JsonTools.mapReplacer));
    },
    getSessionConfig: function() {
        let sessionConfig = localStorage.getItem(this.sessionMaskKey);
        if (!sessionConfig) {
            this.hasSessionConfig = false;
            return false;
        }
        sessionConfig = JSON.parse(sessionConfig, MODx.util.JsonTools.mapReviver);
        if (MODx.util.isEmptyObject(this.cache)) {
            this.cache = sessionConfig;
        }
        this.hasSessionConfig = true;
        return sessionConfig;
    },
    destroySessionConfig: function() {
        localStorage.removeItem(this.sessionMaskKey);
    },
    clearSessionConfig: function() {
        localStorage.removeItem(this.sessionMaskKey);
        this.hasSessionConfig = false;
    },
    updateSessionConfig: function(type, config, updateFallback = false, overridesData = null) {
        let sessionConfig = this.getSessionConfig();
        if (!sessionConfig) {
            sessionConfig = this.config;
        }
        Object.keys(config).forEach(key => {
            // console.log(`Updating ${type} key (${key} to ${config[key]})`);
            // sessionConfig.attributes[type][key] = config[key];
            sessionConfig.current[type][key] = config[key];
            if (updateFallback) {
                sessionConfig.fallback[type][key] = config[key];
            }
        });
        if (overridesData) {
            const
                keyList = sessionConfig.overrides.groups.get(overridesData.groupId),
                keyListHasKey = keyList.includes(overridesData.key)
            ;
            // console.log(`Override keyList for group ${overridesData.groupId}`, keyList);
            if (overridesData.action === 'remove' && keyListHasKey) {
                keyList = keyList.filter(key => key !== overridesData.key);
            } else if (['create', 'update'].includes(overridesData.action) && !keyListHasKey) {
                keyList.push(overridesData.key);
            }
            sessionConfig.overrides.groups.get(overridesData.groupId, keyList);
        }
        // this.cache = sessionConfig;
        // localStorage.setItem(this.sessionMaskKey, JSON.stringify(sessionConfig));
        // this.hasSessionConfig = true;
        this.saveSessionConfig(sessionConfig);
    },
    /**
     * Get the mask type and attribute prop names based on a settings key
     * @param {String} queryKey The settings key being processed
     * @returns {Object} 
     */
    getMaskPropNamesFromKey: function(queryKey) {
        const props = {
            type: null,
            attribute: null
        };
        for (const maskType in this.settingsKeys) {
            const result = Object.keys(this.settingsKeys[maskType]).find(key => this.settingsKeys[maskType][key] === queryKey);
            if (result) {
                props.type = maskType;
                props.attribute = result;
                break;
            }
        }
        return props;
    },
    /**
     * Prepare global/user setting values for comparison to form values
     * and/or for updating the session configuration
     * @param {String} xtype The Ext xtype for the setting's editor
     * @param {Boolean} initialValue Current setting value retrieved from config or database
     */
    prepareSettingValue: function(xtype, initialValue = null) {
        let value = initialValue;
        if (xtype.includes('number')) {
            value = parseFloat(value);
        } else if (xtype.includes('boolean')) {
            value = MODx.util.Types.castToBoolean(value);
        }
        return value;
    },
    updateSystemSettings: function(windowType, settingsTarget, values, userId) {
        const
            params = {
                namespace: 'core',
                area: 'manager'
            },
            exitDelay = 150,
            /**
             * 
             */
            SetActionMap = target => {
                const
                    currentSettings = {
                        user: {},
                        global: {}
                    },
                    buildMap = (target, settings) => {
                        this.settingsMap.keys.forEach(key => {
                            const
                                userSettingExists = Object.hasOwn(settings.user, key),
                                globalSettingExists = Object.hasOwn(settings.global, key),
                                userSettingSaveAction = userSettingExists ? 'update' : 'create',
                                globalSettingSaveAction = globalSettingExists ? 'update' : 'create',
                                payload = {
                                    ...params,
                                    key: key,
                                    value: this.valuesMap[key],
                                    xtype: this.settingsMap.xtypes[key],
                                    status: 0
                                }
                            ;
                            if (target === 'user') {
                                // Remove setting if it matches the global setting
                                if (userSettingExists && settings.global[key] === this.valuesMap[key]) {
                                    this.actionMap.user.delete.push({
                                        key: key,
                                        user: MODx.config.user,
                                        status: 0
                                    });
                                    this.actionMap.totalActions++;
                                // Create or update otherwise
                                } else if (
                                    (!userSettingExists && settings.global[key] !== this.valuesMap[key]) 
                                    || (userSettingExists && settings.user[key] !== this.valuesMap[key])
                                ) {
                                    this.actionMap.user[userSettingSaveAction].push({
                                        ...payload,
                                        user: MODx.config.user,
                                    });
                                    this.actionMap.totalActions++;
                                }
                            }
                            // Remove user settings since, in this case, they would match the global one being updated
                            if (target === 'both' && userSettingExists) {
                                this.actionMap.user.delete.push({
                                    key: key,
                                    user: MODx.config.user,
                                    status: 0
                                });
                                this.actionMap.totalActions++;
                            }
                            // Handle global settings for all targets; note that we elect to re-create the global key/value if it's missing
                            if (!globalSettingExists || (['both', 'global'].includes(target) && settings.global[key] !== this.valuesMap[key])) {
                                this.actionMap.global[globalSettingSaveAction].push(payload);
                                this.actionMap.totalActions++;
                            }
                        });
                        
                    }
                ;
                this.settingsMap.keys.forEach(key => {
                    currentSettings.global[key] = this.prepareSettingValue(this.settingsMap.xtypes[key], MODx.config[key]);
                });

                // Fetch user settings to determine which ones are present and can be acted upon
                MODx.Ajax.request({
                    url: MODx.config.connector_url,
                    params: {
                        ...params,
                        action: 'Security/User/Setting/GetListIn',
                        user: MODx.config.user,
                        keys: JSON.stringify(this.settingsMap.keys) 
                    },
                    listeners: {
                        success: {
                            fn: function(response) {
                                response.results.forEach(result => {
                                    if (this.settingsMap.keys.includes(result.key)) {
                                        currentSettings.user[result.key] = this.prepareSettingValue(this.settingsMap.xtypes[result.key], result.value);
                                    }
                                });
                                buildMap(target, currentSettings);
                                this.fireEvent('actionsReady');
                            },
                            scope: this
                        },
                        failure: {
                            fn: function(response) {
                                this.fireEvent('actionsFail', response);
                            },
                            scope: this
                        }
                    }
                });
            }
        ;
        this.saveStatus = new MODx.window.SaveProgress({ exitDelay })
        // start status window
        this.saveStatus.init();

        this.settingsMap = {
            keys: [],
            xtypes: {}
        };
        this.valuesMap = {};
        this.actionMap = {
            totalActions: 0,
            actionErrors: [],
            user: {
                create: [],
                update: [],
                delete: []
            },
            global: {
                create: [],
                update: []
            }
        };
        Object.entries(values).forEach(([key, value]) => {
            const settingKey = this.settingsKeys[windowType][key];
            this.settingsMap.keys.push(settingKey);
            this.settingsMap.xtypes[settingKey] = this.settingsXtypes[key];
            if (settingKey.includes('_opacity')) {
                value = value <= 1 ? parseInt(value * 100) : value ;
            }
            this.valuesMap[settingKey] = value;
        });
        SetActionMap(settingsTarget);
    },
    commitSettingsChanges: function(target) {
        console.log('commitSettingsChanges :: actionMap', this.actionMap);
        const
            userActionBase = 'Security/User/Setting/',
            globalActionBase = 'System/Settings/',
            processorsMap = {
                create: 'Create',
                update: 'Update',
                delete: 'Remove'
            },
            onSuccess = response => {
                taskSuccesses++;
                taskIndex++;
                console.log(`
                    - - onSuccess - -
                    Incrementing success count to: ${taskSuccesses}
                    Completed ${taskIndex} of ${this.actionMap.totalActions} actions

                `, response);
                if (taskIndex === this.actionMap.totalActions) {
                    this.fireEvent('actionsDone');
                }
            },
            onFailure = response => {
                taskFailures++;
                taskIndex++;
                console.log(`
                    - - onFailure - -
                    Dang it, something went wrong!!!
                `, response);
                // this.fireEvent('actionsFail', response);
            },
            baseRequest = {
                url: MODx.config.connector_url,
                listeners: {
                    success: { fn: onSuccess },
                    failure: { fn: onFailure }
                }
            }
        ;
        let
            taskIndex = 0,
            taskSuccesses = 0,
            taskFailures = 0
        ;

        for (const action in this.actionMap.user) {
            // console.log('User action processing: ', action);
            const
                tasks = this.actionMap.user[action],
                actionParam = userActionBase + processorsMap[action]
            ;
            if (tasks.length > 0) {
                tasks.forEach(params => {
                    const request = {
                        ...baseRequest,
                        params: { ...params, action: actionParam }
                    };
                    // console.log('Full request obj:', request);
                    MODx.Ajax.request(request);
                });
            }
        }
        for (const action in this.actionMap.global) {
            // console.log('Global action processing: ', action);
            const
                tasks = this.actionMap.global[action],
                actionParam = globalActionBase + processorsMap[action]
            ;
            if (tasks.length > 0) {
                tasks.forEach(params => {
                    MODx.Ajax.request({
                        ...baseRequest,
                        params: { ...params, action: actionParam }
                    });
                });
            }
        }

    }
});

MODx.form.Handler = function(config) {
    config = config || {};
    MODx.form.Handler.superclass.constructor.call(this,config);
};
Ext.extend(MODx.form.Handler,Ext.Component,{
    fields: []

    ,handle: function(o,s,r) {
        r = Ext.decode(r.responseText);
        if (!r.success) {
            this.showError(r.message);
            return false;
        }
        return true;
    }

    ,highlightField: function(f) {
        if (f.id !== undefined && f.id !== 'forEach' && f.id !== '') {
            var fld = Ext.get(f.id);
            if (fld && fld.dom) {
                fld.dom.style.border = '1px solid red';
            }
            var ef = Ext.get(f.id+'_error');
            if (ef) { ef.innerHTML = f.msg; }
            this.fields.push(f.id);
        }
    }

    ,unhighlightFields: function() {
        for (var i=0;i<this.fields.length;i=i+1) {
            Ext.get(this.fields[i]).dom.style.border = '';
            var ef = Ext.get(this.fields[i]+'_error');
            if (ef) { ef.innerHTML = ''; }
        }
        this.fields = [];
    }

    ,errorJSON: function(e) {
        if (e === '') { return this.showError(e); }
        if (e.data && e.data !== null) {
            for (var p=0;p<e.data.length;p=p+1) {
                this.highlightField(e.data[p]);
            }
        }

        this.showError(e.message);
        return false;
    }

    ,errorExt: function(r,frm) {
        this.unhighlightFields();
        if (r && r.errors !== null && frm) {
            frm.markInvalid(r.errors);
        }
        if (r && r.message !== undefined && r.message !== '') {
            this.showError(r.message);
        } else {
            MODx.msg.hide();
        }
        return false;
    }

    ,showError: function(e) {
        if (e === '') {
            MODx.msg.hide();
        } else {
            MODx.msg.alert(_('error'),e,Ext.emptyFn);
        }
    }

    ,closeError: function() { MODx.msg.hide(); }
});
Ext.reg('modx-form-handler',MODx.form.Handler);

MODx.Msg = function(config) {
    config = config || {};
    MODx.Msg.superclass.constructor.call(this,config);
    this.addEvents({
        'success': true
        ,'failure': true
        ,'cancel': true
    });
    Ext.MessageBox.minWidth = 200;
};
Ext.extend(MODx.Msg,Ext.Component,{
    confirm: function(config) {
        this.purgeListeners();
        if (config.listeners) {
            for (var i in config.listeners) {
              var l = config.listeners[i];
              this.addListener(i,l.fn,l.scope || this,l.options || {});
            }
        }
        Ext.MessageBox.minWidth = config.minWidth || 200;
        Ext.Msg.confirm(config.title || _('warning'),config.text,function(e) {
            if (e == 'yes') {
                MODx.Ajax.request({
                    url: config.url
                    ,params: config.params || {}
                    ,method: 'post'
                    ,scope: this
                    ,listeners: {
                        'success':{fn:function(r) {
                            this.fireEvent('success',r);
                        },scope:this}
                        ,'failure':{fn:function(r) {
                            return this.fireEvent('failure',r);
                        },scope:this}
                    }
                });
            } else {
                this.fireEvent('cancel',config);
            }
        },this);
    }

    ,getWindow: function() {
        return Ext.Msg.getDialog();
    }

    ,alert: function(title,text,fn,scope) {
        fn = fn || Ext.emptyFn;
        scope = scope || this;
        Ext.Msg.alert(title,text,fn,scope);
    }

    ,status: function(opt) {
        if (!MODx.stMsgCt) {
            MODx.stMsgCt = Ext.DomHelper.insertFirst(document.body, {id:'modx-status-message-ct'}, true);
        }
        MODx.stMsgCt.alignTo(document, 't-t');
        var markup = this.getStatusMarkup(opt);
        var m = Ext.DomHelper.overwrite(MODx.stMsgCt, {html:markup}, true);

        var fadeOpts = {remove:true,useDisplay:true};
        if (!opt.dontHide) {
            if(!Ext.isIE8) {
                m.pause(opt.delay || 1.5).ghost("t",fadeOpts);
            } else {
                fadeOpts.duration = (opt.delay || 1.5);
                m.ghost("t",fadeOpts);
            }
        } else {
            m.on('click',function() {
                m.ghost('t',fadeOpts);
            });
        }

    }
    ,getStatusMarkup: function(opt) {
        var mk = '<div class="modx-status-msg">';
        if (opt.title) { mk += '<h3>'+opt.title+'</h3>'; }
        if (opt.message) { mk += '<span class="modx-smsg-message">'+opt.message+'</span>'; }
        return mk+'</div>';
    }
});
Ext.reg('modx-msg',MODx.Msg);

/**
 * Server-side state provider for MODx
 *
 * @class MODx.state.HttpProvider
 * @extends Ext.state.Provider
 * @constructor
 * @param {Object} config Configuration object.
 */
MODx.HttpProvider = function(config) {
    config = config || {};
    this.addEvents(
        'readsuccess'
        ,'readfailure'
        ,'writesuccess'
        ,'writefailure'
    );
    MODx.HttpProvider.superclass.constructor.call(this,config);
    Ext.apply(this, config, {
        delay: 500
        ,dirty: false
        ,started: false
        ,autoStart: true
        ,autoRead: true
        ,queue: {}
        ,readUrl: MODx.config.connector_url
        ,writeUrl: MODx.config.connector_url
        ,method: 'post'
        ,baseParams: {
            register: 'state'
            ,topic: ''
        }
        ,writeBaseParams: {
            action: 'System/Registry/Register/Send'
            ,message: ''
            ,message_key: ''
            ,message_format: 'json'
            ,delay: 0
            ,ttl: 0
            ,kill: 0
        }
        ,readBaseParams: {
            action: 'System/Registry/Register/Read'
            ,format: 'json'
            ,poll_limit: 1
            ,poll_interval: 1
            ,time_limit: 10
            ,message_limit: 1000
            ,remove_read: 0
            ,show_filename: 0
            ,include_keys: 1
        }
        ,paramNames: {
            topic: 'topic'
            ,name: 'name'
            ,value: 'value'
            ,message: 'message'
            ,message_key: 'message_key'
        }
    });
    this.config = config;
    if (this.autoRead) {
        this.readState();
    }
    this.dt = new Ext.util.DelayedTask(this.submitState, this);
    if (this.autoStart) {
        this.start();
    }
};
Ext.extend(MODx.HttpProvider, Ext.state.Provider, {
    initState: function(state) {
        if (state instanceof Object) {
            Ext.iterate(state, function(name, value, o) {
                this.state[name] = value;
            }, this)
        } else {
            this.state = {};
        }
    }
    ,set: function(name, value) {
        if (!name) {
            return;
        }
        this.queueChange(name, value);
    }
    ,get : function(name, defaultValue){
        return typeof this.state[name] == "undefined" ?
            defaultValue : this.state[name];
    }
    ,start: function() {
        this.dt.delay(this.delay);
        this.started = true;
    }
    ,stop: function() {
        this.dt.cancel();
        this.started = false;
    }
    ,queueChange:function(name, value) {
        var lastValue = this.state[name];
        var found = this.queue[name] !== undefined;
        if (found) {
            lastValue = this.queue[name];
        }
        var changed = undefined === lastValue || lastValue !== value;
        if (changed) {
            this.queue[name] = value;
            this.dirty = true;
        }
        if (this.started) {
            this.start();
        }
        return changed;
    }
    ,submitState: function() {
        if (!this.dirty) {
            this.dt.delay(this.delay);
            return;
        }
        this.dt.cancel();

        var o = {
             url: this.writeUrl
            ,method: this.method
            ,scope: this
            ,success: this.onWriteSuccess
            ,failure: this.onWriteFailure
            ,queue: Ext.apply({}, this.queue)
            ,params: {}
        };
        var params = Ext.apply({}, this.baseParams, this.writeBaseParams);
        params[this.paramNames.topic] = '/ys/user-' + MODx.user.id + '/';
        params[this.paramNames.message] = Ext.encode(this.queue);

        Ext.apply(o.params, params);
        // be optimistic
        this.dirty = false;

        Ext.Ajax.request(o);
    }
    ,clear: function(name) {
        this.set(name, undefined);
    }
    ,onWriteSuccess: function(r,o) {
        r = Ext.decode(r.responseText);
        if (true !== r.success) {
            this.dirty = true;
        } else {
            Ext.iterate(o.queue, function(name, value) {
                if(!name) {
                    return;
                }
                if (undefined === value || null === value) {
                    MODx.HttpProvider.superclass.clear.call(this, name);
                } else {
                    // parent sets value and fires event
                    MODx.HttpProvider.superclass.set.call(this, name, value);
                }
            }, this);
            if (false === this.dirty) {
                this.queue = {};
            } else {
                Ext.iterate(o.queue, function(name, value) {
                    var found = this.queue[name] !== undefined;
                    if (true === found && value === this.queue[name]) {
                        delete this.queue[name];
                    }
                }, this);
            }
            this.fireEvent('writesuccess', this);
        }
    }
    ,onWriteFailure: function(r) {
        r = Ext.decode(r.responseText);
        this.dirty = true;
        this.fireEvent('writefailure', this);
    }
    ,onReadFailure: function(r) {
        r = Ext.decode(r.responseText);
        this.fireEvent('readfailure', this);
    }
    ,onReadSuccess: function(r) {
        r = Ext.decode(r.responseText);
        var state;
        if (true === r.success && r.message) {
            state = Ext.decode(r.message);
            if (!(state instanceof Object)) {
                return;
            }
            Ext.iterate(state, function(name, value, o) {
                this.state[name] = value;
            }, this);
            this.queue = {};
            this.dirty = false;
            this.fireEvent('readsuccess', this);
        }
    }
    ,readState: function() {
        var o = {
             url: this.readUrl
            ,method: this.method
            ,scope: this
            ,success: this.onReadSuccess
            ,failure: this.onReadFailure
            ,params: {}
        };

        var params = Ext.apply({}, this.baseParams, this.readBaseParams);
        params[this.paramNames.topic] = '/ys/user-' + MODx.user.id + '/';

        Ext.apply(o.params, params);
        Ext.Ajax.request(o);
    }
});

MODx.Header = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        cls: 'modx-page-header'
        ,autoEl: {
            tag: 'h2'
        }
        ,itemId: 'header'
    });
    MODx.Header.superclass.constructor.call(this, config);
};
Ext.extend(MODx.Header, Ext.BoxComponent, {});
Ext.reg('modx-header', MODx.Header);

MODx.Description = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        cls: 'panel-desc'
        ,itemId: 'description'
    });
    MODx.Description.superclass.constructor.call(this, config);
};
Ext.extend(MODx.Description, Ext.BoxComponent, {});
Ext.reg('modx-description', MODx.Description);
