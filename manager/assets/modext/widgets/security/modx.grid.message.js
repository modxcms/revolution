
MODx.panel.Messages = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'modx-panel-message'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/message/getlist'
        }
        ,layout: 'anchor'
        ,bodyStyle: 'background: none;'
        ,cls: 'container form-with-labels'
        ,border: false
        ,items: [{
            html: '<h2>'+_('messages')+'</h2>'
            ,id: 'modx-messages-header'
            ,cls: 'modx-page-header'
            ,border: false
            ,autoHeight: true
            ,anchor: '100%'
        },MODx.getPageStructure([{
            title: _('messages')
            ,cls: 'main-wrapper'
            ,id: 'modx-messages-tab'
            ,autoHeight: true
            ,border: false
            ,items: [{
                html: ''
                ,id: 'modx-messages-msg'
                ,border: false
            },{
                xtype: 'modx-grid-message'
                ,user: config.user
                ,preventRender: true
            }]
        }])]
    });
    MODx.panel.Messages.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Messages,MODx.Panel);
Ext.reg('modx-panel-messages',MODx.panel.Messages);

/**
 * Loads a grid of Messages.
 *
 * @class MODx.grid.Message
 * @extends MODx.grid.Grid
 * @param {Object} config An object of options.
 * @xtype modx-grid-message
 */
MODx.grid.Message = function(config) {
    config = config || {};

    this.exp = new Ext.grid.RowExpander({
        tpl : new Ext.Template(
            '<span style="float: right;">'
            ,'<i>'+_('sent_by')+': {sender_name:this.htmlEncode} <br />'+_('sent_on')+': {date_sent}</i><br /><br />'
            ,'</span>'
            ,'<h3>{subject:this.htmlEncode}</h3>'
            ,'<p>{message:this.htmlEncode}</p>'
            , {
                htmlEncode: function(value){
                    return Ext.util.Format.htmlEncode(value);
                }
            }
        )
    });
    this.exp.on('expand',this.read,this);
    var disabled = !(MODx.perm.view_user || MODx.perm.view_role || MODx.perm.view_usergroup)
    Ext.applyIf(config,{
        title: _('messages')
        ,id: 'modx-grid-message'
        ,url: MODx.config.connector_url
        ,baseParams: {
            action: 'security/message/getlist'
        }
        ,fields: ['id','type','subject','message','sender','recipient','private'
            ,'date_sent','read','sender_name']
        ,autosave: true
        ,paging: true
        ,plugins: this.exp
        ,columns: [this.exp,{
            header: _('id')
            ,dataIndex: 'id'
            ,width: 60
        },{
            header: _('sender')
            ,dataIndex: 'sender_name'
            ,width: 120
            ,renderer: Ext.util.Format.htmlEncode
        },{
            header: _('subject')
            ,dataIndex: 'subject'
            ,width: 200
            ,renderer: Ext.util.Format.htmlEncode
        },{
            header: _('date_sent')
            ,dataIndex: 'date_sent'
            ,width: 150
        },{
            header: _('read')
            ,dataIndex: 'read'
            ,width: 100
            ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' }
            ,editable: false
        }]
        ,tbar: [{
            text: _('message_new')
            ,cls:'primary-button'
            ,disabled: disabled
            ,scope: this
            ,handler: this.newMessage
        },'->',{
            xtype: 'textfield'
            ,name: 'search'
            ,id: 'modx-messages-search'
            ,cls: 'x-form-filter'
            ,emptyText: _('search_ellipsis')
            ,listeners: {
                'change': {fn: this.search, scope: this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: this.blur
                        ,scope: cmp
                    });
                },scope:this}
            }
        },{
            xtype: 'button'
            ,id: 'modx-filter-clear'
            ,cls: 'x-form-filter-clear'
            ,text: _('filter_clear')
            ,listeners: {
                'click': {fn: this.clearFilter, scope: this}
            }
        }]
    });
    MODx.grid.Message.superclass.constructor.call(this,config);
};
Ext.extend(MODx.grid.Message,MODx.grid.Grid,{
    read: function(exp,rec,body,ri) {
        var r = rec.data;
        if (r.read) return false;
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'security/message/read'
                ,id: r.id
            }
            ,listeners: {
            	'success': {fn:function(r) {
                    var r2 = this.getStore().getAt(ri);
                    r2.set('read',true);
                    r2.commit();
                    this.exp.expandRow(ri);
            	},scope:this}
            }
        });
    }
    ,markUnread: function(btn,e) {
        var rec = this.getSelectionModel().getSelected();
        MODx.Ajax.request({
            url: this.config.url
            ,params: {
                action: 'security/message/unread'
                ,id: rec.data.id
            }
            ,listeners: {
            	'success': {fn:function(r) {
            		rec.set('read',false);
            		rec.commit();
            	},scope:this}
            }
        });
    }
    ,getMenu: function() {
        var r = this.getSelectionModel().getSelected();
        var m = [{
            text: _('reply')
            ,scope: this
            ,handler: this.reply
        },{
            text: _('forward')
            ,scope: this
            ,handler: this.forward
        }];
        if (r.data.read) {
            m.push({
                text: _('mark_unread')
                ,handler: this.markUnread
            });
            m.push('-');
        }
        m.push({
            text: _('delete')
            ,handler: this.remove.createDelegate(this,['message_remove_confirm', 'security/message/remove'])
        });
        return m;
    }
    ,reply: function(btn,e) {
        this.menu.record = {
            type: 'user'
            ,user: this.menu.record.sender
            ,subject: 'RE: ' + this.menu.record.subject
            ,message: ''
        };
        this.loadWindow(btn,e,{
            xtype: 'modx-window-message-create'
        });
    }
    ,forward: function(btn,e) {
        this.menu.record = {
            type: 'user'
            ,user: ''
            ,subject: 'Fwd: ' + this.menu.record.subject
            ,message: "\r\n--\r\n" + this.menu.record.message
        };
        this.loadWindow(btn,e,{
            xtype: 'modx-window-message-create'
        });
    }
    ,newMessage: function(btn,e) {
        this.menu.record = {
            type: 'user'
            ,user: ''
            ,subject: ''
            ,message: ''
        };
        this.loadWindow(btn,e,{
            xtype: 'modx-window-message-create'
        });
    }
    ,search: function(tf,newValue,oldValue) {
        var nv = newValue || tf;
        this.getStore().baseParams.search = Ext.isEmpty(nv) || Ext.isObject(nv) ? '' : nv;
        this.getBottomToolbar().changePage(1);
        //this.refresh();
        return true;
    }
    ,clearFilter: function() {
    	this.getStore().baseParams = {
            action: 'security/message/getList'
    	};
        Ext.getCmp('modx-messages-search').reset();
    	this.getBottomToolbar().changePage(1);
        //this.refresh();
    }
});
Ext.reg('modx-grid-message',MODx.grid.Message);

