
MODx.SearchBar = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        renderTo: 'modx-manager-search'
        ,listClass: 'modx-manager-search-results'
        ,emptyText: 'Awesomesauce…'
        ,id: 'modx-awesomebar'
        ,maxHeight: this.getViewPortSize()
        ,typeAhead: true
        //,autoSelect: false
        ,triggerConfig: {tag: 'span', cls: 'x-form-trigger icon-large icon-magic'}
        ,minChars: 1
        ,displayField: 'name'
        ,valueField: 'action'
        ,width: 180
        ,maxWidth: 300
        ,itemSelector: '.section'
        ,tpl: new Ext.XTemplate(
            '<tpl for=".">',
            '<div class="section">',
            '<tpl if="this.type != values.type">',
            '<tpl exec="this.type = values.type"></tpl>',
                '<h3>{type}</h3>',
            '</tpl>',
                '<p><a><tpl exec="values.icon = this.getClass(values)"><i class="icon-{icon}"></i></tpl>{name}<tpl if="description"><em> – {description}</em></tpl></a></p>',
            '</div >',
            '</tpl>', {
                getClass: function(values) {
                    //console.log('in test!', values);
                    switch (values.type) {
                        case 'Resources':
                            return 'file-alt';
                        case 'Chunks':
                            return 'th';
                        case 'Templates':
                            return 'columns';
                        case 'Snippets':
                            return 'code';
                        case 'TVs':
                            return 'asterisk';
                        case 'Plugins':
                            return 'puzzle-piece';
                        case 'Users':
                            return 'user';
                        case 'Actions':
                            return 'mail-forward';
                    }
                }
            }
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

        ,onTypeAhead : function() {}
        ,onSelect: function(record) {
            MODx.loadPage('?a=' + record.data.action);
        }

        ,listeners: {
            beforequery: {
                fn: function() {
                    this.tpl.type = null;
                }
            }
            ,focus: {
                fn: this.focusBar
                ,scope: this
            }
            ,blur: {
                fn: this.blurBar
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

    ,focusBar: function() {
        this.selectText();
        this.animate();
    }
    ,blurBar: function() {
        this.animate(true);
    }
    ,animate: function(blur) {
        var to = blur ? this.width : this.maxWidth;
        this.wrap.setWidth(to, true);
        this.el.setWidth(to - this.getTriggerWidth(), true);
    }
    ,getViewPortSize: function() {
        var height = 300;
        if (window.innerHeight !== undefined) {
            height = window.innerHeight;
        }
        //console.log(height);

        return height - 70;
    }
});
Ext.reg('modx-searchbar', MODx.SearchBar);
