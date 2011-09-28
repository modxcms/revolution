Ext.onReady(function(){
    MODx.Welcome.init();
});

MODx.Welcome = function() {
    return {
        init: function() {
            var div = Ext.get('cck-div');
            if(div !== null) {
                div.setVisibilityMode(Ext.Element.DISPLAY);
                div.setVisible(false);
                Ext.get('cck-href').on('click',function() {
                    div.toggle();
                });
            }
        }
    }
}();