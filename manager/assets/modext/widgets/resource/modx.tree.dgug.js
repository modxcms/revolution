/**
 * Generates the Document Group to User Group pairing Tree in Ext
 * 
 * @class MODx.tree.DGUG
 * @extends MODx.tree.Tree
 * @constructor
 * @param {Object} config An object of options.
 * @xtype tree-dgug
 */
MODx.tree.DGUG = function(config) {
    config = config || {};
	Ext.applyIf(config,{
		root_id: '0'
		,root_name: _('document_groups')
		,enableDrag: false
		,enableDrop: true
		,ddAppendOnly: true
		,action: 'getPairingNodes'
	});
	MODx.tree.DGUG.superclass.constructor.call(this,config);
	this.addContextMenuItem({
		id: 'new_documentgroup'
		,text: _('create_document_group')
		,handler: this.showCreate
		,scope: this
	});
};
Ext.extend(MODx.tree.DGUG,MODx.tree.Tree,{	
	forms: {}
	,dialogs: {}
	,stores: {}
		
	,removePairing: function(item,e) {
		var n = this.cm.activeNode;
		var ug_id = n.id.substr(2).split('_'); ug_id = ug_id[1];
		var dg_id = n.parentNode.id.substr(2).split('_'); dg_id = dg_id[1];
		MODx.msg.confirm({
			text: _('confirm_delete_user_group_document_group')
			,url: MODx.config.connectors_url+'security/documentgroup.php'
			,params: { 
				action: 'removePairing'
				,ug_id: ug_id
				,dg_id: dg_id
			}
			,listeners: {
			     'success': {fn:function() { this.refresh(); },scope:this}
			}
		});
	}
	
	,remove: function(item,e) {
		var n = this.cm.activeNode;
		var id = n.id.substr(2).split('_'); id = id[1];
		MODx.msg.confirm({
			text: _('confirm_delete_document_group')
			,url: MODx.config.connectors_url+'security/documentgroup.php'
			,params: { 
				action: 'remove'
				,id: id
			}
			,listeners: {
				'success': {fn:function() {
				    this.refresh();
				    this.dgtree.refresh();
			    },scope:this}
			}
		});
	}
	
	,create: function(item,e) {
		var id = this.cm.activeNode.id.substr(2); id = id[1];
		
		var d = new MODx.window.CreateDocumentGroup({
			title: _('create_document_group')
			,listeners: {
				'success': {fn:function() {
				    this.refresh();
				    this.dgtree.refresh();
			     },scope: this}
			}
		});
		d.show(e.target);
		this.dialogs.create = d;
	}
	
	,isUGCopy: function(e, n) {
        var a = e.target.attributes;
		if (e.target.findChild('id',n.attributes.id) !== null) { return false; }
        return n.attributes.type == 'usergroup' && n.getOwnerTree().getEl().id == this.ugtree.getEl().id && 
           ((e.point == 'append' && a.type == 'documentgroup') || a.leaf === false);
	}
	
	,createDGUG: function(n, text){
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
	
	,_showContextMenu: function(node,e) {
		node.select();
		
		var m = this.cm;
		var nar = node.id.split('_');
		m.removeAll();
		
		m.add({
			id: 'create_document_group'
			,text: _('create_document_group')
			,scope: this
			,handler: this.create
		});
		
		var type = nar[1];
		switch (type) {
			case 'ug':
				m.add('-',{
					id: 'remove_pairing'
					,text: _('delete_user_group_document_group')
					,scope: this
					,handler: this.removePairing
				});
				break;
			case 'dg':
				m.add('-',{
					id: 'remove_group'
					,text: _('delete_document_group')
					,scope: this
					,handler: this.remove
				});
				break;		
		}
		
		m.show(node.ui.getEl(),'t?');
		m.activeNode = node;
	}
		
	,_handleDrop:  function(e){
        var n = e.dropNode;

        // copy node from usergroup tree
        if(this.isUGCopy(e,n)) {
            var copy = new Ext.tree.TreeNode(
                Ext.apply({leaf: true,allowDelete:true,expanded:true}, n.attributes)
            );
            copy.loader = undefined;
            if(e.target.attributes.options){
                e.target = this.createDGUG(e.target, copy.text);
            }
            e.dropNode = copy;
            return true;
        }
		return false;
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
		
		function simplifyNodes(node) {
			var resultNode = {};
			var kids = node.childNodes;
			var len = kids.length;
			for (var i = 0; i < len; i++) {
				resultNode[kids[i].id] = simplifyNodes(kids[i]);
			}
			return resultNode;
		}
		
        // JSON-encode our tree
		var encNodes = Ext.encode(simplifyNodes(dropEvent.tree.root));
				
		// send it to the backend to save
		Ext.Ajax.request({
			url: this.config.url
			,scope: this
			,params: {
				data: encodeURIComponent(encNodes)
				,action: 'updatePairing'
			}
			,callback: function(o,s,xhr) {
				MODx.util.Progress.reset();
				Ext.Msg.hide();
				var e = Ext.decode(xhr.responseText);
				if (!e.success) {
					MODx.form.Handler.errorJSON(e);
					this.refresh();
					return false;
				}
			}
		});
	}
});
Ext.reg('tree-dgug',MODx.tree.DGUG);