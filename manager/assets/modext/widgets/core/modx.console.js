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
            ,id: 'modx-console-body'
            ,cls: 'modx-console'            
        }]
        ,buttons: [{
            text: 'Download Output to File'
            ,handler: this.download
            ,scope: this
        },{
            text: _('ok')
            ,id: 'modx-console-ok'
            ,disabled: true
            ,scope: this
            ,handler: this.hideConsole
        }]
	});
	MODx.Console.superclass.constructor.call(this,config);
	this.config = config;
    this.addEvents({
        'shutdown': true
        ,'complete': true
    });
    this.on('show',this.init,this);
    this.on('complete',this.complete,this);
};
Ext.extend(MODx.Console,Ext.Window,{
    mgr: null
    ,running: false
    
    ,init: function() {
        Ext.Msg.hide();
        Ext.get('modx-console-body').update('');
        if (this.running !== true) {
            this.mgr = new Ext.Updater('modx-console-body');
        }
        this.mgr.startAutoRefresh('.5',this.config.url,this.config.baseParams || {},this.renderMsg,true);
        this.running = true;
    }
    
    ,download: function() {
        var c = Ext.get('modx-console-body').dom.innerHTML;
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'system/index.php'
            ,params: {
                action: 'downloadOutput'
                ,data: c
            }
            ,listeners: {
                'success':{fn:function(r) {
                    location.href = MODx.config.connectors_url+'system/index.php?action=downloadOutput&download='+r.message;
                },scope:this}
            }            
        });
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
        this.shutdown();
        this.hide();
    }
    
    ,complete: function() {
    	Ext.getCmp('modx-console-ok').setDisabled(false);
        if (this.mgr) {
            this.mgr.refresh();
            this.mgr.stopAutoRefresh();
        }
    }
    
    ,shutdown: function() {
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
                    Ext.getCmp('modx-console-body').getEl().update(r.message);
                    this.mgr.stopAutoRefresh();
                    this.fireEvent('shutdown',r);
        	    },scope:this}
            }
    	});
    }
});
Ext.reg('modx-console',MODx.Console);