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
            ,'<br />{description}</div></tpl>')
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
						Ext.getCmp('modx-content').doLayout(); /* Fix layout bug with absolute positioning */
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
						Ext.getCmp('modx-content').doLayout(); /* Fix layout bug with absolute positioning */
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
        if (!this.exp) return false;

        this.exp.expandAll();
        this.tools['plus'].hide();
        this.tools['minus'].show();
        return true;
    }

    ,collapseAll: function() {
        if (!this.exp) return false;

        this.exp.collapseAll();
        this.tools['minus'].hide();
        this.tools['plus'].show();
        return true;
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
        if (!this.exp) return false;

        this.exp.expandAll();
        this.tools['plus'].hide();
        this.tools['minus'].show();
        return true;
    }

    ,collapseAll: function() {
        if (!this.exp) return false;

        this.exp.collapseAll();
        this.tools['minus'].hide();
        this.tools['plus'].show();
        return true;
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

MODx.Console = function(config) {
    config = config || {};
    Ext.Updater.defaults.showLoadIndicator = false;
    Ext.applyIf(config,{
        title: _('console')
        ,modal: Ext.isIE ? false : true
        ,closeAction: 'hide'
        // ,shadow: true
        ,resizable: false
        ,collapsible: false
        ,closable: true
        ,maximizable: true
        ,autoScroll: true
        ,height: 400
        ,width: 600
        ,refreshRate: 2
        ,cls: 'modx-window modx-console'
        ,items: [{
            itemId: 'header'
            ,cls: 'modx-console-text'
            ,html: _('console_running')
            ,border: false
        },{
            xtype: 'panel'
            ,itemId: 'body'
            ,cls: 'x-panel-bwrap modx-console-text'
        }]
        ,buttons: [{
            text: _('console_download_output')
            ,handler: this.download
            ,scope: this
        },{
            text: _('ok')
            ,cls: 'primary-button'
            ,itemId: 'okBtn'
            ,disabled: true
            ,scope: this
            ,handler: this.hideConsole
        }]
        ,keys: [{
            key: Ext.EventObject.S
            ,ctrl: true
            ,fn: this.download
            ,scope: this
        },{
            key: Ext.EventObject.ENTER
            ,fn: this.hideConsole
            ,scope: this
        }]
    });
    MODx.Console.superclass.constructor.call(this,config);
    this.config = config;
    this.addEvents({
        'shutdown': true
        ,'complete': true
    });
    this.on('show',this.init,this);
    this.on('hide',function() {
        if (this.provider && this.provider.disconnect) {
            try {
                this.provider.disconnect();
            } catch (e) {}
        }
        this.fireEvent('shutdown');
        //this.getComponent('body').el.update('');
        this.destroy();
    });
    this.on('complete',this.onComplete,this);
};
Ext.extend(MODx.Console,Ext.Window,{
    mgr: null
    ,running: false

    ,init: function() {
        Ext.Msg.hide();
        this.fbar.setDisabled(true);
        this.keyMap.setDisabled(true);
        this.getComponent('body').el.dom.innerHTML = '';
        this.provider = new Ext.direct.PollingProvider({
            type:'polling'
            ,url: MODx.config.connector_url
            ,interval: 1000
            ,baseParams: {
                action: 'system/console'
                ,register: this.config.register || ''
                ,topic: this.config.topic || ''
                ,clear: false
                ,show_filename: this.config.show_filename || 0
                ,format: this.config.format || 'html_log'
            }
        });
        Ext.Direct.addProvider(this.provider);
        Ext.Direct.on('message', this.onMessage, this);
    }

    ,onMessage: function(e,p) {
        var out = this.getComponent('body');
        if (out) {
            out.el.insertHtml('beforeEnd',e.data);
            e.data = '';
            out.el.scroll('b', out.el.getHeight(), true);
        }
        if (e.complete) {
            this.fireEvent('complete');
        }
        delete e;
    }

    ,onComplete: function() {
        if (this.provider && this.provider.disconnect) {
            try {
                this.provider.disconnect();
            } catch (e) {}
        }
        this.fbar.setDisabled(false);
        this.keyMap.setDisabled(false);
    }

    ,download: function() {
        var c = this.getComponent('body').getEl().dom.innerHTML || '&nbsp;';
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'system/downloadoutput'
                ,data: c
            }
            ,listeners: {
                'success':{fn:function(r) {
                    location.href = MODx.config.connector_url+'?action=system/downloadOutput&HTTP_MODAUTH='+MODx.siteId+'&download='+r.message;
                },scope:this}
            }
        });
    }

    ,setRegister: function(register,topic) {
    	this.config.register = register;
        this.config.topic = topic;
    }

    ,hideConsole: function() {
        this.hide();
    }
});
Ext.reg('modx-console',MODx.Console);

Ext.namespace('MODx.portal');

/* ext portal code */
Ext.ux.Portal=Ext.extend(Ext.Panel,{layout:'column',cls:'x-portal',defaultType:'portalcolumn',initComponent:function(){Ext.ux.Portal.superclass.initComponent.call(this);this.addEvents({validatedrop:true,beforedragover:true,dragover:true,beforedrop:true,drop:true})},initEvents:function(){Ext.ux.Portal.superclass.initEvents.call(this);this.dd=new Ext.ux.Portal.DropZone(this,this.dropConfig)},beforeDestroy:function(){if(this.dd){this.dd.unreg()}Ext.ux.Portal.superclass.beforeDestroy.call(this)}});Ext.reg('portal',Ext.ux.Portal);Ext.ux.Portal.DropZone=function(a,b){this.portal=a;Ext.dd.ScrollManager.register(a.body);Ext.ux.Portal.DropZone.superclass.constructor.call(this,a.bwrap.dom,b);a.body.ddScrollConfig=this.ddScrollConfig};Ext.extend(Ext.ux.Portal.DropZone,Ext.dd.DropTarget,{ddScrollConfig:{vthresh:50,hthresh:-1,animate:true,increment:200},createEvent:function(a,e,b,d,c,f){return{portal:this.portal,panel:b.panel,columnIndex:d,column:c,position:f,data:b,source:a,rawEvent:e,status:this.dropAllowed}},notifyOver:function(a,e,b){var d=e.getXY(),portal=this.portal,px=a.proxy;if(!this.grid){this.grid=this.getGrid()}var f=portal.body.dom.clientWidth;if(!this.lastCW){this.lastCW=f}else if(this.lastCW!=f){this.lastCW=f;portal.doLayout();this.grid=this.getGrid()}var g=0,xs=this.grid.columnX,cmatch=false;for(var i=xs.length;g<i;g++){if(d[0]<(xs[g].x+xs[g].w)){cmatch=true;break}}if(!cmatch){g--}var p,match=false,pos=0,c=portal.items.itemAt(g),items=c.items.items,overSelf=false;for(var i=items.length;pos<i;pos++){p=items[pos];var h=p.el.getHeight();if(h===0){overSelf=true}else if((p.el.getY()+(h/2))>d[1]){match=true;break}}pos=(match&&p?pos:c.items.getCount())+(overSelf?-1:0);var j=this.createEvent(a,e,b,g,c,pos);if(portal.fireEvent('validatedrop',j)!==false&&portal.fireEvent('beforedragover',j)!==false){px.getProxy().setWidth('auto');if(p){px.moveProxy(p.el.dom.parentNode,match?p.el.dom:null)}else{px.moveProxy(c.el.dom,null)}this.lastPos={c:c,col:g,p:overSelf||(match&&p)?pos:false};this.scrollPos=portal.body.getScroll();portal.fireEvent('dragover',j);return j.status}else{return j.status}},notifyOut:function(){delete this.grid},notifyDrop:function(a,e,b){delete this.grid;if(!this.lastPos){return}var c=this.lastPos.c,col=this.lastPos.col,pos=this.lastPos.p;var f=this.createEvent(a,e,b,col,c,pos!==false?pos:c.items.getCount());if(this.portal.fireEvent('validatedrop',f)!==false&&this.portal.fireEvent('beforedrop',f)!==false){a.proxy.getProxy().remove();a.panel.el.dom.parentNode.removeChild(a.panel.el.dom);if(pos!==false){if(c==a.panel.ownerCt&&(c.items.items.indexOf(a.panel)<=pos)){pos++}c.insert(pos,a.panel)}else{c.add(a.panel)}c.doLayout();this.portal.fireEvent('drop',f);var g=this.scrollPos.top;if(g){var d=this.portal.body.dom;setTimeout(function(){d.scrollTop=g},10)}}delete this.lastPos},getGrid:function(){var a=this.portal.bwrap.getBox();a.columnX=[];this.portal.items.each(function(c){a.columnX.push({x:c.el.getX(),w:c.el.getWidth()})});return a},unreg:function(){Ext.ux.Portal.DropZone.superclass.unreg.call(this)}});

MODx.portal.Column = Ext.extend(Ext.Container,{
    layout: 'anchor'
    ,defaultType: 'portlet'
    ,cls:'x-portal-column'
    ,style:'padding:10px;'
    ,columnWidth: 1
    ,defaults: {
        collapsible: true
        ,autoHeight: true
        ,titleCollapse: true
        ,draggable: true
        ,style: 'padding: 5px 0;'
        ,bodyStyle: 'padding: 15px;'
    }
});
Ext.reg('portalcolumn', MODx.portal.Column);

MODx.portal.Portlet = Ext.extend(Ext.Panel,{
    anchor: Ext.isSafari ? '98%' : '100%'
    ,frame:true
    ,collapsible:true
    ,draggable:true
    ,cls:'x-portlet'
    ,stateful: false
    ,layout: 'form'
});
Ext.reg('portlet', MODx.portal.Portlet);
/**
 * Generates the Duplicate Resource window.
 *
 * @class MODx.window.DuplicateResource
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-resource-duplicate
 */
MODx.window.DuplicateResource = function(config) {
    config = config || {};
    this.ident = config.ident || 'dupres'+Ext.id();
    Ext.applyIf(config,{
        title: _('duplication_options')
        ,id: this.ident
        // ,width: 500
    });
    MODx.window.DuplicateResource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.DuplicateResource,MODx.Window,{
    _loadForm: function() {
        if (this.checkIfLoaded(this.config.record)) {
            this.fp.getForm().baseParams = {
                action: 'resource/updateduplicate'
                ,prefixDuplicate: true
                ,id: this.config.resource
            };
            return false;
        }
        var items = [];
        items.push({
            xtype: 'textfield'
            ,id: 'modx-'+this.ident+'-name'
            ,fieldLabel: _('resource_name_new')
            ,name: 'name'
            ,anchor: '100%'
            ,value: ''
        });

        if (this.config.hasChildren) {
            items.push({
                xtype: 'xcheckbox'
                ,boxLabel: _('duplicate_children')
                ,hideLabel: true
                ,name: 'duplicate_children'
                ,id: 'modx-'+this.ident+'-duplicate-children'
                ,checked: true
                ,listeners: {
                    'check': {fn: function(cb,checked) {
                        if (checked) {
                            this.fp.getForm().findField('modx-'+this.ident+'-name').disable();
                        } else {
                            this.fp.getForm().findField('modx-'+this.ident+'-name').enable();
                        }
                    },scope:this}
                }
            });
        }

        var pov = MODx.config.default_duplicate_publish_option || 'preserve';
        items.push({
            xtype: 'fieldset'
            ,title: _('publishing_options')
            ,items: [{
                xtype: 'radiogroup'
                ,hideLabel: true
                ,columns: 1
                ,value: pov
                ,items: [{
                    boxLabel: _('po_make_all_unpub')
                    ,hideLabel: true
                    ,name: 'published_mode'
                    ,inputValue: 'unpublish'
                },{
                    boxLabel: _('po_make_all_pub')
                    ,hideLabel: true
                    ,name: 'published_mode'
                    ,inputValue: 'publish'
                },{
                    boxLabel: _('po_preserve')
                    ,hideLabel: true
                    ,name: 'published_mode'
                    ,inputValue: 'preserve'
                }]
            }]
        });

        this.fp = this.createForm({
            url: this.config.url || MODx.config.connector_url
            ,baseParams: this.config.baseParams || {
                action: 'resource/duplicate'
                ,id: this.config.resource
                ,prefixDuplicate: true
            }
            ,labelWidth: 125
            ,defaultType: 'textfield'
            ,autoHeight: true
            ,items: items
        });

        this.renderForm();
    }
});
Ext.reg('modx-window-resource-duplicate',MODx.window.DuplicateResource);

/**
 * Generates the Duplicate Element window
 *
 * @class MODx.window.DuplicateElement
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-element-duplicate
 */
MODx.window.DuplicateElement = function(config) {
    config = config || {};
    this.ident = config.ident || 'dupeel-'+Ext.id();
    var flds = [{
        xtype: 'hidden'
        ,name: 'id'
        ,id: 'modx-'+this.ident+'-id'
    },{
        xtype: 'textfield'
        ,fieldLabel: _('element_name_new')
        ,name: config.record.type == 'template' ? 'templatename' : 'name'
        ,id: 'modx-'+this.ident+'-name'
        ,anchor: '100%'
    }];
    if (config.record.type == 'tv') {
        flds.push({
            xtype: 'xcheckbox'
            ,fieldLabel: _('element_duplicate_values')
            ,labelSeparator: ''
            ,name: 'duplicateValues'
            ,id: 'modx-'+this.ident+'-duplicate-values'
            ,anchor: '100%'
            ,inputValue: 1
            ,checked: false
        });
    }
    Ext.applyIf(config,{
        title: _('element_duplicate')
        ,url: MODx.config.connector_url
        ,action: 'element/'+config.record.type+'/duplicate'
        ,fields: flds
        ,labelWidth: 150
    });
    MODx.window.DuplicateElement.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.DuplicateElement,MODx.Window);
Ext.reg('modx-window-element-duplicate',MODx.window.DuplicateElement);

MODx.window.CreateCategory = function(config) {
    config = config || {};
    this.ident = config.ident || 'ccat'+Ext.id();
    Ext.applyIf(config,{
        title: _('new_category')
        ,id: this.ident
        // ,height: 150
        // ,width: 350
        ,url: MODx.config.connector_url
        ,action: 'element/category/create'
        ,fields: [{
            fieldLabel: _('name')
            ,name: 'category'
            ,id: 'modx-'+this.ident+'-category'
            ,xtype: 'textfield'
            ,anchor: '100%'
        },{
            fieldLabel: _('parent')
            ,name: 'parent'
            ,hiddenName: 'parent'
            ,id: 'modx-'+this.ident+'-parent'
            ,xtype: 'modx-combo-category'
            ,anchor: '100%'
        },{
            fieldLabel: _('rank')
            ,name: 'rank'
            ,id: 'modx-'+this.ident+'-rank'
            ,xtype: 'numberfield'
            ,anchor: '100%'
        }]
    });
    MODx.window.CreateCategory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateCategory,MODx.Window);
Ext.reg('modx-window-category-create',MODx.window.CreateCategory);

/**
 * Generates the Rename Category window.
 *
 * @class MODx.window.RenameCategory
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-category-rename
 */
MODx.window.RenameCategory = function(config) {
    config = config || {};
    this.ident = config.ident || 'rencat-'+Ext.id();
    Ext.applyIf(config,{
        title: _('category_rename')
        // ,height: 150
        // ,width: 350
        ,url: MODx.config.connector_url
        ,action: 'element/category/update'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
            ,id: 'modx-'+this.ident+'-id'
            ,value: config.record.id
        },{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,name: 'category'
            ,id: 'modx-'+this.ident+'-category'
            ,width: 150
            ,value: config.record.category
            ,anchor: '100%'
        },{
            fieldLabel: _('rank')
            ,name: 'rank'
            ,id: 'modx-'+this.ident+'-rank'
            ,xtype: 'numberfield'
            ,anchor: '100%'
        }]
    });
    MODx.window.RenameCategory.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.RenameCategory,MODx.Window);
Ext.reg('modx-window-category-rename',MODx.window.RenameCategory);


MODx.window.CreateNamespace = function(config) {
    config = config || {};
    var r = config.record;
    this.ident = config.ident || 'cns'+Ext.id();
    Ext.applyIf(config,{
        title: _('namespace_create')
        ,id: this.ident
        ,width: 600
        ,url: MODx.config.connector_url
        ,action: 'workspace/namespace/create'
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('name')
            ,description: MODx.expandHelp ? '' : _('namespace_name_desc')
            ,name: 'name'
            ,id: 'modx-'+this.ident+'-name'
            ,anchor: '100%'
            ,maxLength: 100
            ,readOnly: config.isUpdate || false
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-name'
            ,html: _('namespace_name_desc')
            ,cls: 'desc-under'

        },{
            xtype: 'textfield'
            ,fieldLabel: _('namespace_path')
            ,description: MODx.expandHelp ? '' : _('namespace_path_desc')
            ,name: 'path'
            ,id: 'modx-'+this.ident+'-path'
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-path'
            ,html: _('namespace_path_desc')
            ,cls: 'desc-under'

        },{
            xtype: 'textfield'
            ,fieldLabel: _('namespace_assets_path')
            ,description: MODx.expandHelp ? '' : _('namespace_assets_path_desc')
            ,name: 'assets_path'
            ,id: 'modx-'+this.ident+'-assets-path'
            ,anchor: '100%'
        },{
            xtype: MODx.expandHelp ? 'label' : 'hidden'
            ,forId: 'modx-'+this.ident+'-assets-path'
            ,html: _('namespace_assets_path_desc')
            ,cls: 'desc-under'
        }]
    });
    MODx.window.CreateNamespace.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.CreateNamespace,MODx.Window);
Ext.reg('modx-window-namespace-create',MODx.window.CreateNamespace);

MODx.window.UpdateNamespace = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        title: _('namespace_update')
        ,action: 'workspace/namespace/update'
        ,isUpdate: true
    });
    MODx.window.UpdateNamespace.superclass.constructor.call(this, config);
};
Ext.extend(MODx.window.UpdateNamespace, MODx.window.CreateNamespace, {});
Ext.reg('modx-window-namespace-update',MODx.window.UpdateNamespace);


MODx.window.QuickCreateChunk = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        title: _('quick_create_chunk')
        ,width: 600
        //,height: 640
        // ,autoHeight: true
        ,layout: 'anchor'
        ,url: MODx.config.connector_url
        ,action: 'element/chunk/create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,fieldLabel: _('name')
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,fieldLabel: _('description')
            ,anchor: '100%'
            //,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'snippet'
            ,fieldLabel: _('code')
            ,anchor: '100%'
            ,grow: true
            ,growMax: 216
        },{
            xtype: 'xcheckbox'
            ,name: 'clearCache'
            ,hideLabel: true
            ,boxLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
        }]
        ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickCreateChunk.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateChunk,MODx.Window);
Ext.reg('modx-window-quick-create-chunk',MODx.window.QuickCreateChunk);

MODx.window.QuickUpdateChunk = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        title: _('quick_update_chunk')
        ,action: 'element/chunk/update'
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,cls: 'primary-button'
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.QuickUpdateChunk.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateChunk, MODx.window.QuickCreateChunk);
Ext.reg('modx-window-quick-update-chunk',MODx.window.QuickUpdateChunk);

MODx.window.QuickCreateTemplate = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        title: _('quick_create_template')
        ,width: 600
        // ,autoHeight: true
        ,layout: 'anchor'
        ,url: MODx.config.connector_url
        ,action: 'element/template/create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,name: 'templatename'
            ,fieldLabel: _('name')
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,fieldLabel: _('description')
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,name: 'content'
            ,fieldLabel: _('code')
            ,anchor: '100%'
            ,grow: true
            ,growMax: 216
        },{
            xtype: 'xcheckbox'
            ,name: 'clearCache'
            ,hideLabel: true
            ,boxLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
        }]
        ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickCreateTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateTemplate,MODx.Window);
Ext.reg('modx-window-quick-create-template',MODx.window.QuickCreateTemplate);

MODx.window.QuickUpdateTemplate = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        title: _('quick_update_template')
        ,action: 'element/template/update'
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,cls: 'primary-button'
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.QuickUpdateTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateTemplate,MODx.window.QuickCreateTemplate);
Ext.reg('modx-window-quick-update-template',MODx.window.QuickUpdateTemplate);


MODx.window.QuickCreateSnippet = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        title: _('quick_create_snippet')
        ,width: 600
        // ,autoHeight: true
        ,layout: 'anchor'
        ,url: MODx.config.connector_url
        ,action: 'element/snippet/create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,fieldLabel: _('name')
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,fieldLabel: _('description')
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,name: 'snippet'
            ,fieldLabel: _('code')
            ,anchor: '100%'
            ,grow: true
            ,growMax: 216
        },{
            xtype: 'xcheckbox'
            ,name: 'clearCache'
            ,hideLabel: true
            ,boxLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
        }]
        ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickCreateSnippet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateSnippet,MODx.Window);
Ext.reg('modx-window-quick-create-snippet',MODx.window.QuickCreateSnippet);

MODx.window.QuickUpdateSnippet = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        title: _('quick_update_snippet')
        ,action: 'element/snippet/update'
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,cls: 'primary-button'
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.QuickUpdateSnippet.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateSnippet,MODx.window.QuickCreateSnippet);
Ext.reg('modx-window-quick-update-snippet',MODx.window.QuickUpdateSnippet);



MODx.window.QuickCreatePlugin = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        title: _('quick_create_plugin')
        ,width: 600
        // ,autoHeight: true
        ,layout: 'anchor'
        ,url: MODx.config.connector_url
        ,action: 'element/plugin/create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,name: 'name'
            ,fieldLabel: _('name')
            ,anchor: '100%'
        },{
            xtype: 'modx-combo-category'
            ,name: 'category'
            ,fieldLabel: _('category')
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,name: 'description'
            ,fieldLabel: _('description')
            ,anchor: '100%'
            ,rows: 2
        },{
            xtype: 'textarea'
            ,name: 'plugincode'
            ,fieldLabel: _('code')
            ,anchor: '100%'
            ,grow: true
            ,growMax: 216
        },{
            xtype: 'xcheckbox'
            ,name: 'disabled'
            ,boxLabel: _('disabled')
            ,hideLabel: true
            ,inputValue: 1
            ,checked: false
        },{
            xtype: 'xcheckbox'
            ,name: 'clearCache'
            ,boxLabel: _('clear_cache_on_save')
            ,hideLabel: true
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
        }]
        ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickCreatePlugin.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreatePlugin,MODx.Window);
