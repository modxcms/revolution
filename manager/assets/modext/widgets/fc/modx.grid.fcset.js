MODx.grid.FCSet = function(config) {
    config = config || {};
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config,{
        id: 'modx-grid-fc-set'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/forms/set/getlist'
        }
        ,fields: ['id','profile','action','description','active','template','templatename','constraint_data','constraint','constraint_field','constraint_class','rules','perm']
        ,paging: true
        ,autosave: true
        ,sm: this.sm
        ,remoteSort: true
        ,autoExpandColumn: 'controller'
        ,columns: [this.sm,{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 40
            ,sortable: true
        },{
            header: _('action')
            ,dataIndex: 'action'
            ,width: 200
            ,editable: true
            ,sortable: true
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 200
            ,editable: true
            ,sortable: true
        },{
            header: _('template')
            ,dataIndex: 'templatename'
            ,width: 150
            ,sortable: true
        },{
            header: _('constraint')
            ,dataIndex: 'constraint_data'
            ,width: 200
            ,editable: false
            ,sortable: false
        }]
        ,viewConfig: {
            forceFit:true
            ,enableRowBody:true
            ,scrollOffset: 0
            ,autoFill: true
            ,showPreview: true
            ,getRowClass : function(rec, ri, p){
                return rec.data.active ? 'grid-row-active' : 'grid-row-inactive';
            }
        }
        ,tbar: [{
            text: _('set_new')
            ,cls: 'primary-button'
            ,scope: this
            ,handler: this.createSet
        },{
            text: _('bulk_actions')
            ,menu: [{
                text: _('selected_activate')
                ,handler: this.activateSelected
                ,scope: this
            },{
                text: _('selected_deactivate')
                ,handler: this.deactivateSelected
                ,scope: this
            },{
                text: _('selected_remove')
                ,handler: this.removeSelected
                ,scope: this
            }]
        },{
            text: _('import_from_xml')
            ,handler: this.importSet
            ,scope: this
        },'->',{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-fcs-search'
            ,cls: 'x-form-filter'
            ,emptyText: _('filter_by_search')
            ,listeners: {
                'change': {fn: this.search, scope: this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: this.blur
                        ,scope: cmp
                    });
                },scope:this}
            }
        },{
            xtype: 'button'
            ,id: 'modx-filter-clear'
            ,cls: 'x-form-filter-clear'
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this},
                'mouseout': { fn: function(evt){
                    this.removeClass('x-btn-focus');
                }
                }
            }
        }]
    });
    MODx.grid.FCSet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.FCSet,MODx.grid.Grid,{
    getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var p = r.data.perm;

        var m = [];
        if (this.getSelectionModel().getCount() > 1) {
            m.push({
                text: _('selected_activate')
                ,handler: this.activateSelected
            });
            m.push({
                text: _('selected_deactivate')
                ,handler: this.deactivateSelected
            });
            m.push('-');
            m.push({
                text: _('selected_remove')
                ,handler: this.removeSelected
            });
        } else {
            if (p.indexOf('pedit') != -1) {
                m.push({
                    text: _('edit')
                    ,handler: this.updateSet
                });
                m.push({
                    text: _('duplicate')
                    ,handler: this.duplicateSet
                });
                m.push({
                    text: _('export')
                    ,handler: this.exportSet
                });
                m.push('-');
                if (r.data.active) {
                    m.push({
                        text: _('deactivate')
                        ,handler: this.deactivateSet
                    });
                } else {
                    m.push({
                        text: _('activate')
                        ,handler: this.activateSet
                    });
                }
            }
            if (p.indexOf('premove') != -1) {
                m.push('-',{
                    text: _('remove')
                    ,handler: this.confirm.createDelegate(this,['security/forms/set/remove','set_remove_confirm'])
                });
            }
        }

        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    }


    ,search: function(tf,newValue,oldValue) {
        var nv = newValue || tf;
        this.getStore().baseParams.search = Ext.isEmpty(nv) || Ext.isObject(nv) ? '' : nv;
        this.getBottomToolbar().changePage(1);
        //this.refresh();
        return true;
    }
    ,clearFilter: function() {
    	this.getStore().baseParams = {
            action: 'security/forms/set/getList'
            ,profile: MODx.request.id
    	};
        Ext.getCmp('modx-fcs-search').reset();
    	this.getBottomToolbar().changePage(1);
        //this.refresh();
    }

    ,exportSet: function(btn,e) {
        var id = this.menu.record.id;
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'security/forms/set/export'
                ,id: id
            }
            ,listeners: {
                'success': {fn:function(r) {
                    location.href = this.config.url+'?action=security/forms/set/export&download='+r.message+'&id='+id+'&HTTP_MODAUTH='+MODx.siteId;
                },scope:this}
            }
        });
    }

    ,importSet: function(btn,e) {
        var r = {
            profile: MODx.request.id
        };
        if (!this.windows.impset) {
            this.windows.impset = MODx.load({
                xtype: 'modx-window-fc-set-import'
                ,record: r
                ,listeners: {
                    'success': {fn:function(o) {
                        this.refresh();
                    },scope:this}
                }
            });
        }
        this.windows.impset.reset();
        this.windows.impset.setValues(r);
        this.windows.impset.show(e.target);
    }

    ,createSet: function(btn,e) {
        var r = {
            profile: MODx.request.id
            ,active: true
        };
        if (!this.windows.cset) {
            this.windows.cset = MODx.load({
                xtype: 'modx-window-fc-set-create'
                ,record: r
                ,listeners: {
                    'success': {fn:function(r) {
                        this.refresh();
                    },scope:this}
                }
            });
        }
        this.windows.cset.reset();
        this.windows.cset.setValues(r);
        this.windows.cset.show(e.target);
    }

    ,updateSet: function(btn,e) {
        var r = this.menu.record;
        location.href = '?a=security/forms/set/update&id='+r.id;
    }
    ,duplicateSet: function(btn,e) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'security/forms/set/duplicate'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,activateSet: function(btn,e) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'security/forms/set/activate'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,activateSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'security/forms/set/activateMultiple'
                ,sets: cs
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                },scope:this}
            }
        });
        return true;
    }
    ,deactivateSet: function(btn,e) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'security/forms/set/deactivate'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }
    ,deactivateSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'security/forms/set/deactivateMultiple'
                ,sets: cs
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                },scope:this}
            }
        });
        return true;
    }
    ,removeSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            title: _('set_remove_multiple')
            ,text: _('set_remove_multiple_confirm')
            ,url: this.config.url
            ,params: {
                action: 'security/forms/set/removeMultiple'
                ,sets: cs
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                },scope:this}
            }
        });
        return true;
    }
});
Ext.reg('modx-grid-fc-set',MODx.grid.FCSet);


