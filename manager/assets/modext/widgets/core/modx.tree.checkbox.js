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
    
    ,removeNode: function(id) {
        var node = this.getNodeById(id);
        if (node) {
            node.remove(); 
        }
    }
    
    ,removeActiveNode: function() {
        this.cm.activeNode.remove();
    }
    
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
    
    ,expand: function() {
        if (this.root) {
            this.root.expand();
            this.root.expandChildNodes();
        }
    }
    
    ,collapse: function() {
        if (this.root) {
            this.root.collapseChildNodes();
            this.root.collapse();
        }
    }
    
    ,getToolbar: function() {
        var iu = MODx.config.template_url+'images/restyle/icons/';
        return [{
            icon: iu+'arrow_down.png'
            ,cls: 'x-btn-icon arrow_down'
            ,scope: this
            ,tooltip: {text: _('tree_expand')}
            ,handler: this.expand
        },{
            icon: iu+'arrow_up.png'
            ,cls: 'x-btn-icon arrow_up'
            ,scope: this
            ,tooltip: {text: _('tree_collapse')}
            ,handler: this.collapse
        },{
            icon: iu+'refresh.png'
            ,cls: 'x-btn-icon refresh'
            ,scope: this
            ,tooltip: {text: _('tree_refresh')}
            ,handler: this.refresh
        }];
    }
});
Ext.reg('tree-checkbox',MODx.tree.CheckboxTree);

