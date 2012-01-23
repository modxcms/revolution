Ext.onReady(function() {
    if (top.frames.length !== 0) {
        top.location=self.document.location;
    }
    Ext.override(Ext.form.Field,{
        defaultAutoCreate: {tag: "input", type: "text", size: "20", autocomplete: "on" }
    });    
    var fl = Ext.get('modx-fl-link');
    if (fl) { fl.on('click',MODx.loadFLForm); }

    var lu = Ext.get('modx-login-username');
    if (lu) { lu.focus(); }

    Ext.get('modx-login-language-select').on('change',function(e,cb) {
        var p = MODx.getURLParameters();
        p.cultureKey = cb.value;
        location.href = '?'+Ext.urlEncode(p);
    });
});

MODx.loadFLForm = function(a) {
    Ext.get('modx-fl-link').ghost().remove();
    Ext.get('modx-forgot-login-form').slideIn();
};