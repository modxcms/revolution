MODx.grid.LocalProperty = function(config = {}) {
    Ext.applyIf(config, {
        dynProperty: 'xtype',
        dynField: 'value',
        propertyRecord: [
            { name: 'name' },
            { name: 'value' }
        ],
        data: []
    });
    MODx.grid.LocalProperty.superclass.constructor.call(this, config);
    this.propRecord = Ext.data.Record.create(config.propertyRecord);
};
Ext.extend(MODx.grid.LocalProperty, MODx.grid.LocalGrid, {
    onCellDblClick: function(grid, rowIndex, colIndex, e) {
        const colModel = this.getColumnModel();
        if (colModel.getColumnId(colIndex) === this.config.dynField) {
            e.preventDefault();
            const record = this.getStore().getAt(rowIndex).data;
            this.initEditor(colModel, colIndex, rowIndex, record);
            this.startEditing(rowIndex, colIndex);
        }
    },

    initEditor: function(colModel, colIndex, rowIndex, record) {
        colModel.setEditable(colIndex, true);
        const fieldType = record[this.config.dynProperty];
        let fieldCmp;
        if (fieldType === 'list') {
            fieldCmp = this.createCombo(record);
        } else {
            const config = {};
            config[this.config.dynProperty] = fieldType || 'textfield';
            try {
                fieldCmp = Ext.ComponentMgr.create(config);
            } catch (e) {
                config[this.config.dynProperty] = 'textfield';
                fieldCmp = MODx.load(config);
            }
        }
        const editor = new Ext.grid.GridEditor(fieldCmp);
        colModel.setEditor(colIndex, editor);
        return editor;
    },

    renderDynField: function(value, metaData, record, rowIndex, colIndex, store, grid) {
        const
            { data } = record,
            fieldType = data[this.config.dynProperty],
            encodedValue = Ext.util.Format.htmlEncode(value),
            rendererArgs = [encodedValue, metaData, record, rowIndex, colIndex, store, grid]
        ;
        let renderFn;

        metaData.css = this.setEditableCellClasses(record);

        if (!fieldType || fieldType === 'combo-boolean') {
            renderFn = MODx.grid.Grid.prototype.rendYesNo;
            return this.renderEditableColumn(renderFn)(...rendererArgs);
        }
        if (fieldType === 'datefield') {
            renderFn = Ext.util.Format.dateRenderer('Y-m-d');
            return this.renderEditableColumn(renderFn)(...rendererArgs);
        }
        if (fieldType === 'password') {
            renderFn = this.rendPassword;
            return this.renderEditableColumn(renderFn)(...rendererArgs);
        }
        if (fieldType.includes('combo') || fieldType === 'list') {
            const colModel = grid.getColumnModel();
            let
                editor = colModel.getCellEditor(colIndex, rowIndex),
                comboCmp
            ;
            if (!editor) {
                data.xtype = data.xtype || 'combo-boolean';
                comboCmp = this.createCombo(data);
                editor = new Ext.grid.GridEditor(comboCmp);
                colModel.setEditor(colIndex, editor);
            } else if (editor?.field?.xtype === 'modx-combo') {
                comboCmp = editor.field;
            }
            if (fieldType !== 'list') {
                renderFn = Ext.util.Format.comboRenderer(editor.field);
                return this.renderEditableColumn(renderFn)(...rendererArgs);
            }
            if (comboCmp) {
                const
                    valueIndex = comboCmp.getStore().find(comboCmp.valueField, value),
                    comboRecord = comboCmp.getStore().getAt(valueIndex)
                ;
                if (comboRecord) {
                    const displayValue = comboRecord.get(comboCmp.displayField);
                    // override args in upper scope with this combo's value and record
                    rendererArgs[0] = Ext.util.Format.htmlEncode(displayValue);
                    rendererArgs[2] = comboRecord;
                }
            }
        }
        return this.renderEditableColumn()(...rendererArgs);
    },

    createCombo: function(record) {
        let combo;
        try {
            combo = Ext.ComponentMgr.create({
                xtype: record.xtype,
                id: Ext.id()
            });
        } catch (e) {
            try {
                const
                    { options } = record,
                    data = []
                ;
                options.map(option => data.push([option.name, option.value, option.text]));

                combo = MODx.load({
                    xtype: 'modx-combo',
                    store: new Ext.data.SimpleStore({
                        fields: ['d', 'v', 't'],
                        data: data
                    }),
                    displayField: 'd',
                    valueField: 'v',
                    mode: 'local',
                    triggerAction: 'all',
                    editable: false,
                    selectOnFocus: false,
                    preventRender: true
                });
            } catch (e2) {
                combo = Ext.ComponentMgr.create({
                    xtype: 'combo-boolean',
                    id: Ext.id()
                });
            }
        }
        return combo;
    }
});
Ext.reg('grid-local-property', MODx.grid.LocalProperty);
