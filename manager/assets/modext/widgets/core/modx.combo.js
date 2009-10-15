Ext.namespace('MODx.combo');
/* fixes combobox value loading issue */
Ext.override(Ext.form.ComboBox, {
    loaded: false
    ,setValue: Ext.form.ComboBox.prototype.setValue.createSequence(function(v) {
        var idx = this.store.find(this.valueField, v);

        if (v && v !== 0 && this.mode == 'remote' && idx == -1 && !this.loaded) {
            var p = {};
            p[this.valueField] = v;
            this.loaded = true;

            this.store.load({
                scope: this,
                params: p,
                callback: function() {
                    this.setValue(v);
                    this.collapse();
                }
            });
        }
    })
});

/**
 * An abstraction of Ext.form.ComboBox with connector ability.
 * 
 * @class MODx.combo.ComboBox
 * @extends Ext.form.ComboBox
 * @constructor
 * @param {Object} config An object of config properties
 * @param {Boolean} getStore If true, will return the store.
 */
MODx.combo.ComboBox = function(config,getStore) {
	config = config || {};
    Ext.applyIf(config,{
        displayField: 'name'
        ,valueField: 'id'
        ,triggerAction: 'all'
        ,fields: ['id','name']
        ,baseParams: {
            action: 'getList'
        }
        ,width: 150
        ,listWidth: 300
        ,editable: false
        ,resizable: true
        ,typeAhead: false
        ,forceSelection: true
        ,minChars: 3
        ,cls: 'modx-combo'
    });
    Ext.applyIf(config,{
        store: new Ext.data.JsonStore({
            url: config.connector || config.url
            ,root: 'results'
            ,totalProperty: 'total'
            ,fields: config.fields
            ,errorReader: MODx.util.JSONReader
            ,baseParams: config.baseParams || {}
            ,remoteSort: config.remoteSort || false
            ,autoDestroy: true
        })
    });
	if (getStore === true) {
	   config.store.load();
	   return config.store;
	}
	MODx.combo.ComboBox.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.combo.ComboBox,Ext.form.ComboBox);
Ext.reg('modx-combo',MODx.combo.ComboBox);

/**
 * Helps with rendering of comboboxes in grids.
 * @class MODx.combo.Renderer
 * @param {Ext.form.ComboBox} combo The combo to display
 */
MODx.combo.Renderer = function(combo) {
    var loaded = false;
    return (function(v) {
        var idx,rec;
        if (!combo.store) return v;
        if (!loaded) {
            if (combo.store.proxy !== undefined && combo.store.proxy !== null) {
                combo.store.load();
            }
            loaded = true;
        }
        idx = combo.store.find(combo.valueField,v);
        rec = combo.store.getAt(idx);
        return (rec === undefined || rec === null ? v : rec.get(combo.displayField));
    });
};

/**
 * Displays a yes/no combobox
 * 
 * @class MODx.combo.Boolean
 * @extends Ext.form.ComboBox
 * @xtype modx-combo-boolean
 */
MODx.combo.Boolean = function(config) {
    config = config || {};
    Ext.applyIf(config,{
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
        ,preventRender: true
    });
    MODx.combo.Boolean.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Boolean,MODx.combo.ComboBox);
Ext.reg('combo-boolean',MODx.combo.Boolean);
Ext.reg('modx-combo-boolean',MODx.combo.Boolean);

/**
 * Displays a dropdown list of modUsers
 * 
 * @class MODx.combo.User
 * @extends MODx.combo.ComboBox
 * @xtype modx-combo-user
 */
MODx.combo.User = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'user'
		,hiddenName: 'user'
		,displayField: 'username'
		,valueField: 'id'
		,fields: ['username','id']
		,url: MODx.config.connectors_url+'security/user.php'
	});
	MODx.combo.User.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.User,MODx.combo.ComboBox);
Ext.reg('modx-combo-user',MODx.combo.User);

/**
 * Displays a dropdown list of modUsers
 * 
 * @class MODx.combo.User
 * @extends MODx.combo.ComboBox
 * @xtype modx-combo-usergroup
 */
