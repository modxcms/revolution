
MODx.SearchBar = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        renderTo: 'nav-search'
        ,listClass: 'modx-manager-search-results'
        ,emptyText: 'Awesomesauce…'
        ,id: 'modx-awesomebar'
        ,typeAhead: true
        ,autoSelect: false
        ,minChars: 1
        ,displayField: 'name'
        ,valueField: 'action'
        ,tpl: new Ext.XTemplate(
            '<tpl for=".">',
            '<div class="section">',
            '<tpl if="this.type != values.type">',
            '<tpl exec="this.type = values.type"></tpl>',
                '<h3>{type}</h3>',
            '</tpl>',
                '<p><a onmousedown="MODx.loadPage(\'?a={action}\');">{name}<tpl if="description"> - <i>{description}</i></tpl></a></p>',
            '</div >',
            '</tpl>'
        )
        ,store: new Ext.data.JsonStore({
            url: MODx.config.connector_url
            ,baseParams: {
                action: 'search/search'
            }
            ,root: 'results'
            ,totalProperty: 'total'
            ,fields: ['name', 'action', 'description', 'type']
        })
        //,width: 170
        ,listWidth: 270
        ,height: 41
        ,boxMinHeight: 41

        ,onTypeAhead : function() {}

        ,listeners: {
            beforequery: {
                fn: function() {
                    if (this.tpl.type) this.tpl.type = null;
                }
            }
        }
    });
    MODx.SearchBar.superclass.constructor.call(this, config);
    this.setKeyMap();
};
Ext.extend(MODx.SearchBar, Ext.form.ComboBox, {
    setKeyMap: function() {
        var nop = ['INPUT', 'TEXTAREA'];
        Ext.EventManager.on(Ext.get(document), 'keyup', function(vent) {
            if (vent.keyCode === 85 && nop.indexOf(vent.target.nodeName) == -1) {
                this.focus();
            }
        }, this);

        new Ext.KeyMap(Ext.get(document)).addBinding({
            key: 'u'
            ,ctrl: false
            ,shift: false
            ,alt: true
            ,handler: function(code, vent) {
                this.focus();
            }
            ,scope: this
            ,stopEvent: true
        });
    }
});
Ext.reg('modx-searchbar', MODx.SearchBar);
