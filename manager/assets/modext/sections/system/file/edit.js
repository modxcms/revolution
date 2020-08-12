/**
 * Loads the edit file page
 *
 * @class MODx.page.EditFile
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-file-edit
 */
MODx.page.EditFile = function(config) {
    config = config || {};
    var btns = [];
    if (config.canSave) {
        btns.push({
            process: 'Browser/File/Update'
            ,text: _('save')
            ,id: 'modx-abtn-save'
            ,cls: 'primary-button'
            ,method: 'remote'
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        });
    }
    btns.push({
        text: _('cancel')
        ,id: 'modx-abtn-cancel'
    });

    Ext.applyIf(config,{
        formpanel: 'modx-panel-file-edit'
        ,components: [{
            xtype: 'modx-panel-file-edit'
            ,file: config.file
            ,record: config.record || {}
        }]
        ,buttons: btns
    });
    MODx.page.EditFile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.EditFile,MODx.Component);
Ext.reg('modx-page-file-edit',MODx.page.EditFile);

/**
 * Loads the EditFile panel
 *
 * @class MODx.panel.EditFile
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-file-edit
 */
MODx.panel.EditFile = function(config) {
    config = config || {};
    config.record = config.record || {};
    Ext.applyIf(config,{
        id: 'modx-panel-file-edit'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Browser/File/Update'
            ,file: config.file
            ,wctx: MODx.request.wctx
        }
        ,cls: 'container form-with-labels'
        ,class_key: 'modTemplate'
        ,template: ''
        ,bodyStyle: ''
        ,items: [{
            html: _('file_edit')+': '+config.record.basename
            ,id: 'modx-file-header'
            ,xtype: 'modx-header'
        },MODx.getPageStructure([{
            title: _('file_edit')
            ,id: 'modx-form-file-edit'
            ,defaults: { border: false ,msgTarget: 'side' }
            ,layout: 'form'
            ,labelWidth: 150
            ,items: [{
                xtype: 'panel'
                ,border: false
                ,layout: 'form'
                ,cls:'main-wrapper'
                ,items: [{
                    xtype: 'hidden'
                    ,name: 'source'
                    ,value: config.record.source || 0
                },{
                    xtype: 'statictextfield'
                    ,fieldLabel: _('file_name')
                    ,name: 'basename'
                    ,id: 'modx-file-basename'
                    ,anchor: '100%'
                    ,value: config.record.basename || ''
                },{
                    xtype: 'statictextfield'
                    ,fieldLabel: _('path')
                    ,name: 'name'
                    ,id: 'modx-file-name'
                    ,value: config.record.name || ''
                    ,anchor: '100%'
                    ,submitValue: true
                },{
                    xtype: 'statictextfield'
                    ,fieldLabel: _('file_size')
                    ,name: 'size'
                    ,id: 'modx-file-size'
                    ,anchor: '100%'
                    ,value: (config.record.size || 0) + ' B'
                },{
                    xtype: 'statictextfield'
                    ,fieldLabel: _('file_last_accessed')
                    ,name: 'last_accessed'
                    ,id: 'modx-file-last-accessed'
                    ,anchor: '100%'
                    ,value: MODx.util.Format.dateFromTimestamp(config.record.last_accessed)
                },{
                    xtype: 'statictextfield'
                    ,fieldLabel: _('file_last_modified')
                    ,name: 'last_modified'
                    ,id: 'modx-file-last-modified'
                    ,anchor: '100%'
                    ,value: MODx.util.Format.dateFromTimestamp(config.record.last_modified)
                },{
                    xtype: 'textarea'
                    ,hideLabel: true
                    ,name: 'content'
                    ,id: 'modx-file-content'
                    ,anchor: '100%'
                    ,height: 400
                    ,grow: false
                    ,value: config.record.content || ''
                }]
            }]
        }])]
        ,listeners: {
            'setup': {fn:this.setup,scope:this}
            ,'success': {fn:this.success,scope:this}
            ,'beforeSubmit': {fn:this.beforeSubmit,scope:this}
        }
    });
    MODx.panel.EditFile.superclass.constructor.call(this,config);
    this.addEvents('ready');
};
Ext.extend(MODx.panel.EditFile,MODx.FormPanel,{
    initialized: false
    ,setup: function() {
        this.fireEvent('ready',this.config.record);
        return true;
    }
    ,success: function(r) {
        this.getForm().setValues(r.result.object);
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
Ext.reg('modx-panel-file-edit',MODx.panel.EditFile);
