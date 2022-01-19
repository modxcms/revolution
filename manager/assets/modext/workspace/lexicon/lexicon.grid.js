/**
 * Loads a grid for managing lexicons.
 *
 * @class MODx.grid.Lexicon
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-lexicon
 */
MODx.grid.Lexicon = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-grid-lexicon'
        ,url: MODx.config.connector_url
        ,fields: ['name','value','namespace','topic','language','editedon','overridden']
        ,baseParams: {
            action: 'Workspace/Lexicon/GetList'
            ,'namespace': MODx.request['ns'] ? MODx.request['ns'] : 'core'
            ,topic: ''
            ,language: MODx.config.cultureKey || 'en'
        }
        ,width: '98%'
        ,paging: true
        ,autosave: true
        ,save_action: 'Workspace/Lexicon/UpdateFromGrid'
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
            ,sortable: true
            ,renderer: this._renderStatus
        },{
            header: _('value')
            ,dataIndex: 'value'
            ,width: 500
            ,sortable: false
            ,editor: {xtype: 'textarea'}
            ,renderer: this._renderStatus
        },{
            header: _('last_modified')
            ,dataIndex: 'editedon'
            ,width: 125
            ,renderer: this._renderLastModDate
        }]
        ,tbar: [{
            xtype: 'button'
            ,text: _('create')
            ,cls:'primary-button'
            ,handler: this.createEntry
            ,scope: this
        },
        '->'
        ,{
            xtype: 'tbtext'
            ,text: _('namespace')+':'
        },{
            xtype: 'modx-combo-namespace'
            ,id: 'modx-lexicon-filter-namespace'
            ,itemId: 'namespace'
            ,preselectValue: MODx.request['ns'] ? MODx.request['ns'] : ''
            ,width: 150
            ,listeners: {
                'select': {fn: this.changeNamespace,scope:this}
            }
        },{
            xtype: 'tbtext'
            ,text: _('topic')+':'
        },{
            xtype: 'modx-combo-lexicon-topic'
            ,id: 'modx-lexicon-filter-topic'
            ,itemId: 'topic'
            ,value: 'default'
            ,width: 150
            ,baseParams: {
                action: 'Workspace/Lexicon/Topic/GetList'
                ,'namespace': MODx.request['ns'] ? MODx.request['ns'] : ''
                ,'language': 'en'
            }
            ,listeners: {
                'select': {fn:this.changeTopic,scope:this}
            }
        },{
            xtype: 'tbtext'
            ,text: _('language')+':'
        },{
            xtype: 'modx-combo-language'
            ,name: 'language'
            ,id: 'modx-lexicon-filter-language'
            ,itemId: 'language'
            ,value: MODx.config.cultureKey || 'en'
            ,width: 100
            ,baseParams: {
                action: 'System/Language/GetList'
                ,'namespace': MODx.request['ns'] ? MODx.request['ns'] : ''
            }
            ,listeners: {
                'select': {fn:this.changeLanguage,scope:this}
            }
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,id: 'modx-lexicon-filter-search'
            ,cls: 'x-form-filter'
            ,itemId: 'search'
            ,emptyText: _('search')
            ,listeners: {
                'change': {fn:this.filter.createDelegate(this,['search'],true),scope:this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: this.blur
                        ,scope: cmp
                    });
                },scope:this}
            }
        },{
            xtype: 'button'
            ,id: 'modx-lexicon-filter-clear'
            ,cls: 'x-form-filter-clear'
            ,itemId: 'clear'
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this},
                    'mouseout': { fn: function(evt){
                        this.removeClass('x-btn-focus');
                    }
                    }
            }
        }]
        ,pagingItems: [{
            text: _('reload_from_base')
            ,handler: this.reloadFromBase
            ,scope: this
        }]
    });
    MODx.grid.Lexicon.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Lexicon,MODx.grid.Grid,{
    console: null

    ,_renderStatus: function(v,md,rec,ri) {
        switch (rec.data.overridden) {
            case 1:
                return '<span style="color: green;">'+v+'</span>';break;
            case 2:
                return '<span style="color: purple;">'+v+'</span>';
            default:
                return '<span>'+v+'</span>';
        }
    }

    ,_renderLastModDate: function(value) {
        if (Ext.isEmpty(value)) {
            return '—';
        }

        return new Date(value*1000).format(MODx.config.manager_date_format + ' ' + MODx.config.manager_time_format);
    }

    ,filter: function(cb,r,i,name) {
    	if (!name) {return false;}
    	this.store.baseParams[name] = cb.getValue();
    	this.getBottomToolbar().changePage(1);
        return true;
    }
    ,clearFilter: function() {
    	this.store.baseParams = {
            action: 'Workspace/Lexicon/GetList'
            ,'namespace': 'core'
            ,topic: 'default'
            ,language: 'en'
    	};
    	this.getBottomToolbar().changePage(1);
        var tb = this.getTopToolbar();
    	tb.getComponent('namespace').setValue('core');

        var tcb = tb.getComponent('topic');
        tcb.store.baseParams['namespace'] = 'core';
        tcb.store.load();
    	tcb.setValue('default');

    	var tcl = tb.getComponent('language');
        tcb.store.baseParams['namespace'] = 'core';
        tcb.store.load();
        tcl.setValue('en');

        tb.getComponent('search').setValue('');
    }
    ,changeNamespace: function(cb,nv,ov) {
        this.setFilterParams(cb.getValue(),'default','en');
    }
    ,changeTopic: function(cb,nv,ov) {
        this.setFilterParams(null,cb.getValue());
    }
    ,changeLanguage: function(cb,nv,ov) {
        this.setFilterParams(null,null,cb.getValue());
    }

    ,setFilterParams: function(ns,t,l) {
        var tb = this.getTopToolbar();
        if (!tb) {return false;}

        var tcb,tcl;
        if (ns) {
            tb.getComponent('namespace').setValue(ns);

            tcl = tb.getComponent('language');
            if (tcl) {
                tcl.store.baseParams['namespace'] = ns;
                tcl.store.load({
                    callback: function() {
                        tcl.setValue(l || 'en');
                    }
                });
            }
            tcb = tb.getComponent('topic');
            if (tcb) {
                tcb.store.baseParams['namespace'] = ns;
                tcb.store.baseParams['language'] = l ? l : (tcl ? tcl.getValue() : 'en');
                tcb.store.load({
                    callback: function() {
                        tcb.setValue(t || 'default');
                    }
                });
            }
        } else if (t) {
            tcb = tb.getComponent('topic');
            if (tcb) {tcb.setValue(t);}
        }

        var s = this.getStore();
        if (s) {
            if (ns) {s.baseParams['namespace'] = ns;}
            if (t) {s.baseParams['topic'] = t || 'default';}
            if (l) {s.baseParams['language'] = l || 'en';}
            s.removeAll();
        }
        this.getBottomToolbar().changePage(1);
    }
    ,loadWindow2: function(btn,e,o) {
        var tb = this.getTopToolbar();
    	this.menu.record = {
            'namespace': tb.getComponent('namespace').getValue()
            ,language: tb.getComponent('language').getValue()
        };
        if (o.xtype != 'modx-window-lexicon-import') {
            this.menu.record.topic = tb.getComponent('topic').getValue();
        }
    	this.loadWindow(btn, e, o);
    }
    ,reloadFromBase: function() {
    	Ext.Ajax.timeout = 0;
    	var topic = '/workspace/lexicon/reload/';
        this.console = MODx.load({
           xtype: 'modx-console'
           ,register: 'mgr'
           ,topic: topic
        });

        this.console.on('complete',function(){
            this.refresh();
        },this);
        this.console.show(Ext.getBody());

    	MODx.Ajax.request({
    	   url: this.config.url
    	   ,params: {action: 'Workspace/Lexicon/ReloadFromBase' ,register: 'mgr' ,topic: topic}
    	   ,listeners: {
                'success': {fn:function(r) {
                    this.refresh();
                },scope:this}
	       }
    	});
    }

    ,revertEntry: function() {
        var p = this.menu.record;
        p.action = 'Workspace/Lexicon/Revert';

    	MODx.Ajax.request({
    	   url: this.config.url
    	   ,params: p
    	   ,listeners: {
                'success': {fn:function(r) {
                    this.refresh();
                },scope:this}
            }
    	});
    }
    ,getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var m = [];
        if (r.data.overridden) {
            m.push({
                text: _('entry_revert')
                ,handler: this.revertEntry
            });
        }
        return m;
    }

    ,createEntry: function(btn,e) {
        var r = this.menu.record || {};

        var tb = this.getTopToolbar();
    	r['namespace'] = tb.getComponent('namespace').getValue();
        r.language =  tb.getComponent('language').getValue();
        r.topic = tb.getComponent('topic').getValue();

        if (!this.createEntryWindow) {
            this.createEntryWindow = MODx.load({
                xtype: 'modx-window-lexicon-entry-create'
                ,record: r
                ,listeners: {
                    'success':{fn:function(o) {
                        this.refresh();
                    },scope:this}
                }
            });
        }
        this.createEntryWindow.reset();
        this.createEntryWindow.setValues(r);
        this.createEntryWindow.show(e.target);
    }
});
Ext.reg('modx-grid-lexicon',MODx.grid.Lexicon);


