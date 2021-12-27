/**
 * Loads the create file page
 *
 * @class MODx.page.CreateFile
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-file-create
 */
MODx.page.CreateFile = function(config) {
    config = config || {};
    var buttons = [{
        process: 'Browser/File/Create'
        ,text: _('save')
        ,id: 'modx-abtn-save'
        ,cls: 'primary-button'
        ,method: 'remote'
        ,keys: [{
            key: MODx.config.keymap_save || 's'
            ,ctrl: true
        }]
    },{
        text: _('cancel')
        ,id: 'modx-abtn-cancel'
    }];

    Ext.applyIf(config,{
        formpanel: 'modx-panel-file-create'
        ,components: [{
            xtype: 'modx-panel-file-create'
            ,directory: config.directory
            ,record: config.record || {}
        }]
        ,buttons: buttons
    });
    MODx.page.CreateFile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.CreateFile,MODx.Component);
Ext.reg('modx-page-file-create',MODx.page.CreateFile);
/**
 * Loads the CreateFile panel
 *
 * @class MODx.panel.CreateFile
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-file-create
 */
MODx.panel.CreateFile = function(config) {
    config = config || {};
    config.record = config.record || {};
    Ext.applyIf(config,{
        id: 'modx-panel-file-create'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Browser/File/Create'
            ,directory: config.directory
            ,wctx: MODx.request.wctx
        }
        ,cls: 'container form-with-labels'
        ,template: ''
        ,bodyStyle: ''
        ,items: [{
            html: _('file_create')
            ,id: 'modx-file-header'
            ,xtype: 'modx-header'
        },MODx.getPageStructure([{
            title: _('file_create')
            ,id: 'modx-form-file-create'
            ,defaults: { border: false ,msgTarget: 'side' }
            ,layout: 'form'
            ,labelWidth: 150
            ,items: [{
                xtype: 'panel'
                ,border: false
                ,cls:'main-wrapper'
                ,layout: 'form'
                ,labelAlign: 'top'
                ,items: [{
                    xtype: 'hidden'
                    ,name: 'source'
                    ,value: config.record.source || 0
                },{
                    xtype: 'statictextfield'
                    ,submitValue: true
                    ,fieldLabel: _('directory')
                    ,name: 'directory'
                    ,id: 'modx-file-directory'
                    ,anchor: '100%'
                    ,value: config.record.directory || ''
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('file_name')
                    ,name: 'name'
                    ,id: 'modx-file-name'
                    ,anchor: '100%'
                    ,allowBlank: false
                    ,listeners: {
                        'keyup': {scope:this,fn:function(f,e) {
                            Ext.getCmp('modx-file-header').getEl().update(_('file_create')+': '+f.getValue());
                        }}
                    }
                },{
                    xtype: 'textarea'
                    ,hideLabel: false
                    ,fieldLabel: _('content')
                    ,name: 'content'
                    ,id: 'modx-file-content'
                    ,anchor: '100%'
                    ,grow: false
                    ,height: 400
                }]
            }]
        }])]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.CreateFile.superclass.constructor.call(this,config);
    this.addEvents('ready');
};
Ext.extend(MODx.panel.CreateFile,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        this.fireEvent('ready',this.config.record);
        return true;
    }
    ,success: function(r) {
        MODx.loadPage('system/file/edit', 'file='+r.result.object.file+'&source='+MODx.request.source);
    }
    ,beforeSubmit: function(o) {
        this.cleanupEditor();
        return this.fireEvent('save',{
            values: this.getForm().getValues()
        });
    }
    ,cleanupEditor: function() {
        if (MODx.onSaveEditor) {
            var fld = Ext.getCmp('modx-file-content');
            MODx.onSaveEditor(fld);
        }
    }
});
Ext.reg('modx-panel-file-create',MODx.panel.CreateFile);
