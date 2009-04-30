/**
 * Loads a grid of TVs assigned to the Template.
 * 
 * @class MODx.grid.TemplateVarTemplate
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-tv-template
 */
MODx.grid.TemplateVarTemplate = function(config) {
    config = config || {};
    var tt = MODx.load({
        xtype: 'checkbox-column'
        ,header: _('access')
        ,dataIndex: 'access'
        ,width: 40
        ,sortable: false
    });
    Ext.applyIf(config,{
        id: 'modx-grid-tv-template'
        ,url: MODx.config.connectors_url+'element/tv/template.php'
        ,fields: ['id','templatename','description','rank','access','menu']
        ,baseParams: {
            action: 'getList'
            ,tv: config.tv
        }
        ,saveParams: {
            tv: config.tv
        }
        ,width: 800
        ,paging: true
        ,plugins: tt
        ,columns: [{
            header: _('name')
            ,dataIndex: 'templatename'
            ,width: 150
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 350
        },tt,{
            header: _('rank')
            ,dataIndex: 'rank'
            ,width: 100
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        }]
    });
    MODx.grid.TemplateVarTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.TemplateVarTemplate,MODx.grid.Grid);
Ext.reg('modx-grid-tv-template',MODx.grid.TemplateVarTemplate);