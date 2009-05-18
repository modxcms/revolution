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
        ,url: MODx.config.connectors_url+'security/documentgroup.php'
		,root_id: '0'
		,root_name: _('resource_groups')
		,enableDrag: false
		,enableDrop: true
		,ddAppendOnly: true
        ,useDefaultToolbar: true
        ,tbar: [{
            text: _('resource_group_create')
            ,scope: this
            ,handler: this.create
        }]
	});
	MODx.tree.ResourceGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.tree.ResourceGroup,MODx.tree.Tree,{
	forms: {}
	,windows: {}
	,stores: {}
	
	,removeResource: function(item,e) {
		var n = this.cm.activeNode;
		var doc_id = n.id.split('_'); doc_id = doc_id[1];
		var dg_id = n.parentNode.id.substr(2).split('_'); dg_id = dg_id[1];
		
		MODx.msg.confirm({
			text: _('resource_group_access_remove_confirm')
			,url: this.config.url
			,params: {
				action: 'removeDocument'
				,document: doc_id
				,document_group: dg_id
			}
			,listeners: {
				'success': {fn:this.refresh,scope:this} 
			}
		});
	}
	
	,remove: function(item,e) {
		var n = this.cm.activeNode;
		var id = n.id.substr(2).split('_'); id = id[1];
		
		MODx.msg.confirm({
			text: _('resource_group_remove_confirm')
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
	
	,create: function(itm,e) {
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
	
	,_handleDrop:  function(e){
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
		if (n.attributes.type != 'modResource') { return false; }
		if (e.point != 'append') { return false; }
		if (a.type != 'modResourceGroup') { return false; }
		if (a.leaf === true) { return false; }
		return true;
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
                ,resource_group: dropEvent.target.attributes.id
				,action: 'updateDocumentsIn'
			}
            ,listeners: {
                'success': {fn:function(r,o) {
    				MODx.util.Progress.reset();
    				Ext.Msg.hide();
    				r = Ext.decode(r.responseText);
    				if (!r.success) {
    					Ext.Msg.alert(_('error'),r.message);
    					this.refresh();
    					return false;
    				}
    			},scope:this}
            }
		});
	}
});
Ext.reg('modx-tree-resource-group',MODx.tree.ResourceGroup);