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
    var tt = new Ext.ux.grid.CheckColumn({
        header: _('access')
        ,dataIndex: 'access'
        ,width: 50
        ,sortable: true
    });
    Ext.applyIf(config,{
        id: 'modx-grid-tv-template'
        ,url: MODx.config.connectors_url+'element/tv/template.php'
        ,fields: ['id','templatename','description','access','menu']
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
        ,remoteSort: true
        ,columns: [{
            header: _('name')
            ,dataIndex: 'templatename'
            ,width: 150
            ,sortable: true
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 300
        },tt]
    });
    MODx.grid.TemplateVarTemplate.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.TemplateVarTemplate,MODx.grid.Grid);
Ext.reg('modx-grid-tv-template',MODx.grid.TemplateVarTemplate);
