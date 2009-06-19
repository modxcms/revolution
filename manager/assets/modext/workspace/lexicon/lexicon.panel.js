/**
 * Loads the panel for managing lexicons.
 * 
 * @class MODx.panel.Lexicon
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-lexicon
 */
MODx.panel.Lexicon = function(config) {
    config = config || {};
    
    Ext.applyIf(config,{
        id: 'modx-panel-system-settings'
        ,bodyStyle: ''
        ,defaults: { collapsible: false ,autoHeight: true }
        ,items: [{
            html: '<h2>'+_('lexicon_management')+'</h2>'
            ,border: false
            ,id: 'modx-lexicon-header'
            ,cls: 'modx-page-header'
        },{
            xtype: 'portal'
            ,id: 'modx-lexicon-portal'
            ,items: [{
                columnWidth: .98
                ,id: 'modx-lexicon-grid-col-top'
                ,items: [{
                    title: _('lexicon_management')
                    ,layout: 'form'
                    ,bodyStyle: 'padding: 1.5em;'
                    ,items: [{
                        html: '<p>'+_('lexicon_management_desc')+'</p>'
                        ,border: false
                    },{
                        xtype: 'modx-grid-lexicon'
                        ,title: ''
                        ,preventRender: true
                    }]
                }]
            },{
                columnWidth: .47
                ,id: 'modx-lexicon-grid-col-left'
                ,items: [{
                    title: _('lexicon_topics')
                    ,layout: 'form'
                    ,bodyStyle: 'padding: 1.5em;'
                    ,items: [{
                        html: '<p>'+_('lexicon_topics_desc')+'</p>'
                        ,border: false
                    },{
                        xtype: 'modx-grid-lexicon-topic'
                        ,title: ''
                        ,preventRender: true
                        ,listeners: {
                            'afterRemoveRow':{fn:function() {
                                Ext.getCmp('modx-lexicon-filter-topic').store.reload();
                            },scope:this}
                        }
                    }]
                }]
            },{
                columnWidth: .47
                ,id: 'modx-lexicon-grid-col-right'
                ,items: [{
                    title: _('languages')
                    ,layout: 'form'
                    ,bodyStyle: 'padding: 1.5em;'
                    ,items: [{
                        html: '<p>'+_('languages_desc')+'</p>'
                        ,border: false
                    },{
                        xtype: 'modx-grid-language'
                        ,title: ''
                        ,preventRender: true
                        ,listeners: {
                            'afterRemoveRow':{fn:function() {
                                Ext.getCmp('modx-lexicon-filter-language').store.reload();
                            },scope:this}
                        }
                    }]
                }]
            }]
        }]
    });
    MODx.panel.Lexicon.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Lexicon,MODx.FormPanel);
Ext.reg('modx-panel-lexicon',MODx.panel.Lexicon);
