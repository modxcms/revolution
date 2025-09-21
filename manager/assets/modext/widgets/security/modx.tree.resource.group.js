/**
 * Generates the Resource Group Tree in Ext
 *
 * @class MODx.tree.ResourceGroup
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-resourcegroup
 */
MODx.tree.ResourceGroup = function(config = {}) {
    Ext.applyIf(config, {
        title: _('resource_groups'),
        url: MODx.config.connector_url,
        action: 'Security/ResourceGroup/GetNodes',
        rootIconCls: 'icon-files-o',
        root_id: '0',
        root_name: _('resource_groups'),
        enableDrag: false,
        enableDrop: true,
        ddAppendOnly: true,
        useDefaultToolbar: true,
        baseParams: {
            limit: 0
        },
        tbar: ['->', {
            text: _('resource_group_create'),
            cls: 'primary-button',
            scope: this,
            handler: this.createResourceGroup
        }]
    });
    MODx.tree.ResourceGroup.superclass.constructor.call(this, config);
};
Ext.extend(MODx.tree.ResourceGroup, MODx.tree.Tree, {
    forms: {},
    windows: {},
    stores: {},

    getMenu: function() {
        const
            { activeNode } = this.cm,
            menu = []
        ;
        if (activeNode.attributes.type === 'MODX\\Revolution\\modResourceGroup') {
            menu.push({
                text: _('resource_group_create'),
                handler: this.createResourceGroup
            });
            menu.push('-');
            menu.push({
                text: _('resource_group_update'),
                handler: this.updateResourceGroup
            });
            menu.push('-');
            menu.push({
                text: _('resource_group_remove'),
                handler: this.removeResourceGroup
            });
        } else if (
            activeNode.attributes.type === 'MODX\\Revolution\\modResource'
            || activeNode.attributes.type === 'MODX\\Revolution\\modDocument'
        ) {
            menu.push({
                text: _('resource_group_access_remove'),
                handler: this.removeResource
            });
        }
        return menu;
    },

    updateResourceGroup: function(item, e) {
        const record = this.cm.activeNode.attributes.data;

        if (!this.windows.updateResourceGroup) {
            this.windows.updateResourceGroup = MODx.load({
                xtype: 'modx-window-resourcegroup-update',
                record: record,
                listeners: {
                    success: {
                        fn: this.refresh,
                        scope: this
                    }
                }
            });
        }
        this.windows.updateResourceGroup.reset();
        this.windows.updateResourceGroup.setValues(record);
        this.windows.updateResourceGroup.show(e.target);
    },

    removeResource: function(item, e) {
        const
            { activeNode } = this.cm,
            resourceId = activeNode.id.split('_')[1],
            resourceGroupId = activeNode.parentNode.id.substr(2).split('_')[1]
        ;
        MODx.msg.confirm({
            text: _('resource_group_access_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'Security/ResourceGroup/RemoveResource',
                resource: resourceId,
                resourceGroup: resourceGroupId
            },
            listeners: {
                success: {
                    fn: this.refresh,
                    scope: this
                }
            }
        });
    },

    /**
     * Removes the dragged resource from its source group (when not in copy mode)
     * @param {Number} resourceId The id of the resource being moved
     * @param {Number} previousGroupId The id of the resource group from which the dragged resource will be removed
     */
    removeResourceFromPreviousGroup: function(resourceId, previousGroupId) {
        MODx.Ajax.request({
            url: this.config.url,
            scope: this,
            params: {
                resource: resourceId,
                resourceGroup: previousGroupId,
                action: 'Security/ResourceGroup/RemoveResource'
            },
            listeners: {
                failure: {
                    fn: function(response) {
                        Ext.Msg.alert(_('error'), response.message);
                    },
                    scope: this
                }
            }
        });
    },

    removeResourceGroup: function(item, e) {
        const
            { activeNode } = this.cm.activeNode,
            resourceGroupId = activeNode.id.substr(2).split('_')[1],
            resourceGroupName = activeNode.text
        ;
        MODx.msg.confirm({
            text: _('resource_group_remove_confirm', {
                resource_group: resourceGroupName
            }),
            url: this.config.url,
            params: {
                action: 'Security/ResourceGroup/Remove',
                id: resourceGroupId
            },
            listeners: {
                failure: {
                    fn: function(response) {
                        Ext.Msg.alert(_('error'), response.message);
                    },
                    scope: this
                }
            }
        });
    },

    createResourceGroup: function(item, e) {
        if (!this.windows.create) {
            this.windows.create = MODx.load({
                xtype: 'modx-window-resourcegroup-create',
                listeners: {
                    success: {
                        fn: this.refresh,
                        scope: this
                    }
                }
            });
        }
        this.windows.create.show(e.target);
    },

    _handleDrop: function(e) {
        const node = e.dropNode;

        if (this.isDocCopy(e, node)) {
            const copy = new Ext.tree.TreeNode(
                Ext.apply({ leaf: true, allowDelete: true, expanded: true }, node.attributes)
            );
            copy.loader = undefined;
            if (e.target.attributes.options) {
                e.target = this.createDGD(e.target, copy.text);
            }
            e.dropNode = copy;
            return true;
        }
        return false;
    },

    isDocCopy: function(e, node) {
        const docId = `n_${node.attributes.id.split('_')[1]}`;

        if (e.target.findChild('id', docId) !== null) {
            return false;
        }
        if (
            node.attributes.type !== 'MODX\\Revolution\\modResource'
            && node.attributes.type !== 'MODX\\Revolution\\modDocument'
        ) {
            return false;
        }
        if (e.point !== 'append') {
            return false;
        }
        if (e.target.attributes.type !== 'MODX\\Revolution\\modResourceGroup') {
            return false;
        }
        return e.target.attributes.leaf !== true;
    },

    createDGD: function(n, text) {
        const
            cnode = this.getNodeById(n.attributes.cmpId),
            node = new Ext.tree.TreeNode({
                text: text,
                cmpId: cnode.id,
                leaf: true,
                allowDelete: true,
                allowEdit: true,
                id: this._guid('o-')
            })
        ;
        cnode.childNodes[2].appendChild(node);
        cnode.childNodes[2].expand(false, false);

        return node;
    },

    _handleDrag: function(dropEvent) {
        /*
            - - Node id formats --
            dropEvent.target.attributes.id:
                n_dg_[group-id], e.g., n_dg_2
            dropEvent.dropNode.attributes.id (the resource being moved):
                from Contexts: [context-key]_[resource-id], e.g., web_18
                between Groups: n_[resource-id]_[source-group-id], e.g., n_35_1
        */

        if (Ext.isEmpty(dropEvent.target.attributes.id) || Ext.isEmpty(dropEvent.dropNode.attributes.id)) {
            return;
        }

        const
            dropNodeId = dropEvent.dropNode.attributes.id.split('_'),
            sourceIsGroup = dropNodeId.length === 3,
            previousGroupId = sourceIsGroup ? dropNodeId[2] : false,
            resourceGroupId = dropEvent.target.attributes.id.split('_')[2],
            resourceId = dropNodeId[1]
        ;
        MODx.Ajax.request({
            url: this.config.url,
            scope: this,
            params: {
                resource: resourceId,
                resourceGroup: resourceGroupId,
                action: 'Security/ResourceGroup/UpdateResourcesIn'
            },
            listeners: {
                success: {
                    fn: function(response) {
                        // Cleanup source node when moving between groups
                        if (response.success && sourceIsGroup && !dropEvent.rawEvent.altKey) {
                            this.removeResourceFromPreviousGroup(resourceId, previousGroupId);
                        }
                        this.refresh();
                    },
                    scope: this
                },
                failure: {
                    fn: function(response) {
                        Ext.Msg.alert(_('error'), response.message);
                        this.refresh();
                    },
                    scope: this
                }
            }
        });
    }
});
Ext.reg('modx-tree-resource-group', MODx.tree.ResourceGroup);

