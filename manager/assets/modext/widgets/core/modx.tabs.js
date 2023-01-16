MODx.Tabs = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        enableTabScroll: true
        ,layoutOnTabChange: true
        ,plain: true
        ,deferredRender: true
        ,hideMode: 'offsets'
        ,defaults: {
            autoHeight: true
            ,hideMode: 'offsets'
            ,border: true
            ,autoWidth: true
            ,bodyCssClass: 'tab-panel-wrapper'
        }
        ,activeTab: 0
        ,border: false
        ,autoScroll: true
        ,autoHeight: true
        ,cls: 'modx-tabs'
    });
    MODx.Tabs.superclass.constructor.call(this,config);
    this.config = config;
    this.on({
        afterrender: function(tabPanel) {
            if (MODx.request && MODx.request.hasOwnProperty('tab')){
                const tabId = parseInt(MODx.request.tab);
                this.setActiveTab(tabId);
            }
            /* Placing listener here because we only want to listen after the initial panel has loaded */
            tabPanel.on({
                beforetabchange: function(tabPanel, newTab, currentTab) {
                    /*
                        Only proceed with the clearing process if the tab has changed.
                        This is needed to prevent clearing when a URL has been typed in.

                        NOTE: The currentTab is the previous one being navigated away from
                    */
                    if (newTab.id != currentTab.id) {
                        const resetVerticalTabPanelFilters = (currentTab.items.items[0] && currentTab.items.items[0].xtype == 'modx-vtabs') || currentTab.ownerCt.xtype == 'modx-vtabs',
                              changedBetweenVtabs = newTab.ownerCt.xtype == 'modx-vtabs' && currentTab.ownerCt.xtype == 'modx-vtabs'
                        ;
                        let itemsSource,
                            gridObj = null
                        ;
                        if (resetVerticalTabPanelFilters) {
                            itemsSource = changedBetweenVtabs ? currentTab.items : currentTab.items.items[0].activeTab.items ;
                        } else {
                            itemsSource = currentTab.items;
                        }
                        if (itemsSource.length > 0) {
                            gridObj = itemsSource.find(obj => {
                                return Object.entries(obj).find(([key, value]) => key == 'xtype' && value.includes('modx-grid'));
                            });
                        }
                        if (gridObj) {
                            const toolbar = gridObj.getTopToolbar(),
                                  filterIds = []
                            ;
                            if (toolbar && toolbar.items.items.length > 0) {
                                toolbar.items.items.forEach(cmp => {
                                    if (cmp.xtype && (cmp.xtype.includes('combo') || cmp.xtype == 'textfield') && cmp.itemId) {
                                        filterIds.push(cmp.itemId);
                                    }
                                });
                            }
                            if (filterIds.length > 0) {
                                gridObj.clearGridFilters(filterIds);
                            }
                        }
                    }
                }
            });
        }
    });
};
Ext.extend(MODx.Tabs,Ext.TabPanel);
Ext.reg('modx-tabs',MODx.Tabs);

MODx.VerticalTabs = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        cls: 'vertical-tabs-panel'
        ,headerCfg: { tag: 'div', cls: 'x-tab-panel-header vertical-tabs-header' }
        ,bwrapCfg: { tag: 'div', cls: 'x-tab-panel-bwrap vertical-tabs-bwrap' }
        ,defaults: {
            bodyCssClass: 'vertical-tabs-body'
            ,autoScroll: true
            ,autoHeight: true
            ,autoWidth: true
            ,layout: 'form'
        }
    });
    MODx.VerticalTabs.superclass.constructor.call(this,config);
    this.config = config;
    this.on('afterrender', function() {
        if (MODx.request && MODx.request.hasOwnProperty('vtab')){
            const tabId = parseInt(MODx.request.vtab);
            this.setActiveTab(tabId);
        }
    });
};
Ext.extend(MODx.VerticalTabs, MODx.Tabs);
Ext.reg('modx-vtabs',MODx.VerticalTabs);
