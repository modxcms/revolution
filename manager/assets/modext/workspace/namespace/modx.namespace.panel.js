/**
 * Loads the panel for managing namespaces.
 *
 * @class MODx.panel.Namespaces
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-namespaces
 */
MODx.panel.Namespaces = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-namespaces'
        ,cls: 'container'
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: _('namespaces')
            ,id: 'modx-namespaces-header'
            ,xtype: 'modx-header'
        },MODx.getPageStructure([{
            title: _('namespaces')
            ,layout: 'form'
            ,items: [{
                html: '<p>'+_('namespaces_desc')+'</p>'
                ,xtype: 'modx-description'
            },{
                xtype: 'modx-grid-namespace'
                ,urlFilters: ['search']
                ,cls:'main-wrapper'
                ,preventRender: true
            }]
        }])]
    });
    MODx.panel.Namespaces.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Namespaces,MODx.FormPanel);
Ext.reg('modx-panel-namespaces',MODx.panel.Namespaces);

/**
 * Loads a grid for managing namespaces.
 *
 * @class MODx.grid.Namespace
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-namespace
 */
MODx.grid.Namespace = function(config) {
    config = config || {};
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config,{
        url: MODx.config.connector_url
        ,baseParams: {
            action: 'Workspace/PackageNamespace/GetList'
        }
        ,fields: ['id','name','path','assets_path','perm']
        ,anchor: '100%'
        ,paging: true
        ,autosave: true
        ,save_action: 'Workspace/PackageNamespace/UpdateFromGrid'
        ,primaryKey: 'name'
        ,remoteSort: true
        ,sm: this.sm
        ,columns: [this.sm,{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
            ,sortable: true
        },{
            header: _('namespace_path')
            ,dataIndex: 'path'
            ,width: 500
            ,sortable: false
            ,editor: { xtype: 'textfield' }
        },{
            header: _('namespace_assets_path')
            ,dataIndex: 'assets_path'
            ,width: 500
            ,sortable: false
            ,editor: { xtype: 'textfield' }
        }]
        ,tbar: [{
            text: _('create')
            ,handler: { xtype: 'modx-window-namespace-create' ,blankValues: true }
            ,cls:'primary-button'
            ,scope: this
        },'->',{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-namespace-search'
            ,cls: 'x-form-filter'
            ,emptyText: _('search')
            ,value: MODx.request.search
            ,listeners: {
                'change': {
                    fn: function (cb, rec, ri) {
                        this.namespaceSearch(cb, rec, ri);
                    }
                    ,scope: this
                },
                'afterrender': {
                    fn: function (cb){
                        if (MODx.request.search) {
                            this.namespaceSearch(cb, cb.value);
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
    MODx.grid.Namespace.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Namespace,MODx.grid.Grid,{
    getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var p = r.data.perm;
        var m = [];
        if (this.getSelectionModel().getCount() > 1) {
            m.push({
                text: _('selected_remove')
                ,handler: this.removeSelected
                ,scope: this
            });
        } else {
            m.push({
                text: _('edit')
                ,handler: this.namespaceUpdate
            });
            if (p.indexOf('premove') != -1 && this.menu.record.name != 'core') {
                m.push({
                    text: _('delete')
                    ,handler: this.remove.createDelegate(this,['namespace_remove_confirm','Workspace/PackageNamespace/Remove'])
                });
            }
        }
        return m;
    }

    ,namespaceUpdate: function(elem, vent) {
        var win = MODx.load({
            xtype: 'modx-window-namespace-update'
            ,record: this.menu.record
            ,listeners: {
                success: {
                    fn: this.refresh
                    ,scope: this
                }
            }
        });
        win.setValues(this.menu.record);
        win.show(vent.target);
    }

    ,namespaceSearch: function(tf,newValue,oldValue) {
        var s = this.getStore();
        s.baseParams.search = newValue;
        this.replaceState();
        this.getBottomToolbar().changePage(1);
    }

    ,clearFilter: function() {
        var s = this.getStore();
        var namespaceSearch = Ext.getCmp('modx-namespace-search');
        s.baseParams = {
            action: 'Workspace/PackageNamespace/GetList'
        };
        MODx.request.search = '';
        namespaceSearch.setValue('');
        this.replaceState();
        this.getBottomToolbar().changePage(1);
    }

    ,removeSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            title: _('selected_remove')
            ,text: _('namespace_remove_multiple_confirm')
            ,url: this.config.url
            ,params: {
                action: 'Workspace/PackageNamespace/RemoveMultiple'
                ,namespaces: cs
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
Ext.reg('modx-grid-namespace',MODx.grid.Namespace);
