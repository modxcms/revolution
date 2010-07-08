Ext.ns('MODx.orm');
MODx.orm.Tree = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        height: 300
        ,width: 400
        ,useArrows:true
        ,autoScroll:true
        ,animate:true
        ,enableDD: false
        ,border: false
        ,containerScroll: true
        ,rootVisible: false
        ,root: new Ext.tree.AsyncTreeNode({
            expanded: true
            ,children: config.data
            ,listeners: {
                'load': {fn:function() {
                    this.expandAll();
                },scope:this}
            }
        })
    });
    MODx.orm.Tree.superclass.constructor.call(this,config);
    this.config = config;
    this.on('click',this.onClick,this);
};
Ext.extend(MODx.orm.Tree,Ext.tree.TreePanel,{
    encode: function(node) {
        if (!node) { node = this.getRootNode(); }
        var _encode = function(node) {
            var resultNode = {};
            var kids = node.childNodes;
            for (var i = 0;i < kids.length;i=i+1) {
                var n = kids[i];
                var c = _encode(n);
                if (n.attributes.value) {
                    resultNode[n.id] = n.attributes.value;
                } else {
                    resultNode[n.id] = c;
                }
            }
            return resultNode;
        };
        var nodes = _encode(node);
        return Ext.encode(nodes);
    }
    
    ,onClick: function(n) {
        var vs = n.attributes;
        if (vs.value) {
            var f = Ext.getCmp(this.config.formPanel).getForm();
            f.findField(this.config.prefix+'_id').setValue(vs.id);
            f.findField(this.config.prefix+'_name').setValue(vs.name);
            f.findField(this.config.prefix+'_value').setValue(vs.value);
        }
    }
});
Ext.reg('modx-orm-tree',MODx.orm.Tree);


MODx.orm.Form = function(config) {
    Ext.applyIf(config,{
        layout: 'form'
        ,xtype: 'fieldset'
        ,labelWidth: 150
        ,autoHeight: true
        ,border: false
        ,bodyStyle: 'padding: 15px 0;'
        ,defaults: { msgTarget: 'side', border: false }
        ,buttonAlign: 'center'
        ,items: [{
            xtype: 'textfield'
            ,name: config.prefix+'_name'
            ,fieldLabel: _('name')
            ,anchor: '95%'
        },{
            xtype: 'textfield'
            ,name: config.prefix+'_value'
            ,fieldLabel: _('value')
            ,anchor: '95%'
        },{
            xtype: 'hidden'
            ,name: config.prefix+'_id'
        }]
        ,buttons: [{
            text: _('set')
            ,handler: this.saveProperty
            ,scope: this
        }]
    });
    MODx.orm.Form.superclass.constructor.call(this,config);
    this.config = config;
}
Ext.extend(MODx.orm.Form,Ext.Panel,{
    saveProperty: function() {
        var fp = Ext.getCmp(this.config.formPanel);
        var t = Ext.getCmp(this.config.treePanel);
        if (!fp || !t) return false;
        var f = fp.getForm();
        var n = t.getSelectionModel().getSelectedNode();

        var txt = f.findField(this.config.prefix+'_name').getValue();
        var val = f.findField(this.config.prefix+'_value').getValue();
        n.attributes.id = f.findField(this.config.prefix+'_id').getValue();
        n.attributes.text = txt;
        n.attributes.value = val;
        n.setText(txt+' - <i>'+Ext.util.Format.ellipsis(val,33)+'</i>');
        fp.markDirty();
        return true;
    }
});
Ext.reg('modx-orm-form',MODx.orm.Form);
