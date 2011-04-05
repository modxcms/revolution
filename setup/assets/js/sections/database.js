Ext.onReady(function() {
    Ext.select('#modx-testconn').on('click',MODx.DB.testConnection);
    Ext.select('#modx-testcoll').on('click',MODx.DB.testCollation);

    Ext.select('#modx-db-info').hide();
    var es = Ext.select('.modx-hidden2');
    es.setVisibilityMode(Ext.Element.DISPLAY);
    es.hide();
    if (!MODx.showHidden) {
        var ez = Ext.select('.modx-hidden');
        ez.setVisibilityMode(Ext.Element.DISPLAY);
        ez.hide();
    }
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
                    if (r.success) {
                        if (r.object.client_version) {
                            Ext.select('#modx-db-info').show();
                            var cv = Ext.select('#modx-db-client-version');
                            if (r.object.client_version_result != 'success') {
                                cv.addClass('warning');
                            } else {
                                cv.addClass('success');
                            }
                            cv.update('&nbsp;'+r.object.client_version_msg);

                            var sv = Ext.select('#modx-db-server-version');
                            if (r.object.server_version_result != 'success') {
                                sv.addClass('warning');
                            } else {
                                sv.addClass('success');
                            }
                            sv.update('&nbsp;'+r.object.server_version_msg);
                        }
                        Ext.select('#modx-db-step1-msg span.connect-msg').update(r.message);
                        Ext.select('#modx-db-step2').fadeIn();                   
                        
                        var ch = Ext.get('database-connection-charset');
                        if (ch) {
                            ch.update('');
                            if (r.object.charsets) {
                                for (var i=0;i<r.object.charsets.length;i++) {
                                    MODx.DB.optionTpl.append('database-connection-charset',r.object.charsets[i]);
                                }
                            } else {
                                MODx.DB.optionTpl.append('database-connection-charset',{
                                    name: r.object.charset
                                    ,value: r.object.charset
                                });
                            }
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
                        var errorMsg = '&nbsp;<br />'+r.message+'<br />';
                        if (r.object) {
                            for (var i=0;i<r.object.length;i++) {
                                errorMsg = errorMsg + '<br />' + r.object[i] + '<br />';
                            }
                        }
                        Ext.select('#modx-db-step1-msg span.connect-msg').update(errorMsg);
                        msg.addClass('error');
                    }
               }
               ,scope: this
               ,params: { 
                    action: 'database/connection'
                    ,database_type: Ext.get('database-type').getValue()
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
                    Ext.select('#modx-db-step2-msg span.result').update(r.message);
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