/**
 * Generates the new message window.
 *
 * @class MODx.window.CreateMessage
 * @extends MODx.Window
 * @param {Object} config An object of options.
 * @xtype modx-window-message-create
 */
MODx.window.CreateMessage = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('message_create')
        ,url: MODx.config.connector_url
        ,action: 'security/message/create'
        ,fields: this.getFields()
        ,keys: []
    });
    MODx.window.CreateMessage.superclass.constructor.call(this,config);
    this.on('show',function() {
        this.fp.getForm().findField('type').fireEvent('select');
    },this);
};
Ext.extend(MODx.window.CreateMessage,MODx.Window,{
    tps: ['user','usergroup','role','all']

    ,getFields: function() {
        var data = [];
        if (MODx.perm.view_user) data.push(['user',_('user')]);
        if (MODx.perm.view_usergroup) data.push(['usergroup',_('usergroup')]);
        if (MODx.perm.view_role) data.push(['role',_('role')]);
        if (MODx.perm.view_user) data.push(['all',_('all')]);

        var items = [{
            xtype: 'combo'
            ,fieldLabel: _('recipient_type')
            ,name: 'type'
            ,hiddenName: 'type'
            ,store: new Ext.data.SimpleStore({
                fields: ['type','disp']
                ,data: data
            })
            ,mode: 'local'
            ,triggerAction: 'all'
            ,displayField: 'disp'
            ,valueField: 'type'
            ,editable: false
            ,value: data[0][0]
            ,listeners: {
                'select': {fn:this.showRecipient,scope:this}
            }
            ,anchor: '100%'
        }];

        if (MODx.perm.view_user) items.push({
            xtype: 'modx-combo-user'
            ,id: 'mc-recipient-user'
            ,fieldLabel: _('user')
            ,allowBlank: true
            ,anchor: '100%'
        });
        if (MODx.perm.view_usergroup) items.push({
            xtype: 'modx-combo-usergroup'
            ,id: 'mc-recipient-usergroup'
            ,fieldLabel: _('usergroup')
            ,allowBlank: true
            ,anchor: '100%'
        });
        if (MODx.perm.view_role) items.push({
            xtype: 'modx-combo-role'
            ,id: 'mc-recipient-role'
            ,fieldLabel: _('role')
            ,allowBlank: true
            ,anchor: '100%'
        });
        if (MODx.perm.view_user) items.push({
            xtype: 'hidden'
            ,id: 'mc-recipient-all'
            ,name: 'all'
            ,fieldLabel: _('all')
            ,value: 'all'
        });

        items.push( [{
            xtype: 'textfield'
            ,fieldLabel: _('subject')
            ,name: 'subject'
            ,maxLength: 255
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,fieldLabel: _('message')
            ,name: 'message'
            ,anchor: '100%'
            ,grow: true
        },{
            xtype: 'xcheckbox'
            ,name: 'sendemail'
            ,boxLabel: _('message_send_email')
            ,hideLabel: true
            ,inputValue: 0
            ,checked: false
        }]);
        return items;
    }
    ,showRecipient: function(cb,rec,i) {
        var form = this.fp.getForm();
        for (var x=0;x<this.tps.length;x++) {
            var f = form.findField('mc-recipient-'+this.tps[x]);
            if (f) { this.hideField(f); }
        }
        var type = rec ? rec.data.type : 'user';
        var fd = form.findField('mc-recipient-'+type);
        if (fd) { this.showField(fd);}
    }
});
Ext.reg('modx-window-message-create',MODx.window.CreateMessage);