Ext.reg('modx-window-quick-create-plugin',MODx.window.QuickCreatePlugin);

MODx.window.QuickUpdatePlugin = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        title: _('quick_update_plugin')
        ,action: 'element/plugin/update'
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,cls: 'primary-button'
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.QuickUpdatePlugin.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdatePlugin,MODx.window.QuickCreatePlugin);
Ext.reg('modx-window-quick-update-plugin',MODx.window.QuickUpdatePlugin);


MODx.window.QuickCreateTV = function(config) {
    config = config || {};
    this.ident = config.ident || 'qtv'+Ext.id();

    Ext.applyIf(config,{
        title: _('quick_create_tv')
        ,width: 700
        ,url: MODx.config.connector_url
        ,action: 'element/tv/create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            layout: 'column'
            ,border: false
            ,items: [{
                columnWidth: .6
                ,layout: 'form'
                ,items: [{
                    xtype: 'textfield'
                    ,name: 'name'
                    ,fieldLabel: _('name')
                    ,anchor: '100%'
                },{
                    xtype: 'textfield'
                    ,name: 'caption'
                    ,id: 'modx-'+this.ident+'-caption'
                    ,fieldLabel: _('caption')
                    ,anchor: '100%'
                },{
                    xtype: 'label'
                    ,forId: 'modx-'+this.ident+'-caption'
                    ,html: _('caption_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'modx-combo-category'
                    ,name: 'category'
                    ,fieldLabel: _('category')
                    ,anchor: '100%'
                },{
                    xtype: 'textarea'
                    ,name: 'description'
                    ,fieldLabel: _('description')
                    ,anchor: '100%'
                }]
            },{
                columnWidth: .4
                ,border: false
                ,layout: 'form'
                ,items: [{
                    xtype: 'modx-combo-tv-input-type'
                    ,fieldLabel: _('tv_type')
                    ,name: 'type'
                    ,anchor: '100%'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('tv_elements')
                    ,name: 'els'
                    ,id: 'modx-'+this.ident+'-elements'
                    ,anchor: '100%'
                },{
                    xtype: 'label'
                    ,forId: 'modx-'+this.ident+'-elements'
                    ,html: _('tv_elements_desc')
                    ,cls: 'desc-under'
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('tv_default')
                    ,name: 'default_text'
                    ,id: 'modx-'+this.ident+'-default-text'
                    ,anchor: '100%'
                    ,grow: true
                    ,growMax: Ext.getBody().getViewSize().height <= 768 ? 300 : 380
                },{
                    xtype: 'label'
                    ,forId: 'modx-'+this.ident+'-default-text'
                    ,html: _('tv_default_desc')
                    ,cls: 'desc-under'
                }]
            }]
        },{
            xtype: 'xcheckbox'
            ,name: 'clearCache'
            ,hideLabel: true
            ,boxLabel: _('clear_cache_on_save')
            ,description: _('clear_cache_on_save_msg')
            ,inputValue: 1
            ,checked: true
        }]
        ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickCreateTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateTV,MODx.Window);
Ext.reg('modx-window-quick-create-tv',MODx.window.QuickCreateTV);

MODx.window.QuickUpdateTV = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        title: _('quick_update_tv')
        ,action: 'element/tv/update'
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,cls: 'primary-button'
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.QuickUpdateTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateTV,MODx.window.QuickCreateTV);
Ext.reg('modx-window-quick-update-tv',MODx.window.QuickUpdateTV);


MODx.window.DuplicateContext = function(config) {
    config = config || {};
    this.ident = config.ident || 'dupctx'+Ext.id();
    Ext.Ajax.timeout = 0;
    Ext.applyIf(config,{
        title: _('context_duplicate')
        ,id: this.ident
        ,url: MODx.config.connector_url
        ,action: 'context/duplicate'
        // ,width: 400
        ,fields: [{
            xtype: 'statictextfield'
            ,id: 'modx-'+this.ident+'-key'
            ,fieldLabel: _('old_key')
            ,name: 'key'
            ,anchor: '100%'
            ,submitValue: true
        },{
            xtype: 'textfield'
            ,id: 'modx-'+this.ident+'-newkey'
            ,fieldLabel: _('new_key')
            ,name: 'newkey'
            ,anchor: '100%'
            ,value: ''
        },{
            xtype: 'checkbox'
            ,id: 'modx-'+this.ident+'-preservealias'
            ,hideLabel: true
            ,boxLabel: _('preserve_alias') // Todo: add translation
            ,name: 'preserve_alias'
            ,anchor: '100%'
            ,checked: true
        },{
            xtype: 'checkbox'
            ,id: 'modx-'+this.ident+'-preservemenuindex'
            ,hideLabel: true
            ,boxLabel: _('preserve_menuindex') // Todo: add translation
            ,name: 'preserve_menuindex'
            ,anchor: '100%'
            ,checked: true
        }]
    });
    MODx.window.DuplicateContext.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.DuplicateContext,MODx.Window);
Ext.reg('modx-window-context-duplicate',MODx.window.DuplicateContext);

MODx.window.Login = function(config) {
    config = config || {};
    this.ident = config.ident || 'dupctx'+Ext.id();
    Ext.Ajax.timeout = 0;
    Ext.applyIf(config,{
        title: _('login')
        ,id: this.ident
        ,url: MODx.config.connectors_url + 'security/login.php'
        // ,width: 400
        ,fields: [{
            html: '<p>'+_('session_logging_out')+'</p>'
            ,bodyCssClass: 'panel-desc'
        },{
            xtype: 'textfield'
            ,id: 'modx-'+this.ident+'-username'
            ,fieldLabel: _('username')
            ,name: 'username'
            ,anchor: '100%'
        },{
            xtype: 'textfield'
            ,inputType: 'password'
            ,id: 'modx-'+this.ident+'-password'
            ,fieldLabel: _('password')
            ,name: 'password'
            ,anchor: '100%'
        },{
            xtype: 'hidden'
            ,name: 'rememberme'
            ,value: 1
        }]
        ,buttons: [{
            text: _('logout')
            ,scope: this
            ,handler: function() { location.href = '?logout=1' }
        },{
            text: _('login')
            ,cls: 'primary-button'
            ,scope: this
            ,handler: this.submit
        }]
    });
    MODx.window.Login.superclass.constructor.call(this,config);
    this.on('success',this.onLogin,this);
};
Ext.extend(MODx.window.Login,MODx.Window,{
    onLogin: function(o) {
        var r = o.a.result;
        if (r.object && r.object.token) {
            Ext.Ajax.defaultHeaders = {
                'modAuth': r.object.token
            };
            Ext.Ajax.extraParams = {
                'HTTP_MODAUTH': r.object.token
            };
            MODx.siteId = r.object.token;
            MODx.msg.status({
                message: _('session_extended')
            });
        }
    }
});
Ext.reg('modx-window-login',MODx.window.Login);

/*! fileapi 2.0.3 - BSD | git://github.com/mailru/FileAPI.git
 * FileAPI — a set of  javascript tools for working with files. Multiupload, drag'n'drop and chunked file upload. Images: crop, resize and auto orientation by EXIF.
 */

/*
 * JavaScript Canvas to Blob 2.0.5
 * https://github.com/blueimp/JavaScript-Canvas-to-Blob
 *
 * Copyright 2012, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 *
 * Based on stackoverflow user Stoive's code snippet:
 * http://stackoverflow.com/q/4998908
 */

/*jslint nomen: true, regexp: true */
/*global window, atob, Blob, ArrayBuffer, Uint8Array */

(function (window) {
    'use strict';
    var CanvasPrototype = window.HTMLCanvasElement &&
            window.HTMLCanvasElement.prototype,
        hasBlobConstructor = window.Blob && (function () {
            try {
                return Boolean(new Blob());
            } catch (e) {
                return false;
            }
        }()),
        hasArrayBufferViewSupport = hasBlobConstructor && window.Uint8Array &&
            (function () {
                try {
                    return new Blob([new Uint8Array(100)]).size === 100;
                } catch (e) {
                    return false;
                }
            }()),
        BlobBuilder = window.BlobBuilder || window.WebKitBlobBuilder ||
            window.MozBlobBuilder || window.MSBlobBuilder,
        dataURLtoBlob = (hasBlobConstructor || BlobBuilder) && window.atob &&
            window.ArrayBuffer && window.Uint8Array && function (dataURI) {
                var byteString,
                    arrayBuffer,
                    intArray,
                    i,
                    mimeString,
                    bb;
                if (dataURI.split(',')[0].indexOf('base64') >= 0) {
                    // Convert base64 to raw binary data held in a string:
                    byteString = atob(dataURI.split(',')[1]);
                } else {
                    // Convert base64/URLEncoded data component to raw binary data:
                    byteString = decodeURIComponent(dataURI.split(',')[1]);
                }
                // Write the bytes of the string to an ArrayBuffer:
                arrayBuffer = new ArrayBuffer(byteString.length);
                intArray = new Uint8Array(arrayBuffer);
                for (i = 0; i < byteString.length; i += 1) {
                    intArray[i] = byteString.charCodeAt(i);
                }
                // Separate out the mime component:
                mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
                // Write the ArrayBuffer (or ArrayBufferView) to a blob:
                if (hasBlobConstructor) {
                    return new Blob(
                        [hasArrayBufferViewSupport ? intArray : arrayBuffer],
                        {type: mimeString}
                    );
                }
                bb = new BlobBuilder();
                bb.append(arrayBuffer);
                return bb.getBlob(mimeString);
            };
    if (window.HTMLCanvasElement && !CanvasPrototype.toBlob) {
        if (CanvasPrototype.mozGetAsFile) {
            CanvasPrototype.toBlob = function (callback, type, quality) {
                if (quality && CanvasPrototype.toDataURL && dataURLtoBlob) {
                    callback(dataURLtoBlob(this.toDataURL(type, quality)));
                } else {
                    callback(this.mozGetAsFile('blob', type));
                }
            };
        } else if (CanvasPrototype.toDataURL && dataURLtoBlob) {
            CanvasPrototype.toBlob = function (callback, type, quality) {
                callback(dataURLtoBlob(this.toDataURL(type, quality)));
            };
        }
    }
    window.dataURLtoBlob = dataURLtoBlob;
})(window);

/*jslint evil: true */
/*global window, URL, webkitURL, ActiveXObject */

