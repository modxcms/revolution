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
        actions: { 'close': 'welcome' }
        ,formpanel: false
        ,id: 'modx-action-buttons'
        ,params: {}
        ,items: []
        ,renderTo: Ext.get('modx-action-buttons-container') ? 'modx-action-buttons-container' : 'modx-container'
    });
    if (config.formpanel) {
        this.setupDirtyButtons(config.formpanel);
    }
    this.checkDirtyBtns = [];
    MODx.toolbar.ActionButtons.superclass.constructor.call(this,config);
    this.config = config;
};
Ext.extend(MODx.toolbar.ActionButtons,Ext.Toolbar,{
    id: ''
    ,buttons: []
    ,options: { a_close: 'welcome' }

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

            if (el.keys) {
                el.keyMap = new Ext.KeyMap(Ext.get(document));
                for (var j = 0; j < el.keys.length; j++) {
                    var key = el.keys[j];
                    Ext.applyIf(key,{
                        scope: this
                        ,stopEvent: true
                        ,fn: function(e) {
                            var b = Ext.getCmp(id);
                            if (b) this.checkConfirm(b,e);
                        }
                    });
                    el.keyMap.addBinding(key);
                }
                el.listeners['destroy'] = {fn:function(btn) {
                    btn.keyMap.disable();
                },scope:this}
            }

            /* add button to toolbar */
            MODx.toolbar.ActionButtons.superclass.add.call(this,el);
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

                    if (itm.redirect != false) {
                        var redirect = this.redirect;

                        if (typeof itm.redirect == 'function') {
                            redirect = itm.redirect;
                        }

                        Ext.callback(redirect,this,[o,itm,r.result],1000);
                    }

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
        } else {
            // if just doing a URL redirect
            var params = itm.params || {};
            Ext.applyIf(params, o.baseParams || {});
            MODx.loadPage('?' + Ext.urlEncode(params));
        }
        return false;
    }

    ,resetDirtyButtons: function(r) {
        for (var i=0;i<this.checkDirtyBtns.length;i=i+1) {
            var btn = this.checkDirtyBtns[i];
            btn.setDisabled(true);
        }
    }

    ,redirect: function(o,itm,res) {
        o = this.config;
        itm.params = itm.params || {};
        Ext.applyIf(itm.params,o.baseParams);
        var url;

        var process = itm.process.substr(itm.process.lastIndexOf('/') + 1);
        if ((process === 'create' || process === 'duplicate' || itm.reload) && res.object.id) {
            itm.params.id = res.object.id;
            if (MODx.request.parent) { itm.params.parent = MODx.request.parent; }
            if (MODx.request.context_key) { itm.params.context_key = MODx.request.context_key; }
            url = Ext.urlEncode(itm.params);
            var action;
            if (o.actions && o.actions.edit) {
                // If an edit action is given, use it (BC)
                action = o.actions.edit;
            } else {
                // Else assume we want the 'update' controller
                action = itm.process.replace('create', 'update');
            }
            MODx.loadPage(action, url);

        } else if (process === 'delete') {
            itm.params.a = o.actions.cancel;
            url = Ext.urlEncode(itm.params);
            MODx.loadPage('?'+url);
        }
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