MODx.combo.UserGroup = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'group'
		,hiddenName: 'group'
		,displayField: 'name'
		,valueField: 'id'
		,fields: ['name','id']
		,listWidth: 300
		,url: MODx.config.connectors_url+'security/group.php'
	});
	MODx.combo.UserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.UserGroup,MODx.combo.ComboBox);
Ext.reg('modx-combo-usergroup',MODx.combo.UserGroup);

/**
 * Displays a dropdown list of modUserGroupRoles.
 * 
 * @class MODx.combo.UserGroupRole
 * @extends MODx.combo.ComboBox
 * @xtype modx-combo-usergrouprole
 */
MODx.combo.UserGroupRole = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'role'
		,hiddenName: 'role'
		,displayField: 'name'
		,valueField: 'id'
		,fields: ['name','id']
		,url: MODx.config.connectors_url+'security/role.php'
	});
	MODx.combo.UserGroupRole.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.UserGroupRole,MODx.combo.ComboBox);
Ext.reg('modx-combo-usergrouprole',MODx.combo.UserGroupRole);

/**
 * Displays a dropdown list of modResourceGroups.
 * 
 * @class MODx.combo.ResourceGroup
 * @extends MODx.combo.ComboBox
 * @xtype modx-combo-resourcegroup
 */
MODx.combo.ResourceGroup = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        name: 'resourcegroup'
        ,hiddenName: 'resourcegroup'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['name','id']
        ,url: MODx.config.connectors_url+'security/resourcegroup.php'
    });
    MODx.combo.ResourceGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ResourceGroup,MODx.combo.ComboBox);
Ext.reg('modx-combo-resourcegroup',MODx.combo.ResourceGroup);

/**
 * Displays a dropdown list of modContexts.
 * 
 * @class MODx.combo.Context
 * @extends MODx.combo.ComboBox
 * @xtype modx-combo-context
 */
MODx.combo.Context = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        name: 'context'
        ,hiddenName: 'context'
        ,displayField: 'key'
        ,valueField: 'key'
        ,fields: ['key']
        ,url: MODx.config.connectors_url+'context/index.php'
    });
    MODx.combo.Context.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Context,MODx.combo.ComboBox);
Ext.reg('modx-combo-context',MODx.combo.Context);

/**
 * Displays a dropdown list of modPolicies.
 * 
 * @class MODx.combo.Policy
 * @extends MODx.combo.ComboBox
 * @xtype modx-combo-policy
 */
MODx.combo.Policy = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        name: 'policy'
        ,hiddenName: 'policy'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['name','id']
        ,allowBlank: false
        ,editable: false
        ,url: MODx.config.connectors_url+'security/access/policy.php'
    });
    MODx.combo.Policy.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Policy,MODx.combo.ComboBox);
Ext.reg('modx-combo-policy',MODx.combo.Policy);

/**
 * Displays a dropdown list of modTemplates.
 * 
 * @class MODx.combo.Template
 * @extends MODx.combo.ComboBox
 * @xtype modx-combo-template
 */
MODx.combo.Template = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'template'
		,hiddenName: 'template'
		,displayField: 'templatename'
		,valueField: 'id'
		,fields: ['id','templatename','description','category']
		,tpl: new Ext.XTemplate('<tpl for="."><div class="x-combo-list-item"><span style="font-weight: bold">{templatename}</span>'
							   ,' - <span style="font-style:italic">{category}</span>'
							   ,'<br />{description}</div></tpl>')
		,url: MODx.config.connectors_url+'element/template.php'
        ,listWidth: 350
        ,allowBlank: true
	});
	MODx.combo.Template.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Template,MODx.combo.ComboBox);
Ext.reg('modx-combo-template',MODx.combo.Template);

/**
 * Displays a dropdown list of modCategories.
 * 
 * @class MODx.combo.Category
 * @extends MODx.combo.ComboBox
 * @xtype combo-category
 */
