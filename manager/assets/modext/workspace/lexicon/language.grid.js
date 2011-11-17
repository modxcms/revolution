/**
 * Loads a grid for managing languages.
 * 
 * @class MODx.grid.Language
 * @extends MODx.grid.Grid
 * @param {Object} config An object of configuration properties
 * @xtype modx-grid-language
 */
MODx.grid.Language = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('languages')
        ,id: 'modx-grid-language'
        ,url: MODx.config.connectors_url+'system/language.php'
        ,fields: ['id','name','menu']
        ,width: '97%'
        ,paging: true
        ,autosave: true
        ,primaryKey: 'name'
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 200
            ,sortable: true
        }]
        ,tbar: [{
            text: _('language_create')
            ,handler: { 
                xtype: 'modx-window-language-create'
                ,listeners: {
                    'success':{fn:function(o) {
                        var r = o.a.result.object;
                        this.refresh();
                        
                        var g = Ext.getCmp('modx-grid-lexicon');
                        if (g) {
                            g.setFilterParams(null,null,r.name);
                        }
                    },scope:this}
                }
            }
            ,scope: this
        }]
    });
    MODx.grid.Language.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Language,MODx.grid.Grid,{
    duplicateLanguage: function(btn,e) {
        var df = Ext.Ajax.timeout;
        Ext.Ajax.timeout = 0;
        this.menu.record.new_name = _('duplicate_of')+this.menu.record.name;
        this.loadWindow(btn,e,{
            xtype: 'modx-window-language-duplicate'
            ,record: this.menu.record
            ,listeners: {
                'success': {fn:function(r) {
                    Ext.Ajax.timeout = df;
                    this.refresh();
                    var g = Ext.getCmp('modx-grid-lexicon');
                    if (g) {
                        g.setFilterParams(null,null,r.name);
                    }
                },scope:this}
            }
        });
    }
});
Ext.reg('modx-grid-language',MODx.grid.Language);

/**
 * Generates the create language window.
 *  
 * @class MODx.window.CreateLanguage
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-language-create
 */
MODx.window.CreateLanguage = function(config) {
    config = config || {};
    var r = config.record;
    Ext.applyIf(config,{
        title: _('language_create')
        ,url: MODx.config.connectors_url+'system/language.php'
        ,action: 'create'
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'name'
            ,itemId: 'name'
            ,anchor: '95%'
            ,maxLength: 100
            ,allowBlank: false
        }]
    });
    MODx.window.CreateLanguage.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateLanguage,MODx.Window);
Ext.reg('modx-window-language-create',MODx.window.CreateLanguage);

MODx.window.DuplicateLanguage = function(config) {
    config = config || {};
    var r = config.record;
    Ext.applyIf(config,{
        title: _('language_duplicate')
        ,url: MODx.config.connectors_url+'system/language.php'
        ,action: 'duplicate'
        ,fields: [{
            xtype: 'statictextfield'
            ,fieldLabel: _('duplicate')
            ,name: 'name'
            ,itemId: 'name'
            ,anchor: '100%'
            ,maxLength: 100
            ,allowBlank: false
            ,submitValue: true
        },{
            xtype: 'textfield'
            ,fieldLabel: _('language_new_name')
            ,description: _('language_new_name_desc')
            ,name: 'new_name'
            ,itemId: 'new_name'
            ,anchor: '100%'
            ,allowBlank: false
        },{
            xtype: 'xcheckbox'
            ,boxLabel: _('language_recursive')
            ,description: _('language_recursive_desc')
            ,name: 'recursive'
            ,itemId: 'recursive'
            ,inputValue: 1
            ,checked: true
        }]
    });
    MODx.window.DuplicateLanguage.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.DuplicateLanguage,MODx.Window);
Ext.reg('modx-window-language-duplicate',MODx.window.DuplicateLanguage);
