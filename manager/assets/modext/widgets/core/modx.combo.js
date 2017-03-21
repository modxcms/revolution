Ext.namespace('MODx.combo');
/* disable shadows for the combo-list globally, saves a few dom nodes as it's not used anyways */
Ext.form.ComboBox.prototype.shadow = false;
/* replaces the default img tag for the combo trigger with a div to make the use of iconfonts with :before possible */
Ext.override(Ext.form.TriggerField, {
    // this is the exact method from the source code, just the triggerConfig is modified to not use an img tag
    // We cannot override the prototype Ext.form.TriggerField.prototype.triggerConfig because we loose the option to add a custom triggerClass
    onRender: function(ct, position){
        this.doc = Ext.isIE ? Ext.getBody() : Ext.getDoc();
        Ext.form.TriggerField.superclass.onRender.call(this, ct, position);

        this.wrap = this.el.wrap({cls: 'x-form-field-wrap x-form-field-trigger-wrap'});
        this.trigger = this.wrap.createChild(this.triggerConfig ||
                {tag: 'div', cls: 'x-form-trigger ' + (this.triggerClass || '')});
        this.initTrigger();
        if(!this.width){
            this.wrap.setWidth(this.el.getWidth()+this.trigger.getWidth());
        }
        this.resizeEl = this.positionEl = this.wrap;
    }
});
/* store the original onLoad method to have acces to it in the override */
var originalComboBoxOnLoad = Ext.form.ComboBox.prototype.onLoad;
/* fixes combobox value loading issue */
Ext.override(Ext.form.ComboBox, {
    loaded: false
    ,setValue: Ext.form.ComboBox.prototype.setValue.createSequence(function(v) {
        var a = this.store.find(this.valueField, v);
        if (typeof v !== 'undefined' && v !== null && this.mode == 'remote' && a == -1 && !this.loaded) {
            var p = {};
            p[this.valueField] = v;
            this.loaded = true;
            this.store.load({
                scope: this
                ,params: p
                ,callback: function() {
                    this.setValue(v);
                    this.collapse()
                }
            })
        }
    })
    // this sets the width of combobox dropdown lists automatically to the width of the combobox element
    // and thus prevents the sometimes unnecessary wide dropdowns
    ,onLoad: function() {
        var ret = originalComboBoxOnLoad.apply(this,arguments);
        // true flag on getWidth() to ignore border and padding
        var maxwidth = Math.max(this.minListWidth || 0, this.wrap.getWidth(true));
        this.list.setWidth(maxwidth);
        return ret;
    }
});

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
        // ,listWidth: 300
        ,editable: false
        ,resizable: true
        ,typeAhead: false
        ,forceSelection: true
        ,minChars: 3
        ,cls: 'modx-combo'
        ,tries: 0
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
            ,listeners: {
                'loadexception': {fn: function(o,trans,resp) {
                    var status = _('code') + ': ' + resp.status + ' ' + resp.statusText + '<br/>';
                    MODx.msg.alert(_('error'), status + resp.responseText);
                }}
            }
        })
    });
    if (getStore === true) {
       config.store.load();
       return config.store;
    }
    MODx.combo.ComboBox.superclass.constructor.call(this,config);
    this.config = config;
    this.addEvents({
        'loaded': true
    });
    // remove the custom open class on collapse
    this.on('collapse', function() {
        this.wrap.removeClass('x-trigger-wrap-open');
    });
    this.store.on('load', function() {
        // Workaround to let the combobox know the store is loaded (to help hide/display the pagination if required)
        this.fireEvent('loaded', this);
        this.loaded = true;
    }, this, {
        single: true
    });
    return this;
};
Ext.extend(MODx.combo.ComboBox,Ext.form.ComboBox, {
    expand : function(){
        if(this.isExpanded() || !this.hasFocus){
            return;
        }

        // unfortunately there is no default indicator wether a combo is open or not, so we add a class here
        this.wrap.addClass('x-trigger-wrap-open');

        if (this.mode == 'remote' && !this.loaded && this.tries < 4) {
            // Store not yet loaded, let's wait a little bit
            this.tries += 1;
            Ext.defer(this.expand, 250, this);
            return false;
        }
        this.tries = 0;

        if(this.title || this.pageSize){
            this.assetHeight = 0;
            if(this.title){
                this.assetHeight += this.header.getHeight();
            }
            if(this.pageSize < this.store.getTotalCount()){
                this.assetHeight += this.footer.getHeight();
            } else {
                this.list.setHeight(this.list.getHeight() - this.footer.getHeight());
                this.pageTb.hide();
            }
        }

        if(this.bufferSize){
            this.doResize(this.bufferSize);
            delete this.bufferSize;
        }
        this.list.alignTo.apply(this.list, [this.el].concat(this.listAlign));

        // zindex can change, re-check it and set it if necessary
        this.list.setZIndex(this.getZIndex());
        this.list.show();
        if(Ext.isGecko2){
            this.innerList.setOverflow('auto'); // necessary for FF 2.0/Mac
        }
        this.mon(Ext.getDoc(), {
            scope: this,
            mousewheel: this.collapseIf,
            mousedown: this.collapseIf
        });
        this.fireEvent('expand', this);
    }
});
Ext.reg('modx-combo',MODx.combo.ComboBox);

