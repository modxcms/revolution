Ext.onReady(function(){
    MODx.Summary.init();
});

MODx.Summary = function() {
    return {
        init: function() {
            Ext.get('install').on('submit',function() {
                Ext.get('modx-next').setVisible(false);
            });
        }
    }
}();