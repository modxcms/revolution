/**
 * Automatically sends forms through AJAX calls, returns the result
 * (and parses any JS script within response), and if not TRUE, then
 * outputs that response to an 'errormsg' div. Also allows you to
 * specify the ?action= parameter in _GET, which utilitizes
 * PHP connectors to access their respective processor files.
 *  
 * @class MODx.form.Handler
 * @extends Ext.Component
 * @param {Object} config An object of configuration properties
 * @xtype modx-form-handler
 */
MODx.form.Handler = function(config) {
    config = config || {};
    MODx.form.Handler.superclass.constructor.call(this,config);
};
Ext.extend(MODx.form.Handler,Ext.Component,{
    fields: []
    /**
     * Sends the request to the connector. Use Ext.Ajax instead from now on.
     * @param {String,Object} fid The form ID
     * @param {String} a The action for the connector.
     * @param {Function} h An optional callback function.
     * @param {Object} The scope to execute the handler in
     * @deprecated
     */ 
    ,send: function(fid,a,h,scope) {
        var Frm = Ext.get(fid);
        this.unhighlightFields();
        
        Ext.Ajax.request({
            url: Frm.dom.action+'?action='+a
            ,params: Ext.Ajax.serializeForm(fid)
            ,method: 'post'
            ,scope: scope || this
            ,callback: h === null ? this.handle : h
        });
        return false;
    }
    
    /**
     * Default handler for Ajax responses.
     * @param {Object} o The options for the Ajax request.
     * @param {Object} s Whether or not the Ajax request succeeded.
     * @param {Object} r The xhr response.
     */
    ,handle: function(o,s,r) {
        r = Ext.decode(r.responseText);
        if (!r.success) {
            this.showError(r.message);
            return false;
        }
        return true;
    }
    
    ,highlightField: function(f) {
        if (f.id !== undefined && f.id !== 'forEach' && f.id !== '') {
            Ext.get(f.id).dom.style.border = '1px solid red';
            var ef = Ext.get(f.id+'_error');
            if (ef) { ef.innerHTML = f.msg; }
            this.fields.push(f.id);
        }
    }
    
    ,unhighlightFields: function() {
        for (var i=0;i<this.fields.length;i=i+1) {
            Ext.get(this.fields[i]).dom.style.border = '';
            var ef = Ext.get(this.fields[i]+'_error');
            if (ef) { ef.innerHTML = ''; }
        }
        this.fields = [];
    }
    
    ,errorJSON: function(e) {
        if (e === '') { return this.showError(e); }
        if (e.data !== null) {
            for (var p=0;p<e.data.length;p=p+1) {
                this.highlightField(e.data[p]);
            }
        }

        this.showError(e.message);
        return false;
    }
    
    ,errorExt: function(r,frm) {
        this.unhighlightFields();
        if (r.errors !== null && frm) {
            frm.markInvalid(r.errors);
        }
        if (r.message !== undefined && r.message !== '') { 
            this.showError(r.message);
        } else {
            MODx.msg.hide();    
        }
        return false;
    }
    
    ,showError: function(e) {
        if (e === '') {
            MODx.msg.hide();
        } else {
            MODx.msg.alert(_('error'),e,Ext.emptyFn);
        }
    }
    
    ,closeError: function() {
        MODx.msg.hide();
    }
});
Ext.reg('modx-form-handler',MODx.form.Handler);