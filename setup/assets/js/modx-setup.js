Object.extend(FormHandler, {
    type: 'error'
    ,showError: function(e,t) {
        $('modx_error_content').setHTML(e);
        $('modx_error').setProperty('class',t || error);
        $('modx_error').setStyle('display', 'block');
        $('modx_error').effect('opacity',{
            duration: 500
        }).start(.9);
    }
    ,closeError: function() {
        $('modx_error').effect('opacity',{
            duration: 500,
            onComplete: function() { $('modx_error').setStyle('display', 'none'); }
        }).start(0);
    }
    ,errorJSON: function(e) {
        this.unhighlightFields();
        if (e == '') return this.showError('Unknown error processing request.');
        this.type = e.type;

        if (e.fields != null) {
            for (p=0;p<e.fields.length;p++) {
                this.highlightField(e.fields[p]);
            }
        }

        if (e.message == '') return this.showError('Unknown error processing request.');
        this.showError(e.message,e.type);
    }
});
var installHandler = function(r) {
    r = Json.evaluate(r);
    if (r.success) {
        goAction(r.message);
    } else {
        FormHandler.errorJSON(r);
    }
    return false;
}
var doAction = function(action) {
    return FormHandler.send($('install'), action, installHandler);
}
var goAction = function(action) {
    $('install').setProperty('action', 'index.php?action=' + action);
    $('install').submit();
    return false;
}