MODx.combo.Category = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'category'
		,hiddenName: 'category'
		,displayField: 'name'
		,valueField: 'id'
		,mode: 'remote'
		,fields: ['id','category','parent','name']
		,forceSelection: true
		,typeAhead: false
		,allowBlank: true
		,editable: false
        ,enableKeyEvents: true
		,url: MODx.config.connectors_url+'element/category.php'
        ,baseParams: { action: 'getList' ,showNone: true }
	});
	MODx.combo.Category.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Category,MODx.combo.ComboBox,{
    _onblur: function(t,e) { 
        var v = this.getRawValue();
        this.setRawValue(v);
        this.setValue(v,true);
    }
});
Ext.reg('modx-combo-category',MODx.combo.Category);

/**
 * Displays a dropdown list of languages.
 * 
 * @class MODx.combo.Language
 * @extends MODx.combo.ComboBox
 * @xtype modx-combo-language
 */
MODx.combo.Language = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'language'
		,hiddenName: 'language'
		,displayField: 'name'
		,valueField: 'name'
		,fields: ['name']
		,forceSelection: true
		,typeAhead: false
		,editable: false
		,allowBlank: false
		,url: MODx.config.connectors_url+'system/language.php'
	});
	MODx.combo.Language.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Language,MODx.combo.ComboBox);
Ext.reg('modx-combo-language',MODx.combo.Language);

/**
 * Displays a dropdown list of available charsets.
 * 
 * @class MODx.combo.Charset
 * @extends MODx.combo.ComboBox
 * @constructor
 * @xtype combo-charset
 */
MODx.combo.Charset = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'charset'
		,hiddenName: 'charset'
		,displayField: 'text'
		,valueField: 'value'
		,fields: ['value','text']
		,forceSelection: true
		,typeAhead: false
		,editable: false
		,allowBlank: false
		,listWidth: 300
		,url: MODx.config.connectors_url+'system/charset.php'
	});
	MODx.combo.Charset.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Charset,MODx.combo.ComboBox);
Ext.reg('modx-combo-charset',MODx.combo.Charset);

/**
 * Displays a dropdown list of available RTEs.
 * 
 * @class MODx.combo.RTE
 * @extends MODx.combo.ComboBox
 * @constructor
 * @xtype combo-rte
 */
MODx.combo.RTE = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		name: 'rte'
		,hiddenName: 'rte'
		,displayField: 'value'
		,valueField: 'value'
		,fields: ['value']
		,forceSelection: true
		,typeAhead: false
		,editable: false
		,allowBlank: false
		,listWidth: 300
		,url: MODx.config.connectors_url+'system/rte.php'
	});
	MODx.combo.RTE.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.RTE,MODx.combo.ComboBox);
Ext.reg('modx-combo-rte',MODx.combo.RTE);

/**
 * Displays a dropdown list of available Roles.
 * 
 * @class MODx.combo.Role
 * @extends MODx.combo.ComboBox
 * @xtype combo-role
 */
MODx.combo.Role = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'role'
        ,hiddenName: 'role'
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,listWidth: 300
        ,url: MODx.config.connectors_url+'security/role.php'
        ,baseParams: { action: 'getList', addNone: true }
    });
    MODx.combo.Role.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Role,MODx.combo.ComboBox);
Ext.reg('modx-combo-role',MODx.combo.Role);

/**
 * Displays a dropdown list of available Content Types.
 * 
 * @class MODx.combo.ContentType
 * @extends MODx.combo.ComboBox
 * @xtype modx-combo-content-type
 */
MODx.combo.ContentType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'content_type'
        ,hiddenName: 'content_type'
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,listWidth: 300
        ,url: MODx.config.connectors_url+'system/contenttype.php'
        ,baseParams: { action: 'getList' }
    });
    MODx.combo.ContentType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ContentType,MODx.combo.ComboBox);
Ext.reg('modx-combo-content-type',MODx.combo.ContentType);

/**
 * Displays a content disposition combo
 * 
 * @class MODx.combo.ContentDisposition
 * @extends Ext.form.ComboBox
 * @constructor
 * @xtype combo-boolean
 */
MODx.combo.ContentDisposition = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: [[_('inline'),0],[_('attachment'),1]]
        })
        ,name: 'content_dispo'
        ,hiddenName: 'content_dispo'
        ,width: 200
        ,displayField: 'd'
        ,valueField: 'v'
        ,mode: 'local'
        ,triggerAction: 'all'
        ,editable: false
        ,selectOnFocus: false
        ,preventRender: true
    });
    MODx.combo.ContentDisposition.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ContentDisposition,Ext.form.ComboBox);
