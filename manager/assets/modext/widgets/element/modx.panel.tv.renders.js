/**
 * Renders an input for an image TV
 *
 * @class MODx.panel.ImageTV
 * @extends MODx.Panel
 * @param {Object} config An object of configuration properties
 * @xtype panel-tv-image
 */
MODx.panel.ImageTV = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        layout: 'form'
        ,autoHeight: true
        ,border: false
        ,hideLabels: true
        ,defaults: {
            autoHeight: true
            ,border: false
        }
        ,items: [{
            xtype: 'hidden'
            ,name: 'tv'+config.tv
            ,id: 'tv'+config.tv
            ,value: config.value
            ,allowBlank: config.allowBlank
        },{
            xtype: 'modx-combo-browser'
            ,browserEl: 'tvbrowser'+config.tv
            ,name: 'tvbrowser'+config.tv
            ,id: 'tvbrowser'+config.tv
            ,caption: 'tv'+config.tv+'-caption'
            ,triggerClass: 'x-form-image-trigger'
            ,value: config.relativeValue
            ,source: config.source || 1
            ,allowBlank: config.allowBlank
            ,allowedFileTypes: config.allowedFileTypes || ''
            ,msgTarget: config.msgTarget
            ,openTo: config.openTo || ''
            ,hideSourceCombo: true
            ,listeners: {
                'select': {fn:function(data) {
                    Ext.getCmp('tv'+this.config.tv).setValue(data.relativeUrl);
                    Ext.getCmp('tvbrowser'+this.config.tv).setValue(data.relativeUrl);
                    this.fireEvent('select',data);
                },scope:this}
                ,'change': {fn:function(cb,nv) {
                    Ext.getCmp('tv'+this.config.tv).setValue(nv);
                    this.fireEvent('select',{
                        relativeUrl: nv
                        ,url: nv
                    });
                },scope:this}
            }
        }]
    });
    MODx.panel.ImageTV.superclass.constructor.call(this,config);
    this.addEvents({select: true});
};
Ext.extend(MODx.panel.ImageTV,MODx.Panel, {
    isDirty: function() {
        if (this.disabled || !this.rendered) {
            return false;
        }

        var inputField = this.find('name', 'tv' + this.config.tv);
        if (!inputField || !inputField[0]) return false;

        return inputField[0].isDirty();
    }
});
Ext.reg('modx-panel-tv-image',MODx.panel.ImageTV);

/**
 * Renders an input for an file TV
 *
 * @class MODx.panel.FileTV
 * @extends MODx.Panel
 * @param {Object} config An object of configuration properties
 * @xtype panel-tv-file
 */
MODx.panel.FileTV = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        layout: 'form'
        ,autoHeight: true
        ,border: false
        ,hideLabels: true
        ,defaults: {
            autoHeight: true
            ,border: false
        }
        ,items: [{
            xtype: 'hidden'
            ,name: 'tv'+config.tv
            ,id: 'tv'+config.tv
            ,value: config.value
            ,allowBlank: config.allowBlank
        },{
            xtype: 'modx-combo-browser'
            ,browserEl: 'tvbrowser'+config.tv
            ,name: 'tvbrowser'+config.tv
            ,id: 'tvbrowser'+config.tv
            ,caption: 'tv'+config.tv+'-caption'
            ,value: config.relativeValue
            ,source: config.source || 1
            ,allowBlank: config.allowBlank
            ,allowedFileTypes: config.allowedFileTypes || ''
            ,msgTarget: config.msgTarget
            ,wctx: config.wctx || 'web'
            ,openTo: config.openTo || ''
            ,hideSourceCombo: true
            ,listeners: {
                'select': {fn:function(data) {
                    Ext.getCmp('tv'+this.config.tv).setValue(data.relativeUrl);
                    Ext.getCmp('tvbrowser'+this.config.tv).setValue(data.relativeUrl);
                    this.fireEvent('select',data);
                },scope:this}
                ,'change': {fn:function(cb,nv) {
                    Ext.getCmp('tv'+this.config.tv).setValue(nv);
                    this.fireEvent('select',{
                        relativeUrl: nv
                        ,url: nv
                    });
                },scope:this}
            }
        }]
    });
    MODx.panel.FileTV.superclass.constructor.call(this,config);
    this.addEvents({select: true});
};
Ext.extend(MODx.panel.FileTV,MODx.Panel, {
    isDirty: function() {
        if (this.disabled || !this.rendered) {
            return false;
        }

        var inputField = this.find('name', 'tv' + this.config.tv);
        if (!inputField || !inputField[0]) return false;

        return inputField[0].isDirty();
    }
});
Ext.reg('modx-panel-tv-file',MODx.panel.FileTV);

MODx.checkTV = function(id) {
    var cb = Ext.get('tv'+id);
    Ext.get('tvh'+id).dom.value = cb.dom.checked ? cb.dom.value : '';
};
