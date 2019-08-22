Ext.namespace('MODx.grid');

MODx.grid.Grid = function(config) {
    config = config || {};
    this.config = config;
    this._loadStore();
    this._loadColumnModel();

    Ext.applyIf(config,{
        store: this.store
        ,cm: this.cm
        ,sm: new Ext.grid.RowSelectionModel({singleSelect:true})
        ,paging: (config.bbar ? true : false)
        ,loadMask: true
        ,autoHeight: true
        ,collapsible: true
        ,stripeRows: true
        ,header: false
        ,cls: 'modx-grid'
        ,preventRender: true
        ,preventSaveRefresh: true
        ,showPerPage: true
        ,stateful: false
        ,menuConfig: {
            defaultAlign: 'tl-b?'
            ,enableScrolling: false
        }
        ,viewConfig: {
            forceFit: true
            ,enableRowBody: true
            ,autoFill: true
            ,showPreview: true
            ,scrollOffset: 0
            ,emptyText: config.emptyText || _('ext_emptymsg')
        }
        ,groupingConfig: {
            enableGroupingMenu: true
        }
    });
    if (config.paging) {
        var pgItms = config.showPerPage ? [_('per_page')+':',{
            xtype: 'textfield'
            ,cls: 'x-tbar-page-size'
            ,value: config.pageSize || (parseInt(MODx.config.default_per_page) || 20)
            ,listeners: {
                'change': {fn:this.onChangePerPage,scope:this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: this.blur
                        ,scope: cmp
                    });
                },scope:this}
            }
        }] : [];
        if (config.pagingItems) {
            for (var i=0;i<config.pagingItems.length;i++) {
                pgItms.push(config.pagingItems[i]);
            }
        }
        Ext.applyIf(config,{
            bbar: new Ext.PagingToolbar({
                pageSize: config.pageSize || (parseInt(MODx.config.default_per_page) || 20)
                ,store: this.getStore()
                ,displayInfo: true
                ,items: pgItms
            })
        });
    }
    if (config.grouping) {
        var groupingConfig = {
            forceFit: true
            ,scrollOffset: 0
            ,groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "'
                + (config.pluralText || _('records')) + '" : "'
                + (config.singleText || _('record')) + '"]})'
        };

        Ext.applyIf(config.groupingConfig, groupingConfig);

        Ext.applyIf(config,{
            view: new Ext.grid.GroupingView(config.groupingConfig)
        });
    }
    if (config.tbar) {
        for (var ix = 0;ix<config.tbar.length;ix++) {
            var itm = config.tbar[ix];
            if (itm.handler && typeof(itm.handler) == 'object' && itm.handler.xtype) {
                itm.handler = this.loadWindow.createDelegate(this,[itm.handler],true);
            }
            if (!itm.scope) { itm.scope = this; }
        }
    }
    MODx.grid.Grid.superclass.constructor.call(this,config);
    this._loadMenu(config);
    this.addEvents('beforeRemoveRow','afterRemoveRow','afterAutoSave');
    if (this.autosave) {
        this.on('afterAutoSave', this.onAfterAutoSave, this);
    }
    if (!config.preventRender) { this.render(); }

    this.on('rowcontextmenu',this._showMenu,this);
    if (config.autosave) {
        this.on('afteredit',this.saveRecord,this);
    }

    if (config.paging && config.grouping) {
        this.getBottomToolbar().bind(this.store);
    }

    if (!config.paging && !config.hasOwnProperty('pageSize')) {
        config.pageSize = 0;
    }

    this.getStore().load({
        params: {
            start: config.pageStart || 0
            ,limit: config.hasOwnProperty('pageSize') ? config.pageSize : (parseInt(MODx.config.default_per_page) || 20)
        }
    });
    this.getStore().on('exception',this.onStoreException,this);
    this.config = config;
};
Ext.extend(MODx.grid.Grid,Ext.grid.EditorGridPanel,{
    windows: {}

    ,onStoreException: function(dp,type,act,opt,resp){
        var r = Ext.decode(resp.responseText);
        if (r.message) {
            this.getView().emptyText = r.message;
            this.getView().refresh(false);
        }
    }
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
                success: {
                    fn: function(r) {
                        if (this.config.save_callback) {
                            Ext.callback(this.config.save_callback,this.config.scope || this,[r]);
                        }
                        e.record.commit();
                        if (!this.config.preventSaveRefresh) {
                            this.refresh();
                        }
                        this.fireEvent('afterAutoSave',r);
                    }
                    ,scope: this
                }
                ,failure: {
                    fn: function(r) {
                        e.record.reject();
                        this.fireEvent('afterAutoSave', r);
                    }
                    ,scope: this
                }
            }
        });
    }

    /**
     * Method executed after a record has been edited/saved inline from within the grid
     *
     * @param {Object} response - The processor save response object. See modConnectorResponse::outputContent (PHP)
     */
    ,onAfterAutoSave: function(response) {
        if (!response.success && response.message === '') {
            var msg = '';
            if (response.data.length) {
                // We get some data for specific field(s) error but not regular error message
                Ext.each(response.data, function(data, index, list) {
                    msg += (msg != '' ? '<br/>' : '') + data.msg;
                }, this);
            }
            if (Ext.isEmpty(msg)) {
                // Still no valid message so far, let's use some fallback
                msg = this.autosaveErrorMsg || _('error');
            }
            MODx.msg.alert(_('error'), msg);
        }
    }

    ,onChangePerPage: function(tf,nv) {
        if (Ext.isEmpty(nv)) return false;
        nv = parseInt(nv);
        this.getBottomToolbar().pageSize = nv;
        this.store.load({params:{
            start:0
            ,limit: nv
        }});
    }

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

    ,remove: function(text, action) {
        if (this.destroying) {
            return MODx.grid.Grid.superclass.remove.apply(this, arguments);
        }
        var r = this.menu.record;
        text = text || 'confirm_remove';
        var p = this.config.saveParams || {};
        Ext.apply(p,{ action: action || 'remove' });
        //console.log(action, p);
        var k = this.config.primaryKey || 'id';
        p[k] = r[k];

        if (this.fireEvent('beforeRemoveRow',r)) {
            MODx.msg.confirm({
                title: _('warning')
                ,text: _(text, r)
                ,url: this.config.url
                ,params: p
                ,listeners: {
                	'success': {fn:function() {
                        this.removeActiveRow(r);
                    },scope:this}
                }
            });
        }
    }

    ,removeActiveRow: function(r) {
        if (this.fireEvent('afterRemoveRow',r)) {
            var rx = this.getSelectionModel().getSelected();
            this.getStore().remove(rx);
        }
    }

    ,_loadMenu: function() {
        this.menu = new Ext.menu.Menu(this.config.menuConfig);
    }

    ,_showMenu: function(g,ri,e) {
        e.stopEvent();
        e.preventDefault();
        this.menu.record = this.getStore().getAt(ri).data;
        if (!this.getSelectionModel().isSelected(ri)) {
            this.getSelectionModel().selectRow(ri);
        }
        this.menu.removeAll();
        if (this.getMenu) {
            var m = this.getMenu(g,ri,e);
            if (m && m.length && m.length > 0) {
                this.addContextMenuItem(m);
            }
        }
        if ((!m || m.length <= 0) && this.menu.record.menu) {
            this.addContextMenuItem(this.menu.record.menu);
        }
        if (this.menu.items.length > 0) {
            this.menu.showAt(e.xy);
        }
    }

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
                    field: this.config.sortBy || 'id'
                    ,direction: this.config.sortDir || 'ASC'
                }
                ,remoteSort: this.config.remoteSort || false
                ,groupField: this.config.groupBy || 'name'
                ,storeId: this.config.storeId || Ext.id()
                ,autoDestroy: true
                ,listeners:{
                    load: function(){
                        var cmp = Ext.getCmp('modx-content');
                        if(typeof cmp !== "undefined") {
                            cmp.doLayout(); /* Fix layout bug with absolute positioning */
                        }
                    }
                }
            });
        } else {
            this.store = new Ext.data.JsonStore({
                url: this.config.url
                ,baseParams: this.config.baseParams || { action: this.config.action || 'getList' }
                ,fields: this.config.fields
                ,root: 'results'
                ,totalProperty: 'total'
                ,remoteSort: this.config.remoteSort || false
                ,storeId: this.config.storeId || Ext.id()
                ,autoDestroy: true
                ,listeners:{
                    load: function(){
                        var cmp = Ext.getCmp('modx-content');
                        if(typeof cmp !== "undefined") {
                            cmp.doLayout(); /* Fix layout bug with absolute positioning */
                        }
                    }
                }
            });
        }
    }

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
                    if (Ext.isEmpty(c[i].editor.id)) { c[i].editor.id = Ext.id(); }
                    c[i].editor = Ext.ComponentMgr.create(c[i].editor);
                    if (r === true) {
                        if (c[i].editor && c[i].editor.store && !c[i].editor.store.isLoaded && c[i].editor.config.mode != 'local') {
                            c[i].editor.store.load();
                            c[i].editor.store.isLoaded = true;
                        }
                        c[i].renderer = Ext.util.Format.comboRenderer(c[i].editor);
                    } else if (c[i].editor.initialConfig.xtype === 'datefield') {
                        c[i].renderer = Ext.util.Format.dateRenderer(c[i].editor.initialConfig.format || 'Y-m-d');
                    } else if (r === 'boolean') {
                        c[i].renderer = this.rendYesNo;
                    } else if (r === 'password') {
                        c[i].renderer = this.rendPassword;
                    } else if (r === 'local' && typeof(c[i].renderer) == 'string') {
                        c[i].renderer = eval(c[i].renderer);
                    }
                }
            }
            this.cm = new Ext.grid.ColumnModel(c);
        }
    }

    ,addContextMenuItem: function(items) {
        var l = items.length;
        for(var i = 0; i < l; i++) {
            var options = items[i];

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
                h = function(itm) {
                    var o = itm.options;
                    var id = this.menu.record.id;
                    if (o.confirm) {
                        Ext.Msg.confirm('',o.confirm,function(e) {
                            if (e == 'yes') {
                                var act = Ext.urlEncode(o.params || {action: o.action});
                                location.href = '?id='+id+'&'+act;
                            }
                        },this);
                    } else {
                        var act = Ext.urlEncode(o.params || {action: o.action});
                        location.href = '?id='+id+'&'+act;
                    }
                };
            }
            this.menu.add({
                id: options.id || Ext.id()
                ,text: options.text
                ,scope: options.scope || this
                ,options: options
                ,handler: h
            });
        }
    }

    ,refresh: function() {
        this.getStore().reload();
    }

    ,rendPassword: function(v) {
        var z = '';
        for (var i=0;i<v.length;i++) {
            z = z+'*';
        }
        return z;
    }

    ,rendYesNo: function(v,md) {
        if (v === 1 || v == '1') { v = true; }
        if (v === 0 || v == '0') { v = false; }
        switch (v) {
            case true:
            case 'true':
            case 1:
                md.css = 'green';
                return _('yes');
            case false:
            case 'false':
            case '':
            case 0:
                md.css = 'red';
                return _('no');
        }
    }

    ,getSelectedAsList: function() {
        var sels = this.getSelectionModel().getSelections();
        if (sels.length <= 0) return false;

        var cs = '';
        for (var i=0;i<sels.length;i++) {
            cs += ','+sels[i].data[this.config.primaryKey || 'id'];
        }

        if (cs[0] == ',') {
            cs = cs.substr(1);
        }
        return cs;
    }

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

    ,encodeModified: function() {
        var p = this.getStore().getModifiedRecords();
        var rs = {};
        for (var i=0;i<p.length;i++) {
            rs[p[i].data[this.config.primaryKey || 'id']] = p[i].data;
        }
        return Ext.encode(rs);
    }

    ,encode: function() {
        var p = this.getStore().getRange();
        var rs = {};
        for (var i=0;i<p.length;i++) {
            rs[p[i].data[this.config.primaryKey || 'id']] = p[i].data;
        }
        return Ext.encode(rs);
    }

    ,expandAll: function() {

        var expander = this.findExpanderPlugin(this.config.plugins);

        if (!expander) {
            return false;
        }

        var rows = this.getView().getRows();

        for (var i = 0; i < rows.length; i++) {
            expander.expandRow(rows[i]);
        }

        if (this.tools['plus'] !== undefined) {
            this.tools['plus'].hide();
        }

        if (this.tools['minus'] !== undefined) {
            this.tools['minus'].show();
        }

        return true;
    }

    ,collapseAll: function() {

        var expander = this.findExpanderPlugin(this.config.plugins);

        if (!expander) {
            return false;
        }

        var rows = this.getView().getRows();

        for (var i = 0; i < rows.length; i++) {
            expander.collapseRow(rows[i]);
        }

        if (this.tools['minus'] !== undefined) {
            this.tools['minus'].hide();
        }

        if (this.tools['plus'] !== undefined) {
            this.tools['plus'].show();
        }

        return true;
    }

    /**
     * Returns first found expander plugin
     * @param plugins
     */
    ,findExpanderPlugin: function (plugins) {

        if (Ext.isObject(plugins)) {
            plugins = [plugins];
        }

        var index = Ext.each(plugins, function (item) {
            if (item.id !== undefined && item.id === 'expander') {
                return false;
            }
        });

        return plugins[index];
    }
});