Ext.util.Format.comboRenderer = function (combo,val) {
    return function (v,md,rec,ri,ci,s) {
        if (!s) return v;
        if (!combo.findRecord) return v;
        var record = combo.findRecord(combo.valueField, v);
        return record ? record.get(combo.displayField) : val;
    }
};

/** @deprecated MODX 2.2 */
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
        var v2 = combo.getValue();
        idx = combo.store.find(combo.valueField,v2 ? v2 : v);
        rec = combo.store.getAt(idx);
        return (rec === undefined || rec === null ? (v2 ? v2 : v) : rec.get(combo.displayField));
    });
};

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
        ,forceSelection: true
        ,enableKeyEvents: true
    });
    MODx.combo.Boolean.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Boolean,MODx.combo.ComboBox);
Ext.reg('combo-boolean',MODx.combo.Boolean);
Ext.reg('modx-combo-boolean',MODx.combo.Boolean);

MODx.util.PasswordField = function(config) {
    config = config || {};
    delete config.xtype;
    Ext.applyIf(config,{
        xtype: 'textfield'
        ,inputType: 'password'
    });
    MODx.util.PasswordField.superclass.constructor.call(this,config);
};
Ext.extend(MODx.util.PasswordField,Ext.form.TextField);
Ext.reg('text-password',MODx.util.PasswordField);
Ext.reg('modx-text-password',MODx.util.PasswordField);

MODx.combo.User = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'user'
        ,hiddenName: 'user'
        ,displayField: 'username'
        ,valueField: 'id'
        ,fields: ['username','id']
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/user/getlist'
        }
        ,typeAhead: true
        ,editable: true
    });
    MODx.combo.User.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.User,MODx.combo.ComboBox);
Ext.reg('modx-combo-user',MODx.combo.User);

MODx.combo.UserGroup = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'group'
        ,hiddenName: 'group'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['name','id','description']
        // ,listWidth: 300
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/group/getlist'
        }
        ,tpl: new Ext.XTemplate('<tpl for="."><div class="x-combo-list-item"><span style="font-weight: bold">{name}</span>'
            ,'<br />{description}</div></tpl>')
    });
    MODx.combo.UserGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.UserGroup,MODx.combo.ComboBox);
Ext.reg('modx-combo-usergroup',MODx.combo.UserGroup);

MODx.combo.UserGroupRole = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'role'
        ,hiddenName: 'role'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['name','id']
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/role/getlist'
        }
    });
    MODx.combo.UserGroupRole.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.UserGroupRole,MODx.combo.ComboBox);
Ext.reg('modx-combo-usergrouprole',MODx.combo.UserGroupRole);

MODx.combo.ResourceGroup = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'resourcegroup'
        ,hiddenName: 'resourcegroup'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['name','id']
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/resourcegroup/getlist'
        }
    });
    MODx.combo.ResourceGroup.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ResourceGroup,MODx.combo.ComboBox);
Ext.reg('modx-combo-resourcegroup',MODx.combo.ResourceGroup);

MODx.combo.Context = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'context'
        ,hiddenName: 'context'
        ,displayField: 'name'
        ,valueField: 'key'
        ,fields: ['key', 'name']
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'context/getlist'
        }
    });
    MODx.combo.Context.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Context,MODx.combo.ComboBox);
Ext.reg('modx-combo-context',MODx.combo.Context);

MODx.combo.Policy = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'policy'
        ,hiddenName: 'policy'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name','permissions']
        ,allowBlank: false
        ,editable: false
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/access/policy/getlist'
        }
    });
    MODx.combo.Policy.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Policy,MODx.combo.ComboBox);
Ext.reg('modx-combo-policy',MODx.combo.Policy);

