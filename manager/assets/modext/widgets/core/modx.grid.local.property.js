/**
 * @class MODx.grid.LocalProperty
 * @extends MODx.grid.LocalGrid
 * @param {Object} config An object of configuration properties
 * @xtype grid-local-property
 */
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
    /**
     * Dynamically change the editor for the row via its xtype property.
     * @param {MODx.grid.SettingsGrid} g The grid object
     * @param {Integer} ri The row index
     * @param {Integer} ci The column index
     * @param {Ext.EventObject} e The event object that occurred
     */
    onCellDblClick: function(g,ri,ci,e) {
        var cm = this.getColumnModel();
        if (cm.getColumnId(ci) != this.config.dynField) {
            this.onCellDblClick(g,ri,ci,e);
        } else {
            e.preventDefault();
            var r = this.getStore().getAt(ri).data;
            this.initEditor(cm,ci,ri,r);
            this.startEditing(ri,ci);
        }
    }
    
    /**
     * Initializes the editor for the cell
     * @param {Ext.grid.ColumnModel} cm The column model for the grid
     * @param {Integer} ri The row index
     * @param {Integer} ci The column index
     * @param {Object} r The data record for the cell
     */
    ,initEditor: function(cm,ci,ri,r) {
        cm.setEditable(ci,true);
        var xtype = this.config.dynProperty;
        if (r[xtype] == 'list') {
            var o = this.createCombo(r);
        } else {
            var z = {};
            z[xtype] = r[xtype] || 'textfield';
            var o = Ext.ComponentMgr.create(z);
        }
        var ed = new Ext.grid.GridEditor(o);
        cm.setEditor(ci,ed);
        return ed;
    }
    
    /**
     * A custom renderer that renders the custom xtype editor
     * @param {String} v The raw value
     * @param {Object} md The metadata for the cell
     * @param {Object} rec The store data record
     * @param {Integer} ri The row index
     * @param {Integer} ci The column index
     * @param {Ext.data.Store} s The store for the grid
     * @param {MODx.grid.SettingsGrid} g The grid object 
     */
    ,renderDynField: function(v,md,rec,ri,ci,s,g) {
        var r = s.getAt(ri).data;
        var f;
        var xtype = this.config.dynProperty;
        if (!r[xtype] || r[xtype] == 'combo-boolean') {
            f = MODx.grid.Grid.prototype.rendYesNo;
            return f(v == 1 ? true : false,md);
        } else if (r[xtype] === 'datefield') {
            f = Ext.util.Format.dateRenderer('Y-m-d');
            return f(v);
        } else if (r[xtype].substr(0,5) == 'combo' || r[xtype] == 'list' || r[xtype].substr(0,9) == 'modx-combo') {
            var cm = g.getColumnModel();
            var ed = cm.getCellEditor(ci,ri);
            if (!ed) {
                r.xtype = r.xtype || 'combo-boolean';
                var o = this.createCombo(r);
                ed = new Ext.grid.GridEditor(o);
                cm.setEditor(ci,ed);
            }
            f = MODx.combo.Renderer(ed.field);
            return f(v);
        }
        return v;
    }
    
    ,createCombo: function(p) {
        var obj;
        try {
            obj = Ext.ComponentMgr.create({ xtype: r.xtype });
        } catch(e) {
            try {
                var flds = p.options;
                var data = [];
                for (var i=0;i<flds.length;i=i+1) {
                    data.push([flds[i].name,flds[i].value]);
                }
                obj = MODx.load({
                    xtype: 'modx-combo'
                    ,store: new Ext.data.SimpleStore({
                        fields: ['d','v']
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
                obj = Ext.ComponentMgr.create({ xtype: 'combo-boolean' });
            }
        }
        return obj;
    }
});
Ext.reg('grid-local-property',MODx.grid.LocalProperty);