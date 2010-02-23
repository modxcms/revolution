
MODx.rte.Selection = function(cmp) {
    MODx.rte.Selection.superclass.constructor.call(this);
    this.cmp = cmp;
};
Ext.extend(MODx.rte.Selection,Ext.util.Observable,{
    'get': function() {
        return this.getBlocklevelElement(this.getNode());
    }
        
    ,getSelection : function() {
        win = this.cmp.getWin();
        return win.getSelection ? win.getSelection() : win.document.selection;
    }

    ,getRange : function() {
        var win = this.cmp.getWin(), s, r;

        try {
            if (s = this.getSelection())
                r = s.rangeCount > 0 ? s.getRangeAt(0) : (s.createRange ? s.createRange() : win.document.createRange());
        } catch (ex) {  }

        // No range found then create an empty one
        // This can occur when the editor is placed in a hidden container element on Gecko
        // Or on IE when there was an exception
        if (!r)
            r = Exi.isIE ? win.document.body.createTextRange() : win.document.createRange();

        return r;
    }  

    ,getNode: function(){
        var elem, e, r = this.getRange(), s = this.getSelection();
        if (!Ext.isIE) {
            // Range maybe lost after the editor is made visible again
            if (!r)
                return this.cmp.getDoc().dom.getRoot();

            e = r.commonAncestorContainer;
            // Handle selection a image or other control like element such as anchors
            if (!r.collapsed) {
                // If the anchor node is a element instead of a text node then return this element
                if (Ext.isWebKit && s.anchorNode && s.anchorNode.nodeType == 1)
                    return s.anchorNode.childNodes[s.anchorOffset]; 

                if (r.startContainer == r.endContainer) {
                    if (r.startOffset - r.endOffset < 2) {
                        if (r.startContainer.hasChildNodes())
                            e = r.startContainer.childNodes[r.startOffset];
                    }
                }
            }
            elem = e.parentNode;
        } else {
            if (r.item)
                elem = r.item(0);
            else
                elem = r.parentElement();
        }
        return Ext.fly(elem);
    }

    ,getBlocklevelElement: function(n){
        if(n){
            if (!n.dom) {
                n = Ext.fly(n);
                n = n;
            }
            if (!n) return null;
            
            if (/^(P|DIV|H[1-6]|ADDRESS|BODY|BLOCKQUOTE|PRE)$/.test(n.dom.nodeName)){
                return n;
            } else {
                var pn = null;
                try {
                    pn = n.findParentNode();
                } catch (e) {}
                
                return pn ? this.getBlocklevelElement(pn) : n;
            }
        }
        return null;
    }
});