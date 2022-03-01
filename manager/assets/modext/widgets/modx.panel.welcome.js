/**
 * @class MODx.panel.Welcome
 * @extends MODx.Panel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-welcome
 */
MODx.panel.Welcome = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'modx-panel-welcome',
        cls: 'container',
        layout: 'auto',
        defaults: {
            collapsible: false,
            autoHeight: true,
        },
        items: [{
            xtype: 'modx-actionbuttons',
            items: [{
                id: 'widget-add-button',
                text: _('add') + ' <i class="icon icon-plus"></i>',
                hidden: true,
                handler: this.addWidget,
                scope: this
            }]
        },{
            id: 'modx-dashboard-header'
            ,xtype: 'modx-header'
            ,html: _('dashboard')
        },{
            id: 'modx-dashboard',
            applyTo: 'modx-dashboard',
            sizes: ['quarter', 'one-third', 'half', 'two-thirds', 'three-quarters', 'full', 'double'],
            border: false,
        }]
        ,listeners: {
            afterrender: function() {
                var obj = this;
                var newsContainer = document.getElementById('modx-news-feed-container');
                if (newsContainer) {
                    obj.loadFeed(newsContainer, 'news');
                }

                var securityContainer = document.getElementById('modx-security-feed-container');
                if (securityContainer) {
                    obj.loadFeed(securityContainer, 'security');
                }
            }
        }
    });
    MODx.panel.Welcome.superclass.constructor.call(this, config);
    this.setup();
};
Ext.extend(MODx.panel.Welcome, MODx.Panel, {
    _addWidget: null,

    setup: function () {
        if (this.config.dashboard && this.config.dashboard.hide_trees) {
            Ext.getCmp('modx-layout').hideLeftbar(false);
        }

        this.initDashboard();
        this.checkNew();

        MODx.fireEvent('ready');
    },

    checkNew: function() {
        if (this._addWidget) {
            if (this.dashboard.new_widgets > 0) {
                this._addWidget.show();
            } else {
                this._addWidget.hide();
            }
        }
    },

    loadFeed: function (container, feed) {
        MODx.Ajax.request({
                url: MODx.config.connector_url,
                params: {
                    action: 'System/Dashboard/Widget/Feed',
                    feed: feed
                },
                listeners: {
                    success: {
                        fn: function (response) {
                            if (response.success) {
                                container.innerHTML = MODx.util.safeHtml(response.object.html, '<h1><h2><h3><h4><span><div><ul><li><p><ol><dl><dd><dt><img><a><br><i><em><b><strong>');
                            }
                            else if (response.message.length > 0) {
                                container.innerHTML = '<p class="error">' + MODx.util.safeHtml(response.message) + '</p>';
                            }
                            var datestamps = Ext.select(".date_stamp", container);
                            datestamps.each(function (el) {
                                el.dom.innerText = new Date(el.dom.innerText).format(MODx.config.manager_date_format + ' ' + MODx.config.manager_time_format);
                            });
                        }, scope: this
                    }
                    ,failure: {
                        fn: function(response) {
                            var message = response.message.length > 0 ? response.message : _('error_loading_feed');
                            container.innerHTML = '<p class="error">' + MODx.util.safeHtml(message) + '</p>';
                        }, scope: this
                    }
                }
            }
        );
    },

    initDashboard: function () {
        var obj = Ext.getCmp('modx-dashboard');
        var el = obj.getEl().dom;
        var dashboard = obj.ownerCt.dashboard;
        if (!dashboard.customizable) {
            return;
        }
        this._addWidget = Ext.getCmp('widget-add-button');

        new Sortable(el, {
            sort: true,
            draggable: ".dashboard-block",
            handle: ".draggable",
            chosenClass: "dashboard-block-placeholder",
            onEnd: function (e) {
                if (e.oldIndex == e.newIndex) {
                    return;
                }
                MODx.Ajax.request({
                    url: MODx.config.connector_url,
                    params: {
                        action: 'System/Dashboard/User/Sort',
                        widget: e.item.getAttribute('data-id'),
                        dashboard: dashboard.id,
                        from: e.oldIndex,
                        to: e.newIndex,
                    },
                    listeners: {
                    }
                });
            },
        });

        el.addEventListener('click', function (e) {
            if (e.target.className.match(/^action\s/)) {
                var button = e.target;
                var sizes = JSON.parse(JSON.stringify(obj.sizes));
                var parent = button.parentNode.parentNode.parentNode;
                var wrapper = parent.parentNode;
                var children = parent.querySelectorAll('.action');
                var buttons = {};
                var i, other, size;
                for (i in children) {
                    if (children.hasOwnProperty(i)) {
                        buttons[children[i].getAttribute('data-action')] = children[i];
                    }
                }

                var action = button.getAttribute('data-action');
                if (parent && action) {
                    var id = parent.getAttribute('data-id');
                    if (id) {
                        if (action == 'remove') {
                            parent.outerHTML = '';
                        } else {
                            if (action == 'expand') {
                                other = buttons['shrink'];
                            } else {
                                sizes = sizes.reverse();
                                other = buttons['expand'];
                            }
                            for (i = 0; i < sizes.length; i++) {
                                if (other.classList.contains('hidden')) {
                                    other.classList.remove('hidden');
                                }
                                if (parent.classList.contains(sizes[i])) {
                                    if (sizes[i + 1] !== undefined) {
                                        parent.classList.remove(sizes[i]);
                                        parent.classList.add(sizes[i + 1]);
                                        if (sizes[i + 2] === undefined) {
                                            button.classList.add('hidden');
                                        }
                                        size = sizes[i + 1];
                                        break;
                                    } else {
                                        return;
                                    }
                                }
                            }
                            action = 'resize';
                        }
                        MODx.Ajax.request({
                            url: MODx.config.connector_url,
                            params: {
                                action: 'system/dashboard/user/' + action,
                                widget: parent.getAttribute('data-id'),
                                dashboard: dashboard.id,
                                size: size,
                            },
                            listeners: {
                                success: {
                                    fn: function (res) {
                                        if (action == 'remove') {
                                            if (!wrapper.querySelectorAll('.dashboard-block').length) {
                                                document.location.reload();
                                            } else if (res.object.new_widgets != undefined) {
                                                obj.ownerCt.dashboard.new_widgets = res.object.new_widgets;
                                                obj.ownerCt.checkNew();
                                            }

                                        }
                                    }, scope: this
                                },
                            }
                        });
                    }
                }
            }
            Ext.getCmp('modx-content').doLayout();
        });
    },

    addWidget: function () {
        MODx.load({
            xtype: 'modx-window-dashboard-widget-add',
            dashboard: this.dashboard,
            listeners: {
                success: {},
            }
        }).show();
    },
});
Ext.reg('modx-panel-welcome', MODx.panel.Welcome);


