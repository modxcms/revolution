/**
 * Loads a grid of System Events
 *
 * @class MODx.grid.SystemEvent
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-system-event
 */
MODx.grid.SystemEvent = function(config = {}) {
    const queryValue = this.applyRequestFilter(1, 'query', 'tab', true);
    Ext.applyIf(config,{
        title: _('system_events')
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'System/Event/GetList'
        }
        ,fields: [
            'id',
            'name',
            'service',
            'groupname',
            'plugins'
        ]
        ,autosave: true
        ,paging: true
		,clicksToEdit: 2
        ,grouping: true
        ,groupBy: 'groupname'
        ,singleText: _('system_event')
        ,pluralText: _('system_events')
        ,showActionsColumn: false
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
        },{
            header: _('system_events.service')
            ,dataIndex: 'service'
			,renderer: this.renderServiceField.createDelegate(this,[this],true)
        },{
            header: _('system_events.plugins')
            ,dataIndex: 'plugins'
            ,width: 150
            ,renderer: this.renderPluginsField.createDelegate(this,[this],true)
        },{
			header: _('system_events.groupname')
            ,dataIndex: 'groupname'
            ,width: 150
			,hidden: true
		}]
		,tbar: [
            {
                text: _('create')
                ,scope: this
                ,cls:'primary-button'
                ,handler: {
                    xtype: 'modx-window-events-create-update'
                    ,url: config.url || MODx.config.connector_url
                    ,blankValues: true
    				,isUpdate: false
                }
            },
            '->',
            this.getQueryFilterField(`filter-query-events:${queryValue}`),
            this.getClearFiltersButton('filter-query-events')
        ]
    });
    MODx.grid.SystemEvent.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.SystemEvent,MODx.grid.Grid,{
	getMenu: function(btn, e) {
		var m = [];
		if (this.menu.record.service == 6) { /* user defined */
			m.push({
				text: _('delete')
				,handler: this.removeEvent
			});
		}
		return m;
	}

	,removeEvent: function(btn, e) {
		MODx.msg.confirm({
			title: _('delete')
			,text: _('system_events.remove_confirm', { name: this.menu.record.name })
			,url: this.config.url
			,params: {
				action: 'System/Event/Remove'
				,name: this.menu.record.name
			}
			,listeners: {
				'success': { fn: this.refresh ,scope: this }
			}
		});
	}

	,renderServiceField: function(v,md,rec,ri,ci,s,g) {
        return _('system_events.service_' + v);
    }

    ,renderPluginsField: function(v,md,rec,ri,ci,s,g) {
        var output = [];
        Ext.each(v, function(elem) {
            if (!elem) { return; }
            output.push(elem.name);
        });

        return output.join(', ');
    }
});
Ext.reg('modx-grid-system-event',MODx.grid.SystemEvent);



MODx.window.CreateUpdateEvent = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('create')
        ,width: 450
		,autoHeight: true
        ,url: config.url
        ,action: 'System/Event/Create'
        ,fields: [{
			xtype: 'hidden'
			,name: 'service'
			,value: 6 /* user defined */
		},{
			xtype: 'textfield'
			,fieldLabel: _('name')
			,name: 'name'
			,id: 'modx-se-name'
			,allowBlank: false
			,maxLength: 50
			,anchor: '100%'
		},{
			xtype: 'label'
			,forId: 'modx-se-name'
			,html: _('system_events.name_desc')
			,cls: 'desc-under'
		},{
			xtype: 'modx-combo-event-groups'
			,fieldLabel: _('system_events.groupname')
			,name: 'groupname'
			,id: 'modx-se-groupname'
			,anchor: '100%'
		},{
			xtype: 'label'
			,forId: 'modx-se-groupname'
			,html: _('system_events.groupname_desc')
			,cls: 'desc-under'
		}]
        ,keys: []
    });
    MODx.window.CreateUpdateEvent.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateUpdateEvent,MODx.Window);
Ext.reg('modx-window-events-create-update',MODx.window.CreateUpdateEvent);



MODx.combo.SystemEventGroups = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        name: 'groupname'
		,hiddenName: 'groupname'
		,displayField: 'name'
		,valueField: 'name'
		,fields: ['name']
		,forceSelection: false
		,typeAhead: true
		,editable: true
		,allowBlank: false
		,pageSize: 10
		,url: MODx.config.connector_url
		,baseParams: {
            action: 'System/Event/GroupList'
			,combo: true
        }
    });
    MODx.combo.SystemEventGroups.superclass.constructor.call(this, config);
};
Ext.extend(MODx.combo.SystemEventGroups, MODx.combo.ComboBox);
Ext.reg('modx-combo-event-groups', MODx.combo.SystemEventGroups);
