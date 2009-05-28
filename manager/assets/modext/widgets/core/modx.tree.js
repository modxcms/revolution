Ext.namespace('MODx.tree');
/**
 * Generates the Tree in Ext. All modTree classes extend this base class.
 * 
 * @class MODx.tree.Tree
 * @extends Ext.tree.TreePanel
 * @constructor
 * @param {Object} config An object of options.
 * @xtype modx-tree
 */
MODx.tree.Tree = function(config) {
	config = config || {};
	if (config.url === null) { return false; }
    config.action = config.action || 'getNodes';
    
	Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
    Ext.applyIf(config,{
        baseParams: {}
    });
    if (config.action) {
        config.baseParams.action = config.action;
    }	
    this.config = config;
    
	var tl = new Ext.tree.TreeLoader({
		dataUrl: config.url
		,baseParams: config.baseParams
        ,clearOnLoad: true
	});
	tl.on('beforeload',function(loader,node) {
		tl.dataUrl = this.config.url+'?action='+this.config.action+'&id='+node.attributes.id;
        if (node.attributes.type) {
            tl.dataUrl += '&type='+node.attributes.type;
        }
	},this);
	
	Ext.applyIf(config,{
		animate:true
		,autoHeight: true
		,enableDrag: true
		,enableDrop: true
		,ddAppendOnly: false
		,rootVisible: true
		,loader: tl
		,collapsible: true
		,hideBorders: true
		,bodyBorder: false
		,containerScroll: true
		,autoScroll: true
        ,cls: 'modx-tree'
	});
	if (config.remoteToolbar === true && (config.tbar === undefined || config.tbar === null)) {
		Ext.Ajax.request({
			url: config.url
			,params: {
                action: 'getToolbar'
            }
            ,scope: this
            ,success: function(r,o) {
                r = Ext.decode(r.responseText);
				config.tbar = this._formatToolbar(r);
                this.setup(config);
            }
		});
	} else {
		var tb = this.getToolbar();
        if (config.tbar && config.useDefaultToolbar) {
            tb.push('-');
            for (var i=0;i<config.tbar.length;i++) {
                tb.push(config.tbar[i]);
            }
        } else if (config.tbar) {
            tb = config.tbar;
        }
        Ext.apply(config,{tbar: tb});		
		this.setup(config);
	}
    this.config = config;
};
Ext.extend(MODx.tree.Tree,Ext.tree.TreePanel,{
	menu: null
	,options: {}
	
	/**
	 * Sets up the tree and initializes it with the specified options.
	 * @param {Object} options
	 */
	,setup: function(config) {
	    MODx.tree.Tree.superclass.constructor.call(this,config);
	    this.cm = new Ext.menu.Menu(Ext.id(),{});
	    
	    this.on('contextmenu',this._showContextMenu,this);
	    this.on('beforenodedrop',this._handleDrop,this);
	    this.on('nodedragover',this._handleDrop,this);
	    this.on('nodedrop',this._handleDrag,this);
	    this.on('click',this._saveState,this);
        this.on('contextmenu',this._saveState,this);
        this.on('click',this._handleClick,this);
	    
	    this.root = new Ext.tree.AsyncTreeNode({
	        text: config.root_name || ''
	        ,draggable: false
	        ,id: config.root_id || 'root'
	    });
	    this.setRootNode(this.root);
	    
	    this.treestate_id = this.config.id || Ext.id();
	    this.on('load',this._initExpand,this,{single: true});
	    this.root.expand();
	    
	    this._loadToolbar();
        if (config.el) { this.render(); }
	}
	
	/**
	 * Expand the tree upon initialization.
	 */
	,_initExpand: function() {
		var treeState = Ext.state.Manager.get(this.treestate_id);
		if (treeState === undefined && this.root) {
			this.root.expand();
			if (this.root.firstChild && this.config.expandFirst) {
				this.root.firstChild.select();
				this.root.firstChild.expand();
			}
		} else { this.expandPath(treeState); }		
	}
	
	/**
	 * Add context menu items to the tree.
	 * @param {Object, Array} items Either an Object config or array of Object configs.  
	 */
	,addContextMenuItem: function(items) {
		var a = items, l = a.length;
        for(var i = 0; i < l; i++) {
            a[i].scope = this;
            this.cm.add(a[i]);
        }
	}
	
    /**
     * Shows the current context menu.
     * @param {Ext.tree.TreeNode} node The 
     * @param {Ext.EventObject} e The event object run.
     */
    ,_showContextMenu: function(node,e) {
        node.select();
        this.cm.activeNode = node;
        var nar = node.id.split('_');
        
        this.cm.removeAll();
        if (node.attributes.menu && node.attributes.menu.items) {
            this.addContextMenuItem(node.attributes.menu.items);
            this.cm.show(node.ui.getEl(),'t?');
        }
    }
    
	/**
	 * Checks to see if a node exists in a tree node's children.
	 * @param {Object} t The parent node.
	 * @param {Object} n The node to find.
	 * @return {Boolean} True if the node exists in the parent's children. 
	 */
	,hasNode: function(t, n) {
        return (t.findChild('id', n.id)) || (t.leaf === true && t.parentNode.findChild('id', n.id));
    }
	
	/**
	 * Refreshes the tree and runs an optional func.
	 * @param {Function} func The function to run.
	 * @param {Object} scope The scope to run the function in.
	 * @param {Array} args An array of arguments to run with.
	 * @return {Boolean} True if successful.
	 */
	,refresh: function(func,scope,args) {
		var treeState = Ext.state.Manager.get(this.treestate_id);
		this.root.reload();
		treeState === undefined
			? this.root.expand(null,null)
			: this.expandPath(treeState,null);
		if (func) {
			scope = scope || this;
			args = args || [];
			this.root.on('load',function() { Ext.callback(func,scope,args); },scope);
		}
		return true;
	}
	
	,reloadNode: function(n) {
        this.getLoader().load(n);
        n.expand();
	}
    
    /**
     * Abstracted remove function
     */
    ,remove: function(text,substr,split) {
        var node = this.cm.activeNode;
        var id = this._extractId(node.id,substr,split);
        var p = { action: 'remove' };
        var pk = this.config.primaryKey || 'id';
        p[pk] = id;
        MODx.msg.confirm({
            title: _('warning')
            ,text: _(text)
            ,url: this.config.url
            ,params: p
            ,listeners: {
            	'success': {fn:this.refresh,scope:this}
            }
        }); 
    }
    
    ,_extractId: function(id,substr,split) {
        substr = substr || false;
        split = split || false;
        if (substr !== false) {
            id = node.id.substr(substr);
        }
        if (split !== false) {
            id = node.id.split('_');
            id = id[split];
        }
        return id;
    }
	
	/**
	 * Expand the tree and all children.
	 */
	,expand: function() {
		if (this.root) {
            this.root.expand();
            this.root.expandChildNodes();
        }
	}
	
	/**
	 * Completely collapse the tree.
	 */
	,collapse: function() {
		if (this.root) {
            this.root.collapseChildNodes();
            this.root.collapse();
        }
	}
	
	/**
	 * Save the state of the tree's open children for a certain node.
	 * @param {Ext.tree.TreeNode} n The most recent clicked-on node.
	 */
	,_saveState: function(n) {
		Ext.state.Manager.set(this.treestate_id,n.getPath());
	}
    
    /**
     * Handles tree clicks
     * @param {Object} n The node clicked 
     */
	,_handleClick: function (n,e) {
        e.preventDefault();
        e.stopEvent();
        if (n.attributes.href && n.attributes.href !== '') {
            location.href = n.attributes.href;
        }
    }
    
	/**
	 * Handles all drag events into the tree.
	 * @param {Object} dropEvent The node dropped on the parent node.
	 */
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
		
		/**
		 * Simplify nodes into JSON format.
		 * @param {Object} node
		 */
		function simplifyNodes(node) {
			var resultNode = {};
			var kids = node.childNodes;
			var len = kids.length;
			for (var i = 0; i < len; i++) {
				resultNode[kids[i].id] = simplifyNodes(kids[i]);
			}
			return resultNode;
		}
		
		var encNodes = Ext.encode(simplifyNodes(dropEvent.tree.root));
		MODx.Ajax.request({
			url: this.config.url
			,params: {
				data: encodeURIComponent(encNodes)
				,action: 'sort'
			}
			,listeners: {
				'success': {fn:function(r) {
                    MODx.util.Progress.reset();
    				Ext.Msg.hide();
    				this.reloadNode(dropEvent.target.parentNode);
				},scope:this}
				,'failure': {fn:function(r) {
					MODx.util.Progress.reset();
					Ext.Msg.hide();
                    MODx.form.Handler.errorJSON(r);
                    return false;
				},scope:this}
			}
		});
	}
	
	/**
	 * Abstract definition to handle drop events.
	 */
	,_handleDrop: function() { }
	
	
	/**
	 * Semi unique ids across edits
	 * @param {String} prefix Prefix the guid.
	 * @return {String} The newly generated guid.
	 */
	,_guid: function(prefix){
        return prefix+(new Date().getTime());
    }
	
	/**
	 * Redirects the page or the content frame to the correct location.
	 * @param {String} loc The URL to direct to.
	 */
	,redirect: function(loc) {
        location.href = loc;
	}
	
    ,loadAction: function(p) {
        var id = this.cm.activeNode.id.split('_'); id = id[1];
        var u = 'index.php?id='+id+'&'+p;
        location.href = u;
    }
	/**
	 * Loads the default toolbar for the tree.
	 * @access private
	 * @see Ext.Toolbar
	 */
	,_loadToolbar: function() {}
	
	/**
	 * Refreshes a given tree node.
	 * @access public
	 * @param {String} id The ID of the node
	 * @param {Boolean} self If true, will refresh self rather than parent.
	 */
	,refreshNode: function(id,self) {
		var node = this.getNodeById(id);
		if (node) {
            var n = self ? node : node.parentNode;
            var l = this.getLoader().load(n);
            n.expand();
		}
	}
	
	/**
	 * Refreshes selected active node
	 * @access public
	 */
	,refreshActiveNode: function() {
        this.getLoader().load(this.cm.activeNode);
        this.cm.activeNode.expand();
    }
    
    /**
     * Refreshes selected active node's parent
     * @access public
     */
    ,refreshParentNode: function() {
        this.getLoader().load(this.cm.activeNode.parentNode);
        this.cm.activeNode.parentNode.expand();
    }
    
    /**
     * Removes specified node
     * @param {String} id The node's ID
     */
    ,removeNode: function(id) {
    	var node = this.getNodeById(id);
        if (node) {
            node.remove(); 
        }
    }
    
    /**
     * Dynamically removes active node
     * @access public 
     */
    ,removeActiveNode: function() {
        this.cm.activeNode.remove();
    }
	
    /**
     * Gets a default toolbar setup
     */
	,getToolbar: function() {
		var iu = MODx.config.template_url+'images/restyle/icons/';
        return [{
            icon: iu+'arrow_down.png'
            ,cls: 'x-btn-icon'
            ,scope: this
            ,tooltip: {text: _('tree_expand')}
            ,handler: this.expand
        },{
            icon: iu+'arrow_up.png'
            ,cls: 'x-btn-icon'
            ,scope: this
            ,tooltip: {text: _('tree_collapse')}
            ,handler: this.collapse
        },'-',{
            icon: iu+'refresh.png'
            ,cls: 'x-btn-icon'
            ,scope: this
            ,tooltip: {text: _('tree_refresh')}
            ,handler: this.refresh
        }];
    }
	
	/**
	 * Add Items to the toolbar.
	 * @param {Ext.Toolbar} tb The toolbar to attach to.
	 * @param {Array} items An array of items to add.
	 */
	,_formatToolbar: function(a) {
		var l = a.length;
		for (var i = 0; i < l; i++) {
			
			if (a[i].handler) { 
				a[i].handler = eval(a[i].handler); 
			}
			Ext.applyIf(a[i],{
				scope: this
				,cls: 'x-btn-icon'
			});
		}
		return a;
	}
	
    /**
     * If set for the tree, displays a help dialog.
     * @abstract
     */
    ,help: function() {
        MODx.msg.alert(_('help'),_('help_not_yet'));
    }
});
Ext.reg('modx-tree',MODx.tree.Tree);