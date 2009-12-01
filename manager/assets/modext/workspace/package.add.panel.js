/** 
 * Generates the Package Downloader wizard.
 *  
 * @class MODx.window.PackageDownloader
 * @extends Ext.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-package-downloader
 */
MODx.window.PackageDownloader = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('package_retriever')
        ,id: 'modx-window-package-downloader'
        ,resizable: true
        ,collapsible: true
        ,maximizable: true
        ,autoHeight: true
        ,width: '90%'
        ,firstPanel: 'modx-pd-start'
        ,lastPanel: 'modx-pd-selpackage'
        ,bodyStyle: 'background-color: white;'
        ,modal: Ext.isIE ? false : true
        ,items: [{
            xtype: 'modx-panel-pd-first'
        },{
            xtype: 'modx-panel-pd-selprov'
        },{
            xtype: 'modx-panel-pd-newprov'
        },{
            xtype: 'modx-panel-pd-selpackage'
        }]
    });
    MODx.window.PackageDownloader.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.PackageDownloader,MODx.Wizard);
Ext.reg('modx-window-package-downloader',MODx.window.PackageDownloader);


MODx.panel.PDFirst = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-pd-start'
        ,back: 'modx-pd-start'
        ,defaults: { labelSeparator: '', border: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('package_retriever')+'</h2>'
            ,autoHeight: true
        },{
            html: '<p>'+_('package_obtain_method')+'</p>'   
            ,style: 'padding-bottom: 2em'
            ,autoHeight: true
        },{
            boxLabel: _('provider_select')
            ,xtype: 'radio'
            ,inputValue: 'selprov'
            ,name: 'method'
            ,id: 'modx-pdfirst-selprov'
            ,checked: true
        },{
            boxLabel: _('provider_add')
            ,xtype: 'radio'
            ,inputValue: 'newprov'
            ,name: 'method'
            ,id: 'modx-pdfirst-newprov'
        },{
            boxLabel: _('package_search_local_title')
            ,xtype: 'radio'
            ,inputValue: 'local'
            ,name: 'method'
            ,id: 'modx-pdfirst-local'
        }]
    });
    MODx.panel.PDFirst.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PDFirst,MODx.panel.WizardPanel,{
    submit: function(o) {
        var va = this.getForm().getValues();
        if (!va.method) {
            
        } else if (va.method === 'local') {
           this.searchLocal();
        } else {
            Ext.getCmp('modx-window-package-downloader').fireEvent('proceed','modx-pd-'+va.method);
        }
    }
    
    ,searchLocal: function() {
        MODx.msg.confirm({
           title: _('package_search_local_title')
           ,text: _('package_search_local_confirm')
           ,url: MODx.config.connectors_url+'workspace/packages.php'
           ,params: {
                action: 'scanLocal' 
           }
           ,listeners: {
                'success':{fn:function(r) {
                    Ext.getCmp('modx-grid-package').refresh();
                    Ext.getCmp('modx-window-package-downloader').hide();
                },scope:this}
           }
        });
    }
});
Ext.reg('modx-panel-pd-first',MODx.panel.PDFirst);


MODx.panel.PDSelProv = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-pd-selprov'
        ,back: 'modx-pd-start'
        ,defaults: {border: false}
        ,items: [{
            html: '<h2>'+ _('provider_select')+'</h2>'
            ,autoHeight: true
        },{
            html: '<p>'+_('provider_select_desc')+'</p>'
            ,style: 'padding-bottom: 2em;'
            ,autoHeight: true
        },{
            fieldLabel: _('provider')
            ,xtype: 'modx-combo-provider'
            ,id: 'modx-pdselprov-provider'
            ,allowBlank: false
        },{
            text: _('provider_add_or')
            ,xtype: 'button'
            ,id: 'modx-pdselprov-addnew'
            ,scope: this
            ,handler: function() {
                Ext.getCmp('modx-window-package-downloader').proceed('modx-pd-newprov');
            }
        }]
    });
    MODx.panel.PDSelProv.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PDSelProv,MODx.panel.WizardPanel,{
    submit: function(o) {
        if (this.getForm().isValid()) {
            var vs = this.getForm().getValues();
            Ext.getCmp('modx-window-package-downloader').fireEvent('proceed','modx-pd-selpackage');
            Ext.getCmp('modx-tree-package-download').setProvider(vs.provider);
            Ext.getCmp('modx-pd-selpackage').provider = vs.provider;
        }
    }
});
Ext.reg('modx-panel-pd-selprov',MODx.panel.PDSelProv);


