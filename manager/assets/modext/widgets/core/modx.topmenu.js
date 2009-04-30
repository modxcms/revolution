/**
 * Generates the Top Menubar in Ext
 * 
 * @class MODx.toolbar.TopMenu
 * @extends Ext.Toolbar
 * @constructor
 * @param {Object} config An object of configuration properties
 * @xtype modx-topmenu
 */
MODx.toolbar.TopMenu = function(config) {
	config = config || {};
    Ext.applyIf(config,{
        id: 'modx-topmenu'
        ,renderTo: 'modx_tm'
        ,cls: 'modx-topmenu'
    });
	MODx.toolbar.TopMenu.superclass.constructor.call(this,config);
	this.config = config;
	this.init();
};
Ext.extend(MODx.toolbar.TopMenu,Ext.Toolbar,{
	menus: {}
	
	/**
	 * Initializes the top menu, grabbing it from the JSON-based connector.
	 */
	,init: function() {
		MODx.Ajax.request({
			url: MODx.config.connectors_url+'system/menu.php'
			,params: {
				action: 'getMenu'
			}
			,listeners: {
				'success': {fn:function(r) { 
				    this._loadMenus(r.object);
			    },scope:this}
			}
		});
	}
	
	/**
	 * Loads the menus from JSON data.
	 * @param {Object} data JSON object menus.
	 */
	,_loadMenus: function(data) {
		for (var i=0; i<data.length; i++) {
			var mi = data[i];
            var t = _(mi.text);
            if (t === undefined || t === '') { t = mi.text; }
			this.addMenu({
				id: 'mi_'+mi.id
				,icon: MODx.config.template_url+mi.icon
				,text: t
			});
			
			if (mi.children.length > 0) {
				for (var j=0; j<mi.children.length; j++) {
					var msi = mi.children[j];
                    var msio;
					if (msi.text == '-') {
						msio = '-';
					} else {
                        var tx = _(msi.text);
                        if (tx === undefined || tx === '') {
                            tx = msi.text;
                        }
						msio = { text: tx };
						if (msi.icon !== '') {
                            msio.icon = MODx.config.template_url+msi.icon;
                        }
						if (msi.handler !== '') {
							msio.handler = new Function('itm','e',''+msi.handler+'; return false;');
							msio.href = 'javascript:;';
						} else {
							msio.action = msi.action+msi.params;
						}
					}
					this.addMenuItem('mi_'+mi.id,msio);
				}
			}
		}
		
		this.addMenu('->',MODx.config.version);
	}
	
	/**
	 * Default click handler.
	 * @param {Ext.menu.Item} item The menu item clicked. 
	 * @param {Ext.EventObject} e
	 */
	,handleClick: function(itm,e) {
		e.preventDefault();
        e.stopPropagation();
		Ext.get('modx_content').dom.src = '?a='+itm.action;
	}

	/**
	 * Add a menu to the Top Bar.
	 */
	,addMenu: function() {
		var a = arguments, l = a.length;
        for(var i = 0; i < l; i++) {
			var options = a[i];
			
			if (typeof(options) == 'string' && options != '-' && options != '->' && options != '<-') {
				options = { text: options };
			} else {
				this.menus[options.id] = new Ext.menu.Menu({
					id: options.id
					,items: []
				});
				Ext.apply(options,{
					cls: 'x-btn-text-icon bmenu'
					,menu: this.menus[options.id]
				});
			}
			
			this.add(options);
		}
	}
	
	/**
	 * Add a menu item to a top bar menu.
	 * @param {String} menu_id The menu id to load into. 
	 */
	,addMenuItem: function(menu_id) {
		var a = arguments, l = a.length;
        for(var i = 1; i < l; i++) {
			var options = a[i];
			var mi;
			if (options == '-') {
				mi = '-';
			} else {
				Ext.applyIf(options,{
					href: 'index.php?a='+(options.action || 3)
					,scope: this
					,handler: this.handleClick
				});
				mi = new Ext.menu.Item(options);
			}
			this.menus[menu_id].add(mi);
		}
	}
});
Ext.reg('modx-topmenu',MODx.toolbar.TopMenu);