MODx.combo.Template = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'template'
        ,hiddenName: 'template'
        ,displayField: 'templatename'
        ,valueField: 'id'
        ,pageSize: 20
        ,fields: ['id','templatename','description','category_name']
        ,tpl: new Ext.XTemplate('<tpl for="."><div class="x-combo-list-item"><span style="font-weight: bold">{templatename}</span>'
            ,'<tpl if="category_name"> - <span style="font-style:italic">{category_name}</span></tpl>'
            ,'<br />{description:htmlEncode()}</div></tpl>')
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'element/template/getlist'
        }
        // ,listWidth: 350
        ,allowBlank: true
    });
    MODx.combo.Template.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Template,MODx.combo.ComboBox);
Ext.reg('modx-combo-template',MODx.combo.Template);

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
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'element/category/getlist'
            ,showNone: true
            ,limit: 0
        }
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

MODx.combo.Language = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'language'
        ,hiddenName: 'language'
        ,displayField: 'name'
        ,valueField: 'name'
        ,fields: ['name']
        ,typeAhead: true
        ,minChars: 1
        ,editable: true
        ,allowBlank: true
        // ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/language/getlist'
        }
    });
    MODx.combo.Language.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Language,MODx.combo.ComboBox);
Ext.reg('modx-combo-language',MODx.combo.Language);

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
        // ,listWidth: 300
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/charset/getlist'
        }
    });
    MODx.combo.Charset.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Charset,MODx.combo.ComboBox);
Ext.reg('modx-combo-charset',MODx.combo.Charset);

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
        // ,listWidth: 300
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/rte/getlist'
        }
    });
    MODx.combo.RTE.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.RTE,MODx.combo.ComboBox);
Ext.reg('modx-combo-rte',MODx.combo.RTE);

MODx.combo.Role = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'role'
        ,hiddenName: 'role'
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        // ,listWidth: 300
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/role/getlist'
            ,addNone: true
        }
    });
    MODx.combo.Role.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Role,MODx.combo.ComboBox);
Ext.reg('modx-combo-role',MODx.combo.Role);

MODx.combo.ContentType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'content_type'
        ,hiddenName: 'content_type'
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        // ,listWidth: 300
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/contenttype/getlist'
        }
    });
    MODx.combo.ContentType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ContentType,MODx.combo.ComboBox);
Ext.reg('modx-combo-content-type',MODx.combo.ContentType);

MODx.combo.ContentDisposition = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        store: new Ext.data.SimpleStore({
            fields: ['d','v']
            ,data: [[_('inline'),0],[_('attachment'),1]]
        })
        ,name: 'content_dispo'
        ,hiddenName: 'content_dispo'
        ,displayField: 'd'
        ,valueField: 'v'
        ,mode: 'local'
        ,triggerAction: 'all'
        ,editable: false
        ,pageSize: 20
        ,selectOnFocus: false
        ,preventRender: true
    });
    MODx.combo.ContentDisposition.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ContentDisposition,MODx.combo.ComboBox);
Ext.reg('modx-combo-content-disposition',MODx.combo.ContentDisposition);

MODx.combo.ClassMap = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'class'
        ,hiddenName: 'class'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/classmap/getlist'
        }
        ,displayField: 'class'
        ,valueField: 'class'
        ,fields: ['class']
        ,editable: false
        ,pageSize: 20
    });
    MODx.combo.ClassMap.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ClassMap,MODx.combo.ComboBox);
Ext.reg('modx-combo-class-map',MODx.combo.ClassMap);

MODx.combo.ClassDerivatives = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'class'
        ,hiddenName: 'class'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/derivatives/getList'
            ,skip: 'modXMLRPCResource'
            ,'class': 'modResource'
        }
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name']
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        // ,listWidth: 300
        ,pageSize: 20
    });
    MODx.combo.ClassDerivatives.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ClassDerivatives,MODx.combo.ComboBox);
Ext.reg('modx-combo-class-derivatives',MODx.combo.ClassDerivatives);

MODx.combo.Object = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'object'
        ,hiddenName: 'object'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'workspace/builder/getAssocObject'
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

MODx.combo.Namespace = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'namespace'
        ,hiddenName: 'namespace'
        ,typeAhead: true
        ,minChars: 1
        ,queryParam: 'search'
        ,editable: true
        ,allowBlank: true
        ,preselectValue: false
        // ,listWidth: 300
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'workspace/namespace/getlist'
        }
        ,fields: ['name']
        ,displayField: 'name'
        ,valueField: 'name'
    });
    MODx.combo.Namespace.superclass.constructor.call(this,config);

    if (config.preselectValue !== false) {
        this.store.on('load', this.preselectFirstValue, this, {single: true});
        this.store.load();
    }

};
Ext.extend(MODx.combo.Namespace,MODx.combo.ComboBox, {
    preselectFirstValue: function(r) {
        var item;
        
        if (this.config.preselectValue == '') {
            item = r.getAt(0);
        } else {
            var found = r.find('name', this.config.preselectValue);
            
            if (found != -1) {
                item = r.getAt(found);
            } else {
                item = r.getAt(0);
            }
        }
        
        if (item) {
            this.setValue(item.data.name);
            this.fireEvent('select', this, item);
        }

    }
});
Ext.reg('modx-combo-namespace',MODx.combo.Namespace);

