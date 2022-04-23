/**
 * Loads a grid of modAccessContexts.
 *
 * @class MODx.grid.AccessContext
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-access-context
 */
MODx.grid.MediaSourceAccess = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-grid-source-access'
        ,fields: ['id','target','target_name','principal_class','principal','principal_name','authority','authority_name','policy','policy_name','context_key']
        ,type: 'MODX\\Revolution\\Sources\\modAccessMediaSource'
        ,paging: true
        ,columns: [{
            header: _('user_group')
            ,dataIndex: 'principal_name'
            ,width: 120
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=security/usergroup/update&id=' + record.data.principal
                    ,target: '_blank'
                });
            }, scope: this }
        },{
            header: _('minimum_role')
            ,dataIndex: 'authority_name'
            ,width: 50
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=security/permission'
                    ,target: '_blank'
                });
            }, scope: this }
        },{
            header: _('policy')
            ,dataIndex: 'policy_name'
            ,width: 175
            ,renderer: { fn: function(v,md,record) {
                return this.renderLink(v, {
                    href: '?a=security/access/policy/update&id=' + record.data.policy
                    ,target: '_blank'
                });
            }, scope: this }
        }]
        ,tbar: [{
            text: _('source_access_add')
            ,cls: 'primary-button'
            ,scope: this
            ,handler: this.createAcl
        }]
    });
    MODx.grid.MediaSourceAccess.superclass.constructor.call(this,config);
    this.propRecord = Ext.data.Record.create(config.fields);
};
Ext.extend(MODx.grid.MediaSourceAccess,MODx.grid.LocalGrid,{
    combos: {}
    ,windows: {}

    ,getMenu: function() {
        var menu = [];
        if (this.menu.record.id) {
            menu.push({
                text: _('source_access_update')
                ,handler: this.editAcl
            });
        }
        menu.push({
            text: _('source_access_remove')
            ,handler: this.remove.createDelegate(this,[{
                title: _('source_access_remove')
                ,text: _('source_access_remove_confirm')
            }])
        });

        return menu;
    }

    ,createAcl: function(itm,e) {
        var r = {
            target: this.config.source
            ,principal_class: 'MODX\\Revolution\\modUserGroup'
        };
        if (!this.windows.access_add) {
            this.windows.access_add = MODx.load({
                xtype: 'modx-window-source-access-create'
                ,record: r
                ,listeners: {
                    'success': {fn:function(vs) {
                        var rec = new this.propRecord(vs);
                        this.getStore().add(rec);
                    },scope:this}
                }
            });
        }
        this.windows.access_add.fp.getForm().reset();
        this.windows.access_add.setValues(r);
        this.windows.access_add.show(e.target);
    }

    ,editAcl: function(itm,e) {
        var r = this.menu.record;
        Ext.applyIf(r,{
            source: r.target
            ,user_group: r.principal
        });

        if (!this.windows.update_acl) {
            this.windows.update_acl = MODx.load({
                xtype: 'modx-window-source-access-update'
                ,acl: r.id
                ,record: r
                ,listeners: {
                    'success': {fn:function(vs) {
                        var s = this.getStore();
                        var rec;
                        try {
                            rec = s.getAt(s.find('id',vs.id));
                        } catch(e) {}
                        if (rec) {
                            for (var k in vs) {
                                rec.set(k,vs[k]);
                            }
                            rec.commit();
                        }
                    },scope:this}
                }
            });
        }
        this.windows.update_acl.setValues(r);
        this.windows.update_acl.show(e.target);
    }

    ,removeAcl: function(itm,e) {
        MODx.msg.confirm({
            title: _('source_access_remove')
            ,text: _('source_access_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'Security/Access/RemoveAcl'
                ,id: this.menu.record.id
                ,type: this.config.type || 'MODX\\Revolution\\Sources\\modAccessMediaSource'
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }
});
Ext.reg('modx-grid-source-access',MODx.grid.MediaSourceAccess);

/**
 * @class MODx.window.CreateSourceAccess
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-source-access-create
 */
MODx.window.CreateSourceAccess = function(config) {
    config = config || {};
    var r = config.record;

    Ext.applyIf(config,{
        title: _('source_access_add')
        ,type: 'MODX\\Revolution\\Sources\\modAccessMediaSource'
        ,acl: 0
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,value: r.id || ''
        },{
            xtype: 'hidden'
            ,name: 'target'
            ,value: config.target || 0
        },{
            xtype: 'hidden'
            ,name: 'principal_class'
            ,value: 'MODX\\Revolution\\modUserGroup'
        },{
            xtype: 'hidden'
            ,name: 'context_key'
            ,hiddenName: 'context_key'
            ,value: 'mgr'
        },{
            xtype: 'modx-combo-usergroup'
            ,fieldLabel: _('user_group')
            ,name: 'principal'
            ,hiddenName: 'principal'
            ,value: r.principal || ''
            ,baseParams: {
                action: 'Security/Group/GetList'
                ,combo: true
            }
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-authority'
            ,fieldLabel: _('minimum_role')
            ,name: 'authority'
            ,hiddenName: 'authority'
            ,value: r.authority || 9999
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-policy'
            ,fieldLabel: _('policy')
            ,name: 'policy'
            ,hiddenName: 'policy'
            ,value: r.policy || ''
            ,baseParams: {
                action: 'Security/Access/Policy/GetList'
                ,group: 'MediaSource'
            }
            ,anchor: '100%'
        }]
    });
    MODx.window.CreateSourceAccess.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateSourceAccess,MODx.Window,{
    submit: function() {
        var f = this.fp.getForm();
        var prf = f.findField('principal');
        var pof = f.findField('policy');
        var auf = f.findField('authority');
        var r = f.getValues();

        if (prf) { r.principal_name = prf.getRawValue(); }
        if (pof) { r.policy_name = pof.getRawValue(); }
        if (auf) { r.authority_name = auf.getRawValue(); }

        if (this.fp.getForm().isValid()) {
            if (this.fireEvent('success',r)) {
                this.fp.getForm().reset();
                this.hide();
                return true;
            }
        }

        return true;
    }
});
Ext.reg('modx-window-source-access-create',MODx.window.CreateSourceAccess);

/**
 * @class MODx.window.UpdateSourceAccess
 * @extends MODx.window.CreateSourceAccess
 * @param {Object} config An object of options.
 * @xtype modx-window-source-access-update
 */
MODx.window.UpdateSourceAccess = function(config) {
    config = config || {};
    var r = config.record;

    Ext.applyIf(config,{
        title: _('source_access_update')
    });
    MODx.window.UpdateSourceAccess.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateSourceAccess,MODx.window.CreateSourceAccess,{});
Ext.reg('modx-window-source-access-update',MODx.window.UpdateSourceAccess);
