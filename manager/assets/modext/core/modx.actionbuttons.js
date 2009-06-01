/**
 * Generates the Action Buttons in Ext
 * 
 * @class MODx.toolbar.ActionButtons
 * @extends Ext.Toolbar
 * @constructor
 * @param {Object} config An object of config options.
 * @xtype modx-actionbuttons
 */
MODx.toolbar.ActionButtons = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        actions: { 'close': MODx.action.welcome }
        ,formpanel: false
        ,id: 'modx-action-buttons'
        ,loadStay: false
        ,params: {}
        ,items: []
        ,renderTo: 'modAB'
    });
    if (config.formpanel) {
        this.setupDirtyButtons(config.formpanel);
    }
    if (config.loadStay === true) {
        config.items.push('-',this.getStayMenu());
    }
	MODx.toolbar.ActionButtons.superclass.constructor.call(this,config);
	this.config = config;
};
Ext.extend(MODx.toolbar.ActionButtons,Ext.Toolbar,{
	/**
	 * @var {String} The ID of the toolbar.
	 */
	id: ''
	/**
	 * @var {Array} The array of buttons added.
	 */
	,buttons: []
	/**
	 * @var {Object} The options for the toolbar. The default close action goes to the home page.
	 */
	,options: { a_close: 'welcome' }
	/**
	 * @var {string} The stay action, default is to continue editing.
	 */
	,stay: 'stay' 
	
	,checkDirtyBtns: []
    
    ,add: function() {
        var a = arguments, l = a.length;
        for(var i = 0; i < l; i++) {
            var el = a[i];
            var ex = ['-','->','<-','',' '];
            if (ex.indexOf(el) != -1 || (el.xtype && el.xtype == 'switch')) {
                MODx.toolbar.ActionButtons.superclass.add.call(this,el);
                continue;
            }
            Ext.applyIf(el,{
                xtype: 'button'
                ,cls: (el.icon ? 'x-btn-icon bmenu' : 'x-btn-text bmenu')
                ,scope: this
            });
            if (el.button) {
                MODx.toolbar.ActionButtons.superclass.add.call(this,el);
            }
            
            
            if (el.handler === null && el.menu === null) {
                el.handler = this.checkConfirm;
            } else if (el.confirm && el.handler) {
                el.handler = function() {
                    Ext.Msg.confirm(_('warning'),el.confirm,function(e) {
                      if (e === 'yes') { Ext.callback(el.handler,this); }
                    },el.scope || this);
                };
            } else if (el.handler) {} else { el.handler = this.handleClick; }
                                        
            /* create the button */
            var b = new Ext.Toolbar.Button(el);        
            
            /* if checkDirty, disable until field change */
            if (el.checkDirty) {
                b.setDisabled(true);
                this.checkDirtyBtns.push(b);
            }
            
            /* if javascript is specified, run it when button is click, before this.checkConfirm is run */
            if (el.javascript) {
                b.addListener('click',this.evalJS,this);
            }
            
            /* add button to toolbar */
            MODx.toolbar.ActionButtons.superclass.add.call(this,b);
            
            if (el.keys) {
                var map = new Ext.KeyMap(Ext.get(document));
                var y = el.keys.length;
                for (var x=0;x<y;x=x+1) {
                    var k = el.keys[x];
                    Ext.applyIf(k,{
                        scope: this
                        ,stopEvent: true
                        ,fn: function(e) { this.checkConfirm(b,e); }
                    });
                    map.addBinding(k);
                }
            }
        }
    }
    
    ,evalJS: function(itm,e) {
        if (!eval(itm.javascript)) {
            e.stopEvent();
            e.preventDefault();
        }
    }
	
	/**
	 * If any confirm dialogs are specified, show them, else just redirect to the action.
	 * @param {Ext.Toolbar.Button} itm The action button pressed.
	 * @param {Ext.EventObject} e The event object.
	 */
	,checkConfirm: function(itm,e) {
		if (itm.confirm !== null && itm.confirm !== undefined) {
			this.confirm(itm,function() {
				this.handleClick(itm,e);
			},this);
		} else { this.handleClick(itm,e); }
		return false;
	}
	
	/**
	 * Handle confirm dialogs.
	 * You can abstract this so that you can choose whether or not to have confirm 
	 * dialogs, and you can also pass in functions if you dont want to redirect.
	 * If you pass a function, the only argument will be the action button.
	 * @param {Ext.Toolbar.Button} itm The action button pressed
	 * @param {Object} callback An optional function to call after the confirm.
	 * @param {Object} scope The scope to execute the function in.
	 */
	,confirm: function(itm,callback,scope) {
		/* if no message go ahead and redirect...we dont like blank questions */
		if (itm.confirm === null) { return true; }
		
		Ext.Msg.confirm('',itm.confirm,function(e) {
			/* if the user is okay with the action */
			if (e === 'yes') {
				if (callback === null) { return true; }
				if (typeof(callback) === 'function') { /* if callback is a function, run it, and pass Button */
					Ext.callback(callback,scope || this,[itm]);
				} else { location.href = callback; }
			}
		},this);
	}
	
	/**
	 * Handle any onComplete events.
	 * 
	 * @param {Object} o The options for the action buttons
	 * @param {Object} itm The action button clicked
	 * @param {Object} res XHR responseText
	 */
	,checkOnComplete: function(o,itm,res) {
        if (itm.onComplete) {
            itm.onComplete(o,itm,res);
        }
        if (itm.hasListener('success') && res.success) {
            itm.fireEvent('success',{r:res});
        }
        Ext.callback(this.redirectStay,this,[o,itm,res],1000);
	}
	
	/**
	 * Reloads the page. Encapsulated in a function to provide delay.
	 */
	,reloadPage: function() {
		location.href = location.href;
	}
	
	/**
	 * Handle any clicks on action buttons.
	 * @param {Object} itm The button
	 * @param {Ext.EventObject} e The event object 
	 */
	,handleClick: function(itm,e) {
        var o = this.config;
		/* action buttons handlers, abstracted to all get-out */
        if (itm.method === 'remote') { /* if using connectors */
			MODx.util.Progress.reset(); /* reset the Progress Bar */
            
            /* if using formpanel */
            if (o.formpanel !== undefined && o.formpanel !== '' && o.formpanel !== null) {
                o.form = Ext.getCmp(o.formpanel);
            }
            
			/* if using Ext.form */
            if (o.form !== undefined) {
                var f = o.form.getForm ? o.form.getForm() : o.form;
				if (f.isValid()) { /* client-side validation with modHExt */
                    Ext.applyIf(o.params,{
                        action: itm.process
                       ,'modx-ab-stay': MODx.config.stay
                    });
                    
                    Ext.apply(f.baseParams,o.params);
                    
                    o.form.on('success',function(r) {                        
                        /* allow for success messages */
                        if (r.result.message != '') {
                            Ext.Msg.alert(_('success'),r.result.message,function() {
                                this.checkOnComplete(o,itm,r.result);
                             },this);
                        } else {
                            /* pass the handling onto the checkOnComplete func */                                   
                            this.checkOnComplete(o,itm,r.result);
                        }
                        if (o.form.clearDirty) o.form.clearDirty();
                    },this);
					o.form.submit();
				} else {
					Ext.Msg.alert(_('error'),_('correct_errors'));	
				}
			}
        } else {	/* this is any other action besides remote */
			Ext.applyIf(itm.params || {},o.baseParams || {});
			location.href = '?'+Ext.urlEncode(itm.params);
		}
		return false;
	}
	
	/**
	 * Select the stay option.
	 * @param {Ext.menu.Item} itm The menu item checked.
	 * @param {Ext.EventObject} e The event object
	 */
	,checkStay: function(itm,e) {
		this.stay = itm.value;
	}
	
	/**
	 * Redirect the user to the correct stay value.
	 * @param {Object} o The options for the request.
	 * @param {Ext.Toolbar.Button} itm The action button pressed.
	 * @param {Object} res The XHR responseText.
	 */			
	,redirectStay: function(o,itm,res) {
		o = this.config;
		Ext.applyIf(itm.params || {},o.baseParams);
		var a = Ext.urlEncode(itm.params);
		switch (MODx.config.stay) {
			case 'new': /* if user selected 'new', then always redirect */
                if (MODx.request.parent) a = a+'&parent='+MODx.request.parent;
				location.href = '?a='+o.actions['new']+'&'+a;
				break;
			case 'stay':
				/* if Continue Editing, then don't reload the page - just hide the Progress bar
				   unless the user is on a 'Create' page...if so, then redirect
				   to the proper Edit page */
                if ((itm.process === 'create' || itm.process === 'duplicate' || itm.reload) && res.object.id !== null) {
					location.href = '?a='+o.actions.edit+'&id='+res.object.id+'&'+a;
				} else if (itm.process === 'delete') {
					location.href = '?a='+o.actions.cancel+'&'+a;
				} else { Ext.Msg.hide(); }
				break;
			case 'close': /* redirect to the cancel action */
				location.href = '?a='+o.actions.cancel+'&'+a;
				break;
		}
	}
	
	/**
	 * Returns the stay menu.
	 */
	,getStayMenu: function() {
		return {
            xtype:'switch'
            ,id: 'modx-stay-menu'
            ,activeItem: MODx.config.stay === 'new' ? 0 : 1 
            ,items: [{
                tooltip: _('stay_new')
                ,value: 'new'
                ,menuIndex: 0
                ,id: 'modx-stay-new'
                ,iconCls:'icon-list-new'
            },{
            	tooltip: _('stay')
                ,value: 'stay'
                ,menuIndex: 1
                ,id: 'modx-stay-stay'
                ,iconCls:'icon-mark-active'
            },{
                tooltip: _('close')
                ,value: 'close'
                ,menuIndex: 2
                ,id: 'modx-stay-close'
                ,iconCls:'icon-mark-complete'
            }]
            ,listeners: {
                change: function(btn,itm){
                    MODx.config.stay = itm.value;
                }
                ,scope: this
                ,delay: 10
            }
        };
	}
	
	/**
	 * Refreshes specified tree's node
	 * @param {MODx.tree.Tree} tree The tree to refresh
	 * @param {String} node The ID of the node to refresh
	 * @param {Boolean} self If true, will refresh the node itself instead of parent. Defaults to false. 
	 */
	,refreshTreeNode: function(tree,node,self) {
		var t = parent.Ext.getCmp(tree);
        t.refreshNode(node,self || false);
        return false;
	}
	
	,setupDirtyButtons: function(f) {
		var fp = Ext.getCmp(f);
        if (fp) {
            fp.on('fieldChange',function(o) {
               for (var i=0;i<this.checkDirtyBtns.length;i=i+1) {
                    var btn = this.checkDirtyBtns[i];
                    btn.setDisabled(false);
               }
            },this);
        }
	}
});
Ext.reg('modx-actionbuttons',MODx.toolbar.ActionButtons);