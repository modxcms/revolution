/**
 * Loads the help page
 * 
 * @class MODx.page.Help
 * @extends MODx.Component
 * @param {Object} config An object of config properties
 * @xtype modx-page-help
 */


MODx.page.Help = function(config) {
    config = config || {};

    // Hijack the html content
    var content = document.getElementById('modx-page-help-content');
    var contentHTML = content.innerHTML;
    content.parentNode.removeChild(content);


    Ext.applyIf(config,{
        padding: 0
        ,margin: 0
        ,components: [{
            xtype: 'modx-panel'
            ,style: {
                margin: 0
            }
            ,padding: 10
            ,margin:0
            ,renderTo: 'help-content-here-people'
            ,html: contentHTML
            ,cls: 'nobg'
            ,listeners: {
                afterrender: {fn: this.onReady,scope:this}
            }
        }]
    });
    MODx.page.Help.superclass.constructor.call(this,config);

};
Ext.extend(MODx.page.Help,MODx.Component,{

    /**
     * Fires once the page is rendered, and ready to accept jQuery shiznit
     */
    onReady: function(){

        /**
         * StripeCheckout support popups
         */
        (function($){
            $('.supportTicket').click(function (e) {

                e.preventDefault();

                var token = function (res) {
                    var $input = $('<input type=hidden name=stripeToken />').val(res.id);
                    $('form').append($input).submit();
                };

                StripeCheckout.open({
                    key: 'pk_test_hT0zzA6jxhqLhyxltfU61Ld3',
                    address: false,
                    amount: 30000,
                    currency: 'usd',
                    name: _('support_ticket_title') || 'Support Ticket title missing',
                    description: _('support_ticket_subtitle') || 'Support ticket subtitle missing',
                    panelLabel: _('support_ticket_button') || 'Click me!',
                    token: token
                });

                return false;
            });
        })(jQuery);

    }




});
Ext.reg('modx-page-help',MODx.page.Help);