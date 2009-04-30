Ext.onReady(function() {
    if (top.frames.length !== 0) {
        top.location=self.document.location;
    }
    MODx.load({ xtype: 'modx-page-login' });
});
var loginHandler = function(opt,s,r) {
    r = Ext.decode(r.responseText);
    if (r.success) {
       top.document.location.href = (r.object.url !== undefined) ? r.object.url : './';
    } else { MODx.form.Handler.errorExt(r); }
};
var doLogin = function() {
    return MODx.form.Handler.send('loginfrm', 'login', loginHandler);
};

/**
 * @class MODx.page.Login
 * @extends MODx.Component
 * @param {Object} config An object with config parameters
 * @xtype modx-page-login
 */
MODx.page.Login = function(config) {
	config = config || {};
	Ext.applyIf(config,{
	   components: [{
	       xtype: 'modx-panel-login'
	       ,renderTo: 'modx-panel-login'
	   }]
	});
	MODx.page.Login.superclass.constructor.call(this,config);
};
Ext.extend(MODx.page.Login,MODx.Component,{
	
});
Ext.reg('modx-page-login',MODx.page.Login);

/**
 * @class MODx.panel.Login
 * @extends MODx.FormPanel
 * @param {Object} config An object of config parameters
 * @xtype modx-panel-login
 */
MODx.panel.Login = function(config) {
	config = config || {};
	Ext.applyIf(config,{
	   title: _('login')
	   ,url: CONNECTORS_URL+'security/login.php'
	   ,baseParams: {
	   	
	   }
	   ,border: true
	   ,frame: true
	   ,collapsible: false
	   ,labelAlign: 'right'
	   ,buttonAlign: 'right'
	   ,defaults: { 
	       labelSeparator: ''
       }
	   ,items: [{
	       html: '<h2>'+SITE_NAME+'</h2>'
	       ,border: false
	   },{
	       html: _('login_message')+'<br /><br />'
	       ,border: false
	   },{
	       xtype: 'hidden'
	       ,name: 'login_context'
           ,id: 'modx-login-context'
	       ,value: 'mgr'
	   },{
	       xtype: 'textfield'
	       ,fieldLabel: _('login_username')
	       ,name: 'username'
	       ,el: 'modx-login-username'
           ,id: 'modx-login-username'
	   },{
	       xtype: 'textfield'
	       ,fieldLabel: _('login_password')
	       ,name: 'password'
	       ,el: 'modx-login-password'
           ,id: 'modx-login-password'
	       ,inputType: 'password'
	   },{
	       xtype: 'checkbox'
	       ,boxLabel: _('remember_username')
	       ,name: 'rememberme'
	       ,el: 'modx-login-rememberme'
           ,id: 'modx-login-rememberme'
           ,inputValue: true
	   },{
	   	   html: onManagerLoginFormRender
	   }]
	   ,buttons: [{
	       text: _('login_button')
           ,id: 'modx-login-button'
	       ,handler: this.submit
	       ,scope: this
	   }]
       ,renderTo: 'modx-login-form'
	   ,listeners: {
	   	   'success': {fn:function(o) {
	   	   	   location.href = (o.result.object.id !== undefined) ? './index.php?id=' + o.result.object.id : './';
	   	   },scope:this}
	   }
	   ,keys: [{
            key: Ext.EventObject.ENTER,
            fn: this.submit
            ,scope: this
        }]

	});
	MODx.panel.Login.superclass.constructor.call(this,config);
};
Ext.extend(MODx.panel.Login,MODx.FormPanel);
Ext.reg('modx-panel-login',MODx.panel.Login);