MODx.panel.PDNewProv = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-pd-newprov'
        ,back: 'modx-pd-start'
        ,defaults: { border: false }
        ,url: MODx.config.connectors_url+'workspace/providers.php'
        ,baseParams: {
            action: 'create'
        }
        ,items: [{
            html: '<h2>'+_('provider_add')+'</h2>'
            ,autoHeight: true
        },{
            fieldLabel: _('name')
            ,xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-pdnewprov-name'
            ,allowBlank: false
            ,width: 200
        },{
            fieldLabel: _('description')
            ,xtype: 'textarea'
            ,name: 'description'
            ,id: 'modx-pdnewprov-description'
            ,width: 200
        },{
            fieldLabel: _('provider_url')
            ,xtype: 'textfield'
            ,name: 'service_url'
            ,id: 'modx-pdnewprov-service-url'
            ,vtype: 'url'
            ,allowBlank: false
            ,width: 300
        }]
    });
    MODx.panel.PDNewProv.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.PDNewProv,MODx.panel.WizardPanel,{
    submit: function(o) {
        if (this.getForm().isValid()) {
            this.getForm().submit({
                waitMsg: _('saving')
                ,scope: this
                ,failure: function(f,a) {
                    MODx.form.Handler.errorExt(a.result,f);
                }
                ,success: function(f,a) {
                    var p = a.result.object;
                    Ext.getCmp('modx-window-package-downloader').fireEvent('proceed','modx-pd-selpackage');
                    Ext.getCmp('modx-tree-package-download').setProvider(p.id);
                    Ext.getCmp('modx-pd-selpackage').provider = p.id;
                }
            });
        }
    }
});
Ext.reg('modx-panel-pd-newprov',MODx.panel.PDNewProv);



MODx.panel.PDSelPackage = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-pd-selpackage'
        ,back: 'modx-pd-selprov'
        ,url: MODx.config.connectors_url+'workspace/providers.php'
        ,baseParams: {
            action: 'download'
        }
        ,autoHeight: true
        ,items: [{
            html: '<h2>'+_('package_select_download')+'</h2>'
            ,id: 'modx-pdselpackage-header'
            ,border: false
            ,autoHeight: true
        },{
            html: '<p>'+_('package_select_download_desc')+'</p>'
            ,id: 'modx-pdselpackage-desc'
            ,style: 'padding-bottom: 2em'
            ,border: false
            ,autoHeight: true
        },{
            xtype: 'modx-panel-package-download'
        }]
    });
    MODx.panel.PDSelPackage.superclass.constructor.call(this,config);
    this.on('show',function() {
        var pd = Ext.getCmp('modx-window-package-downloader');
        pd.setHeight(600);
        pd.center();
    },this);
    
};
Ext.extend(MODx.panel.PDSelPackage,MODx.panel.WizardPanel,{
    provider: null
    
    ,submit: function(o) {
        var pkgs = Ext.getCmp('modx-tree-package-download').encode();
        if (pkgs.length > 0) {
            this.getForm().submit({
                waitMsg: _('downloading')
                ,params: {
                    packages: pkgs
                    ,provider: this.provider
                }
                ,scope: this
                ,failure: function(f,a) {
                    MODx.form.Handler.errorExt(a.result,f);
                }
                ,success: function(f,a) {
                    Ext.getCmp('modx-grid-package').refresh();
                    Ext.getCmp('modx-window-package-downloader').hide();
                }
            });
        } else { Ext.Msg.alert('',_('package_select_download_ns')); }
    }
});
Ext.reg('modx-panel-pd-selpackage',MODx.panel.PDSelPackage);