MODx.window.CreateFCSet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('set_create')
        ,url: MODx.config.connector_url
        ,action: 'security/forms/set/create'
        // ,height: 150
        ,width: 600
        ,fields: [{
            xtype: 'hidden'
            ,name: 'profile'
            ,value: MODx.request.id
        },{
            xtype: 'hidden'
            ,fieldLabel: _('constraint_class')
            ,name: 'constraint_class'
            ,anchor: '100%'
            ,allowBlank: true
            ,value: 'modResource'
        },{
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,anchor: '100%'
                ,border: false
            }
            ,items: [{
                columnWidth: .5
                ,items: [{
                    fieldLabel: _('action')
                    ,name: 'action_id'
                    ,hiddenName: 'action_id'
                    ,id: 'modx-fcsc-action'
                    ,xtype: 'modx-combo-fc-action'
                    ,editable: false
                    ,allowBlank: false
                    ,anchor: '100%'
                },{
                    xtype: 'textarea'
                    ,name: 'description'
                    ,fieldLabel: _('description')
                    ,id: 'modx-fcsc-description'
                    ,anchor: '100%'
                },{
                    xtype: 'xcheckbox'
                    ,boxLabel: _('active')
                    ,hideLabel: true
                    ,name: 'active'
                    ,inputValue: 1
                    ,value: 1
                    ,checked: true
                    ,anchor: '100%'
                    ,allowBlank: true
                }]
            },{
                columnWidth: .5
                ,items: [{
                    xtype: 'modx-combo-template'
                    ,name: 'template'
                    ,hiddenName: 'template'
                    ,fieldLabel: _('template')
                    ,description: MODx.expandHelp ? '' : _('set_template_desc')
                    ,id: 'modx-fcsc-template'
                    ,anchor: '100%'
                    ,baseParams: { action: 'element/template/getList', combo: true }
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-fcsc-template'
                    ,html: _('set_template_desc')
                    ,cls: 'desc-under'

                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('constraint_field')
                    ,description: MODx.expandHelp ? '' : _('set_constraint_field_desc')
                    ,name: 'constraint_field'
                    ,id: 'modx-fcsc-constraint-field'
                    ,anchor: '100%'
                    ,allowBlank: true
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-fcsc-constraint-field'
                    ,html: _('set_constraint_field_desc')
                    ,cls: 'desc-under'

                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('constraint')
                    ,description: MODx.expandHelp ? '' : _('set_constraint_desc')
                    ,name: 'constraint'
                    ,id: 'modx-fcsc-constraint'
                    ,anchor: '100%'
                    ,allowBlank: true
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-fcsc-constraint'
                    ,html: _('set_constraint_desc')
                    ,cls: 'desc-under'

                }]
            }]
        }]
        ,keys: []
    });
    MODx.window.CreateFCSet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateFCSet,MODx.Window);
Ext.reg('modx-window-fc-set-create',MODx.window.CreateFCSet);


MODx.window.ImportFCSet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('import_from_xml')
        ,id: 'modx-window-fc-set-import'
        ,url: MODx.config.connector_url
        ,action: 'security/forms/set/import'
        ,fileUpload: true
        ,saveBtnText: _('import')
        ,fields: [{
            xtype: 'hidden'
            ,name: 'profile'
            ,value: MODx.request.id
        },{
            html: _('set_import_msg')
            ,id: 'modx-impset-desc'
            ,xtype: 'modx-description'
            ,style: 'margin-bottom: 10px;'
        },{
            xtype: 'fileuploadfield'
            ,fieldLabel: _('file')
            ,buttonText: _('upload.buttons.upload')
            ,name: 'file'
            ,id: 'modx-impset-file'
            ,anchor: '100%'
            // ,inputType: 'file'
        }]
    });
    MODx.window.ImportFCSet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.ImportFCSet,MODx.Window);
Ext.reg('modx-window-fc-set-import',MODx.window.ImportFCSet);
