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
        // ,triggerConfig: { // handeled globally for Ext.form.ComboBox via override
        //     tag: 'span'
        //     ,cls: 'x-form-trigger icon icon-large icon-search'
        // }
        // ,shadow: false // handeled globall for Ext.form.ComboBox via override
        // ,triggerAction: 'query'
        ,minChars: 0
        ,displayField: 'name'
        ,valueField: '_action'
        ,width: 209 // make the uberbar border to the right of the searchfield be in line with the right tree edge
        ,maxWidth: 300
        ,itemSelector: '.x-combo-list-item a'
        ,tpl: new Ext.XTemplate(            
            '<tpl for=".">'
                ,'<tpl if="values.id == \'\'">'
                ,   '<div id="advancedSearch" class="section">'
                ,   '<h3>{uberbar_header}</h3>'
                ,   '<tpl if="header"><h4>{header}</h4></tpl>'
                ,   '<tpl if="options"><ul class="options">{options}</ul></tpl>'
                ,   '<tpl if="msg"><p class="msg">{msg}</p></tpl>'
                ,   '</div>'
                ,'</tpl>'
                ,'<tpl if="values.id != \'\'">'
                // Section wrapper
                ,'   <div class="section">'
                // Display header only once
                ,       '<tpl if="this.type != values.type">'
                ,           '<tpl exec="this.type = values.type; values.label = this.getLabel(values.type)"></tpl>'
                ,           '<h3><i class="icon icon-{icon}"></i> {label}</h3>'
                ,       '</tpl>'
                        // Real result, make it use the default styles for a combobox dropdown with x-combo-list-item
                ,       '<p class="x-combo-list-item">' // x-combo-list-item
                ,           '<span>#{current}</span>'
                ,           '<a class="x-btn x-btn-small x-btn-icon-small-left primary-button x-btn-noicon" href="?a={_action}">'
                ,               'Edit'
                ,           '</a>'
                ,           '<strong> - {name} ({id}) </strong>'
                ,           '<em>{description}</em>'
                ,           '<tpl if="content">'
                ,               '<div class="content">{content}</div>'
                ,           '</tpl>'
                ,       '</p>'                
                ,   '</div>'
                ,'</tpl>'
            ,'</tpl>'
            ,{
                /**
                 * Get the result type lexicon
                 *
                 * @param {string} type
                 * @returns {string}
                 */
                getLabel: function(type) {
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
            ,fields: [
                 'uberbar_header'
                ,'msg'
                ,'options'
                ,'header'
                
                ,'id'
                ,'name'
                ,'description'
                ,'content'
                ,'icon'
                
                ,'_action'
                ,'type'
                
                ,'current'
                
                ,'start'
                ,'end'
                ,'totalResults'

            ]
            ,listeners: {
                beforeload: function(store, options) {
                    if (options.params._action) {
                        // Prevent weird query on first combo box blur
                        return false;
                    }
                }
                ,load: {
                    fn: function(){
                        //console.log('event:load');
                    }
                }                  
            }
        })

        ,listeners: {
            beforequery: {
                fn: function() {
                    //console.log('event:beforequery');
                    this.tpl.type = null;
                }
            }
            ,focus: {
                fn: function(){
                    //console.log('event:focus');
                    this.focusBar;
                }
            }
            ,blur: {
                fn: function(){
                    //console.log('event:blur');
                    this.blurBar
                }
            }       
            ,scope: this
        }
        
    });
        
    MODx.SearchBar.superclass.constructor.call(this, config);
    //this.setKeyMap();    
    
};


Ext.extend(MODx.SearchBar, Ext.form.ComboBox, {

    // Initialize the keyboard shortcuts to focus the bar (ctrl + alt + /)
    setKeyMap: function() {
        new Ext.KeyMap(document, {
            //key: 191
            //key: 111
            key: [191, 0]
            ,ctrl: true
            //,shift: false
            ,alt: true
            ,handler: function(code, vent) {
                this.focus();
            }
            ,scope: this
            ,stopEvent: true
        });

        // Ext.get(document).on('keydown', function(vent) {
        //    console.log(vent.keyCode);
        // });
    }

    /**
     * Override to support opening results in new window/tab
     */
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
            
            this.view.getEl().on('click', function(e, t) {
                //e.stopEvent();
                dataValue = t.getAttribute('data-value');
                if (dataValue) {
                    console.log('Data value: ' + dataValue);
                    
                    Ext.get(t).toggleClass('active');
                    
                    if (Ext.get(t).hasClass('active')) {
                        
                        uberGet = Ext.get('modx-uberbar');
                        uberGetCmp = Ext.getCmp('modx-uberbar');
                        
                        oldValue = uberGet.getValue();
                        
                        console.log('Current value: ' + oldValue);
                        
                        newValue = oldValue + dataValue;
                        
                        uberGetCmp.setValue(newValue).focus(false, 200);
                        
                        // trigger select event to load new menuitems
                        //uberGetCmp.on('myownevent', function () { alert('my Event fired')});
                        //uberGetCmp.fireEvent('myEvent');
                        
                        //uberGetCmp.fireEvent('keydown');
                        //uberGetCmp.fireEvent('keypress');
                        //uberGetCmp.fireEvent('change', newValue, oldValue);
                        
                        uberGetCmp.fireEvent('change', uberGetCmp);
                        
                    }
                    else {
                        
                    }
                }
                
            }, null, {delegate: 'a'});

            // Original view listeners
            // this.mon(this.view, {
            //    containerclick : this.onViewClick,
            //    click : this.onViewClick,
            //    scope :this
            // });
            
            this.view.on('click', function(view, index, node, vent) {
                //
                // Force node selection to make sure it is available in onViewClick
                //
                // @see Ext.form.ComboBox#onViewClick
                //
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
    ,onSelect: function(record, index) {
        var e = window.event;
        e.stopPropagation();
        e.preventDefault();

        var target = '?a=' + record.data._action;

        if (e.ctrlKey || e.metaKey || e.shiftKey) {
            return window.open(target);
        }

        MODx.loadPage(target);
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
        var height = 400;
        if (window.innerHeight !== undefined) {
            height = window.innerHeight;
        }
        //console.log(height);

        return height - 70;
    }
});

Ext.reg('modx-searchbar', MODx.SearchBar);