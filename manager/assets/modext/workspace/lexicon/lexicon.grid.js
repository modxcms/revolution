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
        ,url: MODx.config.connectors_url+'workspace/lexicon/index.php'
        ,fields: ['id','name','value','namespace','topic','language','editedon','menu']
		,baseParams: {
			action: 'getList'
			,'namespace': 'core'
			,topic: ''
		}
        ,width: '98%'
        ,paging: true
        ,autosave: true
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
            ,sortable: true
        },{
            header: _('value')
            ,dataIndex: 'value'
            ,width: 500
            ,sortable: false
            ,editor: { xtype: 'textarea' }
        },{
            header: _('last_modified')
            ,dataIndex: 'editedon'
            ,width: 125
            
        }]
        ,tbar: [{
            text: _('namespace')+':'
        },{
			xtype: 'modx-combo-namespace'
			,id: 'modx-lexicon-filter-namespace'
			,value: 'core'
			,listeners: {
				'select': {fn: this.changeNamespace,scope:this}
			}
		},{
		    text: _('topic')+':'
		},{
			xtype: 'modx-combo-lexicon-topic'
			,id: 'modx-lexicon-filter-topic'
			,value: 'default'
            ,listeners: {
                'select': {fn:this.filter.createDelegate(this,['topic'],true),scope:this}
            }
		},{
		    text: _('language')+':'
		},{
			xtype: 'modx-combo-language'
			,name: 'language'
			,id: 'modx-lexicon-filter-language'
			,value: 'en'
            ,listeners: {
                'select': {fn:this.filter.createDelegate(this,['language'],true),scope:this}
            }
		},{
            text: _('create_new')
            ,xtype: 'button'
            ,menu: [{
                text: _('entry')
                ,handler: this.loadWindow2.createDelegate(this,[{ 
                    xtype: 'modx-window-lexicon-entry-create'
                }],true)
                ,scope: this
            },{
                text: _('topic')
                ,handler: this.loadWindow2.createDelegate(this,[{ 
                    xtype: 'modx-window-lexicon-topic-create'
                    ,listeners: {
                        'success': {fn:function() {
                            Ext.getCmp('modx-lexicon-filter-topic').store.reload();
                            Ext.getCmp('modx-grid-lexicon-topic').refresh();
                        },scope:this}
                    }
                }],true)
                ,scope: this
            },{
                text: _('namespace')
                ,handler: this.loadWindow2.createDelegate(this,[{
                	   xtype: 'modx-window-namespace-create'
                	   ,listeners: {
                            'success':{fn: function() {
                    	       Ext.getCmp('modx-lexicon-filter-namespace').store.reload();
                               var cb = Ext.getCmp('modx-lexicon-topic-filter-namespace');
                               if (cb) { cb.store.reload(); }
                            },scope: this}
                	   }
                }],true)
                ,scope: this
            }]
        }
		,'->'
		,{
		    xtype: 'textfield'
		    ,name: 'name'
		    ,id: 'modx-lexicon-filter-name'
		    ,emptyText: _('search')+'...'
            ,listeners: {
                'change': {fn:this.filter.createDelegate(this,['name'],true),scope:this}
                ,'render': {fn:function(tf) {
                    tf.getEl().addKeyListener(Ext.EventObject.ENTER,function() {
                        tf.fireEvent('change'); 
                    },this);
                }}
            }
		},{
            xtype: 'button'
            ,id: 'modx-lexicon-filter-clear'
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this}
            }
        }]
        ,pagingItems: [{
            text: _('reload_from_base')
            ,handler: this.reloadFromBase
            ,scope: this
        }
        ,'-'
        ,{
            xtype: 'button'
            ,id: 'modx-lexicon-import-btn'
            ,text: _('lexicon_import')
            ,handler: function(btn,e) {
                this.loadWindow2(btn,e,{ 
                    xtype: 'modx-window-lexicon-import'
                }); 
            }
            ,scope: this
        },{
            xtype: 'button'
            ,id: 'modx-lexicon-export-btn'
            ,text: _('lexicon_export')
            ,handler: function(btn,e) {
                this.loadWindow2(btn,e,{ 
                    xtype: 'modx-window-lexicon-export'
                    ,listeners: {
                        'success': {fn:function(o) {
                            location.href = MODx.config.connectors_url+'workspace/lexicon/index.php?action=export&download='+o.a.result.message;
                        },scope:this}
                    }
                }); 
            }
            ,scope: this
        }]
    });
    MODx.grid.Lexicon.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Lexicon,MODx.grid.Grid,{
    console: null
    
    ,filter: function(cb,r,i,name) {
    	if (!name) { return false; }
    	this.store.baseParams[name] = cb.getValue();
    	this.getBottomToolbar().changePage(1);
    	this.refresh();
    }
    ,clearFilter: function() {
    	this.store.baseParams = {
    		action: 'getList'
            ,'namespace': 'core'
            ,topic: 'default'
            ,language: 'en'
    	};
    	this.getBottomToolbar().changePage(1);
    	Ext.getCmp('modx-lexicon-filter-namespace').setValue('core');
    	Ext.getCmp('modx-lexicon-filter-topic').setValue('default');
    	Ext.getCmp('modx-lexicon-filter-language').setValue('en');
    	this.refresh();
    }
    ,changeNamespace: function(cb,nv,ov) {
    	var ft = Ext.getCmp('modx-lexicon-filter-topic');
        var s = ft.store;
    	s.baseParams['namespace'] = cb.getValue();
    	s.reload();
        ft.setValue('');
    	
    	this.filter(cb,null,1,'namespace');
    }
    ,loadWindow2: function(btn,e,o) {
    	this.menu.record = {
            'namespace': Ext.getCmp('modx-lexicon-filter-namespace').getValue()
            ,language: Ext.getCmp('modx-lexicon-filter-language').getValue()
        };
        if (o.xtype != 'modx-window-lexicon-import') {
        	this.menu.record.topic = Ext.getCmp('modx-lexicon-filter-topic').getValue();
        }
        var clef = Ext.getCmp('modx-cle-topic');
        if (clef) { clef.store.baseParams['namespace'] = this.menu.record['namespace']; }
    	this.loadWindow(btn, e, o);
    }
    ,reloadFromBase: function() {
    	Ext.Ajax.timeout = 0;
    	var topic = '/workspace/lexicon/reload/';
        if (this.console === null) {
            this.console = MODx.load({
               xtype: 'modx-console'
               ,register: 'mgr'
               ,topic: topic
            });
        } else {
            this.console.setRegister('mgr',topic);
        }
        this.console.show(Ext.getBody());
    	
    	MODx.Ajax.request({
    	   url: this.config.url
    	   ,params: { action: 'reloadFromBase' ,register: 'mgr' ,topic: topic }
    	   ,listeners: {
    	       'success': {fn:function(r) {
        	       this.console.fireEvent('complete');
                   this.refresh();
        	   },scope:this}
	       }
    	});
    }
});
Ext.reg('modx-grid-lexicon',MODx.grid.Lexicon);

