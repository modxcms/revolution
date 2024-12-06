Ext.namespace('MODx.util.Progress');
Ext.namespace('MODx.util.Format');
/**
 * A JSON Reader specific to MODExt
 *
 * @class MODx.util.JSONReader
 * @extends Ext.util.JSONReader
 * @param {Object} config An object of configuration properties
 * @xtype modx-json-reader
 */
MODx.util.JSONReader = function(config = {}) {
    Ext.applyIf(config, {
        successProperty: 'success',
        totalProperty: 'total',
        root: 'data'
    });
    MODx.util.JSONReader.superclass.constructor.call(this, config, ['id', 'msg']);
};
Ext.extend(MODx.util.JSONReader, Ext.data.JsonReader);
Ext.reg('modx-json-reader', MODx.util.JSONReader);

/**
 * @class MODx.util.Progress
 */
MODx.util.Progress = {
    id: 0,
    time: function(v, id, msg) {
        msg = msg || _('saving');
        if (MODx.util.Progress.id === id && v < 11) {
            Ext.MessageBox.updateProgress(v / 10, msg);
        }
    },
    reset: function() {
        MODx.util.Progress.id += 1;
    }
};

MODx.util.UrlParams = {
    get() {
        return this.parse(window.location.search);
    },
    set(data) {
        const params = decodeURIComponent(new URLSearchParams(data).toString());
        if (params.length) {
            window.history.pushState(params, '', `${document.location.pathname}?${params}`);
        } else {
            window.history.pushState('', '', document.location.pathname);
        }
    },
    add(key, val) {
        const params = this.get();
        params[key] = val;
        this.set(params);
    },
    remove(key) {
        const params = this.get();
        delete params[key];
        this.set(params);
    },
    clear() {
        this.set({});
    },
    parse(str) {
        const params = new URLSearchParams(str);
        return Object.fromEntries(params.entries());
    }
};

/** Adds a lock mask to an element */
MODx.LockMask = function(config = {}) {
    Ext.applyIf(config, {
        msg: _('locked'),
        msgCls: 'modx-lockmask'
    });
    MODx.LockMask.superclass.constructor.call(this, config.el, config);
};
Ext.extend(MODx.LockMask, Ext.LoadMask, {
    locked: false,
    toggle: function() {
        if (this.locked) {
            this.hide();
            this.locked = false;
        } else {
            this.show();
            this.locked = true;
        }
    },
    lock: function() {
        this.locked = true;
        this.show();
    },
    unlock: function() {
        this.locked = false;
        this.hide();
    }
});
Ext.reg('modx-lockmask', MODx.LockMask);

/**
 * Adds a new config parameter to allow preservation of trailing zeros in decimal numbers
 */
Ext.override(Ext.form.NumberField, {
    strictDecimalPrecision: false,
    fixPrecision: function(value) {
        const nan = Number.isNaN(value);
        if (!this.allowDecimals || this.decimalPrecision === -1 || nan || !value) {
            return nan ? '' : value;
        }
        return this.allowDecimals && this.strictDecimalPrecision
            ? parseFloat(value).toFixed(this.decimalPrecision)
            : parseFloat(parseFloat(value).toFixed(this.decimalPrecision))
        ;
    }
});

/** add clearDirty to basicform */
Ext.override(Ext.form.BasicForm, {
    clearDirty: function(nodeToRecurse) {
        nodeToRecurse = nodeToRecurse || this;
        nodeToRecurse?.items?.each?.(function(field) {
            if (!field.getValue) {
                return;
            }
            if (field.items) {
                this.clearDirty(field);
            } else if (field.originalValue !== field.getValue()) {
                field.originalValue = field.getValue();
            }
        }, this);
    }
});

/**
 * Static Textfield
 */
MODx.StaticTextField = Ext.extend(Ext.form.TextField, {
    fieldClass: 'x-static-text-field',

    onRender: function() {
        this.readOnly = true;
        this.disabled = !this.initialConfig.submitValue;
        MODx.StaticTextField.superclass.onRender.apply(this, arguments);
    }
});
Ext.reg('statictextfield', MODx.StaticTextField);

/**
 * Static Textarea
 */
MODx.StaticTextArea = Ext.extend(Ext.form.TextArea, {
    fieldClass: 'x-static-text-field',

    onRender: function() {
        this.readOnly = true;
        this.disabled = !this.initialConfig.submitValue;
        MODx.StaticTextArea.superclass.onRender.apply(this, arguments);
    }
});
Ext.reg('statictextarea', MODx.StaticTextArea);

/**
 * Static Boolean
 */
MODx.StaticBoolean = Ext.extend(Ext.form.TextField, {
    fieldClass: 'x-static-text-field',

    onRender: function(tf) {
        this.readOnly = true;
        this.disabled = !this.initialConfig.submitValue;
        MODx.StaticBoolean.superclass.onRender.apply(this, arguments);
        this.on('change', this.onChange, this);
    },

    setValue: function(v) {
        if (v === 1) {
            this.addClass('green');
            v = _('yes');
        } else {
            this.addClass('red');
            v = _('no');
        }
        MODx.StaticBoolean.superclass.setValue.apply(this, arguments);
    }
});
Ext.reg('staticboolean', MODx.StaticBoolean);

