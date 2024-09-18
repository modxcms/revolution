/**
 * Loads the panel for managing user messages.
 *
 * @class MODx.panel.Messages
 * @extends MODx.FormPanel
 * @param {Object} config An object of configuration properties
 * @xtype modx-panel-messages
 */
MODx.panel.Messages = function(config = {}) {
    Ext.applyIf(config, {
        id: 'modx-panel-message',
        cls: 'container',
        bodyStyle: '',
        defaults: { collapsible: false, autoHeight: true },
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Security/Message/GetList'
        },
        items: [{
            html: _('messages'),
            id: 'modx-messages-header',
            xtype: 'modx-header'
        }, MODx.getPageStructure([{
            title: _('messages'),
            id: 'modx-messages-tab',
            autoHeight: true,
            layout: 'form',
            defaults: { border: false, msgTarget: 'side' },
            items: [{
                layout: 'form',
                autoHeight: true,
                defaults: { border: false },
                items: [{
                    html: `<p>${_('messages_desc')}</p>`,
                    id: 'modx-messages-msg',
                    xtype: 'modx-description'
                }, {
                    xtype: 'modx-grid-message',
                    cls: 'main-wrapper',
                    user: config.user,
                    preventRender: true
                }]
            }]
        }])]
    });
    MODx.panel.Messages.superclass.constructor.call(this, config);
};
Ext.extend(MODx.panel.Messages, MODx.FormPanel);
Ext.reg('modx-panel-messages', MODx.panel.Messages);

/**
 * Loads a grid of Messages.
 *
 * @class MODx.grid.Message
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-message
 */
