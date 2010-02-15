Ext.onReady(function(){
    MODx.Install.init();
});

MODx.Install = function() {
    return {
        init: function() {            
            Ext.select('.modx-toggle-success').on('click',function() {
                MODx.Install.toggle('success');
            });
            Ext.select('.modx-toggle-warning').on('click',function() {
                MODx.Install.toggle('warning');
            });
        }
        
        ,toggle: function(type) {
            var es = Ext.select('.'+type);
            es.setVisibilityMode(Ext.Element.DISPLAY);
            es.toggle();
        }
    }
}();