MODx.combo.Browser = function(config) {
    config = config || {};
    Ext.applyIf(config,{
       width: 400
       ,triggerAction: 'all'
       ,triggerClass: 'x-form-file-trigger'
       ,source: config.source || MODx.config.default_media_source
    });
    MODx.combo.Browser.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.combo.Browser,Ext.form.TriggerField,{
    browser: null

    ,onTriggerClick : function(btn){
        if (this.disabled){
            return false;
        }

        //if (this.browser === null) {
            this.browser = MODx.load({
                xtype: 'modx-browser'
                ,closeAction: 'close'
                ,id: Ext.id()
                ,multiple: true
                ,source: this.config.source || MODx.config.default_media_source
                ,hideFiles: this.config.hideFiles || false
                ,rootVisible: this.config.rootVisible || false
                ,allowedFileTypes: this.config.allowedFileTypes || ''
                ,wctx: this.config.wctx || 'web'
                ,openTo: this.config.openTo || ''
                ,rootId: this.config.rootId || '/'
                ,hideSourceCombo: this.config.hideSourceCombo || false
                ,listeners: {
                    'select': {fn: function(data) {
                        this.setValue(data.relativeUrl);
                        this.fireEvent('select',data);
                    },scope:this}
                }
            });
        //}
        this.browser.show(btn);
        return true;
    }

    ,onDestroy: function(){
        MODx.combo.Browser.superclass.onDestroy.call(this);
    }
});
Ext.reg('modx-combo-browser',MODx.combo.Browser);

MODx.combo.Country = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'country'
        ,hiddenName: 'country'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/country/getlist'
        }
        ,displayField: 'country'
        ,valueField: 'iso'
        ,fields: [
            'iso',
            'country',
            'value' // Deprecated (available for BC)
        ]
        ,editable: true
        ,value: 0
        ,typeAhead: true
    });
    MODx.combo.Country.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Country,MODx.combo.ComboBox);
Ext.reg('modx-combo-country',MODx.combo.Country);

MODx.combo.PropertySet = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'propertyset'
        ,hiddenName: 'propertyset'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'element/propertyset/getlist'
        }
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name']
        ,editable: false
        ,pageSize: 20
        ,width: 300
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
        ,readOnly: false
        ,formpanel: 'modx-panel-resource'
        ,parentcmp: 'modx-resource-parent-hidden'
        ,contextcmp: 'modx-resource-context-key'
        ,currentid: MODx.request.id
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

        MODx.debug('Setting parent to: '+p.v);

        Ext.getCmp(this.config.parentcmp).setValue(p.v);

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
        MODx.debug('onTriggerClick');

        var t = Ext.getCmp('modx-resource-tree');
        if (!t) {
            MODx.debug('no tree found, trying to activate');
            var tp = Ext.getCmp('modx-leftbar-tabpanel');
            if (tp) {
                tp.on('tabchange',function(tbp,tab) {
                    if (tab.id == 'modx-resource-tree-ct') {
                        this.disableTreeClick();
                    }
                },this);
                tp.activate('modx-resource-tree-ct');
            } else {
                MODx.debug('no tabpanel');
            }
            return false;
        }

        this.disableTreeClick();
    }

    ,disableTreeClick: function() {
        MODx.debug('Disabling tree click');
        t = Ext.getCmp('modx-resource-tree');
        if (!t) {
            MODx.debug('No tree found in disableTreeClick!');
            return false;
        }
        this.oldDisplayValue = this.getValue();
        this.oldValue = Ext.getCmp(this.config.parentcmp).getValue();

        this.setValue(_('resource_parent_select_node'));

        t.expand();
        t.removeListener('click',t._handleClick);
        t.on('click',this.handleChangeParent,this);
        t.disableHref = true;

        return true;}

    ,handleChangeParent: function(node,e) {
        var t = Ext.getCmp('modx-resource-tree');
        if (!t) { return false; }
        t.disableHref = true;

        var id = node.id.split('_'); id = id[1];
        if (id == this.config.currentid) {
            MODx.msg.alert('',_('resource_err_own_parent'));
            return false;
        }

        var ctxf = Ext.getCmp(this.config.contextcmp);
        if (ctxf) {
            var ctxv = ctxf.getValue();
            if (node.attributes && node.attributes.ctx != ctxv) {
                ctxf.setValue(node.attributes.ctx);
            }
        }
        this.fireEvent('end',{
            v: node.attributes.type != 'modContext' ? id : node.attributes.pk
            ,d: Ext.util.Format.stripTags(node.text)
        });
        e.preventDefault();
        e.stopEvent();
        return true;
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'element/tv/renders/getOutputs'
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
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'element/tv/renders/getInputs'
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
        ,fields: ['id','controller','namespace']
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/action/getlist'
        }
        ,tpl: new Ext.XTemplate('<tpl for="."><div class="x-combo-list-item"><tpl if="namespace">{namespace} - </tpl>{controller}</div></tpl>')
    });
    MODx.combo.Action.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Action,MODx.combo.ComboBox);
