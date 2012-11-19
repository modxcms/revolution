MODx.grid.LocalProperty = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        dynProperty: 'xtype'
        ,dynField: 'value'
        ,propertyRecord: [{name: 'name'},{name: 'value'}]
        ,data: []
    });
    MODx.grid.LocalProperty.superclass.constructor.call(this,config);
    this.propRecord = Ext.data.Record.create(config.propertyRecord);
};
Ext.extend(MODx.grid.LocalProperty,MODx.grid.LocalGrid,{
    onCellDblClick: function(g,ri,ci,e) {
        var cm = this.getColumnModel();
        if (cm.getColumnId(ci) == this.config.dynField) {
            e.preventDefault();
            var r = this.getStore().getAt(ri).data;
            this.initEditor(cm,ci,ri,r);
            this.startEditing(ri,ci);
        }
    }
    
    ,initEditor: function(cm,ci,ri,r) {
        cm.setEditable(ci,true);
        var xtype = this.config.dynProperty;
        var o;
        if (r[xtype] == 'list') {
            o = this.createCombo(r);
        } else {
            var z = {};
            z[xtype] = r[xtype] || 'textfield';
            try {
                o = Ext.ComponentMgr.create(z);
            } catch (e) {
                z[xtype] = 'textfield';
                o = MODx.load(z);
            }
        }
        var ed = new Ext.grid.GridEditor(o);
        cm.setEditor(ci,ed);
        return ed;
    }
    
    ,renderDynField: function(v,md,rec,ri,ci,s,g) {
        var r = s.getAt(ri).data;
        var f,idx;
        var oz = v;
        var xtype = this.config.dynProperty;
        if (!r[xtype] || r[xtype] == 'combo-boolean') {
            f = MODx.grid.Grid.prototype.rendYesNo;
            oz = f(v == 1,md);
        } else if (r[xtype] === 'datefield') {
            f = Ext.util.Format.dateRenderer('Y-m-d');
            oz = f(v);
        } else if (r[xtype] === 'password') {
            f = this.rendPassword;
            oz = f(v,md);
        } else if (r[xtype].substr(0,5) == 'combo' || r[xtype] == 'list' || r[xtype].substr(0,9) == 'modx-combo') {
            var cm = g.getColumnModel();
            var ed = cm.getCellEditor(ci,ri);
            var cb;
            if (!ed) {
                r.xtype = r.xtype || 'combo-boolean';
                cb = this.createCombo(r);
                ed = new Ext.grid.GridEditor(cb);
                cm.setEditor(ci,ed);
            } else if (ed && ed.field && ed.field.xtype == 'modx-combo') {
                cb = ed.field;
            }
            if (r[xtype] != 'list') {
                f = Ext.util.Format.comboRenderer(ed.field);
                oz = f(v);
            } else if (cb) {
                idx = cb.getStore().find(cb.valueField,v);
                rec = cb.getStore().getAt(idx);
                if (rec) {
                    oz = rec.get(cb.displayField);
                } else {
                    oz = v;
                }
            }
        }
        return Ext.util.Format.htmlEncode(oz);
    }
    
    ,createCombo: function(p) {
        var obj;
        try {
            obj = Ext.ComponentMgr.create({ xtype: r.xtype, id: Ext.id() });
        } catch(e) {
            try {
                var flds = p.options;
                var data = [];
                for (var i=0;i<flds.length;i=i+1) {
                    data.push([flds[i].name,flds[i].value,flds[i].text]);
                }
                obj = MODx.load({
                    xtype: 'modx-combo'
                    ,store: new Ext.data.SimpleStore({
                        fields: ['d','v','t']
                        ,data: data
                    })
                    ,displayField: 'd'
                    ,valueField: 'v'
                    ,mode: 'local'
                    ,triggerAction: 'all'
                    ,editable: false
                    ,selectOnFocus: false
                    ,preventRender: true
                });
            } catch (e2) {
                obj = Ext.ComponentMgr.create({ xtype: 'combo-boolean', id: Ext.id() });
            }
        }
        return obj;
    }
});
Ext.reg('grid-local-property',MODx.grid.LocalProperty);