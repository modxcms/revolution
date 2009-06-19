MODx.TreeDrop = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-treedrop'
        ,ddGroup: 'modx-treedrop-dd'
    })
    MODx.TreeDrop.superclass.constructor.call(this,config);
    this.config = config;
    this.setup();
};
Ext.extend(MODx.TreeDrop,Ext.Component,{
    
    setup: function() {
        var ddTarget = this.config.target;
        var ddTargetEl = this.config.targetEl;
        
        this.targetEl = new Ext.dd.DropTarget(this.config.targetEl, {
            ddGroup: this.config.ddGroup
            
            ,notifyEnter: function(ddSource, e, data) {
                ddTarget.getEl().frame();
            }
            ,notifyDrop: function(ddSource, e, data) {
                var v = '';
                switch (data.node.attributes.type) {
                    case 'modResource': v = '[[~'+data.node.attributes.pk+']]'; break;
                    case 'snippet': v = '[['+data.node.attributes.name+']]'; break;
                    case 'chunk': v = '[[$'+data.node.attributes.name+']]'; break;
                    case 'tv': v = '[[*'+data.node.attributes.name+']]'; break;
                    default: return false; break;
                }
                MODx.insertAtCursor(ddTargetEl,v);
                return true;
            }
        });
    }    
});
Ext.reg('modx-treedrop',MODx.TreeDrop);

MODx.insertAtCursor = function(myField, myValue) {
    if (document.selection) { 
        myField.focus(); 
        sel = document.selection.createRange(); 
        sel.text = myValue; 
    } else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart; 
        var endPos = myField.selectionEnd; 
        myField.value = myField.value.substring(0, startPos)+ myValue+ myField.value.substring(endPos, myField.value.length); 
    } else { 
        myField.value += myValue; 
    }
};