/**
 * @class MODx.window.CreateResourceGroup
 * @extends MODx.Window
 * @param {Object} config An object of configuration resource groups
 * @xtype modx-window-resourcegroup-create
 */
MODx.window.CreateResourceGroup = function(config = {}) {
    this.ident = config.ident || `modx-create-resource-grp-${Ext.id()}`;
    Ext.applyIf(config, {
        title: _('resource_group_create'),
        id: this.ident,
        width: 600,
        stateful: false,
        url: MODx.config.connector_url,
        action: 'Security/ResourceGroup/Create',
        fields: [{
            fieldLabel: _('name'),
            name: 'name',
            id: `modx-${this.ident}-name`,
            xtype: 'textfield',
            allowBlank: false,
            blankText: _('resource_group_err_name_ns'),
            anchor: '100%'
        }, {
            xtype: 'fieldset',
            collapsible: true,
            collapsed: false,
            title: _('resource_group_automatic_access'),
            defaults: {
                labelSeparator: '',
                anchor: '100%'
            },
            items: [{
                html: `<br><p>${_('resource_group_automatic_access_desc')}</p>`,
                cls: 'desc-under'
            }, {
                xtype: 'textfield',
                name: 'access_contexts',
                fieldLabel: _('contexts'),
                description: MODx.expandHelp ? '' : _('resource_group_access_contexts'),
                id: `${this.ident}-access-contexts`,
                value: 'web'
            }, {
                xtype: 'box',
                hidden: !MODx.expandHelp,
                html: _('resource_group_access_contexts'),
                cls: 'desc-under'
            }, {
                layout: 'column',
                border: false,
                defaults: {
                    layout: 'form',
                    labelSeparator: ''
                },
                items: [{
                    columnWidth: 0.5,
                    items: [{
                        boxLabel: _('resource_group_access_admin'),
                        description: _('resource_group_access_admin_desc'),
                        name: 'access_admin',
                        id: `${this.ident}-access-admin`,
                        xtype: 'checkbox',
                        checked: false,
                        inputValue: 1
                    }, {
                        xtype: 'box',
                        hidden: !MODx.expandHelp,
                        html: _('resource_group_access_admin_desc'),
                        cls: 'desc-under'
                    }, {
                        boxLabel: _('resource_group_access_anon'),
                        description: _('resource_group_access_anon_desc'),
                        name: 'access_anon',
                        id: `${this.ident}-access-anon`,
                        xtype: 'checkbox',
                        checked: false,
                        inputValue: 1
                    }, {
                        xtype: 'box',
                        hidden: !MODx.expandHelp,
                        html: _('resource_group_access_anon_desc'),
                        cls: 'desc-under'
                    }]
                }, {
                    columnWidth: 0.5,
                    items: [{
                        boxLabel: _('resource_group_access_parallel'),
                        description: _('resource_group_access_parallel_desc'),
                        name: 'access_parallel',
                        id: `${this.ident}-access-parallel`,
                        xtype: 'checkbox',
                        checked: false,
                        inputValue: 1
                    }, {
                        xtype: 'box',
                        hidden: !MODx.expandHelp,
                        html: _('resource_group_access_parallel_desc'),
                        cls: 'desc-under'
                    }, {
                        fieldLabel: _('resource_group_access_ugs'),
                        description: _('resource_group_access_ugs_desc'),
                        name: 'access_usergroups',
                        id: `${this.ident}-access-usergroups`,
                        xtype: 'textfield',
                        value: '',
                        anchor: '100%'
                    }, {
                        xtype: 'box',
                        hidden: !MODx.expandHelp,
                        html: _('resource_group_access_ugs_desc'),
                        cls: 'desc-under'
                    }]
                }]
            }]
        }]
    });
    MODx.window.CreateResourceGroup.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.CreateResourceGroup, MODx.Window);
Ext.reg('modx-window-resourcegroup-create', MODx.window.CreateResourceGroup);

/**
 * @class MODx.window.UpdateResourceGroup
 * @extends MODx.Window
 * @param {Object} config An object of configuration resource groups
 * @xtype modx-window-resourcegroup-update
 */
MODx.window.UpdateResourceGroup = function(config = {}) {
    this.ident = config.ident || `modx-update-resource-grp-${Ext.id()}`;
    Ext.applyIf(config, {
        title: _('resource_group_update'),
        id: this.ident,
        url: MODx.config.connector_url,
        action: 'Security/ResourceGroup/Update',
        fields: [{
            name: 'id',
            xtype: 'hidden',
            id: `modx-${this.ident}-id`
        }, {
            fieldLabel: _('name'),
            name: 'name',
            id: `modx-${this.ident}-name`,
            xtype: 'textfield',
            allowBlank: false,
            blankText: _('resource_group_err_name_ns'),
            anchor: '100%'
        }]
    });
    MODx.window.UpdateResourceGroup.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.UpdateResourceGroup, MODx.Window);
Ext.reg('modx-window-resourcegroup-update', MODx.window.UpdateResourceGroup);
