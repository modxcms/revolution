Ext.onReady(function() {
    MODx.Distributions.packages = Ext.select('.packages');
    MODx.Distributions.packages.setVisibilityMode(Ext.Element.DISPLAY);
    MODx.Distributions.packages.hide();

    var distributions = Ext.select('.distribution input');
    distributions.on('change', MODx.Distributions.selectDistribution);

    Ext.select('#distribution').on('submit', function() {
        Ext.select('.setup_navbar input').hide();
    });
});

MODx.Distributions = function() {
    return {
        selectDistribution: function() {
            var el = Ext.getDom(this);
            var packages = el.getAttribute('data-packages');
            if (packages === '*') {
                MODx.Distributions.packages.show();
            }
            else {
                MODx.Distributions.packages.hide();
                packages = packages.split(',');
                var inputs = Ext.select('.packages input');
                inputs.each(function(i,v) {
                    var el = Ext.getDom(i);
                    el.checked = packages.indexOf(i.getAttribute('value')) !== -1;
                })
            }

        },
        packages: false,
    };
}();