Ext.reg('modx-combo-content-disposition',MODx.combo.ContentDisposition);


/**
 * Displays a dropdown list of class keys
 * 
 * @class MODx.combo.ClassKey
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of options.
 * @xtype modx-combo-class-key
 */
MODx.combo.ClassKey = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'classKey'
        ,hiddenName: 'classKey'
        ,url: MODx.config.connectors_url+'workspace/builder/index.php'
        ,baseParams: { 
            action: 'getClassKeys'
        }
        ,displayField: 'key'
        ,valueField: 'key'
        ,fields: ['key']
        ,editable: false
    });
    MODx.combo.ClassKey.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ClassKey,MODx.combo.ComboBox);
Ext.reg('modx-combo-class-key',MODx.combo.ClassKey);


/**
 * Displays a dropdown list of various objects, dynamically chosen
 * by a class key
 * 
 * @class MODx.combo.Object
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of options.
 * @xtype modx-combo-object
 */
MODx.combo.Object = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'object'
        ,hiddenName: 'object'
        ,url: MODx.config.connectors_url+'workspace/builder/index.php'
        ,baseParams: { 
            action: 'getAssocObject'
            ,class_key: 'modResource'
        }
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name']
        ,pageSize: 10
        ,editable: false
    });
    MODx.combo.Object.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Object,MODx.combo.ComboBox);
Ext.reg('modx-combo-object',MODx.combo.Object);

/**
 * Displays a dropdown list of available Content Types.
 * 
 * @class MODx.combo.ContentType
 * @extends MODx.combo.ComboBox
 * @xtype modx-combo-namespace
 */
MODx.combo.Namespace = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'namespace'
        ,hiddenName: 'namespace'
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,listWidth: 300
        ,url: MODx.config.connectors_url+'workspace/namespace.php'
        ,fields: ['name']
        ,displayField: 'name'
        ,valueField: 'name'
    });
    MODx.combo.Namespace.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Namespace,MODx.combo.ComboBox);
Ext.reg('modx-combo-namespace',MODx.combo.Namespace);



/**
 * Launches MODx.Browser in a nice, clean way.
 * 
 * @class MODx.combo.Browser
 * @extends Ext.form.TriggerField
 * @param {Object} config An object of configuration properties
 * @xtype modx-combo-browser
 */
MODx.combo.Browser = function(config) {
    config = config || {};
    Ext.applyIf(config,{
       width: 300
       ,triggerAction: 'all'
       ,browserEl: 'modx-browser'
    });
    MODx.combo.Browser.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.combo.Browser,Ext.form.TriggerField,{
    browser: null
    
    ,onTriggerClick : function(){
        if (this.disabled){
            return false;
        }
        
        if (this.browser === null) {
            this.browser = MODx.load({
                xtype: 'modx-browser'
                ,prependPath: this.config.prependPath || null
                ,prependUrl: this.config.prependUrl || null
                ,hideFiles: this.config.hideFiles || false
                ,rootVisible: this.config.rootVisible || false
                ,listeners: {
                    'select': {fn: function(data) {
                        this.setValue(data.url);
                        this.fireEvent('select',data);
                    },scope:this}
                }
            });
        }
        this.browser.show();
    }
    
    ,onDestroy: function(){
        MODx.combo.Browser.superclass.onDestroy.call(this);
    }
});
Ext.reg('modx-combo-browser',MODx.combo.Browser);


/**
 * Displays a dropdown list of available countries.
 * 
 * @class MODx.combo.Country
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of configuration properties
 * @xtype modx-combo-country
 */
MODx.combo.Country = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'country'
        ,hiddenName: 'country'
        ,url: MODx.config.connectors_url+'system/country.php'
        ,displayField: 'value'
        ,valueField: 'value'
        ,fields: ['value']
        ,editable: false
        ,value: 0
    });
    MODx.combo.Country.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Country,MODx.combo.ComboBox);
Ext.reg('modx-combo-country',MODx.combo.Country);


/**
 * Displays a dropdown list of property sets.
 * 
 * @class MODx.combo.PropertySet
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of configuration properties
 * @xtype modx-combo-property-set
 */
