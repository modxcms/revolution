/**
 * Loads a grid of all the resources a user has recently edited.
 *
 * @class MODx.grid.RecentlyEditedResourcesByUser
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-user-recent-resource
 */
MODx.grid.RecentlyEditedResourcesByUser = function(config = {}) {
    const dateFormat = `${MODx.config.manager_date_format} ${MODx.config.manager_time_format}`;
    Ext.applyIf(config, {
        title: _('recent_docs'),
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Security/User/GetRecentlyEditedResources',
            user: config.user
        },
        autosave: true,
        save_action: 'Resource/UpdateFromGrid',
        pageSize: 10,
        fields: [
            'id',
            'pagetitle',
            'description',
            'editedon',
            'deleted',
            'published',
            'context_key',
            'menu',
            'link',
            'occurred'
        ],
        columns: [{
            header: _('id'),
            dataIndex: 'id',
            width: 75,
            fixed: true
        }, {
            header: _('pagetitle'),
            dataIndex: 'pagetitle',
            renderer: {
                fn: function(value, metaData, record) {
                    return this.renderLink(value, {
                        href: `?a=resource/update&id=${record.data.id}`,
                        target: '_blank'
                    });
                },
                scope: this
            }
        }, {
            header: _('editedon'),
            dataIndex: 'occurred',
            renderer: Ext.util.Format.dateRenderer(dateFormat)
        }, {
            header: _('published'),
            dataIndex: 'published',
            width: 120,
            fixed: true,
            editor: {
                xtype: 'combo-boolean',
                renderer: 'boolean'
            }
        }],
        paging: true,
        listeners: {
            afteredit: this.refresh,
            afterrender: this.onAfterRender,
            scope: this
        }
    });
    MODx.grid.RecentlyEditedResourcesByUser.superclass.constructor.call(this, config);
};
Ext.extend(MODx.grid.RecentlyEditedResourcesByUser, MODx.grid.Grid, {
    getMenu: function() {
        const menu = [];
        menu.push({
            text: _('resource_overview'),
            params: {
                a: 'resource/data',
                type: 'view'
            }
        });
        if (MODx.perm.edit_document) {
            menu.push({
                text: _('resource_edit'),
                params: {
                    a: 'resource/update',
                    type: 'edit'
                }
            });
        }
        menu.push('-');
        menu.push({
            text: _('resource_view'),
            handler: this.preview
        });

        return menu;
    },

    preview: function() {
        window.open(this.menu.record.link);
    },
    refresh: function() {
        const tree = Ext.getCmp('modx-resource-tree');
        if (tree && tree.rendered) {
            tree.refresh();
        }
    },
    // Workaround to resize the grid when in a dashboard widget
    onAfterRender: function() {
        const
            contentCmp = Ext.getCmp('modx-content'),
            grid = Ext.get('modx-grid-user-recent-resource')
        ;
        if (contentCmp && grid) {
            contentCmp.on('afterlayout', function(elem, layout) {
                const width = grid.getWidth();
                // Only resize when more than 500px (else let's use/enable the horizontal scrolling)
                if (width > 500) {
                    this.setWidth(width);
                }
            }, this);
        }
    }
});
Ext.reg('modx-grid-user-recent-resource', MODx.grid.RecentlyEditedResourcesByUser);
