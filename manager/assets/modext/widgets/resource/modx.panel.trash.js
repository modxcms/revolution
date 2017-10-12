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
                , id: 'modx-trash-resourcelist'
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
        ,baseParams: {
            action: 'resource/trash/getlist'
        }
        ,fields: [
            'id',
            'context_key',
            'parentPath',
            'pagetitle',
            'longtitle',
            'published',
            'deletedon',
            'cls',
            'deletedbyUser',
            'context_name']
        ,paging: true

        // TODO if we autosave a changed published state, we should also refresh the tree, but how to do?
        ,autosave: true
        ,save_action: 'resource/updatefromgrid'

        ,remoteSort: true
        ,sm: this.sm
        ,columns: [this.sm, {
            header: _('id')
            , dataIndex: 'id'
            , width: 20
            , sortable: true
        }, {
            header: _('trash.context_title')
            , dataIndex: 'context_name'
            , width: 60
            , sortable: false
        }, {
            header: _('pagetitle')
            , dataIndex: 'pagetitle'
            , width: 80
            , sortable: true
            , tooltip: "TODO: longtitle here"
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
        }, {
            xtype: 'button'
            , text: _('trash.purge_all')
            , id: 'modx-purge-all'
            , cls: 'x-form-purge-all red'
            , listeners: {
                'click': {fn: this.purgeAll, scope: this}
            }
        }, {
            xtype: 'button'
            , text: _('trash.restore_all')
            , id: 'modx-restore-all'
            , cls: 'x-form-restore-all green'
            , listeners: {
                'click': {fn: this.restoreAll, scope: this}
            }
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
                'success': {
                    fn: function () {
                        this.refreshEverything();
                    }, scope: this
                }
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
                    fn: function () {
                        this.refreshEverything();
                    }, scope: this
                }
            }
        });
    }
    , purgeSelected: function () {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            title: _('trash.purge_confirm_title')
            , text: _('trash.purge_confirm_message', {
                'list': this.listResources('')
            })
            , url: this.config.url
            , params: {
                action: 'resource/trash/purge'
                , ids: cs
            }
            , listeners: {
                'success': {
                    fn: function () {
                        this.getSelectionModel().clearSelections(true);
                        this.refreshEverything();
                    }, scope: this
                }
            }
        });
        return true;
    }

    , purgeAll: function () {
        MODx.msg.confirm({
            title: _('trash.purge_confirm_title')
            , text: _('trash.purgeall_confirm_message', {
                'count': this.listResources('')
            })
            , url: this.config.url //MODx.config.connector_url
            , params: {
                //action: 'resource/emptyRecycleBin'
                action: 'resource/trash/purge'
                , ids: -1  // this causes the processor to delete everything you have access to
            }
            , listeners: {
                'success': {
                    fn: function (data) {
                        MODx.msg.status({
                            title: _('success')
                            , message: data.message
                        });
                        if (data.object.count_success > 0) {
                            this.refreshEverything();       // no need to refresh if nothing was purged
                        }
                    }, scope: this
                },
                'error': {
                    fn: function (data) {
                        MODx.msg.status({
                            title: _('error')
                            , message: data.message
                        });
                    }, scope: this
                }
            }
        })
    }

    , refreshEverything: function() {
        this.refresh();
        var t = Ext.getCmp('modx-resource-tree');
        t.refresh();
        this.refreshRecycleBinButton();
    }

    , refreshRecycleBinButton: function() {
        var t = Ext.getCmp('modx-resource-tree');
        var trashButton = t.getTopToolbar().findById('emptifier');
        console.info(trashButton);
        trashButton.disable();
        trashButton.setTooltip(_('empty_recycle_bin') + ' (0)');
        this.fireEvent('emptyTrash');
    }

    , restoreSelected: function () {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            title: _('trash.restore_confirm_title')
            , text: _('trash.restore_confirm_message', {
                'list': this.listResources('')
            })
            , url: this.config.url
            , params: {
                action: 'resource/trash/restore',
                ids: cs
            }
            , listeners: {
                'success': {
                    fn: function (data) {
                        this.refreshEverything();
                    }, scope: this
                }
            }
        });
        return true;
    }
    , listResources: function (separator) {
        if (separator === undefined) separator = ',';

        // creates a textual representation of the selected resources
        // we create a textlist of the resources here to show them again in the confirmation box
        var selections = this.getSelectionModel().getSelections();
        var text = [], t;
        selections.forEach(function (selection) {
            //t = selection.data.pagetitle + " (" + selection.data.id + ")";
            t = selection.data.parentPath + "<strong>" + selection.data.pagetitle + " (" + selection.data.id + ")" + "</strong>";
            if (selection.data.published) {
                t = '<em>' + t + '</em>';
            }
            t = "<div style='white-space:nowrap'>" + t + "</div>";
            text.push(t);
        });
        return text.join(separator);
    }
});
Ext.reg('modx-grid-trash', MODx.grid.Trash);

