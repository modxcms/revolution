Ext.namespace('MODx.panel');
/**
 * An abstract class for Ext Panels in MODx. 
 * 
 * @class MODx.Panel
 * @extends Ext.Panel
 * @constructor
 * @param {Object} config An object of config options.
 * @xtype modx-panel
 */
MODx.Panel = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        cls: 'modx-panel'
        ,title: ''
    });
    MODx.Panel.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.Panel,Ext.Panel);
Ext.reg('modx-panel',MODx.Panel);

/**
 * An abstract class for Ext FormPanels in MODx. 
 * 
 * @class MODx.FormPanel
 * @extends Ext.FormPanel
 * @constructor
 * @param {Object} config An object of config options.
 * @xtype modx-formpanel
 */
MODx.FormPanel = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        autoHeight: true
        ,collapsible: true
        ,bodyStyle: 'padding: 1em'
        ,border: false
        ,header: false
        ,title: ''
        ,method: 'POST'
        ,cls: 'modx-form'
        ,ddGroup: 'modx-treedrop-dd'
        ,allowDrop: true
        ,errorReader: MODx.util.JSONReader
        ,checkDirty: true
    });
    if (config.items) { this.addChangeEvent(config.items); }
    
    MODx.FormPanel.superclass.constructor.call(this,config);    
    this.config = config;
    
    this.addEvents({
        setup: true
        ,fieldChange: true
        ,ready: true
        ,beforeSubmit: true
        ,success: true
        ,failure: true
        ,save: true
    });
    this.getForm().addEvents({
        success: true
        ,failure: true
    });
    this.on('ready',this.onReady);
    this.fireEvent('setup',config);
};
Ext.extend(MODx.FormPanel,Ext.FormPanel,{
	isReady: false
	/**
     * Submits the form to the connector.
     */
    ,submit: function(o) {
        var fm = this.getForm();
        if (fm.isValid()) {
        	if (this.fireEvent('beforeSubmit',{
        	   form: fm
        	   ,options: o
        	   ,config: this.config
        	})) {
                fm.submit({
                    waitMsg: this.config.saveMsg || _('saving')
                    ,scope: this
                    ,failure: function(f,a) {
                    	if (this.fireEvent('failure',{
                    	   form: f
                    	   ,result: a.result
                    	   ,options: o
                    	   ,config: this.config
                    	})) {
                            MODx.form.Handler.errorExt(a.result,f);
                    	}
                    }
                    ,success: function(f,a) {
                        if (this.config.success) {
                            Ext.callback(this.config.success,this.config.scope || this,[f,a]);
                        }
                        this.fireEvent('success',{
                            form:f
                            ,result:a.result
                            ,options:o
                            ,config:this.config
                        });
                        this.clearDirty();
                        this.fireEvent('setup',this.config);
                    }
                });
            }
        } else {
            return false;
        }
        return true;
    }
    
    ,addChangeEvent: function(items) {
    	if (!items) { return false; }
    	if (typeof(items) == 'object' && items.items) {
    		items = items.items;
    	}
    	
        for (var f=0;f<items.length;f++) {
            var cmp = items[f];
            if (cmp.items) {
                this.addChangeEvent(cmp.items);
            } else if (cmp.xtype) {
                if (!cmp.listeners) { cmp.listeners = {}; }
                var ctype = 'change';
                cmp.enableKeyEvents = true;
                switch (cmp.xtype) {
                	case 'textfield':
                	case 'textarea':
                	   ctype = 'keydown';
                	   break;
                	case 'checkbox':
                	case 'radio':
                	   ctype = 'check';
                	   break;
                }
                cmp.listeners[ctype] = {fn:this.fieldChangeEvent,scope:this};
            }
        }
    }
    
    ,fieldChangeEvent: function(fld,nv,ov,f) {
        if (!this.isReady) { return false; }
        var f = this.config.onDirtyForm ? Ext.getCmp(this.config.onDirtyForm) : this.getForm();
        this.fireEvent('fieldChange',{
            field: fld
            ,nv: nv
            ,ov: ov
            ,form: f
        });
    }
    
    ,isDirty: function() {
        var f = this.config.onDirtyForm ? Ext.getCmp(this.config.onDirtyForm) : this.getForm();
    	return f.isDirty();
    }
    
    ,clearDirty: function() {
        var f = this.config.onDirtyForm ? Ext.getCmp(this.config.onDirtyForm) : this.getForm();
    	return f.clearDirty();
    }
    
    ,onReady: function(r) {
    	this.isReady = true;
        if (this.config.allowDrop) { this.loadDropZones(); }
    }
    
    ,loadDropZones: function() {        
        var flds = this.getForm().items;
        flds.each(function(fld) {
            if (fld.isFormField && (
                fld.isXType('textfield') || fld.isXType('textarea')
            ) && !fld.isXType('combo')) {
                var el = fld.getEl();
                if (el) {
                    new MODx.load({
                        xtype: 'modx-treedrop'
                        ,target: fld
                        ,targetEl: el.dom
                    });
                }
            }
        });
    }
    
    ,getField: function(f) {
        var fld = false;
        if (typeof f == 'string') {
            fld = this.getForm().findField(f);
            if (!fld) fld = Ext.getCmp(f);
        }
        return fld;
    }
    
    ,hideField: function(f) {
        f = this.getField(f);
        if (!f) return;
        f.disable();
        f.hide();
        var d = f.getEl().up('.x-form-item');
        if (d) { d.setDisplayed(false); }
    }

    ,showField: function(f) {
        f = this.getField(f);
        if (!f) return;
        f.enable();
        f.show();
        var d = f.getEl().up('.x-form-item');
        if (d) { d.setDisplayed(true); }
    }
    
    ,setLabel: function(fld,text){
        var f = this.getField(fld);
        if (!f) return;
        var r = f.getEl().up('div.x-form-item');
        r.dom.firstChild.firstChild.nodeValue = String.format('{0}', text);
    }
}); 
Ext.reg('modx-formpanel',MODx.FormPanel);

