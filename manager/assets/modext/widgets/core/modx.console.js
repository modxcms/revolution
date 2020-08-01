MODx.Console = function(config) {
    config = config || {};
    Ext.Updater.defaults.showLoadIndicator = false;
    Ext.applyIf(config,{
        title: _('console')
        ,modal: Ext.isIE ? false : true
        ,closeAction: 'hide'
        ,resizable: false
        ,collapsible: false
        ,closable: true
        ,maximizable: true
        ,autoScroll: true
        ,height: 400
        ,width: 600
        ,refreshRate: 2
        ,cls: 'modx-window modx-console'
        ,items: [{
            itemId: 'header'
            ,cls: 'modx-console-text'
            ,html: _('console_running')
            ,border: false
        },{
            xtype: 'panel'
            ,itemId: 'body'
            ,cls: 'x-panel-bwrap modx-console-text'
        }]
        ,buttons: [{
            text: _('console_download_output')
            ,handler: this.download
            ,scope: this
        },{
            text: _('ok')
            ,cls: 'primary-button'
            ,itemId: 'okBtn'
            ,disabled: true
            ,scope: this
            ,handler: this.hideConsole
        }]
        ,keys: [{
            key: Ext.EventObject.S
            ,ctrl: true
            ,fn: this.download
            ,scope: this
        },{
            key: Ext.EventObject.ENTER
            ,fn: this.hideConsole
            ,scope: this
        }]
    });
    MODx.Console.superclass.constructor.call(this,config);
    this.config = config;
    this.addEvents({
        'shutdown': true
        ,'complete': true
    });
    this.on('show',this.init,this);
    this.on('hide',function() {
        if (this.provider && this.provider.disconnect) {
            try {
                this.provider.disconnect();
            } catch (e) {}
        }
        this.fireEvent('shutdown');
        this.destroy();
    });
    this.on('complete',this.onComplete,this);
};
Ext.extend(MODx.Console,Ext.Window,{
    mgr: null
    ,running: false

    ,init: function() {
        Ext.Msg.hide();
        this.fbar.setDisabled(true);
        this.keyMap.setDisabled(true);
        this.getComponent('body').el.dom.innerHTML = '';
        this.provider = new Ext.direct.PollingProvider({
            type:'polling'
            ,url: MODx.config.connector_url
            ,interval: 1000
            ,baseParams: {
                action: 'System/Console'
                ,register: this.config.register || ''
                ,topic: this.config.topic || ''
                ,clear: false
                ,show_filename: this.config.show_filename || 0
                ,format: this.config.format || 'html_log'
            }
        });
        Ext.Direct.addProvider(this.provider);
        Ext.Direct.on('message', this.onMessage, this);
    }

    ,onMessage: function(e,p) {
        var out = this.getComponent('body');
        if (out) {
            out.el.insertHtml('beforeEnd',e.data);
            e.data = '';
            out.el.scroll('b', out.el.getHeight(), true);
        }
        if (e.complete) {
            this.fireEvent('complete');
        }
        delete e;
    }

    ,onComplete: function() {
        if (this.provider && this.provider.disconnect) {
            try {
                this.provider.disconnect();
            } catch (e) {}
        }
        this.fbar.setDisabled(false);
        this.keyMap.setDisabled(false);
    }

    ,download: function() {
        var c = this.getComponent('body').getEl().dom.innerHTML || '&nbsp;';
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'System/DownloadOutput'
                ,data: c
            }
            ,listeners: {
                'success':{fn:function(r) {
                    location.href = MODx.config.connector_url+'?action=System/DownloadOutput&HTTP_MODAUTH='+MODx.siteId+'&download='+r.message;
                },scope:this}
            }
        });
    }

    ,setRegister: function(register,topic) {
    	this.config.register = register;
        this.config.topic = topic;
    }

    ,hideConsole: function() {
        this.hide();
    }
});
Ext.reg('modx-console',MODx.Console);
