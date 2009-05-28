/**
 * Abstract class for Ext.Window creation in MODx
 * 
 * @class MODx.Window
 * @extends Ext.Window
 * @constructor
 * @param {Object} config An object of options.
 * @xtype modx-window
 */
MODx.Window = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		modal: false
		,layout: 'fit'
		,closeAction: 'hide'
		,shadow: true
		,resizable: true
        ,collapsible: true
        ,maximizable: true
		,autoHeight: true
		,width: 450
        ,cls: 'modx-window'
		,buttons: [{
			text: config.cancelBtnText || _('cancel')
			,scope: this
			,handler: function() { this.hide(); }
		},{
			text: config.saveBtnText || _('save')
			,scope: this
			,handler: this.submit
		}]
		,record: {}
       ,keys: [{
            key: Ext.EventObject.ENTER,
            fn: this.submit
            ,scope: this
        }]

	});
	MODx.Window.superclass.constructor.call(this,config);
	this.options = config;
    this.config = config;
	
    this.addEvents({
        success: true
        ,failure: true
    });
	this._loadForm();
};
Ext.extend(MODx.Window,Ext.Window,{
	/**
	 * Abstract loader for FormPanel creation
	 */
	_loadForm: function() {
		if (this.checkIfLoaded(this.config.record || null)) { return false; }		
		
        var r = this.config.record;
        /* set values here, since setValue after render seems to be broken */
        if (this.config.fields) {
            var l = this.config.fields.length;
            for (var i=0;i<l;i++) {
                var f = this.config.fields[i];
                if (r[f.name]) {
                    if (f.xtype == 'checkbox' || f.xtype == 'radio') {
                        f.checked = r[f.name];
                    } else {
                        f.value = r[f.name];
                    }
                }
            }
        }
        this.fp = this.createForm({
            url: this.config.url
            ,baseParams: this.config.baseParams || { action: this.config.action || '' }
            ,items: this.config.fields || []
        });
		this.renderForm();
	}
	
	/**
	 * Default handler for Window Form submissions. Allows for callbacks.
	 */
	,submit: function() {
		if (this.fp.getForm().isValid()) {
			this.fp.getForm().submit({ 
				waitMsg: _('saving')
				,scope: this
				,failure: function(frm,a) {
                    this.fireEvent('failure',frm,a);
					MODx.form.Handler.errorExt(a.result,frm);
				}
				,success: function(frm,a) {
					if (this.config.success) {
						Ext.callback(this.config.success,this.config.scope || this,[frm,a]);
					}
                    this.fireEvent('success',{f:frm,a:a});
					this.hide();
				}
			});
		}
	}
	
	/**
	 * Creates the FormPanel with preset options
	 * @param {Object} options
	 */
	,createForm: function(config) {
		config = config || {};
		Ext.applyIf(config,{
			labelAlign: 'right'
			,labelWidth: 100
			,frame: true
			,border: false
			,bodyBorder: false
			,autoHeight: true
			,errorReader: MODx.util.JSONReader
			,url: this.config.url
			,baseParams: this.config.baseParams || {}
			,fileUpload: this.config.fileUpload || false
		});
		return new Ext.FormPanel(config);
	}
	
	/**
	 * Renders the FormPanel into the Window
	 */
	,renderForm: function() {
		this.add(this.fp);
	}
	
	/**
	 * Checks to see if the window has already been created or not.
	 * @param {Object} r
	 */
	,checkIfLoaded: function(r) {
		r = r || {};
		if (this.fp && this.fp.getForm()) { /* so as not to duplicate form */
			this.fp.getForm().reset();
			this.fp.getForm().setValues(r);
			return true;
		}
		return false;
	}
    /**
     * Sets the form values for the dialog.
     * @param {Object} r An object of values to set.
     */
    ,setValues: function(r) {
        if (r === null) { return false; }
        this.fp.getForm().setValues(r);
    }
    /**
     * Hides a field in the form.
     * @param {Ext.form.Field} f An Ext Form Field to hide.
     */
    ,hideField: function(f) {
        f.disable();
        f.hide();
        var d = f.getEl().up('.x-form-item');
        if (d) { d.setDisplayed(false); }
    }
    /**
     * Shows a field in the form.
     * @param {Ext.form.Field} f An Ext Form Field to show.
     */
    ,showField: function(f) {
        f.enable();
        f.show();
        var d = f.getEl().up('.x-form-item');
        if (d) { d.setDisplayed(true); }
    }
    
    /**
     * If set for the window, displays a help dialog.
     * @abstract
     */
    ,help: function() {
        Ext.Msg.alert(_('help'),_('help_not_yet'));
    }
});
Ext.reg('modx-window',MODx.Window);