/**
 * Generates the export lexicon window.
 *
 * @class MODx.window.ExportLexicon
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-lexicon-export
 */
MODx.window.ExportLexicon = function(config) {
    config = config || {};
    this.ident = config.ident || 'explex'+Ext.id();
    var r = config.record;
    Ext.applyIf(config,{
        title: _('export')
        ,url: MODx.config.connector_url
        ,action: 'workspace/lexicon/export'
        ,fileUpload: true
        ,fields: [{
            html: _('lexicon_export_desc')
            ,border: false
            ,bodyStyle: 'margin: 10px;'
            ,id: 'modx-'+this.ident+'-desc'
            ,itemId: 'desc'
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-namespace'
            ,fieldLabel: _('namespace')
            ,name: 'namespace'
            ,id: 'modx-'+this.ident+'-namespace'
            ,itemId: 'namespace'
            ,anchor: '100%'
            ,listeners: {
                'select': {fn: function(cb,r,i) {
                    cle = this.fp.getComponent('topic');
                    if (cle) {
                        cle.store.baseParams['namespace'] = cb.getValue();
                        cle.setValue('');
                        cle.store.reload();
                    } else {MODx.debug('cle not found');}
                },scope:this}
            }
        },{
            xtype: 'modx-combo-lexicon-topic'
            ,fieldLabel: _('topic')
            ,name: 'topic'
            ,id: 'modx-'+this.ident+'-topic'
            ,itemId: 'topic'
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-language'
            ,fieldLabel: _('language')
            ,name: 'language'
            ,id: 'modx-'+this.ident+'-language'
            ,itemId: 'language'
            ,anchor: '100%'
        }]
    });
    MODx.window.ExportLexicon.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.ExportLexicon,MODx.Window);
