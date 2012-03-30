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
            html: '<h2>'+_('namespaces')+'</h2>'
            ,border: false
            ,id: 'modx-namespaces-header'
            ,cls: 'modx-page-header'
        },{
            layout: 'form'
            ,items: [{
                html: '<p>'+_('namespaces_desc')+'</p>'
				,bodyCssClass: 'panel-desc'
                ,border: false
            },{
                xtype: 'modx-grid-namespace'
				,cls:'main-wrapper'
                ,preventRender: true
            }]
        }]
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
        url: MODx.config.connectors_url+'workspace/namespace.php'
        ,fields: ['id','name','path','assets_path','perm']
        ,anchor: '100%'
        ,paging: true
        ,autosave: true
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
            text: _('create_new')
            ,handler: { xtype: 'modx-window-namespace-create' ,blankValues: true }
            ,scope: this
        },'->',{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-namespace-search'
            ,emptyText: _('search_ellipsis')
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
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this}
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
            if (p.indexOf('premove') != -1) {
                m.push({
                    text: _('namespace_remove')
                    ,handler: this.remove.createDelegate(this,["namespace_remove_confirm"])
                });
            }
        }
        return m;
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
        Ext.getCmp('modx-namespace-search').reset();
    	this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,removeSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            title: _('namespace_remove_multiple')
            ,text: _('namespace_remove_multiple_confirm')
            ,url: this.config.url
            ,params: {
                action: 'removeMultiple'
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
