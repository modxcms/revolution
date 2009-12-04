MODx.Component = function(config) {
    config = config || {};
    MODx.Component.superclass.constructor.call(this,config);
    this.config = config;
    
    this._loadForm();
    if (this.config.tabs) {
        this._loadTabs();
    }
    this._loadComponents();
    this._loadActionButtons();
};
Ext.extend(MODx.Component,Ext.Component,{
    fields: {}
    
	,_loadForm: function() {
		if (!this.config.form) { return false; }
        this.form = new Ext.form.BasicForm(Ext.get(this.config.form),{ errorReader : MODx.util.JSONReader });
        
        if (this.config.fields) {
        	for (var i in this.config.fields) {
               if (this.config.fields.hasOwnProperty(i)) {
            	   var f = this.config.fields[i];
                   if (f.xtype) {
                    f = Ext.ComponentMgr.create(f);
                   }
            	   this.fields[i] = f;
            	   this.form.add(f);
               }
        	}
        }
        this.form.render();
    }
    
	,_loadActionButtons: function() {
		if (!this.config.buttons) { return false; }        
        this.ab = MODx.load({
            xtype: 'modx-actionbuttons'
            ,form: this.form || null
            ,formpanel: this.config.formpanel || null
            ,actions: this.config.actions || null
            ,items: this.config.buttons || []
            ,loadStay: this.config.loadStay || false
        });
	}
	
	,_loadTabs: function() {
		if (!this.config.tabs) { return false; }
        var o = this.config.tabOptions || {};
        Ext.applyIf(o,{
            xtype: 'modx-tabs'
            ,renderTo: this.config.tabs_div || 'tabs_div'
            ,items: this.config.tabs
        });
        MODx.load(o);
	}
    
    ,_loadComponents: function() {
        if (!this.config.components) { return false; }
        var l = this.config.components.length;
        for (var i=0;i<l;i=i+1) {
            MODx.load(this.config.components[i]);
        }
    }	
});
Ext.reg('modx-component',MODx.Component);


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
    id: ''
    ,buttons: []
    ,options: { a_close: 'welcome' }
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
            
            var id = el.id || Ext.id();
            Ext.applyIf(el,{
                xtype: 'button'
                ,cls: (el.icon ? 'x-btn-icon bmenu' : 'x-btn-text bmenu')
                ,scope: this
                ,disabled: el.checkDirty ? true : false
                ,listeners: {}
                ,id: id
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
            
            /* if javascript is specified, run it when button is click, before this.checkConfirm is run */
            if (el.javascript) {
                el.listeners['click'] = {fn:this.evalJS,scope:this};
            }
            
            /* if checkDirty, disable until field change */
            if (el.xtype == 'button') {
                el.listeners['render'] = {fn:function(btn) {
                    if (el.checkDirty && btn) {
                        this.checkDirtyBtns.push(btn);
                    }
                },scope:this}
            }
            
            /* add button to toolbar */
            MODx.toolbar.ActionButtons.superclass.add.call(this,el);
            
            if (el.keys) {
                var map = new Ext.KeyMap(Ext.get(document));
                var y = el.keys.length;
                for (var x=0;x<y;x=x+1) {
                    var k = el.keys[x];
                    Ext.applyIf(k,{
                        scope: this
                        ,stopEvent: true
                        ,fn: function(e) {
                            var b = Ext.getCmp(id);
                            if (b) this.checkConfirm(b,e);
                        }
                    });
                    map.addBinding(k);
                }
            }
            delete el;
        }
    }
    
    ,evalJS: function(itm,e) {
        if (!eval(itm.javascript)) {
            e.stopEvent();
            e.preventDefault();
        }
    }
    
    ,checkConfirm: function(itm,e) {
        if (itm.confirm !== null && itm.confirm !== undefined) {
            this.confirm(itm,function() {
                this.handleClick(itm,e);
            },this);
        } else { this.handleClick(itm,e); }
        return false;
    }
    
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
    
    ,checkOnComplete: function(o,itm,res) {
        if (itm.onComplete) {
            itm.onComplete(o,itm,res);
        }
        if (itm.hasListener('success') && res.success) {
            itm.fireEvent('success',{r:res});
        }
        Ext.callback(this.redirectStay,this,[o,itm,res],1000);
    }
    
    ,reloadPage: function() {
        location.href = location.href;
    }
    
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
        } else {    /* this is any other action besides remote */
            Ext.applyIf(itm.params || {},o.baseParams || {});
            location.href = '?'+Ext.urlEncode(itm.params);
        }
        return false;
    }
    
    ,checkStay: function(itm,e) {
        this.stay = itm.value;
    }
            
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