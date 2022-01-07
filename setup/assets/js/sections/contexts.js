Ext.onReady(function() {
    Ext.select('.modx-hidden').hide();
    MODx.ctx.init();
});

MODx.ctx = function() {
    return {
        init: function() {
            var contextProperties = [
                'context_web_path',
                'context_web_url',
                'context_connectors_path',
                'context_connectors_url',
                'context_mgr_path',
                'context_mgr_url',
            ];

            function handlePropertyChange(propertyName, input, checkbox) {
                input.addEventListener('input', function() {
                    checkbox.checked = true;
                });

                checkbox.addEventListener('change', function(event) {
                    if (!event.target.checked) {
                        input.value = MODx[propertyName];
                    }
                });
            }

            for (var i = 0; i < contextProperties.length; i++) {
                var propertyName = contextProperties[i];
                var checkboxId = propertyName + '_toggle';

                handlePropertyChange(propertyName, document.getElementById(propertyName), document.getElementById(checkboxId));
            }
        }
    };
}();
