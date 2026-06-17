MODx.Component = function(config = {}) {
    MODx.Component.superclass.constructor.call(this, config);
    this.config = config;

    this._loadForm();
    if (this.config.tabs) {
        this._loadTabs();
    }
    this._loadComponents();
    this._loadActionButtons();
    MODx.activePage = this;
};
Ext.extend(MODx.Component, Ext.Component, {
    fields: {},
    form: null,
    action: false,

    _loadForm: function() {
        if (!this.config.form) {
            return false;
        }
        this.form = new Ext.form.BasicForm(Ext.get(this.config.form), { errorReader: MODx.util.JSONReader });

        if (this.config.fields) {
            Object.entries(this.config.fields).forEach(([property, value]) => {
                let field = value;
                if (field.xtype) {
                    field = Ext.ComponentMgr.create(field);
                }
                this.fields[property] = field;
                this.form.add(field);
            });
        }
        return this.form.render();
    },

    _loadActionButtons: function() {
        if (!this.config.buttons) {
            return false;
        }

        this.ab = MODx.load({
            xtype: 'modx-actionbuttons',
            form: this.form || null,
            formpanel: this.config.formpanel || null,
            actions: this.config.actions || null,
            items: this.config.buttons || []
        });
        return this.ab;
    },

    _loadTabs: function() {
        if (!this.config.tabs) {
            return false;
        }
        const { tabOptions } = this.config || {};
        Ext.applyIf(tabOptions, {
            xtype: 'modx-tabs',
            renderTo: this.config.tabs_div || 'tabs_div',
            items: this.config.tabs
        });
        return MODx.load(tabOptions);
    },

    _loadComponents: function() {
        if (!this.config.components) {
            return false;
        }
        const
            count = this.config.components.length,
            contentPanel = Ext.getCmp('modx-content')
        ;
        for (let i = 0; i < count; i++) {
            const component = MODx.load(this.config.components[i]);
            if (contentPanel) {
                contentPanel.add(component);
            }
        }
        if (contentPanel) {
            contentPanel.doLayout();
        }
        return true;
    },

    // Note: In the context of the core, this is exclusively used when changing a resource template
    submitForm: function(listeners = {}, options = {}, otherParams = {}) {
        if (!this.config.formpanel || !this.config.action) {
            return false;
        }
        const formPanel = Ext.getCmp(this.config.formpanel);
        if (!formPanel) {
            return false;
        }

        Object.entries(listeners).forEach(([event, value]) => {
            if (typeof typeof value === 'function') {
                formPanel.on(event, value, this);
            } else if (typeof value === 'object' && value.fn) {
                formPanel.on(event, value.fn, value.scope || this);
            }
        });

        Ext.apply(formPanel.baseParams, {
            action: this.config.action
        });
        Ext.apply(formPanel.baseParams, otherParams);
        options.headers = {
            'Powered-By': 'MODx',
            modAuth: MODx.siteId
        };
        formPanel.submit(options);
        return true;
    }
});
Ext.reg('modx-component', MODx.Component);