/* local grid */
MODx.grid.LocalGrid = function(config) {
    config = config || {};

    if (config.grouping) {
        Ext.applyIf(config,{
          view: new Ext.grid.GroupingView({
            forceFit: true
            ,scrollOffset: 0
            ,hideGroupedColumn: config.hideGroupedColumn ? true : false
            ,groupTextTpl: config.groupTextTpl || ('{text} ({[values.rs.length]} {[values.rs.length > 1 ? "'
                +(config.pluralText || _('records')) + '" : "'
                +(config.singleText || _('record'))+'"]})' )
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
    Ext.applyIf(config,{
        title: ''
        ,store: this._loadStore(config)
        ,sm: new Ext.grid.RowSelectionModel({singleSelect:false})
        ,loadMask: true
        ,collapsible: true
        ,stripeRows: true
        ,enableColumnMove: true
        ,header: false
        ,cls: 'modx-grid'
        ,viewConfig: {
            forceFit: true
            ,enableRowBody: true
            ,autoFill: true
            ,showPreview: true
            ,scrollOffset: 0
            ,emptyText: config.emptyText || _('ext_emptymsg')
        }
        ,menuConfig: { defaultAlign: 'tl-b?' ,enableScrolling: false }
    });

    this.menu = new Ext.menu.Menu(config.menuConfig);
    this.config = config;
    this._loadColumnModel();
    MODx.grid.LocalGrid.superclass.constructor.call(this,config);
    this.addEvents({
        beforeRemoveRow: true
        ,afterRemoveRow: true
    });
    this.on('rowcontextmenu',this._showMenu,this);
};

Ext.extend(MODx.grid.LocalGrid,Ext.grid.EditorGridPanel,{
    windows: {}

    ,_loadStore: function(config) {
        if (config.grouping) {
            this.store = new Ext.data.GroupingStore({
                data: config.data || []
                ,reader: new Ext.data.ArrayReader({},config.fields || [])
                ,sortInfo: config.sortInfo || {
                    field: config.sortBy || 'name'
                    ,direction: config.sortDir || 'ASC'
                }
                ,groupField: config.groupBy || 'name'
            });
        } else {
            this.store = new Ext.data.SimpleStore({
                fields: config.fields
                ,data: config.data || []
            })
        }
        return this.store;
    }

    ,loadWindow: function(btn,e,win,or) {
        var r = this.menu.record;
        if (!this.windows[win.xtype]) {
            Ext.applyIf(win,{
                scope: this
                ,success: this.refresh
                ,record: win.blankValues ? {} : r
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

    ,_loadColumnModel: function() {
        if (this.config.columns) {
            var c = this.config.columns;
            for (var i=0;i<c.length;i++) {
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
                        if (c[i].editor && c[i].editor.store && !c[i].editor.store.isLoaded && c[i].editor.config.mode != 'local') {
                            c[i].editor.store.load();
                            c[i].editor.store.isLoaded = true;
                        }
                        c[i].renderer = Ext.util.Format.comboRenderer(c[i].editor);
                    } else if (c[i].editor.initialConfig.xtype === 'datefield') {
                        c[i].renderer = Ext.util.Format.dateRenderer(c[i].editor.initialConfig.format || 'Y-m-d');
                    } else if (r === 'boolean') {
                        c[i].renderer = this.rendYesNo;
                    } else if (r === 'password') {
                        c[i].renderer = this.rendPassword;
                    } else if (r === 'local' && typeof(c[i].renderer) == 'string') {
                        c[i].renderer = eval(c[i].renderer);
                    }
                }
            }
            this.cm = new Ext.grid.ColumnModel(c);
        }
    }

    ,_showMenu: function(g,ri,e) {
        e.stopEvent();
        e.preventDefault();
        this.menu.recordIndex = ri;
        this.menu.record = this.getStore().getAt(ri).data;
        if (!this.getSelectionModel().isSelected(ri)) {
            this.getSelectionModel().selectRow(ri);
        }
        this.menu.removeAll();
        var m = this.getMenu(g,ri);
        if (m) {
            this.addContextMenuItem(m);
            this.menu.showAt(e.xy);
        }
    }

    ,getMenu: function() {
        return this.menu.record.menu;
    }

    ,addContextMenuItem: function(items) {
        var l = items.length;
        for(var i = 0; i < l; i++) {
            var options = items[i];

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
                h = function(itm) {
                    var o = itm.options;
                    var id = this.menu.record.id;
                    var w = Ext.get('modx_content');
                    if (o.confirm) {
                        Ext.Msg.confirm('',o.confirm,function(e) {
                            if (e == 'yes') {
                                var a = Ext.urlEncode(o.params || {action: o.action});
                                var s = '?id='+id+'&'+a;
                                if (w === null) {
                                    location.href = s;
                                } else { w.dom.src = s; }
                            }
                        },this);
                    } else {
                        var a = Ext.urlEncode(o.params || {action: o.action});
                        var s = '?id='+id+'&'+a;
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
            });
        }
    }


    ,remove: function(config) {
        if (this.destroying) {
            return MODx.grid.LocalGrid.superclass.remove.apply(this, arguments);
        }
        var r = this.getSelectionModel().getSelected();
        if (this.fireEvent('beforeRemoveRow',r)) {
            Ext.Msg.confirm(config.title || '',config.text || '',function(e) {
                if (e == 'yes') {
                    this.getStore().remove(r);
                    this.fireEvent('afterRemoveRow',r);
                }
            },this);
        }
    }

    ,encode: function() {
        var s = this.getStore();
        var ct = s.getCount();
        var rs = this.config.encodeByPk ? {} : [];
        var r;
        for (var j=0;j<ct;j++) {
            r = s.getAt(j).data;
            r.menu = null;
            if (this.config.encodeAssoc) {
               rs[r[this.config.encodeByPk || 'id']] = r;
            } else {
               rs.push(r);
            }
        }

        return Ext.encode(rs);
    }

    ,expandAll: function() {

        var expander = this.findExpanderPlugin(this.config.plugins);

        if (!expander) {
            return false;
        }

        var rows = this.getView().getRows();

        for (var i = 0; i < rows.length; i++) {
            expander.expandRow(rows[i]);
        }

        if (this.tools['plus'] !== undefined) {
            this.tools['plus'].hide();
        }

        if (this.tools['minus'] !== undefined) {
            this.tools['minus'].show();
        }

        return true;
    }

    ,collapseAll: function() {

        var expander = this.findExpanderPlugin(this.config.plugins);

        if (!expander) {
            return false;
        }

        var rows = this.getView().getRows();

        for (var i = 0; i < rows.length; i++) {
            expander.collapseRow(rows[i]);
        }

        if (this.tools['minus'] !== undefined) {
            this.tools['minus'].hide();
        }

        if (this.tools['plus'] !== undefined) {
            this.tools['plus'].show();
        }

        return true;
    }

    /**
     * Returns first found expander plugin
     * @param plugins
     */
    ,findExpanderPlugin: function (plugins) {

        if (Ext.isObject(plugins)) {
            plugins = [plugins];
        }

        var index = Ext.each(plugins, function (item) {
            if (item.id !== undefined && item.id === 'expander') {
                return false;
            }
        });

        return plugins[index];
    }

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

    ,rendPassword: function(v) {
        var z = '';
        for (var i=0;i<v.length;i++) {
            z = z+'*';
        }
        return z;
    }
});
Ext.reg('grid-local',MODx.grid.LocalGrid);
Ext.reg('modx-grid-local',MODx.grid.LocalGrid);

/* grid extensions */
/*!
 * Ext JS Library 3.4.0
 * Copyright(c) 2006-2011 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */
Ext.ns('Ext.ux.grid');

/**
 * @class Ext.ux.grid.RowExpander
 * @extends Ext.util.Observable
 * Plugin (ptype = 'rowexpander') that adds the ability to have a Column in a grid which enables
 * a second row body which expands/contracts.  The expand/contract behavior is configurable to react
 * on clicking of the column, double click of the row, and/or hitting enter while a row is selected.
 *
 * @ptype rowexpander
 */
Ext.ux.grid.RowExpander = Ext.extend(Ext.util.Observable, {
    /**
     * @cfg {Boolean} expandOnEnter
     * <tt>true</tt> to toggle selected row(s) between expanded/collapsed when the enter
     * key is pressed (defaults to <tt>true</tt>).
     */
    expandOnEnter : true,
    /**
     * @cfg {Boolean} expandOnDblClick
     * <tt>true</tt> to toggle a row between expanded/collapsed when double clicked
     * (defaults to <tt>true</tt>).
     */
    expandOnDblClick : true,

    header : '',
    width : 20,
    sortable : false,
    fixed : true,
    hideable: false,
    menuDisabled : true,
    dataIndex : '',
    id : 'expander',
    lazyRender : true,
    enableCaching : true,

    constructor: function(config){
        Ext.apply(this, config);

        this.addEvents({
            /**
             * @event beforeexpand
             * Fires before the row expands. Have the listener return false to prevent the row from expanding.
             * @param {Object} this RowExpander object.
             * @param {Object} Ext.data.Record Record for the selected row.
             * @param {Object} body body element for the secondary row.
             * @param {Number} rowIndex The current row index.
             */
            beforeexpand: true,
            /**
             * @event expand
             * Fires after the row expands.
             * @param {Object} this RowExpander object.
             * @param {Object} Ext.data.Record Record for the selected row.
             * @param {Object} body body element for the secondary row.
             * @param {Number} rowIndex The current row index.
             */
            expand: true,
            /**
             * @event beforecollapse
             * Fires before the row collapses. Have the listener return false to prevent the row from collapsing.
             * @param {Object} this RowExpander object.
             * @param {Object} Ext.data.Record Record for the selected row.
             * @param {Object} body body element for the secondary row.
             * @param {Number} rowIndex The current row index.
             */
            beforecollapse: true,
            /**
             * @event collapse
             * Fires after the row collapses.
             * @param {Object} this RowExpander object.
             * @param {Object} Ext.data.Record Record for the selected row.
             * @param {Object} body body element for the secondary row.
             * @param {Number} rowIndex The current row index.
             */
            collapse: true
        });

        Ext.ux.grid.RowExpander.superclass.constructor.call(this);

        if(this.tpl){
            if(typeof this.tpl == 'string'){
                this.tpl = new Ext.Template(this.tpl);
            }
            this.tpl.compile();
        }

        this.state = {};
        this.bodyContent = {};
    },

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


        grid.on('render', this.onRender, this);
        grid.on('destroy', this.onDestroy, this);
    },

    // @private
    onRender: function() {
        var grid = this.grid;
        var mainBody = grid.getView().mainBody;
        mainBody.on('mousedown', this.onMouseDown, this, {delegate: '.x-grid3-row-expander'});
        if (this.expandOnEnter) {
            this.keyNav = new Ext.KeyNav(this.grid.getGridEl(), {
                'enter' : this.onEnter,
                scope: this
            });
        }
        if (this.expandOnDblClick) {
            grid.on('rowdblclick', this.onRowDblClick, this);
        }
    },

    // @private
    onDestroy: function() {
        if(this.keyNav){
            this.keyNav.disable();
            delete this.keyNav;
        }
        /*
         * A majority of the time, the plugin will be destroyed along with the grid,
         * which means the mainBody won't be available. On the off chance that the plugin
         * isn't destroyed with the grid, take care of removing the listener.
         */
        var mainBody = this.grid.getView().mainBody;
        if(mainBody){
            mainBody.un('mousedown', this.onMouseDown, this);
        }
    },
    // @private
    onRowDblClick: function(grid, rowIdx, e) {
        this.toggleRow(rowIdx);
    },

    onEnter: function(e) {
        var g = this.grid;
        var sm = g.getSelectionModel();
        var sels = sm.getSelections();
        for (var i = 0, len = sels.length; i < len; i++) {
            var rowIdx = g.getStore().indexOf(sels[i]);
            this.toggleRow(rowIdx);
        }
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
        e.stopEvent();
        var row = e.getTarget('.x-grid3-row');
        this.toggleRow(row);
    },

    renderer : function(v, p, record){
        p.cellAttr = 'rowspan="2"';
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
});

Ext.preg('rowexpander', Ext.ux.grid.RowExpander);

//backwards compat
Ext.grid.RowExpander = Ext.ux.grid.RowExpander;

Ext.ns('Ext.ux.grid');Ext.ux.grid.CheckColumn=function(a){Ext.apply(this,a);if(!this.id){this.id=Ext.id()}this.renderer=this.renderer.createDelegate(this)};Ext.ux.grid.CheckColumn.prototype={init:function(b){this.grid=b;this.grid.on('render',function(){var a=this.grid.getView();a.mainBody.on('mousedown',this.onMouseDown,this)},this);this.grid.on('destroy',this.onDestroy,this)},onMouseDown:function(e,t){this.grid.fireEvent('rowclick');if(t.className&&t.className.indexOf('x-grid3-cc-'+this.id)!=-1){e.stopEvent();var a=this.grid.getView().findRowIndex(t);var b=this.grid.store.getAt(a);b.set(this.dataIndex,!b.data[this.dataIndex]);this.grid.fireEvent('afteredit')}},renderer:function(v,p,a){p.css+=' x-grid3-check-col-td';return'<div class="x-grid3-check-col'+(v?'-on':'')+' x-grid3-cc-'+this.id+'">&#160;</div>'},onDestroy:function(){var mainBody = this.grid.getView().mainBody;
        if(mainBody){
            mainBody.un('mousedown', this.onMouseDown, this);
        }}};Ext.preg('checkcolumn',Ext.ux.grid.CheckColumn);Ext.grid.CheckColumn=Ext.ux.grid.CheckColumn;

Ext.grid.PropertyColumnModel=function(a,b){var g=Ext.grid,f=Ext.form;this.grid=a;g.PropertyColumnModel.superclass.constructor.call(this,[{header:this.nameText,width:50,sortable:true,dataIndex:'name',id:'name',menuDisabled:true},{header:this.valueText,width:50,resizable:false,dataIndex:'value',id:'value',menuDisabled:true}]);this.store=b;var c=new f.Field({autoCreate:{tag:'select',children:[{tag:'option',value:'true',html:'true'},{tag:'option',value:'false',html:'false'}]},getValue:function(){return this.el.dom.value=='true'}});this.editors={'date':new g.GridEditor(new f.DateField({selectOnFocus:true})),'string':new g.GridEditor(new f.TextField({selectOnFocus:true})),'number':new g.GridEditor(new f.NumberField({selectOnFocus:true,style:'text-align:left;'})),'boolean':new g.GridEditor(c)};this.renderCellDelegate=this.renderCell.createDelegate(this);this.renderPropDelegate=this.renderProp.createDelegate(this)};Ext.extend(Ext.grid.PropertyColumnModel,Ext.grid.ColumnModel,{nameText:'Name',valueText:'Value',dateFormat:'m/j/Y',renderDate:function(a){return a.dateFormat(this.dateFormat)},renderBool:function(a){return a?'true':'false'},isCellEditable:function(a,b){return a==1},getRenderer:function(a){return a==1?this.renderCellDelegate:this.renderPropDelegate},renderProp:function(v){return this.getPropertyName(v)},renderCell:function(a){var b=a;if(Ext.isDate(a)){b=this.renderDate(a)}else if(typeof a=='boolean'){b=this.renderBool(a)}return Ext.util.Format.htmlEncode(b)},getPropertyName:function(a){var b=this.grid.propertyNames;return b&&b[a]?b[a]:a},getCellEditor:function(a,b){var p=this.store.getProperty(b),n=p.data.name,val=p.data.value;if(this.grid.customEditors[n]){return this.grid.customEditors[n]}if(Ext.isDate(val)){return this.editors.date}else if(typeof val=='number'){return this.editors.number}else if(typeof val=='boolean'){return this.editors['boolean']}else{return this.editors.string}},destroy:function(){Ext.grid.PropertyColumnModel.superclass.destroy.call(this);for(var a in this.editors){Ext.destroy(a)}}});
