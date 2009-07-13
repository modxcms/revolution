/**
 * @class MODx.page.EditFile
 * @extends MODx.Component
 * @param {Object} config An object of configuration properties
 * @xtype page-file-edit
 */
MODx.page.EditFile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'panel-file-edit'
            ,renderTo: 'panel-file-edit-div'
            ,file: config.file
        }]
    });
    MODx.page.EditFile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.EditFile,MODx.Component);
Ext.reg('page-file-edit',MODx.page.EditFile);

/**
 * @class MODx.panel.EditFile
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype panel-file-edit
 */
MODx.panel.EditFile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('file_edit')
        ,url: MODx.config.connectors_url+'browser/file.php'
        ,baseParams: {
            action: 'update'
            ,file: config.file
        }
        ,width: '90%'
        ,autoHeight: true
        ,collapsible: true
        ,buttonAlign: 'center'
        ,style: 'margin: 1em;'
        ,bodyStyle: 'padding: 1em;'
        ,items: [{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,id: 'file_name'
            ,width: 300
        },{
            xtype: 'statictextfield'
            ,fieldLabel: _('file_size')
            ,name: 'size'
        },{
            xtype: 'statictextfield'
            ,fieldLabel: _('file_last_accessed')
            ,name: 'last_accessed'
            ,width: 200
        },{
            xtype: 'statictextfield'
            ,fieldLabel: _('file_last_modified')
            ,name: 'last_modified'
            ,width: 200
        },{
            xtype: 'textarea'
            ,fieldLabel: _('content')
            ,name: 'content'
            ,grow: true
            ,width: '95%'
        }]
        ,buttons: [{
            text: _('save')
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.panel.EditFile.superclass.constructor.call(this,config);
    Ext.getCmp('modx_file_tree').expand();
    this.config = config;
    this.setup();
};
Ext.extend(MODx.panel.EditFile,Ext.FormPanel,{
    setup: function() {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,file: this.config.file
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                },scope:this}
            }
        });
    }
    ,submit: function() {
        if (this.getForm().isValid()) {
            this.getForm().submit({
                waitMsg: _('processing')
                ,reset: false
                ,scope: this
                ,failure: function(f,a) {
                    MODx.form.Handler.errorExt(a.result);
                }
                ,success: function(f,a) {
                    this.config.file = a.result.object.file;
                    this.getForm().baseParams.file = this.config.file;
                }
            });
        }
    }
});
Ext.reg('panel-file-edit',MODx.panel.EditFile);