MODx.grid.Message = function(config = {}) {
    this.exp = new Ext.grid.RowExpander({
        tpl: new Ext.Template(
            `<div class="manager-user-message">
                <div class="meta">
                    <p><span>${_('sent_by')}:</span> {sender_name:this.htmlEncode}</p>
                    <p><span>${_('sent_on')}:</span> {date_sent}</p>
                </div>
                <div class="body">
                    <h3>{subject:this.htmlEncode}</h3>
                    <p>{message:this.htmlEncode}</p>
                </div>
            </div>`,
            {
                htmlEncode: function(value) {
                    return Ext.util.Format.htmlEncode(value);
                }
            }
        )
    });
    this.exp.on('expand', this.read, this);
    Ext.applyIf(config, {
        title: _('messages'),
        id: 'modx-grid-message',
        url: MODx.config.connector_url,
        baseParams: {
            action: 'Security/Message/GetList',
            type: MODx.request.type || null
        },
        fields: [
            'id',
            'type',
            'subject',
            'message',
            'sender',
            'recipient',
            'private',
            'date_sent',
            'read',
            'sender_name',
            'recipient_name'
        ],
        autosave: true,
        paging: true,
        plugins: this.exp,
        columns: [this.exp, {
            header: _('id'),
            dataIndex: 'id',
            width: 30
        }, {
            header: _('subject'),
            dataIndex: 'subject',
            width: 200,
            renderer: Ext.util.Format.htmlEncode
        }, {
            header: _('sender'),
            dataIndex: 'sender_name',
            width: 120,
            renderer: Ext.util.Format.htmlEncode
        }, {
            header: _('recipient'),
            dataIndex: 'recipient_name',
            width: 120,
            renderer: Ext.util.Format.htmlEncode
        }, {
            header: _('date_sent'),
            dataIndex: 'date_sent',
            width: 150
        }, {
            header: _('read'),
            dataIndex: 'read',
            width: 100,
            editor: {
                xtype: 'combo-boolean',
                renderer: 'boolean'
            },
            editable: false
        }],
        tbar: [
            {
                text: _('create'),
                cls: 'primary-button',
                disabled: !(MODx.perm.view_user || MODx.perm.view_role || MODx.perm.usergroup_view),
                scope: this,
                handler: this.newMessage
            },
            '->',
            {
                xtype: 'modx-combo-message-type',
                name: 'type',
                itemId: 'filter-type',
                emptyText: _('filter_by_type'),
                width: 200,
                value: MODx.request.type || null,
                listeners: {
                    render: {
                        fn: function(cmp) {
                            // Maintain default type in URL and in this combo when loading this combo and when clearing all filters
                            const clearFiltersButton = cmp.ownerCt.getComponent('filter-clear'),
                                  resetDefaults = () => {
                                      MODx.util.url.setParams({ type: 'inbox' });
                                      cmp.setValue('inbox');
                                  };
                            if (!MODx.request.type) {
                                resetDefaults();
                            }
                            if (clearFiltersButton) {
                                clearFiltersButton.on('click', button => {
                                    resetDefaults();
                                });
                            }
                        },
                        scope: this
                    },
                    select: {
                        fn: function(cmp, record, selectedIndex) {
                            this.applyGridFilter(cmp, 'type');
                        },
                        scope: this
                    }
                }
            },
            this.getQueryFilterField(),
            this.getClearFiltersButton('filter-type, filter-query')
        ]
    });
    MODx.grid.Message.superclass.constructor.call(this, config);
};
Ext.extend(MODx.grid.Message, MODx.grid.Grid, {
    read: function(expanderData, record, body, rowIndex) {
        const message = record.data;
        if (message.read) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'Security/Message/Read',
                id: message.id
            },
            listeners: {
                success: {
                    fn: function(response) {
                        const selectedMessage = this.getStore().getAt(rowIndex);
                        selectedMessage.set('read', true);
                        selectedMessage.commit();
                        this.exp.expandRow(rowIndex);
                    },
                    scope: this
                }
            }
        });
    },
    markUnread: function(btn, e) {
        const message = this.getSelectionModel().getSelected();
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'Security/Message/Unread',
                id: message.data.id
            },
            listeners: {
                success: {
                    fn: function(response) {
                        message.set('read', false);
                        message.commit();
                    },
                    scope: this
                }
            }
        });
    },
    getMenu: function() {
        const
            message = this.getSelectionModel().getSelected(),
            senderIsCurrentUser = parseInt(MODx.user.id, 10) === parseInt(message.data.sender, 10),
            menu = [{
                text: _('reply'),
                scope: this,
                handler: this.reply
            }, {
                text: _('forward'),
                scope: this,
                handler: this.forward
            }]
        ;
        if (message.data.read && !senderIsCurrentUser) {
            menu.push({
                text: _('mark_unread'),
                handler: this.markUnread
            });
            menu.push('-');
        }
        if (!senderIsCurrentUser) {
            menu.push({
                text: _('delete'),
                handler: this.remove.createDelegate(this, ['message_remove_confirm', 'Security/Message/Remove'])
            });
        }
        return menu;
    },
    reply: function(btn, e) {
        this.menu.record = {
            type: 'user',
            user: this.menu.record.sender,
            subject: `RE: ${this.menu.record.subject}`,
            message: ''
        };
        this.loadWindow(btn, e, {
            xtype: 'modx-window-message-create'
        });
    },
    forward: function(btn, e) {
        this.menu.record = {
            type: 'user',
            user: '',
            subject: `Fwd: ${this.menu.record.subject}`,
            message: `\r\n--\r\n${this.menu.record.message}`
        };
        this.loadWindow(btn, e, {
            xtype: 'modx-window-message-create'
        });
    },
    newMessage: function(btn, e) {
        this.menu.record = {
            type: 'user',
            user: '',
            subject: '',
            message: ''
        };
        this.loadWindow(btn, e, {
            xtype: 'modx-window-message-create'
        });
    }
});
Ext.reg('modx-grid-message', MODx.grid.Message);

/**
 * Generates the new message window.
 *
 * @class MODx.window.CreateMessage
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-message-create
 */
