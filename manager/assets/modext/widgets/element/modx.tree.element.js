/**
 * Generates the Element Tree
 *
 * @class MODx.tree.Element
 * @extends MODx.tree.Tree
 * @param {Object} config An object of options.
 * @xtype modx-tree-element
 */
MODx.tree.Element = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        rootVisible: false
        ,enableDD: !Ext.isEmpty(MODx.config.enable_dragdrop) ? true : false
        ,ddGroup: 'modx-treedrop-elements-dd'
        ,title: ''
        ,url: MODx.config.connector_url
        ,action: 'Element/GetNodes'
        ,sortAction: 'Element/Sort'
        ,baseParams: {
            currentElement: MODx.request.id || 0
            ,currentAction: MODx.request.a || 0
        }
    });
    MODx.tree.Element.superclass.constructor.call(this,config);
    this.on('afterSort',this.afterSort);
};
Ext.extend(MODx.tree.Element,MODx.tree.Tree,{
    forms: {}
    ,windows: {}
    ,stores: {}
    ,getToolbar: function() {
        return [];
    }
    ,createCategory: function(n,e) {
        var r = {};
        if (this.cm.activeNode && this.cm.activeNode.attributes.data) {
            r['parent'] = this.cm.activeNode.attributes.data.id;
        }

        var w = MODx.load({
            xtype: 'modx-window-category-create'
            ,record: r
            ,listeners: {
                success: {
                    fn: function() {
                        var node = (this.cm.activeNode) ? this.cm.activeNode.id : 'n_category'
                            ,self = node.indexOf('_category_') !== -1;
                        this.refreshNode(node, self);
                    }
                    ,scope: this
                }
                ,hide: {
                    fn: function() {
                        this.destroy();
                    }
                }
            }
        });
        w.show(e.target);
    }

    ,renameCategory: function(itm,e) {
        var r = this.cm.activeNode.attributes.data;
        var w = MODx.load({
            xtype: 'modx-window-category-rename'
            ,record: r
            ,listeners: {
                'success':{fn:function(r) {
                    var c = r.a.result.object;
                    var n = this.cm.activeNode;
                    n.setText(c.category+' ('+c.id+')');
                    Ext.get(n.getUI().getEl()).frame();
                    n.attributes.data.id = c.id;
                    n.attributes.data.category = c.category;
                    n.attributes.data.rank = c.rank;
                },scope:this}
                ,'hide':{fn:function() {this.destroy();}}
            }
        });
        w.show(e.target);
    }

    ,removeCategory: function(itm,e) {
        var id = this.cm.activeNode.attributes.data.id;
        MODx.msg.confirm({
            title: _('warning')
            ,text: _('category_confirm_delete')
            ,url: MODx.config.connector_url
            ,params: {
                action: 'Element/Category/Remove'
                ,id: id
            }
            ,listeners: {
                'success': {fn:function() {
                    this.cm.activeNode.remove();
                },scope:this}
            }
        });
    }

    ,duplicateElement: function(itm,e,id,type) {
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'element/' + type + '/get'
                ,id: id
            }
            ,listeners: {
                'success': {fn:function(results) {
                    var rec = {
                        id: id
                        ,type: type
                        ,name: _('duplicate_of',{name: this.cm.activeNode.attributes.name})
                        ,caption: _('duplicate_of',{name: this.cm.activeNode.attributes.caption})
                        ,category: results.object.category
                        ,source: results.object.source
                        ,static: results.object.static
                        ,static_file: results.object.static_file
                    };
                    var w = MODx.load({
                        xtype: 'modx-window-element-duplicate'
                        ,record: rec
                        ,redirect: false
                        ,listeners: {
                            'success': {
                                fn:function(r) {
                                    var response = Ext.decode(r.a.response.responseText);
                                    if (response.object.redirect) {
                                        MODx.loadPage('element/'+ rec.type +'/update', 'id='+ response.object.id);
                                    } else {
                                        this.refreshNode(this.cm.activeNode.id)
                                    };
                                },scope:this}
                            ,'hide':{fn:function() {this.destroy();}}
                        }
                    });
                    w.show(e.target);

                },scope:this}
            }
        });
    }

    /**
     * @property {Function} extractElementIdentifiersFromActiveNode Gets an Element's type, id, and category id from an active Node's id
     *
     * @param {Ext.tree.Node} activeNode The Node currently being acted upon
     * @return {Object} An object containing relevant identifiers of the Element this Node represents
     */
    ,extractElementIdentifiersFromActiveNode: function(activeNode) {
        let startIndex;
        const extractedData = {};

        switch (true) {
            // When creating Elements in the root of their tree
            case activeNode.id.indexOf('n_type_') === 0:
                startIndex = 7;
                break;
            // When altering or removing an Element from within the Categories tree
            case activeNode.id.indexOf('n_c_') === 0:
                startIndex = 4;
                break;
            default:
                startIndex = 2;
        }
        const identifiers = activeNode.id.substr(startIndex).split('_');

        /*
            Expected array items:
            - When working in the Categories tree: [element type, node type ('element'), element id, element's category id]
            - When working in any of the five Element trees: [element type, node type ('category'), element's category id]
            - When creating and Element in the root of it's type's tree: [element type]
        */

        [extractedData.type] = identifiers;

        switch (identifiers.length) {
            case 4:
                return {
                    ...extractedData,
                    elementId: parseInt(identifiers[2], 10),
                    categoryId: parseInt(identifiers[3], 10)
                };
            case 3:
                return {
                    ...extractedData,
                    categoryId: parseInt(identifiers[2], 10)
                };
            case 1:
                return extractedData;
            // no default
        }
        return false;
    }

    ,removeElement: function(itm, e) {
        const elementIdentifiers = this.extractElementIdentifiersFromActiveNode(this.cm.activeNode);
        MODx.msg.confirm({
            title: _('warning'),
            text: _('remove_this_confirm', {
                type: _(elementIdentifiers.type),
                name: this.cm.activeNode.attributes.name
            }),
            url: MODx.config.connector_url,
            params: {
                action: `element/${elementIdentifiers.type}/remove`,
                id: elementIdentifiers.elementId
            },
            listeners: {
                success: {
                    fn: function() {
                        this.cm.activeNode.remove();
                        /* if editing the element being removed */
                        if (
                            MODx.request.a === `element/${elementIdentifiers.type}/update`
                            && parseInt(MODx.request.id, 10) === elementIdentifiers.elementId
                        ) {
                            MODx.loadPage('welcome');
                        }
                    },
                    scope: this
                }
            }
        });
    }

    ,activatePlugin: function(itm, e) {
        const elementIdentifiers = this.extractElementIdentifiersFromActiveNode(this.cm.activeNode);
        MODx.Ajax.request({
            url: MODx.config.connector_url,
            params: {
                action: 'Element/Plugin/Activate',
                id: elementIdentifiers.elementId
            },
            listeners: {
                success: {
                    fn: function() {
                        this.refreshParentNode();
                    },
                    scope: this
                }
            }
        });
    }

    ,deactivatePlugin: function(itm, e) {
        const elementIdentifiers = this.extractElementIdentifiersFromActiveNode(this.cm.activeNode);
        MODx.Ajax.request({
            url: MODx.config.connector_url,
            params: {
                action: 'Element/Plugin/Deactivate',
                id: elementIdentifiers.elementId
            },
            listeners: {
                success: {
                    fn: function() {
                        this.refreshParentNode();
                    },
                    scope: this
                }
            }
        });
    }

    ,quickCreate: function(itm,e,type) {
        var r = {
            category: this.cm.activeNode.attributes.pk || ''
        };
        var w = MODx.load({
            xtype: 'modx-window-quick-create-'+type
            ,record: r
            ,listeners: {
                success: {
                    fn: function() {
                        this.refreshNode(this.cm.activeNode.id, true);
                    }
                    ,scope: this
                }
                ,hide: {
                    fn: function() {
                        this.destroy();
                    }
                }
            }
        });
        w.setValues(r);
        w.show(e.target);
    }

    ,quickUpdate: function(itm,e,type) {
        MODx.Ajax.request({
            url: MODx.config.connector_url
            ,params: {
                action: 'element/'+type+'/get'
                ,id: this.cm.activeNode.attributes.pk
            }
            ,listeners: {
                'success': {fn:function(r) {
                    var nameField = (type == 'template') ? 'templatename' : 'name';
                    var w = MODx.load({
                        xtype: 'modx-window-quick-update-'+type
                        ,record: r.object
                        ,listeners: {
                            'success':{fn:function(r) {
                                this.refreshNode(this.cm.activeNode.id);
                                var newTitle = '<span dir="ltr">' + r.f.findField(nameField).getValue() + ' (' + w.record.id + ')</span>';
                                w.setTitle(w.title.replace(/<span.*\/span>/, newTitle));
                            },scope:this}
                            ,'hide':{fn:function() {this.destroy();}}
                        }
                    });
                    w.title += ': <span dir="ltr">' + w.record[nameField] + ' ('+ w.record.id + ')</span>';
                    w.setValues(r.object);
                    w.show(e.target);
                },scope:this}
            }
        });
    }

    ,_createElement: function(itm, e, t) {
        const elementIdentifiers = this.extractElementIdentifiersFromActiveNode(this.cm.activeNode);
        this.redirect(`?a=element/${elementIdentifiers.type}/create&category=${elementIdentifiers.categoryId}`)
        this.cm.hide();
        return false;
    }

    ,afterSort: function(o) {
        var tn = o.event.target.attributes;
        if (tn.type == 'category') {
            var dn = o.event.dropNode.attributes;
            if (tn.id != 'n_category' && dn.type == 'category') {
                o.event.target.expand();
            } else {
                this.refreshNode(o.event.target.attributes.id,true);
                this.refreshNode('n_type_'+o.event.dropNode.attributes.type,true);
            }
        }
    }

    ,_handleDrop: function(e) {
        var target = e.target;
        if (e.point == 'above' || e.point == 'below') {return false;}
        if (target.attributes.classKey != 'MODX\\Revolution\\modCategory' && target.attributes.classKey != 'root') { return false; }

        if (!this.isCorrectType(e.dropNode,target)) {return false;}
        if (target.attributes.type == 'category' && e.point == 'append') {return true;}

        return target.getDepth() > 0;
    }

    ,isCorrectType: function(dropNode,targetNode) {
        var r = false;
        /* types must be the same */
        if(targetNode.attributes.type == dropNode.attributes.type) {
            /* do not allow anything to be dropped on an element */
            if(!(targetNode.parentNode &&
                ((dropNode.attributes.cls == 'folder'
                && targetNode.attributes.cls == 'folder'
                && dropNode.parentNode.id == targetNode.parentNode.id
                ) || targetNode.attributes.cls == 'file'))) {
                r = true;
            }
        }
        return r;
    }


    /**
     * Shows the current context menu.
     * @param {Ext.tree.TreeNode} n The current node
     * @param {Ext.EventObject} e The event object run.
     */
    ,_showContextMenu: function(n,e) {
        this.cm.activeNode = n;
        this.cm.removeAll();
        if (n.attributes.menu && n.attributes.menu.items) {
            this.addContextMenuItem(n.attributes.menu.items);
            this.cm.show(n.getUI().getEl(),'t?');
        } else {
            var m = [];
            switch (n.attributes.classKey) {
                case 'root':
                    m = this._getRootMenu(n);
                    break;
                case 'MODX\\Revolution\\modCategory':
                    m = this._getCategoryMenu(n);
                    break;
                default:
                    m = this._getElementMenu(n);
                    break;
            }

            this.addContextMenuItem(m);
            this.cm.showAt(e.xy);
        }
        e.stopEvent();
    }

    ,_getQuickCreateMenu: function(n,m) {
        var ui = n.getUI();
        var mn = [];
        var types = ['template','tv','chunk','snippet','plugin'];
        var t;
        for (var i=0;i<types.length;i++) {
            t = types[i];
            if (ui.hasClass('pnew_'+t)) {
                mn.push({
                    text: _(t)
                    ,scope: this
                    ,type: t
                    ,handler: function(itm,e) {
                        this.quickCreate(itm,e,itm.type);
                    }
                });
            }
        }
        if (mn.length > 0) {
            m.push({
                text: _('quick_create')
                , handler: function () {
                    return false;
                }
                , menu: {
                    items: mn
                }
            });
        }
        return m;
    }

    ,_getElementMenu: function(n) {
        var a = n.attributes;
        var ui = n.getUI();
        var m = [];

        m.push({
            text: '<b>'+a.text+'</b>'
            ,handler: function() { return false; }
            ,header: true
        });
        m.push('-');

        if (ui.hasClass('pedit')) {
            m.push({
                text: _('edit_'+a.type)
                ,type: a.type
                ,pk: a.pk
                ,handler: function(itm,e) {
                    MODx.loadPage('element/'+itm.type+'/update',
                        'id='+itm.pk);
                }
            });
            m.push({
                text: _('quick_update_'+a.type)
                ,type: a.type
                ,handler: function(itm,e) {
                    this.quickUpdate(itm,e,itm.type);
                }
            });
            if (a.classKey === 'MODX\\Revolution\\modPlugin') {
                if (a.active) {
                    m.push({
                        text: _('plugin_deactivate')
                        ,type: a.type
                        ,handler: this.deactivatePlugin
                    });
                } else {
                    m.push({
                        text: _('plugin_activate')
                        ,type: a.type
                        ,handler: this.activatePlugin
                    });
                }
            }
        }
        if (ui.hasClass('pnew')) {
            m.push({
                text: _('duplicate_'+a.type)
                ,pk: a.pk
                ,type: a.type
                ,handler: function(itm,e) {
                    this.duplicateElement(itm,e,itm.pk,itm.type);
                }
            });
        }
        if (ui.hasClass('pdelete')) {
            m.push('-');
            m.push({
                text: _('remove_'+a.type)
                ,handler: this.removeElement
            });
        }
        m.push('-');
        if (ui.hasClass('pnew')) {
            m.push({
                text: _('add_to_category_'+a.type)
                ,handler: this._createElement
            });
        }
        if (ui.hasClass('pnewcat')) {
            m.push({
                text: _('category_create')
                ,handler: this.createCategory
            });
        }
        return m;
    }

    ,_getCategoryMenu: function(n) {
        var a = n.attributes;
        var ui = n.getUI();
        var m = [];

        m.push({
            text: '<b>'+a.text+'</b>'
            ,handler: function() { return false; }
            ,header: true
        });
        m.push('-');
        if (ui.hasClass('pnewcat')) {
            m.push({
                text: _('category_create')
                ,handler: this.createCategory
            });
        }
        if (ui.hasClass('peditcat')) {
            m.push({
                text: _('category_rename')
                ,handler: this.renameCategory
            });
        }
        if (m.length > 2) {m.push('-');}

        if (ui.hasClass('pnew_' + a.type)) {
            m.push({
                text: _('add_to_category_'+a.type)
                ,handler: this._createElement
            });
        }
        this._getQuickCreateMenu(n,m);

        if (ui.hasClass('pdelcat')) {
            m.push('-');
            m.push({
                text: _('category_remove')
                ,handler: this.removeCategory
            });
        }
        return m;
    }

    ,_getRootMenu: function(n) {
        var a = n.attributes;
        var ui = n.getUI();
        var m = [];

        if (ui.hasClass('pnew')) {
            m.push({
                text: _('new_'+a.type)
                ,handler: this._createElement
            });
            m.push({
                text: _('quick_create_'+a.type)
                ,type: a.type
                ,handler: function(itm,e) {
                    this.quickCreate(itm,e,itm.type);
                }
            });
        }

        if (ui.hasClass('pnewcat')) {
            if (ui.hasClass('pnew')) {m.push('-');}
            m.push({
                text: _('category_create')
                ,handler: this.createCategory
            });
        }

        if (n.isLoaded()) {
            var childNodes = n.childNodes;

            if (childNodes.some(function(child) { return !child.leaf; })) { // If any childNode has own children
                m.push('-');

                if (n.isExpanded() && childNodes.some(function(child) { return child.isExpanded(); })) { // If any childNode is expanded
                    m.push({
                        text: _('collapse_all')
                        ,handler: function() {
                            n.collapseChildNodes();
                        }
                    });
                }

                m.push({
                    text: _('expand_all')
                    ,handler: function() {
                        if (n.isExpandable()) {
                            n.expand(true);
                        }
                    }
                });
            }
        }

        return m;
    }

    ,handleCreateClick: function(node){
        this.cm.activeNode = node;
        var type = this.cm.activeNode.id.substr(2).split('_');
        if (type[0] != 'category') {
            this._createElement(null, null, null);
        } else {
            this.createCategory(null, {target: this});
        }
    }
});
Ext.reg('modx-tree-element',MODx.tree.Element);