MODx.combo.PropertySet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'propertyset'
        ,hiddenName: 'propertyset'
        ,url: MODx.config.connectors_url+'element/propertyset.php'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name']
        ,editable: false
        ,value: 0
    });
    MODx.combo.PropertySet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.PropertySet,MODx.combo.ComboBox);
Ext.reg('modx-combo-property-set',MODx.combo.PropertySet);


MODx.ChangeParentField = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        triggerAction: 'all'
        ,editable: false
        ,readOnly: true
        ,formpanel: 'modx-panel-resource'
    });    
    MODx.ChangeParentField.superclass.constructor.call(this,config);
    this.config = config;
    this.on('click',this.onTriggerClick,this);
    this.addEvents({ end: true });
    this.on('end',this.end,this);
};
Ext.extend(MODx.ChangeParentField,Ext.form.TriggerField,{
    oldValue: false
    ,oldDisplayValue: false
    ,end: function(p) {
        var t = Ext.getCmp('modx-resource-tree');
        if (!t) return;
        p.d = p.d || p.v;
        
        t.removeListener('click',this.handleChangeParent,this);
        t.on('click',t._handleClick,t);
        t.disableHref = false;
        
        Ext.getCmp('modx-resource-parent-hidden').setValue(p.v);
        
        this.setValue(p.d);
        this.oldValue = false;
        
        Ext.getCmp(this.config.formpanel).fireEvent('fieldChange');
    }
    ,onTriggerClick: function() {
        if (this.disabled) { return false; }
        if (this.oldValue) {
            this.fireEvent('end',{
                v: this.oldValue
                ,d: this.oldDisplayValue
            });
            return false;
        }
        
        var t = Ext.getCmp('modx-resource-tree');
        if (!t) return;
        
        this.oldDisplayValue = this.getValue();
        this.oldValue = Ext.getCmp('modx-resource-parent-hidden').getValue();
        
        this.setValue(_('resource_parent_select_node'));
        
        t.expand();
        t.removeListener('click',t._handleClick);
        t.on('click',this.handleChangeParent,this);
        t.disableHref = true;
    }
        
    ,handleChangeParent: function(node,e) {
        
        var t = Ext.getCmp('modx-resource-tree');
        if (!t) return;
        t.disableHref = true;
        
        var id = node.id.split('_'); id = id[1];
        if (id == MODx.request.id) {
            MODx.msg.alert('',_('resource_err_own_parent'));            
            return;
        }
        
        this.fireEvent('end',{
            v: id
            ,d: node.text
        });
        e.preventDefault();
        e.stopEvent();
        return;
    }
});
Ext.reg('modx-field-parent-change',MODx.ChangeParentField);


MODx.combo.TVWidget = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'widget'
        ,hiddenName: 'widget'
        ,displayField: 'name'
        ,valueField: 'value'
        ,fields: ['value','name']
        ,editable: false
        ,url: MODx.config.connectors_url+'element/tv/renders.php'
        ,baseParams: {
            action: 'getOutputs'
        }
        ,value: 'default'
    });
    MODx.combo.TVWidget.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.TVWidget,MODx.combo.ComboBox);
Ext.reg('modx-combo-tv-widget',MODx.combo.TVWidget);

MODx.combo.TVInputType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'type'
        ,hiddenName: 'type'
        ,displayField: 'name'
        ,valueField: 'value'
        ,editable: false
        ,fields: ['value','name']
        ,url: MODx.config.connectors_url+'element/tv/renders.php'
        ,baseParams: {
            action: 'getInputs'
        }
        ,value: 'text'
    });
    MODx.combo.TVInputType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.TVInputType,MODx.combo.ComboBox);
Ext.reg('modx-combo-tv-input-type',MODx.combo.TVInputType);

MODx.combo.Action = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'action'
        ,hiddenName: 'action'
        ,displayField: 'controller'
        ,valueField: 'id'
        ,fields: ['id','controller']
        ,url: MODx.config.connectors_url+'system/action.php'
    });
    MODx.combo.Action.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Action,MODx.combo.ComboBox);
Ext.reg('modx-combo-action',MODx.combo.Action);