/* checkbox node ui */
MODx.tree.CheckboxNodeUI=Ext.extend(Ext.tree.TreeNodeUI,{onCheckChange:function(){MODx.tree.CheckboxNodeUI.superclass.onCheckChange.apply(this,arguments);var p;if((p=this.node.parentNode)&&p.getUI().updateParent){p.getUI().updateParent()}},toggleCheck:function(){var a=MODx.tree.CheckboxNodeUI.superclass.toggleCheck.apply(this,arguments);this.updateChild(a);return a},renderElements:function(n,a,b,c){MODx.tree.CheckboxNodeUI.superclass.renderElements.apply(this,arguments);this.updateChild(this.node.attributes.checked)},updateParent:function(){var a;this.node.eachChild(function(n){if(a===undefined){a=n.attributes.checked}else if(a!==n.attributes.checked){a=this.grayedValue;return false}},this);this.toggleCheck(a)},updateChild:function(a){if(this.checkbox&&false){if(a===true){Ext.fly(this.ctNode).replaceClass('x-tree-branch-unchecked','x-tree-branch-checked')}else if(a===false){Ext.fly(this.ctNode).replaceClass('x-tree-branch-checked','x-tree-branch-unchecked')}else{Ext.fly(this.ctNode).removeClass(['x-tree-branch-checked','x-tree-branch-unchecked'])}}}});Ext.override(Ext.tree.TreeNodeUI,{grayedValue:null,onDisableChange:function(a,b){this.disabled=b;this[b?'addClass':'removeClass']("x-tree-node-disabled")},initEvents:function(){this.node.on("move",this.onMove,this);this.addClass('x-tree-checkboxnode');if(this.node.disabled){this.addClass("x-tree-node-disabled")}if(this.node.hidden){this.hide()}var a=this.node.getOwnerTree();var b=a.enableDD||a.enableDrag||a.enableDrop;if(b&&(!this.node.isRoot||a.rootVisible)){Ext.dd.Registry.register(this.elNode,{node:this.node,handles:this.getDDHandles(),isHandle:false})}},onDblClick:function(e){e.preventDefault();if(this.disabled){return}if(!this.animating&&this.node.isExpandable()){this.node.toggle()}this.fireEvent("dblclick",this.node,e)},onCheckChange:function(){var a=this.isChecked();this.node.attributes.checked=a;this.fireEvent('checkchange',this.node,a)},toggleCheck:function(a){var b=this.checkbox;if(!b){return false}if(a===undefined){a=this.isChecked()===false}if(a===true){Ext.fly(b).replaceClass('x-tree-node-grayed','x-tree-node-checked')}else if(a!==false){Ext.fly(b).replaceClass('x-tree-node-checked','x-tree-node-grayed')}else{Ext.fly(b).removeClass(['x-tree-node-checked','x-tree-node-grayed'])}this.onCheckChange();return a},onCheckboxClick:function(){if(!this.disabled){this.toggleCheck()}},onCheckboxOver:function(){this.addClass('x-tree-checkbox-over')},onCheckboxOut:function(){this.removeClass('x-tree-checkbox-over')},onCheckboxDown:function(){this.addClass('x-tree-checkbox-down')},onCheckboxUp:function(){this.removeClass('x-tree-checkbox-down')},renderElements:function(n,a,b,c){this.indentMarkup=n.parentNode?n.parentNode.ui.getChildIndent():'';var d=a.checked!==undefined;var e=a.href?a.href:Ext.isGecko?"":"#";var f=['<li class="x-tree-node"><div ext:tree-node-id="',n.id,'" class="x-tree-node-el x-tree-node-leaf x-unselectable ',a.cls,'" unselectable="on">','<span class="x-tree-node-indent">',this.indentMarkup,"</span>",'<img src="',this.emptyIcon,'" class="x-tree-ec-icon x-tree-elbow" />','<img src="',a.icon||this.emptyIcon,'" class="x-tree-node-icon',(a.icon?" x-tree-node-inline-icon":""),(a.iconCls?" "+a.iconCls:""),'" unselectable="on" />',d?('<img src="'+this.emptyIcon+'" class="x-tree-checkbox'+(a.checked===true?' x-tree-node-checked':(a.checked!==false?' x-tree-node-grayed':''))+'" />'):'','<a hidefocus="on" class="x-tree-node-anchor" href="',e,'" tabIndex="1" ',(a.hrefTarget?' target="'+a.hrefTarget+'"':""),'>','<span unselectable="on">',n.text,'</span>','</a>','</div><ul class="x-tree-node-ct" style="display:none;"></ul>',"</li>"].join('');var g;if(c!==true&&n.nextSibling&&(g=n.nextSibling.ui.getEl())){this.wrap=Ext.DomHelper.insertHtml("beforeBegin",g,f)}else{this.wrap=Ext.DomHelper.insertHtml("beforeEnd",b,f)}this.elNode=this.wrap.childNodes[0];this.ctNode=this.wrap.childNodes[1];var h=this.elNode.childNodes;this.indentNode=h[0];this.ecNode=h[1];this.iconNode=h[2];var i=3;if(d){this.checkbox=h[3];i=i+1}this.anchor=h[i];this.textNode=h[i].firstChild;var z;if(a.description&&this.textNode){z=new Ext.ToolTip({target:this.textNode,html:a.description})}},isChecked:function(){return this.checkbox?(Ext.fly(this.checkbox).hasClass('x-tree-node-checked')?true:Ext.fly(this.checkbox).hasClass('x-tree-node-grayed')?this.grayedValue:false):false}});Ext.override(Ext.tree.TreeEventModel,{initEvents:function(){var a=this.tree.getTreeEl();a.on('click',this.delegateClick,this);if(this.tree.trackMouseOver!==false){a.on('mouseover',this.delegateOver,this);a.on('mouseout',this.delegateOut,this)}a.on('mousedown',this.delegateDown,this);a.on('mouseup',this.delegateUp,this);a.on('dblclick',this.delegateDblClick,this);a.on('contextmenu',this.delegateContextMenu,this)},delegateOver:function(e,t){if(!this.beforeEvent(e)){return}if(this.lastEcOver){this.onIconOut(e,this.lastEcOver);delete this.lastEcOver}if(this.lastCbOver){this.onCheckboxOut(e,this.lastCbOver);delete this.lastCbOver}if(e.getTarget('.x-tree-ec-icon',1)){this.lastEcOver=this.getNode(e);this.onIconOver(e,this.lastEcOver)}else if(e.getTarget('.x-tree-checkbox',1)){this.lastCbOver=this.getNode(e);this.onCheckboxOver(e,this.lastCbOver)}t=this.getNodeTarget(e);if(t){this.onNodeOver(e,this.getNode(e))}},delegateOut:function(e,t){if(!this.beforeEvent(e)){return}var n;if(e.getTarget('.x-tree-ec-icon',1)){n=this.getNode(e);this.onIconOut(e,n);if(n===this.lastEcOver){delete this.lastEcOver}}else if(e.getTarget('.x-tree-checkbox',1)){n=this.getNode(e);this.onCheckboxOut(e,n);if(n===this.lastCbOver){delete this.lastCbOver}}t=this.getNodeTarget(e);if(t&&!e.within(t,true)){this.onNodeOut(e,this.getNode(e))}},delegateDown:function(e,t){if(!this.beforeEvent(e)){return}if(e.getTarget('.x-tree-checkbox',1)){this.onCheckboxDown(e,this.getNode(e))}},delegateUp:function(e,t){if(!this.beforeEvent(e)){return}if(e.getTarget('.x-tree-checkbox',1)){this.onCheckboxUp(e,this.getNode(e))}},delegateOut:function(e,t){if(!this.beforeEvent(e)){return}var n;if(e.getTarget('.x-tree-ec-icon',1)){n=this.getNode(e);this.onIconOut(e,n);if(n===this.lastEcOver){delete this.lastEcOver}}else if(e.getTarget('.x-tree-checkbox',1)){n=this.getNode(e);this.onCheckboxOut(e,n);if(n===this.lastCbOver){delete this.lastCbOver}}if((t=this.getNodeTarget(e))&&!e.within(t,true)){this.onNodeOut(e,this.getNode(e))}},delegateClick:function(e,t){if(!this.beforeEvent(e)){return}if(e.getTarget('.x-tree-checkbox',1)){this.onCheckboxClick(e,this.getNode(e))}else if(e.getTarget('.x-tree-ec-icon',1)){this.onIconClick(e,this.getNode(e))}else if(this.getNodeTarget(e)){this.onNodeClick(e,this.getNode(e))}},onCheckboxClick:function(e,a){a.ui.onCheckboxClick()},onCheckboxOver:function(e,a){a.ui.onCheckboxOver()},onCheckboxOut:function(e,a){a.ui.onCheckboxOut()},onCheckboxDown:function(e,a){a.ui.onCheckboxDown()},onCheckboxUp:function(e,a){a.ui.onCheckboxUp()}});