(function (window, undef){
	'use strict';

	var
		gid = 1,
		noop = function (){},

		document = window.document,
		doctype = document.doctype || {},
		userAgent = window.navigator.userAgent,

		// https://github.com/blueimp/JavaScript-Load-Image/blob/master/load-image.js#L48
		apiURL = (window.createObjectURL && window) || (window.URL && URL.revokeObjectURL && URL) || (window.webkitURL && webkitURL),

		Blob = window.Blob,
		File = window.File,
		FileReader = window.FileReader,
		FormData = window.FormData,


		XMLHttpRequest = window.XMLHttpRequest,
		jQuery = window.jQuery,

		html5 =    !!(File && (FileReader && (window.Uint8Array || FormData || XMLHttpRequest.prototype.sendAsBinary)))
				&& !(/safari\//i.test(userAgent) && !/chrome\//i.test(userAgent) && /windows/i.test(userAgent)), // BugFix: https://github.com/mailru/FileAPI/issues/25

		cors = html5 && ('withCredentials' in (new XMLHttpRequest)),

		chunked = html5 && !!Blob && !!(Blob.prototype.webkitSlice || Blob.prototype.mozSlice || Blob.prototype.slice),

		// https://github.com/blueimp/JavaScript-Canvas-to-Blob
		dataURLtoBlob = window.dataURLtoBlob,


		_rimg = /img/i,
		_rcanvas = /canvas/i,
		_rimgcanvas = /img|canvas/i,
		_rinput = /input/i,
		_rdata = /^data:[^,]+,/,


		Math = window.Math,

		_SIZE_CONST = function (pow){
			pow = new window.Number(Math.pow(1024, pow));
			pow.from = function (sz){ return Math.round(sz * this); };
			return	pow;
		},

		_elEvents = {}, // element event listeners
		_infoReader = [], // list of file info processors

		_readerEvents = 'abort progress error load loadend',
		_xhrPropsExport = 'status statusText readyState response responseXML responseText responseBody'.split(' '),

		currentTarget = 'currentTarget', // for minimize
		preventDefault = 'preventDefault', // and this too

		_isArray = function (ar) {
			return	ar && ('length' in ar);
		},

		/**
		 * Iterate over a object or array
		 */
		_each = function (obj, fn, ctx){
			if( obj ){
				if( _isArray(obj) ){
					for( var i = 0, n = obj.length; i < n; i++ ){
						if( i in obj ){
							fn.call(ctx, obj[i], i, obj);
						}
					}
				}
				else {
					for( var key in obj ){
						if( obj.hasOwnProperty(key) ){
							fn.call(ctx, obj[key], key, obj);
						}
					}
				}
			}
		},

		/**
		 * Merge the contents of two or more objects together into the first object
		 */
		_extend = function (dst){
			var args = arguments, i = 1, _ext = function (val, key){ dst[key] = val; };
			for( ; i < args.length; i++ ){
				_each(args[i], _ext);
			}
			return  dst;
		},

		/**
		 * Add event listener
		 */
		_on = function (el, type, fn){
			if( el ){
				var uid = api.uid(el);

				if( !_elEvents[uid] ){
					_elEvents[uid] = {};
				}

				_each(type.split(/\s+/), function (type){
					if( jQuery ){
						jQuery.event.add(el, type, fn);
					}
					else {
						if( !_elEvents[uid][type] ){
							_elEvents[uid][type] = [];
						}

						_elEvents[uid][type].push(fn);

						if( el.addEventListener ){
							el.addEventListener(type, fn, false);
						}
						else if( el.attachEvent ){
							el.attachEvent('on'+type, fn);
						}
						else {
							el['on'+type] = fn;
						}
					}
				});
			}
		},


		/**
		 * Remove event listener
		 */
		_off = function (el, type, fn){
			if( el ){
				var uid = api.uid(el), events = _elEvents[uid] || {};

				_each(type.split(/\s+/), function (type){
					if( jQuery ){
						jQuery.event.remove(el, type, fn);
					}
					else {
						var fns = events[type] || [], i = fns.length;

						while( i-- ){
							if( fns[i] === fn ){
								fns.splice(i, 1);
								break;
							}
						}

						if( el.removeEventListener ){
							el.removeEventListener(type, fn, false);
						}
						else if( el.detachEvent ){
							el.detachEvent('on'+type, fn);
						}
						else {
							el['on'+type] = null;
						}
					}
				});
			}
		},


		_one = function(el, type, fn){
			_on(el, type, function _(evt){
				_off(el, type, _);
				fn(evt);
			});
		},


		_fixEvent = function (evt){
			if( !evt.target ){ evt.target = window.event && window.event.srcElement || document; }
			if( evt.target.nodeType === 3 ){ evt.target = evt.target.parentNode; }
			return  evt;
		},


		_supportInputAttr = function (attr){
			var input = document.createElement('input');
			input.setAttribute('type', "file");
			return attr in input;
		},


		/**
		 * FileAPI (core object)
		 */
		api = {
			version: '2.0.3',

			cors: false,
			html5: true,
			media: false,
			formData: true,

			debug: false,
			pingUrl: false,
			multiFlash: false,
			flashAbortTimeout: 0,
			withCredentials: true,

			staticPath: './dist/',

			flashUrl: 0, // @default: './FileAPI.flash.swf'
			flashImageUrl: 0, // @default: './FileAPI.flash.image.swf'

			postNameConcat: function (name, idx){
				return	name + (idx != null ? '['+ idx +']' : '');
			},

			ext2mime: {
				  jpg:	'image/jpeg'
				, tif:	'image/tiff'
				, txt:	'text/plain'
			},

			// Fallback for flash
			accept: {
				  'image/*': 'art bm bmp dwg dxf cbr cbz fif fpx gif ico iefs jfif jpe jpeg jpg jps jut mcf nap nif pbm pcx pgm pict pm png pnm qif qtif ras rast rf rp svf tga tif tiff xbm xbm xpm xwd'
				, 'audio/*': 'm4a flac aac rm mpa wav wma ogg mp3 mp2 m3u mod amf dmf dsm far gdm imf it m15 med okt s3m stm sfx ult uni xm sid ac3 dts cue aif aiff wpl ape mac mpc mpp shn wv nsf spc gym adplug adx dsp adp ymf ast afc hps xs'
				, 'video/*': 'm4v 3gp nsv ts ty strm rm rmvb m3u ifo mov qt divx xvid bivx vob nrg img iso pva wmv asf asx ogm m2v avi bin dat dvr-ms mpg mpeg mp4 mkv avc vp3 svq3 nuv viv dv fli flv wpl'
			},

			chunkSize : 0,
			chunkUploadRetry : 0,
			chunkNetworkDownRetryTimeout : 2000, // milliseconds, don't flood when network is down

			KB: _SIZE_CONST(1),
			MB: _SIZE_CONST(2),
			GB: _SIZE_CONST(3),
			TB: _SIZE_CONST(4),

			expando: 'fileapi' + (new Date).getTime(),

			uid: function (obj){
				return	obj
					? (obj[api.expando] = obj[api.expando] || api.uid())
					: (++gid, api.expando + gid)
				;
			},

			log: function (){
				if( api.debug && window.console && console.log ){
					if( console.log.apply ){
						console.log.apply(console, arguments);
					}
					else {
						console.log([].join.call(arguments, ' '));
					}
				}
			},

			/**
			 * Create new image
			 *
			 * @param {String} [src]
			 * @param {Function} [fn]   1. error -- boolean, 2. img -- Image element
			 * @returns {HTMLElement}
			 */
			newImage: function (src, fn){
				var img = document.createElement('img');
				if( fn ){
					api.event.one(img, 'error load', function (evt){
						fn(evt.type == 'error', img);
						img = null;
					});
				}
				img.src = src;
				return	img;
			},

			/**
			 * Get XHR
			 * @returns {XMLHttpRequest}
			 */
			getXHR: function (){
				var xhr;

				if( XMLHttpRequest ){
					xhr = new XMLHttpRequest;
				}
				else if( window.ActiveXObject ){
					try {
						xhr = new ActiveXObject('MSXML2.XMLHttp.3.0');
					} catch (e) {
						xhr = new ActiveXObject('Microsoft.XMLHTTP');
					}
				}

				return  xhr;
			},

			isArray: _isArray,

			support: {
				dnd:     cors && ('ondrop' in document.createElement('div')),
				cors:    cors,
				html5:   html5,
				chunked: chunked,
				dataURI: true,
				accept:   _supportInputAttr('accept'),
				multiple: _supportInputAttr('multiple')
			},

			event: {
				  on: _on
				, off: _off
				, one: _one
				, fix: _fixEvent
			},


			throttle: function(fn, delay) {
				var id, args;

				return function _throttle(){
					args = arguments;

					if( !id ){
						fn.apply(window, args);
						id = setTimeout(function (){
							id = 0;
							fn.apply(window, args);
						}, delay);
					}
				};
			},


			F: function (){},


			parseJSON: function (str){
				var json;
				if( window.JSON && JSON.parse ){
					json = JSON.parse(str);
				}
				else {
					json = (new Function('return ('+str.replace(/([\r\n])/g, '\\$1')+');'))();
				}
				return json;
			},


			trim: function (str){
				str = String(str);
				return	str.trim ? str.trim() : str.replace(/^\s+|\s+$/g, '');
			},

			/**
			 * Simple Defer
			 * @return	{Object}
			 */
			defer: function (){
				var
					  list = []
					, result
					, error
					, defer = {
						resolve: function (err, res){
							defer.resolve = noop;
							error	= err || false;
							result	= res;

							while( res = list.shift() ){
								res(error, result);
							}
						},

						then: function (fn){
							if( error !== undef ){
								fn(error, result);
							} else {
								list.push(fn);
							}
						}
				};

				return	defer;
			},

			queue: function (fn){
				var
					  _idx = 0
					, _length = 0
					, _fail = false
					, _end = false
					, queue = {
						inc: function (){
							_length++;
						},

						next: function (){
							_idx++;
							setTimeout(queue.check, 0);
						},

						check: function (){
							(_idx >= _length) && !_fail && queue.end();
						},

						isFail: function (){
							return _fail;
						},

						fail: function (){
							!_fail && fn(_fail = true);
						},

						end: function (){
							if( !_end ){
								_end = true;
								fn();
							}
						}
					}
				;
				return queue;
			},


			/**
			 * For each object
			 *
			 * @param	{Object|Array}	obj
			 * @param	{Function}		fn
			 * @param	{*}				[ctx]
			 */
			each: _each,


			/**
			 * Async for
			 * @param {Array} array
			 * @param {Function} callback
			 */
			afor: function (array, callback){
				var i = 0, n = array.length;

				if( _isArray(array) && n-- ){
					(function _next(){
						callback(n != i && _next, array[i], i++);
					})();
				}
				else {
					callback(false);
				}
			},


			/**
			 * Merge the contents of two or more objects together into the first object
			 *
			 * @param	{Object}	dst
			 * @return	{Object}
			 */
			extend: _extend,


			/**
			 * Is file instance
			 *
			 * @param  {File}  file
			 * @return {Boolean}
			 */
			isFile: function (file){
				return	html5 && file && (file instanceof File);
			},


			/**
			 * Is canvas element
			 *
			 * @param	{HTMLElement}	el
			 * @return	{Boolean}
			 */
			isCanvas: function (el){
				return	el && _rcanvas.test(el.nodeName);
			},


			getFilesFilter: function (filter){
				filter = typeof filter == 'string' ? filter : (filter.getAttribute && filter.getAttribute('accept') || '');
				return	filter ? new RegExp('('+ filter.replace(/\./g, '\\.').replace(/,/g, '|') +')$', 'i') : /./;
			},



			/**
			 * Read as DataURL
			 *
			 * @param {File|Element} file
			 * @param {Function} fn
			 */
			readAsDataURL: function (file, fn){
				if( api.isCanvas(file) ){
					_emit(file, fn, 'load', api.toDataURL(file));
				}
				else {
					_readAs(file, fn, 'DataURL');
				}
			},


			/**
			 * Read as Binary string
			 *
			 * @param {File} file
			 * @param {Function} fn
			 */
			readAsBinaryString: function (file, fn){
				if( _hasSupportReadAs('BinaryString') ){
					_readAs(file, fn, 'BinaryString');
				} else {
					// Hello IE10!
					_readAs(file, function (evt){
						if( evt.type == 'load' ){
							try {
								// dataURL -> binaryString
								evt.result = api.toBinaryString(evt.result);
							} catch (e){
								evt.type = 'error';
								evt.message = e.toString();
							}
						}
						fn(evt);
					}, 'DataURL');
				}
			},


			/**
			 * Read as ArrayBuffer
			 *
			 * @param {File} file
			 * @param {Function} fn
			 */
			readAsArrayBuffer: function(file, fn){
				_readAs(file, fn, 'ArrayBuffer');
			},


			/**
			 * Read as text
			 *
			 * @param {File} file
			 * @param {String} encoding
			 * @param {Function} [fn]
			 */
			readAsText: function(file, encoding, fn){
				if( !fn ){
					fn	= encoding;
					encoding = 'utf-8';
				}

				_readAs(file, fn, 'Text', encoding);
			},


			/**
			 * Convert image or canvas to DataURL
			 *
			 * @param   {Element}  el      Image or Canvas element
			 * @param   {String}   [type]  mime-type
			 * @return  {String}
			 */
			toDataURL: function (el, type){
				if( typeof el == 'string' ){
					return  el;
				}
				else if( el.toDataURL ){
					return  el.toDataURL(type || 'image/png');
				}
			},


			/**
			 * Canvert string, image or canvas to binary string
			 *
			 * @param   {String|Element} val
			 * @return  {String}
			 */
			toBinaryString: function (val){
				return  window.atob(api.toDataURL(val).replace(_rdata, ''));
			},


			/**
			 * Read file or DataURL as ImageElement
			 *
			 * @param	{File|String}	file
			 * @param	{Function}		fn
			 * @param	{Boolean}		[progress]
			 */
			readAsImage: function (file, fn, progress){
				if( api.isFile(file) ){
					if( apiURL ){
						/** @namespace apiURL.createObjectURL */
						var data = apiURL.createObjectURL(file);
						if( data === undef ){
							_emit(file, fn, 'error');
						}
						else {
							api.readAsImage(data, fn, progress);
						}
					}
					else {
						api.readAsDataURL(file, function (evt){
							if( evt.type == 'load' ){
								api.readAsImage(evt.result, fn, progress);
							}
							else if( progress || evt.type == 'error' ){
								_emit(file, fn, evt, null, { loaded: evt.loaded, total: evt.total });
							}
						});
					}
				}
				else if( api.isCanvas(file) ){
					_emit(file, fn, 'load', file);
				}
				else if( _rimg.test(file.nodeName) ){
					if( file.complete ){
						_emit(file, fn, 'load', file);
					}
					else {
						var events = 'error abort load';
						_one(file, events, function _fn(evt){
							if( evt.type == 'load' && apiURL ){
								/** @namespace apiURL.revokeObjectURL */
								apiURL.revokeObjectURL(file.src);
							}

							_off(file, events, _fn);
							_emit(file, fn, evt, file);
						});
					}
				}
				else if( file.iframe ){
					_emit(file, fn, { type: 'error' });
				}
				else {
					// Created image
					var img = api.newImage(file.dataURL || file);
					api.readAsImage(img, fn, progress);
				}
			},


			/**
			 * Make file by name
			 *
			 * @param	{String}	name
			 * @return	{Array}
			 */
			checkFileObj: function (name){
				var file = {}, accept = api.accept;

				if( typeof name == 'object' ){
					file = name;
				}
				else {
					file.name = (name + '').split(/\\|\//g).pop();
				}

				if( file.type == null ){
					file.type = file.name.split('.').pop();
				}

				_each(accept, function (ext, type){
					ext = new RegExp(ext.replace(/\s/g, '|'), 'i');
					if( ext.test(file.type) || api.ext2mime[file.type] ){
						file.type = api.ext2mime[file.type] || (type.split('/')[0] +'/'+ file.type);
					}
				});

				return	file;
			},


			/**
			 * Get drop files
			 *
			 * @param	{Event}	evt
			 * @param	{Function} callback
			 */
			getDropFiles: function (evt, callback){
				var
					  files = []
					, dataTransfer = _getDataTransfer(evt)
					, entrySupport = _isArray(dataTransfer.items) && dataTransfer.items[0] && _getAsEntry(dataTransfer.items[0])
					, queue = api.queue(function (){ callback(files); })
				;

				_each((entrySupport ? dataTransfer.items : dataTransfer.files) || [], function (item){
					queue.inc();

					try {
						if( entrySupport ){
							_readEntryAsFiles(item, function (err, entryFiles){
								if( err ){
									api.log('[err] getDropFiles:', err);
								} else {
									files.push.apply(files, entryFiles);
								}
								queue.next();
							});
						}
						else {
							_isRegularFile(item, function (yes){
								yes && files.push(item);
								queue.next();
							});
						}
					}
					catch( err ){
						queue.next();
						api.log('[err] getDropFiles: ', err);
					}
				});

				queue.check();
			},


			/**
			 * Get file list
			 *
			 * @param	{HTMLInputElement|Event}	input
			 * @param	{String|Function}	[filter]
			 * @param	{Function}			[callback]
			 * @return	{Array|Null}
			 */
			getFiles: function (input, filter, callback){
				var files = [];

				if( callback ){
					api.filterFiles(api.getFiles(input), filter, callback);
					return null;
				}

				if( input.jquery ){
					// jQuery object
					input.each(function (){
						files = files.concat(api.getFiles(this));
					});
					input	= files;
					files	= [];
				}

				if( typeof filter == 'string' ){
					filter	= api.getFilesFilter(filter);
				}

				if( input.originalEvent ){
					// jQuery event
					input = _fixEvent(input.originalEvent);
				}
				else if( input.srcElement ){
					// IE Event
					input = _fixEvent(input);
				}


				if( input.dataTransfer ){
					// Drag'n'Drop
					input = input.dataTransfer;
				}
				else if( input.target ){
					// Event
					input = input.target;
				}

				if( input.files ){
					// Input[type="file"]
					files = input.files;

					if( !html5 ){
						// Partial support for file api
						files[0].blob	= input;
						files[0].iframe	= true;
					}
				}
				else if( !html5 && isInputFile(input) ){
					if( api.trim(input.value) ){
						files = [api.checkFileObj(input.value)];
						files[0].blob   = input;
						files[0].iframe = true;
					}
				}
				else if( _isArray(input) ){
					files	= input;
				}

				return	api.filter(files, function (file){ return !filter || filter.test(file.name); });
			},


			/**
			 * Get total file size
			 * @param	{Array}	files
			 * @return	{Number}
			 */
			getTotalSize: function (files){
				var size = 0, i = files && files.length;
				while( i-- ){
					size += files[i].size;
				}
				return	size;
			},


			/**
			 * Get image information
			 *
			 * @param	{File}		file
			 * @param	{Function}	fn
			 */
			getInfo: function (file, fn){
				var info = {}, readers = _infoReader.concat();

				if( api.isFile(file) ){
					(function _next(){
						var reader = readers.shift();
						if( reader ){
							if( reader.test(file.type) ){
								reader(file, function (err, res){
									if( err ){
										fn(err);
									}
									else {
										_extend(info, res);
										_next();
									}
								});
							}
							else {
								_next();
							}
						}
						else {
							fn(false, info);
						}
					})();
				}
				else {
					fn('not_support_info', info);
				}
			},


			/**
			 * Add information reader
			 *
			 * @param {RegExp} mime
			 * @param {Function} fn
			 */
			addInfoReader: function (mime, fn){
				fn.test = function (type){ return mime.test(type); };
				_infoReader.push(fn);
			},


			/**
			 * Filter of array
			 *
			 * @param	{Array}		input
			 * @param	{Function}	fn
			 * @return	{Array}
			 */
			filter: function (input, fn){
				var result = [], i = 0, n = input.length, val;

				for( ; i < n; i++ ){
					if( i in input ){
						val = input[i];
						if( fn.call(val, val, i, input) ){
							result.push(val);
						}
					}
				}

				return	result;
			},


			/**
			 * Filter files
			 *
			 * @param	{Array}		files
			 * @param	{Function}	eachFn
			 * @param	{Function}	resultFn
			 */
			filterFiles: function (files, eachFn, resultFn){
				if( files.length ){
					// HTML5 or Flash
					var queue = files.concat(), file, result = [], deleted = [];

					(function _next(){
						if( queue.length ){
							file = queue.shift();
							api.getInfo(file, function (err, info){
								(eachFn(file, err ? false : info) ? result : deleted).push(file);
								_next();
							});
						}
						else {
							resultFn(result, deleted);
						}
					})();
				}
				else {
					resultFn([], files);
				}
			},


			upload: function (options){
				options = _extend({
					  jsonp: 'callback'
					, prepare: api.F
					, beforeupload: api.F
					, upload: api.F
					, fileupload: api.F
					, fileprogress: api.F
					, filecomplete: api.F
					, progress: api.F
					, complete: api.F
					, pause: api.F
					, imageOriginal: true
					, chunkSize: api.chunkSize
					, chunkUpoloadRetry: api.chunkUploadRetry
				}, options);


				if( options.imageAutoOrientation && !options.imageTransform ){
					options.imageTransform = { rotate: 'auto' };
				}


				var
					  proxyXHR = new api.XHR(options)
					, dataArray = this._getFilesDataArray(options.files)
					, _this = this
					, _total = 0
					, _loaded = 0
					, _nextFile
					, _complete = false
				;


				// calc total size
				_each(dataArray, function (data){
					_total += data.size;
				});

				// Array of files
				proxyXHR.files = [];
				_each(dataArray, function (data){
					proxyXHR.files.push(data.file);
				});

				// Set upload status props
				proxyXHR.total	= _total;
				proxyXHR.loaded	= 0;
				proxyXHR.filesLeft = dataArray.length;

				// emit "beforeupload"  event
				options.beforeupload(proxyXHR, options);

				// Upload by file
				_nextFile = function (){
					var
						  data = dataArray.shift()
						, _file = data && data.file
						, _fileLoaded = false
						, _fileOptions = _simpleClone(options)
					;

					proxyXHR.filesLeft = dataArray.length;

					if( _file && _file.name === api.expando ){
						_file = null;
						api.log('[warn] FileAPI.upload() — called without files');
					}

					if( ( proxyXHR.statusText != 'abort' || proxyXHR.current ) && data ){
					    // Mark active job
					    _complete = false;

						// Set current upload file
						proxyXHR.currentFile = _file;

						// Prepare file options
						_file && options.prepare(_file, _fileOptions);

						_this._getFormData(_fileOptions, data, function (form){
							if( !_loaded ){
								// emit "upload" event
								options.upload(proxyXHR, options);
							}

							var xhr = new api.XHR(_extend({}, _fileOptions, {

								upload: _file ? function (){
									// emit "fileupload" event
									options.fileupload(_file, xhr, _fileOptions);
								} : noop,

								progress: _file ? function (evt){
									if( !_fileLoaded ){
										// For ignore the double calls.
										_fileLoaded = (evt.loaded === evt.total);

										// emit "fileprogress" event
										options.fileprogress({
											  type:   'progress'
											, total:  data.total = evt.total
											, loaded: data.loaded = evt.loaded
										}, _file, xhr, _fileOptions);

										// emit "progress" event
										options.progress({
											  type:   'progress'
											, total:  _total
											, loaded: proxyXHR.loaded = (_loaded + data.size * (evt.loaded/evt.total))|0
										}, _file, xhr, _fileOptions);
									}
								} : noop,

								complete: function (err){
									_each(_xhrPropsExport, function (name){
										proxyXHR[name] = xhr[name];
									});

									if( _file ){
										data.total = (data.total || data.size);
										data.loaded	= data.total;

										// emulate 100% "progress"
										this.progress(data);

										// fixed throttle event
										_fileLoaded = true;

										// bytes loaded
										_loaded += data.size; // data.size != data.total, it's desirable fix this
										proxyXHR.loaded = _loaded;

										// emit "filecomplete" event
										options.filecomplete(err, xhr, _file, _fileOptions);
									}

									// upload next file
									_nextFile.call(_this);
								}
							})); // xhr


							// ...
							proxyXHR.abort = function (current){
								if (!current) { dataArray.length = 0; }
								this.current = current;
								xhr.abort();
							};

							// Start upload
							xhr.send(form);
						});
					}
					else {
						options.complete(proxyXHR.status == 200 || proxyXHR.status == 201 ? false : (proxyXHR.statusText || 'error'), proxyXHR, options);
						// Mark done state
						_complete = true;
					}
				};


				// Next tick
				setTimeout(_nextFile, 0);


				// Append more files to the existing request
				// first - add them to the queue head/tail
				proxyXHR.append = function (files, first) {
					files = api._getFilesDataArray([].concat(files));

					_each(files, function (data) {
						_total += data.size;
						proxyXHR.files.push(data.file);
						if (first) {
							dataArray.unshift(data);
						} else {
							dataArray.push(data);
						}
					});

					proxyXHR.statusText = "";

					if( _complete ){
						_nextFile.call(_this);
					}
				};


				// Removes file from queue by file reference and returns it
				proxyXHR.remove = function (file) {
				    var i = dataArray.length, _file;
				    while( i-- ){
						if( dataArray[i].file == file ){
							_file = dataArray.splice(i, 1);
							_total -= _file.size;
						}
					}
					return	_file;
				};

				return proxyXHR;
			},


			_getFilesDataArray: function (data){
				var files = [], oFiles = {};

				if( isInputFile(data) ){
					var tmp = api.getFiles(data);
					oFiles[data.name || 'file'] = data.getAttribute('multiple') !== null ? tmp : tmp[0];
				}
				else if( _isArray(data) && isInputFile(data[0]) ){
					_each(data, function (input){
						oFiles[input.name || 'file'] = api.getFiles(input);
					});
				}
				else {
					oFiles = data;
				}

				_each(oFiles, function add(file, name){
					if( _isArray(file) ){
						_each(file, function (file){
							add(file, name);
						});
					}
					else if( file && (file.name || file.image) ){
						files.push({
							  name: name
							, file: file
							, size: file.size
							, total: file.size
							, loaded: 0
						});
					}
				});

				if( !files.length ){
					// Create fake `file` object
					files.push({ file: { name: api.expando } });
				}

				return	files;
			},


			_getFormData: function (options, data, fn){
				var
					  file = data.file
					, name = data.name
					, filename = file.name
					, filetype = file.type
					, trans = api.support.transform && options.imageTransform
					, Form = new api.Form
					, queue = api.queue(function (){ fn(Form); })
					, isOrignTrans = trans && _isOriginTransform(trans)
					, postNameConcat = api.postNameConcat
				;

				(function _addFile(file/**Object*/){
					if( file.image ){ // This is a FileAPI.Image
						queue.inc();

						file.toData(function (err, image){
							// @todo: error
							filename = filename || (new Date).getTime()+'.png';

							_addFile(image);
							queue.next();
						});
					}
					else if( api.Image && trans && (/^image/.test(file.type) || _rimgcanvas.test(file.nodeName)) ){
						queue.inc();

						if( isOrignTrans ){
							// Convert to array for transform function
							trans = [trans];
						}

						api.Image.transform(file, trans, options.imageAutoOrientation, function (err, images){
							if( isOrignTrans && !err ){
								if( !dataURLtoBlob && !api.flashEngine ){
									// Canvas.toBlob or Flash not supported, use multipart
									Form.multipart = true;
								}

								Form.append(name, images[0], filename,  trans[0].type || filetype);
							}
							else {
								var addOrigin = 0;

								if( !err ){
									_each(images, function (image, idx){
										if( !dataURLtoBlob && !api.flashEngine ){
											Form.multipart = true;
										}

										if( !trans[idx].postName ){
											addOrigin = 1;
										}

										Form.append(trans[idx].postName || postNameConcat(name, idx), image, filename, trans[idx].type || filetype);
									});
								}

								if( err || options.imageOriginal ){
									Form.append(postNameConcat(name, (addOrigin ? 'original' : null)), file, filename, filetype);
								}
							}

							queue.next();
						});
					}
					else if( filename !== api.expando ){
						Form.append(name, file, filename);
					}
				})(file);


				// Append data
				_each(options.data, function add(val, name){
					if( typeof val == 'object' ){
						_each(val, function (v, i){
							add(v, postNameConcat(name, i));
						});
					}
					else {
						Form.append(name, val);
					}
				});

				queue.check();
			},


			reset: function (inp, notRemove){
				var parent, clone;

				if( jQuery ){
					clone = jQuery(inp).clone(true).insertBefore(inp).val('')[0];
					if( !notRemove ){
						jQuery(inp).remove();
					}
				} else {
					parent  = inp.parentNode;
					clone   = parent.insertBefore(inp.cloneNode(true), inp);
					clone.value = '';

					if( !notRemove ){
						parent.removeChild(inp);
					}

					_each(_elEvents[api.uid(inp)], function (fns, type){
						_each(fns, function (fn){
							_off(inp, type, fn);
							_on(clone, type, fn);
						});
					});
				}

				return  clone;
			},


			/**
			 * Load remote file
			 *
			 * @param   {String}    url
			 * @param   {Function}  fn
			 * @return  {XMLHttpRequest}
			 */
			load: function (url, fn){
				var xhr = api.getXHR();
				if( xhr ){
					xhr.open('GET', url, true);

					if( xhr.overrideMimeType ){
				        xhr.overrideMimeType('text/plain; charset=x-user-defined');
					}

					_on(xhr, 'progress', function (/**Event*/evt){
						/** @namespace evt.lengthComputable */
						if( evt.lengthComputable ){
							fn({ type: evt.type, loaded: evt.loaded, total: evt.total }, xhr);
						}
					});

					xhr.onreadystatechange = function(){
						if( xhr.readyState == 4 ){
							xhr.onreadystatechange = null;
							if( xhr.status == 200 ){
								url = url.split('/');
								/** @namespace xhr.responseBody */
								var file = {
								      name: url[url.length-1]
									, size: xhr.getResponseHeader('Content-Length')
									, type: xhr.getResponseHeader('Content-Type')
								};
								file.dataURL = 'data:'+file.type+';base64,' + api.encode64(xhr.responseBody || xhr.responseText);
								fn({ type: 'load', result: file }, xhr);
							}
							else {
								fn({ type: 'error' }, xhr);
							}
					    }
					};
				    xhr.send(null);
				} else {
					fn({ type: 'error' });
				}

				return  xhr;
			},

			encode64: function (str){
				var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=', outStr = '', i = 0;

				if( typeof str !== 'string' ){
					str	= String(str);
				}

				while( i < str.length ){
					//all three "& 0xff" added below are there to fix a known bug
					//with bytes returned by xhr.responseText
					var
						  byte1 = str.charCodeAt(i++) & 0xff
						, byte2 = str.charCodeAt(i++) & 0xff
						, byte3 = str.charCodeAt(i++) & 0xff
						, enc1 = byte1 >> 2
						, enc2 = ((byte1 & 3) << 4) | (byte2 >> 4)
						, enc3, enc4
					;

					if( isNaN(byte2) ){
						enc3 = enc4 = 64;
					} else {
						enc3 = ((byte2 & 15) << 2) | (byte3 >> 6);
						enc4 = isNaN(byte3) ? 64 : byte3 & 63;
					}

					outStr += b64.charAt(enc1) + b64.charAt(enc2) + b64.charAt(enc3) + b64.charAt(enc4);
				}

				return  outStr;
			}

		} // api
	;


	function _emit(target, fn, name, res, ext){
		var evt = {
			  type:		name.type || name
			, target:	target
			, result:	res
		};
		_extend(evt, ext);
		fn(evt);
	}


	function _hasSupportReadAs(as){
		return	FileReader && !!FileReader.prototype['readAs'+as];
	}


	function _readAs(file, fn, as, encoding){
		if( api.isFile(file) && _hasSupportReadAs(as) ){
			var Reader = new FileReader;

			// Add event listener
			_on(Reader, _readerEvents, function _fn(evt){
				var type = evt.type;
				if( type == 'progress' ){
					_emit(file, fn, evt, evt.target.result, { loaded: evt.loaded, total: evt.total });
				}
				else if( type == 'loadend' ){
					_off(Reader, _readerEvents, _fn);
					Reader = null;
				}
				else {
					_emit(file, fn, evt, evt.target.result);
				}
			});


			try {
				// ReadAs ...
				if( encoding ){
					Reader['readAs'+as](file, encoding);
				}
				else {
					Reader['readAs'+as](file);
				}
			}
			catch (err){
				_emit(file, fn, 'error', undef, { error: err.toString() });
			}
		}
		else {
			_emit(file, fn, 'error', undef, { error: 'filreader_not_support_'+as });
		}
	}


	function _isRegularFile(file, callback){
		// http://stackoverflow.com/questions/8856628/detecting-folders-directories-in-javascript-filelist-objects
		if( !file.type && (file.size % 4096) === 0 && (file.size <= 102400) ){
			if( FileReader ){
				try {
					var Reader = new FileReader();

					_one(Reader, _readerEvents, function (evt){
						var isFile = evt.type != 'error';
						callback(isFile);
						if( isFile ){
							Reader.abort();
						}
					});

					Reader.readAsDataURL(file);
				} catch( err ){
					callback(false);
				}
			}
			else {
				callback(null);
			}
		}
		else {
			callback(true);
		}
	}


	function _getAsEntry(item){
		var entry;
		if( item.getAsEntry ){ entry = item.getAsEntry(); }
		else if( item.webkitGetAsEntry ){ entry = item.webkitGetAsEntry(); }
		return	entry;
	}


	function _readEntryAsFiles(entry, callback){
		if( !entry ){
			// error
			callback('invalid entry');
		}
		else if( entry.isFile ){
			// Read as file
			entry.file(function(file){
				// success
				file.fullPath = entry.fullPath;
				callback(false, [file]);
			}, function (err){
				// error
				callback('FileError.code: '+err.code);
			});
		}
		else if( entry.isDirectory ){
			var reader = entry.createReader(), result = [];

			reader.readEntries(function(entries){
				// success
				api.afor(entries, function (next, entry){
					_readEntryAsFiles(entry, function (err, files){
						if( err ){
							api.log(err);
						}
						else {
							result = result.concat(files);
						}

						if( next ){
							next();
						}
						else {
							callback(false, result);
						}
					});
				});
			}, function (err){
				// error
				callback('directory_reader: ' + err);
			});
		}
		else {
			_readEntryAsFiles(_getAsEntry(entry), callback);
		}
	}


	function _simpleClone(obj){
		var copy = {};
		_each(obj, function (val, key){
			if( val && (typeof val === 'object') && (val.nodeType === void 0) ){
				val = _extend({}, val);
			}
			copy[key] = val;
		});
		return	copy;
	}


	function isInputFile(el){
		return	_rinput.test(el && el.tagName);
	}


	function _getDataTransfer(evt){
		return	(evt.originalEvent || evt || '').dataTransfer || {};
	}


	function _isOriginTransform(trans){
		var key;
		for( key in trans ){
			if( trans.hasOwnProperty(key) ){
				if( !(trans[key] instanceof Object || key === 'overlay' || key === 'filter') ){
					return	true;
				}
			}
		}
		return	false;
	}


	// Add default image info reader
	api.addInfoReader(/^image/, function (file/**File*/, callback/**Function*/){
		if( !file.__dimensions ){
			var defer = file.__dimensions = api.defer();

			api.readAsImage(file, function (evt){
				var img = evt.target;
				defer.resolve(evt.type == 'load' ? false : 'error', {
					  width:  img.width
					, height: img.height
				});
				img = null;
			});
		}

		file.__dimensions.then(callback);
	});


	/**
	 * Drag'n'Drop special event
	 *
	 * @param	{HTMLElement}	el
	 * @param	{Function}		onHover
	 * @param	{Function}		onDrop
	 */
	api.event.dnd = function (el, onHover, onDrop){
		var _id, _type;

		if( !onDrop ){
			onDrop = onHover;
			onHover = api.F;
		}

		if( FileReader ){
			_on(el, 'dragenter dragleave dragover', function (evt){
				var
					  types = _getDataTransfer(evt).types
					, i = types && types.length
					, debounceTrigger = false
				;

				while( i-- ){
					if( ~types[i].indexOf('File') ){
						evt[preventDefault]();

						if( _type !== evt.type ){
							_type = evt.type; // Store current type of event

							if( _type != 'dragleave' ){
								onHover.call(evt[currentTarget], true, evt);
							}

							debounceTrigger = true;
						}

						break; // exit from "while"
					}
				}

				if( debounceTrigger ){
					clearTimeout(_id);
					_id = setTimeout(function (){
						onHover.call(evt[currentTarget], _type != 'dragleave', evt);
					}, 50);
				}
			});

			_on(el, 'drop', function (evt){
				evt[preventDefault]();

				_type = 0;
				onHover.call(evt[currentTarget], false, evt);

				api.getDropFiles(evt, function (files){
					onDrop.call(evt[currentTarget], files, evt);
				});
			});
		}
		else {
			api.log("Drag'n'Drop -- not supported");
		}
	};


	/**
	 * Remove drag'n'drop
	 * @param	{HTMLElement}	el
	 * @param	{Function}		onHover
	 * @param	{Function}		onDrop
	 */
	api.event.dnd.off = function (el, onHover, onDrop){
		_off(el, 'dragenter dragleave dragover', onHover);
		_off(el, 'drop', onDrop);
	};


	// Support jQuery
	if( jQuery && !jQuery.fn.dnd ){
		jQuery.fn.dnd = function (onHover, onDrop){
			return this.each(function (){
				api.event.dnd(this, onHover, onDrop);
			});
		};

		jQuery.fn.offdnd = function (onHover, onDrop){
			return this.each(function (){
				api.event.dnd.off(this, onHover, onDrop);
			});
		};
	}

	// @export
	window.FileAPI  = _extend(api, window.FileAPI);


	// Debug info
	api.log('FileAPI: ' + api.version);
	api.log('protocol: ' + window.location.protocol);
	api.log('doctype: [' + doctype.name + '] ' + doctype.publicId + ' ' + doctype.systemId);


	// @detect 'x-ua-compatible'
	_each(document.getElementsByTagName('meta'), function (meta){
		if( /x-ua-compatible/i.test(meta.getAttribute('http-equiv')) ){
			api.log('meta.http-equiv: ' + meta.getAttribute('content'));
		}
	});


	// @configuration
	if( !api.flashUrl ){ api.flashUrl = api.staticPath + 'FileAPI.flash.swf'; }
	if( !api.flashImageUrl ){ api.flashImageUrl = api.staticPath + 'FileAPI.flash.image.swf'; }
	if( !api.flashWebcamUrl ){ api.flashWebcamUrl = api.staticPath + 'FileAPI.flash.camera.swf'; }
})(window, void 0);

/*global window, FileAPI, document */

(function (api, document, undef){
	'use strict';

	var
		min = Math.min,
		round = Math.round,
		getCanvas = function (){ return document.createElement('canvas'); },
		support = false,
		exifOrientation = {
			  8:	270
			, 3:	180
			, 6:	90
		}
	;

	try {
		support = getCanvas().toDataURL('image/png').indexOf('data:image/png') > -1;
	}
	catch (e){}


	function Image(file){
		if( file instanceof Image ){
			var img = new Image(file.file);
			api.extend(img.matrix, file.matrix);
			return	img;
		}
		else if( !(this instanceof Image) ){
			return	new Image(file);
		}

		this.file   = file;
		this.size   = file.size || 100;

		this.matrix	= {
			sx: 0,
			sy: 0,
			sw: 0,
			sh: 0,
			dx: 0,
			dy: 0,
			dw: 0,
			dh: 0,
			resize: 0, // min, max OR preview
			deg: 0,
			quality: 1, // jpeg quality
			filter: 0
		};
	}


	Image.prototype = {
		image: true,
		constructor: Image,

		set: function (attrs){
			api.extend(this.matrix, attrs);
			return	this;
		},

		crop: function (x, y, w, h){
			if( w === undef ){
				w	= x;
				h	= y;
				x = y = 0;
			}
			return	this.set({ sx: x, sy: y, sw: w, sh: h || w });
		},

		resize: function (w, h, strategy){
			if( /min|max/.test(h) ){
				strategy = h;
				h = w;
			}

			return	this.set({ dw: w, dh: h || w, resize: strategy });
		},

		preview: function (w, h){
			return	this.resize(w, h || w, 'preview');
		},

		rotate: function (deg){
			return	this.set({ deg: deg });
		},

		filter: function (filter){
			return	this.set({ filter: filter });
		},

		overlay: function (images){
			return	this.set({ overlay: images });
		},

		clone: function (){
			return	new Image(this);
		},

		_load: function (image, fn){
			var self = this;

			if( /img|video/i.test(image.nodeName) ){
				fn.call(self, null, image);
			}
			else {
				api.readAsImage(image, function (evt){
					fn.call(self, evt.type != 'load', evt.result);
				});
			}
		},

		_apply: function (image, fn){
			var
				  canvas = getCanvas()
				, m = this.getMatrix(image)
				, ctx = canvas.getContext('2d')
				, width = image.videoWidth || image.width
				, height = image.videoHeight || image.height
				, deg = m.deg
				, dw = m.dw
				, dh = m.dh
				, w = width
				, h = height
				, filter = m.filter
				, copy // canvas copy
				, buffer = image
				, overlay = m.overlay
				, queue = api.queue(function (){ fn(false, canvas); })
				, renderImageToCanvas = api.renderImageToCanvas
			;

			// For `renderImageToCanvas`
			image._type = this.file.type;

			while( min(w/dw, h/dh) > 2 ){
				w = (w/2 + 0.5)|0;
				h = (h/2 + 0.5)|0;

				copy = getCanvas();
				copy.width  = w;
				copy.height = h;

				if( buffer !== image ){
					renderImageToCanvas(copy, buffer, 0, 0, buffer.width, buffer.height, 0, 0, w, h);
					buffer = copy;
				}
				else {
					buffer = copy;
					renderImageToCanvas(buffer, image, m.sx, m.sy, m.sw, m.sh, 0, 0, w, h);
					m.sx = m.sy = m.sw = m.sh = 0;
				}
			}


			canvas.width  = (deg % 180) ? dh : dw;
			canvas.height = (deg % 180) ? dw : dh;

			canvas.type = m.type;
			canvas.quality = m.quality;

			ctx.rotate(deg * Math.PI / 180);
			renderImageToCanvas(canvas, buffer
				, m.sx, m.sy
				, m.sw || buffer.width
				, m.sh || buffer.height
				, (deg == 180 || deg == 270 ? -dw : 0)
				, (deg == 90 || deg == 180 ? -dh : 0)
				, dw, dh
			);

			dw = canvas.width;
			dh = canvas.height;

			// Apply overlay
			overlay && api.each([].concat(overlay), function (over){
				queue.inc();
				// preload
				var img = new window.Image, fn = function (){
					var
						  x = over.x|0
						, y = over.y|0
						, w = over.w || img.width
						, h = over.h || img.height
						, rel = over.rel
					;

					// center  |  right  |  left
					x = (rel == 1 || rel == 4 || rel == 7) ? (dw - w + x)/2 : (rel == 2 || rel == 5 || rel == 8 ? dw - (w + x) : x);

					// center  |  bottom  |  top
					y = (rel == 3 || rel == 4 || rel == 5) ? (dh - h + y)/2 : (rel >= 6 ? dh - (h + y) : y);

					api.event.off(img, 'error load abort', fn);

					try {
						ctx.globalAlpha = over.opacity || 1;
						ctx.drawImage(img, x, y, w, h);
					}
					catch (er){}

					queue.next();
				};

				api.event.on(img, 'error load abort', fn);
				img.src = over.src;

				if( img.complete ){
					fn();
				}
			});

			if( filter ){
				queue.inc();
				Image.applyFilter(canvas, filter, queue.next);
			}

			queue.check();
		},

		getMatrix: function (image){
			var
				  m  = api.extend({}, this.matrix)
				, sw = m.sw = m.sw || image.videoWidth || image.naturalWidth ||  image.width
				, sh = m.sh = m.sh || image.videoHeight || image.naturalHeight || image.height
				, dw = m.dw = m.dw || sw
				, dh = m.dh = m.dh || sh
				, sf = sw/sh, df = dw/dh
				, strategy = m.resize
			;

			if( strategy == 'preview' ){
				if( dw != sw || dh != sh ){
					// Make preview
					var w, h;

					if( df >= sf ){
						w	= sw;
						h	= w / df;
					} else {
						h	= sh;
						w	= h * df;
					}

					if( w != sw || h != sh ){
						m.sx	= ~~((sw - w)/2);
						m.sy	= ~~((sh - h)/2);
						sw		= w;
						sh		= h;
					}
				}
			}
			else if( strategy ){
				if( !(sw > dw || sh > dh) ){
					dw = sw;
					dh = sh;
				}
				else if( strategy == 'min' ){
					dw = round(sf < df ? min(sw, dw) : dh*sf);
					dh = round(sf < df ? dw/sf : min(sh, dh));
				}
				else {
					dw = round(sf >= df ? min(sw, dw) : dh*sf);
					dh = round(sf >= df ? dw/sf : min(sh, dh));
				}
			}

			m.sw = sw;
			m.sh = sh;
			m.dw = dw;
			m.dh = dh;

			return	m;
		},

		_trans: function (fn){
			this._load(this.file, function (err, image){
				if( err ){
					fn(err);
				}
				else {
					try {
						this._apply(image, fn);
					} catch (err){
						api.log('[err] FileAPI.Image.fn._apply:', err);
						fn(err);
					}
				}
			});
		},


		get: function (fn){
			if( api.support.transform ){
				var _this = this, matrix = _this.matrix;

				if( matrix.deg == 'auto' ){
					api.getInfo(_this.file, function (err, info){
						// rotate by exif orientation
						matrix.deg = exifOrientation[info && info.exif && info.exif.Orientation] || 0;
						_this._trans(fn);
					});
				}
				else {
					_this._trans(fn);
				}
			}
			else {
				fn('not_support_transform');
			}

			return this;
		},


		toData: function (fn){
			return this.get(fn);
		}

	};


	Image.exifOrientation = exifOrientation;


	Image.transform = function (file, transform, autoOrientation, fn){
		function _transform(err, img){
			// img -- info object
			var
				  images = {}
				, queue = api.queue(function (err){
					fn(err, images);
				})
			;

			if( !err ){
				api.each(transform, function (params, name){
					if( !queue.isFail() ){
						var ImgTrans = new Image(img.nodeType ? img : file);

						if( typeof params == 'function' ){
							params(img, ImgTrans);
						}
						else if( params.width ){
							ImgTrans[params.preview ? 'preview' : 'resize'](params.width, params.height, params.strategy);
						}
						else {
							if( params.maxWidth && (img.width > params.maxWidth || img.height > params.maxHeight) ){
								ImgTrans.resize(params.maxWidth, params.maxHeight, 'max');
							}
						}

						if( params.crop ){
							var crop = params.crop;
							ImgTrans.crop(crop.x|0, crop.y|0, crop.w || crop.width, crop.h || crop.height);
						}

						if( params.rotate === undef && autoOrientation ){
							params.rotate = 'auto';
						}

						ImgTrans.set({
							  deg: params.rotate
							, type: params.type || file.type || 'image/png'
							, quality: params.quality || 1
							, overlay: params.overlay
							, filter: params.filter
						});

						queue.inc();
						ImgTrans.toData(function (err, image){
							if( err ){
								queue.fail();
							}
							else {
								images[name] = image;
								queue.next();
							}
						});
					}
				});
			}
			else {
				queue.fail();
			}
		}


		// @todo: Оло-ло, нужно рефакторить это место
		if( file.width ){
			_transform(false, file);
		} else {
			api.getInfo(file, _transform);
		}
	};


	// @const
	api.each(['TOP', 'CENTER', 'BOTTOM'], function (x, i){
		api.each(['LEFT', 'CENTER', 'RIGHT'], function (y, j){
			Image[x+'_'+y] = i*3 + j;
			Image[y+'_'+x] = i*3 + j;
		});
	});


	/**
	 * Trabsform element to canvas
	 *
	 * @param    {Image|HTMLVideoElement}   el
	 * @returns  {Canvas}
	 */
	Image.toCanvas = function(el){
		var canvas		= document.createElement('canvas');
		canvas.width	= el.videoWidth || el.width;
		canvas.height	= el.videoHeight || el.height;
		canvas.getContext('2d').drawImage(el, 0, 0);
		return	canvas;
	};


	/**
	 * Create image from DataURL
	 * @param  {String}  dataURL
	 * @param  {Object}  size
	 * @param  {Function}  callback
	 */
	Image.fromDataURL = function (dataURL, size, callback){
		var img = api.newImage(dataURL);
		api.extend(img, size);
		callback(img);
	};


	/**
	 * Apply filter (caman.js)
	 *
	 * @param  {Canvas|Image}   canvas
	 * @param  {String|Function}  filter
	 * @param  {Function}  doneFn
	 */
	Image.applyFilter = function (canvas, filter, doneFn){
		if( typeof filter == 'function' ){
			filter(canvas, doneFn);
		}
		else if( window.Caman ){
			// http://camanjs.com/guides/
			window.Caman(canvas.tagName == 'IMG' ? Image.toCanvas(canvas) : canvas, function (){
				if( typeof filter == 'string' ){
					this[filter]();
				}
				else {
					api.each(filter, function (val, method){
						this[method](val);
					}, this);
				}
				this.render(doneFn);
			});
		}
	};


	/**
	 * For load-image-ios.js
	 */
	api.renderImageToCanvas = function (canvas, img, sx, sy, sw, sh, dx, dy, dw, dh){
		canvas.getContext('2d').drawImage(img, sx, sy, sw, sh, dx, dy, dw, dh);
		return canvas;
	};


	// @export
	api.support.canvas = api.support.transform = support;
	api.Image = Image;
})(FileAPI, document);

/*
 * JavaScript Load Image iOS scaling fixes 1.0.3
 * https://github.com/blueimp/JavaScript-Load-Image
 *
 * Copyright 2013, Sebastian Tschan
 * https://blueimp.net
 *
 * iOS image scaling fixes based on
 * https://github.com/stomita/ios-imagefile-megapixel
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/*jslint nomen: true, bitwise: true */
/*global FileAPI, window, document */

(function (factory) {
	'use strict';
	factory(FileAPI);
}(function (loadImage) {
    'use strict';

    // Only apply fixes on the iOS platform:
    if (!window.navigator || !window.navigator.platform ||
             !(/iP(hone|od|ad)/).test(window.navigator.platform)) {
        return;
    }

    var originalRenderMethod = loadImage.renderImageToCanvas;

    // Detects subsampling in JPEG images:
    loadImage.detectSubsampling = function (img) {
        var canvas,
            context;
        if (img.width * img.height > 1024 * 1024) { // only consider mexapixel images
            canvas = document.createElement('canvas');
            canvas.width = canvas.height = 1;
            context = canvas.getContext('2d');
            context.drawImage(img, -img.width + 1, 0);
            // subsampled image becomes half smaller in rendering size.
            // check alpha channel value to confirm image is covering edge pixel or not.
            // if alpha value is 0 image is not covering, hence subsampled.
            return context.getImageData(0, 0, 1, 1).data[3] === 0;
        }
        return false;
    };

    // Detects vertical squash in JPEG images:
    loadImage.detectVerticalSquash = function (img, subsampled) {
        var naturalHeight = img.naturalHeight || img.height,
            canvas = document.createElement('canvas'),
            context = canvas.getContext('2d'),
            data,
            sy,
            ey,
            py,
            alpha;
        if (subsampled) {
            naturalHeight /= 2;
        }
        canvas.width = 1;
        canvas.height = naturalHeight;
        context.drawImage(img, 0, 0);
        data = context.getImageData(0, 0, 1, naturalHeight).data;
        // search image edge pixel position in case it is squashed vertically:
        sy = 0;
        ey = naturalHeight;
        py = naturalHeight;
        while (py > sy) {
            alpha = data[(py - 1) * 4 + 3];
            if (alpha === 0) {
                ey = py;
            } else {
                sy = py;
            }
            py = (ey + sy) >> 1;
        }
        return (py / naturalHeight) || 1;
    };

    // Renders image to canvas while working around iOS image scaling bugs:
    // https://github.com/blueimp/JavaScript-Load-Image/issues/13
    loadImage.renderImageToCanvas = function (
        canvas,
        img,
        sourceX,
        sourceY,
        sourceWidth,
        sourceHeight,
        destX,
        destY,
        destWidth,
        destHeight
    ) {
        if (img._type === 'image/jpeg') {
            var context = canvas.getContext('2d'),
                tmpCanvas = document.createElement('canvas'),
                tileSize = 1024,
                tmpContext = tmpCanvas.getContext('2d'),
                subsampled,
                vertSquashRatio,
                tileX,
                tileY;
            tmpCanvas.width = tileSize;
            tmpCanvas.height = tileSize;
            context.save();
            subsampled = loadImage.detectSubsampling(img);
            if (subsampled) {
                sourceX /= 2;
                sourceY /= 2;
                sourceWidth /= 2;
                sourceHeight /= 2;
            }
            vertSquashRatio = loadImage.detectVerticalSquash(img, subsampled);
            if (subsampled || vertSquashRatio !== 1) {
                sourceY *= vertSquashRatio;
                destWidth = Math.ceil(tileSize * destWidth / sourceWidth);
                destHeight = Math.ceil(
                    tileSize * destHeight / sourceHeight / vertSquashRatio
                );
                destY = 0;
                tileY = 0;
                while (tileY < sourceHeight) {
                    destX = 0;
                    tileX = 0;
                    while (tileX < sourceWidth) {
                        tmpContext.clearRect(0, 0, tileSize, tileSize);
                        tmpContext.drawImage(
                            img,
                            sourceX,
                            sourceY,
                            sourceWidth,
                            sourceHeight,
                            -tileX,
                            -tileY,
                            sourceWidth,
                            sourceHeight
                        );
                        context.drawImage(
                            tmpCanvas,
                            0,
                            0,
                            tileSize,
                            tileSize,
                            destX,
                            destY,
                            destWidth,
                            destHeight
                        );
                        tileX += tileSize;
                        destX += destWidth;
                    }
                    tileY += tileSize;
                    destY += destHeight;
                }
                context.restore();
                return canvas;
            }
        }
        return originalRenderMethod(
            canvas,
            img,
            sourceX,
            sourceY,
            sourceWidth,
            sourceHeight,
            destX,
            destY,
            destWidth,
            destHeight
        );
    };

}));

/*global window, FileAPI */

(function (api, window){
	"use strict";

	var
		  document = window.document
		, FormData = window.FormData
		, Form = function (){ this.items = []; }
		, encodeURIComponent = window.encodeURIComponent
	;


	Form.prototype = {

		append: function (name, blob, file, type){
			this.items.push({
				  name: name
				, blob: blob && blob.blob || (blob == void 0 ? '' : blob)
				, file: blob && (file || blob.name)
				, type:	blob && (type || blob.type)
			});
		},

		each: function (fn){
			var i = 0, n = this.items.length;
			for( ; i < n; i++ ){
				fn.call(this, this.items[i]);
			}
		},

		toData: function (fn, options){
		    // allow chunked transfer if we have only one file to send
		    // flag is used below and in XHR._send
		    options._chunked = api.support.chunked && options.chunkSize > 0 && api.filter(this.items, function (item){ return item.file; }).length == 1;

			if( !api.support.html5 ){
				api.log('FileAPI.Form.toHtmlData');
				this.toHtmlData(fn);
			}
			else if( !api.formData || this.multipart || !FormData ){
				api.log('FileAPI.Form.toMultipartData');
				this.toMultipartData(fn);
			}
			else if( options._chunked ){
				api.log('FileAPI.Form.toPlainData');
				this.toPlainData(fn);
			}
			else {
				api.log('FileAPI.Form.toFormData');
				this.toFormData(fn);
			}
		},

		_to: function (data, complete, next, arg){
			var queue = api.queue(function (){
				complete(data);
			});

			this.each(function (file){
				next(file, data, queue, arg);
			});

			queue.check();
		},


		toHtmlData: function (fn){
			this._to(document.createDocumentFragment(), fn, function (file, data/**DocumentFragment*/){
				var blob = file.blob, hidden;

				if( file.file ){
					api.reset(blob, true);
					// set new name
					blob.name = file.name;
					data.appendChild(blob);
				}
				else {
					hidden = document.createElement('input');
					hidden.name  = file.name;
					hidden.type  = 'hidden';
					hidden.value = blob;
					data.appendChild(hidden);
				}
			});
		},

		toPlainData: function (fn){
			this._to({}, fn, function (file, data, queue){
				if( file.file ){
					data.type = file.file;
				}

				if( file.blob.toBlob ){
				    // canvas
					queue.inc();
					_convertFile(file, function (file, blob){
						data.name = file.name;
						data.file = blob;
						data.size = blob.length;
						data.type = file.type;
						queue.next();
					});
				}
				else if( file.file ){
				    // file
					data.name = file.blob.name;
					data.file = file.blob;
					data.size = file.blob.size;
					data.type = file.type;
				}
				else {
				    // additional data
				    if( !data.params ){
				        data.params = [];
				    }
				    data.params.push(encodeURIComponent(file.name) +"="+ encodeURIComponent(file.blob));
				}

				data.start = -1;
				data.end = data.file && data.file.FileAPIReadPosition || -1;
				data.retry = 0;
			});
		},

		toFormData: function (fn){
			this._to(new FormData, fn, function (file, data, queue){
				if( file.blob && file.blob.toBlob ){
					queue.inc();
					_convertFile(file, function (file, blob){
						data.append(file.name, blob, file.file);
						queue.next();
					});
				}
				else if( file.file ){
					data.append(file.name, file.blob, file.file);
				}
				else {
					data.append(file.name, file.blob);
				}

				if( file.file ){
					data.append('_'+file.name, file.file);
				}
			});
		},


		toMultipartData: function (fn){
			this._to([], fn, function (file, data, queue, boundary){
				queue.inc();
				_convertFile(file, function (file, blob){
					data.push(
						  '--_' + boundary + ('\r\nContent-Disposition: form-data; name="'+ file.name +'"'+ (file.file ? '; filename="'+ encodeURIComponent(file.file) +'"' : '')
						+ (file.file ? '\r\nContent-Type: '+ (file.type || 'application/octet-stream') : '')
						+ '\r\n'
						+ '\r\n'+ (file.file ? blob : encodeURIComponent(blob))
						+ '\r\n')
					);
					queue.next();
				}, true);
			}, api.expando);
		}
	};


	function _convertFile(file, fn, useBinaryString){
		var blob = file.blob, filename = file.file;

		if( filename ){
			if( !blob.toDataURL ){
				// The Blob is not an image.
				api.readAsBinaryString(blob, function (evt){
					if( evt.type == 'load' ){
						fn(file, evt.result);
					}
				});
				return;
			}

			var
				  mime = { 'image/jpeg': '.jpe?g', 'image/png': '.png' }
				, type = mime[file.type] ? file.type : 'image/png'
				, ext  = mime[type] || '.png'
				, quality = blob.quality || 1
			;

			if( !filename.match(new RegExp(ext+'$', 'i')) ){
				// Does not change the current extension, but add a new one.
				filename += ext.replace('?', '');
			}

			file.file = filename;
			file.type = type;

			if( !useBinaryString && blob.toBlob ){
				blob.toBlob(function (blob){
					fn(file, blob);
				}, type, quality);
			}
			else {
				fn(file, api.toBinaryString(blob.toDataURL(type, quality)));
			}
		}
		else {
			fn(file, blob);
		}
	}


	// @export
	api.Form = Form;
})(FileAPI, window);

/*global window, FileAPI, Uint8Array */

(function (window, api){
	"use strict";

	var
		  noop = function (){}
		, document = window.document

		, XHR = function (options){
			this.uid = api.uid();
			this.xhr = {
				  abort: noop
				, getResponseHeader: noop
				, getAllResponseHeaders: noop
			};
			this.options = options;
		},

		_xhrResponsePostfix = { '': 1, XML: 1, Text: 1, Body: 1 }
	;


	XHR.prototype = {
		status: 0,
		statusText: '',
		constructor: XHR,

		getResponseHeader: function (name){
			return this.xhr.getResponseHeader(name);
		},

		getAllResponseHeaders: function (){
			return this.xhr.getAllResponseHeaders() || {};
		},

		end: function (status, statusText){
			var _this = this, options = _this.options;

			_this.end		=
			_this.abort		= noop;
			_this.status	= status;

			if( statusText ){
				_this.statusText = statusText;
			}

			api.log('xhr.end:', status, statusText);
			options.complete(status == 200 || status == 201 ? false : _this.statusText || 'unknown', _this);

			if( _this.xhr && _this.xhr.node ){
				setTimeout(function (){
					var node = _this.xhr.node;
					try { node.parentNode.removeChild(node); } catch (e){}
					try { delete window[_this.uid]; } catch (e){}
					window[_this.uid] = _this.xhr.node = null;
				}, 9);
			}
		},

		abort: function (){
			this.end(0, 'abort');

			if( this.xhr ){
			    this.xhr.aborted = true;
				this.xhr.abort();
			}
		},

		send: function (FormData){
			var _this = this, options = this.options;

			FormData.toData(function (data){
				// Start uploading
				options.upload(options, _this);
				_this._send.call(_this, options, data);
			}, options);
		},

		_send: function (options, data){
			var _this = this, xhr, uid = _this.uid, url = options.url;

			api.log('XHR._send:', data);

			if( !options.cache ){
				// No cache
				url += (~url.indexOf('?') ? '&' : '?') + api.uid();
			}

			if( data.nodeName ){
				var jsonp = options.jsonp;

				// prepare callback in GET
				url = url.replace(/([a-z]+)=(\?)/i, '$1='+uid);

				// legacy
				options.upload(options, _this);

				xhr = document.createElement('div');
				xhr.innerHTML = '<form target="'+ uid +'" action="'+ url +'" method="POST" enctype="multipart/form-data" style="position: absolute; top: -1000px; overflow: hidden; width: 1px; height: 1px;">'
							+ '<iframe name="'+ uid +'" src="javascript:false;"></iframe>'
							+ (jsonp && (options.url.indexOf('=?') == -1) ? '<input value="'+ uid +'" name="'+jsonp+'" type="hidden"/>' : '')
							+ '</form>'
				;

				// get form-data & transport
				var
					  form = xhr.getElementsByTagName('form')[0]
					, transport = xhr.getElementsByTagName('iframe')[0]
				;

				form.appendChild(data);

				api.log(form.parentNode.innerHTML);

				// append to DOM
				document.body.appendChild(xhr);

				// keep a reference to node-transport
				_this.xhr.node = xhr;

				var
					onPostMessage = function (evt){
						if( url.indexOf(evt.origin) != -1 ){
							try {
								var result = api.parseJSON(evt.data);
								if( result.id == uid ){
									complete(result.status, result.statusText, result.response);
								}
							} catch( err ){
								complete(0, err.message);
							}
						}
					},

					// jsonp-callack
					complete = window[uid] = function (status, statusText, response){
						_this.readyState	= 4;
						_this.responseText	= response;
						_this.end(status, statusText);

						api.event.off(window, 'message', onPostMessage);
						window[uid] = xhr = transport = transport.onload = null;
					}
				;

				_this.xhr.abort = function (){
					try {
						if( transport.stop ){ transport.stop(); }
						else if( transport.contentWindow.stop ){ transport.contentWindow.stop(); }
						else { transport.contentWindow.document.execCommand('Stop'); }
					}
					catch (er) {}
					complete(0, "abort");
				};

				api.event.on(window, 'message', onPostMessage);

				transport.onload = function (){
					try {
						var
							  win = transport.contentWindow
							, doc = win.document
							, result = win.result || api.parseJSON(doc.body.innerHTML)
						;
						complete(result.status, result.statusText, result.response);
					} catch (e){}
				};

				// send
				_this.readyState = 2; // loaded
				form.submit();
				form = null;
			}
			else {
				// Clean url
				url = url.replace(/([a-z]+)=(\?)&?/i, '');

				// html5
				if (this.xhr && this.xhr.aborted) {
					api.log("Error: already aborted");
					return;
				}
				xhr = _this.xhr = api.getXHR();

				if (data.params) {
				    url += (url.indexOf('?') < 0 ? "?" : "&") + data.params.join("&");
				}

				xhr.open('POST', url, true);

				if( api.withCredentials ){
					xhr.withCredentials = "true";
				}

				if( !options.headers || !options.headers['X-Requested-With'] ){
					xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
				}

				api.each(options.headers, function (val, key){
					xhr.setRequestHeader(key, val);
				});


				if ( options._chunked ) {
					// chunked upload
					if( xhr.upload ){
						xhr.upload.addEventListener('progress', api.throttle(function (/**Event*/evt){
							if (!data.retry) {
							    // show progress only for correct chunk uploads
								options.progress({
									  type:			evt.type
									, total:		data.size
									, loaded:		data.start + evt.loaded
									, totalSize:	data.size
								}, _this, options);
							}
						}, 100), false);
					}

					xhr.onreadystatechange = function (){
						var lkb = parseInt(xhr.getResponseHeader('X-Last-Known-Byte'), 10);

						_this.status     = xhr.status;
						_this.statusText = xhr.statusText;
						_this.readyState = xhr.readyState;

						if( xhr.readyState == 4 ){
							for( var k in _xhrResponsePostfix ){
								_this['response'+k]  = xhr['response'+k];
							}
							xhr.onreadystatechange = null;

							if (!xhr.status || xhr.status - 201 > 0) {
							    api.log("Error: " + xhr.status);
								// some kind of error
								// 0 - connection fail or timeout, if xhr.aborted is true, then it's not recoverable user action
								// up - server error
								if (((!xhr.status && !xhr.aborted) || 500 == xhr.status || 416 == xhr.status) && ++data.retry <= options.chunkUploadRetry) {
									// let's try again the same chunk
									// only applicable for recoverable error codes 500 && 416
									var delay = xhr.status ? 0 : api.chunkNetworkDownRetryTimeout;

								    // inform about recoverable problems
									options.pause(data.file, options);

									// smart restart if server reports about the last known byte
									api.log("X-Last-Known-Byte: " + lkb);
									if (lkb) {
										data.end = lkb;
									} else {
										data.end = data.start - 1;
										if (416 == xhr.status) {
											data.end = data.end - options.chunkSize;
										}
									}

									setTimeout(function () {
									    _this._send(options, data);
									}, delay);
								} else {
									// no mo retries
									_this.end(xhr.status);
								}
							} else {
								// success
								data.retry = 0;

								if (data.end == data.size - 1) {
									// finished
									_this.end(xhr.status);
								} else {
									// next chunk

									// shift position if server reports about the last known byte
									api.log("X-Last-Known-Byte: " + lkb);
									if (lkb) {
										data.end = lkb;
									}
									data.file.FileAPIReadPosition = data.end;

									setTimeout(function () {
									    _this._send(options, data);
									}, 0);
								}
							}

							xhr = null;
						}
					};

					data.start = data.end + 1;
					data.end = Math.max(Math.min(data.start + options.chunkSize, data.size) - 1, data.start);

					// Retrieve a slice of file
					var
						  file = data.file
						, slice = (file.slice || file.mozSlice || file.webkitSlice)(data.start, data.end + 1)
					;

					if( data.size && !slice.size ){
						setTimeout(function (){
							_this.end(-1);
						});
					} else {
						xhr.setRequestHeader("Content-Range", "bytes " + data.start + "-" + data.end + "/" + data.size);
						xhr.setRequestHeader("Content-Disposition", 'attachment; filename=' + encodeURIComponent(data.name));
						xhr.setRequestHeader("Content-Type", data.type || "application/octet-stream");

						xhr.send(slice);
					}

					xhr.send(slice);
					file = slice = null;
				} else {
					// single piece upload
					if( xhr.upload ){
						// https://github.com/blueimp/jQuery-File-Upload/wiki/Fixing-Safari-hanging-on-very-high-speed-connections-%281Gbps%29
						xhr.upload.addEventListener('progress', api.throttle(function (/**Event*/evt){
							options.progress(evt, _this, options);
						}, 100), false);
					}

					xhr.onreadystatechange = function (){
						_this.status     = xhr.status;
						_this.statusText = xhr.statusText;
						_this.readyState = xhr.readyState;

						if( xhr.readyState == 4 ){
							for( var k in _xhrResponsePostfix ){
								_this['response'+k]  = xhr['response'+k];
							}
							xhr.onreadystatechange = null;
							_this.end(xhr.status);
							xhr = null;
						}
					};

					if( api.isArray(data) ){
						// multipart
						xhr.setRequestHeader('Content-Type', 'multipart/form-data; boundary=_'+api.expando);
						data = data.join('') +'--_'+ api.expando +'--';

						/** @namespace  xhr.sendAsBinary  https://developer.mozilla.org/ru/XMLHttpRequest#Sending_binary_content */
						if( xhr.sendAsBinary ){
							xhr.sendAsBinary(data);
						}
						else {
							var bytes = Array.prototype.map.call(data, function(c){ return c.charCodeAt(0) & 0xff; });
							xhr.send(new Uint8Array(bytes).buffer);

						}
					} else {
						// FormData
						xhr.send(data);
					}
				}
			}
		}
	};


	// @export
	api.XHR = XHR;
})(window, FileAPI);

/**
 * @class	FileAPI.Camera
 * @author	RubaXa	<trash@rubaxa.org>
 * @support	Chrome 21+, FF 18+, Opera 12+
 */

/*global window, FileAPI, jQuery */
/** @namespace LocalMediaStream -- https://developer.mozilla.org/en-US/docs/WebRTC/MediaStream_API#LocalMediaStream */
(function (window, api){
	"use strict";

	var
		URL = window.URL || window.webkitURL,

		document = window.document,
		navigator = window.navigator,

		getMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia,

		html5 = !!getMedia
	;


	// Support "media"
	api.support.media = html5;


	var Camera = function (video){
		this.video = video;
	};


	Camera.prototype = {
		isActive: function (){
			return	!!this._active;
		},


		/**
		 * Start camera streaming
		 * @param	{Function}	callback
		 */
		start: function (callback){
			var
				  _this = this
				, video = _this.video
				, _successId
				, _failId
				, _complete = function (err){
					_this._active = !err;
					clearTimeout(_failId);
					clearTimeout(_successId);
//					api.event.off(video, 'loadedmetadata', _complete);
					callback && callback(err, _this);
				}
			;

			getMedia.call(navigator, { video: true }, function (stream/**LocalMediaStream*/){
				// Success
				_this.stream = stream;

//				api.event.on(video, 'loadedmetadata', function (){
//					_complete(null);
//				});

				// Set camera stream
				video.src = URL.createObjectURL(stream);

				// Note: onloadedmetadata doesn't fire in Chrome when using it with getUserMedia.
				// See crbug.com/110938.
				_successId = setInterval(function (){
					if( _detectVideoSignal(video) ){
						_complete(null);
					}
				}, 1000);

				_failId = setTimeout(function (){
					_complete('timeout');
				}, 5000);

				// Go-go-go!
				video.play();
			}, _complete/*error*/);
		},


		/**
		 * Stop camera streaming
		 */
		stop: function (){
			try {
				this._active = false;
				this.video.pause();
				this.stream.stop();
			} catch( err ){ }
		},


		/**
		 * Create screenshot
		 * @return {FileAPI.Camera.Shot}
		 */
		shot: function (){
			return	new Shot(this.video);
		}
	};


	/**
	 * Get camera element from container
	 *
	 * @static
	 * @param	{HTMLElement}	el
	 * @return	{Camera}
	 */
	Camera.get = function (el){
		return	new Camera(el.firstChild);
	};


	/**
	 * Publish camera element into container
	 *
	 * @static
	 * @param	{HTMLElement}	el
	 * @param	{Object}		options
	 * @param	{Function}		[callback]
	 */
	Camera.publish = function (el, options, callback){
		if( typeof options == 'function' ){
			callback = options;
			options = {};
		}

		// Dimensions of "camera"
		options = api.extend({}, {
			  width:	'100%'
			, height:	'100%'
			, start:	true
		}, options);


		if( el.jquery ){
			// Extract first element, from jQuery collection
			el = el[0];
		}


		var doneFn = function (err){
			if( err ){
				callback(err);
			}
			else {
				// Get camera
				var cam = Camera.get(el);
				if( options.start ){
					cam.start(callback);
				}
				else {
					callback(null, cam);
				}
			}
		};


		el.style.width	= _px(options.width);
		el.style.height	= _px(options.height);


		if( api.html5 && html5 ){
			// Create video element
			var video = document.createElement('video');

			// Set dimensions
			video.style.width	= _px(options.width);
			video.style.height	= _px(options.height);

			// Clean container
			if( window.jQuery ){
				jQuery(el).empty();
			} else {
				el.innerHTML = '';
			}

			// Add "camera" to container
			el.appendChild(video);

			// end
			doneFn();
		}
		else {
			Camera.fallback(el, options, doneFn);
		}
	};


	Camera.fallback = function (el, options, callback){
		callback('not_support_camera');
	};


	/**
	 * @class	FileAPI.Camera.Shot
	 */
	var Shot = function (video){
		var canvas	= video.nodeName ? api.Image.toCanvas(video) : video;
		var shot	= api.Image(canvas);
		shot.type	= 'image/png';
		shot.width	= canvas.width;
		shot.height	= canvas.height;
		shot.size	= canvas.width * canvas.height * 4;
		return	shot;
	};


	/**
	 * Add "px" postfix, if value is a number
	 *
	 * @private
	 * @param	{*}  val
	 * @return	{String}
	 */
	function _px(val){
		return	val >= 0 ? val + 'px' : val;
	}


	/**
	 * @private
	 * @param	{HTMLVideoElement} video
	 * @return	{Boolean}
	 */
	function _detectVideoSignal(video){
		var canvas = document.createElement('canvas'), ctx, res = false;
		try {
			ctx = canvas.getContext('2d');
			ctx.drawImage(video, 0, 0, 1, 1);
			res = ctx.getImageData(0, 0, 1, 1).data[4] != 255;
		}
		catch( e ){}
		return	res;
	}


	// @export
	Camera.Shot	= Shot;
	api.Camera	= Camera;
})(window, FileAPI);

/**
 * FileAPI fallback to Flash
 *
 * @flash-developer  "Vladimir Demidov" <v.demidov@corp.mail.ru>
 */

/*global window, ActiveXObject, FileAPI */
(function (window, jQuery, api){
	"use strict";

	var
		  document = window.document
		, location = window.location
		, navigator = window.navigator

		, _each = api.each
		, _cameraQueue = []
	;


	api.support.flash = (function (){
		var mime = navigator.mimeTypes, has = false;

		if( navigator.plugins && typeof navigator.plugins['Shockwave Flash'] == 'object' ){
			has	= navigator.plugins['Shockwave Flash'].description && !(mime && mime['application/x-shockwave-flash'] && !mime['application/x-shockwave-flash'].enabledPlugin);
		}
		else {
			try {
				has	= !!(window.ActiveXObject && new ActiveXObject('ShockwaveFlash.ShockwaveFlash'));
			}
			catch(er){
				api.log('Flash -- does not supported.');
			}
		}

		if( has && /^file:/i.test(location) ){
			api.log('[warn] Flash does not work on `file:` protocol.');
		}

		return	has;
	})();


	   api.support.flash
	&& (0
		|| !api.html5 || !api.support.html5
		|| (api.cors && !api.support.cors)
		|| (api.media && !api.support.media)
	)
	&& (function (){
		var
			  _attr  = api.uid()
			, _retry = 0
			, _files = {}
			, _rhttp = /^https?:/i

			, flash = {
				_fn: {},


				/**
				 * Initialization & preload flash object
				 */
				init: function (){
					var child = document.body && document.body.firstChild;

					if( child ){
						do {
							if( child.nodeType == 1 ){
								api.log('FlashAPI.state: awaiting');

								var dummy = document.createElement('div');

								dummy.id = '_' + _attr;

								_css(dummy, {
									  top: 1
									, right: 1
									, width: 5
									, height: 5
									, position: 'absolute'
									, zIndex: 1e6+'' // set max zIndex
								});

								child.parentNode.insertBefore(dummy, child);
								flash.publish(dummy, _attr);

								return;
							}
						}
						while( child = child.nextSibling );
					}

					if( _retry < 10 ){
						setTimeout(flash.init, ++_retry*50);
					}
				},


				/**
				 * Publish flash-object
				 *
				 * @param {HTMLElement} el
				 * @param {String} id
				 * @param {Object} [opts]
				 */
				publish: function (el, id, opts){
					opts = opts || {};
					el.innerHTML = _makeFlashHTML({
						  id: id
						, src: _getUrl(api.flashUrl, 'r=' + api.version)
//						, src: _getUrl('http://v.demidov.boom.corp.mail.ru/uploaderfileapi/FlashFileAPI.swf?1')
						, wmode: opts.camera ? '' : 'transparent'
						, flashvars: 'callback=' + (opts.onEvent || 'FileAPI.Flash.onEvent')
							+ '&flashId='+ id
							+ '&storeKey='+ navigator.userAgent.match(/\d/ig).join('') +'_'+ api.version
							+ (flash.isReady || (api.pingUrl ? '&ping='+api.pingUrl : ''))
							+ '&timeout='+api.flashAbortTimeout
							+ (opts.camera ? '&useCamera=' + _getUrl(api.flashWebcamUrl) : '')
//							+ '&debug=1'
					}, opts);
				},


				ready: function (){
					api.log('FlashAPI.state: ready');

					flash.ready = api.F;
					flash.isReady = true;
					flash.patch();

					api.event.on(document, 'mouseover', flash.mouseover);
					api.event.on(document, 'click', function (evt){
						if( flash.mouseover(evt) ){
							evt.preventDefault
								? evt.preventDefault()
								: (evt.returnValue = true)
							;
						}
					});
				},


				getEl: function (){
					return	document.getElementById('_'+_attr);
				},


				getWrapper: function (node){
					do {
						if( /js-fileapi-wrapper/.test(node.className) ){
							return	node;
						}
					}
					while( (node = node.parentNode) && (node !== document.body) );
				},


				mouseover: function (evt){
					var target = api.event.fix(evt).target;

					if( /input/i.test(target.nodeName) && target.type == 'file' ){
						var
							  state = target.getAttribute(_attr)
							, wrapper = flash.getWrapper(target)
						;

						if( api.multiFlash ){
							// check state:
							//   i — published
							//   i — initialization
							//   r — ready
							if( state == 'i' || state == 'r' ){
								// publish fail
								return	false;
							}
							else if( state != 'p' ){
								// set "init" state
								target.setAttribute(_attr, 'i');

								var dummy = document.createElement('div');

								if( !wrapper ){
									api.log('[err] FlashAPI.mouseover: js-fileapi-wrapper not found');
									return;
								}

								_css(dummy, {
									  top:    0
									, left:   0
									, width:  target.offsetWidth + 100
									, height: target.offsetHeight + 100
									, zIndex: 1e6+'' // set max zIndex
									, position: 'absolute'
								});

								wrapper.appendChild(dummy);
								flash.publish(dummy, api.uid());

								// set "publish" state
								target.setAttribute(_attr, 'p');
							}

							return	true;
						}
						else if( wrapper ){
							// Use one flash element
							var box = _getDimensions(wrapper);

							_css(flash.getEl(), box);

							// Set current input
							flash.curInp = target;
						}
					}
					else if( !/object|embed/i.test(target.nodeName) ){
						_css(flash.getEl(), { top: 1, left: 1, width: 5, height: 5 });
					}
				},

				onEvent: function (evt){
					var type = evt.type;

					if( type == 'ready' ){
						try {
							// set "ready" state
							flash.getInput(evt.flashId).setAttribute(_attr, 'r');
						} catch (e){
						}

						flash.ready();
						setTimeout(function (){ flash.mouseenter(evt); }, 50);
						return	true;
					}
					else if( type === 'ping' ){
						api.log('(flash -> js).ping:', [evt.status, evt.savedStatus], evt.error);
					}
					else if( type === 'log' ){
						api.log('(flash -> js).log:', evt.target);
					}
					else if( type in flash ){
						setTimeout(function (){
							api.log('FlashAPI.event.'+evt.type+':', evt);
							flash[type](evt);
						}, 1);
					}
				},


				mouseenter: function (evt){
					var node = flash.getInput(evt.flashId);

					if( node ){
						// Set multiple mode
						flash.cmd(evt, 'multiple', node.getAttribute('multiple') != null);


						// Set files filter
						var accept = [], exts = {};

						_each((node.getAttribute('accept') || '').split(/,\s*/), function (mime){
							api.accept[mime] && _each(api.accept[mime].split(' '), function (ext){
								exts[ext] = 1;
							});
						});

						_each(exts, function (i, ext){
							accept.push( ext );
						});

						flash.cmd(evt, 'accept', accept.length ? accept.join(',')+','+accept.join(',').toUpperCase() : '*');
					}
				},


				get: function (id){
					return	document[id] || window[id] || document.embeds[id];
				},


				getInput: function (id){
					if( api.multiFlash ){
						try {
							var node = flash.getWrapper(flash.get(id));
							if( node ){
								return node.getElementsByTagName('input')[0];
							}
						} catch (e){
							api.log('[err] Can not find "input" by flashId:', id, e);
						}
					} else {
						return	flash.curInp;
					}
				},


				select: function (evt){
					var
						  inp = flash.getInput(evt.flashId)
						, uid = api.uid(inp)
						, files = evt.target.files
						, event
					;

					_each(files, function (file){
						api.checkFileObj(file);
					});

					_files[uid] = files;

					if( document.createEvent ){
						event = document.createEvent('Event');
						event.files = files;
						event.initEvent('change', true, true);
						inp.dispatchEvent(event);
					}
					else if( jQuery ){
						jQuery(inp).trigger({ type: 'change', files: files });
					}
					else {
						event = document.createEventObject();
						event.files = files;
						inp.fireEvent('onchange', event);
					}
				},


				cmd: function (id, name, data, last){
					try {
						api.log('(js -> flash).'+name+':', data);
						return flash.get(id.flashId || id).cmd(name, data);
					} catch (e){
						api.log('(js -> flash).onError:', e);
						if( !last ){
							// try again
							setTimeout(function (){ flash.cmd(id, name, data, true); }, 50);
						}
					}
				},


				patch: function (){
					api.flashEngine =
					api.support.transform = true;

					// FileAPI
					_inherit(api, {
						getFiles: function (input, filter, callback){
							if( callback ){
								api.filterFiles(api.getFiles(input), filter, callback);
								return null;
							}

							var files = api.isArray(input) ? input : _files[api.uid(input.target || input.srcElement || input)];


							if( !files ){
								// Файлов нету, вызываем родительский метод
								return	this.parent.apply(this, arguments);
							}


							if( filter ){
								filter	= api.getFilesFilter(filter);
								files	= api.filter(files, function (file){ return filter.test(file.name); });
							}

							return	files;
						},


						getInfo: function (file, fn){
							if( _isHtmlFile(file) ){
								this.parent.apply(this, arguments);
							}
							else if( file.isShot ){
								fn(null, file.info = {
									width: file.width,
									height: file.height
								});
							}
							else {
								if( !file.__info ){
									var defer = file.__info = api.defer();

									flash.cmd(file, 'getFileInfo', {
										  id: file.id
										, callback: _wrap(function _(err, info){
											_unwrap(_);
											defer.resolve(err, file.info = info);
										})
									});
								}

								file.__info.then(fn);
							}
						}
					});


					// FileAPI.Image
					api.support.transform = true;
					api.Image && _inherit(api.Image.prototype, {
						get: function (fn, scaleMode){
							this.set({ scaleMode: scaleMode || 'noScale' }); // noScale, exactFit
							return this.parent(fn);
						},

						_load: function (file, fn){
							api.log('FlashAPI.Image._load:', file);

							if( _isHtmlFile(file) ){
								this.parent.apply(this, arguments);
							}
							else {
								var _this = this;
								api.getInfo(file, function (err){
									fn.call(_this, err, file);
								});
							}
						},

						_apply: function (file, fn){
							api.log('FlashAPI.Image._apply:', file);

							if( _isHtmlFile(file) ){
								this.parent.apply(this, arguments);
							}
							else {
								var m = this.getMatrix(file.info), doneFn = fn;

								flash.cmd(file, 'imageTransform', {
									  id: file.id
									, matrix: m
									, callback: _wrap(function _(err, base64){
										api.log('FlashAPI.Image._apply.callback:', err);
										_unwrap(_);

										if( err ){
											doneFn(err);
										}
										else if( !api.support.html5 && (!api.support.dataURI || base64.length > 3e4) ){
											_makeFlashImage({
												  width:	(m.deg % 180) ? m.dh : m.dw
												, height:	(m.deg % 180) ? m.dw : m.dh
												, scale:	m.scaleMode
											}, base64, doneFn);
										}
										else {
											if( m.filter ){
												doneFn = function (err, img){
													if( err ){
														fn(err);
													}
													else {
														api.Image.applyFilter(img, m.filter, function (){
															fn(err, this.canvas);
														});
													}
												};
											}

											api.newImage('data:'+ file.type +';base64,'+ base64, doneFn);
										}
									})
								});
							}
						},

						toData: function (fn){
							var
								  file = this.file
								, info = file.info
								, matrix = this.getMatrix(info)
							;

							if( _isHtmlFile(file) ){
								this.parent.apply(this, arguments);
							}
							else {
								if( matrix.deg == 'auto' ){
									matrix.deg = api.Image.exifOrientation[info && info.exif && info.exif.Orientation] || 0;
								}

								fn.call(this, !file.info, {
									  id:		file.id
									, flashId:	file.flashId
									, name:		file.name
									, type:		file.type
									, matrix:	matrix
								});
							}
						}
					});


					api.Image && _inherit(api.Image, {
						fromDataURL: function (dataURL, size, callback){
							if( !api.support.dataURI || dataURL.length > 3e4 ){
								_makeFlashImage(
									  api.extend({ scale: 'exactFit' }, size)
									, dataURL.replace(/^data:[^,]+,/, '')
									, function (err, el){ callback(el); }
								);
							}
							else {
								this.parent(dataURL, size, callback);
							}
						}
					});



					// FileAPI.Camera:statics
					api.Camera.fallback = function (el, options, callback){
						var camId = api.uid();
						api.log('FlashAPI.Camera.publish: ' + camId);
						flash.publish(el, camId, api.extend(options, {
							camera: true,
							onEvent: _wrap(function _(evt){
								if( evt.type == 'camera' ){
									_unwrap(_);

									if( evt.error ){
										api.log('FlashAPI.Camera.publish.error: ' + evt.error);
										callback(evt.error);
									}
									else {
										api.log('FlashAPI.Camera.publish.success: ' + camId);
										callback(null);
									}
								}
							})
						}));
					};

					// Run
					_each(_cameraQueue, function (args){
						api.Camera.fallback.apply(api.Camera, args);
					});
					_cameraQueue = [];


					// FileAPI.Camera:proto
					_inherit(api.Camera.prototype, {
						_id: function (){
							return	this.video.id;
						},

						start: function (callback){
							var _this = this;
							flash.cmd(this._id(), 'camera.on', {
								callback: _wrap(function _(evt){
									_unwrap(_);

									if( evt.error ){
										api.log('FlashAPI.camera.on.error: ' + evt.error);
										callback(evt.error, _this);
									}
									else {
										api.log('FlashAPI.camera.on.success: ' + _this._id());
										_this._active = true;
										callback(null, _this);
									}
								})
							});
						},

						stop: function (){
							this._active = false;
							flash.cmd(this._id(), 'camera.off');
						},

						shot: function (){
							api.log('FlashAPI.Camera.shot:', this._id());

							var shot = flash.cmd(this._id(), 'shot', {});
							shot.type = 'image/png';
							shot.flashId = this._id();
							shot.isShot = true;

							return	new api.Camera.Shot(shot);
						}
					});


					// FileAPI.Form
					_inherit(api.Form.prototype, {
						toData: function (fn){
							var items = this.items, i = items.length;

							for( ; i--; ){
								if( items[i].file && _isHtmlFile(items[i].blob) ){
									return this.parent.apply(this, arguments);
								}
							}

							api.log('FlashAPI.Form.toData');
							fn(items);
						}
					});


					// FileAPI.XHR
					_inherit(api.XHR.prototype, {
						_send: function (options, formData){
							if(
								   formData.nodeName
								|| formData.append && api.support.html5
								|| api.isArray(formData) && (typeof formData[0] === 'string')
							){
								// HTML5, Multipart or IFrame
								return	this.parent.apply(this, arguments);
							}


							var
								  data = {}
								, files = {}
								, _this = this
								, flashId
								, fileId
							;

							_each(formData, function (item){
								if( item.file ){
									files[item.name] = item = _getFileDescr(item.blob);
									fileId  = item.id;
									flashId = item.flashId;
								}
								else {
									data[item.name] = item.blob;
								}
							});

							if( !fileId ){
								flashId = _attr;
							}

							if( !flashId ){
								api.log('[err] FlashAPI._send: flashId -- undefined');
								return this.parent.apply(this, arguments);
							}
							else {
								api.log('FlashAPI.XHR._send: '+ flashId +' -> '+ fileId, files);
							}

							_this.xhr = {
								headers: {},
								abort: function (){ flash.cmd(flashId, 'abort', { id: fileId }); },
								getResponseHeader: function (name){ return this.headers[name]; },
								getAllResponseHeaders: function (){ return this.headers; }
							};

							var queue = api.queue(function (){
								flash.cmd(flashId, 'upload', {
									  url: _getUrl(options.url)
									, data: data
									, files: fileId ? files : null
									, headers: options.headers || {}
									, callback: _wrap(function upload(evt){
										var type = evt.type, result = evt.result;

										api.log('FlashAPI.upload.'+type+':', evt);

										if( type == 'progress' ){
											evt.loaded = Math.min(evt.loaded, evt.total); // @todo fixme
											evt.lengthComputable = true;
											options.progress(evt);
										}
										else if( type == 'complete' ){
											_unwrap(upload);

											if( typeof result == 'string' ){
												_this.responseText	= result.replace(/%22/g, "\"").replace(/%5c/g, "\\").replace(/%26/g, "&").replace(/%25/g, "%");
											}

											_this.end(evt.status || 200);
										}
										else if( type == 'abort' || type == 'error' ){
											_this.end(evt.status || 0, evt.message);
											_unwrap(upload);
										}
									})
								});
							});


							// #2174: FileReference.load() call while FileReference.upload() or vice versa
							_each(files, function (file){
								queue.inc();
								api.getInfo(file, queue.next);
							});

							queue.check();
						}
					});
				}
			}
		;


		function _makeFlashHTML(opts){
			return ('<object id="#id#" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'+(opts.width || '100%')+'" height="'+(opts.height || '100%')+'">'
				+ '<param name="movie" value="#src#" />'
				+ '<param name="flashvars" value="#flashvars#" />'
				+ '<param name="swliveconnect" value="true" />'
				+ '<param name="allowscriptaccess" value="always" />'
				+ '<param name="allownetworking" value="all" />'
				+ '<param name="menu" value="false" />'
				+ '<param name="wmode" value="#wmode#" />'
				+ '<embed flashvars="#flashvars#" swliveconnect="true" allownetworking="all" allowscriptaccess="always" name="#id#" src="#src#" width="'+(opts.width || '100%')+'" height="'+(opts.height || '100%')+'" menu="false" wmode="transparent" type="application/x-shockwave-flash"></embed>'
				+ '</object>').replace(/#(\w+)#/ig, function (a, name){ return opts[name]; })
			;
		}


		function _css(el, css){
			if( el && el.style ){
				var key, val;
				for( key in css ){
					val = css[key];
					if( typeof val == 'number' ){
						val += 'px';
					}
					try { el.style[key] = val; } catch (e) {}
				}
			}
		}


		function _inherit(obj, methods){
			_each(methods, function (fn, name){
				var prev = obj[name];
				obj[name] = function (){
					this.parent = prev;
					return fn.apply(this, arguments);
				};
			});
		}

		function _isHtmlFile(file){
			return	file && !file.flashId;
		}

		function _wrap(fn){
			var id = fn.wid = api.uid();
			flash._fn[id] = fn;
			return	'FileAPI.Flash._fn.'+id;
		}


		function _unwrap(fn){
			try {
				flash._fn[fn.wid] = null;
				delete	flash._fn[fn.wid];
			}
			catch(e){}
		}


		function _getUrl(url, params){
			if( !_rhttp.test(url) ){
				if( /^\.\//.test(url) || '/' != url.charAt(0) ){
					var path = location.pathname;
					path = path.substr(0, path.lastIndexOf('/'));
					url = (path +'/'+ url).replace('/./', '/');
				}

				if( '//' != url.substr(0, 2) ){
					url = '//' + location.host + url;
				}

				if( !_rhttp.test(url) ){
					url = location.protocol + url;
				}
			}

			if( params ){
				url += (/\?/.test(url) ? '&' : '?') + params;
			}

			return	url;
		}


		function _makeFlashImage(opts, base64, fn){
			var
				  key
				, flashId = api.uid()
				, el = document.createElement('div')
				, attempts = 10
			;

			for( key in opts ){
				el.setAttribute(key, opts[key]);
				el[key] = opts[key];
			}

			_css(el, opts);

			opts.width	= '100%';
			opts.height	= '100%';

			el.innerHTML = _makeFlashHTML(api.extend({
				  id: flashId
				, src: _getUrl(api.flashImageUrl, 'r='+ api.uid())
				, wmode: 'opaque'
				, flashvars: 'scale='+ opts.scale +'&callback='+_wrap(function _(){
					_unwrap(_);
					if( --attempts > 0 ){
						_setImage();
					}
					return true;
				})
			}, opts));

			function _setImage(){
				try {
					// Get flash-object by id
					var img = flash.get(flashId);
					img.setImage(base64);
				} catch (e){
					api.log('[err] FlashAPI.Preview.setImage -- can not set "base64":', e);
				}
			}

			fn(false, el);
			el = null;
		}


		function _getFileDescr(file){
			return	{
				  id: file.id
				, name: file.name
				, matrix: file.matrix
				, flashId: file.flashId
			};
		}


		function _getDimensions(el){
			var
				  box = el.getBoundingClientRect()
				, body = document.body
				, docEl = (el && el.ownerDocument).documentElement
			;

			return {
				  top:		box.top + (window.pageYOffset || docEl.scrollTop)  - (docEl.clientTop || body.clientTop || 0)
				, left:		box.left + (window.pageXOffset || docEl.scrollLeft) - (docEl.clientLeft || body.clientLeft || 0)
				, width:	box.right - box.left
				, height:	box.bottom - box.top
			};
		}


		api.Camera.fallback = function (){
			_cameraQueue.push(arguments);
		};


		// @export
		api.Flash = flash;


		// Check dataURI support
		api.newImage('data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==', function (err, img){
			api.support.dataURI = !(img.width != 1 || img.height != 1);
			flash.init();
		});
	})();
})(window, window.jQuery, FileAPI);
if( typeof define === "function" && define.amd ){ define("FileAPI", [], function (){ return FileAPI; }); }
(function(){
    var maxFileSize = parseInt(MODx.config['upload_maxsize'], 10);
    var permittedFileTypes = MODx.config['upload_files'].toLowerCase().split(',');

    FileAPI.debug = false;
    FileAPI.staticPath = MODx.config['manager_url'] + 'assets/fileapi/';

    var api = {
        humanFileSize: function(bytes, si) {
            var thresh = si ? 1000 : 1024;
            if(bytes < thresh) return bytes + ' B';
            var units = si ? ['kB','MB','GB','TB','PB','EB','ZB','YB'] : ['KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB'];
            var u = -1;
            do {
                bytes /= thresh;
                ++u;
            } while(bytes >= thresh);
            return bytes.toFixed(1)+' '+units[u];
        },

        getFileExtension: function(filename)
        {
            var result = '';
            var parts = filename.split('.');
            if (parts.length > 1) {
                result = parts.pop();
            }
            return result;
        },

        isFileSizePermitted: function(size){
            return (size <= maxFileSize);
        },

        formatBytes: function(size, unit){
            unit = unit || FileAPI.MB;
            return Math.round(((size / unit) + 0.00001) * 100) / 100;
        }
    };

    Ext.namespace('MODx.util.MultiUploadDialog');

    /**
     * File upload browse button.
     *
     * @class MODx.util.MultiUploadDialog.BrowseButton
     */
    MODx.util.MultiUploadDialog.BrowseButton = Ext.extend(Ext.Button,{
        input_name : 'file',
        input_file : null,
        original_handler : null,
        original_scope : null,

        /**
        * @access private
        */
        initComponent : function()
        {
            MODx.util.MultiUploadDialog.BrowseButton.superclass.initComponent.call(this);
            this.original_handler = this.handler || null;
            this.original_scope = this.scope || window;
            this.handler = null;
            this.scope = null;
        },

        /**
        * @access private
        */
        onRender : function(ct, position)
        {
            MODx.util.MultiUploadDialog.BrowseButton.superclass.onRender.call(this, ct, position);
            this.createInputFile();
        },

        /**
        * @access private
        */
        createInputFile : function()
        {
            var button_container = this.el.child('button').wrap();

            // button_container.position('relative');
            this.input_file = button_container.createChild({
                tag: 'input',
                type: 'file',
                size: 1,
                name: this.input_name || Ext.id(this.el),
                style: "cursor: pointer; display: inline-block; opacity: 0; position: absolute; top: 0; left: 0; width: 100%; height: 100%;",
                multiple: true
            });
            // this can all be done via the inline css above
            // doing it like this prevents a too big hidden file field creating weird hover behavoir on the buttons
            // this.input_file.setOpacity(0.0);

            // var button_box = this.el.getBox();
            // this.input_file.setStyle('font-size', (button_box.height * 1) + 'px');

            // var adj = {x: -3, y: -3};

            // this.input_file.setLeft(adj.x + 'px');
            // this.input_file.setTop(adj.y + 'px');
            // this.input_file.setOpacity(0.0);

            if (this.handleMouseEvents) {
                this.input_file.on('mouseover', this.onMouseOver, this);
                this.input_file.on('mousedown', this.onMouseDown, this);
            }

            if(this.tooltip){
                if(typeof this.tooltip == 'object'){
                    Ext.QuickTips.register(Ext.apply({target: this.input_file}, this.tooltip));
                }
                else {
                    this.input_file.dom[this.tooltipType] = this.tooltip;
                }
            }

            this.input_file.on('change', this.onInputFileChange, this);
            this.input_file.on('click', function(e) { e.stopPropagation(); });
        },

        /**
        * @access public
        */
        detachInputFile : function(no_create)
        {
            var result = this.input_file;

            if (typeof this.tooltip == 'object') {
                Ext.QuickTips.unregister(this.input_file);
            }
            else {
                this.input_file.dom[this.tooltipType] = null;
            }

            this.input_file.removeAllListeners();
            this.input_file = null;

            return result;
        },

        /**
        * @access public
        */
        getInputFile : function()
        {
            return this.input_file;
        },

        /**
        * @access public
        */
        disable : function()
        {
            MODx.util.MultiUploadDialog.BrowseButton.superclass.disable.call(this);
            this.input_file.dom.disabled = true;
        },

        /**
        * @access public
        */
        enable : function()
        {
            MODx.util.MultiUploadDialog.BrowseButton.superclass.enable.call(this);
            this.input_file.dom.disabled = false;
        },

        /**
        * @access public
        */
        destroy : function()
        {
            var input_file = this.detachInputFile(true);

            input_file.remove();
            input_file = null;

            MODx.util.MultiUploadDialog.BrowseButton.superclass.destroy.call(this);
        },

        /**
        * @access public
        */
        reset : function()
        {
            var form = new Ext.Element(document.createElement('form'));
            var buttonParent = this.input_file.parent();
            form.appendChild(this.input_file);
            form.dom.reset();
            buttonParent.appendChild(this.input_file);
        },

        /**
        * @access private
        */
        onInputFileChange : function(ev)
        {
            if (this.original_handler) {
                this.original_handler.call(this.original_scope, this, ev);
            }
            this.fireEvent('click', this, ev);
        }
    });
    Ext.reg('multiupload-browse-btn', MODx.util.MultiUploadDialog.BrowseButton);


    MODx.util.MultiUploadDialog.FilesGrid = function(config) {
        config = config || {};
        Ext.applyIf(config,{
            height: 300
            ,autoScroll: true
            ,border: false
            ,fields: ['name', 'size', 'file', 'permitted', 'message', 'uploaded']
            ,paging: false
            ,remoteSort: false
            ,viewConfig: {
                forceFit: true
                ,getRowClass: function(record, index, rowParams){
                    if(!record.get('permitted')){
                        return 'upload-error';
                    }
                    else if(record.get('uploaded')){
                        return 'upload-success';
                    }
                }
            }
            ,sortInfo: {
                field: 'name'
                ,direction: 'ASC'
            }
            ,deferRowRender: true
            ,anchor: '100%'
            ,autoExpandColumn: 'state'
            ,columns: [{
                header: _('upload.columns.file')
                ,dataIndex: 'name'
                ,sortable: true
                ,width: 200
                ,renderer: function(value, meta, record){
                    var id = Ext.id();

                    FileAPI.Image(record.get('file'))
                        .resize(100, 50, 'max')
                        .get(function (err, img){
                            if(!err){
                                img = new Ext.Element(img).addClass('upload-thumb');
                                Ext.get(id).insertFirst(img);
                            }
                        });

                    return '<div id="' + id + '"><p>' + value + '</p></div>';
                }
            }
            ,{
                header: _('upload.columns.state')
                ,id: 'state'
                ,width: 100
                ,renderer: function(value, meta, record) {
                    if(!record.get('permitted') || record.get('uploaded')){
                        return '<p class="upload-status-text">' + record.get('message') + '</p>';
                    }
                    else{
                        var id = Ext.id();
                        (function() {
                            record.progressbar = new Ext.ProgressBar({
                                renderTo: id
                                ,value: 0
                                ,text : '0 / ' + record.get('size')
                            });
                        }).defer(25);
                        return '<div id="' + id + '"></div>';
                    }
                }
            }]
            ,getMenu: function() {
                return [{
                    text: _('upload.contextmenu.remove_entry')
                    ,handler: this.removeFile
                }];
            }
        });
        MODx.util.MultiUploadDialog.FilesGrid.superclass.constructor.call(this,config);
    };


    Ext.extend(MODx.util.MultiUploadDialog.FilesGrid,MODx.grid.LocalGrid,{
        removeFile: function() {
            var selected = this.getSelectionModel().getSelections();
            this.getStore().remove(selected);
        }
    });
    Ext.reg('multiupload-grid-files',MODx.util.MultiUploadDialog.FilesGrid);



    MODx.util.MultiUploadDialog.Dialog = function(config) {
        this.filesGridId = Ext.id();

        config = config || {};
        Ext.applyIf(config,{
            permitted_extensions: permittedFileTypes
            ,autoHeight: true
            ,width: 600
            ,closeAction: 'hide'
            ,layout: 'anchor'
            ,listeners: {
                'show': {fn: this.onShow}
                ,'hide': {fn: this.onHide}
            }
            ,items: [{
                    xtype: 'multiupload-grid-files'
                    ,id: this.filesGridId
                    ,anchor: '100%'
                }
            ]
            ,buttons: [
                {
                    xtype: 'multiupload-browse-btn'
                    ,text: _('upload.buttons.choose')
                    ,cls: 'primary-button'
                    ,listeners: {
                        'click': {
                            scope: this,
                            fn: function(btn, ev){
                                var files = FileAPI.getFiles(ev);
                                this.addFiles(files);
                                btn.reset();
                            }
                        }
                    }
                }
                ,{
                    xtype: 'splitbutton'
                    ,text: _('upload.buttons.clear')
                    ,listeners: {
                        'click': {
                            scope: this,
                            fn: this.clearStore
                        }
                    }
                    ,menu: new Ext.menu.Menu({
                        items: [
                            {
                                text: _('upload.clear_list.all')
                                ,listeners: {
                                    'click': {
                                        scope: this,
                                        fn: this.clearStore
                                    }
                                }
                            }
                            ,{
                                text: _('upload.clear_list.notpermitted')
                                ,listeners: {
                                    'click': {
                                        scope: this,
                                        fn: this.clearNotPermittedItems
                                    }
                                }
                            }
                        ]
                    })
                }
                ,{
                    xtype: 'button'
                    ,text: _('upload.buttons.upload')
                    ,cls: 'primary-button'
                    ,listeners: {
                        'click': {
                            scope: this
                            ,fn: this.startUpload
                        }
                    }
                }
                ,{
                    xtype: 'button'
                    ,text: _('upload.buttons.close')
                    ,listeners: {
                        'click': {
                            scope: this,
                            fn: this.hideDialog
                        }
                    }
                }

            ]
        });
        MODx.util.MultiUploadDialog.Dialog.superclass.constructor.call(this,config);
    };

    var originalWindowOnShow = Ext.Window.prototype.onShow;
    var originalWindowOnHide = Ext.Window.prototype.onHide;

    Ext.extend(MODx.util.MultiUploadDialog.Dialog, Ext.Window, {
        addFiles: function(files){
            var store = Ext.getCmp(this.filesGridId).getStore();
            var dialog = this;
            FileAPI.each(files, function(file){
                var permitted = true;
                var message = '';

                if(!api.isFileSizePermitted(file.size)){
                    message = _('upload.notpermitted.filesize', {
                        'size': api.humanFileSize(file.size),
                        'max':  api.humanFileSize(maxFileSize)
                    });
                    permitted = false;
                }

                if(!dialog.isFileTypePermitted(file.name)){
                    message = _('upload.notpermitted.extension', {
                        'ext': api.getFileExtension(file.name)
                    });
                    permitted = false;
                }

                var data = {
                    'name': file.name,
                    'size': api.humanFileSize(file.size),
                    'file': file,
                    'permitted': permitted,
                    'message': message,
                    'uploaded': false
                };

                var p = new store.recordType(data);
                store.insert(0, p);
            });
        },

        startUpload: function(){
            var dialog = this;
            var files = [];
            var params = Ext.apply(this.base_params, {
                'HTTP_MODAUTH': MODx.siteId
            });
            var store = Ext.getCmp(this.filesGridId).getStore();

            store.each(function(){
                var file = this.get('file');
                if(this.get('permitted') && !this.get('uploaded')){
                    file.record = this;
                    files.push(file);
                }
            });


            var xhr = FileAPI.upload({
                url: this.url
                ,data: params
                ,files: { file: files }
                ,fileprogress: function(evt, file){
                    file.record.progressbar.updateProgress(
                        evt.loaded/evt.total,
                        _('upload.upload_progress', {
                            'loaded': api.humanFileSize(evt.loaded),
                            'total':  file.record.get('size')
                        }),
                        true);
                }
                ,filecomplete: function (err, xhr, file, options){
                    if( !err ){
                        var resp = Ext.util.JSON.decode(xhr.response);
                        if(resp.success){
                            file.record.set('uploaded', true);
                            file.record.set('message', _('upload.upload.success'))
                        }
                        else{
                            file.record.set('permitted', false);
                            file.record.set('message', resp.message);
                        }
                    }
                    else{
                        if(xhr.status !== 401){
                            MODx.msg.alert(_('upload.msg.title.error'), err);
                        }
                    }
                }
                ,complete: function(err, xhr){
                    dialog.fireEvent('uploadsuccess');
                }
            });
        },

        removeEntry: function(record){
            Ext.getCmp(this.filesGridId).getStore().remove(record);
        },

        clearStore: function(){
            Ext.getCmp(this.filesGridId).getStore().removeAll();
        },

        clearNotPermittedItems: function(){
            var store = Ext.getCmp(this.filesGridId).getStore();
            var notPermitted = store.query('permitted', false);
            store.remove(notPermitted.getRange());
        },

        hideDialog: function(){
            this.hide();
        },

        onDDrag: function(ev){
            ev && ev.preventDefault();
        },

        onDDrop: function(ev){
            ev && ev.preventDefault();

            var dialog = this;

            FileAPI.getDropFiles(ev.browserEvent, function(files){
                if( files.length ){
                    dialog.addFiles(files);
                }
            });
        },

        onShow: function(){
            // make sure the window is shown with the reworked windows
            var ret = originalWindowOnShow.apply(this,arguments);

            var store = Ext.getCmp(this.filesGridId).getStore();
            store.removeAll();

            if(!this.isDDSet){
                this.el.on('dragenter', this.onDDrag, this);
                this.el.on('dragover', this.onDDrag, this);
                this.el.on('dragleave', this.onDDrag, this);
                this.el.on('drop', this.onDDrop, this);

                this.isDDSet = true;
            }

            return ret;
        },

        onHide: function(){
            // make sure the window is hidden with the reworked windows
            var ret = originalWindowOnHide.apply(this,arguments);

            this.el.un('dragenter', this.onDDrag, this);
            this.el.un('dragover', this.onDDrag, this);
            this.el.un('dragleave', this.onDDrag, this);
            this.el.un('drop', this.onDDrop, this);
            this.isDDSet = false;

            return ret;
        },

        setBaseParams: function(params){
            this.base_params = params;
            this.setTitle(_('upload.title.destination_path', {'path': this.base_params['path']}));
        },

        isFileTypePermitted: function(filename){
            var ext = api.getFileExtension(filename);
            return (this.permitted_extensions.indexOf(ext.toLowerCase()) > -1);
        }
    });
    Ext.reg('multiupload-window-dialog', MODx.util.MultiUploadDialog.Dialog);

})();
Ext.namespace('MODx.tree');
/**
 * Generates the Tree in Ext. All modTree classes extend this base class.
 *
 * @class MODx.tree.Tree
 * @extends Ext.tree.TreePanel
 * @constructor
 * @param {Object} config An object of options.
 * @xtype modx-tree
 */
MODx.tree.Tree = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        baseParams: {}
        ,action: 'getNodes'
        ,loaderConfig: {}
    });
    if (config.action) {
        config.baseParams.action = config.action;
    }
    config.loaderConfig.dataUrl = config.url;
    config.loaderConfig.baseParams = config.baseParams;
    Ext.applyIf(config.loaderConfig,{
        preloadChildren: true
        ,clearOnLoad: true
    });

    this.config = config;
    var tl,root;
    if (this.config.url) {
        //@TODO extend TreeLoader here
        tl = new MODx.tree.TreeLoader(config.loaderConfig);
        tl.on('beforeload',function(l,node) {
            tl.dataUrl = this.config.url+'?action='+this.config.action+'&id='+node.attributes.id;
            if (node.attributes.type) {
                tl.dataUrl += '&type='+node.attributes.type;
            }
        },this);
        tl.on('load',this.onLoad,this);
        root = {
            nodeType: 'async'
            ,text: config.root_name || config.rootName || ''
            ,qtip: config.root_qtip || config.rootQtip || ''
            ,draggable: false
            ,id: config.root_id || config.rootId || 'root'
            ,pseudoroot: true
            ,attributes: {
                pseudoroot: true
            }
            ,cls: 'tree-pseudoroot-node'
            ,iconCls: config.root_iconCls || config.rootIconCls || ''
        };
    } else {
        tl = new Ext.tree.TreeLoader({
            preloadChildren: true
            ,baseAttrs: {
                uiProvider: MODx.tree.CheckboxNodeUI
            }
        });
        root = new Ext.tree.TreeNode({
            text: this.config.rootName || ''
            ,draggable: false
            ,id: this.config.rootId || 'root'
            ,children: this.config.data || []
            ,pseudoroot: true
        });
    }
    Ext.applyIf(config,{
        useArrows: true
        ,autoScroll: true
        ,animate: true
        ,enableDD: true
        ,enableDrop: true
        ,ddAppendOnly: false
        ,containerScroll: true
        ,collapsible: true
        ,border: false
        ,autoHeight: true
        ,rootVisible: true
        ,loader: tl
        ,header: false
        ,hideBorders: true
        ,bodyBorder: false
        ,cls: 'modx-tree'
        ,root: root
        ,preventRender: false
        ,stateful: true
        ,menuConfig: {
            defaultAlign: 'tl-b?',
            enableScrolling: false,
            listeners: {
                show: function() {
                    var node = this.activeNode;
                    if (node)
                        node.ui.addClass('x-tree-selected');
                },
                hide: function() {
                    var node = this.activeNode;
                    if (node){
                        node.isSelected() || node.ui.removeClass('x-tree-selected');
                    }
                }
            }
        }
    });
    if (config.remoteToolbar === true && (config.tbar === undefined || config.tbar === null)) {
        Ext.Ajax.request({
            url: config.remoteToolbarUrl || config.url
            ,params: {
                action: config.remoteToolbarAction || 'getToolbar'
            }
            ,success: function(r) {
                r = Ext.decode(r.responseText);
                var itms = this._formatToolbar(r.object);
                var tb = this.getTopToolbar();
                if (tb) {
                    for (var i=0;i<itms.length;i++) {
                        tb.add(itms[i]);
                    }
                    tb.doLayout();
                }
            }
            ,scope:this
        });
        config.tbar = {bodyStyle: 'padding: 0'};
    } else {
        var tb = this.getToolbar();
        if (config.tbar && config.useDefaultToolbar) {
            for (var i=0;i<config.tbar.length;i++) {
                tb.push(config.tbar[i]);
            }
        } else if (config.tbar) {
            tb = config.tbar;
        }
        Ext.apply(config,{tbar: tb});
    }
    this.setup(config);
    this.config = config;

    this.on('append',this._onAppend,this)

};
Ext.extend(MODx.tree.Tree,Ext.tree.TreePanel,{
    menu: null
    ,options: {}
    ,disableHref: false

    ,onLoad: function(ldr,node,resp) {
        // no select() here, just addClass, using Active Input Cookie Value to set focus
        Ext.each(node.childNodes, function(node){
            if (node.attributes.selected) {
                //node.select();
                node.ui.addClass('x-tree-selected');
            }
        });
        
        var r = Ext.decode(resp.responseText);
        if (r.message) {
            var el = this.getTreeEl();
            el.addClass('modx-tree-load-msg');
            el.update(r.message);
            var w = 270;
            if (this.config.width > 150) {
                w = this.config.width;
            }
            el.setWidth(w);
            this.doLayout();
        }
    }

    /**
     * Sets up the tree and initializes it with the specified options.
     */
    ,setup: function(config) {
        config.listeners = config.listeners || {};
        config.listeners.render = {
            fn: function() {
                if (config.autoExpandRoot !== false || !config.hasOwnProperty('autoExpandRoot')) {
                    this.root.expand();
                }
                var tl = this.getLoader();
                Ext.apply(tl,{fullMask : new Ext.LoadMask(this.getEl())});
                tl.fullMask.removeMask=false;
                tl.on({
                    'load' : function(){this.fullMask.hide();}
                    ,'loadexception' : function(){this.fullMask.hide();}
                    ,'beforeload' : function(){this.fullMask.show();}
                    ,scope : tl
                });
            }
            ,scope: this
        };
        MODx.tree.Tree.superclass.constructor.call(this,config);
        this.addEvents('afterSort','beforeSort');
        this.cm = new Ext.menu.Menu(config.menuConfig);
        this.on('contextmenu',this._showContextMenu,this);
        this.on('beforenodedrop',this._handleDrop,this);
        this.on('nodedragover',this._handleDrop,this);
        this.on('nodedrop',this._handleDrag,this);
        this.on('click',this._saveState,this);
        this.on('contextmenu',this._saveState,this);
        this.on('click',this._handleClick,this);

        this.treestate_id = this.config.id || Ext.id();
        this.on('load',this._initExpand,this,{single: true});
        this.on('expandnode',this._saveState,this);
        this.on('collapsenode',this._saveState,this);



        /* Absolute positionning fix  */
        this.on('expandnode',function(){ var cnt = Ext.getCmp('modx-content'); if (cnt) { cnt.doLayout(); } },this);
    }

    /**
     * Expand the tree upon initialization.
     */
    ,_initExpand: function() {
        var treeState = Ext.state.Manager.get(this.treestate_id);
        if (Ext.isEmpty(treeState) && this.root) {
            this.root.expand();
            if (this.root.firstChild && this.config.expandFirst) {
                //this.root.firstChild.select();
                this.root.firstChild.expand();
            }
        } else {
            for (var i=0;i<treeState.length;i++) {
                this.expandPath(treeState[i]);
            }
        }
    }

    /**
     * Add context menu items to the tree.
     * @param {Object, Array} items Either an Object config or array of Object configs.
     */
    ,addContextMenuItem: function(items) {
        var a = items, l = a.length;
        for(var i = 0; i < l; i++) {
            a[i].scope = a[i].scope || this;
            if (a[i].handler && typeof a[i].handler == 'string') {
                a[i].handler = eval(a[i].handler);
            }
            this.cm.add(a[i]);
        }
    }

    /**
     * Shows the current context menu.
     * @param {Ext.tree.TreeNode} node The
     * @param {Ext.EventObject} e The event object run.
     */
    ,_showContextMenu: function(node,e) {
        this.cm.activeNode = node;
        this.cm.removeAll();
        var m;
        var handled = false;

        if (!Ext.isEmpty(node.attributes.treeHandler) || (node.isRoot && !Ext.isEmpty(node.childNodes[0].attributes.treeHandler))) {
            var h = Ext.getCmp(node.isRoot ? node.childNodes[0].attributes.treeHandler : node.attributes.treeHandler);
            if (h) {
                if (node.isRoot) { node.attributes.type = 'root'; }
                m = h.getMenu(this,node,e);
                handled = true;
            }
        }
        if (!handled) {
            if (this.getMenu) {
                m = this.getMenu(node,e);
            } else if (node.attributes.menu && node.attributes.menu.items) {
                m = node.attributes.menu.items;
            }
        }
        if (m && m.length > 0) {
            this.addContextMenuItem(m);
            this.cm.showAt(e.xy);
        }
        e.preventDefault();
        e.stopEvent();
    }

    /**
     * Checks to see if a node exists in a tree node's children.
     * @param {Object} t The parent node.
     * @param {Object} n The node to find.
     * @return {Boolean} True if the node exists in the parent's children.
     */
    ,hasNode: function(t, n) {
        return (t.findChild('id', n.id)) || (t.leaf === true && t.parentNode.findChild('id', n.id));
    }

    /**
     * Refreshes the tree and runs an optional func.
     * @param {Function} func The function to run.
     * @param {Object} scope The scope to run the function in.
     * @param {Array} args An array of arguments to run with.
     * @return {Boolean} True if successful.
     */
    ,refresh: function(func,scope,args) {
        var treeState = Ext.state.Manager.get(this.treestate_id);
        this.root.reload();
        if (treeState === undefined) {
            this.root.expand();
        } else {
            // Make sure we have a valid state array
            if (Ext.isArray(treeState)) {
                Ext.each(treeState, function(path, idx) {
                    this.expandPath(path);
                }, this);
            }
        }
        if (func) {
            scope = scope || this;
            args = args || [];
            this.root.on('load',function() {Ext.callback(func,scope,args);},scope);
        }
        return true;
    }

    ,removeChildren: function(node) {
        while(node.firstChild){
             var c = node.firstChild;
             node.removeChild(c);
             c.destroy();
        }
    }

    ,loadRemoteData: function(data) {
        this.removeChildren(this.getRootNode());
        for (var c in data) {
            if (typeof data[c] === 'object') {
                this.getRootNode().appendChild(data[c]);
            }
        }
    }

    ,reloadNode: function(n) {
        this.getLoader().load(n);
        n.expand();
    }

    /**
     * Abstracted remove function
     */
    ,remove: function(text,substr,split) {
        if (this.destroying) {
            return MODx.tree.Tree.superclass.remove.apply(this, arguments);
        }
        var node = this.cm.activeNode;
        var id = this._extractId(node.id,substr,split);
        var p = {action: this.config.removeAction || 'remove'};
        var pk = this.config.primaryKey || 'id';
        p[pk] = id;
        MODx.msg.confirm({
            title: this.config.removeTitle || _('warning')
            ,text: _(text)
            ,url: this.config.url
            ,params: p
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }

    ,_extractId: function(id,substr,split) {
        substr = substr || false;
        split = split || false;
        if (substr !== false) {
            id = node.id.substr(substr);
        }
        if (split !== false) {
            id = node.id.split('_');
            id = id[split];
        }
        return id;
    }

    /**
     * Expand the tree and all children.
     */
    ,expandNodes: function() {
        if (this.root) {
            this.root.expand();
            this.root.expandChildNodes(true);
        }
    }

    /**
     * Completely collapse the tree.
     */
    ,collapseNodes: function() {
        if (this.root) {
            this.root.collapseChildNodes(true);
            this.root.collapse();
        }
    }

    /**
     * Save the state of the tree's open children.
     * @param {Ext.tree.TreeNode} n The most recent expanded or collapsed node.
     */
    ,_saveState: function(n) {
        if (!this.stateful) {
            return true;
        }
        var s = Ext.state.Manager.get(this.treestate_id);
        var p = n.getPath();
        var i;
        if (!Ext.isObject(s) && !Ext.isArray(s)) {
            s = [s]; /* backwards compat */
        } else {
            s = s.slice();
        }
        if (Ext.isEmpty(p) || p == undefined) return; /* ignore invalid paths */
        if (n.expanded) { /* if expanding, add to state */
            if (Ext.isString(p) && s.indexOf(p) === -1) {
                var f = false;
                var sr;
                for (i=0;i<s.length;i++) {
                    if (s[i] == undefined || s[i] == 'undefined') { s.splice(i,1); continue; }
                    sr = s[i].search(p);
                    if (sr !== -1 && s[sr]) { /* dont add if already in */
                        if (s[sr].length > s[i].length) {
                            f = true;
                        }
                    }
                }
                if (!f) { /* if not in, add */
                    s.push(p);
                }
            }
        } else { /* if collapsing, remove from state */
            s = s.remove(p);
            /* remove all children of node */
            for (i=0;i<s.length;i++) {
                if (s[i] == undefined || s[i] == 'undefined') { s.splice(i,1); continue; }
                if (s[i].search(p) !== -1) {
                    delete s[i];
                }
            }
        }
        /* clear out undefineds */
        for (i=0;i<s.length;i++) {
            if (s[i] == undefined || s[i] == 'undefined') { s.splice(i,1); continue; }
        }
        Ext.state.Manager.set(this.treestate_id,s);
    }

    /**
     * Handles tree clicks
     * @param {Object} n The node clicked
     * @param {Object} e The event object
     */
    ,_handleClick: function (n,e) {
        e.stopEvent();
        e.preventDefault();

        if (this.disableHref) {return true;}
        if (n.attributes.page && n.attributes.page !== '') {
            if (e.button == 1) return window.open(n.attributes.page,'_blank');
            else if (e.ctrlKey == 1 || e.metaKey == 1 || e.shiftKey == 1) return window.open(n.attributes.page);
            MODx.loadPage(n.attributes.page);
        } else if (n.isExpandable()) {
            n.toggle();
        }
        return true;
    }

    ,encode: function(node) {
        if (!node) {node = this.getRootNode();}
        var _encode = function(node) {
            var resultNode = {};
            var kids = node.childNodes;
            for (var i = 0;i < kids.length;i=i+1) {
                var n = kids[i];
                resultNode[n.id] = {
                    id: n.id
                    ,checked: n.ui.isChecked()
                    ,type: n.attributes.type || ''
                    ,data: n.attributes.data || {}
                    ,children: _encode(n)
                };
            }
            return resultNode;
        };
        var nodes = _encode(node);
        return Ext.encode(nodes);
    }

    /**
     * Handles all drag events into the tree.
     * @param {Object} dropEvent The node dropped on the parent node.
     */
    ,_handleDrag: function(dropEvent) {
        function simplifyNodes(node) {
            var resultNode = {};
            var kids = node.childNodes;
            var len = kids.length;
            for (var i = 0; i < len; i++) {
                resultNode[kids[i].id] = simplifyNodes(kids[i]);
            }
            return resultNode;
        }

        var encNodes = Ext.encode(simplifyNodes(dropEvent.tree.root))
            ,source = dropEvent.dropNode;
        //var target = dropEvent.target;

        this.fireEvent('beforeSort',encNodes);
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                data: encodeURIComponent(encNodes)
                ,action: this.config.sortAction || 'sort'
                ,source_pk: source.attributes.pk
                ,source_type: source.attributes.type
            }
            ,listeners: {
                'success': {fn:function(r) {
                    var el = dropEvent.dropNode.getUI().getTextEl();
                    if (el) {Ext.get(el).frame();}
                    this.fireEvent('afterSort',{event:dropEvent,result:r});
                },scope:this}
                ,'failure': {fn:function(r) {
                    MODx.form.Handler.errorJSON(r);
                    this.refresh();
                    return false;
                },scope:this}
            }
        });
    }

    /**
     * Abstract definition to handle drop events.
     */
    ,_handleDrop: function(dropEvent) {
        var node = dropEvent.dropNode;
        if (node.isRoot) return false;

        if (!Ext.isEmpty(node.attributes.treeHandler)) {
            var h = Ext.getCmp(node.attributes.treeHandler);
            if (h) {
                return h.handleDrop(this,dropEvent);
            }
        }
    }

    /**
     * Semi unique ids across edits
     * @param {String} prefix Prefix the guid.
     * @return {String} The newly generated guid.
     */
    ,_guid: function(prefix){
        return prefix+(new Date().getTime());
    }

    /**
     * Redirects the page or the content frame to the correct location.
     * @param {String} loc The URL to direct to.
     */
    ,redirect: function(loc) {
        MODx.loadPage(loc);
    }

    ,loadAction: function(p) {
        var id = '';
        if (this.cm.activeNode && this.cm.activeNode.id) {
            var pid = this.cm.activeNode.id.split('_');
            id = 'id='+pid[1];
        }
        MODx.loadPage('?'+id+'&'+p);
    }
    /**
     * Loads the default toolbar for the tree.
     * @access private
     * @see Ext.Toolbar
     */
    ,_loadToolbar: function() {}

    /**
     * Refreshes a given tree node.
     * @access public
     * @param {String} id The ID of the node
     * @param {Boolean} self If true, will refresh self rather than parent.
     */
    ,refreshNode: function(id,self) {
        var node = this.getNodeById(id);
        if (node) {
            var n = self ? node : node.parentNode;
            this.getLoader().load(n,function() {n.expand();},this);
        }
    }

    /**
     * Refreshes selected active node
     * @access public
     */
    ,refreshActiveNode: function() {
        if (this.cm.activeNode) {
            this.getLoader().load(this.cm.activeNode,this.cm.activeNode.expand);
        } else {
            this.refresh();
        }
    }

    /**
     * Refreshes selected active node's parent
     * @access public
     */
    ,refreshParentNode: function() {
        this.getLoader().load(this.cm.activeNode.parentNode,this.cm.activeNode.expand);
    }

    /**
     * Removes specified node
     * @param {String} id The node's ID
     */
    ,removeNode: function(id) {
        var node = this.getNodeById(id);
        if (node) {
            node.remove();
        }
    }

    /**
     * Dynamically removes active node
     */
    ,removeActiveNode: function() {
        this.cm.activeNode.remove();
    }

    /**
     * Gets a default toolbar setup
     */
    ,getToolbar: function() {
        var iu = MODx.config.manager_url+'templates/default/images/restyle/icons/';
        return [{
            icon: iu+'arrow_down.png'
            ,cls: 'x-btn-icon arrow_down'
            ,tooltip: {text: _('tree_expand')}
            ,handler: this.expandNodes
            ,scope: this
        },{
            icon: iu+'arrow_up.png'
            ,cls: 'x-btn-icon arrow_up'
            ,tooltip: {text: _('tree_collapse')}
            ,handler: this.collapseNodes
            ,scope: this
        },{
            icon: iu+'refresh.png'
            ,cls: 'x-btn-icon refresh'
            ,tooltip: {text: _('tree_refresh')}
            ,handler: this.refresh
            ,scope: this
        }];
    }

    /**
     * Add Items to the toolbar.
     */
    ,_formatToolbar: function(a) {
        var l = a.length;
        for (var i = 0; i < l; i++) {
            if (a[i].handler) {
                a[i].handler = eval(a[i].handler);
            }
            Ext.applyIf(a[i],{
                scope: this
                ,cls: this.config.toolbarItemCls || 'x-btn-icon'
            });
        }
        return a;
    }

    /**
     * Allow pseudoroot actions
     * @param tree {self}
     * @param parent {Ext.tree.TreeNode} Parent node
     * @param node {Ext.tree.TreeNode} Node to be inserted
     */
    ,_onAppend: function(tree, parent, node){
        if(node.attributes.pseudoroot){

            setTimeout((function(tree){ return function(){

                var elId = node.ui.elNode.id+ '_tools';
                var el = document.createElement('div');
                    el.id = elId;
                    el.className = 'modx-tree-node-tool-ct'
                node.ui.elNode.appendChild(el);

                var inlineButtonsLang = tree.getInlineButtonsLang(node);

                var btn = MODx.load({
                    xtype: 'modx-button',
                    text: '',
                    scope: this,
                    tooltip: new Ext.ToolTip({
                        title: inlineButtonsLang.add
                        ,target: this
                    }),
                    node: node,
                    handler: function(btn,evt){
                        evt.stopPropagation(evt);
                        node.getOwnerTree().handleCreateClick(node);
                    },
                    iconCls: 'icon-plus-circle',
                    renderTo: elId,
                    listeners: {
                        mouseover: function(button, e){
                            button.tooltip.onTargetOver(e);
                        }
                        ,mouseout: function(button, e){
                            button.tooltip.onTargetOut(e);
                        }
                    }
                });

                var btn = MODx.load({
                    xtype: 'modx-button',
                    text: '',
                    scope: this,
                    tooltip: new Ext.ToolTip({
                        title: inlineButtonsLang.refresh
                        ,target: this
                    }),
                    node: node,
                    handler: function(btn,evt){
                        evt.stopPropagation(evt);
                        node.reload();
                    },
                    iconCls: 'icon-refresh',
                    renderTo: elId,
                    listeners: {
                        mouseover: function(button, e){
                            button.tooltip.onTargetOver(e);
                        }
                        ,mouseout: function(button, e){
                            button.tooltip.onTargetOut(e);
                        }
                    }
                });


                window.BTNS.push(btn);


            }}(this)),200);

            return false;

            var btn = document.createElement('div');
                btn.innerHTML = 'H';

            node.el.appendChild(btn);
        }
    }

    /**
     * Handled inline add button click
     * Need to be extended in MODx.tree.Tree instances to work properly
     *
     * @param Ext.tree.AsyncTreeNode node
     */
    ,handleCreateClick: function(node){}

    ,getInlineButtonsLang: function(node){
        var langs = {};
        if (node.id != undefined) {
            var type = node.id.substr(2).split('_');
            if (type[0] == 'type') {
                langs.add = _('new_' + type[1]);
            } else if (type[0] == 'category') {
                langs.add = _('new_' + type[0]);
            } else {
                langs.add = _('new_document');
            }
        }

        langs.refresh = _('ext_refresh');
        return langs;
    }

});
Ext.reg('modx-tree',MODx.tree.Tree);


