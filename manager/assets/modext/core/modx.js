Ext.namespace('MODx');
Ext.apply(Ext,{
    isFirebug: (window.console && window.console.firebug)
});
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

    ,startup: function() {
        this.initQuickTips();
        this.request = this.getURLParameters();
        this.Ajax = this.load({ xtype: 'modx-ajax' });
        Ext.override(Ext.form.Field,{
            defaultAutoCreate: {tag: "input", type: "text", size: "20", autocomplete: "on" }
        });
        Ext.menu.Menu.prototype.enableScrolling = false;
        this.addEvents({
            beforeClearCache: true
            ,beforeLogout: true
            ,beforeReleaseLocks: true
            ,afterClearCache: true
            ,afterLogout: true
            ,afterReleaseLocks: true
            ,ready: true
        });
        Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
            expires: new Date(new Date().getTime()+(1000*60*60*24))
        }));
    }
	
    ,add: function(cmp) {
        var ctr = Ext.getCmp('modx-content');
        if (ctr) {
            ctr.removeAll();
            ctr.add({ xtype: cmp });
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

    ,getURLParameters: function() {
        var arg = {};
        var href = document.location.href;

        if (href.indexOf('?') !== -1) {
            var params = href.split('?')[1];
            var param = params.split('&');
            for (var i=0; i<param.length;i=i+1) {
                arg[param[i].split('=')[0]] = param[i].split('=')[1];
            }
        }
        return arg;
    }

    ,loadAccordionPanels: function() { return []; }

    ,clearCache: function() {
        if (!this.fireEvent('beforeClearCache')) { return false; }

        var topic = '/clearcache/';
        if (this.console == null || this.console == undefined) {
            this.console = MODx.load({
               xtype: 'modx-console'
               ,register: 'mgr'
               ,topic: topic
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
        } else {
            this.console.setRegister('mgr',topic);
        }
        this.console.show(Ext.getBody());

        MODx.Ajax.request({
            url: MODx.config.connectors_url+'system/index.php'
            ,params: { action: 'clearCache',register: 'mgr' ,topic: topic }
            ,listeners: {
                'success':{fn:function() {
                    this.console.fireEvent('complete');
                },scope:this}
            }
        });
        return true;
    }

    ,releaseLock: function(id) {
        if (this.fireEvent('beforeReleaseLocks')) {
            MODx.Ajax.request({
                url: MODx.config.connectors_url+'resource/locks.php'
                ,params: {
                    action: 'release'
                    ,id: id
                }
                ,listeners: {
                    'success':{fn:function(r) { this.fireEvent('afterReleaseLocks',r); },scope:this}
                }
            });
        }
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
                ,url: MODx.config.connectors_url+'security/logout.php'
                ,params: {
                    action: 'logout'
                    ,login_context: 'mgr'
                }
                ,listeners: {
                    'success': {fn:function(r) {
                        if (this.fireEvent('afterLogout',r)) {
                            location.href = './';
                        }
                    },scope:this}
                }
            });
        }
    }

    ,getPageStructure: function(v,c) {
        c = c || {};
        if (MODx.config.manager_use_tabs) {
            Ext.applyIf(c,{xtype: 'modx-tabs',itemId: 'tabs' ,items: v});
        } else {
            Ext.applyIf(c,{xtype:'portal',itemId: 'tabs' ,items:[{columnWidth:1,items: v,forceLayout: true}]});
        }
        return c;
    }

    ,loadHelpPane: function(b) {
        var url = MODx.config.help_url;
        if (!url) { return false; }
        MODx.helpWindow = new Ext.Window({
            title: _('help')
            ,width: 850
            ,height: 500
            ,modal: Ext.isIE ? false : true
            ,layout: 'fit'
            ,html: '<iframe src="' + url + '" width="100%" height="100%" frameborder="0"></iframe>'
        });
        MODx.helpWindow.show(b);
        return true;
    }

    ,addTab: function(tbp,opt) {
        var tabs = Ext.getCmp(tbp);
        if (tabs) {
            Ext.applyIf(opt,{
                id: 'modx-'+Ext.id()+'-tab'
                ,layout: 'form'
                ,cls: 'modx-resource-tab'
                ,bodyStyle: 'padding: 15px;'
                ,autoHeight: true
                ,defaults: {
                    border: false
                    ,msgTarget: 'side'
                    ,width: 400
                }
            });
            tabs.add(opt);
            tabs.doLayout();
            tabs.setActiveTab(0);
        }
    }
    ,hiddenTabs: []
    ,hideTab: function(ct,tab) {
        var tp = Ext.getCmp(ct);
        if (tp) {
            tp.hideTabStripItem(tab);
            MODx.hiddenTabs.push(tab);
            var idx = this._getNextActiveTab(tp,tab);
            tp.setActiveTab(idx);
        }
    }
    ,_getNextActiveTab: function(tp,tab) {
        if (MODx.hiddenTabs.indexOf(tab) != -1) {
            var id;
            for (var i=0;i<tp.items.items.length;i++) {
                 id = tp.items.items[i].id;
                if (MODx.hiddenTabs.indexOf(id) == -1) { break; }
            }
        } else { id = tab; }
        return id;
    }

    ,moveTV: function(tvs,tab) {
        if (!Ext.isArray(tvs)) { tvs = [tvs]; }
        var tvp = Ext.getCmp('modx-panel-resource-tv');
        if (!tvp) { return; }

        for (var i=0;i<tvs.length;i++) {
            var tr = Ext.get(tvs[i]+'-tr');

            if (!tr) { return; }
            var fp = Ext.getCmp(tab);
            if (!fp) { return; }
            fp.add({
                html: ''
                ,width: '100%'
                ,id: 'tv-tr-out-'+tvs[i]
                ,cls: 'modx-tv-out'
            });
            fp.doLayout();

            var o = Ext.get('tv-tr-out-'+tvs[i]);
            o.replaceWith(tr);
        }
    }
    ,hideTV: function(tvs) {
        if (!Ext.isArray(tvs)) { tvs = [tvs]; }
        this.hideTVs(tvs);
    }
    ,hideTVs: function(tvs) {
        if (!Ext.isArray(tvs)) { tvs = [tvs]; }
        var el;
        for (var i=0;i<tvs.length;i++) {
            el = Ext.get(tvs[i]+'-tr');
            if (el) {
                el.setVisibilityMode(Ext.Element.DISPLAY);
                el.hide();
            }
        }
    }
    ,renameLabel: function(ct,flds,vals) {
        var cto;
        if (ct == 'modx-panel-resource' && flds.indexOf('modx-resource-content') != -1) {
            cto = Ext.getCmp('modx-resource-content');
            if (cto) {
                cto.setTitle(vals[0]);
            }
        } else {
            cto = Ext.getCmp(ct);
            if (cto) {
                cto.setLabel(flds,vals);
            }
        }
    }
    ,renameTab: function(tb,title) {
        var tab = Ext.getCmp(tb);
        if (tab) {
            tab.setTitle(title);
        }
    }
    ,hideField: function(ct,flds) {
        ct = Ext.getCmp(ct);
        if (ct) {
            ct.hideField(flds);
        }
    }
    ,preview: function() {
        window.open(MODx.config.site_url);
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
    MODx.Ajax.superclass.constructor.call(this,config);
    this.addEvents({
        'success': true
        ,'failure': true
    });
};
Ext.extend(MODx.Ajax,Ext.Component,{
    request: function(config) {
        this.purgeListeners();
        if (config.listeners) {
            for (var i in config.listeners) {
              if (config.listeners.hasOwnProperty(i)) {
                var l = config.listeners[i];
                this.on(i,l.fn,l.scope || this,l.options || {});
              }
            }
        }

        Ext.apply(config,{
            success: function(r,o) {
                r = Ext.decode(r.responseText);
                if (!r) { return false; }
                r.options = o;
                if (r.success) {
                    this.fireEvent('success',r);
                } else if (this.fireEvent('failure',r)) {
                    MODx.form.Handler.errorJSON(r);
                }
                return true;
            }
            ,failure: function(r,o) {
            	r = Ext.decode(r.responseText);
                if (!r) { return false; }
            	r.options = o;
            	if (this.fireEvent('failure',r)) {
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
});
Ext.reg('modx-ajax',MODx.Ajax);


MODx = new MODx();


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
            Ext.get(f.id).dom.style.border = '1px solid red';
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