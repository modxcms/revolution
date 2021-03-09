/**
 * Generates the Resource Group Tree in Ext
 *
 * @class MODx.tree.ResourceGroup
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-resourcegroup
 */
MODx.tree.ResourceGroup = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('resource_groups')
        ,url: MODx.config.connector_url
        ,action: 'Security/ResourceGroup/GetNodes'
        ,rootIconCls: 'icon-files-o'
        ,root_id: '0'
        ,root_name: _('resource_groups')
        ,enableDrag: false
        ,enableDrop: true
        ,ddAppendOnly: true
        ,useDefaultToolbar: true
        ,baseParams: {
            limit: 0
        }
        ,tbar: ['->', {
            text: _('resource_group_create')
            ,cls: 'primary-button'
            ,scope: this
            ,handler: this.createResourceGroup
        }]
    });
    MODx.tree.ResourceGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.tree.ResourceGroup,MODx.tree.Tree,{
    forms: {}
    ,windows: {}
    ,stores: {}

    ,getMenu: function() {
        var n = this.cm.activeNode;
        var m = [];
        if (n.attributes.type == 'MODX\\Revolution\\modResourceGroup') {
            m.push({
                text: _('resource_group_create')
                ,handler: this.createResourceGroup
            });
            m.push('-');
            m.push({
                text: _('resource_group_update')
                ,handler: this.updateResourceGroup
            });
            m.push('-');
            m.push({
                text: _('resource_group_remove')
                ,handler: this.removeResourceGroup
            });
        } else if (n.attributes.type == 'MODX\\Revolution\\modResource' || n.attributes.type == 'MODX\\Revolution\\modDocument') {
            m.push({
                text: _('resource_group_access_remove')
                ,handler: this.removeResource
            });
        }
        return m;
    }

    ,updateResourceGroup: function(itm,e) {
        var r = this.cm.activeNode.attributes.data;

        if (!this.windows.updateResourceGroup) {
            this.windows.updateResourceGroup = MODx.load({
                xtype: 'modx-window-resourcegroup-update'
                ,record: r
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        }
        this.windows.updateResourceGroup.reset();
        this.windows.updateResourceGroup.setValues(r);
        this.windows.updateResourceGroup.show(e.target);

    }

    ,removeResource: function(item,e) {
        var n = this.cm.activeNode;
        var resourceId = n.id.split('_'); resourceId = resourceId[1];
        var resourceGroupId = n.parentNode.id.substr(2).split('_'); resourceGroupId = resourceGroupId[1];

        MODx.msg.confirm({
            text: _('resource_group_access_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'Security/ResourceGroup/RemoveResource'
                ,resource: resourceId
                ,resourceGroup: resourceGroupId
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,removeResourceGroup: function(item,e) {
        var n = this.cm.activeNode;
        var id = n.id.substr(2).split('_'); id = id[1];
        var resource_group = n.text;

        MODx.msg.confirm({
            text: _('resource_group_remove_confirm',{
                resource_group: resource_group
            })
            ,url: this.config.url
            ,params: {
                action: 'Security/ResourceGroup/Remove'
                ,id: id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,createResourceGroup: function(itm,e) {
        if (!this.windows.create) {
            this.windows.create = MODx.load({
                xtype: 'modx-window-resourcegroup-create'
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        }
        this.windows.create.show(e.target);
    }

    ,_handleDrop: function(e){
        var n = e.dropNode;

        if(this.isDocCopy(e,n)) {
            var copy = new Ext.tree.TreeNode(
                Ext.apply({leaf: true,allowDelete:true,expanded:true}, n.attributes)
            );
            copy.loader = undefined;
            if(e.target.attributes.options){
                e.target = this.createDGD(e.target, copy.text);
            }
            e.dropNode = copy;
            return true;
        }
        return false;
    }

    ,isDocCopy: function(e, n) {
        var a = e.target.attributes;
        var docid = n.attributes.id.split('_'); docid = 'n_'+docid[1];

        if (e.target.findChild('id',docid) !== null) { return false; }
        if (n.attributes.type != 'MODX\\Revolution\\modResource' && n.attributes.type != 'MODX\\Revolution\\modDocument') { return false; }
        if (e.point != 'append') { return false; }
        if (a.type != 'MODX\\Revolution\\modResourceGroup') { return false; }
        return a.leaf !== true;

    }

    ,createDGD: function(n, text){
        var cnode = this.getNodeById(n.attributes.cmpId);

        var node = new Ext.tree.TreeNode({
            text: text
            ,cmpId:cnode.id
            ,leaf: true
            ,allowDelete:true
            ,allowEdit:true
            ,id:this._guid('o-')
        });
        cnode.childNodes[2].appendChild(node);
        cnode.childNodes[2].expand(false, false);

        return node;
    }

    ,_handleDrag: function(dropEvent) {
        Ext.Msg.show({
            title: _('please_wait')
            ,msg: _('saving')
            ,width: 240
            ,progress:true
            ,closable:false
        });

        MODx.util.Progress.reset();
        for(var i = 1; i < 20; i++) {
            setTimeout('MODx.util.Progress.time('+i+','+MODx.util.Progress.id+')',i*1000);
        }

        MODx.Ajax.request({
            url: this.config.url
            ,scope: this
            ,params: {
                resource: dropEvent.dropNode.attributes.id
                ,resourceGroup: dropEvent.target.attributes.id
                ,action: 'Security/ResourceGroup/UpdateResourcesIn'
            }
            ,listeners: {
                'success': {fn: function(r,o) {
                    MODx.util.Progress.reset();
                    Ext.Msg.hide();
                    if (!r.success) {
                        Ext.Msg.alert(_('error'),r.message);
                        return false;
                    }
                    this.refresh();
                    return true;
                },scope:this}
            }
        });
    }
});
Ext.reg('modx-tree-resource-group',MODx.tree.ResourceGroup);

/**
 * @class MODx.window.CreateResourceGroup
 * @extends MODx.Window
 * @param {Object} config An object of configuration resource groups
 * @xtype modx-window-resourcegroup-create
 */
MODx.window.CreateResourceGroup = function(config) {
    config = config || {};
    this.ident = config.ident || 'modx-crgrp'+Ext.id();
    Ext.applyIf(config,{
        title: _('resource_group_create')
        ,id: this.ident
        ,width: 600
        ,stateful: false
        ,url: MODx.config.connector_url
        ,action: 'Security/ResourceGroup/Create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,xtype: 'textfield'
            ,anchor: '100%'
        },{
            xtype: 'fieldset'
            ,collapsible: true
            ,collapsed: false
            ,title: _('resource_group_automatic_access')
            ,items: [{
                html: '<br /><p>'+_('resource_group_automatic_access_desc')+'</p>'
                ,cls: 'desc-under'
            },{
                xtype: 'textfield'
                ,name: 'access_contexts'
                ,fieldLabel: _('contexts')
                ,description: MODx.expandHelp ? '' : _('resource_group_access_contexts')
                ,id: this.ident+'-access-contexts'
                ,anchor: '100%'
                ,value: 'web'
            },{
                xtype: MODx.expandHelp ? 'label' : 'hidden'
                ,forId: this.ident+'-access-contexts'
                ,html: _('resource_group_access_contexts')
                ,cls: 'desc-under'
            },{
                layout: 'column'
                ,border: false
                ,defaults: {
                    layout: 'form'
                    ,labelAlign: 'top'
                    ,anchor: '100%'
                    ,border: false
                }
                ,items: [{
                    columnWidth: .5
                    ,items: [{
                        boxLabel: _('resource_group_access_admin')
                        ,description: _('resource_group_access_admin_desc')
                        ,name: 'access_admin'
                        ,id: this.ident+'-access-admin'
                        ,xtype: 'checkbox'
                        ,checked: false
                        ,inputValue: 1
                        ,anchor: '100%'
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: this.ident+'-access-admin'
                        ,html: _('resource_group_access_admin_desc')
                        ,cls: 'desc-under'
                    },{
                        boxLabel: _('resource_group_access_anon')
                        ,description: _('resource_group_access_anon_desc')
                        ,name: 'access_anon'
                        ,id: this.ident+'-access-anon'
                        ,xtype: 'checkbox'
                        ,checked: false
                        ,inputValue: 1
                        ,anchor: '100%'
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: this.ident+'-access-anon'
                        ,html: _('resource_group_access_anon_desc')
                        ,cls: 'desc-under'
                    }]
                },{
                    columnWidth: .5
                    ,items: [{
                        boxLabel: _('resource_group_access_parallel')
                        ,description: _('resource_group_access_parallel_desc')
                        ,name: 'access_parallel'
                        ,id: this.ident+'-access-parallel'
                        ,xtype: 'checkbox'
                        ,checked: false
                        ,inputValue: 1
                        ,anchor: '100%'
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: this.ident+'-access-parallel'
                        ,html: _('resource_group_access_parallel_desc')
                        ,cls: 'desc-under'
                    },{
                        fieldLabel: _('resource_group_access_ugs')
                        ,description: _('resource_group_access_ugs_desc')
                        ,name: 'access_usergroups'
                        ,id: this.ident+'-access-usergroups'
                        ,xtype: 'textfield'
                        ,value: ''
                        ,anchor: '100%'
                    },{
                        xtype: MODx.expandHelp ? 'label' : 'hidden'
                        ,forId: this.ident+'-access-usergroups'
                        ,html: _('resource_group_access_ugs_desc')
                        ,cls: 'desc-under'
                    }]
                }]
            }]
        }]
    });
    MODx.window.CreateResourceGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateResourceGroup,MODx.Window);
Ext.reg('modx-window-resourcegroup-create',MODx.window.CreateResourceGroup);

/**
 * @class MODx.window.UpdateResourceGroup
 * @extends MODx.Window
 * @param {Object} config An object of configuration resource groups
 * @xtype modx-window-resourcegroup-update
 */
MODx.window.UpdateResourceGroup = function(config) {
    config = config || {};
    this.ident = config.ident || 'urgrp'+Ext.id();
    Ext.applyIf(config,{
        title: _('resource_group_update')
        ,id: this.ident
        ,url: MODx.config.connector_url
        ,action: 'Security/ResourceGroup/Update'
        ,fields: [{
            name: 'id'
            ,xtype: 'hidden'
            ,id: 'modx-'+this.ident+'-id'
        },{
            fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,xtype: 'textfield'
            ,anchor: '100%'
        }]
    });
    MODx.window.UpdateResourceGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateResourceGroup,MODx.Window);
Ext.reg('modx-window-resourcegroup-update',MODx.window.UpdateResourceGroup);
