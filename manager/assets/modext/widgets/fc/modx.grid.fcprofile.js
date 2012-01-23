MODx.panel.FCProfiles = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-fc-profiles'
		,cls: 'container'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
             html: '<h2>'+_('form_customization')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
            ,id: 'modx-fcp-header'
        },MODx.getPageStructure([{
            title: _('profiles')
            ,autoHeight: true
			,layout: "form"
            ,items: [{
                html: '<p>'+_('form_customization_msg')+'</p>'
				,bodyCssClass: 'panel-desc'
                ,border: false
            },{
                title: ''
                ,preventRender: true
                ,xtype: 'modx-grid-fc-profile'
				,cls:'main-wrapper'
            }]
        }],{
            id: 'modx-form-customization-tabs'
        })]
    });
    MODx.panel.FCProfiles.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.FCProfiles,MODx.FormPanel);
Ext.reg('modx-panel-fc-profiles',MODx.panel.FCProfiles);

MODx.grid.FCProfile = function(config) {
    config = config || {};
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config,{
        id: 'modx-grid-fc-profile'
        ,url: MODx.config.connectors_url+'security/forms/profile.php'
        ,fields: ['id','name','description','usergroups','active','rank','sets','perm']
        ,paging: true
        ,autosave: true
        ,sm: this.sm
        ,remoteSort: true
        ,columns: [this.sm,{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 40
            ,sortable: true
        },{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
            ,sortable: true
            ,editor: { xtype: 'textfield' }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 250
            ,sortable: true
            ,editor: { xtype: 'textfield' }
        },{
            header: _('usergroups')
            ,dataIndex: 'usergroups'
            ,width: 150
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
            text: _('profile_create')
            ,scope: this
            ,handler: this.createProfile
        },'-',{
            text: _('bulk_actions')
            ,menu: [{
                text: _('selected_activate')
                ,handler: this.activateSelected
                ,scope: this
            },{
                text: _('selected_deactivate')
                ,handler: this.deactivateSelected
                ,scope: this
            },'-',{
                text: _('selected_remove')
                ,handler: this.removeSelected
                ,scope: this
            }]
        },'->',{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-fcp-search'
            ,emptyText: _('filter_by_search')
            ,listeners: {
                'change': {fn: this.search, scope: this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: function() {
                            this.fireEvent('change',this.getValue());
                            this.blur();
                            return true;}
                        ,scope: cmp
                    });
                },scope:this}
            }
        },{
            xtype: 'button'
            ,id: 'modx-filter-clear'
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this}
            }
        }]
    });
    MODx.grid.FCProfile.superclass.constructor.call(this,config);
    this.on('render',function() { this.getStore().reload(); },this);
};
Ext.extend(MODx.grid.FCProfile,MODx.grid.Grid,{
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
                    ,handler: this.updateProfile
                },{
                    text: _('duplicate')
                    ,handler: this.duplicateProfile
                },'-');
                if (r.data.active) {
                    m.push({
                        text: _('deactivate')
                        ,handler: this.deactivateProfile
                    });
                } else {
                    m.push({
                        text: _('activate')
                        ,handler: this.activateProfile
                    });
                }
            }
            if (p.indexOf('premove') != -1) {
                m.push('-',{
                    text: _('remove')
                    ,handler: this.confirm.createDelegate(this,['remove','profile_remove_confirm'])
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
        this.refresh();
        return true;
    }
    ,clearFilter: function() {
    	this.getStore().baseParams = {
            action: 'getList'
    	};
        Ext.getCmp('modx-fcp-search').reset();
    	this.getBottomToolbar().changePage(1);
        this.refresh();
    }

    ,createProfile: function(btn,e) {
        if (!this.windows.cpro) {
            this.windows.cpro = MODx.load({
                xtype: 'modx-window-fc-profile-create'
                ,listeners: {
                    'success': {fn:function(r) {
                        this.refresh();
                    },scope:this}
                }
            });
        }
        this.windows.cpro.reset();
        this.windows.cpro.show(e.target);
    }

    ,updateProfile: function(btn,e) {
        var r = this.menu.record;
        location.href = '?a='+MODx.action['security/forms/profile/update']+'&id='+r.id;
    }
    ,duplicateProfile: function(btn,e) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'duplicate'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,activateProfile: function(btn,e) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'activate'
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
                action: 'activateMultiple'
                ,profiles: cs
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
    ,deactivateProfile: function(btn,e) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'deactivate'
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
                action: 'deactivateMultiple'
                ,profiles: cs
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
            title: _('profile_remove_multiple')
            ,text: _('profile_remove_multiple_confirm')
            ,url: this.config.url
            ,params: {
                action: 'removeMultiple'
                ,profiles: cs
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
Ext.reg('modx-grid-fc-profile',MODx.grid.FCProfile);


MODx.window.CreateFCProfile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('profile_create')
        ,url: MODx.config.connectors_url+'security/forms/profile.php'
        ,action: 'create'
        ,height: 150
        ,width: 375
        ,fields: [{
            xtype: 'textfield'
            ,name: 'name'
            ,fieldLabel: _('name')
            ,id: 'modx-fccp-name'
            ,allowBlank: false
            ,anchor: '100%'

        },{
            xtype: 'textarea'
            ,name: 'description'
            ,fieldLabel: _('description')
            ,id: 'modx-fccp-description'
            ,anchor: '100%'

        },{
            xtype: 'xcheckbox'
            ,boxLabel: _('active')
            ,hideLabel: true
            ,name: 'active'
            ,id: 'modx-fccp-active'
            ,inputValue: 1
            ,value: 1
            ,checked: true
            ,anchor: '100%'
        }]
        ,keys: []
    });
    MODx.window.CreateFCProfile.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateFCProfile,MODx.Window);
Ext.reg('modx-window-fc-profile-create',MODx.window.CreateFCProfile);
