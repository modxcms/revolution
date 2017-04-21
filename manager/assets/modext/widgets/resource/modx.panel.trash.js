MODx.panel.Trash = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'modx-panel-trash'
        , cls: 'container'
        , bodyStyle: ''
        , defaults: {collapsible: false, autoHeight: true}
        , items: [{
            html: _('trash.page_title')
            , id: 'modx-trash-header'
            , xtype: 'modx-header'
        }, MODx.getPageStructure([{
            layout: 'form'
            , title: _('trash.tab_title')
            , items: [{
                html: '<p>' + _('trash.intro_msg') + '</p>'
                , xtype: 'modx-description'
            }, {
                xtype: 'modx-grid-trash'
                , cls: 'main-wrapper'
                , preventRender: true
            }]
        }], {
            stateful: true
            , stateId: 'modx-trash-tabpanel'
            , stateEvents: ['tabchange']
            , getState: function () {
                return {activeTab: this.items.indexOf(this.getActiveTab())};
            }
        })]
    });
    MODx.panel.Trash.superclass.constructor.call(this, config);
};
Ext.extend(MODx.panel.Trash, MODx.FormPanel);
Ext.reg('modx-panel-trash', MODx.panel.Trash);

MODx.grid.Trash = function (config) {
    config = config || {};

    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config, {
        url: MODx.config.connector_url
        , baseParams: {
            action: 'resource/trash/getlist'
        }
        , fields: ['id', 'pagetitle', 'longtitle', 'published', 'deletedon', 'deletedby', 'cls']
        , paging: true
        , remoteSort: true
        , sm: this.sm
        , columns: [this.sm, {
            header: _('id')
            , dataIndex: 'id'
            , width: 20
            , sortable: true
        }, {
            header: _('pagetitle')
            , dataIndex: 'pagetitle'
            , width: 80
            , sortable: true
            //,editor: { xtype: 'textfield' ,allowBlank: false }
        }, {
            header: _('long_title')
            , dataIndex: 'longtitle'
            , width: 150
            , sortable: false
            //,editor: { xtype: 'textarea' }
        },{
            header: _('published')
            , dataIndex: 'published'
            , width: 40
            , sortable: false
            //,editor: { xtype: 'textarea' }
        }, {
            header: _('trash.deletedon_title')
            , dataIndex: 'deletedon'
            , width: 50
            , sortable: false
            //,editor: { xtype: 'textarea' }
        }, {
            header: _('trash.deletedby_title')
            , dataIndex: 'deletedby'
            , width: 40
            , sortable: false
            //,editor: { xtype: 'textarea' }
        }]
        , tbar: [{
            text: _('bulk_actions')
            , menu: [{
                text: _('trash.selected_purge')
                , handler: this.purgeSelected
                , scope: this
            }, {
                text: _('trash.selected_restore')
                , handler: this.restoreSelected
                , scope: this
            }]
        }, '->', {
            xtype: 'textfield'
            , name: 'search'
            , id: 'modx-source-search'
            , cls: 'x-form-filter'
            , emptyText: _('search_ellipsis')
            , listeners: {
                'change': {fn: this.search, scope: this}
                , 'render': {
                    fn: function (cmp) {
                        new Ext.KeyMap(cmp.getEl(), {
                            key: Ext.EventObject.ENTER
                            , fn: this.blur
                            , scope: cmp
                        });
                    }, scope: this
                }
            }
        }, {
            xtype: 'button'
            , text: _('filter_clear')
            , id: 'modx-filter-clear'
            , cls: 'x-form-filter-clear'
            , listeners: {
                'click': {fn: this.clearFilter, scope: this}
            }
        }]
    });
    MODx.grid.Trash.superclass.constructor.call(this, config);
};

Ext.extend(MODx.grid.Trash, MODx.grid.Grid, {
    getMenu: function () {
        var r = this.getSelectionModel().getSelected();
        var p = r.data.cls;

        var m = [];
        if (this.getSelectionModel().getCount() > 1) {
            m.push({
                text: _('selected_rpurge')
                , handler: this.purgeSelected
                , scope: this
            });
        } else {
            if (p.indexOf('purge') !== -1) {
                m.push({
                    text: _('trash.purge')
                    , handler: this.purgeResource
                });
            }
            if (p.indexOf('restore') !== -1) {
                m.push({
                    text: _('trash.restore')
                    , handler: this.restoreResource
                });
            }
        }
        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    }
    , purgeResource: function () {
        MODx.msg.confirm({
            title: _('trash.purge_confirm_title')
            , text: _('trash.purge_confirm_message')
            , url: this.config.url
            , params: {
                action: 'trash/purge'
                , id: this.menu.record.id
            }
            , listeners: {
                'success': {fn: this.refresh, scope: this}
            }
        });
    }
    , restoreResource: function () {
        MODx.msg.confirm({
            title: _('trash.restore_confirm_title')
            , text: _('trash.restore_confirm_message')
            , url: this.config.url
            , params: {
                action: 'trash/restore'
                , id: this.menu.record.id
            }
            , listeners: {
                'success': {fn: this.refresh, scope: this}
            }
        });
    }

});
Ext.reg('modx-grid-trash', MODx.grid.Trash);