MODx.window.CreateMessage = function(config = {}) {
    Ext.applyIf(config, {
        title: _('create'),
        url: MODx.config.connector_url,
        action: 'Security/Message/Create',
        formDefaults: {
            allowBlank: false,
            anchor: '100%',
            msgTarget: 'under',
            validationEvent: 'change',
            validateOnBlur: false
        },
        fields: this.getFields(),
        keys: []
    });
    MODx.window.CreateMessage.superclass.constructor.call(this, config);
    this.on({
        afterrender: {
            fn: function() {
                /*
                    Force the form the layout again after the auto select of the Type combo,
                    otherwise the Recipients combo will not size correctly (due for some reason
                    to the validation effect of allowBlank: false)
                */
                this.setWidth(this.getWidth() + 1);
            }
        },
        show: {
            fn: function() {
                const form = this.fp.getForm();
                form.findField('type').fireEvent('select');
                form.items.items.forEach(field => {
                    field.clearInvalid();
                });
            }
        }
    });
};
Ext.extend(MODx.window.CreateMessage, MODx.Window, {
    recipientTypes: ['user', 'usergroup', 'role', 'all'],

    getFields: function() {
        const data = [];
        if (MODx.perm.view_user) {
            data.push(['user', _('user')]);
        }
        if (MODx.perm.usergroup_view) {
            data.push(['usergroup', _('usergroup')]);
        }
        if (MODx.perm.view_role) {
            data.push(['role', _('role')]);
        }
        if (MODx.perm.view_user) {
            data.push(['all', _('all')]);
        }

        const items = [{
            xtype: 'combo',
            fieldLabel: _('recipient_type'),
            name: 'type',
            hiddenName: 'type',
            store: new Ext.data.SimpleStore({
                fields: [
                    'type',
                    'disp'
                ],
                data: data
            }),
            mode: 'local',
            triggerAction: 'all',
            displayField: 'disp',
            valueField: 'type',
            editable: false,
            value: data[0][0],
            listeners: {
                select: {
                    fn: this.showRecipient,
                    scope: this
                }
            }
        }];

        if (MODx.perm.view_user) {
            items.push({
                xtype: 'modx-combo-user',
                id: 'mc-recipient-user',
                fieldLabel: _('user')
            });
            items.push({
                xtype: 'modx-combo-usergroup',
                id: 'mc-recipient-usergroup',
                fieldLabel: _('usergroup'),
                hidden: true
            });
        }
        if (MODx.perm.view_role) {
            items.push({
                xtype: 'modx-combo-role',
                id: 'mc-recipient-role',
                fieldLabel: _('role'),
                hidden: true
            });
        }
        if (MODx.perm.view_user) {
            items.push({
                xtype: 'hidden',
                id: 'mc-recipient-all',
                name: 'all',
                fieldLabel: _('all'),
                value: 'all'
            });
        }

        items.push([{
            xtype: 'textfield',
            fieldLabel: _('subject'),
            name: 'subject',
            maxLength: 255
        }, {
            xtype: 'textarea',
            fieldLabel: _('message'),
            name: 'message',
            grow: true
        }, {
            xtype: 'xcheckbox',
            name: 'sendemail',
            ctCls: 'display-switch space-before',
            boxLabel: _('message_send_email'),
            hideLabel: true,
            inputValue: 0,
            checked: false
        }]);
        return items;
    },
    showRecipient: function(combo, record, selectedIndex) {
        const
            form = this.fp.getForm(),
            currentRecipientType = record ? record.data.type : 'user',
            currentRecipientsCombo = form.findField(`mc-recipient-${currentRecipientType}`)
        ;
        // Initially hide all combos
        for (let i = 0; i < this.recipientTypes.length; i++) {
            const recipientsCombo = form.findField(`mc-recipient-${this.recipientTypes[i]}`);
            if (recipientsCombo) {
                this.hideField(recipientsCombo);
            }
        }
        // Show the combo for the currently-selected type
        if (currentRecipientsCombo) {
            this.showField(currentRecipientsCombo);
            currentRecipientsCombo.setSize('100%');
        }
    }
});
Ext.reg('modx-window-message-create', MODx.window.CreateMessage);

/**
 * Select Box with types of messages for showing to user.
 *
 * @class MODx.combo.MessageType
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-combo-message-type
 */
MODx.combo.MessageType = function(config = {}) {
    Ext.applyIf(config, {
        store: new Ext.data.SimpleStore({
            fields: [
                'd',
                'v'
            ],
            data: [
                [_('messages_inbox'), 'inbox'],
                [_('messages_outbox'), 'outbox']
            ]
        }),
        displayField: 'd',
        valueField: 'v',
        mode: 'local',
        editable: false,
        selectOnFocus: false,
        preventRender: true,
        forceSelection: true,
        enableKeyEvents: true,
        allowBlank: false
    });
    MODx.combo.MessageType.superclass.constructor.call(this, config);
};
Ext.extend(MODx.combo.MessageType, MODx.combo.ComboBox);
Ext.reg('modx-combo-message-type', MODx.combo.MessageType);
