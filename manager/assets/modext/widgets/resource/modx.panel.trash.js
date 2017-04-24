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
        ,
        baseParams: {
            action: 'resource/trash/getlist'
        }
        ,
        fields: ['id', 'pagetitle', 'longtitle', 'published', 'deletedon', /*'deletedby', 'context_key',*/ 'cls', 'deletedbyUser', 'context_name']
        ,
        paging: true
        ,
        remoteSort: true
        ,
        sm: this.sm
        ,
        columns: [this.sm, {
            header: _('id')
            , dataIndex: 'id'
            , width: 20
            , sortable: true
        }, {
            header: _('pagetitle')
            , dataIndex: 'pagetitle'
            , width: 80
            , sortable: true
        }, {
            header: _('long_title')
            , dataIndex: 'longtitle'
            , width: 120
            , sortable: false
        }, {
            header: _('published')
            , dataIndex: 'published'
            , width: 40
            , sortable: false
            , editor: {xtype: 'combo-boolean', renderer: 'boolean'}
        }, {
            /*    header: _('context')
             , dataIndex: 'context_key'
             , width: 40
             , sortable: false
             }, {
             */    header: _('trash.context_title')
            , dataIndex: 'context_name'
            , width: 40
            , sortable: false
        }, {
            header: _('trash.deletedon_title')
            , dataIndex: 'deletedon'
            , width: 75
            , sortable: false
        }, {
            /*    header: _('trash.deletedby_title')
             , dataIndex: 'deletedby'
             , width: 40
             , sortable: false
             }, {
             */    header: _('trash.deletedbyUser_title')
            , dataIndex: 'deletedbyUser'
            , width: 40
            , sortable: false
        }]
        ,
        tbar: [{
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
                text: _('trash.selected_purge')
                , handler: this.purgeSelected
                , scope: this
            });
            m.push({
                text: _('trash.selected_restore')
                , handler: this.restoreSelected
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
            , text: _('trash.purge_confirm_message', {
                'list': this.listResources('<br/>')
            })
            , url: this.config.url
            , params: {
                action: 'resource/trash/purge'
                , id: this.menu.record.id
            }
            , listeners: {
                'success': {fn: this.refresh, scope: this}
                // TODO: refresh tree as well
            }
        });
    }
    , restoreResource: function () {
        console.log(this.menu.record.published);
        var withPublish = '';
        if (this.menu.record.published) withPublish = '_with_publish';
        MODx.msg.confirm({
            title: _('trash.restore_confirm_title')
            , text: _('trash.restore_confirm_message' + withPublish, {
                'list': this.listResources('<br/>')
            })
            , url: this.config.url
            , params: {
                action: 'resource/undelete'
                , id: this.menu.record.id
            }
            , listeners: {
                'success': {
                    fn: function (data) {
                        // TODO we need to refresh the tree as well here
                        this.refresh();
                    }, scope: this
                }
                //{fn: this.refresh, scope: this}


            }
        });
    }
    , purgeSelected: function () {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            title: _('trash.purge_confirm_title')
            , text: _('trash.purge_confirm_message', {
                'list': this.listResources('<br/>')
            })
            , url: this.config.url
            , params: {
                action: 'resource/trash/purge'
                , id: cs
            }
            , listeners: {
                'success': {
                    fn: function (r) {
                        this.getSelectionModel().clearSelections(true);
                        this.refresh();
                        // TODO: refresh tree as well
                        // TODO: refresh recycle bin icon
                    }, scope: this
                }
            }
        });
        return true;
    }
    , restoreSelected: function () {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            title: _('trash.restore_confirm_title')
            , text: _('trash.restore_confirm_message', {
                'list': this.listResources('<br/>')
            })
            , url: this.config.url
            , params: {
                //action: 'resource/trash/purge'
                 id: cs
            }
            , listeners: {
                'success': {
                    fn: function (r) {
                        //this.getSelectionModel().clearSelections(true);
                        this.refresh();
                        // TODO: refresh tree as well
                        // TODO: refresh recycle bin icon
                    }, scope: this
                }
            }
        });
        return true;
    }
    , listResources: function(separator) {
        if (separator === undefined) separator=',';

        /* creates a textual representation of the selected resources */
        /* we create a textlist of the resources here to show them again in the confirm box */
        var sels = this.getSelectionModel().getSelections();
        var text = [], t;
        sels.forEach( function( selection ) {
            t = selection.data.pagetitle + " (" + selection.data.id + ")";
            if (selection.data.published) {
                t = '<em>'+t+'</em>';
            }
            text.push(t);
        });
        return text.join(separator);
    }
});
Ext.reg('modx-grid-trash', MODx.grid.Trash);

