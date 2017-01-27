MODx.grid.SettingsGrid = function(config) {
    config = config || {};
    this.exp = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<p class="desc">{description_trans}</p>'
        )
    });

    if (!config.tbar) {
        config.tbar = [{
            text: _('setting_create')
            ,scope: this
            ,cls:'primary-button'
            ,handler: {
                xtype: 'modx-window-setting-create'
                ,url: config.url || MODx.config.connector_url
                ,blankValues: true
            }
        }];
    }
    config.tbar.push('->',{
        xtype: 'modx-combo-namespace'
        ,name: 'namespace'
        ,id: 'modx-filter-namespace'
        ,emptyText: _('namespace_filter')
        ,preselectValue: MODx.request['ns'] ? MODx.request['ns'] : 'core'
        ,allowBlank: false
        ,editable: true
        ,typeAhead: true
        ,forceSelection: true
        ,queryParam: 'search'
        ,width: 150
        ,listeners: {
            'select': {fn: this.filterByNamespace, scope:this}
        }
    },{
        xtype: 'modx-combo-area'
        ,name: 'area'
        ,id: 'modx-filter-area'
        ,emptyText: _('area_filter')
        ,value: MODx.request['area']
        ,baseParams: {
            action: 'system/settings/getAreas'
            ,namespace: MODx.request['ns'] ? MODx.request['ns'] : 'core'
        }
        ,width: 250
        ,allowBlank: true
        ,editable: true
        ,typeAhead: true
        ,forceSelection: true
        ,listeners: {
            'select': {fn: this.filterByArea, scope:this}
        }
    },{
        xtype: 'textfield'
        ,name: 'filter_key'
        ,id: 'modx-filter-key'
        ,cls: 'x-form-filter'
        ,emptyText: _('search_by_key')
        ,listeners: {
            'change': {fn: this.filterByKey, scope: this}
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
        ,cls: 'x-form-filter-clear'
        ,text: _('filter_clear')
        ,listeners: {
            'click': {fn: this.clearFilter, scope: this}
        }
    });

    this.cm = new Ext.grid.ColumnModel({
        columns: [this.exp,{
            header: _('name')
            ,dataIndex: 'name_trans'
            ,sortable: true
            ,editable: false
            ,width: 175
        },{
            header: _('key')
            ,dataIndex: 'key'
            ,sortable: true
            ,editable: false
            ,width: 150
        },{
            header: _('value')
            ,dataIndex: 'value'
            ,sortable: true
            ,editable: true
            ,renderer: this.renderDynField.createDelegate(this,[this],true)
            ,width: 260
        },{
            header: _('last_modified')
            ,dataIndex: 'editedon'
            ,sortable: true
            ,editable: false
            ,renderer: this.renderLastModDate.createDelegate(this,[this],true)
            ,width: 100
        },{
            header: _('area')
            ,dataIndex: 'area_text'
            ,sortable: true
            ,hidden: true
            ,editable: false
        }]
        /* Editors are pushed here. I think that they should be in general grid
         * definitions (modx.grid.js) and activated via a config property (loadEditor: true) */
        ,getCellEditor: function(colIndex, rowIndex) {
            var field = this.getDataIndex(colIndex);
            if (field == 'value') {
                var rec = config.store.getAt(rowIndex);
                var xt = {xtype: 'textfield'};
                if (rec) {
                    xt.xtype = rec.get('xtype');
                    if (xt == 'text-password') {
                        xt.xtype = 'textfield';
                        xt.inputType = 'password';
                    }
                }
                var o = MODx.load(xt);
                return new Ext.grid.GridEditor(o);
            }
            return Ext.grid.ColumnModel.prototype.getCellEditor.call(this, colIndex, rowIndex);
        }
    });

    Ext.applyIf(config, {
         cm: this.cm
        ,fields: ['key','name','value','description','xtype','namespace','area','area_text','editedon','oldkey','menu','name_trans','description_trans']
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/settings/getList'
            ,namespace: MODx.request['ns'] ? MODx.request['ns'] : 'core'
            ,area: MODx.request['area']
        }
        ,clicksToEdit: 2
        ,grouping: true
        ,groupBy: 'area_text'
        ,singleText: _('setting')
        ,pluralText: _('settings')
        ,sortBy: 'key'
        ,plugins: this.exp
        ,primaryKey: 'key'
        ,autosave: true
        ,save_action: 'system/settings/updatefromgrid'
        ,pageSize: parseInt(MODx.config.default_per_page) > 30 ? parseInt(MODx.config.default_per_page) : 30
        ,paging: true
        ,collapseFirst: false
        ,tools: [{
            id: 'plus'
            ,qtip: _('expand_all')
            ,handler: this.expandAll
            ,scope: this
        },{
            id: 'minus'
            ,hidden: true
            ,qtip: _('collapse_all')
            ,handler: this.collapseAll
            ,scope: this
        }]
    });

    this.view = new Ext.grid.GroupingView({
        emptyText: config.emptyText || _('ext_emptymsg')
        ,forceFit: true
        ,autoFill: true
        ,showPreview: true
        ,enableRowBody: true
        ,scrollOffset: 0
    });
    MODx.grid.SettingsGrid.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.SettingsGrid,MODx.grid.Grid,{
    _addEnterKeyHandler: function() {
        this.getEl().addKeyListener(Ext.EventObject.ENTER,function() {
            this.fireEvent('change');
        },this);
    }

    ,_showMenu: function(g,ri,e) {
        e.stopEvent();
        e.preventDefault();
        this.menu.record = this.getStore().getAt(ri).data;
        if (!this.getSelectionModel().isSelected(ri)) {
            this.getSelectionModel().selectRow(ri);
        }
        this.menu.removeAll();

        var m = [];
        if (this.menu.record.menu) {
            m = this.menu.record.menu;
        } else {
            m.push({
                text: _('setting_update')
                ,handler: this.updateSetting
            },'-',{
                text: _('setting_remove')
                ,handler: this.removeSetting
            });
        }
        if (m.length > 0) {
            this.addContextMenuItem(m);
            this.menu.showAt(e.xy);
        }
    }

    ,removeSetting: function() {
        return this.remove('setting_remove_confirm', 'system/settings/remove');
    }

    ,updateSetting: function(btn,e) {
        var r = this.menu.record;
        r.fk = Ext.isDefined(this.config.fk) ? this.config.fk : 0;
        var uss = MODx.load({
            xtype: 'modx-window-setting-update'
            ,record: r
            ,grid: this
            ,listeners: {
                'success': {fn:function(r) {
                    this.refresh();
                },scope:this}
            }
        });
        uss.reset();
        uss.setValues(r);
        uss.show(e.target);
    }

    ,clearFilter: function() {
        var ns = MODx.request['ns'] ? MODx.request['ns'] : Ext.getCmp('modx-filter-namespace').getValue();
        var area = MODx.request['area'] ? MODx.request['area'] : '';

        this.getStore().baseParams = this.initialConfig.baseParams;

        var acb = Ext.getCmp('modx-filter-area');
        if (acb) {
            acb.store.baseParams['namespace'] = ns;
            acb.store.load();
            acb.reset();
        }

        Ext.getCmp('modx-filter-namespace').setValue(ns);
        Ext.getCmp('modx-filter-key').reset();

        this.getStore().baseParams.namespace = ns;
        this.getStore().baseParams.area = area;
        this.getStore().baseParams.key = '';

    	this.getBottomToolbar().changePage(1);
       // this.refresh();
    }
    ,filterByKey: function(tf,newValue,oldValue) {
        this.getStore().baseParams.key = newValue;
        this.getStore().baseParams.namespace = '';
        this.getBottomToolbar().changePage(1);
        //this.refresh();
        return true;
    }

    ,filterByNamespace: function(cb,rec,ri) {
        this.getStore().baseParams['namespace'] = rec.data['name'];
        this.getStore().baseParams['area'] = '';
        this.getBottomToolbar().changePage(1);
        //this.refresh();

        var acb = Ext.getCmp('modx-filter-area');
        if (acb) {
            var s = acb.store;
            s.baseParams['namespace'] = rec.data.name;
            s.removeAll();
            s.load();
            acb.setValue('');
        }
    }

    ,filterByArea: function(cb,rec,ri) {
        this.getStore().baseParams['area'] = rec.data['v'];
        this.getBottomToolbar().changePage(1);
       // this.refresh();
    }

    ,renderDynField: function(v,md,rec,ri,ci,s,g) {
        var r = s.getAt(ri).data;
        v = Ext.util.Format.htmlEncode(v);
        var f;
        if (r.xtype == 'combo-boolean' || r.xtype == 'modx-combo-boolean') {
            f = MODx.grid.Grid.prototype.rendYesNo;
            return f(v,md,rec,ri,ci,s,g);
        } else if (r.xtype === 'datefield') {
            f = Ext.util.Format.dateRenderer(MODx.config.manager_date_format);
            return f(v,md,rec,ri,ci,s,g);
        } else if (r.xtype === 'text-password' || r.xtype == 'modx-text-password') {
            f = MODx.grid.Grid.prototype.rendPassword;
            return f(v,md,rec,ri,ci,s,g);
        } else if (r.xtype.substr(0,5) == 'combo' || r.xtype.substr(0,10) == 'modx-combo') {
            var cm = g.getColumnModel();
            var ed = cm.getCellEditor(ci,ri);
            if (!ed) {
                var o = Ext.ComponentMgr.create({xtype: r.xtype || 'textfield'});
                ed = new Ext.grid.GridEditor(o);
                cm.setEditor(ci,ed);
            }
            if (ed.store && !ed.store.isLoaded && ed.config.mode != 'local') {
                ed.store.load();
                ed.store.isLoaded = true;
            }
            f = Ext.util.Format.comboRenderer(ed.field,v);
            return f(v,md,rec,ri,ci,s,g);
        }
        return v;
    }

    /**
     * Prevent display updated date for unmodified records
     *
     * @param {String} value
     *
     * @returns {String}
     */
    ,renderLastModDate: function(value) {
        if (Ext.isEmpty(value)) {
            return '—';
        }

        // Return formatted date (server side)
        return value;
        // JavaScripts time is in milliseconds
        //return new Date(value*1000).format(MODx.config.manager_date_format + ' ' + MODx.config.manager_time_format);
    }
});
Ext.reg('modx-grid-settings',MODx.grid.SettingsGrid);


