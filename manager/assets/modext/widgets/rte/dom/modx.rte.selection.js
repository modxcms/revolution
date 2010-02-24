
MODx.rte.Selection = function(cmp) {
    MODx.rte.Selection.superclass.constructor.call(this);    
    this.cmp = cmp;
    this.input = cmp.doc.body;
    if (!this.input || !this.input.nodeName) {
        this.isTA = false;
    } else {
        this.isTA = this.input.nodeName.toLowerCase() == "textarea";
    }
    
    var d = document, o = d.createElement("input");
    this.selStandard = "selectionStart" in o;
    this.selSupported = this.selStandard || (o = d.selection) && !!o.createRange;
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
            if (s = this.getSelection()) {
                var rg = s.createRange ? s.createRange() : win.document.createRange();
                rg.setStart(s.anchorNode,s.anchorOffset);
                rg.setEnd(s.focusNode,s.focusOffset);
                
                r = s.rangeCount > 0 && s.getRangeAt ? s.getRangeAt(0) : (rg);
            }
        } catch (ex) {  }

        /* No range found then create an empty one
         This can occur when the editor is placed in a hidden container element on Gecko
         Or on IE when there was an exception */
        if (!r) {
            r = Ext.isIE ? win.document.body.createTextRange() : win.document.createRange();
        }

        return r;
    }  

    ,getNode: function(){
        var elem, e, r = this.getRange(), s = this.getSelection();
        if (!Ext.isIE) {
            /* Range maybe lost after the editor is made visible again */
            if (!r) {
                return this.cmp.getDoc().dom.getRoot();
            }

            e = r.commonAncestorContainer;
            /* Handle selection a image or other control like element such as anchors */
            if (!r.collapsed) {
                /* If the anchor node is a element instead of a text node then return this element */
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
            elem = r.item ? r.item(0) : r.parentElement();
        }
        return Ext.fly(elem);
    }

    /* get a block-level element for a node */
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
    
    /* get the first node of specified tag in a selection */
    ,getFirstTagInSelection: function(tag,tags) {
        if (!tag) return {};
        if (!tags) tags = this.getTagsInSelection();
        
        for (var i=0;i<tags.length;i++) {
            if (tags[i].nodeName == tag) {
                return tags[i];
            }
        }
        return {};
    }
    
    /* get all attributes for a tag in an object */
    ,getTagAttributes: function(tag) {
        if (!tag) return {};
        
        var attr = {};
        for (var i=0;i<tag.attributes.length;i++) {
            attr[tag.attributes[i].name] = tag.attributes[i].value;
        }
        return attr;
    }
    
    /* gets all tags (or tag names) in a selection */
    ,getTagsInSelection: function(namesOnly) {
        namesOnly = namesOnly || false;
        
        var tags = [];
        var nodes=this.getElementsFromSelection();
        if (nodes) {
            for(var ii=0; ii<nodes.length; ii++) {
                if (namesOnly) {
                    tags.push(nodes[ii].nodeName);
                } else {
                    tags.push(nodes[ii]);
                }
            }
        }
        return tags;
    }
    
    /* get all DOM elements from browsers selection */
    ,getElementsFromSelection: function(){ 
        var nodes=null, candidates=[], children, el, parent, rng; 
        //Main 
        rng = this.getRange(); 
        if (rng) {
            parent=this.getCommonAncestor(rng); 
            if(parent) { 
                /* adjust from text node to element, if needed */ 
                while(parent.nodeType!=1) parent=parent.parentNode; 
                /* obtain all candidates from parent (excluded) 
                   up to BODY (included) */
                if(parent.nodeName.toLowerCase()!="body") { 
                    el=parent; 
                    do { 
                        el=el.parentNode; 
                        candidates[candidates.length]=el; 
                    } while(el.nodeName.toLowerCase()!="body"); 
                }
                /* obtain all candidates down to all children */ 
                children=parent.all||parent.getElementsByTagName("*"); 
                for(var j=0; j<children.length; j++) {
                    candidates[candidates.length]=children[j];
                }
                /* proceed - keep element when range touches it */
                nodes=[parent]; 
                for(var ii=0, r2; ii<candidates.length; ii++) { 
                    r2 = this.createRangeFromElement(candidates[ii]); 
                    if(r2 && this.rangeContact(rng, r2)) { 
                        nodes[nodes.length]=candidates[ii];
                    }
                } 
            } 
        } 
        return nodes; 
    }
    
    /* get the common ancestor of a range */
    ,getCommonAncestor: function(rng) { 
        return rng.parentElement ? rng.parentElement() : rng.commonAncestorContainer; 
    }
    
    /* get the contact points of a range */
    ,rangeContact: function(r1, r2) { 
        var p=null; 
        if (r1.compareEndPoints) { 
            p = { 
                method:"compareEndPoints" 
                ,StartToStart:"StartToStart" 
                ,StartToEnd:"StartToEnd"
                ,EndToEnd:"EndToEnd"
                ,EndToStart:"EndToStart"
            };
        } else if (r1.compareBoundaryPoints) { 
            p = { 
                method:"compareBoundaryPoints" 
                ,StartToStart:0 
                ,StartToEnd:1
                ,EndToEnd:2 
                ,EndToStart:3 
            };
        }
        return p && !( 
            r2[p.method](p.StartToStart, r1)==1 && 
            r2[p.method](p.EndToEnd, r1)==1 && 
            r2[p.method](p.StartToEnd, r1)==1 && 
            r2[p.method](p.EndToStart, r1)==1 
            || 
            r2[p.method](p.StartToStart, r1)==-1 && 
            r2[p.method](p.EndToEnd, r1)==-1 && 
            r2[p.method](p.StartToEnd, r1)==-1 && 
            r2[p.method](p.EndToStart, r1)==-1 
        );
    }
    
    /* create a range from a DOM element */
    ,createRangeFromElement: function(el) { 
        var rng=null; 
        if(document.body.createTextRange) { 
            rng=document.body.createTextRange(); 
            rng.moveToElementText(el); 
        } else if(document.createRange) { 
            rng=document.createRange(); 
            rng.selectNodeContents(el); 
        }
        return rng; 
    } 
});
