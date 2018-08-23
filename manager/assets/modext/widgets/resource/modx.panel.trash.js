MODx.panel.Trash = function (config) {
    config = config || {};

    var pageLayout = MODx.getPageStructure([{
            layout: 'form',
            title: _('trash.tab_title'),
            items: [{
                html: '<p>' + _('trash.intro_msg') + '</p>',
                xtype: 'modx-description'
            }, {
                xtype: 'modx-grid-trash',
                id: 'modx-trash-resources',
                cls: 'main-wrapper',
                preventRender: true
            }]
        }], {
            stateful: true,
            stateId: 'modx-trash-tabpanel',
            stateEvents: ['tabchange'],
            getState: function () {
                return {activeTab: this.items.indexOf(this.getActiveTab())};
            }
        }
    );

    Ext.applyIf(config, {
        id: 'modx-panel-trash',
        cls: 'container',
        bodyStyle: '',
        defaults: {collapsible: false, autoHeight: true},
        items: [{
            html: _('trash.page_title'),
            id: 'modx-trash-header',
            xtype: 'modx-header'
        }, pageLayout]
    });

    MODx.panel.Trash.superclass.constructor.call(this, config);

    this.addEvents('emptyTrash');
};
Ext.extend(MODx.panel.Trash, MODx.FormPanel);
Ext.reg('modx-panel-trash', MODx.panel.Trash);

