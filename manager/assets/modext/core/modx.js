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
    this.initQuickTips();
    this.request = this.getURLParameters();
    this.Ajax = this.load({ xtype: 'modx-ajax' });
    Ext.override(Ext.form.Field,{
        defaultAutoCreate: {tag: "input", type: "text", size: "20", autocomplete: "on" }
    });
};
Ext.extend(MODx,Ext.Component,{
    config: {}
    ,util:{},window:{},panel:{},tree:{},form:{},grid:{},combo:{},toolbar:{},page:{},msg:{}
    ,Ajax:{}
    
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
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'system/index.php'
            ,params: { action: 'clearCache' }
            ,listeners: {
                'success':{fn:function(r) {
                    MODx.msg.alert(_('success'),r.message,function() {
                        Ext.getCmp('modx-layout').refreshTrees();
                    },this);
                },scope:this}
            }
        });
    }
    
    ,logout: function() {
        MODx.msg.confirm({
            title: _('logout')
            ,text: _('logout_confirm')
            ,url: MODx.config.connectors_url+'security/logout.php'
            ,params: {
                action: 'logout'
                ,login_context: 'mgr'
            }
            ,listeners: {
                'success': {fn:function() { location.href = './'; },scope:this}
            }
        });
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
                this.addListener(i,l.fn,l.scope || this,l.options || {});
              }
            }
        }
        
        Ext.applyIf(config,{
            success: function(r,o) {
                r = Ext.decode(r.responseText);
                r.options = o;
                if (r.success) {
                    this.fireEvent('success',r);
                } else if (this.fireEvent('failure',r)) {
                    MODx.form.Handler.errorJSON(r);
                }
            }
            ,failure: function(r,o) {
            	r = Ext.decode(r.responseText);
            	r.options = o;
            	if (this.fireEvent('failure',r)) {
            		MODx.form.Handler.errorJSON(r);
            	}
            }
            ,scope: this
        });
        Ext.Ajax.request(config);
    }
});
Ext.reg('modx-ajax',MODx.Ajax);


MODx = new MODx();