MODx.combo.Area = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'area'
        ,hiddenName: 'area'
        ,displayField: 'd'
        ,valueField: 'v'
        ,fields: ['d','v']
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/settings/getAreas'
        }
    });
    MODx.combo.Area.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Area,MODx.combo.ComboBox);
Ext.reg('modx-combo-area',MODx.combo.Area);


MODx.window.CreateSetting = function(config) {
    config = config || {};
    config.keyField = config.keyField || {};
    Ext.applyIf(config,{
        title: _('setting_create')
        ,width: 600
        ,url: config.url
        ,action: 'system/settings/create'
        ,autoHeight: true
        ,fields: [{
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,anchor: '100%'
                ,border: false
            }
            ,autoHeight: true
            ,items: [{
                columnWidth: .5
                ,items: [{
                    xtype: 'hidden'
                    ,name: 'fk'
                    ,id: 'modx-cs-fk'
                    ,value: config.fk || 0
                },Ext.applyIf(config.keyField, {
                    xtype: 'textfield'
                    ,fieldLabel: _('key')
                    ,name: 'key'
                    ,id: 'modx-cs-key'
                    ,maxLength: 100
                    ,anchor: '100%'
                }),{
                    xtype: 'label'
                    ,forId: 'modx-cs-key'
                    ,html: _('key_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('name')
                    ,name: 'name'
                    ,id: 'modx-cs-name'
                    ,anchor: '100%'
                },{
                    xtype: 'label'
                    ,forId: 'modx-cs-name'
                    ,html: _('name_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('description')
                    ,name: 'description'
                    ,id: 'modx-cs-description'
                    ,allowBlank: true
                    ,anchor: '100%'
                },{
                    xtype: 'label'
                    ,forId: 'modx-cs-description'
                    ,html: _('description_desc')
                    ,cls: 'desc-under'
                }]
            },{
                columnWidth: .5
                ,items: [{
                    xtype: 'modx-combo-xtype-spec'
                    ,fieldLabel: _('xtype')
                    ,description: MODx.expandHelp ? '' : _('xtype_desc')
                    ,id: 'modx-cs-xtype'
                    ,anchor: '100%'
                },{
                    xtype: 'label'
                    ,forId: 'modx-cs-xtype'
                    ,html: _('xtype_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'modx-combo-namespace'
                    ,fieldLabel: _('namespace')
                    ,name: 'namespace'
                    ,id: 'modx-cs-namespace'
                    ,value: Ext.getCmp('modx-filter-namespace').getValue()
                    ,anchor: '100%'
                },{
                    xtype: 'label'
                    ,forId: 'modx-cs-namespace'
                    ,html: _('namespace_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('area_lexicon_string')
                    ,description: _('area_lexicon_string_msg')
                    ,name: 'area'
                    ,id: 'modx-cs-area'
                    ,anchor: '100%'
                    ,value: Ext.getCmp('modx-filter-area').getValue()
                },{
                    xtype: 'label'
                    ,forId: 'modx-cs-area'
                    ,html: _('area_lexicon_string_msg')
                    ,cls: 'desc-under'
                }]
            }]
        },{
            xtype: 'textarea'
            ,fieldLabel: _('value')
            ,name: 'value'
            ,id: 'modx-cs-value'
            ,autoHeight: true
            ,anchor: '100%'
        }]
        ,keys: []
    });
    MODx.window.CreateSetting.superclass.constructor.call(this,config);
    this.on('show',function() {
        this.reset();
        this.setValues({
            namespace: Ext.getCmp('modx-filter-namespace').value
            ,area: Ext.getCmp('modx-filter-area').value
        });
    },this);
};
Ext.extend(MODx.window.CreateSetting,MODx.Window);
Ext.reg('modx-window-setting-create',MODx.window.CreateSetting);


MODx.combo.xType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: [[_('textfield'),'textfield']
                ,[_('textarea'),'textarea']
                ,[_('yesno'),'combo-boolean']
                ,[_('password'),'text-password']
                ,[_('category'),'modx-combo-category']
                ,[_('charset'),'modx-combo-charset']
                ,[_('country'),'modx-combo-country']
                ,[_('context'),'modx-combo-context']
                ,[_('namespace'),'modx-combo-namespace']
                ,[_('template'),'modx-combo-template']
                ,[_('user'),'modx-combo-user']
                ,[_('usergroup'),'modx-combo-usergroup']
                ,[_('language'),'modx-combo-language']
                ,[_('source'),'modx-combo-source']
                ,[_('setting_manager_theme'),'modx-combo-manager-theme']
            ]
        })
        ,displayField: 'd'
        ,valueField: 'v'
        ,mode: 'local'
        ,name: 'xtype'
        ,hiddenName: 'xtype'
        ,triggerAction: 'all'
        ,editable: false
        ,selectOnFocus: false
        ,value: 'textfield'
    });
    MODx.combo.xType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.xType,Ext.form.ComboBox);