/**
 * Adds clearDirty functionality to Ext.form.BasicForm
 */
Ext.override(Ext.form.BasicForm,{
    clearDirty : function(nodeToRecurse){
        nodeToRecurse = nodeToRecurse || this;
        nodeToRecurse.items.each(function(f){
            if(f.items){
                this.clearDirty(f);
            } else if(f.originalValue != f.getValue()){
                f.originalValue = f.getValue();
            }
        },this);
    }
});



/**
 * An abstract class for Wizard panels in MODx
 * 
 * @class MODx.panel.Wizard
 * @extends Ext.Panel
 * @constructor
 * @param {Object} config An object of config options.
 * @xtype modx-wizard
 */
MODx.panel.Wizard = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        layout: 'card'
        ,activeItem: 0
        ,resizable: true
        ,collapsible: true
        ,maximizable: true
        ,autoHeight: true
        ,width: 750
        ,firstPanel: ''
        ,lastPanel: ''
        ,defaults: { border: false }
        ,modal: true
        ,txtFinish: _('finish')
        ,txtNext: _('next')
        ,txtBack: _('back')
        ,bbar: [{
            id: 'pi-btn-bck'
            ,text: config.txtBack || _('back')
            ,handler: this.navHandler.createDelegate(this,[-1])
            ,scope: this
            ,disabled: true         
        },{
            id: 'pi-btn-fwd'
            ,text: config.txtNext || _('next')
            ,handler: this.navHandler.createDelegate(this,[1])
            ,scope: this
        }]
    });
    MODx.panel.Wizard.superclass.constructor.call(this,config);
    this.config = config;
    this.lastActiveItem = this.config.firstPanel;
    this._go();
};
Ext.extend(MODx.panel.Wizard,Ext.Panel,{
    /**
     * @var {Object} windows The object collection of windows
     * @access private
     */
    windows: {}
    
    /**
     * Launches the wizard.
     * 
     * @access private
     */
    ,_go: function() {
        this.getBottomToolbar().items.item(1).setText(this.config.txtNext);
        this.proceed(this.config.firstPanel);
    }
    
    /**
     * Handles navigation between panels
     * 
     * @access public
     * @param {Integer} dir Either 1 for forward, or -1 for backward
     */
    ,navHandler: function(dir) {
        this.doLayout();
        var a = this.getLayout().activeItem;
        if (dir == -1) {
            this.proceed(a.config.back || a.config.id);
        } else {
            a.submit({
                scope: this
                ,proceed: this.proceed
            });
        }
    }
    
    /**
     * Proceeds to the next frame
     * 
     * @access public
     * @param {String} id The id of the panel to proceed to
     */
    ,proceed: function(id) {
        this.doLayout();
        this.getLayout().setActiveItem(id);
        if (id == this.config.firstPanel) {
            this.getBottomToolbar().items.item(0).setDisabled(true);
            this.getBottomToolbar().items.item(1).setText(this.config.txtNext);
        } else if (id == this.config.lastPanel) {
            this.getBottomToolbar().items.item(1).setText(this.config.txtFinish);
        } else {
            this.getBottomToolbar().items.item(0).setDisabled(false);
            this.getBottomToolbar().items.item(1).setText(this.config.txtNext);
        }
    }
});
Ext.reg('modx-panel-wizard',MODx.panel.Wizard);

MODx.panel.WizardPanel = function(config) {
	config = config || {};
	Ext.applyIf(config,{
        wizard: null
        ,checkDirty: false
        ,bodyStyle: 'padding: 3em 3em'
        ,hideMode: 'offsets'
	});
	MODx.panel.WizardPanel.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.WizardPanel,MODx.FormPanel);
Ext.reg('modx-wizard-panel',MODx.panel.WizardPanel);


MODx.PanelSpacer = {
    html: '<br />'
    ,border: false
};