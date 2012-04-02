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
    MODx.activePage = this;
};
Ext.extend(MODx.Component,Ext.Component,{
    fields: {}
    ,form: null
    ,action: false

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
        return this.form.render();
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
        return this.ab;
    }

    ,_loadTabs: function() {
        if (!this.config.tabs) { return false; }
        var o = this.config.tabOptions || {};
        Ext.applyIf(o,{
            xtype: 'modx-tabs'
            ,renderTo: this.config.tabs_div || 'tabs_div'
            ,items: this.config.tabs
        });
        return MODx.load(o);
    }

    ,_loadComponents: function() {
        if (!this.config.components) { return false; }
        var l = this.config.components.length;

        var cp = Ext.getCmp('modx-content');
        for (var i=0;i<l;i=i+1) {
            var a = MODx.load(this.config.components[i]);
            if (cp) {
                cp.add(a);
            }
        }
        if (cp) {
            cp.doLayout();
        }
        return true;
    }

    ,submitForm: function(listeners,options,otherParams) {
        listeners = listeners || {};
        otherParams = otherParams || {};
        if (!this.config.formpanel || !this.config.action) { return false; }
        f = Ext.getCmp(this.config.formpanel);
        if (!f) { return false; }

        for (var i in listeners) {
            if (typeof listeners[i] == 'function') {
                f.on(i,listeners[i],this);
            } else if (listeners[i] && typeof listeners[i] == 'object' && listeners[i].fn) {
                f.on(i,listeners[i].fn,listeners[i].scope || this);
            }
        }

        Ext.apply(f.baseParams,{
            'action':this.config.action
        });
        Ext.apply(f.baseParams,otherParams);
        options = options || {};
        options.headers = {
            'Powered-By': 'MODx'
            ,'modAuth': MODx.siteId
        };
        f.submit(options);
        return true;
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
            return true;
        },this);
        return true;
    }

    ,reloadPage: function() {
        location.href = location.href;
    }

    ,handleClick: function(itm,e) {
        var o = this.config;
        if (o.formpanel === false || o.formpanel === undefined || o.formpanel === null) return false;

        if (itm.method === 'remote') { /* if using connectors */
            MODx.util.Progress.reset();
            o.form = Ext.getCmp(o.formpanel);
            if (!o.form) return false;

            var f = o.form.getForm ? o.form.getForm() : o.form;
            var isv = true;
            if (f.items && f.items.items) {
                for (var fld in f.items.items) {
                    if (f.items.items[fld] && f.items.items[fld].validate) {
                        var fisv = f.items.items[fld].validate();
                        if (!fisv) {
                            f.items.items[fld].markInvalid();
                            isv = false;
                        }
                    }
                }
            }

            if (isv) {
                Ext.applyIf(o.params,{
                    action: itm.process
                   ,'modx-ab-stay': MODx.config.stay
                });

                Ext.apply(f.baseParams,o.params);

                o.form.on('success',function(r) {
                    if (o.form.clearDirty) o.form.clearDirty();
                    /* allow for success messages */
                    MODx.msg.status({
                        title: _('success')
                        ,message: r.result.message || _('save_successful')
                        ,dontHide: r.result.message != '' ? true : false
                    });
                    Ext.callback(this.redirectStay,this,[o,itm,r.result],1000);

                    this.resetDirtyButtons(r.result);
                },this);
                o.form.submit({
                    headers: {
                        'Powered-By': 'MODx'
                        ,'modAuth': MODx.siteId
                    }
                });
            } else {
                Ext.Msg.alert(_('error'),_('correct_errors'));
            }
        } else { /* if just doing a URL redirect */
            Ext.applyIf(itm.params || {},o.baseParams || {});
            location.href = '?'+Ext.urlEncode(itm.params);
        }
        return false;
    }

    ,resetDirtyButtons: function(r) {
        for (var i=0;i<this.checkDirtyBtns.length;i=i+1) {
            var btn = this.checkDirtyBtns[i];
            btn.setDisabled(true);
        }
    }

    ,checkStay: function(itm,e) {
        this.stay = itm.value;
    }

    ,redirectStay: function(o,itm,res) {
        o = this.config;
        itm.params = itm.params || {};
        Ext.applyIf(itm.params,o.baseParams);
        var stay = Ext.state.Manager.get('modx.stay.'+MODx.request.a,'stay');
        switch (stay) {
            case 'new': /* if user selected 'new', then always redirect */
                if (o.form.hasListener('actionNew')) {
                    o.form.fireEvent('actionNew',itm.params);
                } else if (o.actions) {
                    if (MODx.request.parent) { itm.params.parent = MODx.request.parent; }
                    if (MODx.request.context_key) { itm.params.context_key = MODx.request.context_key; }
                    if (MODx.request.class_key) { itm.params.class_key = MODx.request.class_key; }
                    var a = Ext.urlEncode(itm.params);
                    location.href = '?a='+o.actions['new']+'&'+a;
                }
                break;
            case 'stay':
                var url;
                if (o.form.hasListener('actionContinue')) {
                    o.form.fireEvent('actionContinue',itm.params);
                } else if (o.actions) {
                    /* if Continue Editing, then don't reload the page - just hide the Progress bar
                       unless the user is on a 'Create' page...if so, then redirect
                       to the proper Edit page */
                    if ((itm.process === 'create' || itm.process === 'duplicate' || itm.reload) && res.object.id && res.object.id !== null) {
                        itm.params.id = res.object.id;
                        if (MODx.request.parent) { itm.params.parent = MODx.request.parent; }
                        if (MODx.request.context_key) { itm.params.context_key = MODx.request.context_key; }
                        url = Ext.urlEncode(itm.params);
                        location.href = '?a='+o.actions.edit+'&'+url;

                    } else if (itm.process === 'delete') {
                        itm.params.a = o.actions.cancel;
                        url = Ext.urlEncode(itm.params);
                        location.href = '?'+url;
                    }
                }
                break;
            case 'close': /* redirect to the cancel action */
                if (o.form.hasListener('actionClose')) {
                    o.form.fireEvent('actionClose',itm.params);
                } else if (o.actions) {
                    location.href = '?a='+o.actions.cancel+'&'+Ext.encode(itm.params);
                }
                break;
        }
    }

    ,getStayMenu: function() {
        var stay = Ext.state.Manager.get('modx.stay.'+MODx.request.a,'stay');
        var a = 0;
        switch (stay) {
            case 'new': a = 0; break;
            case 'close': a = 2; break;
            case 'stay': default: a = 1; break;
        }
        return {
            xtype:'switch'
            ,id: 'modx-stay-menu'
            ,activeItem: a
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
                    Ext.state.Manager.set('modx.stay.'+MODx.request.a,itm.value);
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