/**
 * Loads a grid of System Events
 *
 * @class MODx.grid.SystemEvent
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-system-event
 */
MODx.grid.SystemEvent = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('system_events')
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/event/getlist'
        }
        ,fields: ['id','name','service','groupname','plugins']
        ,autosave: true
        ,paging: true
		,clicksToEdit: 2
        ,grouping: true
        ,groupBy: 'groupname'
        ,singleText: _('system_event')
        ,pluralText: _('system_events')
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
		,tbar: [{
            text: _('system_events.create')
            ,scope: this
            ,cls:'primary-button'
            ,handler: {
                xtype: 'modx-window-events-create-update'
                ,url: config.url || MODx.config.connector_url
                ,blankValues: true
				,isUpdate: false
            }
        },'->',{
			xtype: 'textfield'
			,name: 'filter_key'
			,id: 'modx-filter-event'
			,cls: 'x-form-filter'
			,emptyText: _('system_events.search_by_name')+'...'
			,listeners: {
				'change': {fn: this.filterByName, scope: this}
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
			,cls: 'x-form-filter-clear'
			,text: _('filter_clear')
			,listeners: {
				'click': {fn: this.clearFilter, scope: this},
				'mouseout': { fn: function(evt){
					this.removeClass('x-btn-focus');
				}
				}
			}
		}]
    });
    MODx.grid.SystemEvent.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.SystemEvent,MODx.grid.Grid,{
	getMenu: function(btn, e) {
		var m = [];
		if (this.menu.record.service == 6) { /* user defined */
			m.push({
				text: _('system_events.remove')
				,handler: this.removeEvent
			});
		}
		return m;
	}

    ,filterByName: function(tf,newValue,oldValue) {
        this.getStore().baseParams.query = newValue;
        this.getBottomToolbar().changePage(1);
        this.refresh();
        return true;
    }
	,clearFilter: function() {
		Ext.getCmp('modx-filter-event').reset();

        this.getStore().baseParams = this.initialConfig.baseParams;
        this.getStore().baseParams.query = '';

    	this.getBottomToolbar().changePage(1);
        this.refresh();
    }

	,removeEvent: function(btn, e) {
		MODx.msg.confirm({
			title: _('system_events.remove')
			,text: _('system_events.remove_confirm', { name: this.menu.record.name })
			,url: this.config.url
			,params: {
				action: 'system/event/remove'
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
        title: _('system_events.create')
        ,width: 450
		,autoHeight: true
        ,url: config.url
        ,action: 'system/event/create'
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
		,autocomplete: true
		,pageSize: 10
		,url: MODx.config.connector_url
		,baseParams: {
            action: 'system/event/groupList'
			,combo: true
        }
    });
    MODx.combo.SystemEventGroups.superclass.constructor.call(this, config);
};
Ext.extend(MODx.combo.SystemEventGroups, MODx.combo.ComboBox);
Ext.reg('modx-combo-event-groups', MODx.combo.SystemEventGroups);