MODx.window.DashboardWidgetAdd = function (config) {
    delete config.listeners;

    config = config || {};
    this.ident = Ext.id();
    Ext.applyIf(config, {
        title: _('widget_add'),
        id: this.ident,
        url: MODx.config.connector_url,
        baseParams: {
            action: 'System/Dashboard/User/Create',
            dashboard: config.dashboard.id,
        },
        modal: true,
        resizable: false,
        collapsible: false,
        maximizable: false,
        fields: this.getFields(config),
        keys: this.getKeys(config),
        buttons: this.getButtons(config),
        closeAction: 'close',
        success: function () {
            this._reload = true;
            var combo = Ext.getCmp(this.ident + '-widget');
            if (combo) {
                combo.reset();
                combo.getStore().reload();

                MODx.msg.status({
                    title: _('success')
                    ,message: _('widget_add_success')
                });
            }
        }
    });
    MODx.window.DashboardWidgetAdd.superclass.constructor.call(this, config);
    this.on('hide', function () {
        if (this._reload) {
            document.location.reload();
        }
    });
};
Ext.extend(MODx.window.DashboardWidgetAdd, MODx.Window, {

    _reload: false,

    getFields: function (config) {
        return [{
            hideLabel: true,
            xtype: 'displayfield',
            html: _('widget_add_desc'),
            anchor: '100%'
        }, {
            fieldLabel: _('widget_add'),
            id: this.ident + '-widget',
            xtype: 'modx-combo-dashboard-widgets',
            baseParams: {
                action: 'System/Dashboard/User/GetList',
                dashboard: config.dashboard.id,
                combo: true
            },
            name: 'widget',
            hiddenName: 'widget',
            allowBlank: false,
            autoSelect: true,
            msgTarget: 'under',
            anchor: '100%',
            listeners: {
                afterrender: function(combo) {
                    var store = combo.getStore();
                    store.on('load', function(store) {
                        config.dashboard.new_widgets = store.getTotalCount();
                        var panel = Ext.getCmp('modx-panel-welcome');
                        if (panel) {
                            panel.checkNew();
                        }
                    }, this);
                }
            }
        }, {
            fieldLabel: _('widget_size'),
            id: this.ident + '-size',
            xtype: 'modx-combo-dashboard-widget-size',
            name: 'size',
            value: 'half',
            anchor: '100%'
        }];
    },

    getButtons: function (config) {
        return [{
            text: config.cancelBtnText || _('cancel'),
            scope: this,
            handler: function () {
                config.closeAction !== 'close'
                    ? this.hide()
                    : this.close();
            }
        }, {
            text: config.saveBtnText || _('save'),
            cls: 'primary-button',
            scope: this,
            handler: function () {
                this.submit(false);
            },
        }];
    },

    getKeys: function () {
        return [{
            key: Ext.EventObject.ENTER,
            shift: true,
            fn: function () {
                this.submit(false);
            }, scope: this
        }];
    },
});
Ext.reg('modx-window-dashboard-widget-add', MODx.window.DashboardWidgetAdd);