Ext.reg('modx-combo-action',MODx.combo.Action);

MODx.combo.Dashboard = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'dashboard'
        ,hiddenName: 'dashboard'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name','description']
        // ,listWidth: 400
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/dashboard/getlist'
        }
        ,tpl: new Ext.XTemplate('<tpl for=".">'
            ,'<div class="x-combo-list-item">'
            ,'<h4 class="modx-combo-title">{name}</h4>'
            ,'<p class="modx-combo-desc">{description}</p>'
            ,'</div></tpl>')
    });
    MODx.combo.Dashboard.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Dashboard,MODx.combo.ComboBox);
Ext.reg('modx-combo-dashboard',MODx.combo.Dashboard);

MODx.combo.MediaSource = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'source'
        ,hiddenName: 'source'
        ,displayField: 'name'
        ,valueField: 'id'
        ,fields: ['id','name','description']
        // ,listWidth: 400
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'source/getlist'
        }
        ,tpl: new Ext.XTemplate('<tpl for=".">'
            ,'<div class="x-combo-list-item">'
            ,'<h4 class="modx-combo-title">{name}</h4>'
            ,'<p class="modx-combo-desc">{description}</p>'
            ,'</div></tpl>')
    });
    MODx.combo.MediaSource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.MediaSource,MODx.combo.ComboBox);
Ext.reg('modx-combo-source',MODx.combo.MediaSource);

MODx.combo.MediaSourceType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'class_key'
        ,hiddenName: 'class_key'
        ,displayField: 'name'
        ,valueField: 'class'
        ,fields: ['id','class','name','description']
        // ,listWidth: 400
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'source/type/getlist'
        }
        ,tpl: new Ext.XTemplate('<tpl for=".">'
            ,'<div class="x-combo-list-item">'
            ,'<h4 class="modx-combo-title">{name}</h4>'
            ,'<p class="modx-combo-desc">{description}</p>'
            ,'</div></tpl>')
    });
    MODx.combo.MediaSourceType.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.MediaSourceType,MODx.combo.ComboBox);
Ext.reg('modx-combo-source-type',MODx.combo.MediaSourceType);


MODx.combo.Authority = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'authority'
        ,hiddenName: 'authority'
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        // ,listWidth: 300
        ,pageSize: 20
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/role/getAuthorityList'
            ,addNone: true
        }
    });
    MODx.combo.Authority.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.Authority,MODx.combo.ComboBox);
Ext.reg('modx-combo-authority',MODx.combo.Authority);

MODx.combo.ManagerTheme = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'theme'
        ,hiddenName: 'theme'
        ,displayField: 'theme'
        ,valueField: 'theme'
        ,fields: ['theme']
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'workspace/theme/getlist'
        }
        ,typeAhead: false
        ,editable: false
    });
    MODx.combo.ManagerTheme.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.ManagerTheme,MODx.combo.ComboBox);
Ext.reg('modx-combo-manager-theme',MODx.combo.ManagerTheme);

MODx.combo.SettingKey = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'key'
        ,hiddenName: 'key'
        ,displayField: 'key'
        ,valueField: 'key'
        ,fields: ['key']
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'system/settings/getlist'
        }
        ,typeAhead: false
        ,triggerAction: 'all'
        ,editable: true
        ,forceSelection: false
        ,queryParam: 'key'
        ,pageSize: 20
    });
    MODx.combo.SettingKey.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.SettingKey,MODx.combo.ComboBox);
Ext.reg('modx-combo-setting-key',MODx.combo.SettingKey);
