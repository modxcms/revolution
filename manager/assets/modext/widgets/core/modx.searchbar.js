
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
        ,selected: 0

        ,onTypeAhead : function() {}

        ,listeners: {
            beforequery: {
                fn: function() {
                    if (this.tpl.type) this.tpl.type = null;
                }
            }
            ,specialkey: {
                fn: this.keyboardNav
                ,scope: this
            }
            // Select existing text if any
            ,focus: {
                fn: this.selectText
                ,scope: this
            }
        }
    });
    MODx.SearchBar.superclass.constructor.call(this, config);
    this.setKeyMap();
};
Ext.extend(MODx.SearchBar, Ext.form.ComboBox, {
    // Initialize the keyboard shortcuts to focus the bar
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

    // Some keyboard shortcuts when the bar is focused
    ,keyboardNav: function(elem, e) {
        //e.preventDefault();
        var store = this.getStore()
            ,results = store.getCount();
        switch (e.getKey()) {
            case e.ENTER:
                console.log('enter pressed, perform some kind of validation');
                break;
            case e.TAB:
                e.preventDefault();
                if (results === 1) {
                    var record = store.getAt(0);
                    this.setValue(record.data[this.valueField]);
                } else if (results > 1) {
                    // select the appropriate element
                    this.cycleChoice(e.shiftKey);
                }
                break;
            case e.DOWN:
                console.log('select the appropriate record');
                break;
            case e.RIGHT:
                console.log('select the appropriate record');
                break;
            case e.ESC:
                console.log('reset the combo');
                this.reset();
                this.blur();
                this.focus();
                break;
        }
    }

    ,cycleChoice: function(shift) {
        if (shift === true) {
            this.selectPrev();
        } else {
            this.selectNext();
        }
        var record = this.getStore().getAt(this.selectedIndex);
        this.setValue(record.data[this.valueField]);
    }
});
Ext.reg('modx-searchbar', MODx.SearchBar);