/**
 * Generates the create lexicon entry window.
 *  
 * @class MODx.window.CreateLexiconEntry
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-lexicon-entry-create
 */
MODx.window.CreateLexiconEntry = function(config) {
    config = config || {};
    this.ident = config.ident || 'cle'+Ext.id();
    var r = config.record;
    Ext.applyIf(config,{
        title: _('entry_create')
        ,url: MODx.config.connectors_url+'workspace/lexicon/index.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('key')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,width: 250
            ,maxLength: 100
        },{
            xtype: 'modx-combo-namespace'
            ,fieldLabel: _('namespace')
            ,name: 'namespace'
            ,id: 'modx-'+this.ident+'-namespace'
            ,value: r['namespace']
            ,listeners: {
            	'select': {fn: function(cb,r,i) {
                    cle = Ext.getCmp('modx-cle-topic');
                    cle.store.baseParams['namespace'] = cb.getValue();
                    cle.store.reload();
                    cle.setValue('default');
            	},scope:this}
            }
        },{
            xtype: 'modx-combo-lexicon-topic'
            ,fieldLabel: _('topic')
            ,name: 'topic'
            ,id: 'modx-'+this.ident+'-topic'
            ,value: r.topic
        },{
            xtype: 'modx-combo-language'
            ,fieldLabel: _('language')
            ,name: 'language'
            ,id: 'modx-'+this.ident+'-language'
            ,value: r.language
        },{
            xtype: 'textarea'
            ,fieldLabel: _('value')
            ,name: 'value'
            ,id: 'modx-'+this.ident+'-value'
            ,width: 300
            ,grow: true
        }]
    });
    MODx.window.CreateLexiconEntry.superclass.constructor.call(this,config);
    this.on('show',this.preFillTopic,this);
};
Ext.extend(MODx.window.CreateLexiconEntry,MODx.Window,{
    preFillTopic: function() {
        var ns = this.fp.getForm().findField('namespace').getValue();        
        var fld = this.fp.getForm().findField('topic');
        fld.store.baseParams['namespace'] = ns;
        var v = fld.getValue();
        fld.store.load({
            callback: function(r,o,s) {
                fld.setValue(v);
            }
        });
    }
});
Ext.reg('modx-window-lexicon-entry-create',MODx.window.CreateLexiconEntry);


