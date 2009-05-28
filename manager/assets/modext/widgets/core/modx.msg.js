/**
 * Abstraction for Ext.Msg, adds connector handling ability
 * and spotlight features.
 *  
 * @class MODx.msg
 * @extends Ext.Component
 * @param {Object} config An object of configuration properties
 * @xtype modx-msg
 */
MODx.Msg = function(config) {
    config = config || {};
    
    this.sl = new Ext.Spotlight({
        easing: 'easeOut'
        ,duration: '.3'
    });
    MODx.Msg.superclass.constructor.call(this,config);
    this.addEvents({
        'success': true
        ,'failure': true
        ,'cancel': true
    });
};
Ext.extend(MODx.Msg,Ext.Component,{
    /**
     * @var {Ext.Spotlight} sl The spotlight object
     * @access private
     */
    sl: null
    
    /**
     * Loads a confirm dialog that, if proceeding, will post to a connector.
     * 
     * @access public
     * @param {Object} options An object of options to initialize with.
     */
    ,confirm: function(config) {
    	this.purgeListeners();
    	if (config.listeners) {
    		for (var i in config.listeners) {
    		  var l = config.listeners[i];
    		  this.addListener(i,l.fn,l.scope || this,l.options || {});
    		}
    	}
        Ext.Msg.confirm(config.title || _('warning'),config.text,function(e) {
            this.sl.hide();
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
        this.sl.show(this.getWindow().getEl());
    }
    
    /**
     * Gets the Ext.Window being shown
     *
     * @access public
     * @return {Ext.Window} The window of the dialog
     */
    ,getWindow: function() {
        return Ext.Msg.getDialog();
    }
    
    /**
     * Displays a spotlighted alert box
     * 
     * @access public
     */
    ,alert: function(title,text,fn,scope) {
        scope = scope || this;
        if (typeof(fn) != 'function') {
            fn = function() { this.sl.hide(); };
        } else {
            fn = fn.createInterceptor(function() { this.sl.hide(); return true; },this);
        }
        Ext.Msg.alert(title,text,fn,scope);
        this.sl.show(this.getWindow().getEl());
    }
});
Ext.reg('modx-msg',MODx.Msg);