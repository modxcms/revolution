/**
 * Abstraction for Ext.Msg, adds connector handling ability.
 *  
 * @class MODx.msg
 * @extends Ext.Component
 * @param {Object} config An object of configuration properties
 * @xtype modx-msg
 */
MODx.Msg = function(config) {
    config = config || {};
    MODx.Msg.superclass.constructor.call(this,config);
    this.addEvents({
        'success': true
        ,'failure': true
        ,'cancel': true
    });
};
Ext.extend(MODx.Msg,Ext.Component,{
    confirm: function(config) {
    	this.purgeListeners();
    	if (config.listeners) {
    		for (var i in config.listeners) {
    		  var l = config.listeners[i];
    		  this.addListener(i,l.fn,l.scope || this,l.options || {});
    		}
    	}
        Ext.Msg.confirm(config.title || _('warning'),config.text,function(e) {
            if (e == 'yes') {
                MODx.Ajax.request({
                    url: config.url
                    ,params: config.params || {}
                    ,method: 'post'
                    ,scope: this
                    ,listeners: {
                    	'success':{fn:function(r) {
                    		this.fireEvent('success',r);
                    	},scope:this}
                    	,'failure':{fn:function(r) {
                    		return this.fireEvent('failure',r);
                    	},scope:this}
                    }
                });
            } else {
            	this.fireEvent('cancel',config);
            }
        },this);
    }
    
    ,getWindow: function() {
        return Ext.Msg.getDialog();
    }
    
    ,alert: function(title,text,fn,scope) {
        fn = fn || Ext.emptyFn;
        scope = scope || this;
        Ext.Msg.alert(title,text,fn,scope);
    }
});
Ext.reg('modx-msg',MODx.Msg);