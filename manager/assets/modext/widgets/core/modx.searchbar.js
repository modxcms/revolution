
MODx.SearchBar = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        renderTo: 'modx-manager-search'
        ,listClass: 'modx-manager-search-results'
        ,emptyText: 'Awesomesauce…'
        ,id: 'modx-awesomebar'
        ,maxHeight: this.getViewPortSize()
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
                '<p><a onmousedown="MODx.loadPage(\'?a={action}\');"><tpl exec="values.icon = this.getClass(values)"><i class="icon-{icon}"></i></tpl>{name}<tpl if="description"><em> – {description}</em></tpl></a></p>',
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
        this.originalWidth = this.getWidth();
        this.animate();
    }
    ,blurBar: function() {
        this.animate(true);
    }
    ,animate: function(blur) {
        var container = Ext.get('modx-manager-search')
            ,to = blur ? this.originalWidth : 300
            ,me = this;
        container.animate({
            width: {
                to: to
                ,from: container.getWidth()
            }
        }, 0.25, function() {
            !blur ? me.setWidth(to).render() : '';
        });
        blur ? this.setWidth(to).render() : '';
    }
    // Some keyboard shortcuts when the bar is focused
    ,keyboardNav: function(elem, e) {
        //e.preventDefault();
        var store = this.getStore()
            ,results = store.getCount();
        switch (e.getKey()) {
            case e.ENTER:
                console.log('enter pressed, perform some kind of validation');
                this.onViewClick();
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
                this.cycleChoice();
                break;
            case e.UP:
                console.log('select the appropriate record');
                this.cycleChoice(true);
                break;
            case e.ESC:
                console.log('reset the combo');
                this.reset();
                this.blur();
                this.focus();
                break;
        }
    }
    // Cycle up/down in the results
    ,cycleChoice: function(prev) {
        if (prev === true) {
            this.selectPrev();
        } else {
            this.selectNext();
        }
        var record = this.getStore().getAt(this.selectedIndex);
        this.setValue(record.data[this.valueField]);
    }
    ,getViewPortSize: function() {
        var height = 300;
        if (window.innerHeight !== 'undefined') {
            height = window.innerHeight;
        }
        //console.log(height);

        return height - 70;
    }
});
Ext.reg('modx-searchbar', MODx.SearchBar);
