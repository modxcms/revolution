
MODx.SearchBar = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        renderTo: 'modx-manager-search'
        ,listClass: 'modx-manager-search-results'
        ,emptyText: _('search_desc')
        ,id: 'modx-uberbar'
        ,maxHeight: this.getViewPortSize()
        ,typeAhead: true
        ,listAlign: [ 'tl-bl?', [0,0] ]
        ,triggerConfig: {
            tag: 'span'
            ,cls: 'x-form-trigger icon icon-large icon-search'
        }
        //,triggerAction: 'query'
        ,minChars: 1
        ,displayField: 'name'
        ,valueField: '_action'
        ,width: 174
        ,maxWidth: 300
        ,itemSelector: '.item'
        ,tpl: new Ext.XTemplate(
            '<tpl for=".">',
            '<div class="section">',
            '<tpl if="this.type != values.type">',
            '<tpl exec="this.type = values.type; values.label = this.getLabel(values.type)"></tpl>',
                '<h3>{label}</h3>',
            '</tpl>',
                '<p class="item"><a><tpl exec="values.icon = this.getClass(values)"><i class="icon icon-{icon}"></i></tpl>{name}<tpl if="description"><em> – {description}</em></tpl></a></p>',
            '</div >',
            '</tpl>'
            ,{
                /**
                 * Get the appropriate CSS class based on the result type
                 *
                 * @param {Array} values
                 * @returns {string}
                 */
                getClass: function(values) {
                    switch (values.type) {
                        case 'resources':
                            return 'file-o';
                        case 'chunks':
                            return 'th';
                        case 'templates':
                            return 'columns';
                        case 'snippets':
                            return 'code';
                        case 'tvs':
                            return 'asterisk';
                        case 'plugins':
                            return 'puzzle-piece';
                        case 'users':
                            return 'user';
                        case 'actions':
                            return 'mail-forward';
                    }
                }
                /**
                 * Get the result type lexicon
                 *
                 * @param {string} type
                 * @returns {string}
                 */
                ,getLabel: function(type) {
                    return _('search_resulttype_' + type);
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
            ,fields: ['name', '_action', 'description', 'type']
            ,listeners: {
                beforeload: function(store, options) {
                    if (options.params._action) {
                        // Prevent weird query on first combo box blur
                        return false;
                    }
                }
            }
        })

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
        new Ext.KeyMap(document, {
            //key: 191
            //key: 111
            key: [191,0]
            ,ctrl: true
//            ,shift: false
            ,alt: true
            ,handler: function(code, vent) {
                this.focus();
            }
            ,scope: this
            ,stopEvent: true
        });

//        Ext.get(document).on('keydown', function(vent) {
//            console.log(vent.keyCode);
//        });
    }

    // Nullify the "parent" function
    ,onTypeAhead : function() {}
    /**
     * Go to the selected record "action" page
     *
     * @param {Object} record
     */
    ,onSelect: function(record) {
        MODx.loadPage('?a=' + record.data._action);
    }

    ,focusBar: function() {
        this.selectText();
        this.animate();
    }
    ,blurBar: function() {
        this.animate(true);
    }
    /**
     * Animate the input "grow"
     *
     * @param {Boolean} blur Whether or not the input loses focus (to "minimize" the input width)
     */
    ,animate: function(blur) {
        var to = blur ? this.width : this.maxWidth;
        this.wrap.setWidth(to, true);
        this.el.setWidth(to - this.getTriggerWidth(), true);
    }
    /**
     * Compute the available max height so results could be scrollable if required
     *
     * @returns {number}
     */
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