/**
 * Generates the update lexicon entry window.
 *  
 * @class MODx.window.UpdateLexiconEntry
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-lexicon-entry-update
 */
MODx.window.UpdateLexiconEntry = function(config) {
    config = config || {};
    this.ident = config.ident || 'ule'+Ext.id();
    var r = config.record;
    Ext.applyIf(config,{
        title: _('entry_update')
        ,url: MODx.config.connectors_url+'workspace/lexicon/index.php'
        ,action: 'update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-'+this.ident+'-id'
            ,value: r.id
        },{
            xtype: 'textfield'
            ,fieldLabel: _('key')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,width: 250
            ,maxLength: 100
            ,value: r.name
        },{
            xtype: 'modx-combo-lexicon-topic'
            ,fieldLabel: _('topic')
            ,name: 'topic'
            ,id: 'modx-'+this.ident+'-topic'
            ,value: r.topic
        },{
            xtype: 'modx-combo-namespace'
            ,fieldLabel: _('namespace')
            ,name: 'namespace'
            ,id: 'modx-'+this.ident+'-namespace'
            ,value: r['namespace']
            ,listeners: {
                'select': {fn: function(cb,r,i) {
                    cle = Ext.getCmp('modx-ule-topic');
                    cle.store.baseParams['namespace'] = cb.getValue();
                    cle.store.reload();
                    cle.setValue('default');
                },scope:this}
            }
        },{
            xtype: 'modx-combo-language'
            ,fieldLabel: _('language')
            ,name: 'language'
            ,id: 'modx-'+this.ident+'-language'
            ,value: r.language
        },{
            xtype: 'textarea'
            ,fieldLabel: _('value')
            ,name: 'value'
            ,id: 'modx-'+this.ident+'-value'
            ,width: 300
            ,grow: true
            ,value: r.value
        }]
    });
    MODx.window.UpdateLexiconEntry.superclass.constructor.call(this,config);
    this.on('show',this.preFillTopic,this);
};
Ext.extend(MODx.window.UpdateLexiconEntry,MODx.Window,{
    preFillTopic: function() {
        var ns = this.fp.getForm().findField('namespace').getValue();        
        var fld = this.fp.getForm().findField('topic');
        fld.store.baseParams['namespace'] = ns;
        var v = fld.getValue();
        fld.store.load({
            callback: function(r,o,s) {
                fld.setValue(v);
            }
        });
    }
});
Ext.reg('modx-window-lexicon-entry-update',MODx.window.UpdateLexiconEntry);

