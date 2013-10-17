
MODx.SearchBar = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        renderTo: 'modx-manager-search'
        ,listClass: 'modx-manager-search-results'
        ,emptyText: 'Search or actions…'
        ,id: 'modx-uberbar'
        ,maxHeight: this.getViewPortSize()
        ,typeAhead: true
        //,autoSelect: false
        ,listAlign: [ 'tl-bl?', [0,0] ]
        ,triggerConfig: {tag: 'span', cls: 'x-form-trigger icon-large icon-search'}
        ,minChars: 1
        ,displayField: 'name'
        ,valueField: 'action'
        ,width: 174
        ,maxWidth: 300
        ,itemSelector: '.item'
        ,tpl: new Ext.XTemplate(
            '<tpl for=".">',
                '<tpl if="this.type != values.type">',
                '<tpl exec="this.type = values.type"></tpl>',
                    '<div class="section">',
                        '<h3>{type}</h3>',
                '</tpl>',
                '<p class="item"><a><tpl exec="values.icon = this.getClass(values)"><i class="icon-{icon}"></i></tpl>{name}<tpl if="description"><em> – {description}</em></tpl></a></p>',
                '<tpl if="this.isLastOfType(values)"></div></tpl>',
            '</tpl>', {
                getClass: function(record) {
                    switch (record.type) {
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

                ,isLastOfType: function(rec) {
                    var ub = Ext.getCmp('modx-uberbar')
                        ,store = ub.getStore()
                        ,total = store.getCount() - 1
                        ,currentType = rec.type
                        ,idx = store.find('action', rec.action);

                    if (total === idx) {
                        // Last record, last of its type
                        return true;
                    }
                    var nextResult = store.getAt(idx + 1);

                    return currentType != nextResult.get('type');
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
        new Ext.KeyMap(Ext.get(document)).addBinding({
            key: 191
            ,ctrl: false
            ,shift: true
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
