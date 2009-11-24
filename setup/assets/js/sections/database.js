Ext.onReady(function() {
    Ext.select('#modx-testconn').on('click',MODx.DB.testConnection);
    Ext.select('#modx-testcoll').on('click',MODx.DB.testCollation);
    Ext.select('.modx-hidden').hide();
});

MODx.DB = function() {
    return {
        testConnection: function() {
            Ext.Ajax.request({
               url: 'processors/connector.php'
               ,success: function(r) {
                    r = Ext.decode(r.responseText);
                    var msg = Ext.select('#modx-db-step1-msg');
                    msg.show();
                    Ext.select('#modx-db-step1-msg span').update(r.message);
                    if (r.success) {
                        Ext.select('#modx-db-step2').fadeIn();
                        
                        var ch = Ext.get('database-connection-charset');
                        if (ch) {
                            ch.set({value:r.object.charset});
                        }
                        
                        var c = Ext.get('database-collation');
                        if (c) {
                            c.update('');
                            if (r.object.collations) {
                                for (var i=0;i<r.object.collations.length;i++) {
                                    MODx.DB.optionTpl.append('database-collation',r.object.collations[i]);
                                }
                            } else {
                                MODx.DB.optionTpl.append('database-collation',{
                                    name: r.object.collation
                                    ,value: r.object.collation
                                });
                            }
                        }
                        msg.addClass('success');
                    } else {
                        msg.addClass('error');
                    }
               }
               ,scope: this
               ,params: { 
                    action: 'database/connection'
                    ,database_server: Ext.get('database-server').getValue()
                    ,database_user: Ext.get('database-user').getValue()
                    ,database_password: Ext.get('database-password').getValue()
                    ,dbase: Ext.get('dbase').getValue()
                    ,table_prefix: Ext.get('table-prefix').getValue()
                }
            });
        }
        
        ,testCollation: function() {
            var p = { action: 'database/collation' };
            
            var co = Ext.get('database-collation');
            if (co) { p.database_collation = co.getValue(); }
            
            var ca = Ext.get('database-connection-charset');
            if (ca) { p.database_connection_charset = ca.getValue(); }
            
            
            Ext.Ajax.request({
               url: 'processors/connector.php'
               ,success: function(r) {
                    r = Ext.decode(r.responseText);
                    var msg = Ext.select('#modx-db-step2-msg');
                    msg.show();
                    Ext.select('#modx-db-step2-msg span').update(r.message);
                    if (r.success) {
                        Ext.select('#modx-db-step3').fadeIn();
                        Ext.select('#modx-next').fadeIn();
                        msg.addClass('success');
                    } else {
                        msg.addClass('error');
                    }
               }
               ,scope: this
               ,params: p
            });
        }
        
        ,optionTpl: new Ext.Template('<option value="{value}"{selected}>{name}</option>')
    };
}();