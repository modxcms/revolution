MODx.page.EditFile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        formpanel: 'modx-panel-file-edit'
        ,components: [{
            xtype: 'modx-panel-file-edit'
            ,renderTo: 'modx-panel-file-edit-div'
            ,file: config.file
        }]
        ,buttons: [{
            process: 'update'
            ,text: _('save')
            ,method: 'remote'
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,alt: true
                ,ctrl: true
            }]
        },'-',{
            process: 'cancel'
            ,text: _('cancel')
            ,params: {a:MODx.action['welcome']}
        }]
    });
    MODx.page.EditFile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.EditFile,MODx.Component);
Ext.reg('modx-page-file-edit',MODx.page.EditFile);

MODx.panel.EditFile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-file-edit'
        ,url: MODx.config.connectors_url+'browser/file.php'
        ,baseParams: {
            action: 'update'
            ,file: config.file
        }
        ,border: false
        ,bodyStyle: ''
        ,items: [{
            html: '<h2>'+_('file_edit')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-chunk-header'
        },MODx.getPageStructure([{
            title: _('file_edit')
            ,id: 'modx-form-file-edit'            
            ,bodyStyle: 'padding: 15px;'
            ,defaults: { border: false ,msgTarget: 'side' }
            ,layout: 'form'
            ,labelWidth: 150
            ,items: [{
                xtype: 'textfield'
                ,fieldLabel: _('name')
                ,name: 'name'
                ,id: 'modx-file-name'
                ,width: 300
            },{
                xtype: 'statictextfield'
                ,fieldLabel: _('file_size')
                ,name: 'size'
                ,id: 'modx-file-size'
            },{
                xtype: 'statictextfield'
                ,fieldLabel: _('file_last_accessed')
                ,name: 'last_accessed'
                ,id: 'modx-file-last-accessed'
                ,width: 200
            },{
                xtype: 'statictextfield'
                ,fieldLabel: _('file_last_modified')
                ,name: 'last_modified'
                ,id: 'modx-file-last-modified'
                ,width: 200
            },{
                xtype: 'textarea'
                ,hideLabel: true
                ,name: 'content'
                ,id: 'modx-file-content'
                ,anchor: '95%'
                ,grow: false
                ,height: 400
                ,style: 'font-size: 11px;'
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
        if (this.initialized) {
            this.fireEvent('ready');
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'get'
                ,file: this.config.file
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getForm().setValues(r.object);
                    this.fireEvent('ready',r);
                    this.initialized = true;
                },scope:this}
            }
        });
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