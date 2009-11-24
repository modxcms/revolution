Ext.onReady(function() {
});

var MODx = function() {
    Ext.Ajax.defaultHeaders = {
        'System': 'MODx'
    };
    return {
        go: function(action) {
            location.href = '?action='+action;
        }
    }
}();