// This method strips not allowed html tags/attributes, html comments and php tags,
// replaces javascript invocation in a href attribute and masks html event attributes
// in an input string - assuming the result is safe to be displayed by a browser
MODx.util.safeHtml = (input, allowedTags, allowedAttributes) => {
    const
        tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
        attributes = /([a-z][a-z0-9]*)\s*=\s*".*?"/gi,
        eventAttributes = /on([a-z][a-z0-9]*\s*=)/gi,
        commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi,
        hrefJavascript = /href(\s*?=\s*?(["'])javascript:.*?\2|\s*?=\s*?javascript:.*?(?![^> ]))/gi,
        strip = (string, allowedTagsDef, allowedAttrDef) => {
            const
                tagsReplacer = (match, group1) => (allowedTagsDef.indexOf(`<${group1.toLowerCase()}>`) > -1 ? match : ''),
                attrReplacer = (match, group1) => (allowedAttrDef.indexOf(`${group1.toLowerCase()},`) > -1 ? match : '')
            ;
            return string.replace(tags, tagsReplacer).replace(attributes, attrReplacer);
        }
    ;
    let length;
    // making sure the allowedTags arg is a string containing only tags in lowercase (<a><b><c>)
    allowedTags = ((`${allowedTags || '<a><br><i><em><b><strong>'}`)
        .toLowerCase()
        .match(/<[a-z][a-z0-9]*>/g) || [])
        .join('');
    // making sure the allowedAttributes arg is a comma separated string containing only attributes in lowercase (a,b,c)
    allowedAttributes = ((`${allowedAttributes || 'href,class'}`)
        .toLowerCase()
        .match(/[a-z\-,]*/g) || [])
        .join('')
        .concat(',');
    input = input.replace(commentsAndPhpTags, '').replace(hrefJavascript, 'href="javascript:void(0)"');
    do {
        length = input.length;
        input = strip(input, allowedTags, allowedAttributes);
    } while (length !== input.length);
    return input.replace(eventAttributes, 'on&#8203;$1');
};

// *** Ext-specific overrides/extensions ***

/* add helper method to set checkbox boxLabel */
Ext.override(Ext.form.Checkbox, {
    setBoxLabel: function(boxLabel) {
        this.boxLabel = boxLabel;
        if (this.rendered) {
            this.wrap.child('.x-form-cb-label').update(boxLabel);
        }
    }
});

const FieldSetOnRender = Ext.form.FieldSet.prototype.onRender;

Ext.override(Ext.form.FieldSet, {
    onRender: function(ct, position) {
        FieldSetOnRender.call(this, ct, position);
        if (this.checkboxToggle) {
            const
                trigger = this.el.dom.getElementsByClassName(this.headerTextCls)[0],
                elem = this
            ;
            if (trigger) {
                trigger.addEventListener('click', e => {
                    elem.checkbox.dom.click(e);
                }, false);
            }
        }
    }
});

/** @deprecated No use found in the core as of 3.x; remove? */
// eslint-disable-next-line no-extend-native
Array.prototype.in_array = function(value) {
    for (let i = 0, l = this.length; i < l; i++) {
        if (this[i] === value) {
            return true;
        }
    }
    return false;
};

/** @deprecated No use found in the core as of 3.x; remove? */
Ext.form.setCheckboxValues = function(form, id, mask) {
    let
        field,
        n = 0
    ;
    // eslint-disable-next-line no-cond-assign
    while ((field = form.findField(id + n)) !== null) {
        // eslint-disable-next-line no-bitwise
        field.setValue((mask & (1 << n)) ? 'true' : 'false');
        n += 1;
    }
};

/** @deprecated No use found in the core as of 3.x; remove? */
Ext.form.getCheckboxMask = function(cbgroup) {
    let mask = '';
    if (typeof cbgroup !== 'undefined') {
        if (typeof cbgroup === 'string') {
            mask = cbgroup;
        } else {
            for (let i = 0, len = cbgroup.length; i < len; i++) {
                mask += (mask !== '' ? ',' : '') + (cbgroup[i] - 0);
            }
        }
    }
    return mask;
};

Ext.form.BasicForm.prototype.append = function() {
    const
        layout = new Ext.form.Layout(),
        fields = []
    ;
    console.log('Ext.form.BasicForm.prototype.append');
    // eslint-disable-next-line prefer-spread
    layout.stack.push.apply(layout.stack, arguments);
    for (let i = 0; i < arguments.length; i++) {
        if (arguments[i].isFormField) {
            fields.push(arguments[i]);
        }
    }
    layout.render(this.el);

    if (fields.length > 0) {
        this.items.addAll(fields);
        for (let f = 0; f < fields.length; f++) {
            fields[f].render(`x-form-el-${fields[f].id}`);
        }
    }
    return this;
};

/** @deprecated No use found in the core as of 3.x; remove? */
Ext.form.AMPMField = function(id, v) {
    return new Ext.form.ComboBox({
        store: new Ext.data.SimpleStore({
            fields: ['ampm'],
            data: [['am'], ['pm']]
        }),
        displayField: 'ampm',
        hiddenName: id,
        mode: 'local',
        editable: false,
        forceSelection: true,
        triggerAction: 'all',
        width: 60,
        value: v || 'am'
    });
};

/** @deprecated No use found in the core as of 3.x; remove? */
Ext.form.HourField = function(id, name, v) {
    return new Ext.form.ComboBox({
        store: new Ext.data.SimpleStore({
            fields: ['hour'],
            data: [[1], [2], [3], [4], [5], [6], [7], [8], [9], [10], [11], [12]]
        }),
        displayField: 'hour',
        mode: 'local',
        triggerAction: 'all',
        width: 60,
        forceSelection: true,
        rowHeight: false,
        editable: false,
        value: v || 1,
        transform: id
    });
};

Ext.override(Ext.tree.TreeNodeUI, {
    hasClass: function(className) {
        const el = Ext.fly(this.elNode);
        if (!el) {
            return '';
        }
        return className && (` ${el.dom.className} `).indexOf(` ${className} `) !== -1;
    },
    renderElements: function(n, a, targetNode, bulkRender) {
        this.indentMarkup = n.parentNode ? n.parentNode.ui.getChildIndent() : '';

        const
            hasCheckbox = Ext.isBoolean(a.checked),
            renderer = n.ownerTree && n.ownerTree.renderItemText ? n.ownerTree.renderItemText : this.renderItemText,
            href = this.getHref(a.page),
            iconClass = a.iconCls ? ` ${a.iconCls}` : '',
            iconMarkup = `<i class="icon${a.icon ? ' x-tree-node-inline-icon' : ''}${iconClass}" unselectable="on"></i>`,
            elbowMarkup = n.attributes.pseudoroot
                ? '<i class="icon-sort-down expanded-icon"></i>'
                : '<i class="x-tree-ec-icon x-tree-elbow"></i>',
            checkboxMarkup = hasCheckbox ? (`<input class="x-tree-node-cb" type="checkbox" ${a.checked ? 'checked="checked">' : '>'}`) : '',
            targetMarkup = a.hrefTarget ? `target="${a.hrefTarget}"` : '',
            buf = [
                '<li class="x-tree-node">',
                `<div ext:tree-node-id="${n.id}" class="x-tree-node-el x-tree-node-leaf x-unselectable ${a.cls}" unselectable="on">`,
                `<span class="x-tree-node-indent">${this.indentMarkup}</span>`,
                elbowMarkup,
                iconMarkup,
                checkboxMarkup,
                `<a hidefocus="on" class="x-tree-node-anchor" href="${href}" tabIndex="0" ${targetMarkup}>`,
                `<span unselectable="on">${renderer(a)}</span>`,
                '</a>',
                '</div>',
                '<ul class="x-tree-node-ct" style="display:none;"></ul>',
                '</li>'
            ].join('')
        ;
        let nel;
        // eslint-disable-next-line no-cond-assign
        if (bulkRender !== true && n.nextSibling && (nel = n.nextSibling.ui.getEl())) {
            this.wrap = Ext.DomHelper.insertHtml('beforeBegin', nel, buf);
        } else {
            this.wrap = Ext.DomHelper.insertHtml('beforeEnd', targetNode, buf);
        }

        /* eslint-disable prefer-destructuring */
        this.elNode = this.wrap.childNodes[0];
        this.ctNode = this.wrap.childNodes[1];
        const cs = this.elNode.childNodes;
        this.indentNode = cs[0];
        this.ecNode = cs[1];
        this.iconNode = cs[2];
        let index = 3;
        if (hasCheckbox) {
            this.checkbox = cs[3];
            index++;
        }
        this.anchor = cs[index];
        this.textNode = cs[index].firstChild;
        /* eslint-enable prefer-destructuring */
    },
    /**
     * Renders the item text as a XSS-safe value. Can be overridden with a renderItemText method on the Tree.
     * @param text
     * @returns string
     */
    renderItemText: function(item) {
        return Ext.util.Format.htmlEncode(item.text);
    },
    getChildIndent: function() {
        if (!this.childIndent) {
            const buf = [];
            let p = this.node;
            while (p) {
                if ((!p.isRoot || (p.isRoot && p.ownerTree.rootVisible)) && !p.attributes.pseudoroot) {
                    if (!p.isLast()) {
                        buf.unshift(`<img alt="" src="${this.emptyIcon}" class="x-tree-elbow-line">`);
                    } else {
                        buf.unshift(`<img alt="" src="${this.emptyIcon}" class="x-tree-icon">`);
                    }
                }
                p = p.parentNode;
            }
            this.childIndent = buf.join('');
        }
        return this.childIndent;
    }
});

/* allows for messages in JSON responses */
Ext.override(Ext.form.Action.Submit, {
    handleResponse: function(response) {
        const messageData = Ext.decode(response.responseText);
        if (this.form.errorReader) {
            const
                responseData = this.form.errorReader.read(response),
                errors = []
            ;
            if (responseData.records) {
                for (let i = 0, len = responseData.records.length; i < len; i++) {
                    const record = responseData.records[i];
                    errors[i] = record.data;
                }
            }
            return {
                success: responseData.success,
                message: messageData.message,
                object: messageData.object,
                errors: errors.length < 1 ? null : errors
            };
        }
        return Ext.decode(response.responseText);
    }
});

/* QTips to form fields */
Ext.form.Field.prototype.afterRender = Ext.form.Field.prototype.afterRender.createSequence(function() {
    if (this.description && parseInt(MODx.config.manager_tooltip_enable, 10)) {
        Ext.QuickTips.register({
            target: this.getEl(),
            text: this.description,
            enabled: true,
            dismissDelay: MODx.config.manager_tooltip_delay
        });
        const label = Ext.form.Field.findLabel(this);
        if (label) {
            Ext.QuickTips.register({
                target: label,
                text: this.description,
                enabled: true,
                dismissDelay: MODx.config.manager_tooltip_delay
            });
        }
    }
});

Ext.applyIf(Ext.form.Field, {
    findLabel: function(field) {
        let
            wrapDiv = null,
            label = null
        ;
        wrapDiv = field.getEl().up('div.x-form-element');
        if (wrapDiv) {
            label = wrapDiv.child('label');
        }
        if (label) {
            return label;
        }
        wrapDiv = field.getEl().up('div.x-form-item');
        if (wrapDiv) {
            label = wrapDiv.child('label');
        }
        if (label) {
            return label;
        }
    }
});

MODx.util.Format = {
    dateFromTimestamp: function(timestamp, date, time, defaultValue) {
        if (date === undefined) {
            date = true;
        }
        if (time === undefined) {
            time = true;
        }
        if (defaultValue === undefined) {
            defaultValue = '';
        }
        timestamp = parseInt(timestamp, 10);
        if (!(timestamp > 0)) {
            return defaultValue;
        }
        if (timestamp.toString().length === 10) {
            timestamp *= 1000;
        }

        let format = [];

        if (date === true) { format.push(MODx.config.manager_date_format); }
        if (time === true) { format.push(MODx.config.manager_time_format); }

        if (format.length === 0) { return defaultValue; }

        format = format.join(' ');

        return (new Date(timestamp).format(format));
    },
    /**
     * Trim a set of characters from the beginning and/or ending of a string
     * @param {String} string
     * @param {String} charList
     */
    trimCharacters: function(string, charList = '', direction = 'both') {
        if (charList.length) {
            const
                trimLeft = {
                    find: new RegExp(`^([${charList}]+)([^${charList}]?)(.*)`, 'g'),
                    replace: '$2$3'
                },
                trimRight = {
                    find: new RegExp(`(.*)([^${charList}]+)([${charList}]+)$`, 'g'),
                    replace: '$1$2'
                }
            ;
            switch (direction) {
                case 'both':
                    return string
                        .replace(trimLeft.find, trimLeft.replace)
                        .replace(trimRight.find, trimRight.replace)
                    ;
                case 'left':
                    return string.replace(trimLeft.find, trimLeft.replace);
                case 'right':
                    return string.replace(trimRight.find, trimRight.replace);
                // no default
            }
        }
        return string;
    },

    /**
     * Trim outer space and collapse multiple inner spaces to a single space for the given input
     * @param {any} value The input value to trim (only strings will be affected)
     * @returns {any}
     */
    trimAndCollapseSpace: function(value) {
        if (typeof value === 'string' && !Ext.isEmpty(value)) {
            return value.trim().replace(/\s{2,}/, ' ');
        }
        return value;
    },

    /**
     * Provides a cleanly-formatted list from user input
     * @param {String} list The full character-separated set of items
     * @param {String} separator The character used as a list item separator
     * @param {Boolean} padListItems Whether to add space to the right of each separator
     * @returns {String}
     */
    trimCharSeparatedList: function(list, separator = ',', padListItems = true) {
        let formattedList = this.trimCharacters(list, `${separator}\\s`);
        formattedList = this.trimAndCollapseSpace(formattedList);
        formattedList = formattedList
            .replace(new RegExp(`\\s*${separator}\\s*`, 'g'), separator)
            .replace(new RegExp(`[${separator}]{2,}`, 'g'), separator)
        ;
        return padListItems ? formattedList.replaceAll(separator, `${separator} `) : formattedList ;
    },

    firstToUpperCase: function(string) {
        return typeof string === 'string' && string.length > 0
            ? string.charAt(0).toUpperCase() + string.substring(1)
            : string
        ;
    }
};

MODx.util.getHeaderBreadCrumbs = function(header, trail) {
    if (typeof header === 'string') {
        header = {
            id: header,
            xtype: 'modx-header'
        };
    }
    if (trail === undefined) {
        trail = [];
    }
    if (!Array.isArray(trail)) {
        trail = [trail];
    }
    return {
        xtype: 'modx-breadcrumbs-panel',
        id: 'modx-header-breadcrumbs',
        cls: 'modx-header-breadcrumbs',
        desc: '',
        bdMarkup: '<ul><tpl for="trail"><li>'
            + '<tpl if="href"><a href="{href}" class="{cls}">{text}</a></tpl>'
            + '<tpl if="!href">{text}</tpl>'
            + '</li></tpl></ul>',
        init: function() {
            this.tpl = new Ext.XTemplate(this.bdMarkup, { compiled: true });
        },
        trail: trail,
        listeners: {
            afterrender: function() {
                this.renderTrail();
            }
        },
        renderTrail: function() {
            this.tpl.overwrite(this.body.dom.lastElementChild, { trail: this.trail });
        },
        // eslint-disable-next-line no-shadow
        updateTrail: function(trail, replace) {
            if (replace === undefined) {
                replace = false;
            }
            if (replace === true) {
                this.trail = (Array.isArray(trail)) ? trail : [trail];
                this.renderTrail();
                return true;
            }
            if (Array.isArray(trail)) {
                for (let i = 0; i < trail.length; i++) {
                    this.trail.push(trail[i]);
                }
                this.renderTrail();
                return true;
            }
            this.trail.push(trail);
            this.renderTrail();
            return true;
        },
        updateHeader: function(text) {
            if (!this.rendered) {
                Ext.getCmp(header.id).html = text;
                return;
            }

            Ext.getCmp(header.id).getEl().update(text);
        },
        items: [header]
    };
};

/* Utility functions used for dynamic alteration of URLs */
MODx.util.url = {
    /**
     * @property {Function} setParams - Sets or replaces url parameters passed via the filterData object
     *
     * @param {Object} filterData - A set of filter parameter name/value pairs to be added to (or changed in) the URL
     * @param {Object} stateData - Optional data to be used in subsequent url processing
     */
    setParams: function(filterData, stateData = {}) {
        if (typeof window.history.replaceState !== 'undefined') {
            const url = new URL(window.location.href),
                  params = url.searchParams
            ;
            Object.entries(filterData).forEach(([param, value]) => {
                params.set(param, value);
            });
            const newUrl = url.toString().replace(/%2F/g, '/');
            window.history.replaceState(stateData, document.title, newUrl);
        }
    },
    /**
     * @property {Function} clearAllParams - Clears all dynamically set url parameters,
     * while preserving those in a pre-defined list.
     *
     * @param {Object} stateData - Optional data to be used in subsequent url processing
     */
    clearAllParams: function(stateData = {}) {
        if (typeof window.history.replaceState !== 'undefined') {
            const preserve = ['a', 'id', 'key', 'namespace'],
                  preserved = [],
                  urlParts = window.location.href.split('?'),
                  params = urlParts[1].split('&')
            ;
            params.forEach(param => {
                const paramName = param.split('=')[0];
                if (preserve.includes(paramName)) {
                    preserved.push(param);
                }
            });
            let newUrl = new URL(`${urlParts[0]}?${preserved.join('&')}`);
            newUrl = newUrl.toString().replace(/%2F/g, '/');
            window.history.replaceState(stateData, document.title, newUrl);
        }
    },
    /**
     * @property {Function} clearParam - Clears a single url parameter;
     * the param name is derived from the calling filter's component itemId.
     *
     * @param {Object|String} reference - The filter's Ext component or the parameter name
     * @param {Boolean} referenceIsComponent - Set to true if deriving parameter from a filter's Ext component, false
     * @param {Object} stateData - Optional data to be used in subsequent url processing
     */
    clearParam: function(reference, referenceIsComponent = true, stateData = {}) {
        if (typeof window.history.replaceState !== 'undefined') {
            let url = new URL(window.location.href),
                removeParamName
            ;
            if (referenceIsComponent) {
                removeParamName = this.getParamNameFromCmp(reference);
                removeParamName = removeParamName === 'namespace' ? 'ns' : removeParamName;
            } else {
                removeParamName = reference.trim();
            }
            url.searchParams.delete(removeParamName);
            url = url.toString().replace(/%2F/g, '/');
            window.history.replaceState(stateData, document.title, url);
        }
    },
    /**
     * @property {Function} getParamNameFromCmp - Parses a filter component's
     * itemId to get the url parameter name, based on the following naming convention:
     * itemId: 'filter-[filterName]-[optionalAdditionalIdentifiers]'
     *
     * @param {Object} cmp - The filter's Ext component
     * @return {String}
     */
    getParamNameFromCmp: function(cmp) {
        const param = cmp.itemId.split('-')[1];
        return param === 'ns' ? 'namespace' : param ;
    },
    /**
     * @property {Function} getParamValue - Fetch and decode given parameter value from a request
     *
     * @param {String} param - The url query parameter
     * @param {Boolean} setEmptyToString - For some components, like combos, setting to null is better
     * when no value is present. Set this to true for components that prefer an empty string
     * @return {Mixed}
     */
    getParamValue: function(param, setEmptyToString = false) {
        const
            key = param === 'namespace' ? 'ns' : param,
            emptyValue = setEmptyToString ? '' : null
        ;
        return MODx.request[key] ? this.decodeParamValue(MODx.request[key]) : emptyValue ;
    },
    /**
     * @property {Function} decodeParamValue - Decodes a given parameter's value
     *
     * @param {String} value
     * @return {String}
     */
    decodeParamValue: function(value) {
        value = value.replace(/\+/g, ' ');
        return decodeURIComponent(value);
    }
};

/**
 * Utility methods for tree objects
 */
MODx.util.tree = {
    getGroupIdFromNode: function(node) {
        return node.id ? node.id.split('_').pop() : 0 ;
    }
};

Ext.util.Format.trimCommas = function(s) {
    s = s.replace(',,', ',');
    const len = s.length;
    if (s.substr(len - 1, 1) === ',') {
        s = s.substring(0, len - 1);
    }
    if (s.substr(0, 1) === ',') {
        s = s.substring(1);
    }
    if (s === ',') {
        s = '';
    }
    return s;
};

/* rowactions plugin */
Ext.ns('Ext.ux.grid');
if (typeof RegExp.escape !== 'function') {
    RegExp.escape = function(s) {
        if (typeof s !== 'string') {
            return s;
        }
        return s.replace(/([.*+?\^=!:${}()|\[\]\/\\])/g, '\\$1');
    };
}
Ext.ux.grid.RowActions = function(a) {
    Ext.apply(this, a);
    this.addEvents('beforeaction', 'action', 'beforegroupaction', 'groupaction');
    Ext.ux.grid.RowActions.superclass.constructor.call(this);
};
Ext.extend(Ext.ux.grid.RowActions, Ext.util.Observable, {
    actionEvent: 'click',
    autoWidth: true,
    dataIndex: '',
    editable: false,
    header: '',
    isColumn: true,
    keepSelection: false,
    menuDisabled: true,
    sortable: false,
    tplGroup: '<tpl for="actions"><div class="ux-grow-action-item<tpl if="\'right\'===align"> ux-action-right</tpl> {cls}" style="{style}" qtip="{qtip}">{text}</div></tpl>',
    tplRow: '<div class="ux-row-action"><tpl for="actions"><div class="ux-row-action-item {cls} <tpl if="text">ux-row-action-text</tpl>" style="{hide}{style}" qtip="{qtip}"><tpl if="text"><span qtip="{qtip}">{text}</span></tpl></div></tpl></div>',
    hideMode: 'visibility',
    widthIntercept: 4,
    widthSlope: 21,
    init: function(g) {
        this.grid = g;
        this.id = this.id || Ext.id();
        const h = g.getColumnModel().lookup;
        delete h[undefined];
        h[this.id] = this;
        if (!this.tpl) {
            this.tpl = this.processActions(this.actions);
        }
        if (this.autoWidth) {
            this.width = this.widthSlope * this.actions.length + this.widthIntercept;
            this.fixed = true;
        }
        const
            i = g.getView(),
            j = { scope: this }
        ;
        j[this.actionEvent] = this.onClick;
        g.afterRender = g.afterRender.createSequence(function() {
            i.mainBody.on(j);
            g.on('destroy', this.purgeListeners, this);
        }, this);
        if (!this.renderer) {
            this.renderer = function(a, b, c, d, e, f) {
                b.css += `${b.css ? ' ' : ''}ux-row-action-cell`;
                return this.tpl.apply(this.getData(a, b, c, d, e, f));
            }.createDelegate(this);
        }
        if (i.groupTextTpl && this.groupActions) {
            i.interceptMouse = i.interceptMouse.createInterceptor(function(e) {
                if (e.getTarget('.ux-grow-action-item')) {
                    return false;
                }
            });
            i.groupTextTpl = `<div class="ux-grow-action-text">${i.groupTextTpl}</div>${this.processActions(this.groupActions, this.tplGroup).apply()}`;
        }
        if (this.keepSelection === true) {
            g.processEvent = g.processEvent.createInterceptor(function(a, e) {
                if (a === 'mousedown') {
                    return !this.getAction(e);
                }
            }, this);
        }
    },
    getData: function(a, b, c, d, e, f) {
        return c.data || {};
    },
    processActions: function(b, c) {
        const d = [];
        Ext.each(
            b,
            function(a, i) {
                if (a.iconCls && typeof (a.callback || a.cb) === 'function') {
                    this.callbacks = this.callbacks || {};
                    this.callbacks[a.iconCls] = a.callback || a.cb;
                }
                const o = {
                    /* eslint-disable no-nested-ternary */
                    cls: a.iconIndex ? `{${a.iconIndex}}` : a.iconCls ? a.iconCls : '',
                    qtip: a.qtipIndex ? `{${a.qtipIndex}}` : a.tooltip || a.qtip ? a.tooltip || a.qtip : '',
                    text: a.textIndex ? `{${a.textIndex}}` : a.text ? a.text : '',
                    hide: a.hideIndex
                        ? `<tpl if="${a.hideIndex}">${this.hideMode === 'display' ? 'display:none' : 'visibility:hidden'};</tpl>`
                        : a.hide ? (this.hideMode === 'display' ? 'display:none' : 'visibility:hidden;') : '',
                    align: a.align || 'right',
                    style: a.style ? a.style : ''
                };
                /* eslint-enable no-nested-ternary */
                d.push(o);
            },
            this
        );
        const e = new Ext.XTemplate(c || this.tplRow);
        return new Ext.XTemplate(e.apply({ actions: d }));
    },
    getAction: function(e) {
        let a = false;
        const t = e.getTarget('.ux-row-action-item');
        if (t) {
            a = t.className.replace(/ux-row-action-item /, '');
            if (a) {
                a = a.replace(/ ux-row-action-text/, '');
                a = a.trim();
            }
        }
        return a;
    },
    onClick: function(e, a) {
        const
            b = this.grid.getView(),
            c = e.getTarget('.x-grid3-row'),
            d = b.findCellIndex(a.parentNode.parentNode)
        ;
        let f = this.getAction(e);
        if (c !== false && d !== false && f !== false) {
            const g = this.grid.store.getAt(c.rowIndex);
            if (this.callbacks && typeof this.callbacks[f] === 'function') {
                this.callbacks[f](this.grid, g, f, c.rowIndex, d);
            }
            if (this.eventsSuspended !== true && this.fireEvent('beforeaction', this.grid, g, f, c.rowIndex, d) === false) {
                return;
            }
            if (this.eventsSuspended !== true) {
                this.fireEvent('action', this.grid, g, f, c.rowIndex, d);
            }
        }
        const t = e.getTarget('.ux-grow-action-item');
        if (t) {
            const
                h = b.findGroup(a),
                i = h ? h.id.replace(/ext-gen[0-9]+-gp-/, '') : null
            ;
            let j;
            if (i) {
                const k = new RegExp(RegExp.escape(i));
                j = this.grid.store.queryBy(function(r) {
                    return r._groupId.match(k);
                });
                j = j ? j.items : [];
            }
            f = t.className.replace(/ux-grow-action-item (ux-action-right )*/, '');
            if (typeof this.callbacks[f] === 'function') {
                this.callbacks[f](this.grid, j, f, i);
            }
            if (this.eventsSuspended !== true && this.fireEvent('beforegroupaction', this.grid, j, f, i) === false) {
                return false;
            }
            this.fireEvent('groupaction', this.grid, j, f, i);
        }
    }
});
Ext.reg('rowactions', Ext.ux.grid.RowActions);

/**
 * Ext JS Library 0.30
 * Copyright(c) 2006-2009, Ext JS, LLC.
 * licensing@extjs.com
 *
 * @deprecated No use found in the core as of 3.x; remove?
 */
Ext.SwitchButton = Ext.extend(Ext.Component, {
    initComponent: function() {
        Ext.SwitchButton.superclass.initComponent.call(this);

        const mc = new Ext.util.MixedCollection();
        mc.addAll(this.items);
        this.items = mc;

        this.addEvents('change');

        if (this.handler) {
            this.on('change', this.handler, this.scope || this);
        }
    },

    onRender: function(ct, position) {
        const el = document.createElement('table');
        el.cellSpacing = 0;
        el.className = 'x-rbtn';
        el.id = this.id;

        const row = document.createElement('tr');
        el.appendChild(document.createElement('tbody')).appendChild(row);

        const
            count = this.items.length,
            last = count - 1
        ;
        this.activeItem = this.items.get(this.activeItem);

        for (let i = 0; i < count; i++) {
            const
                item = this.items.itemAt(i),
                cell = row.appendChild(document.createElement('td')),
                nextCls = i === last ? 'x-rbtn-last' : 'x-rbtn-item'
            ;
            cell.id = `${this.id}-rbi-${i}`;

            let cls = i === 0 ? 'x-rbtn-first' : nextCls;
            item.baseCls = cls;

            if (this.activeItem === item) {
                cls += '-active';
            }
            cell.className = cls;

            const button = document.createElement('button');
            button.innerHTML = '&#160;';
            button.className = item.iconCls;
            button.qtip = item.tooltip;

            cell.appendChild(button);

            item.cell = cell;
        }

        this.el = Ext.get(ct.dom.appendChild(el));

        this.el.on('click', this.onClick, this);
    },

    getActiveItem: function() {
        return this.activeItem;
    },

    setActiveItem: function(item) {
        if (typeof item != 'object' && item !== null) {
            item = this.items.get(item);
        }
        const current = this.getActiveItem();
        if (item !== current) {
            if (current) {
                Ext.fly(current.cell).removeClass(`${current.baseCls}-active`);
            }
            if (item) {
                Ext.fly(item.cell).addClass(`${item.baseCls}-active`);
            }
            this.activeItem = item;
            this.fireEvent('change', this, item);
        }
        return item;
    },

    onClick: function(e) {
        const target = e.getTarget('td', 2);
        if (!this.disabled && target) {
            this.setActiveItem(parseInt(target.id.split('-rbi-')[1], 10));
        }
    }
});
Ext.reg('switch', Ext.SwitchButton);

Ext.onReady(function() {
    MODx.util.JSONReader = MODx.load({ xtype: 'modx-json-reader' });
    MODx.form.Handler = MODx.load({ xtype: 'modx-form-handler' });
    MODx.msg = MODx.load({ xtype: 'modx-msg' });
});

/* always-submit checkboxes */
Ext.form.XCheckbox = Ext.extend(Ext.form.Checkbox, {
    submitOffValue: 0,
    submitOnValue: 1,
    onRender: function() {
        this.inputValue = this.submitOnValue;
        Ext.form.XCheckbox.superclass.onRender.apply(this, arguments);
        this.hiddenField = this.wrap.insertFirst({
            tag: 'input',
            type: 'hidden'
        });
        if (this.tooltip) {
            this.imageEl.set({ qtip: this.tooltip });
        }
        this.updateHidden();
    },
    setValue: function(v) {
        v = this.convertValue(v);
        this.updateHidden(v);
        Ext.form.XCheckbox.superclass.setValue.apply(this, arguments);
    },
    updateHidden: function(v) {
        v = undefined !== v ? v : this.checked;
        v = this.convertValue(v);
        if (this.hiddenField) {
            this.hiddenField.dom.value = v ? this.submitOnValue : this.submitOffValue;
            this.hiddenField.dom.name = v ? '' : this.el.dom.name;
        }
    },
    convertValue: function(v) {
        return v === true || v === 'true' || v === this.submitOnValue || String(v).toLowerCase() === 'on';
    }
});
Ext.reg('xcheckbox', Ext.form.XCheckbox);

/* drag/drop grids */
Ext.namespace('Ext.ux.dd');
Ext.ux.dd.GridDragDropRowOrder = Ext.extend(Ext.util.Observable, {
    copy: false,
    scrollable: false,
    constructor: function(config) {
        if (config) {
            Ext.apply(this, config);
        }
        this.addEvents({
            beforerowmove: true,
            afterrowmove: true,
            beforerowcopy: true,
            afterrowcopy: true
        });
        Ext.ux.dd.GridDragDropRowOrder.superclass.constructor.call(this);
    },
    init: function(grid) {
        this.grid = grid;
        grid.enableDragDrop = true;
        grid.on({
            render: {
                fn: this.onGridRender,
                scope: this,
                single: true
            }
        });
    },
    onGridRender: function(grid) {
        const self = this;
        this.target = new Ext.dd.DropTarget(grid.getEl(), {
            ddGroup: grid.ddGroup || 'GridDD',
            grid: grid,
            gridDropTarget: this,
            notifyDrop: function(dd, e, data) {
                if (this.currentRowEl) {
                    this.currentRowEl.removeClass('grid-row-insert-below');
                    this.currentRowEl.removeClass('grid-row-insert-above');
                }
                const target = Ext.lib.Event.getTarget(e);
                let rindex = this.grid.getView().findRowIndex(target);

                if (rindex === false || rindex === data.rowIndex) {
                    return false;
                }
                if (this.gridDropTarget.fireEvent(self.copy ? 'beforerowcopy' : 'beforerowmove', this.gridDropTarget, data.rowIndex, rindex, data.selections, 123) === false) {
                    return false;
                }
                const
                    ds = this.grid.getStore(),
                    selections = [],
                    { keys } = ds.data
                ;
                // eslint-disable-next-line guard-for-in, no-restricted-syntax
                for (const key in keys) {
                    for (let i = 0; i < data.selections.length; i++) {
                        if (keys[key] === data.selections[i].id) {
                            if (rindex === key) {
                                return false;
                            }
                            selections.push(data.selections[i]);
                        }
                    }
                }
                if (rindex > data.rowIndex && this.rowPosition < 0) {
                    rindex--;
                }
                if (rindex < data.rowIndex && this.rowPosition > 0) {
                    rindex++;
                }
                if (rindex > data.rowIndex && data.selections.length > 1) {
                    rindex -= (data.selections.length - 1);
                }
                if (rindex === data.rowIndex) {
                    return false;
                }
                if (!self.copy) {
                    for (let i = 0; i < data.selections.length; i++) {
                        ds.remove(ds.getById(data.selections[i].id));
                    }
                }
                for (let i = selections.length - 1; i >= 0; i--) {
                    const insertIndex = rindex;
                    ds.insert(insertIndex, selections[i]);
                }
                const sm = this.grid.getSelectionModel();
                if (sm) {
                    sm.selectRecords(data.selections);
                }
                this.gridDropTarget.fireEvent(self.copy ? 'afterrowcopy' : 'afterrowmove', this.gridDropTarget, data.rowIndex, rindex, data.selections);
                return true;
            },
            notifyOver: function(dd, e, data) {
                const
                    target = Ext.lib.Event.getTarget(e),
                    ds = this.grid.getStore(),
                    { keys } = ds.data
                ;
                let rindex = this.grid.getView().findRowIndex(target);
                // eslint-disable-next-line guard-for-in, no-restricted-syntax
                for (const key in keys) {
                    for (let i = 0; i < data.selections.length; i++) {
                        if (keys[key] === data.selections[i].id) {
                            if (rindex === key) {
                                if (this.currentRowEl) {
                                    this.currentRowEl.removeClass('grid-row-insert-below');
                                    this.currentRowEl.removeClass('grid-row-insert-above');
                                }
                                return this.dropNotAllowed;
                            }
                        }
                    }
                }
                if (rindex < 0 || rindex === false) {
                    this.currentRowEl.removeClass('grid-row-insert-above');
                    return this.dropNotAllowed;
                }
                try {
                    const
                        currentRow = this.grid.getView().getRow(rindex),
                        resolvedRow = new Ext.Element(currentRow).getY() - this.grid.getView().scroller.dom.scrollTop,
                        rowHeight = currentRow.offsetHeight
                    ;
                    this.rowPosition = e.getPageY() - resolvedRow - rowHeight / 2;
                    if (this.currentRowEl) {
                        this.currentRowEl.removeClass('grid-row-insert-below');
                        this.currentRowEl.removeClass('grid-row-insert-above');
                    }
                    if (this.rowPosition > 0) {
                        this.currentRowEl = new Ext.Element(currentRow);
                        this.currentRowEl.addClass('grid-row-insert-below');
                    } else if (rindex - 1 >= 0) {
                        const previousRow = this.grid.getView().getRow(rindex - 1);
                        this.currentRowEl = new Ext.Element(previousRow);
                        this.currentRowEl.addClass('grid-row-insert-below');
                    } else {
                        this.currentRowEl.addClass('grid-row-insert-above');
                    }
                } catch (err) {
                    console.warn(err);
                    rindex = false;
                }
                return rindex === false ? this.dropNotAllowed : this.dropAllowed;
            },
            notifyOut: function(dd, e, data) {
                if (this.currentRowEl) {
                    this.currentRowEl.removeClass('grid-row-insert-above');
                    this.currentRowEl.removeClass('grid-row-insert-below');
                }
            }
        });
        if (this.targetCfg) {
            Ext.apply(this.target, this.targetCfg);
        }
        if (this.scrollable) {
            Ext.dd.ScrollManager.register(grid.getView().getEditorParent());
            grid.on({ beforedestroy: this.onBeforeDestroy, scope: this, single: true });
        }
    },
    getTarget: function() {
        return this.target;
    },
    getGrid: function() {
        return this.grid;
    },
    getCopy: function() {
        return this.copy;
    },
    setCopy: function(copy) {
        this.copy = copy;
    },
    onBeforeDestroy: function(grid) {
        Ext.dd.ScrollManager.unregister(grid.getView().getEditorParent());
    }
});

/** selectability in Ext grids */
if (!Ext.grid.GridView.prototype.templates) {
    Ext.grid.GridView.prototype.templates = {};
}
Ext.grid.GridView.prototype.templates.cell = new Ext.Template(
    '<td class="x-grid3-col x-grid3-cell x-grid3-td-{id} x-selectable {css}" style="{style}" tabIndex="0" {cellAttr}>',
    '<div class="x-grid3-cell-inner x-grid3-col-{id}" {attr}>{value}</div>',
    '</td>'
);

/* combocolumn */
if (!MODx.grid) { MODx.grid = {}; }
MODx.grid.ComboColumn = Ext.extend(Ext.grid.Column, {
    gridId: undefined,
    constructor: function(cfg) {
        MODx.grid.ComboColumn.superclass.constructor.call(this, cfg);
        this.renderer = (this.editor && this.editor.triggerAction) ? MODx.grid.ComboBoxRenderer(this.editor, this.gridId, cfg.renderer) : function(value) { return value; };
    }
});
Ext.grid.Column.types.combocolumn = MODx.grid.ComboColumn;
MODx.grid.ComboBoxRenderer = function(combo, gridId, currentRenderer) {
    const getValue = value => {
        const
            idx = combo.store.find(combo.valueField, value),
            record = combo.store.getAt(idx)
        ;
        if (record) {
            return record.get(combo.displayField);
        }
        return value;
    };

    return function(value, metaData, record, rowIndex, colIndex, store) {
        if (currentRenderer) {
            if (typeof currentRenderer.fn === 'function') {
                const scope = (currentRenderer.scope) ? currentRenderer.scope : false;
                currentRenderer = currentRenderer.fn.bind(scope);
            }

            if (typeof currentRenderer === 'function') {
                value = currentRenderer(value, metaData, record, rowIndex, colIndex, store);
            }
        }

        if (combo.store.getCount() === 0 && gridId) {
            combo.store.on('load', function() {
                const grid = Ext.getCmp(gridId);
                if (grid) {
                    grid.getView().refresh();
                }
            }, this, { single: true });
            return value;
        }
        return getValue(value);
    };
};

Ext.Button.buttonTemplate = new Ext.Template(
    '<span id="{4}" class="x-btn {1} {3}" unselectable="on"><em class="{2}"><button type="{0}"></button></em></span>'
);
Ext.Button.buttonTemplate.compile();

Ext.TabPanel.prototype.itemTpl = new Ext.Template(
    '<li class="{cls}" id="{id}"><a class="x-tab-strip-close"></a>',
    '<span class="x-tab-strip-text {iconCls}">{text}</span></li>'
);
Ext.TabPanel.prototype.itemTpl.disableFormats = true;
Ext.TabPanel.prototype.itemTpl.compile();

Ext.namespace('Ext.ux.form');

/**
 * A custom checkbox group class that aggregates the values of its checked children
 * into a hidden field.
 */
Ext.ux.form.CheckboxGroup = Ext.extend(Ext.form.CheckboxGroup, {

    aggregateSubmitField: {},

    initComponent: function() {
        const
            me = this,
            ct = this.ownerCt
        ;
        if (typeof this.name === 'string' && this.name.length > 0) {
            this.aggregateSubmitField = new Ext.form.Hidden({
                name: this.name
            });

            Ext.ux.form.CheckboxGroup.superclass.initComponent.call(this);

            this.cls = typeof this.cls === 'string' && this.cls.length > 0 ? `aggregated-group ${this.cls}` : 'aggregated-group' ;

            // eslint-disable-next-line func-names, prefer-arrow-callback
            Ext.each(this.items, function(item) {
                if (typeof me.value === 'string' && me.value.length > 0) {
                    const savedVals = me.value.split(',');
                    if (savedVals.find(function(v) { return v === item.inputValue; }) === item.inputValue) {
                        item.checked = true;
                    }
                    me.aggregateSubmitField.setValue(me.value);
                }
                item.listeners = {
                    check: {
                        fn: me.setHiddenSubmit,
                        scope: me
                    }
                };
                item.submitValue = false;
            });
            ct.add(this.aggregateSubmitField);
        } else {
            console.warning('Ext.ux.form.CheckboxGroup: A name must be specified in this component’s config for its values to be saved.', this);
        }
    },
    setHiddenSubmit: function() {
        const
            groupOpts = this.items.items,
            vals = []
        ;
        Ext.each(groupOpts, function(item) {
            if (item.checked) {
                vals.push(item.inputValue);
            }
        });
        this.aggregateSubmitField.setValue(vals.join(','));
    }
});
Ext.reg('xcheckboxgroup', Ext.ux.form.CheckboxGroup);

/**
 * Plugin that adds reset and clear field (and in the future copy tag) functionality
 * via dynamically created buttons appearing next to field labels.
 *
 * Currently implemented directly in field configs, but may be added automatically on
 * a more global basis if desired for all fields or all fields of certain classes
 */
Ext.define('AddFieldUtilities.plugin.Class', {
    alias: 'plugin.fieldutilities',
    init: function(cmp) {
        cmp.on('afterrender', this.afterRender, cmp);
    },
    afterRender: function() {
        const me = this;

        // add reset trigger
        this.label.createChild({
            tag: 'a',
            title: _('field_reset'),
            cls: 'modx-field-utils modx-field-reset'
        }).on('click', function() {
            me.reset();
        }, me);

        // add clear trigger
        this.label.createChild({
            tag: 'a',
            title: _('field_clear'),
            cls: 'modx-field-utils modx-field-clear'
        }).on('click', function() {
            switch (this.xtype) {
                case 'xcheckboxgroup':
                case 'checkboxgroup':
                    if (Ext.isArray(this.items.items)) {
                        Ext.each(this.items.items, function(item) {
                            item.setValue(false);
                        });
                        this.doLayout();
                    }
                    break;
                case 'checkbox':
                case 'radio':
                    me.setValue(false);
                    break;
                default:
                    me.setValue('');
                    break;
            }
        }, me);

        // copy tag trigger TBD
    }
});

// This utility method allows downloading files with a failure and a success callback.
// It uses the following setting in the fields property:
//
// Key     | Default | Description
// ------- | ------- | -----------
// url     | MODx.config.connector_url | The download url
// timeout | 30      | Set a polling timeout
// debug   | false   | Log the polling in the console
// params  | {}      | Additional params for the connector url
// success | null    | Callback called after a successful download,
// failure | null    | Callback called after a failed download

// To use the success callback, the download processor has to set a cookie i.e. by:
// if ($this->getProperty('cookieName')) {
//     setcookie($this->getProperty('cookieName'), 'true', time() + 10, '/');
// }

MODx.util.FileDownload = function(fields) {
    if (!Ext.isObject(fields)) {
        return;
    }
    let polling = fields.timeout * 10 || 300;
    const
        me = this,
        cookieName = `fileDownload${me.randomHex(16)}`,
        ident = fields.ident || `filedownload-${Ext.id()}`,
        url = fields.url || MODx.config.connector_url,
        params = fields.params || {},
        debug = fields.debug || false,
        successCallback = fields.success || null,
        failureCallback = fields.failure || null,
        body = Ext.getBody(),
        frame = body.createChild({
            tag: 'iframe',
            cls: 'x-hidden',
            id: `${ident}-iframe`,
            name: `${ident}-iframe`
        }),
        form = body.createChild({
            tag: 'form',
            cls: 'x-hidden',
            id: `${ident}-form`,
            action: url,
            target: `${ident}-iframe`,
            method: 'post'
        })
    ;

    me.clearCookie = function() {
        Ext.util.Cookies.set(cookieName, null, new Date('January 1, 1970'), '/');
        Ext.util.Cookies.clear(cookieName, '/');
    };
    me.randomHex = function(len) {
        const hex = '0123456789ABCDEF';
        let output = '';
        for (let i = 0; i < len; ++i) {
            output += hex.charAt(Math.floor(Math.random() * hex.length));
        }
        return output;
    };
    me.isFinished = function(successCallback, failureCallback) {
        // Check if file is started downloading
        if (Ext.util.Cookies.get(cookieName) && Ext.util.Cookies.get(cookieName) === 'true') {
            me.clearCookie();
            if (successCallback) {
                successCallback({
                    success: true,
                    message: _('$file_msg_download_success')
                });
            }
            return;
        }
        // Check for error / IF any error happens the frame will have content
        try {
            if (frame.dom.contentDocument.body.innerHTML.length > 0) {
                let result = Ext.decode(frame.dom.contentDocument.body.innerHTML);
                result = result || { success: false, message: _('file_msg_download_error') };
                me.clearCookie();
                if (failureCallback) {
                    failureCallback(result);
                }
                frame.dom.contentDocument.body.innerHTML = '';
                return;
            }
        } catch (e) {
            console.log(e);
        }

        if (polling) {
            if (debug) {
                console.log(`polling ${polling}`);
            }
            // Download is not finished. Check again in 100 milliseconds.
            window.setTimeout(function() {
                polling--;
                me.isFinished(successCallback, failureCallback);
            }, 100);
        } else {
            // Polling timeout with no fileDownload cookie set
            me.clearCookie();
            if (failureCallback) {
                failureCallback({ success: false, message: _('file_err_download_timeout') });
            }
        }
    };

    params.HTTP_MODAUTH = MODx.siteId;
    if (typeof successCallback === 'function') {
        params.cookieName = cookieName;
    }
    // eslint-disable-next-line func-names, prefer-arrow-callback
    Ext.iterate(params, function(name, value) {
        form.createChild({
            tag: 'input',
            type: 'text',
            cls: 'x-hidden',
            id: `${ident}-${name}`,
            name: name,
            value: value
        });
    });
    form.dom.submit();
    if (successCallback || failureCallback) {
        me.isFinished(successCallback, failureCallback);
    }
};
