MODx.panel.Users = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-users'
		,cls: 'container'
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('users')+'</h2>'
            ,border: false
            ,id: 'modx-users-header'
            ,cls: 'modx-page-header'
        },{
            layout: 'form'
            ,items: [{
                html: '<p>'+_('user_management_msg')+'</p>'
				,bodyCssClass: 'panel-desc'
                ,border: false
            },{
                xtype: 'modx-grid-user'
				,cls:'main-wrapper'
                ,preventRender: true
            }]
        }]
    });
    MODx.panel.Users.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Users,MODx.FormPanel);
Ext.reg('modx-panel-users',MODx.panel.Users);

MODx.grid.User = function(config) {
    config = config || {};

    this.sm = new Ext.grid.CheckboxSelectionModel();
    Ext.applyIf(config,{
        url: MODx.config.connectors_url+'security/user.php'
        ,baseParams: {
            action: 'getList'
            ,usergroup: MODx.request['usergroup'] ? MODx.request['usergroup'] : ''
        }
        ,fields: ['id','username','fullname','email','gender','blocked','role','active','cls']
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,viewConfig: {
            forceFit:true
            ,enableRowBody:true
            ,scrollOffset: 0
            ,autoFill: true
            ,showPreview: true
            ,getRowClass : function(rec){
                return rec.data.active ? 'grid-row-active' : 'grid-row-inactive';
            }
        }
        ,sm: this.sm
        ,columns: [this.sm,{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 50
            ,sortable: true
        },{
            header: _('name')
            ,dataIndex: 'username'
            ,width: 150
            ,sortable: true
        },{
            header: _('user_full_name')
            ,dataIndex: 'fullname'
            ,width: 180
            ,sortable: true
            ,editor: { xtype: 'textfield' }
        },{
            header: _('email')
            ,dataIndex: 'email'
            ,width: 180
            ,sortable: true
            ,editor: { xtype: 'textfield' }
        },{
            header: _('active')
            ,dataIndex: 'active'
            ,width: 80
            ,sortable: true
            ,editor: { xtype: 'combo-boolean', renderer: 'boolean' }
        },{
            header: _('user_block')
            ,dataIndex: 'blocked'
            ,width: 80
            ,editor: { xtype: 'combo-boolean', renderer: 'boolean' }
        }]
        ,tbar: [{
            text: _('user_new')
            ,handler: this.createUser
            ,scope: this
        },'-',{
            text: _('bulk_actions')
            ,menu: [{
                text: _('selected_activate')
                ,handler: this.activateSelected
                ,scope: this
            },{
                text: _('selected_deactivate')
                ,handler: this.deactivateSelected
                ,scope: this
            },'-',{
                text: _('selected_remove')
                ,handler: this.removeSelected
                ,scope: this
            }]
        },'->',{
            xtype: 'modx-combo-usergroup'
            ,name: 'usergroup'
            ,id: 'modx-user-filter-usergroup'
            ,itemId: 'usergroup'
            ,emptyText: _('user_group')+'...'
            ,baseParams: {
                action: 'getList'
                ,addAll: true
            }
            ,value: MODx.request['usergroup'] ? MODx.request['usergroup'] : ''
            ,width: 200
            ,listeners: {
                'select': {fn:this.filterUsergroup,scope:this}
            }
        },'-',{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-user-search'
            ,emptyText: _('search_ellipsis')
            ,listeners: {
                'change': {fn: this.search, scope: this}
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
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this}
            }
        }]
    });
    MODx.grid.User.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.User,MODx.grid.Grid,{
    getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var p = r.data.cls;

        var m = [];
        if (this.getSelectionModel().getCount() > 1) {
            m.push({
                text: _('selected_activate')
                ,handler: this.activateSelected
                ,scope: this
            });
            m.push({
                text: _('selected_deactivate')
                ,handler: this.deactivateSelected
                ,scope: this
            });
            m.push('-');
            m.push({
                text: _('selected_remove')
                ,handler: this.removeSelected
                ,scope: this
            });
        } else {
            if (p.indexOf('pupdate') != -1) {
                m.push({
                    text: _('user_update')
                    ,handler: this.updateUser
                });
            }
            if (p.indexOf('pcopy') != -1) {
                if (m.length > 0) m.push('-');
                m.push({
                    text: _('user_duplicate')
                    ,handler: this.duplicateUser
                });
            }
            if (p.indexOf('premove') != -1) {
                if (m.length > 0) m.push('-');
                m.push({
                    text: _('user_remove')
                    ,handler: this.removeUser
                });
            }
        }
        if (m.length > 0) {
            this.addContextMenuItem(m);
        }
    }

    ,createUser: function() {
        location.href = 'index.php?a='+MODx.action['security/user/create'];
    }

    ,activateSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'activateMultiple'
                ,users: cs
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                },scope:this}
            }
        });
        return true;
    }
    ,deactivateSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'deactivateMultiple'
                ,users: cs
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                },scope:this}
            }
        });
        return true;
    }
    ,removeSelected: function() {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;

        MODx.msg.confirm({
            title: _('user_remove_multiple')
            ,text: _('user_remove_multiple_confirm')
            ,url: this.config.url
            ,params: {
                action: 'removeMultiple'
                ,users: cs
            }
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                },scope:this}
            }
        });
        return true;
    }
    
    ,removeUser: function() {
        MODx.msg.confirm({
            title: _('user_remove')
            ,text: _('user_confirm_remove')
            ,url: this.config.url
            ,params: {
                action: 'delete'
                ,id: this.menu.record.id
            }
            ,listeners: {
            	'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,duplicateUser: function() {
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'duplicate'
                ,id: this.menu.record.id
            }
            ,listeners: {
            	'success': {fn:this.refresh,scope:this}
            }
        });
    }
    
    ,updateUser: function() {
        location.href = 'index.php?a='+MODx.action['security/user/update']+'&id='+this.menu.record.id;
    }
    				
    ,rendGender: function(d,c) {
        switch(d.toString()) {
            case '0':
                return '-';
            case '1':
                return _('male');
            case '2':
                return _('female');
        }
    }

    ,filterUsergroup: function(cb,nv,ov) {
        this.getStore().baseParams.usergroup = Ext.isEmpty(nv) || Ext.isObject(nv) ? cb.getValue() : nv;
        this.getBottomToolbar().changePage(1);
        this.refresh();
        return true;
    }
    ,search: function(tf,newValue,oldValue) {
        var nv = newValue || tf;
        this.getStore().baseParams.query = Ext.isEmpty(nv) || Ext.isObject(nv) ? '' : nv;
        this.getBottomToolbar().changePage(1);
        return true;
    }
    ,clearFilter: function() {
    	this.getStore().baseParams = {
            action: 'getList'
    	};
        Ext.getCmp('modx-user-search').reset();
        Ext.getCmp('modx-user-filter-usergroup').reset();
    	this.getBottomToolbar().changePage(1);
        this.refresh();
    }
});
Ext.reg('modx-grid-user',MODx.grid.User);
