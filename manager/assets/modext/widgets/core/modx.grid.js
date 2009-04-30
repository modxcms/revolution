Ext.namespace('MODx.grid');
/**
 * An abstract class for Ext Grids in MODx. 
 * 
 * @class MODx.grid.Grid
 * @extends Ext.grid.EditorGridPanel
 * @constructor
 * @param {Object} config An object of config options.
 */
MODx.grid.Grid = function(config) {
	config = config || {};
	this.config = config;
	this._loadStore();
	this._loadColumnModel();
	this._loadMenu();
	
	Ext.applyIf(config,{
		store: this.store
		,cm: this.cm
		,sm: new Ext.grid.RowSelectionModel({singleSelect:true})
		,paging: (config.bbar ? true : false)
		,loadMask: true
		,autoHeight: true
        ,collapsible: true
        ,stripeRows: true
        ,cls: 'modx-grid'
		,viewConfig: {
			forceFit: true
			,enableRowBody: true
            ,autoFill: true
			,showPreview: true
		}
	});
	if (config.paging) {
		Ext.applyIf(config,{
			bbar: new Ext.PagingToolbar({
				pageSize: config.pageSize || 20
				,store: this.getStore()
				,displayInfo: true
				,items: config.pagingItems || []
			})
		});
	}
    if (config.grouping) {
        Ext.applyIf(config,{
          view: new Ext.grid.GroupingView({ 
            forceFit: true 
            ,groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "'
                +(config.pluralText || _('records')) + '" : "'
                +(config.singleText || _('record'))+'"]})' 
          })
        });
    }
    if (config.tbar) {
        for (var i = 0;i<config.tbar.length;i++) {
            var itm = config.tbar[i];
            if (itm.handler && typeof(itm.handler) == 'object' && itm.handler.xtype) {
                itm.handler = this.loadWindow.createDelegate(this,[itm.handler],true);
            }
            if (!itm.scope) { itm.scope = this; }
        }
    }
	MODx.grid.Grid.superclass.constructor.call(this,config);
    this.addEvents({
        beforeRemoveRow: true
        ,afterRemoveRow: true
    });
	if (!config.preventRender) { this.render(); }
	
    this.on('rowcontextmenu',this._showMenu,this);
    
	if (config.autosave) {
		this.on('afteredit',this.saveRecord,this);
	}
	
    if (config.paging && config.grouping) {
        this.getBottomToolbar().bind(this.store);
    }
    
	this.getStore().load({
		params: {
            start: config.pageStart || 0
            ,limit: config.pageSize || 20
        }
		,scope: this
		,callback: function() { this.getStore().reload(); } // fixes comboeditor bug
	});
    this.config = config;
};
Ext.extend(MODx.grid.Grid,Ext.grid.EditorGridPanel,{
	/**
     * @var {Object} All the windows loaded in the grid.
     * @access protected
     */
    windows: {}
    
    /**
	 * Saves the record after editing if autosave is on.
     * 
     * @access public
	 * @param {Object} e The edit event.
	 */
	,saveRecord: function(e) {
		e.record.data.menu = null;
        var p = this.config.saveParams || {};
        Ext.apply(e.record.data,p);
		var d = Ext.util.JSON.encode(e.record.data);
        var url = this.config.saveUrl || (this.config.url || this.config.connector);
		MODx.Ajax.request({
			url: url
			,params: {
				action: this.config.save_action || 'updateFromGrid'
				,data: d
			}
			,listeners: {
				'success': {fn:function(r) {
					if (this.config.save_callback) {
                        Ext.callback(this.config.save_callback,this.config.scope || this,[r]);
                    }
                    e.record.commit();
                    if (!this.config.preventSaveRefresh) this.refresh();
				},scope:this}
			}
		});
	}
    
    /**
     * Dynamically loads a window based on its xtype
     * 
     * @access public
     * @param {Object} btn The item that was pressed.
     * @param {Ext.EventObject} e The EventObject that occurred.
     * @param {Object} win The window config object
     * @param {Object} or Any overrides to use
     */
    ,loadWindow: function(btn,e,win,or) {
        var r = this.menu.record;
        if (!this.windows[win.xtype] || win.force) {  
            Ext.applyIf(win,{
                record: win.blankValues ? {} : r
                ,grid: this
                ,listeners: {
                	'success': {fn:win.success || this.refresh,scope:win.scope || this}
                }
            });
            if (or) {
                Ext.apply(win,or);
            }
            this.windows[win.xtype] = Ext.ComponentMgr.create(win);
        }
        if (this.windows[win.xtype].setValues && win.blankValues !== true && r != undefined) {
            this.windows[win.xtype].setValues(r);
        }
        this.windows[win.xtype].show(e.target);
    }
    
    /**
     * An abstracted confirm dialog that can be loaded via JSON. 
     * Use createDelegate to implement.
     * 
     * @access public
     * @param {String} type The action to connect to
     * @param {String} text The lexicon key for the confirm prompt
     */
    ,confirm: function(type,text) {
        var p = { action: type };
        var k = this.config.primaryKey || 'id';
        p[k] = this.menu.record[k];
        
        MODx.msg.confirm({
            title: _(type)
            ,text: _(text) || _('confirm_remove')
            ,url: this.config.url
            ,params: p
            ,listeners: {
            	'success': {fn:this.refresh,scope:this}
            }
        });
    }
    
    /**
     * An abstracted remove dialog that can be loaded via JSON. 
     * Use createDelegate to implement.
     * 
     * @access public
     * @param {String} text The lexicon key for the remove prompt
     */
    ,remove: function(text) {
        var r = this.menu.record;
        text = text || 'confirm_remove';
        var p = this.config.saveParams || {};
        Ext.apply(p,{ action: 'remove' });
        var k = this.config.primaryKey || 'id';
        p[k] = r[k];
        
        if (this.fireEvent('beforeRemoveRow',r)) {
            MODx.msg.confirm({
                title: _('warning')
                ,text: _(text)
                ,url: this.config.url
                ,params: p
                ,listeners: {
                	'success': {fn:function() {
                        if (this.fireEvent('afterRemoveRow',r)) {
                            this.removeActiveRow(r);
                        }
                    },scope:this}
                }
            });
        }
    }
    
    /**
     * Removes the actively selected row from the grid.
     * 
     * @access public
     */
    ,removeActiveRow: function() {
        var rx = this.getSelectionModel().getSelected();
        this.getStore().remove(rx);
    }
    
	/**
	 * Loads the context menu for the grid.
     * 
     * @access protected
	 */
	,_loadMenu: function() {
		this.menu = new Ext.menu.Menu({ defaultAlign: 'tl-b?' });
	}
    
	/**
	 * Displays the grid's context menu.
     * 
     * @access protected
	 * @param {Object} g The grid object.
	 * @param {Number} ri The index of the row clicked.
	 * @param {Ext.EventObject} e The event object.
	 */
	,_showMenu: function(g,ri,e) {
		e.stopEvent();
		e.preventDefault();
		this.menu.record = this.getStore().getAt(ri).data;
        if (!this.getSelectionModel().isSelected(ri)) {
            this.getSelectionModel().selectRow(ri);
        }
		this.menu.removeAll();
        if (this.menu.record.menu) {
            this.addContextMenuItem(this.menu.record.menu);
            this.menu.show(e.target);
        }
	}
    
	/**
	 * Loads the grid's store.
     * 
     * @access protected
	 */
	,_loadStore: function() {
		if (this.config.grouping) {
            this.store = new Ext.data.GroupingStore({
                url: this.config.url
                ,baseParams: this.config.baseParams || { action: this.config.action || 'getList'}
                ,reader: new Ext.data.JsonReader({
                    totalProperty: 'total'
                    ,root: 'results'
                    ,fields: this.config.fields
                })
                ,sortInfo:{
                    field: this.config.sortBy || 'name'
                    ,direction: this.config.sortDir || 'ASC'
                }
                ,groupField: this.config.groupBy || 'name'
            });
        } else {
            this.store = new Ext.data.JsonStore({
    			url: this.config.url
    			,baseParams: this.config.baseParams || { action: this.config.action || 'getList' }
    			,fields: this.config.fields
    			,root: 'results'
    			,totalProperty: 'total'
    			,remoteSort: this.config.remoteSort || false
    		});
        }
	}
    
	/**
	 * Loads the grid's column model.
     * 
     * @access protected
	 */
	,_loadColumnModel: function() {
        if (this.config.columns) {
            var c = this.config.columns;
            for (var i=0;i<c.length;i++) {
                // if specifying custom editor/renderer
                if (typeof(c[i].editor) == 'string') {
                    c[i].editor = eval(c[i].editor);
                }
                if (typeof(c[i].renderer) == 'string') {
                    c[i].renderer = eval(c[i].renderer);
                }
                if (typeof(c[i].editor) == 'object' && c[i].editor.xtype) {
                    var r = c[i].editor.renderer;
                    c[i].editor = Ext.ComponentMgr.create(c[i].editor);
                    if (r === true) {
                        c[i].renderer = MODx.combo.Renderer(c[i].editor);
                    } else if (c[i].editor.initialConfig.xtype === 'datefield') {
                        c[i].renderer = Ext.util.Format.dateRenderer(c[i].editor.initialConfig.format || 'Y-m-d');
                    } else if (r === 'boolean') {
                        c[i].renderer = this.rendYesNo;
                    } else if (r === 'local' && typeof(c[i].renderer) == 'string') {
                        c[i].renderer = eval(c[i].renderer);
                    }
                }
            }
            this.cm = new Ext.grid.ColumnModel(c);
        }
    }
    
    /**
     * Adds menu items to the grid context menu
     * 
     * @access public
     * @param {Object} items
     */
    ,addContextMenuItem: function(items) {
        var a = items, l = a.length;
        for(var i = 0; i < l; i++) {
            var options = a[i];
            
            if (options == '-') {
                this.menu.add('-');
                continue;
            }
            var h = Ext.emptyFn;
            if (options.handler) {
                h = eval(options.handler);
                if (h && typeof(h) == 'object' && h.xtype) {
                    h = this.loadWindow.createDelegate(this,[h],true);
                }
            } else {
                h = function(itm,e) {
                    var o = itm.options;
                    var id = this.menu.record.id;
                    var w = Ext.get('modx_content');
                    if (o.confirm) {
                        Ext.Msg.confirm('',o.confirm,function(e) {
                            if (e == 'yes') {
                                var a = Ext.urlEncode(o.params || {action: o.action});
                                var s = 'index.php?id='+id+'&'+a;
                                if (w === null) {
                                    location.href = s;
                                } else { w.dom.src = s; }
                            }
                        },this);
                    } else {
                        var a = Ext.urlEncode(o.params || {action: o.action});
                        var s = 'index.php?id='+id+'&'+a;
                        if (w === null) {
                            location.href = s;
                        } else { w.dom.src = s; }
                    }
                };
            }
            this.menu.add({
                id: options.id || Ext.id()
                ,text: options.text
                ,scope: this
                ,options: options
                ,handler: h
                //,cls: (options.header ? 'x-menu-item-active' : '')
            });
        }
    }
    
    /**
     * Reloads the grid store.
     * 
     * @access public
     */
    ,refresh: function() {
        this.getStore().reload();
    }
    
    /**
     * Render the row to a colored Yes/No value.
     * 
     * @access public
     * @param {Object} d The data record
     * @param {Object} c The dom properties
     * @return {String} The value to return
     */
    ,rendYesNo: function(d,c) {
        switch(d) {
            case '':
                return '-';
            case false:
                c.css = 'red';
                return _('no');
            case true:
                c.css = 'green';
                return _('yes');
        }
    }
    
    /**
     * Create an editor combobox for Yes/No editing
     * 
     * @access public
     * @param {Object} r An object of configuration options
     */
    ,editorYesNo: function(r) {
    	r = r || {};
    	Ext.applyIf(r,{
            store: new Ext.data.SimpleStore({
                fields: ['d','v']
                ,data: [[_('yes'),true],[_('no'),false]]
            })
            ,displayField: 'd'
            ,valueField: 'v'
            ,mode: 'local'
            ,triggerAction: 'all'
            ,editable: false
            ,selectOnFocus: false
        });
        return new Ext.form.ComboBox(r);
    }
    
    /**
     * Encodes modified record data into JSON array for form sending
     * 
     * @access public
     */
    ,encodeModified: function() {
        var p = this.getStore().getModifiedRecords();
        var rs = {};
        for (var i=0;i<p.length;i++) {
            rs[p[i].data.id] = p[i].data;
        }
        return Ext.encode(rs);
    }
    
    ,expandAll: function() {
        if (!this.exp) return false;
        
        this.exp.expandAll(); 
        this.tools['plus'].hide();
        this.tools['minus'].show();
    }
    
    ,collapseAll: function() {
        if (!this.exp) return false;
        
        this.exp.collapseAll();
        this.tools['minus'].hide();
        this.tools['plus'].show();
    }
});



