/**
 * Loads a grid of TVs assigned to the Template.
 * 
 * @class MODx.grid.TemplateTV
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-template-tv
 */
MODx.grid.TemplateTV = function(config) {
    config = config || {};
    var tt = new Ext.ux.grid.CheckColumn({
        header: _('access')
        ,dataIndex: 'access'
        ,width: 70
        ,sortable: false
    });
    Ext.applyIf(config,{
        title: _('template_assignedtv_tab')
        ,id: 'modx-grid-template-tv'
        ,url: MODx.config.connectors_url+'element/template/tv.php'
        ,fields: ['id','name','description','tv_rank','access']
        ,baseParams: {
            action: 'getList'
            ,template: config.template
        }
        ,saveParams: {
            template: config.template
        }
        ,width: 800
        ,paging: true
        ,plugins: tt
        ,remoteSort: true
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 150
            ,editor: { xtype: 'textfield' ,allowBlank: false }
            ,sortable: true
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 350
            ,editor: { xtype: 'textfield' }
            ,sortable: false
        },tt,{
            header: _('rank')
            ,dataIndex: 'tv_rank'
            ,width: 100
            ,editor: { xtype: 'textfield' ,allowBlank: false }
            ,sortable: true
        }]
    });
    MODx.grid.TemplateTV.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.TemplateTV,MODx.grid.Grid);
Ext.reg('modx-grid-template-tv',MODx.grid.TemplateTV);