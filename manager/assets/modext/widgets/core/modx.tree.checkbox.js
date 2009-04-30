/**
 * A checkbox-driven tree
 * 
 * @class MODx.tree.CheckboxTree
 * @extends Ext.tree.TreePanel
 * @param {Object} config An object of config properties
 * @xtype tree-checkbox
 */
MODx.tree.CheckboxTree = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        rootVisible: false
        ,autoScroll: true
        ,autoHeight: true
        ,cls: 'modx-tree'
    });
    var tb = this.getToolbar();
    if (config.tbar && config.useDefaultToolbar) {
        tb.push('-');
        for (var i=0;i<config.tbar.length;i=i+1) {
            tb.push(config.tbar[i]);
        }
    } else if (config.tbar) {
        tb = config.tbar;
    }
    Ext.apply(config,{tbar: tb});
    this.config = config;
    this.setup();
};
Ext.extend(MODx.tree.CheckboxTree,Ext.tree.TreePanel,{
    menu: {}
    ,root: null
    
    ,setup: function() {
        var root;
        if (this.config.url) {            
            this.config.loader = new Ext.tree.TreeLoader({
                preloadChildren: false
                ,baseAttrs: {
                    uiProvider:MODx.tree.CheckboxNodeUI
                }
                ,dataUrl: this.config.url
            });
            root = new Ext.tree.AsyncTreeNode({
                text: this.config.rootName || ''
                ,draggable: false
                ,id: this.config.rootId || 'root'
            });
        } else {
            this.config.loader = new Ext.tree.TreeLoader({
                preloadChildren: true
                ,baseAttrs: {
                    uiProvider: MODx.tree.CheckboxNodeUI
                }
            });
            root = new Ext.tree.TreeNode({
                text: this.config.rootName || ''
                ,draggable: false
                ,id: this.config.rootId || 'root'
                ,children: this.config.data || []
            });
        }
        
        
        MODx.tree.CheckboxTree.superclass.constructor.call(this,this.config);
        this.addEvents({
            refresh: true
        });
        this.getLoader().doPreload(root);
        this.setRootNode(root);
        
        this.cm = new Ext.menu.Menu(Ext.id(),{});
        this.on('contextmenu',this._showContextMenu,this);
        
        this.treestateId = Ext.id();
        this.on('click',function(x) {
            Ext.state.Manager.set(this.treestateId,x.getPath());
        },this);        
    }
    
    ,encode: function(node) {
        if (!node) { node = this.getRootNode(); }
        var _encode = function(node) {
            var resultNode = {};
            var kids = node.childNodes;
            for (var i = 0;i < kids.length;i=i+1) {
                var n = kids[i];
                resultNode[n.id] = {
                    id: n.id
                    ,checked: n.ui.isChecked()
                    ,type: n.attributes.type || ''
                    ,data: n.attributes.data || {}
                    ,children: _encode(n)
                };
            }
            return resultNode;
        };
        var nodes = _encode(node);
        return Ext.encode(nodes);
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
    
    ,removeChildren: function(node) {
        while(node.firstChild){
             var c = node.firstChild;
             node.removeChild(c);
             c.destroy();
        }
    }
    
    ,loadRemoteData: function(data) {
        this.removeChildren(this.getRootNode());
        for (var c in data) {
            if (typeof data[c] === 'object') {
                this.getRootNode().appendChild(data[c]);
            }
        }
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
     * Refreshes the tree and fires refresh event.
     * @return {Boolean} True if successful.
     */
    ,refresh: function() {
        var treeState = Ext.state.Manager.get(this.treestateId);
        var root = this.getRootNode();
        if (this.config.url) { root.reload(); }
        if (treeState === undefined) {
            this.root.expand(null,null);
        } else {
            this.expandPath(treeState,null);
        }
        this.fireEvent('refresh',{root:this.getRootNode()});
        return true;
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
});
Ext.reg('tree-checkbox',MODx.tree.CheckboxTree);



MODx.tree.CheckboxNodeUI = Ext.extend(Ext.tree.TreeNodeUI, {
    onCheckChange :function(){
        MODx.tree.CheckboxNodeUI.superclass.onCheckChange.apply(this, arguments);
        var p;
        if((p = this.node.parentNode) && p.getUI().updateParent) {
            p.getUI().updateParent();
        }
    },
    toggleCheck :function(){
        var checked = MODx.tree.CheckboxNodeUI.superclass.toggleCheck.apply(this, arguments);
        this.updateChild(checked);
        return checked;
    },
    renderElements :function(n, a, targetNode, bulkRender){
        MODx.tree.CheckboxNodeUI.superclass.renderElements.apply(this, arguments);
        this.updateChild(this.node.attributes.checked);
    },
    updateParent :function(){
        var checked;
        this.node.eachChild(function(n){
            if(checked === undefined){
                checked = n.attributes.checked;
            }else if (checked !== n.attributes.checked) {
                checked = this.grayedValue;
                return false;
            }
        }, this);
        this.toggleCheck(checked);
    },
    updateChild:function(checked){
        if(this.checkbox && false){
            if(checked === true){
                Ext.fly(this.ctNode).replaceClass('x-tree-branch-unchecked', 'x-tree-branch-checked');
            } else if(checked === false){
                Ext.fly(this.ctNode).replaceClass('x-tree-branch-checked', 'x-tree-branch-unchecked');
            } else {
                Ext.fly(this.ctNode).removeClass(['x-tree-branch-checked', 'x-tree-branch-unchecked']);
            }
        }
    }
});


Ext.override(Ext.tree.TreeNodeUI, {
    grayedValue:null,
    onDisableChange :function(node, state){
        this.disabled = state;
        this[state ? 'addClass' :'removeClass']("x-tree-node-disabled");
    },
    initEvents :function(){
        this.node.on("move", this.onMove, this);
        this.addClass('x-tree-checkboxnode');
        if(this.node.disabled){
            this.addClass("x-tree-node-disabled");
        }
        if(this.node.hidden){
            this.hide();
        }
        var ot = this.node.getOwnerTree();
        var dd = ot.enableDD || ot.enableDrag || ot.enableDrop;
        if(dd && (!this.node.isRoot || ot.rootVisible)){
            Ext.dd.Registry.register(this.elNode, {
                node:this.node,
                handles:this.getDDHandles(),
                isHandle:false
            });
        }
    },
    onDblClick :function(e){
        e.preventDefault();
        if(this.disabled){
            return;
        }
        if(!this.animating && this.node.isExpandable()){
            this.node.toggle();
        }
        this.fireEvent("dblclick", this.node, e);
    },
    onCheckChange :function(){
        var checked = this.isChecked();
        this.node.attributes.checked = checked;
        this.fireEvent('checkchange', this.node, checked);
    },
    toggleCheck :function(checked){
        var cb = this.checkbox;
        if(!cb){
            return false;
        }
        if(checked === undefined){
            checked = this.isChecked() === false;
        }
        if(checked === true){
            Ext.fly(cb).replaceClass('x-tree-node-grayed', 'x-tree-node-checked');
        } else if(checked !== false){
            Ext.fly(cb).replaceClass('x-tree-node-checked', 'x-tree-node-grayed');
        } else {
            Ext.fly(cb).removeClass(['x-tree-node-checked', 'x-tree-node-grayed']);
        }
        this.onCheckChange();
        return checked;
    },
    onCheckboxClick:function() {
        if(!this.disabled){
            this.toggleCheck();
        }
    },
    onCheckboxOver:function() {
        this.addClass('x-tree-checkbox-over');
    },
    onCheckboxOut:function() {
        this.removeClass('x-tree-checkbox-over');
    },
    onCheckboxDown:function() {
        this.addClass('x-tree-checkbox-down');
    },
    onCheckboxUp:function() {
        this.removeClass('x-tree-checkbox-down');
    },
    renderElements :function(n, a, targetNode, bulkRender){
        this.indentMarkup = n.parentNode ? n.parentNode.ui.getChildIndent() :'';
        var cb = a.checked !== undefined;
        var href = a.href ? a.href :Ext.isGecko ? "" :"#";
        var buf = ['<li class="x-tree-node"><div ext:tree-node-id="',n.id,'" class="x-tree-node-el x-tree-node-leaf x-unselectable ', a.cls,'" unselectable="on">',
            '<span class="x-tree-node-indent">',this.indentMarkup,"</span>",
            '<img src="', this.emptyIcon, '" class="x-tree-ec-icon x-tree-elbow" />',
            '<img src="', a.icon || this.emptyIcon, '" class="x-tree-node-icon',(a.icon ? " x-tree-node-inline-icon" :""),(a.iconCls ? " "+a.iconCls :""),'" unselectable="on" />',
            cb ? ('<img src="'+this.emptyIcon+'" class="x-tree-checkbox'+(a.checked === true ? ' x-tree-node-checked' :(a.checked !== false ? ' x-tree-node-grayed' :''))+'" />') :'',
            '<a hidefocus="on" class="x-tree-node-anchor" href="',href,'" tabIndex="1" ',(a.hrefTarget ? ' target="'+a.hrefTarget+'"' :""), '>',
            '<span unselectable="on">',n.text,'</span>',
            '</a>',
            '</div><ul class="x-tree-node-ct" style="display:none;"></ul>',
            "</li>"].join('');
        var nel;
        if(bulkRender !== true && n.nextSibling && (nel = n.nextSibling.ui.getEl())){
            this.wrap = Ext.DomHelper.insertHtml("beforeBegin", nel, buf);
        }else{
            this.wrap = Ext.DomHelper.insertHtml("beforeEnd", targetNode, buf);
        }
        this.elNode = this.wrap.childNodes[0];
        this.ctNode = this.wrap.childNodes[1];
        var cs = this.elNode.childNodes;
        this.indentNode = cs[0];
        this.ecNode = cs[1];
        this.iconNode = cs[2];
        var index = 3;
        if(cb){
            this.checkbox = cs[3];
            index=index+1;
        }
        this.anchor = cs[index];
        this.textNode = cs[index].firstChild;
        var z;
        if (a.description && this.textNode) {
            z = new Ext.ToolTip({
                target: this.textNode
                ,html: a.description
            });
        }
    },
    isChecked :function(){
        return this.checkbox
            ? (Ext.fly(this.checkbox).hasClass('x-tree-node-checked')
                ? true
                :Ext.fly(this.checkbox).hasClass('x-tree-node-grayed')
                    ? this.grayedValue
                    :false)
            :false;
    }
});
Ext.override(Ext.tree.TreeEventModel, {
    initEvents :function(){
        var el = this.tree.getTreeEl();
        el.on('click', this.delegateClick, this);
        if(this.tree.trackMouseOver !== false){
            el.on('mouseover', this.delegateOver, this);
            el.on('mouseout', this.delegateOut, this);
        }
        el.on('mousedown', this.delegateDown, this);
        el.on('mouseup', this.delegateUp, this);
        el.on('dblclick', this.delegateDblClick, this);
        el.on('contextmenu', this.delegateContextMenu, this);
    },
    delegateOver :function(e, t){
        if(!this.beforeEvent(e)){
            return;
        }
        if(this.lastEcOver){
            this.onIconOut(e, this.lastEcOver);
            delete this.lastEcOver;
        }
        if(this.lastCbOver){
            this.onCheckboxOut(e, this.lastCbOver);
            delete this.lastCbOver;
        }
        if(e.getTarget('.x-tree-ec-icon', 1)){
            this.lastEcOver = this.getNode(e);
            this.onIconOver(e, this.lastEcOver);
        }
        else if(e.getTarget('.x-tree-checkbox', 1)){
            this.lastCbOver = this.getNode(e);
            this.onCheckboxOver(e, this.lastCbOver);
        }
        t = this.getNodeTarget(e);
        if (t) {
            this.onNodeOver(e, this.getNode(e));
        }
    },
    delegateOut :function(e, t){
        if(!this.beforeEvent(e)){
            return;
        }
        var n;
        if(e.getTarget('.x-tree-ec-icon', 1)){
            n = this.getNode(e);
            this.onIconOut(e, n);
            if(n === this.lastEcOver){
                delete this.lastEcOver;
            }
        }
        else if(e.getTarget('.x-tree-checkbox', 1)){
            n = this.getNode(e);
            this.onCheckboxOut(e, n);
            if(n === this.lastCbOver){
                delete this.lastCbOver;
            }
        }
        t = this.getNodeTarget(e);
        if(t && !e.within(t, true)){
            this.onNodeOut(e, this.getNode(e));
        }
    },
    delegateDown :function(e, t){
        if(!this.beforeEvent(e)){
            return;
        }
        if(e.getTarget('.x-tree-checkbox', 1)){
            this.onCheckboxDown(e, this.getNode(e));
        }
    },
    delegateUp :function(e, t){
        if(!this.beforeEvent(e)){
            return;
        }
        if(e.getTarget('.x-tree-checkbox', 1)){
            this.onCheckboxUp(e, this.getNode(e));
        }
    },
    delegateOut :function(e, t){
        if(!this.beforeEvent(e)){
            return;
        }
        var n;
        if(e.getTarget('.x-tree-ec-icon', 1)){
            n = this.getNode(e);
            this.onIconOut(e, n);
            if(n === this.lastEcOver){
                delete this.lastEcOver;
            }
        }
        else if(e.getTarget('.x-tree-checkbox', 1)){
            n = this.getNode(e);
            this.onCheckboxOut(e, n);
            if(n === this.lastCbOver){
                delete this.lastCbOver;
            }
        }
        if((t = this.getNodeTarget(e)) && !e.within(t, true)){
            this.onNodeOut(e, this.getNode(e));
        }
    },
    delegateClick :function(e, t){
        if(!this.beforeEvent(e)){
            return;
        }
        if(e.getTarget('.x-tree-checkbox', 1)){
            this.onCheckboxClick(e, this.getNode(e));
        }
        else if(e.getTarget('.x-tree-ec-icon', 1)){
            this.onIconClick(e, this.getNode(e));
        }
        else if(this.getNodeTarget(e)){
            this.onNodeClick(e, this.getNode(e));
        }
    },
    onCheckboxClick :function(e, node){
        node.ui.onCheckboxClick();
    },
    onCheckboxOver :function(e, node){
        node.ui.onCheckboxOver();
    },
    onCheckboxOut :function(e, node){
        node.ui.onCheckboxOut();
    },
    onCheckboxDown :function(e, node){
        node.ui.onCheckboxDown();
    },
    onCheckboxUp :function(e, node){
        node.ui.onCheckboxUp();
    }
});