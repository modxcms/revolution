/**
 * Displays a dropdown list of available Lexicon Topics. Requires a namespace.
 * 
 * @class MODx.combo.LexiconTopic
 * @extends MODx.combo.ComboBox
 * @param {Object} config An object of config properties
 * @xtype modx-combo-lexicon-topic
 */
MODx.combo.LexiconTopic = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        name: 'topic'
        ,hiddenName: 'topic'
        ,forceSelection: true
        ,typeAhead: false
        ,editable: false
        ,allowBlank: false
        ,listWidth: 300
        ,url: MODx.config.connectors_url+'workspace/lexicon/topic.php'
        ,fields: ['name']
        ,displayField: 'name'
        ,valueField: 'name'
        ,baseParams: {
            action: 'getList'
            ,'namespace': 'core'
            ,'language': 'en'
        }
        ,pageSize: 20
    });
    MODx.combo.LexiconTopic.superclass.constructor.call(this,config);
};
Ext.extend(MODx.combo.LexiconTopic,MODx.combo.ComboBox,{
    setNamespace: function(ns,t) {
        this.store.baseParams['namespace'] = ns;
        this.store.load({
            callback: function() {
                if (t) { this.setValue(t); }
            }
            ,scope: this
        });
    }
    ,setLanguage: function(ns,t) {
        this.store.baseParams['language'] = ns;
        this.store.load({
            callback: function() {
                if (t) { this.setValue(t); }
            }
            ,scope: this
        });
    }
});
Ext.reg('modx-combo-lexicon-topic',MODx.combo.LexiconTopic);