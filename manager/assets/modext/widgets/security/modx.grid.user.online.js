/**
 * Loads a grid of all users who are online.
 *
 * @deprecated It is not used anymore because we are trying not to use ExtJS for Dashboard Widgets
 *
 * @class MODx.grid.WhoIsOnline
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-user-online
 */
MODx.grid.WhoIsOnline = function(config) {
  config = config || {};
  Ext.applyIf(config,{
    title: _('onlineusers_title')
    ,url: MODx.config.connector_url
    ,baseParams: {
      action: 'Security/User/GetOnline'
    }
    ,autosave: false
    ,save_action: ''
    ,pageSize: 10
    ,fields: ['user','username','occurred','action']
    ,showActionsColumn: false
    ,columns: [{
      header: _('onlineusers_userid')
      ,dataIndex: 'user'
      ,width: 80
      ,fixed: true
    },{
      header: _('onlineusers_user')
      ,dataIndex: 'username'
    },{
      header: _('onlineusers_lasthit')
      ,dataIndex: 'occurred'
    },{
      header: _('onlineusers_action')
      ,dataIndex: 'action'
    }]
    ,paging: true
    ,listeners: {
      afterrender: this.onAfterRender
      ,scope: this
    }
  });
  MODx.grid.WhoIsOnline.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.WhoIsOnline,MODx.grid.Grid,{
  // Workaround to resize the grid when in a dashboard widget
  onAfterRender: function() {
    var cnt = Ext.getCmp('modx-content')
    // Dashboard widget "parent" (renderTo)
    ,parent = Ext.get('modx-grid-user-online');

    if (cnt && parent) {
      cnt.on('afterlayout', function(elem, layout) {
        var width = parent.getWidth();
        // Only resize when more than 500px (else let's use/enable the horizontal scrolling)
        if (width > 500) {
          this.setWidth(width);
        }
      }, this);
    }
  }
});
Ext.reg('modx-grid-user-online',MODx.grid.WhoIsOnline);