Ext.reg('modx-window-lexicon-export',MODx.window.ExportLexicon);



MODx.window.LexiconEntryCreate = function(config) {
    config = config || {};
    this.ident = config.ident || 'lexentc'+Ext.id();
    var r = config.record;
    Ext.applyIf(config,{
        title: _('create')
        ,url: MODx.config.connector_url
        ,action: 'Workspace/Lexicon/Create'
        ,fileUpload: true
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,id: 'modx-'+this.ident+'-name'
            ,itemId: 'name'
            ,name: 'name'
            ,anchor: '100%'
            ,msgTarget: 'under'
            ,allowBlank: false
        },{
            xtype: 'modx-combo-namespace'
            ,fieldLabel: _('namespace')
            ,name: 'namespace'
            ,id: 'modx-'+this.ident+'-namespace'
            ,itemId: 'namespace'
            ,anchor: '100%'
            ,msgTarget: 'under'
            ,allowBlank: false
            ,listeners: {
                'select': {fn: function(cb,r,i) {
                    cle = this.fp.getComponent('topic');
                    if (cle) {
                        cle.store.baseParams['namespace'] = cb.getValue();
                        cle.setValue('');
                        cle.store.reload();
                    } else {MODx.debug('cle not found');}
                },scope:this}
            }
        },{
            xtype: 'modx-combo-lexicon-topic'
            ,fieldLabel: _('topic')
            ,name: 'topic'
            ,id: 'modx-'+this.ident+'-topic'
            ,itemId: 'topic'
            ,anchor: '100%'
            ,msgTarget: 'under'
            ,allowBlank: false
        },{
            xtype: 'modx-combo-language'
            ,fieldLabel: _('language')
            ,name: 'language'
            ,id: 'modx-'+this.ident+'-language'
            ,itemId: 'language'
            ,anchor: '100%'
            ,msgTarget: 'under'
            ,allowBlank: false
        },{
            xtype: 'textarea'
            ,fieldLabel: _('value')
            ,id: 'modx-'+this.ident+'-value'
            ,itemId: 'value'
            ,name: 'value'
            ,anchor: '100%'
            ,msgTarget: 'under'
        }]
    });
    MODx.window.LexiconEntryCreate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.LexiconEntryCreate,MODx.Window);
Ext.reg('modx-window-lexicon-entry-create',MODx.window.LexiconEntryCreate);
