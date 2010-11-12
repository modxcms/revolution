MODx.grid.FCSet = function(config) {
    config = config || {};
    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config,{
        id: 'modx-grid-fc-set'
        ,url: MODx.config.connectors_url+'security/forms/set.php'
        ,fields: ['id','profile','action','controller','active','rules','perm']
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
            header: _('action')
            ,dataIndex: 'controller'
            ,width: 150
            ,editable: true
            ,sortable: true
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
            ,scope: this
            ,handler: this.createSet
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
        },{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-fcs-search'
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
    MODx.grid.FCSet.superclass.constructor.call(this,config);
    this.on('render',function() { this.getStore().reload(); },this);
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
                },{
                    text: _('duplicate')
                    ,handler: this.duplicateSet
                },'-');
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
                    ,handler: this.confirm.createDelegate(this,['remove','set_remove_confirm'])
                });
            }
        }

        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    }

    ,createSet: function() {
        location.href = '?a='+MODx.action['security/forms/set/create'];
    }

    ,updateSet: function(btn,e) {
        var r = this.menu.record;
        location.href = '?a='+MODx.action['security/forms/set/update']+'&id='+r.id;
    }
    ,duplicateSet: function(btn,e) {
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

    ,activateSet: function(btn,e) {
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
                ,rules: cs
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
                ,rules: cs
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
                action: 'removeMultiple'
                ,rules: cs
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