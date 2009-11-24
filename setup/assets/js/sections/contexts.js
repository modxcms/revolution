Ext.onReady(function() {
    Ext.select('.modx-hidden').hide();
    MODx.ctx.init();
});

MODx.ctx = function() {
    
    return {
        init: function() {
            Ext.get('context_web_path').on('keydown',function() {
                Ext.get('context_web_path_toggle').set({ checked: true });
            });
            Ext.get('context_web_path_toggle').on('click',function() {
                if (!this.dom.checked) {
                    Ext.get('context_web_path').dom.value = MODx.context_web_path;
                }
            });
            
            Ext.get('context_web_url').on('keydown',function() {
                Ext.get('context_web_url_toggle').set({ checked: true });
            });
            Ext.get('context_web_url_toggle').on('click',function() {
                if (!this.dom.checked) {
                    Ext.get('context_web_url').dom.value = MODx.context_web_url;
                }
            });
            
            /* connectors */
            Ext.get('context_connectors_path').on('keydown',function() {
                Ext.get('context_connectors_path_toggle').set({ checked: true });
            });
            Ext.get('context_connectors_path_toggle').on('click',function() {
                if (!this.dom.checked) {
                    Ext.get('context_connectors_path').dom.value = MODx.context_connectors_path;
                }
            });
            
            Ext.get('context_connectors_url').on('keydown',function() {
                Ext.get('context_connectors_url_toggle').set({ checked: true });
            });
            Ext.get('context_connectors_url_toggle').on('click',function() {
                if (!this.dom.checked) {
                    Ext.get('context_connectors_url').dom.value = MODx.context_connectors_url;
                }
            });
            
            /* mgr */
            Ext.get('context_mgr_path').on('keydown',function() {
                Ext.get('context_mgr_path_toggle').set({ checked: true });
            });
            Ext.get('context_mgr_path_toggle').on('click',function() {
                if (!this.dom.checked) {
                    Ext.get('context_mgr_path').dom.value = MODx.context_mgr_path;
                }
            });
            
            Ext.get('context_mgr_url').on('keydown',function() {
                Ext.get('context_mgr_url_toggle').set({ checked: true });
            });
            Ext.get('context_mgr_url_toggle').on('click',function() {
                if (!this.dom.checked) {
                    Ext.get('context_mgr_url').dom.value = MODx.context_mgr_url;
                }
            });
            
            
            
        }
    };
}();