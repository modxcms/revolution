Ext.namespace('MODx.tree');
/**
 * Allows for using FontAwesome icons when rendering tree nodes
 *
 * @class MODx.tree.TreeNodeUI
 * @extends Ext.tree.TreeNodeUI
 * @constructor
 * @param {Object} node The node to be rendered
 */
MODx.tree.TreeNodeUI = function(node){
    MODx.tree.TreeNodeUI.superclass.constructor.apply(this,arguments);
};
Ext.extend(MODx.tree.TreeNodeUI, Ext.tree.TreeNodeUI, {

    /**
     * Override render function to add FontAwesome icons
     *
     * @param n
     * @param a
     * @param targetNode
     * @param bulkRender
     */
    renderElements : function(n, a, targetNode, bulkRender){
        // add some indent caching, this helps performance when rendering a large tree
        this.indentMarkup = n.parentNode ? n.parentNode.ui.getChildIndent() : '';
        var cb = Ext.isBoolean(a.checked),
            nel,
            href = this.getHref(a.href),
            buf = ['<li class="x-tree-node"><div ext:tree-node-id="',n.id,'" class="x-tree-node-el x-tree-node-leaf x-unselectable ', a.cls,'" unselectable="on">',
                '<span class="x-tree-node-indent">',this.indentMarkup,"</span>",
                '<img alt="" src="', this.emptyIcon, '" class="x-tree-ec-icon x-tree-elbow" />',
                '<i class="icon-large',(a.icon ? " x-tree-node-inline-icon" : ""),(a.iconCls ? " "+a.iconCls : ""),'" unselectable="on"></i>',
                cb ? ('<input class="x-tree-node-cb" type="checkbox" ' + (a.checked ? 'checked="checked" />' : '/>')) : '',
                '<a hidefocus="on" class="x-tree-node-anchor" href="',href,'" tabIndex="1" ',
                a.hrefTarget ? ' target="'+a.hrefTarget+'"' : "", '><span unselectable="on">',n.text,"</span></a></div>",
                '<ul class="x-tree-node-ct" style="display:none;"></ul>',
                "</li>"].join('');

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
            // fix for IE6
            this.checkbox.defaultChecked = this.checkbox.checked;
            index++;
        }
        this.anchor = cs[index];
        this.textNode = cs[index].firstChild;
    },


});