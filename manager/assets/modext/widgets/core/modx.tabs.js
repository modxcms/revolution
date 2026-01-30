/**
 * Override of onStripMouseDown (private method) made to update new 'tabClicked' property when
 * a tab is clicked. There is no built-in click event for the Ext.TabPanel component and adding one
 * an instance listener (via a vanilla addEventListener) it ends up firing the event too late in the chain
 * of events. Thus, notification of tab clicks is made in this way (which sets the 'tabClicked' value
 * very early in the process).
 */
Ext.override(Ext.TabPanel, {
    onStripMouseDown: function(e) {
        if (e.button !== 0) {
            return;
        }
        e.preventDefault();
        const t = this.findTargets(e);
        if (t.close) {
            if (t.item.fireEvent('beforeclose', t.item) !== false) {
                t.item.fireEvent('close', t.item);
                this.remove(t.item);
            }
            return;
        }
        if (t.item && t.item !== this.activeTab) {
            this.tabClicked = true;
            this.setActiveTab(t.item);
        }
    }
});

MODx.Tabs = function(config = {}) {
    Ext.applyIf(config, {
        enableTabScroll: true,
        layoutOnTabChange: true,
        plain: true,
        deferredRender: true,
        hideMode: 'offsets',
        defaults: {
            autoHeight: true,
            hideMode: 'offsets',
            border: true,
            autoWidth: true,
            bodyCssClass: 'tab-panel-wrapper'
        },
        activeTab: 0,
        tabClicked: false,
        border: false,
        autoScroll: true,
        autoHeight: true,
        cls: 'modx-tabs',
        itemTpl: new Ext.XTemplate(
            '<li class="{cls}" id="{id}">',
                '<a class="x-tab-strip-close"></a>',
                '<a href="#">',
                    '<span class="x-tab-strip-text">{text}</span>',
                '</a>',
            '</li>'
        )
    });
    MODx.Tabs.superclass.constructor.call(this, config);
    this.config = config;
    this.on({
        afterrender: function(tabPanel) {
            if (MODx.request && Object.prototype.hasOwnProperty.call(MODx.request, 'tab')) {
                const tabId = parseInt(MODx.request.tab, 10);
                // Ensure tab panels other than the main one are unaffected
                if (this.id !== 'modx-leftbar-tabpanel') {
                    this.setActiveTab(tabId);
                }
            }

            /* Placing listener here because we only want to listen after the initial panel has loaded */
            tabPanel.on({
                beforetabchange: function(tabPanelCmp, newTab, currentTab) {
                    /*
                        Only proceed with the clearing process if the tab has changed (via click).
                        This is needed to prevent clearing when a URL has been typed in.

                        NOTE: The currentTab is the previous one being navigated away from
                    */
                    if (this.tabClicked && newTab && currentTab && newTab.id !== currentTab.id) {
                        const resetVerticalTabPanelFilters = (currentTab.items?.items[0]?.xtype === 'modx-vtabs') || currentTab.ownerCt?.xtype === 'modx-vtabs',
                              changedBetweenVtabs = newTab.ownerCt?.xtype === 'modx-vtabs' && currentTab.ownerCt?.xtype === 'modx-vtabs'
                        ;
                        /*
                            When navigating back to Access Permissions and the TabPanel is not stateful,
                            ensure that the first vertical tab is activated
                        */
                        if (newTab.itemId === 'modx-usergroup-permissions-panel' && !this.stateful) {
                            const vTabPanel = newTab.items?.items[0];
                            if (vTabPanel && vTabPanel.xtype === 'modx-vtabs') {
                                vTabPanel.setActiveTab(0);
                            }
                        }
                        this.clearFiltersBeforeChange(currentTab, resetVerticalTabPanelFilters, changedBetweenVtabs);
                    }
                }
            });
        }
    });
};
Ext.extend(MODx.Tabs, Ext.TabPanel, {
    /**
     * Search for and return a grid object based on a given items array
     *
     * @param {String} itemsSource - The config items array to search within
     * @return {MODx.grid.Grid|undefined}
     */
    findGridObject: function(itemsSource) {
        const grid = itemsSource.find(obj => Object.entries(obj).find(([key, value]) => key === 'xtype' && value.includes('-grid-')));
        if (grid) {
            return grid;
        }
        const nextItemsSource = itemsSource?.items;
        if (nextItemsSource) {
            this.findGridObject(nextItemsSource);
        }
        return undefined;
    },

    /**
     * Sets a TabPanel grid and its toolbar to their default states
     *
     * @param {MODx.TabPanel} tabObj The tab panel containing filters and grid query to clear
     * @param {Boolean} resetVtabFilters Whether the targeted tab for clearing is a vertical tab
     * @param {Boolean} changedVtabs Whether both tab being moved away from and tab that is the current target are vertical tabs
     */
    clearFiltersBeforeChange: function(tabObj, resetVtabFilters, changedVtabs) {
        let itemsSource,
            gridObj = null
        ;
        if (resetVtabFilters) {
            itemsSource = changedVtabs
                ? tabObj.items
                : tabObj.items.items[0].activeTab.items
            ;
        } else {
            itemsSource = tabObj.items;
        }
        if (itemsSource.length > 0) {
            gridObj = this.findGridObject(itemsSource);

            // Grids placed in an atypical structure require further searching
            if (!gridObj) {
                let customItemsSource = null;
                if (itemsSource?.map['modx-tree-panel-usergroup']) {
                    // ACLs User Group grid that is activated via the User Groups tree
                    customItemsSource = itemsSource.map['modx-tree-panel-usergroup'].items;
                } else if (itemsSource?.map['packages-breadcrumbs']) {
                    // (Installed) Packages grid
                    customItemsSource = itemsSource.map['card-container'].items.map['modx-panel-packages'].items;
                }
                if (customItemsSource) {
                    gridObj = this.findGridObject(customItemsSource);
                }
            }
        }
        if (gridObj) {
            const
                toolbar = gridObj.getTopToolbar(),
                filterIds = []
            ;
            if (toolbar && toolbar.items.items.length > 0) {
                toolbar.items.items.forEach(cmp => {
                    if (cmp.xtype && (cmp.xtype.includes('combo') || cmp.xtype === 'textfield') && cmp.itemId) {
                        filterIds.push(cmp.itemId);
                    }
                });
            }
            if (filterIds.length > 0) {
                gridObj.clearGridFilters(filterIds);
            }
        }
    }
});
Ext.reg('modx-tabs', MODx.Tabs);

MODx.VerticalTabs = function(config = {}) {
    Ext.applyIf(config, {
        cls: 'vertical-tabs-panel',
        headerCfg: { tag: 'div', cls: 'x-tab-panel-header vertical-tabs-header' },
        bwrapCfg: { tag: 'div', cls: 'x-tab-panel-bwrap vertical-tabs-bwrap' },
        defaults: {
            bodyCssClass: 'vertical-tabs-body',
            autoScroll: true,
            autoHeight: true,
            autoWidth: true,
            layout: 'form'
        }
    });
    MODx.VerticalTabs.superclass.constructor.call(this, config);
    this.config = config;
    this.on({
        afterrender: function() {
            if (MODx.request && Object.prototype.hasOwnProperty.call(MODx.request, 'vtab')) {
                const tabId = parseInt(MODx.request.vtab, 10);
                this.setActiveTab(tabId);
            }
        }
    });
};
Ext.extend(MODx.VerticalTabs, MODx.Tabs);
Ext.reg('modx-vtabs', MODx.VerticalTabs);
