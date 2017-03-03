
MODx.SearchBar = function(config) {
    config = config || {};

    Ext.applyIf(config, {
        renderTo: 'modx-manager-search'
        ,listClass: 'modx-manager-search-results'
        ,emptyText: _('search')
        ,id: 'modx-uberbar'
        ,maxHeight: this.getViewPortSize()
        ,typeAhead: true
        // ,listAlign: [ 'tl-bl?', [0, 0] ] // this is default
        ,listAlign: [ 'tl-bl?', [-12, 0] ] // account for padding + border width of container (added by Ext JS)
        // ,triggerConfig: { // handled globally for Ext.form.ComboBox via override
        //     tag: 'span'
        //     ,cls: 'x-form-trigger icon icon-large icon-search'
        // }
        // ,shadow: false // handled globally for Ext.form.ComboBox via override
        // ,triggerAction: 'query'
        ,minChars: 1
        ,displayField: 'name'
        ,valueField: '_action'
        ,width: 259
        ,maxWidth: 437 // Increase to animate + grow when focused
        ,itemSelector: '.x-combo-list-item'
        ,tpl: new Ext.XTemplate(
            '<tpl for=".">',
            // Section wrapper
            '<div class="section">',
            // Display header only once
            '<tpl if="this.type != values.type">',
            '<tpl exec="this.type = values.type; values.label = this.getLabel(values)"></tpl>',
                '<h3>{label}</h3>',
            '</tpl>',
                // Real result, make it use the default styles for a combobox dropdown with x-combo-list-item
                '<p class="x-combo-list-item"><a href="?a={_action}"><tpl exec="values.icon = this.getClass(values)"><i class="icon icon-{icon}"></i></tpl>{name}<tpl if="description"><em> – {description}</em></tpl></a></p>',
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
                    if (values.icon) {
                        return values.icon;
                    }
                    switch (values.type) {
                        case 'resources':
                            return 'file';
                        case 'chunks':
                            return 'th-large';
                        case 'templates':
                            return 'columns';
                        case 'snippets':
                            return 'code';
                        case 'tvs':
                            return 'list-alt';
                        case 'plugins':
                            return 'cogs';
                        case 'users':
                            return 'user';
                        case 'actions':
                            return 'mail-forward';
                    }
                }
                /**
                 * Get the result type lexicon
                 *
                 * @param {Array} values
                 *
                 * @returns {String}
                 */
                ,getLabel: function(values) {
                    if (values.label) {
                        return values.label;
                    }
                    return _('search_resulttype_' + values.type);
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
            ,fields: ['name', '_action', 'description', 'type', 'icon', 'label']
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
            ,focus: this.focusBar
            ,blur: this.blurBar
            ,scope: this
        }
    });
    MODx.SearchBar.superclass.constructor.call(this, config);
    this.setKeyMap();
};
Ext.extend(MODx.SearchBar, Ext.form.ComboBox, {

    // Initialize the keyboard shortcuts to focus the bar (ctrl + alt + /) and hide it (esc)
    setKeyMap: function() {
        // This keymap is conflicting with typing certain characters, see #11974
        /*new Ext.KeyMap(document, {
            key: [191, 0]
            ,ctrl: true
            ,alt: true
            ,handler: function() {
                this.hideBar();
                this.toggle();
            }
            ,scope: this
            ,stopEvent: true
        });*/

        // Escape to hide SearchBar
        new Ext.KeyMap(document, {
            key: 27
            ,handler: function() {
                this.hideBar();
            }
            ,scope: this
            ,stopEvent: false
        });

        // Ext.get(document).on('keydown', function(vent) {
        //    console.log(vent.keyCode);
        // });
    }

    /**
     * Override to support opening results in new window/tab
     */
    ,initEvents : function(){
        Ext.form.ComboBox.superclass.initEvents.call(this);

        this.keyNav = new Ext.KeyNav(this.el, {
            "up" : function(e){
                this.inKeyMode = true;
                this.selectPrev();
            },

            "down" : function(e){
                if(!this.isExpanded()){
                    this.onTriggerClick();
                }else{
                    this.inKeyMode = true;
                    this.selectNext();
                }
            },

            "enter" : function(e){
                this.onSelect(e);
            },

            "esc" : function(e){
                this.collapse();
            },

            "tab" : function(e){
                if (this.forceSelection === true) {
                    this.collapse();
                } else {
                    this.onViewClick(false);
                }
                return true;
            },

            scope : this,

            doRelay : function(e, h, hname){
                if(hname == 'down' || this.scope.isExpanded()){

                    var relay = Ext.KeyNav.prototype.doRelay.apply(this, arguments);
                    if((((Ext.isIE9 && Ext.isStrict) || Ext.isIE10p) || !Ext.isIE) && Ext.EventManager.useKeydown){

                        this.scope.fireKey(e);
                    }
                    return relay;
                }
                return true;
            },

            forceKeyDown : true,
            defaultEventAction: 'stopEvent'
        });
        this.queryDelay = Math.max(this.queryDelay || 10,
                                   this.mode == 'local' ? 10 : 250);
        this.dqTask = new Ext.util.DelayedTask(this.initQuery, this);
        if(this.typeAhead){
            this.taTask = new Ext.util.DelayedTask(this.onTypeAhead, this);
        }
        if(!this.enableKeyEvents){
            this.mon(this.el, 'keyup', this.onKeyUp, this);
        }
    }
    // ,initEvents : function()
    // {
    //     Ext.form.ComboBox.superclass.initEvents.call(this);
    //
    //     this.keyNav = new Ext.KeyNav(
    //         this.el, {
    //
    //             "enter": function (e)
    //             {
    //                 console.log(e);
    //                 this.onSelect(e);
    //             }
    //         }
    //     );
    // }

    ,initList : function() {
        if(!this.list){
            var cls = 'x-combo-list',
                listParent = Ext.getDom(this.getListParent() || Ext.getBody());

            this.list = new Ext.Layer({
                parentEl: listParent,
                shadow: this.shadow,
                cls: [cls, this.listClass].join(' '),
                constrain:false,
                zindex: this.getZIndex(listParent)
            });

            var lw = this.listWidth || Math.max(this.wrap.getWidth(), this.minListWidth);
            this.list.setSize(lw, 0);
            this.list.swallowEvent('mousewheel');
            this.assetHeight = 0;
            if(this.syncFont !== false){
                this.list.setStyle('font-size', this.el.getStyle('font-size'));
            }
            if(this.title){
                this.header = this.list.createChild({cls:cls+'-hd', html: this.title});
                this.assetHeight += this.header.getHeight();
            }

            this.innerList = this.list.createChild({cls:cls+'-inner'});
            this.mon(this.innerList, 'mouseover', this.onViewOver, this);
            this.mon(this.innerList, 'mousemove', this.onViewMove, this);
            this.innerList.setWidth(lw - this.list.getFrameWidth('lr'));

            if(this.pageSize){
                this.footer = this.list.createChild({cls:cls+'-ft'});
                this.pageTb = new Ext.PagingToolbar({
                    store: this.store,
                    pageSize: this.pageSize,
                    renderTo:this.footer
                });
                this.assetHeight += this.footer.getHeight();
            }

            if(!this.tpl){

                this.tpl = '<tpl for="."><div class="'+cls+'-item">{' + this.displayField + '}</div></tpl>';

            }


            this.view = new Ext.DataView({
                applyTo: this.innerList,
                tpl: this.tpl,
                singleSelect: true,
                selectedClass: this.selectedClass,
                itemSelector: this.itemSelector || '.' + cls + '-item',
                emptyText: this.listEmptyText,
                deferEmptyText: false
            });

            // Original view listeners
            // this.mon(this.view, {
            //    containerclick : this.onViewClick,
            //    click : this.onViewClick,
            //    scope :this
            // });
            this.view.on('click', function(view, index, node, vent) {
                /**
                 * Force node selection to make sure it is available in onViewClick
                 *
                 * @see Ext.form.ComboBox#onViewClick
                 */
                view.select(node);
                if (!window.event) {
                    window.event = vent;
                }
                this.onViewClick();
            }, this);

            this.bindStore(this.store, true);

            if(this.resizable){
                this.resizer = new Ext.Resizable(this.list,  {
                    pinned:true, handles:'se'
                });
                this.mon(this.resizer, 'resize', function(r, w, h){
                    this.maxHeight = h-this.handleHeight-this.list.getFrameWidth('tb')-this.assetHeight;
                    this.listWidth = w;
                    this.innerList.setWidth(w - this.list.getFrameWidth('lr'));
                    this.restrictHeight();
                }, this);

                this[this.pageSize?'footer':'innerList'].setStyle('margin-bottom', this.handleHeight+'px');
            }
        }
    }

    // Nullify the "parent" function
    ,onTypeAhead : function() {}
    /**
     * Go to the selected record "action" page
     *
     * @param {Object} record
     * @param {Number} index
     */
    ,onSelect: function(event) {
        var e = event || window.event;

        e.stopPropagation();
        e.preventDefault();

        var index = this.view.getSelectedIndexes()[0],
            s = this.store,
            record = s.getAt(index);

        var target = '?a=' + record.data._action;

        if (e.ctrlKey || e.metaKey || e.shiftKey) {
            return window.open(target);
        }

        MODx.loadPage(target);
    }
    /**
     * Toggle the search drawer visibility
     *
     * @param {Boolean} hide Whether or not to force-hide MODx.SearchBar
     */
    ,toggle: function( hide ){
        var uberbar = Ext.get( this.container.id );
        if( uberbar.hasClass('visible') || hide ){
            this.blurBar();
			uberbar.removeClass('visible');
        } else {
			uberbar.addClass('visible');
            this.focusBar();
        }
    }
    ,hideBar: function() {
        this.toggle(true);
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

        return height - 70;
    }
});
Ext.reg('modx-searchbar', MODx.SearchBar);
