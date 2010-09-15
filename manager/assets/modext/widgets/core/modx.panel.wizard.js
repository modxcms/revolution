MODx.Wizard = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        layout: 'card'
        ,layoutConfig: {
            layoutOnCardChange: true
            ,deferredRender: false
        }
        ,activeItem: 0
        ,closeAction: 'hide'
        ,resizable: true
        ,collapsible: true
        ,maximizable: true
        ,autoHeight: true
        ,anchor: '90%'
        ,width: '90%'
        ,defaults: { border: false }
        ,modal: Ext.isIE ? false : true
        ,hideMode: 'offsets'
        ,cls: 'modx-window'
        ,bbar: [{
            itemId: 'btn-back'
            ,text: _('back')
            ,handler: function() { this.fireEvent('backward'); }
            ,scope: this
            ,disabled: true         
        },{
            itemId: 'btn-next'
            ,text: _('next')
            ,handler: function() { this.fireEvent('forward'); }
            ,scope: this
        }]
        ,firstPanel: ''
        ,lastPanel: ''
        ,showFirstPanel: true
    });
    MODx.Wizard.superclass.constructor.call(this,config);
    this.lastActiveItem = config.firstPanel;
    this.config = config;
    this.addEvents({
        'forward': true
        ,'backward': true
        ,'proceed': true
        ,'finish': true
        ,'ready': true
    });
    
    this.on('show',this.onShow,this);
    this.on('forward',this.onForward,this);
    this.on('backward',this.onBackward,this);
    this.on('proceed',this.proceed,this);
};
Ext.extend(MODx.Wizard,Ext.Window,{
    windows: {}
    
    ,onForward: function() {
        this.navHandler(1);
    }
    ,onBackward: function() {
        this.navHandler(-1);
    }
    
    ,onShow: function() {
        if (this.config.showFirstPanel) {
            this.getBottomToolbar().getComponent('btn-next').setText(_('next'));
        }
        if (this.config.showFirstPanel) {
            if (this.fireEvent('proceed',this.config.firstPanel)) {
                this.fireEvent('ready');
            }
        } else {
            this.fireEvent('ready');
        }
    }
    
    ,navHandler: function(dir) {
        this.doLayout();
        var a = this.getLayout().activeItem;
        if (dir === -1) {
            this.fireEvent('proceed',a.config.back || a.config.id);
        } else {
            a.submit();
        }
    }
    
    ,proceed: function(panel) {
        var tb = this.getBottomToolbar();
        if (!tb) return false;
        
        this.getLayout().setActiveItem(panel);
        
        if (panel == this.config.firstPanel) {
            tb.getComponent('btn-back').setDisabled(true);
        } else if (panel == this.config.lastPanel) {
            tb.getComponent('btn-next').setText(_('finish'));
        } else {
            tb.getComponent('btn-back').setDisabled(false);
            tb.getComponent('btn-next').setText(_('next'));
        }
        var s = Ext.getCmp(panel).fireEvent('fetch');
        if (s) {
            if (!Ext.isIE) {
                this.syncSize();
                this.center();
            }
        }
        return true;
    }
});
Ext.reg('modx-wizard',MODx.Wizard);

MODx.panel.WizardPanel = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        autoHeight: true
        ,bodyStyle: 'padding: 20px'
    });
    MODx.panel.WizardPanel.superclass.constructor.call(this,config);
    this.config = config;
    this.addEvents({ 'fetch': true });
    this.on('fetch',this.fetch,this);
};
Ext.extend(MODx.panel.WizardPanel,Ext.FormPanel,{
    fetch: function() { return true; }
    ,submit: function() { return true; }
});
Ext.reg('panel-wizard-panel',MODx.panel.WizardPanel);