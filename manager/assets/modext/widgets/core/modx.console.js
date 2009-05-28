/**
 * Displays a running console showing logs until a success messsage is sent
 * from the processor.
 * 
 * @class MODx.Console
 * @extends Ext.Window
 * @param {Object} config An object of configuration properties
 * @xtype modx-console
 */
MODx.Console = function(config) {
	config = config || {};
	Ext.Updater.defaults.showLoadIndicator = false;
	Ext.applyIf(config,{
        title: _('console')
	    ,url: MODx.config.connectors_url+'system/registry/register.php'
	    ,baseParams: {
	    	action: 'read'
	    	,register: config.register || ''
	    	,topic: config.topic || ''
	    	,format: 'html_log'
	    	,remove_read: 0
	    }
	    ,modal: Ext.isIE ? false : true
        ,shadow: true
        ,resizable: false
        ,collapsible: false
        ,closable: false
        ,maximizable: true
        ,autoScroll: true
        ,height: 400
        ,width: 550
        ,bodyStyle: 'background-color: white; padding: .75em; font-family: Courier'
        ,cls: 'modx-window'
        ,items: [{
            id: 'console-header'
            ,html: _('console_running')
            ,border: false
        },{
            xtype: 'modx-panel'
            ,id: 'console-body'
            ,cls: 'modx-console'            
        }]
        ,buttons: [{
            text: 'Copy to Clipboard'
            ,handler: this.copyToClipboard
            ,scope: this
        },{
            text: _('ok')
            ,id: 'modx-console-ok'
            ,disabled: true
            ,scope: this
            ,handler: this.hideConsole
        }]
        ,listeners: {
        	'show': {fn:this.init ,scope:this}
        }
	});
	MODx.Console.superclass.constructor.call(this,config);
	this.config = config;
};
Ext.extend(MODx.Console,Ext.Window,{
    mgr: null
    ,running: false
    
    ,init: function() {
       Ext.Msg.hide();
       if (MODx.util.LoadingBox) { MODx.util.LoadingBox.disable(); }
       Ext.get('console-body').update('');
       if (this.running !== true) {
           this.mgr = new Ext.Updater('console-body');
       }
       this.mgr.startAutoRefresh('.5',this.config.url,this.config.baseParams || {},this.renderMsg,true);
       this.running = true;
    }
    
    ,copyToClipboard: function() {
    	var c = Ext.get('console-body').dom.innerHTML;
    	c = Ext.util.Format.stripTags(c);
    	MODx.util.Clipboard.copy(c);
    }
    
    ,renderMsg: function(el,s,r,o) {
        r = Ext.decode(r.responseText);
        el.update(r.message);
    }
    
    ,setRegister: function(register,topic) {
    	this.config.baseParams.register = register;
        this.config.baseParams.topic = topic;
    }
    
    ,hideConsole: function() {
        Ext.getCmp('modx-console-ok').setDisabled(true);
        this.hide();
    }
    
    ,complete: function() {
    	Ext.getCmp('modx-console-ok').setDisabled(false);
        this.shutdown();
    }
    
    ,shutdown: function() {
        this.mgr.stopAutoRefresh();
        if (MODx.util.LoadingBox) { MODx.util.LoadingBox.enable(); }
    	MODx.Ajax.request({
    	    url: this.config.url
    	    ,params: {
                action: 'read'
                ,register: this.config.baseParams.register || ''
                ,topic: this.config.baseParams.topic || ''
                ,format: 'html_log'
                ,remove_read: 1
            }
            ,listeners: {
            	'success': {fn:function(r) {                    
                    Ext.getCmp('console-body').getEl().update(r.message);
        	    },scope:this}
            }
    	});
    }
});
Ext.reg('modx-console',MODx.Console);