/**
 * Generates the create lexicon topic window.
 *  
 * @class MODx.window.CreateLexiconTopic
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-lexicon-topic-create
 */
MODx.window.CreateLexiconTopic = function(config) {
    config = config || {};
    this.ident = config.ident || 'clt'+Ext.id();
    var r = config.record;
    Ext.applyIf(config,{
        title: _('topic_create')
        ,url: MODx.config.connectors_url+'workspace/lexicon/topic.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,width: 250
            ,maxLength: 100
        },{
            xtype: 'modx-combo-namespace'
            ,fieldLabel: _('namespace')
            ,name: 'namespace'
            ,id: 'modx-'+this.ident+'-namespace'
            ,value: r['namespace']
        }]
    });
    MODx.window.CreateLexiconTopic.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateLexiconTopic,MODx.Window);
Ext.reg('modx-window-lexicon-topic-create',MODx.window.CreateLexiconTopic);


/**
 * Generates the import lexicon window.
 *  
 * @class MODx.window.ImportLexicon
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-lexicon-import
 */
MODx.window.ImportLexicon = function(config) {
    config = config || {};
    this.ident = config.ident || 'implex'+Ext.id();
    var r = config.record;
    Ext.applyIf(config,{
        title: _('lexicon_import')
        ,url: MODx.config.connectors_url+'workspace/lexicon/index.php'
        ,action: 'import'
        ,fileUpload: true
        ,fields: [{
            html: _('lexicon_import_desc')
            ,border: false
            ,bodyStyle: 'margin: 1em;'
            ,id: 'modx-'+this.ident+'-desc'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('lexicon')
            ,name: 'lexicon'
            ,id: 'modx-'+this.ident+'-lexicon'
            ,width: 250
            ,inputType: 'file'
        },{
            xtype: 'modx-combo-namespace'
            ,fieldLabel: _('namespace')
            ,name: 'namespace'
            ,id: 'modx-'+this.ident+'-namespace'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('topic')
            ,name: 'topic'
            ,id: 'modx-'+this.ident+'-topic'
        },{
            xtype: 'modx-combo-language'
            ,fieldLabel: _('language')
            ,name: 'language'
            ,id: 'modx-'+this.ident+'-language'
        }]
    });
    MODx.window.ImportLexicon.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.ImportLexicon,MODx.Window);
Ext.reg('modx-window-lexicon-import',MODx.window.ImportLexicon);



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
        title: _('lexicon_export')
        ,url: MODx.config.connectors_url+'workspace/lexicon/index.php'
        ,action: 'export'
        ,fileUpload: true
        ,fields: [{
            html: _('lexicon_export_desc')
            ,border: false
            ,bodyStyle: 'margin: 1em;'
            ,id: 'modx-'+this.ident+'-desc'
        },{
            xtype: 'modx-combo-namespace'
            ,fieldLabel: _('namespace')
            ,name: 'namespace'
            ,id: 'modx-'+this.ident+'-namespace'
            ,listeners: {
                'select': {fn: function(cb,r,i) {
                    cle = Ext.getCmp('modx-elex-topic');
                    cle.store.baseParams['namespace'] = cb.getValue();
                    cle.store.reload();
                    cle.setValue('');
                },scope:this}
            }
        },{
            xtype: 'modx-combo-lexicon-topic'
            ,fieldLabel: _('topic')
            ,name: 'topic'
            ,id: 'modx-'+this.ident+'-topic'
        },{
            xtype: 'modx-combo-language'
            ,fieldLabel: _('language')
            ,name: 'language'
            ,id: 'modx-'+this.ident+'-language'
        }]
    });
    MODx.window.ExportLexicon.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.ExportLexicon,MODx.Window);
Ext.reg('modx-window-lexicon-export',MODx.window.ExportLexicon);
