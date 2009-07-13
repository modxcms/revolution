Ext.namespace('MODx');
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
    //,Ajax:{}
    
    ,startup: function() {
        this.initQuickTips();
        this.request = this.getURLParameters();
        this.Ajax = this.load({ xtype: 'modx-ajax' });
        Ext.override(Ext.form.Field,{
            defaultAutoCreate: {tag: "input", type: "text", size: "20", autocomplete: "on" }
        });
        this.addEvents({
            beforeClearCache: true
            ,beforeLogout: true
            ,beforeReleaseLocks: true
            ,afterClearCache: true
            ,afterLogout: true
            ,afterReleaseLocks: true
        });
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
        if (!this.fireEvent('beforeClearCache')) return false;
        
        var topic = '/clearcache/';
        if (this.console == null || this.console == undefined) {
            this.console = MODx.load({
               xtype: 'modx-console'
               ,register: 'mgr'
               ,topic: topic
               ,listeners: {
                    'shutdown': {fn:function() {
                        if (this.fireEvent('afterClearCache')) {
                            Ext.getCmp('modx-layout').refreshTrees();
                        }
                    }}
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
            Ext.applyIf(c,{xtype: 'modx-tabs',style: 'margin-top: .5em;',items: v});
        } else {
            Ext.applyIf(c,{xtype:'portal',items:[{columnWidth:1,items: v}]});
        }
        return c;
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
                if (!r) return false;
                r.options = o;
                if (r.success) {
                    this.fireEvent('success',r);
                } else if (this.fireEvent('failure',r)) {
                    MODx.form.Handler.errorJSON(r);
                }
            }
            ,failure: function(r,o) {
            	r = Ext.decode(r.responseText);
                if (!r) return false;
            	r.options = o;
            	if (this.fireEvent('failure',r)) {
            		MODx.form.Handler.errorJSON(r);
            	}
            }
            ,scope: this
            ,headers: { 'modx': 'modx' }
        });
        Ext.Ajax.request(config);
    }
});
Ext.reg('modx-ajax',MODx.Ajax);


MODx = new MODx();