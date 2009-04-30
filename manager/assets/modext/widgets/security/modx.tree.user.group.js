/**
 * Generates the User Group Tree
 *
 * @class MODx.tree.UserGroup
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-usergroup
 */
MODx.tree.UserGroup = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		title: _('user_groups') 
        ,id: 'modx-tree-usergroup'
        ,url: MODx.config.connectors_url+'security/group.php'
		,root_id: 'n_ug_0'
		,root_name: _('user_groups')
		,enableDrag: true
		,enableDrop: true
        ,rootVisible: false
		,ddAppendOnly: true
        ,useDefaultToolbar: true
        ,tbar: [{
            text: _('user_group_new')
            ,scope: this
            ,handler: this.create.createDelegate(this,[true],true)
        }]
	});
	MODx.tree.UserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.tree.UserGroup,MODx.tree.Tree,{	
	windows: {}
	
	,addUser: function(item,e) {
		var n = this.cm.activeNode;
		var ug = n.id.substr(2).split('_'); ug = ug[1];
		if (ug === undefined) { ug = 0; }
		var r = {user_group: ug};
        
        if (!this.windows.adduser) {
            this.windows.adduser = MODx.load({
    			xtype: 'modx-window-usergroup-adduser'
    			,record: r
    			,listeners: {
    				'success': {fn:this.refresh,scope:this}
    			}
    		});
        } else {
            this.windows.adduser.setValues(r);
        }
		this.windows.adduser.show(e.target);
	}
	
	,create: function(item,e,tbar) {
		tbar = tbar || false;
        var p;
        if (tbar === false) {
            var n = this.cm.activeNode;
		    p = n.id.substr(2).split('_'); p = p[1];
		    if (p === undefined) { p = 0; }
        } else { p = 0; }
        
        location.href = 'index.php'
            + '?a=' + MODx.action['security/usergroup/create']
            + '&parent=' + p;
	}
    
    ,update: function(item,e) {
        var n = this.cm.activeNode;
        var id = n.id.substr(2).split('_'); id = id[1];
        
        location.href = 'index.php'
            + '?a=' + MODx.action['security/usergroup/update']
            + '&id=' + id;
    }
	
	,remove: function(item,e) {
		var n = this.cm.activeNode;
		var id = n.id.substr(2).split('_'); id = id[1];
		
		MODx.msg.confirm({
			title: _('warning')
			,text: _('confirm_delete_user_group')
			,url: this.config.url
			,params: {
				action: 'remove'
				,id: id
			}
			,listeners: {
				'success': {fn:this.refresh,scope:this}
			}
		});
	}
	
	,removeUser: function(item,e) {
		var n = this.cm.activeNode;
		var user_id = n.id.substr(2).split('_'); user_id = user_id[1];
		var group_id = n.parentNode.id.substr(2).split('_'); group_id = group_id[1];
		
		MODx.msg.confirm({
			title: _('warning')
			,text: _('confirm_remove_user_from_group')
			,url: this.config.url
			,params: { 
				action: 'removeUser'
				,user_id: user_id
				,group_id: group_id
			}
			,listeners: {
				'success':{fn:this.refresh,scope:this}
			}
		});
	}
});
Ext.reg('modx-tree-usergroup',MODx.tree.UserGroup);
