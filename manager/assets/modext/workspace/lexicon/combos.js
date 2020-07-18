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
        ,typeAhead: true
        ,minChars: 1
        ,editable: true
        ,allowBlank: true
        ,url: MODx.config.connector_url
        ,fields: ['name']
        ,displayField: 'name'
        ,valueField: 'name'
        ,baseParams: {
            action: 'Workspace/Lexicon/Topic/GetList'
            ,'namespace': 'core'
            ,'language': 'en'
        }
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
