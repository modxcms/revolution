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
    config.filemanager_url = MODx.config.filemanager_url;
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
        },{
            xtype: 'modx-combo-browser'
            ,browserEl: 'tvbrowser'+config.tv
            ,name: 'tvbrowser'+config.tv
            ,id: 'tvbrowser'+config.tv
            ,triggerClass: 'x-form-image-trigger'
            ,value: config.relativeValue
            // ,hideFiles: true
            ,source: config.source || 1
            ,allowedFileTypes: config.allowedFileTypes || ''
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
Ext.extend(MODx.panel.ImageTV,MODx.Panel);
Ext.reg('modx-panel-tv-image',MODx.panel.ImageTV);

MODx.panel.FileTV = function(config) {
    config = config || {};
    config.filemanager_url = MODx.config.filemanager_url;
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
        },{
            xtype: 'modx-combo-browser'
            ,browserEl: 'tvbrowser'+config.tv
            ,name: 'tvbrowser'+config.tv
            ,id: 'tvbrowser'+config.tv
            ,value: config.relativeValue
            // ,hideFiles: true
            ,source: config.source || 1
            ,allowedFileTypes: config.allowedFileTypes || ''
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
Ext.extend(MODx.panel.FileTV,MODx.Panel);
Ext.reg('modx-panel-tv-file',MODx.panel.FileTV);

MODx.checkTV = function(id) {
    var cb = Ext.get('tv'+id);
    Ext.get('tvh'+id).dom.value = cb.dom.checked ? cb.dom.value : '';     
};