window.BTNS = [];

/**
 * Handles ajax loading of tree nodes.
 * Overrides native Ext implementation to allow
 * for in-node action tools
 *
 * @class MODx.tree.TreeLoader
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-directory
 */
MODx.tree.TreeLoader = function(config) {
    config = config || {};
    config.id = config.id || Ext.id();
    Ext.applyIf(config,{

    });
    MODx.tree.TreeLoader.superclass.constructor.call(this,config);
};
Ext.extend(MODx.tree.TreeLoader,Ext.tree.TreeLoader,{

});
Ext.reg('modx-tree-treeloader',MODx.tree.TreeLoader);



MODx.TreeDrop = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-treedrop'
        ,ddGroup: 'modx-treedrop-dd'
    });
    MODx.TreeDrop.superclass.constructor.call(this,config);
    this.config = config;
    this.setup();
};
Ext.extend(MODx.TreeDrop,Ext.Component,{
    setup: function() {
        var ddTarget = this.config.target;
        var ddTargetEl = this.config.targetEl;
        var cfg = this.config;

        this.targetEl = new Ext.dd.DropTarget(this.config.targetEl, {
            ddGroup: this.config.ddGroup

            ,notifyEnter: function(ddSource, e, data) {
                if (ddTarget.getEl) {
                    var el = ddTarget.getEl();
                    if (el) {
                        el.frame();
                        el.focus();
                    }
                }
            }
            ,notifyDrop: function(ddSource, e, data) {
                if (!data.node || !data.node.attributes || !data.node.attributes.type) return false;
                if (data.node.attributes.type != 'modResource' && data.node.attributes.leaf != true) return false;
                var v = '';
                var win = false;
                switch (data.node.attributes.type) {
                    case 'modResource': v = '[[~'+data.node.attributes.pk+']]'; break;
                    case 'snippet': win = true; break;
                    case 'chunk': win = true; break;
                    case 'tv': win = true; break;
                    case 'file': v = data.node.attributes.url; break;
                    default:
                        var dh = Ext.getCmp(data.node.attributes.type+'-drop-handler');
                        if (dh) {
                            return dh.handle(data,{
                                ddTargetEl: ddTargetEl
                                ,cfg: cfg
                                ,iframe: cfg.iframe
                                ,iframeEl: cfg.iframeEl
                                ,onInsert: cfg.onInsert
                                ,panel: cfg.panel
                            });
                        }
                        return false;
                        break;
                }
                if (win) {
                    MODx.loadInsertElement({
                        pk: data.node.attributes.pk
                        ,classKey: data.node.attributes.classKey
                        ,name: data.node.attributes.name
                        ,output: v
                        ,ddTargetEl: ddTargetEl
                        ,cfg: cfg
                        ,iframe: cfg.iframe
                        ,iframeEl: cfg.iframeEl
                        ,onInsert: cfg.onInsert
                        ,panel: cfg.panel
                    });
                } else {
                    if (cfg.iframe) {
                        MODx.insertForRTE(v,cfg);
                    } else {
                        var el = Ext.get(ddTargetEl);
                        if (el.dom.id == 'modx-static-content') {
                            v = v.substring(1);
                            Ext.getCmp(el.dom.id).setValue('');
                        }
                        if (el.dom.id == 'modx-symlink-content' || el.dom.id == 'modx-weblink-content') {
                            Ext.getCmp(el.dom.id).setValue('');
                            MODx.insertAtCursor(ddTargetEl,data.node.attributes.pk,cfg.onInsert);
                        } else if (el.dom.id == 'modx-resource-parent') {
                            v = data.node.attributes.pk;
                            var pf = Ext.getCmp('modx-resource-parent');
                            if (v == pf.currentid) {
                                MODx.msg.alert('',_('resource_err_own_parent'));
                                return false;
                            }
                            pf.setValue(v);
                            Ext.getCmp(pf.parentcmp).setValue(v);
                            var p = Ext.getCmp(pf.formpanel);
                            if (p) { p.markDirty(); }
                        } else {
                            MODx.insertAtCursor(ddTargetEl,v,cfg.onInsert);
                        }

                        if (cfg.panel) {
                            var p = Ext.getCmp(cfg.panel);
                            if (p) { p.markDirty(); }
                        }
                    }
                }
                return true;
            }
        });
        // Allow elements & files nodes to be dropped
        this.targetEl.addToGroup('modx-treedrop-elements-dd');
        this.targetEl.addToGroup('modx-treedrop-sources-dd');
    }
});
Ext.reg('modx-treedrop',MODx.TreeDrop);