Ext.reg('modx-combo-xtype-spec',MODx.combo.xType);


MODx.window.UpdateSetting = function(config) {
    config = config || {};
    this.ident = config.ident || 'modx-uss-'+Ext.id();
    Ext.applyIf(config,{
        title: _('setting_update')
        ,width: 600
        ,url: config.grid.config.url
        ,action: 'system/settings/update'
        ,autoHeight: true
        ,fields: [{
            layout: 'column'
            ,border: false
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,anchor: '100%'
                ,border: false
            }
            ,autoHeight: true
            ,items: [{
                columnWidth: .5
                ,items: [{
                    xtype: 'hidden'
                    ,name: 'fk'
                    ,id: 'modx-'+this.ident+'-fk'
                    ,value: config.fk || 0
                },{
                    xtype: 'statictextfield'
                    ,fieldLabel: _('key')
                    ,description: MODx.expandHelp ? '' : _('key_desc')
                    ,name: 'key'
                    ,id: 'modx-'+this.ident+'-key'
                    ,maxLength: 100
                    ,submitValue: true
                    ,anchor: '100%'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-'+this.ident+'-key'
                    ,html: _('key_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('name')
                    ,description: MODx.expandHelp ? '' : _('name_desc')
                    ,name: 'name'
                    ,id: 'modx-'+this.ident+'-name'
                    ,anchor: '100%'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-'+this.ident+'-name'
                    ,html: _('name_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('description')
                    ,description: MODx.expandHelp ? '' : _('description_desc')
                    ,name: 'description'
                    ,id: 'modx-'+this.ident+'-description'
                    ,allowBlank: true
                    ,anchor: '100%'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-'+this.ident+'-description'
                    ,html: _('description_desc')
                    ,cls: 'desc-under'
                }]
            },{
                columnWidth: .5
                ,items: [{
                    xtype: 'modx-combo-xtype-spec'
                    ,fieldLabel: _('xtype')
                    ,description: MODx.expandHelp ? '' : _('xtype_desc')
                    ,id: 'modx-'+this.ident+'-xtype'
                    ,anchor: '100%'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-'+this.ident+'-xtype'
                    ,html: _('xtype_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'modx-combo-namespace'
                    ,fieldLabel: _('namespace')
                    ,description: MODx.expandHelp ? '' : _('namespace_desc')
                    ,name: 'namespace'
                    ,id: 'modx-'+this.ident+'-namespace'
                    ,value: 'core'
                    ,anchor: '100%'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-'+this.ident+'-namespace'
                    ,html: _('namespace_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('area_lexicon_string')
                    ,description: MODx.expandHelp ? '' : _('area_lexicon_string_msg')
                    ,name: 'area'
                    ,id: 'modx-'+this.ident+'-area'
                    ,anchor: '100%'
                },{
                    xtype: MODx.expandHelp ? 'label' : 'hidden'
                    ,forId: 'modx-'+this.ident+'-area'
                    ,html: _('area_lexicon_string_msg')
                    ,cls: 'desc-under'
                }]
            }]
        },{
            xtype: config.record ? config.record.xtype : 'textarea'
            ,fieldLabel: _('value')
            ,name: 'value'
            ,hiddenName: 'value'
            ,id: 'modx-'+this.ident+'-value'
            ,autoHeight: true
            ,anchor: '100%'
        }]
        ,keys: []
    });
    MODx.window.UpdateSetting.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.UpdateSetting,MODx.Window);
Ext.reg('modx-window-setting-update',MODx.window.UpdateSetting);
