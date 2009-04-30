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
    var tt = MODx.load({
        xtype: 'checkbox-column'
        ,header: _('access')
        ,dataIndex: 'access'
        ,width: 40
        ,sortable: false
    });
	Ext.applyIf(config,{
        title: _('template_assignedtv_tab')
        ,id: 'modx-grid-template-tv'
        ,url: MODx.config.connectors_url+'element/template/tv.php'
		,fields: ['id','name','description','rank','access','menu']
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
        ,columns: [{
            header: _('name')
            ,dataIndex: 'name'
            ,width: 150
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        },{
            header: _('description')
            ,dataIndex: 'description'
            ,width: 350
            ,editor: { xtype: 'textfield' }
        },tt,{
            header: _('rank')
            ,dataIndex: 'rank'
            ,width: 100
            ,editor: { xtype: 'textfield' ,allowBlank: false }
        }]
	});
	MODx.grid.TemplateTV.superclass.constructor.call(this,config);
    this.on('afteredit',function(e) {
         Ext.getCmp('modx-panel-template').fireEvent('fieldChange');
    },this);
};
Ext.extend(MODx.grid.TemplateTV,MODx.grid.Grid);
Ext.reg('modx-grid-template-tv',MODx.grid.TemplateTV);