MODx.loadInsertElement = function(r) {
    if (MODx.InsertElementWindow) {
        MODx.InsertElementWindow.hide();
        MODx.InsertElementWindow.destroy();
    }
    MODx.InsertElementWindow = MODx.load({
        xtype: 'modx-window-insert-element'
        ,record: r
        ,listeners: {
            'success':{fn: function() {
            },scope:this}
            ,'hide': {fn:function() { this.destroy(); }}
        }
    });
    MODx.InsertElementWindow.setValues(r);
    MODx.InsertElementWindow.show();
};

MODx.insertAtCursor = function(myField, myValue,h) {
    if (!Ext.isEmpty(h)) {
        var z = h(myValue);
        if (z != undefined) {
            myValue = z;
        }
    }
    myField.blur();
    if (document.selection) {
        sel = document.selection.createRange();
        sel.text = myValue;
    } else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos)+ myValue+ myField.value.substring(endPos, myField.value.length);
        myField.selectionStart = startPos + myValue.length;
        myField.selectionEnd = myField.selectionStart;
    } else {
        myField.value += myValue;
    }
    myField.focus();
};

MODx.insertForRTE = function(v,cfg) {
    var fn = cfg.onInsert || false;
    if (fn) {
        fn(v,cfg);
    } else {
        if (typeof cfg.iframeEl == 'object') {
            var doc = cfg.iframeEl;
        } else {
            var doc = window.frames[0].document.getElementById(cfg.iframeEl);
        }
        if (doc.value) {
            doc.value = doc.value + v;
        } else {
            doc.innerHTML = doc.innerHTML + v;
        }
    }
};