/*
 * Ext JS Library 2.0.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */
Ext.grid.RowExpander = function(config){
    Ext.apply(this, config);

    this.addEvents({
        beforeexpand : true,
        expand: true,
        beforecollapse: true,
        collapse: true
    });

    Ext.grid.RowExpander.superclass.constructor.call(this);

    if(this.tpl){
        if(typeof this.tpl == 'string'){
            this.tpl = new Ext.Template(this.tpl);
        }
        this.tpl.compile();
    }

    this.state = {};
    this.bodyContent = {};
};

Ext.extend(Ext.grid.RowExpander, Ext.util.Observable, {
    header: "",
    width: 20,
    sortable: false,
    fixed:true,
    menuDisabled:true,
    dataIndex: '',
    id: 'expander',
    lazyRender : true,
    enableCaching: true,

    getRowClass : function(record, rowIndex, p, ds){
        p.cols = p.cols-1;
        var content = this.bodyContent[record.id];
        if(!content && !this.lazyRender){
            content = this.getBodyContent(record, rowIndex);
        }
        if(content){
            p.body = content;
        }
        return this.state[record.id] ? 'x-grid3-row-expanded' : 'x-grid3-row-collapsed';
    },

    init : function(grid){
        this.grid = grid;

        var view = grid.getView();
        view.getRowClass = this.getRowClass.createDelegate(this);

        view.enableRowBody = true;

        grid.on('render', function(){
            view.mainBody.on('mousedown', this.onMouseDown, this);
        }, this);
    },

    getBodyContent : function(record, index){
        if(!this.enableCaching){
            return this.tpl.apply(record.data);
        }
        var content = this.bodyContent[record.id];
        if(!content){
            content = this.tpl.apply(record.data);
            this.bodyContent[record.id] = content;
        }
        return content;
    },

    onMouseDown : function(e, t){
        if(t.className == 'x-grid3-row-expander'){
            e.stopEvent();
            var row = e.getTarget('.x-grid3-row');
            this.toggleRow(row);
        }
    },

    renderer : function(v, p, record){
        p.cellAttr = 'rowspan="2"';
        if (record.data.description !== null && record.data.description === '') { return ''; }
        return '<div class="x-grid3-row-expander">&#160;</div>';
    },

    beforeExpand : function(record, body, rowIndex){
        if(this.fireEvent('beforeexpand', this, record, body, rowIndex) !== false){
            if(this.tpl && this.lazyRender){
                body.innerHTML = this.getBodyContent(record, rowIndex);
            }
            return true;
        }else{
            return false;
        }
    },

    toggleRow : function(row){
        if(typeof row == 'number'){
            row = this.grid.view.getRow(row);
        }
        this[Ext.fly(row).hasClass('x-grid3-row-collapsed') ? 'expandRow' : 'collapseRow'](row);
    },

    expandRow : function(row){
        if(typeof row == 'number'){
            row = this.grid.view.getRow(row);
        }
        var record = this.grid.store.getAt(row.rowIndex);
        var body = Ext.DomQuery.selectNode('tr:nth(2) div.x-grid3-row-body', row);
        if(this.beforeExpand(record, body, row.rowIndex)){
            this.state[record.id] = true;
            Ext.fly(row).replaceClass('x-grid3-row-collapsed', 'x-grid3-row-expanded');
            this.fireEvent('expand', this, record, body, row.rowIndex);
        }
    },

    collapseRow : function(row){
        if(typeof row == 'number'){
            row = this.grid.view.getRow(row);
        }
        var record = this.grid.store.getAt(row.rowIndex);
        var body = Ext.fly(row).child('tr:nth(1) div.x-grid3-row-body', true);
        if(this.fireEvent('beforecollapse', this, record, body, row.rowIndex) !== false){
            this.state[record.id] = false;
            Ext.fly(row).replaceClass('x-grid3-row-expanded', 'x-grid3-row-collapsed');
            this.fireEvent('collapse', this, record, body, row.rowIndex);
        }
    }
    
    // Expand all rows
    ,expandAll : function() {
        var aRows = this.grid.getView().getRows();
        for(var i = 0; i < aRows.length; i++) {
            this.expandRow(aRows[i]);
        }
    }

    // Collapse all rows
    ,collapseAll : function() {
        var aRows = this.grid.getView().getRows();
        for(var i = 0; i < aRows.length; i++) {
            this.collapseRow(aRows[i]);
        }
    }
});

Ext.grid.CheckColumn = function(config){
    Ext.apply(this, config);
    if(!this.id){
        this.id = Ext.id();
    }
    this.renderer = this.renderer.createDelegate(this);
    Ext.grid.CheckColumn.superclass.constructor.call(this,config);
};
Ext.extend(Ext.grid.CheckColumn,Ext.Component,{
    init : function(grid){
        this.grid = grid;
        this.grid.on('render', function(){
            var view = this.grid.getView();
            view.mainBody.on('mousedown', this.onMouseDown, this);
        }, this);
    },

    onMouseDown : function(e, t){
        if(t.className && t.className.indexOf('x-grid3-cc-'+this.id) != -1){
            e.stopEvent();
            var index = this.grid.getView().findRowIndex(t);
            var record = this.grid.store.getAt(index);
            record.set(this.dataIndex, !record.data[this.dataIndex]);
            this.grid.fireEvent('afteredit');
        }
    },

    renderer : function(v, p, record){
        p.css += ' x-grid3-check-col-td'; 
        return '<div class="x-grid3-check-col'+(v?'-on':'')+' x-grid3-cc-'+this.id+'">&#160;</div>';
    }
});
Ext.reg('checkbox-column',Ext.grid.CheckColumn);