MODx.toolbar.ActionButtons = function(config = {}) {
    Ext.applyIf(config, {
        actions: {
            close: 'welcome'
        },
        formpanel: false,
        id: 'modx-action-buttons',
        params: {},
        items: [],
        renderTo: Ext.get('modx-action-buttons-container') ? 'modx-action-buttons-container' : 'modx-container'
    });
    if (config.formpanel) {
        this.setupDirtyButtons(config.formpanel);
    }
    this.checkDirtyBtns = [];
    MODx.toolbar.ActionButtons.superclass.constructor.call(this, config);
    this.config = config;
};
Ext.extend(MODx.toolbar.ActionButtons, Ext.Toolbar, {
    id: '',
    buttons: [],
    options: {
        a_close: 'welcome'
    },

    add: function(...args) {
        for (let i = 0; i < args.length; i++) {
            const
                el = args[i],
                hasHandler = ['function', 'object'].includes(typeof el.handler),
                id = el.id || Ext.id(),
                exclude = ['-', '->', '<-', '', ' ']
            ;
            if (exclude.indexOf(el) !== -1 || (el.xtype && el.xtype === 'switch')) {
                MODx.toolbar.ActionButtons.superclass.add.call(this, el);
                continue;
            }
            Ext.applyIf(el, {
                xtype: 'button',
                cls: el.icon ? 'x-btn-icon bmenu' : 'x-btn-text bmenu',
                scope: this,
                disabled: el?.checkDirty,
                listeners: {},
                id: id
            });
            if (el.button) {
                MODx.toolbar.ActionButtons.superclass.add.call(this, el);
            }

            if (hasHandler) {
                // Make adjustment to call handler after confirm
                if (el.confirm) {
                    el.handler = function() {
                        Ext.Msg.confirm(_('warning'), el.confirm, function(e) {
                            if (e === 'yes') {
                                Ext.callback(el.handler, this);
                            }
                        }, el.scope || this);
                    };
                }
                // (unmodified handler) Buttons, i.e., Duplicate, Trash, Help, View, Refresh, Clear/Close
            } else if (el.menu !== null) {
                // Buttons, i.e., Save, etc.
                el.handler = this.handleClick;
            } else {
                el.handler = this.checkConfirm;
            }

            /* if javascript is specified, run it when button is click, before this.checkConfirm is run */
            if (el.javascript) {
                el.listeners.click = {
                    fn: this.evalJS,
                    scope: this
                };
            }

            /* if checkDirty, disable until field change */
            if (el.xtype === 'button') {
                el.listeners.render = {
                    fn: function(btn) {
                        if (el.checkDirty && btn) {
                            this.checkDirtyBtns.push(btn);
                        }
                    },
                    scope: this
                };
            }

            if (el.keys) {
                el.keyMap = new Ext.KeyMap(Ext.get(document));
                // On first pass, el.keys might be a function; only loop on second pass/when value is an array
                if (el.keys instanceof Array) {
                    el.keys.forEach(keyConfig => {
                        Ext.applyIf(keyConfig, {
                            scope: this,
                            stopEvent: true,
                            fn: function(e) {
                                const button = Ext.getCmp(id);
                                if (button) {
                                    this.checkConfirm(button, e);
                                }
                            }
                        });
                        el.keyMap.addBinding(keyConfig);
                    });
                }

                el.listeners.destroy = {
                    fn: function(btn) {
                        btn.keyMap.disable();
                    },
                    scope: this
                };
            }

            /* add button to toolbar */
            MODx.toolbar.ActionButtons.superclass.add.call(this, el);
        }
    },

    evalJS: function(itm, e) {
        // eslint-disable-next-line no-eval
        if (!eval(itm.javascript)) {
            e.stopEvent();
            e.preventDefault();
        }
    },

    checkConfirm: function(itm, e) {
        if (itm.confirm !== null && itm.confirm !== undefined) {
            this.confirm(itm, function() {
                this.handleClick(itm, e);
            }, this);
        } else {
            this.handleClick(itm, e);
        }
        return false;
    },

    confirm: function(itm, callback, scope) {
        /* if no message go ahead and redirect...we dont like blank questions */
        if (itm.confirm === null) {
            return true;
        }

        Ext.Msg.confirm('', itm.confirm, function(e) {
            if (e === 'yes') {
                if (callback === null) {
                    return true;
                }
                if (typeof callback === 'function') {
                    Ext.callback(callback, scope || this, [itm]);
                } else {
                    window.location.href = callback;
                }
            }
            return true;
        }, this);
        return true;
    },

    /** @todo Remove? No usage found in core js */
    reloadPage: function() {
        // eslint-disable-next-line no-restricted-globals, no-self-assign
        location.href = location.href;
    },

    handleClick: function(itm, e) {
        const o = this.config;
        if (o.formpanel === false || o.formpanel === undefined || o.formpanel === null) {
            return false;
        }

        if (itm.method === 'remote') {
            MODx.util.Progress.reset();
            o.form = Ext.getCmp(o.formpanel);
            if (!o.form) {
                return false;
            }

            const form = o.form.getForm ? o.form.getForm() : o.form;
            let isValid = true;
            if (form.items && form.items.items) {
                form.items.items.forEach(item => {
                    if (item && item.validate && !item.validate()) {
                        isValid = false;
                    }
                });
            }

            if (isValid) {
                Ext.applyIf(o.params, {
                    action: itm.process
                });

                Ext.apply(form.baseParams, o.params);

                o.form.on('success', function(r) {
                    if (o.form.clearDirty) {
                        o.form.clearDirty();
                    }
                    MODx.msg.status({
                        title: _('success'),
                        message: r.result.message || _('save_successful'),
                        dontHide: r.result.message !== ''
                    });

                    if (itm.redirect !== false) {
                        let { redirect } = this;

                        if (typeof itm.redirect === 'function') {
                            redirect = itm.redirect;
                        }

                        Ext.callback(redirect, this, [o, itm, r.result], 1000);
                    }

                    this.resetDirtyButtons(r.result);
                }, this);
                o.form.submit({
                    headers: {
                        'Powered-By': 'MODx',
                        modAuth: MODx.siteId
                    }
                });
            } else {
                o.form.fireEvent('failureSubmit');

                Ext.Msg.alert(_('error'), _('correct_errors'));
            }
        } else {
            // if just doing a URL redirect
            const params = itm.params || {};
            Ext.applyIf(params, o.baseParams || {});
            MODx.loadPage(`?${Ext.urlEncode(params)}`);
        }
        return false;
    },

    resetDirtyButtons: function(r) {
        for (let i = 0; i < this.checkDirtyBtns.length; i++) {
            const btn = this.checkDirtyBtns[i];
            btn.setDisabled(true);
        }
    },

    redirect: function(o, itm, res) {
        o = this.config;
        itm.params = itm.params || {};
        Ext.applyIf(itm.params, o.baseParams);

        let url;
        const process = itm.process.substr(itm.process.lastIndexOf('/') + 1).toLowerCase();

        if ((process === 'create' || process === 'duplicate' || itm.reload) && res.object.id) {
            itm.params.id = res.object.id;
            if (MODx.request.parent) {
                itm.params.parent = MODx.request.parent;
            }
            if (MODx.request.context_key) {
                itm.params.context_key = MODx.request.context_key;
            }
            url = Ext.urlEncode(itm.params);
            let action;
            if (o.actions && o.actions.edit) {
                // If an edit action is given, use it (BC)
                action = o.actions.edit;
            } else {
                // Else assume we want the 'update' controller
                action = itm.process.replace('create', 'update').replace('Create', 'Update');
            }
            MODx.loadPage(action, url);
        } else if (process === 'delete') {
            itm.params.a = o.actions.cancel;
            url = Ext.urlEncode(itm.params);
            MODx.loadPage(`?${url}`);
        }
    },

    /** @todo Remove, appears to be ununsed */
    refreshTreeNode: function(tree, node, self) {
        // eslint-disable-next-line no-restricted-globals
        const t = parent.Ext.getCmp(tree);
        t.refreshNode(node, self || false);
        return false;
    },

    setupDirtyButtons: function(f) {
        const fp = Ext.getCmp(f);
        if (fp) {
            fp.on('fieldChange', function(o) {
                for (let i = 0; i < this.checkDirtyBtns.length; i++) {
                    const btn = this.checkDirtyBtns[i];
                    btn.setDisabled(false);
                }
            }, this);
        }
    }
});
Ext.reg('modx-actionbuttons', MODx.toolbar.ActionButtons);
