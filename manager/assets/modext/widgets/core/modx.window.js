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
        ,layout: 'auto'
        ,closeAction: 'hide'
        ,shadow: true
        ,resizable: true
        ,collapsible: true
        ,maximizable: true
        ,autoHeight: true
        ,allowDrop: true
        ,width: 450
        ,cls: 'modx-window'
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { config.closeAction !== 'close' ? this.hide() : this.close(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: this.submit
        }]
        ,record: {}
        ,keys: [{
            key: Ext.EventObject.ENTER
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.Window.superclass.constructor.call(this,config);
    this.options = config;
    this.config = config;
	
    this.addEvents({
        success: true
        ,failure: true
        ,beforeSubmit: true
    });
    this._loadForm();
    this.on('show',function() {
        if (this.config.blankValues) { this.fp.getForm().reset(); }
        if (this.config.allowDrop) { this.loadDropZones(); }
        this.syncSize();
        this.focusFirstField();
    },this);
};
Ext.extend(MODx.Window,Ext.Window,{
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

    ,focusFirstField: function() {
        if (this.fp && this.fp.getForm() && this.fp.getForm().items.getCount() > 0) {
            var fld = this.findFirstTextField();
            if (fld) { fld.focus(false,200); }
        }
    }
    ,findFirstTextField: function(i) {
        i = i || 0;
        var fld = this.fp.getForm().items.itemAt(i);
        if (!fld) return false;
        if (fld.isXType('combo') || fld.isXType('checkbox') || fld.isXType('radio') || fld.isXType('displayfield') || fld.isXType('statictextfield') || fld.isXType('hidden')) {
            i = i+1;
            fld = this.findFirstTextField(i);
        }
        return fld;
    }
	
    ,submit: function(close) {
        close = close === false ? false : true;
        var f = this.fp.getForm();
        if (f.isValid() && this.fireEvent('beforeSubmit',f.getValues())) {
            f.submit({
                waitMsg: _('saving')
                ,scope: this
                ,failure: function(frm,a) {
                    if (this.fireEvent('failure',{f:frm,a:a})) {
                        MODx.form.Handler.errorExt(a.result,frm);
                    }
                }
                ,success: function(frm,a) {
                    if (this.config.success) {
                        Ext.callback(this.config.success,this.config.scope || this,[frm,a]);
                    }
                    this.fireEvent('success',{f:frm,a:a});
                    if (close) { this.config.closeAction !== 'close' ? this.hide() : this.close(); }
                }
            });
        }
    }
	
    ,createForm: function(config) {
        Ext.applyIf(this.config,{
            formFrame: true
            ,border: false
            ,bodyBorder: false
            ,autoHeight: true
        });
        config = config || {};
        Ext.applyIf(config,{
            labelAlign: this.config.labelAlign || 'top'
            ,labelWidth: this.config.labelWidth || 100
            ,labelSeparator: this.config.labelSeparator || ''
            ,frame: this.config.formFrame
            ,border: this.config.border
            ,bodyBorder: this.config.bodyBorder
            ,autoHeight: this.config.autoHeight
            ,anchor: '100% 100%'
            ,errorReader: MODx.util.JSONReader
            ,defaults: this.config.formDefaults || {
                msgTarget: this.config.msgTarget || 'under'
            }
            ,url: this.config.url
            ,baseParams: this.config.baseParams || {}
            ,fileUpload: this.config.fileUpload || false
        });
        return new Ext.FormPanel(config);
    }

    ,renderForm: function() {
        this.add(this.fp);
    }
	
    ,checkIfLoaded: function(r) {
        r = r || {};
        if (this.fp && this.fp.getForm()) { /* so as not to duplicate form */
            this.fp.getForm().reset();
            this.fp.getForm().setValues(r);
            return true;
        }
        return false;
    }
    
    ,setValues: function(r) {
        if (r === null) { return false; }
        this.fp.getForm().setValues(r);
    }
    ,reset: function() {
        this.fp.getForm().reset();
    }
    
    ,hideField: function(f) {
        f.disable();
        f.hide();
        var d = f.getEl().up('.x-form-item');
        if (d) { d.setDisplayed(false); }
    }

    ,showField: function(f) {
        f.enable();
        f.show();
        var d = f.getEl().up('.x-form-item');
        if (d) { d.setDisplayed(true); }
    }
    
    ,loadDropZones: function() {
        if (this._dzLoaded) return false;
        var flds = this.fp.getForm().items;
        flds.each(function(fld) {
            if (fld.isFormField && (
                fld.isXType('textfield') || fld.isXType('textarea')
            ) && !fld.isXType('combo')) {
                new MODx.load({
                    xtype: 'modx-treedrop'
                    ,target: fld
                    ,targetEl: fld.getEl().dom
                });
            }
        });
        this._dzLoaded = true;
    }
});
Ext.reg('modx-window',MODx.Window);
