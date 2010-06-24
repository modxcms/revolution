/** 
 * Generates the Package Installer wizard.
 *  
 * @class MODx.window.PackageInstaller
 * @extends MODx.Wizard
 * @param {Object} config An object of options.
 * @xtype modx-window-package-installer
 */
MODx.window.PackageInstaller = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('package_installer')
        ,id: 'modx-window-package-installer'
        ,resizable: true
        ,forceLayout: true
        ,autoHeight: true
        ,autoScroll: false
        ,stateful: false
        ,shadow: false
        ,width: '90%'
        ,anchor: '90%'
        ,hideMode: 'offsets'
        ,modal: Ext.isIE ? false : true
        ,firstPanel: 'modx-pi-license'
        ,lastPanel: 'modx-pi-install'
        ,items: [{
            xtype: 'modx-panel-pi-license'
        },{
            xtype: 'modx-panel-pi-readme'
        },{
            xtype: 'modx-panel-pi-install'
        }]
    });
    MODx.window.PackageInstaller.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.PackageInstaller,MODx.Wizard);
Ext.reg('modx-window-package-installer',MODx.window.PackageInstaller);

MODx.panel.PILicense = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-pi-license'
        ,back: 'modx-pi-license'
        ,hideLabels: true
        ,defaults: { labelSeparator: '', border: false }
        ,items: [{
            html: '<h2>'+_('license_agreement')+'</h2>'
            ,autoHeight: true
        },{
            html: '<p>'+_('license_agreement_desc')+'</p>'   
            ,style: 'padding-bottom: 20px'
            ,autoHeight: true
        },{
            xtype: 'textarea'
            ,name: 'license'
            ,id: 'modx-pi-license-box'
            ,width: '90%'
            ,height: 250
            ,value: ''
        },{
            boxLabel: _('license_agree')
            ,xtype: 'radio'
            ,inputValue: 'agree'
            ,name: 'agree'
        },{
            boxLabel: _('license_disagree')
            ,xtype: 'radio'
            ,inputValue: 'disagree'
            ,name: 'agree'
        }]
    });
    MODx.panel.PILicense.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PILicense,MODx.panel.WizardPanel,{
    submit: function() {
        var va = this.getForm().getValues();
        if (!va.agree) {
            
        } else if (va.agree === 'disagree') {
           Ext.getCmp('modx-window-package-installer').hide();
        } else {
           Ext.getCmp('modx-window-package-installer').fireEvent('proceed','modx-pi-readme');
        }
    }
    
    ,fetch: function() {
        var sig = Ext.getCmp('modx-grid-package').menu.record.signature;
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'workspace/packages.php'
            ,params: {
                action: 'getAttribute'
                ,signature: sig
                ,attr: 'license'
            }
            ,listeners: {
                'success': {fn:function(r) {
                    var a = r.object.attr;
                    var b = Ext.getCmp('modx-pi-license-box');
                    if (a !== null && a !== 'null' && a !== '') {
                        b.setValue(a);
                    } else {
                        b.setValue('');
                        Ext.getCmp('modx-window-package-installer').fireEvent('proceed','modx-pi-readme');
                    }
                },scope:this}
            }
        });
    }
});
Ext.reg('modx-panel-pi-license',MODx.panel.PILicense);

MODx.panel.PIReadme = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-pi-readme'
        ,back: 'modx-pi-license'
        ,hideLabels: true
        ,defaults: { labelSeparator: '', border: false }
        ,items: [{
            html: '<h2>'+_('readme')+'</h2>'
            ,autoHeight: true
        },{
            html: '<p>'+_('readme_desc')+'</p>'   
            ,style: 'padding-bottom: 20px'
            ,autoHeight: true
        },{
            xtype: 'textarea'
            ,name: 'readme'
            ,id: 'modx-pi-readme-box'
            ,width: '90%'
            ,height: 250
            ,value: ''
        }]
    });
    MODx.panel.PIReadme.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PIReadme,MODx.panel.WizardPanel,{
    submit: function() {
        var va = this.getForm().getValues();
        Ext.getCmp('modx-window-package-installer').fireEvent('proceed','modx-pi-install');
    }
    ,fetch: function() {
        var sig = Ext.getCmp('modx-grid-package').menu.record.signature;
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'workspace/packages.php'
            ,params: {
                action: 'getAttribute'
                ,signature: sig
                ,attr: 'readme'
            }
            ,listeners: {
                'success': {fn:function(r) {
                    var a = r.object.attr;
                    var b = Ext.getCmp('modx-pi-readme-box');
                    if (a !== null && a !== 'null' && a !== '') {
                        b.setValue(a);
                    } else {
                        b.setValue('');
                        Ext.getCmp('modx-window-package-installer').fireEvent('proceed','modx-pi-install');
                    }
                },scope:this}
            }
        });
    }
});
Ext.reg('modx-panel-pi-readme',MODx.panel.PIReadme);

MODx.panel.PIInstall = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-pi-install'
        ,back: 'modx-pi-readme'
        ,hideLabels: true
        ,defaults: { labelSeparator: '', border: false }
        ,bodyStyle: 'padding: 30px'
        ,items: [{
            html: '<h2>'+_('setup_options')+'</h2>'
            ,id: 'modx-setup-options-header'
        },{
            html: '<p>'+_('setup_options_desc')+'</p>'   
            ,style: 'padding-bottom: 2em'
            ,id: 'modx-setup-options-desc'
        },{
            html: ''
            ,id: 'modx-setup-options'
        }]
    });
    MODx.panel.PIInstall.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PIInstall,MODx.panel.WizardPanel,{
    submit: function() {
        var va = this.getForm().getValues();
        var pi = Ext.getCmp('modx-window-package-installer');
        pi.fireEvent('finish',va);
    }
    ,fetch: function() {
        var sig = Ext.getCmp('modx-grid-package').menu.record.signature;
        MODx.Ajax.request({
            url: MODx.config.connectors_url+'workspace/packages.php'
            ,params: {
                action: 'getAttribute'
                ,signature: sig
                ,attr: 'setup-options'
            }
            ,listeners: {
                'success': {fn:function(r) {
                    var a = r.object.attr;
                    var el = Ext.getCmp('modx-setup-options').getEl();
                    if (a !== null && a !== 'null' && a !== '') {
                        el.update(a);
                    } else {
                        var va = this.getForm().getValues();
                        Ext.getCmp('modx-window-package-installer').fireEvent('finish',va);
                    }
                },scope:this}
            }
        });
    }
});
Ext.reg('modx-panel-pi-install',MODx.panel.PIInstall);