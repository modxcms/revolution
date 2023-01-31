Ext.onReady(function() {
});

var MODx = function() {
    Ext.Ajax.defaultHeaders = {
        'System': 'MODx'
    };
    return {
        go: function(action) {
            window.location.href = '?action='+action;
        }
    }
}();
