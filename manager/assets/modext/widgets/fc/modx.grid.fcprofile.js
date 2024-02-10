/**
 * @class MODx.panel.FCProfiles
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration options
 * @xtype modx-panel-fc-profiles
 */
MODx.panel.FCProfiles = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-fc-profiles'
        ,cls: 'container'
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: _('form_customization')
            ,id: 'modx-fcp-header'
            ,xtype: 'modx-header'
        },MODx.getPageStructure([{
            title: _('profiles')
            ,autoHeight: true
            ,layout: "form"
            ,items: [{
                html: '<p>'+_('form_customization_msg')+'</p>'
                ,xtype: 'modx-description'
            },{
                title: ''
                ,preventRender: true
                ,xtype: 'modx-grid-fc-profile'
                ,urlFilters: ['search']
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

/**
 * @class MODx.grid.FCProfile
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-fc-profile
 */
MODx.grid.FCProfile = function(config) {
    config = config || {};
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config,{
        id: 'modx-grid-fc-profile'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'Security/Forms/Profile/GetList'
        }
        ,fields: ['id','name','description','usergroups','active','rank','sets','perm']
        ,paging: true
        ,autosave: true
        ,save_action: 'Security/Forms/Profile/UpdateFromGrid'
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
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=security/forms/profile/update&id=' + record.data.id
                });
            }, scope: this }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 250
            ,sortable: true
            ,editor: { xtype: 'textarea' }
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
            text: _('create')
            ,scope: this
            ,handler: this.createProfile
            ,cls:'primary-button'
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
        },'->',{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-fcp-search'
            ,cls: 'x-form-filter'
            ,emptyText: _('search')
            ,value: MODx.request.search
            ,listeners: {
                'change': {
                    fn: function (cb, rec, ri) {
                        this.fcpSearch(cb, rec, ri);
                    }
                    ,scope: this
                },
                'afterrender': {
                    fn: function (cb){
                        if (MODx.request.search) {
                            this.fcpSearch(cb, cb.value);
                            MODx.request.search = '';
                        }
                    }
                    ,scope: this
                }
                ,'render': {
                    fn: function(cmp) {
                        new Ext.KeyMap(cmp.getEl(), {
                            key: Ext.EventObject.ENTER
                            ,fn: this.blur
                            ,scope: cmp
                        });
                    }
                    ,scope: this
                }
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
                    text: _('delete')
                    ,handler: this.confirm.createDelegate(this,['Security/Forms/Profile/Remove','profile_remove_confirm'])
                });
            }
        }

        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
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
        location.href = '?a=security/forms/profile/update&id='+r.id;
    }

    ,duplicateProfile: function(btn,e) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'security/forms/profile/duplicate'
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
                action: 'Security/Forms/Profile/Activate'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,deactivateProfile: function(btn,e) {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'Security/Forms/Profile/Deactivate'
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
                action: 'Security/Forms/Profile/ActivateMultiple'
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

    ,deactivateSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'Security/Forms/Profile/DeactivateMultiple'
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
            title: _('selected_remove')
            ,text: _('profile_remove_multiple_confirm')
            ,url: this.config.url
            ,params: {
                action: 'Security/Forms/Profile/RemoveMultiple'
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

    ,fcpSearch: function(tf,newValue,oldValue) {
        var s = this.getStore();
        s.baseParams.search = newValue;
        this.replaceState();
        this.getBottomToolbar().changePage(1);
    }

    ,clearFilter: function() {
        var s = this.getStore();
        var fcpSearch = Ext.getCmp('modx-fcp-search');
        s.baseParams = {
            action: 'Security/Forms/Profile/GetList'
        };
        MODx.request.search = '';
        fcpSearch.setValue('');
        this.replaceState();
        this.getBottomToolbar().changePage(1);
    }
});
Ext.reg('modx-grid-fc-profile',MODx.grid.FCProfile);

/**
 * @class MODx.window.CreateFCProfile
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-fc-profile-create
 */
MODx.window.CreateFCProfile = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('create')
        ,url: MODx.config.connector_url
        ,action: 'Security/Forms/Profile/Create'
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