MODx.insertIntoContent = function(v,opt) {
    if (opt.iframe) {
        MODx.insertForRTE(v,opt.cfg);
    } else {
        MODx.insertAtCursor(opt.ddTargetEl,v);
    }
}

MODx.window.InsertElement = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('select_el_opts')
        ,id: 'modx-window-insert-element'
        ,width: 522 // match 300px fieldwidth plus the fieldset
        ,labelAlign: 'left'
        ,labelWidth: 160
        ,url: MODx.config.connector_url
        ,action: 'element/template/create'
        ,fields: [{
            xtype: 'hidden'
            ,name: 'pk'
            ,id: 'modx-dise-pk'
        },{
            xtype: 'hidden'
            ,name: 'classKey'
            ,id: 'modx-dise-classkey'
        },{
            xtype: 'xcheckbox'
            ,fieldLabel: _('cached')
            ,name: 'cached'
            ,id: 'modx-dise-cached'
            ,inputValue: 1
            ,checked: true
        },{
            xtype: 'modx-combo-property-set'
            ,fieldLabel: _('property_set')
            ,name: 'propertyset'
            ,id: 'modx-dise-propset'
            ,width: 300
            ,baseParams: {
                action: 'element/propertyset/getList'
                ,showAssociated: true
                ,elementId: config.record.pk
                ,elementType: config.record.classKey
            }
            ,listeners: {
                'render': {fn:function() {Ext.getCmp('modx-dise-propset').getStore().load(); Ext.getCmp('modx-dise-propset').value = '0';},scope:this} 
                ,'select': {fn:this.changePropertySet,scope:this}
            }
        },{
            id: 'modx-dise-proplist'
            ,autoLoad: {
                url: MODx.config.connector_url
                ,params: {
                   'action': 'element/getinsertproperties'
                   ,classKey: config.record.classKey
                   ,pk: config.record.pk
                   ,resourceId: Ext.get('modx-resource-id').getValue()
                   ,propertySet: 0
                }
                ,scripts: true
                ,callback: this.onPropFormLoad
                ,scope: this
            }
            ,style: 'display: none;'
        },{
            xtype: 'fieldset'
            ,title: _('properties')
            // ,autoHeight: true
            ,height: Ext.getBody().getViewSize().height * 0.6
            ,collapsible: true
            ,autoScroll: true
            ,items: [{
                html: '<div id="modx-iprops-form"></div>'
                ,id: 'modx-iprops-container'
                // ,height: 400
                // ,autoScroll: true
            }]
        }]
    });
    MODx.window.InsertElement.superclass.constructor.call(this,config);
    this.on('show',function() {
        this.center();
        this.mask = new Ext.LoadMask(Ext.get('modx-iprops-container'), {msg:_('loading')});
        this.mask.show();
    },this);
};
Ext.extend(MODx.window.InsertElement,MODx.Window,{
    changePropertySet: function(cb) {
        var fp = Ext.getCmp('modx-iprops-fp');
        if (fp) fp.destroy();

        var u = Ext.getCmp('modx-dise-proplist').getUpdater();
        u.update({
            url: MODx.config.connector_url
            ,params: {
                'action': 'element/getinsertproperties'
                ,classKey: this.config.record.classKey
                ,pk: this.config.record.pk
                ,resourceId: Ext.get('modx-resource-id').getValue()
                ,propertySet: cb.getValue()
            }
            ,scripts: true
            ,callback: this.onPropFormLoad
            ,scope: this
        });
        this.modps = [];
        this.mask.show();
    }
    ,createStore: function(data) {
        return new Ext.data.SimpleStore({
            fields: ["v","d"]
            ,data: data
        });
    }
    ,onPropFormLoad: function(el,s,r) {
        this.mask.hide();
        var vs = Ext.decode(r.responseText);
        if (!vs || vs.length <= 0) { return false; }
        for (var i=0;i<vs.length;i++) {
            if (vs[i].store) {
                vs[i].store = this.createStore(vs[i].store);
            }
        }
        MODx.load({
            xtype: 'panel'
            ,id: 'modx-iprops-fp'
            ,layout: 'form'
            ,autoHeight: true
            ,autoScroll: true
            ,labelWidth: 150
            ,border: false
            ,items: vs
            ,renderTo: 'modx-iprops-form'
        });
    }
    ,submit: function() {
        var v = '[[';
        var n = this.config.record.name;
        var f = this.fp.getForm();

        if (f.findField('cached').getValue() != true) {
            v = v+'!';
        }
        switch (this.config.record.classKey) {
            case 'modSnippet': v = v+n; break;
            case 'modChunk': v = v+'$'+n; break;
            case 'modTemplateVar': v = v+'*'+n; break;
        }
        var ps = f.findField('propertyset').getValue();
        if (ps != 0 && ps !== '') {
            v = v+'@'+f.findField('propertyset').getRawValue();
        }
        v = v+'?';

        for (var i=0;i<this.modps.length;i++) {
            var fld = this.modps[i];
            var val = typeof(Ext.getCmp('modx-iprop-'+fld).getValue) === 'function' ? Ext.getCmp('modx-iprop-'+fld).getValue() : Ext.getCmp('modx-iprop-'+fld).value;
            if (val == true) val = 1;
            if (val == false) val = 0;
            v = v+'\n\t&'+fld+'=`'+val+'`';
        }
        v = v+'\n]]';

        if (this.config.record.iframe) {
            MODx.insertForRTE(v,this.config.record.cfg);
        } else {
            MODx.insertAtCursor(this.config.record.ddTargetEl,v);
        }
        this.hide();
        return true;
    }
    ,modps: []
    ,changeProp: function(k) {
        if (this.modps.indexOf(k) == -1) {
            this.modps.push(k);
        }
    }
});
Ext.reg('modx-window-insert-element',MODx.window.InsertElement);
