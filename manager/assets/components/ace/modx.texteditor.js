Ext.ux.Ace = Ext.extend(Ext.form.TextField,  {

    growMin : 60,

    growMax: 1000,

    mode : 'text',

    theme : 'textmate',

    showInvisibles : false,

    selectionStyle : 'line',

    scrollSpeed : 3,

    showFoldWidgets : true,

    useSoftTabs : true,

    tabSize : 4,

    useWrapMode : false,

    fontSize : '13px',

    value : '',

    style: 'position:relative;padding:0;line-height:1.3',

    initEvents : function(){
        Ext.ux.Ace.superclass.initEvents.call(this);
        this.editor.on('focus', this.onFocus.bind(this));
        this.editor.on('blur', this.onBlur.bind(this));
    },

    initComponent : function(){
        this.valueHolder = document.createElement('input');
        this.valueHolder.type = 'hidden';
        this.valueHolder.name = this.name;
        this.valueHolder.value = this.value;
    },

    onRender : function(ct, position){
        if(!this.el){
            this.defaultAutoCreate = {
                tag: "div",
                cls: "x-form-textarea",
                style:"width:100px;height:60px;position:relative"
            };
        }
        Ext.ux.Ace.superclass.onRender.call(this, ct, position);
        if(this.grow){
            this.el.setHeight(this.growMin);
        }

        this.editor = ace.edit(this.el.dom);

        this.el.appendChild(this.valueHolder);
        this.el.dom.removeAttribute('name');

        this.el.focus = this.focus.bind(this);

        this.editor.getSession().setValue(this.valueHolder.value);

        this.editor.setShowPrintMargin(false);
        this.editor.getSession().setTabSize(this.tabSize);
        this.editor.setDragDelay(0);
        this.editor.setFontSize(this.fontSize);
        this.editor.setFadeFoldWidgets(false);

        this.setShowInvisibles(this.showInvisibles);
        this.setSelectionStyle(this.selectionStyle);
        this.setScrollSpeed(this.scrollSpeed);
        this.setShowFoldWidgets(this.showFoldWidgets);
        this.setUseSoftTabs(this.useSoftTabs);
        this.setUseWrapMode(this.useWrapMode);

        this.setTheme(this.theme);
        this.setMode(this.mode);

        this.editor.getSession().on('change', (function(){
            setTimeout(function(){
                this.valueHolder.value = this.editor.getSession().getValue();
            }.bind(this), 10);
        }).bind(this));
        // TODO: attach autoSize to according event (?)
        this.autoSize();
    },

    onDestroy : function(){
        this.editor.destroy();
        Ext.ux.Ace.superclass.onDestroy.call(this);
    },

    validate : function(){
        return true;
    },

    getErrors : function(value){
        return null;
    },

    onResize : function(){
        this.editor.resize(true);
    },

    doAutoSize : function(e){
        return !e.isNavKeyPress() || e.getKey() == e.ENTER;
    },

    autoSize: function(){
        if(!this.grow){
            return;
        }
        var linesCount = this.editor.getSession().getDocument().getLength();
        var lineHeight = this.editor.renderer.lineHeight;
        var scrollBar =  this.editor.renderer.scrollBar.getWidth();
        var bordersWidth = this.el.getBorderWidth('tb');

        var h = Math.min(this.growMax, Math.max(linesCount * lineHeight + scrollBar + bordersWidth, this.growMin));
        if(h != this.lastHeight){
            this.lastHeight = h;
            this.el.setHeight(h);
            this.editor.resize();
            this.fireEvent("autosize", this, h);
        }
    },

    setSize : function(width, height){
        Ext.ux.Ace.superclass.setSize.apply(this, arguments);
        this.editor.resize(true);
    },

    getValue : function (){
        return this.valueHolder.value;
    },

    setValue : function (value){
        if (this.editor) {
            this.editor.getSession().setValue(value);
        } else {
            this.valueHolder.value = value;
        }
        this.value = value;
    },

    setMode : function (mode){
        this.editor.getSession().setMode( 'ace/mode/' + mode );
    },

    setTheme : function(theme){
        this.editor.setTheme('ace/theme/' + theme);
    },

    setFontSize : function(fontSize){
        this.editor.setFontSize(fontSize);
    },

    setShowInvisibles : function(showInvisibles){
        this.editor.setShowInvisibles(showInvisibles);
    },

    setSelectionStyle : function(selectionStyle){
        this.editor.setSelectionStyle(selectionStyle);
    },

    setScrollSpeed : function(scrollSpeed){
        this.editor.setScrollSpeed(scrollSpeed);
    },

    setShowFoldWidgets : function(showFoldWidgets){
        this.editor.setShowFoldWidgets(showFoldWidgets);
    },

    setUseSoftTabs : function(useSoftTabs){
        this.editor.getSession().setUseSoftTabs(useSoftTabs);
    },

    setUseWrapMode : function(useWrapMode){
        this.editor.getSession().setUseWrapMode(useWrapMode);
    },

    insertAtCursor : function (value){
        return this.editor.insert(value);
    },

    focus: function (){
        this.editor.focus();
    },

    blur: function (){
        this.editor.blur();
    }
});

Ext.reg('ace', Ext.ux.Ace);

Ext.namespace('MODx.ux');

MODx.ux.Ace = Ext.extend(Ext.ux.Ace, {

    mimeType : 'text/plain',

    theme : MODx.config['ace.theme'] || 'textmate',

    fontSize : MODx.config['ace.font_size'] || '13px',

    useWrapMode : MODx.config['ace.word_wrap'] == true,

    useSoftTabs : MODx.config['ace.soft_tabs'] == true,

    tabSize : MODx.config['ace.tab_size'] * 1 || 4,

    showFoldWidgets : MODx.config['ace.fold_widgets'] == true,

    showInvisibles : MODx.config['ace.show_invisibles'] == true,

    initComponent : function() {
        MODx.ux.Ace.superclass.initComponent.call(this);
        var Config = ace.require("ace/config");
        var acePath = MODx.config['manager_url'] + 'assets/components/ace/ace';
        Config.set('modePath', acePath);
        Config.set('themePath', acePath);
        Config.set('workerPath', acePath);
    },

    onRender : function (ct, position) {

        MODx.ux.Ace.superclass.onRender.call(this, ct, position);

        this.setMimeType(this.mimeType);

        var snippetManager = ace.require("ace/snippets").snippetManager;
        var snippets = MODx.config['ace.snippets'] || '';
        snippetManager.register(snippetManager.parseSnippetFile(snippets), "_");

        var HashHandler = ace.require("ace/keyboard/hash_handler").HashHandler;
        var commands = new HashHandler();
        commands.addCommand({
            name: "insertsnippet",
            bindKey: {win: "Tab", mac: "Tab"},
            exec: function(editor) {
                return snippetManager.expandWithTab(editor);
            }
        });
        // to overwrite emmet
        var onChangeMode = function(e, target) {
            var editor = target;
            editor.keyBinding.addKeyboardHandler(commands);
        }
        onChangeMode({}, this.editor);

        var Emmet = ace.require("ace/ext/emmet");
        var net = ace.require('ace/lib/net');
        net.loadScript(MODx.config['manager_url'] + 'assets/components/ace/emmet/emmet.js', function() {
            Emmet.setCore(window.emmet);
            this.editor.setOption("enableEmmet", true);
            this.editor.on("changeMode", onChangeMode);
            onChangeMode({}, this.editor);
        }.bind(this));

        ace.require('ace/ext/keybinding_menu').init(this.editor);
        this.editor.commands.addCommand({
            name: "showKeyboardShortcuts",
            bindKey: {win: "Ctrl-Alt-H", mac: "Command-Alt-H"},
            exec: function(editor) {
                editor.showKeyboardShortcuts()
            }
        });

        this.editor.commands.addCommand({
            name: "find",
            bindKey: {win: "Ctrl-F", mac: "Command-F"},
            exec: this.showFindReplaceWindow.bind(this, 0),
            readOnly: true
        });

        this.editor.commands.addCommand({
            name: "replace",
            bindKey: {win: "Ctrl-R|Ctrl-Shift-R", mac: "Command-Option-F|Command-Shift-Option-F"},
            exec: this.showFindReplaceWindow.bind(this, 1),
            readOnly: true
        });

        this.editor.commands.addCommand({
            name: "gotoline",
            bindKey: {win: "Ctrl-L", mac: "Command-Option-L"},
            exec: this.showGotoLineWindow.bind(this),
            readOnly: true
        });

        this.editor.commands.addCommand({
            name: "fullscreen",
            bindKey: {win: "Ctrl-F11", mac: "Command-Option-Q"},
            exec: this.fullScreen.bind(this),
            readOnly: true
        });

        this.windows = [];
    },

    fullScreen : function() {
        if (this.isFullscreen){
            this.isFullscreen = false;
            this.el.setStyle({
                position: 'relative',
                zIndex: 0,
                height: this.savedState.style.height,
                width: this.savedState.style.width,
                borderWidth: this.savedState.style.borderWidth
            });
            this.grow = this.savedState.grow;
        } else {
            this.isFullscreen = true;
            this.savedState = {
                style: {
                    width: this.el.dom.style.width,
                    height: this.el.dom.style.height,
                    borderWidth: this.el.dom.style.borderWidth,
                },
                grow: this.grow
            };
            this.el.setStyle({
                position: 'fixed',
                zIndex: 100,
                top: 0,
                left: 0,
                bottom: 0,
                right: 0,
                height: 'auto',
                width: 'auto',
                borderWidth: 0
            });
            this.grow = false;
        }
        this.onResize();
    },

    setMimeType : function (mimeType){
        var typeMap = {
             'text/x-php'            : 'php'
            ,'application/x-php'     : 'php'
            ,'text/x-sql'            : 'sql'
            ,'text/x-scss'           : 'scss'
            ,'text/x-less'           : 'less'
            ,'text/xml'              : 'xml'
            ,'application/xml'       : 'xml'
            ,'image/svg+xml'         : 'svg'
            ,'text/html'             : 'html'
            ,'application/xhtml+xml' : 'html'
            ,'text/javascript'       : 'javascript'
            ,'application/javascript': 'javascript'
            ,'application/json'      : 'json'
            ,'text/css'              : 'css'
            ,'text/plain'            : 'text'
        };

        this.setMode( typeMap[mimeType] || 'text' );
    },

    showFindReplaceWindow: function (tab) {
        var window, field, tabs;
        if (!this.windows.findRelpace) {
            this.windows.findRelpace = this.createFindReplaceWindow();
        }
        window = this.windows.findRelpace;
        field = window.fp.getForm().findField('needle');
        tabs = window.findByType('modx-tabs')[0];

        window.show();

        if (this.editor.getCopyText()) {
            field.setValue(this.editor.getCopyText());
        }
        field.focus(false,50);

        tabs.setActiveTab(tab);
    },

    showGotoLineWindow : function(){
        var window;
        if (!this.windows.gotoLine){
            this.windows.gotoLine = this.createGotoLineWindow();
        }
        window = this.windows.gotoLine;
        window.show();
    },

    doFind : function(){
        var window, options, needle;

        window = this.windows.findRelpace;

        needle = window.getFieldValue('needle');
        options = window.getOptions();
        
        this.editor.find(needle, options);
    },

    doReplaceAll : function(){
        var window, options, needle, replacement, result;

        window = this.windows.findRelpace;

        needle = window.getFieldValue('needle');
        replacement = window.getFieldValue('replacement');
        options = window.getOptions();

        options.needle = needle;

        result = this.editor.replaceAll(replacement, options);
        Ext.Msg.alert(_('ui_ace.replace_all'), _('ui_ace.message_replaced', {count: result}));
    },
    
    doReplace : function(){
        var window, options, needle, replacement;

        window = this.windows.findRelpace;

        needle = window.getFieldValue('needle');
        replacement = window.getFieldValue('replacement');
        options = window.getOptions();

        options.needle = needle;

        this.editor.replace(replacement, options);
    },
    
    doGotoLine : function(){
        var window, line;

        window = this.windows.gotoLine;

        line = window.fp.getForm().getFieldValues('line')['line'];
        if (!isNaN(line)){
            this.editor.gotoLine(line);
            window.hide();
        }
    },

    createFindReplaceWindow : function (){
        var window = MODx.load({
            xtype: 'modx-window',
            title: _('ui_ace.find')
            ,resizable: false
            ,maximizable: false
            ,allowDrop: false
            ,width: 300
            ,buttons: [{
                text: _('ui_ace.find')
                ,hidden: true
                ,name: 'find-button'
                ,scope: this
                ,handler: this.doFind
            },{
                text: _('ui_ace.replace_all')
                ,hidden: true
                ,name: 'replaceall-button'
                ,scope: this
                ,handler: this.doReplaceAll
            },{
                text: _('ui_ace.replace')
                ,hidden: true
                ,name: 'replace-button'
                ,scope: this
                ,handler: this.doReplace
            },{
                text: _('ui_ace.close')
                ,scope: this
                ,handler: function() { window.hide(); }
            }]
            ,keys: [{
                key: Ext.EventObject.ENTER
                ,fn: function(){
                    var tabs = window.findByType('modx-tabs')[0];

                    if (tabs.getActiveTab().name == 'find-tab') {
                        window.fbar.items.filter('name', 'find-button').first().handler.call(this);
                    }
                    if (tabs.getActiveTab().name == 'replace-tab') {
                        window.fbar.items.filter('name', 'replace-button').first().handler.call(this);
                    }
                }
                ,scope: this
            }]
            ,action: 'find/replace'
            ,listeners: {
                'hide': {fn: this.focus, scope: this}
            }
            ,fields: [{
                xtype: 'modx-tabs'
                ,forceLayout: true
                ,deferredRender: false
                ,collapsible: false
                ,border: false
                ,items: [{
                    title: _('ui_ace.find')
                    ,cls: 'modx-find-tab'
                    ,layout: 'form'
                    ,name: 'find-tab'
                    ,forceLayout: true
                    ,deferredRender: false
                    ,labelWidth: 200
                    ,border: true
                },{
                    title: _('ui_ace.replace')
                    ,cls: 'modx-replace-tab'
                    ,layout: 'form'
                    ,name: 'replace-tab'
                    ,forceLayout: true
                    ,deferredRender: false
                    ,labelWidth: 200
                    ,border: true
                }]
            },{
                xtype: 'textfield'
                ,fieldLabel:  _('ui_ace.find')
                ,name: 'needle'
                ,selectOnFocus: true
                ,anchor: '100%'
                ,value:  ''
            },{
                xtype: 'textfield'
                ,fieldLabel: _('ui_ace.replace_with')
                ,name: 'replacement'
                ,selectOnFocus: true
                ,anchor: '100%'
                ,value:  ''
            },{
                xtype: 'xcheckbox'
                ,boxLabel: _('ui_ace.whole_word')
                ,name: 'wholeword'
                ,inputValue: 1
                    ,layout: 'form'
                    ,forceLayout: true
                    ,deferredRender: false
                ,checked: false
            },{
                xtype: 'xcheckbox'
                ,boxLabel: _('ui_ace.case_sensitive')
                ,name: 'casesensitive'
                ,inputValue: 1
                ,checked: false
            },{
                xtype: 'xcheckbox'
                ,boxLabel: _('ui_ace.search_wrap')
                ,name: 'wrap'
                ,inputValue: 1
                ,checked: true
            }]
        });

        window.getOptions = function () {
            var options;

            options = {};
            options.wholeWord = this.getFieldValue('wholeword');
            options.caseSensitive = this.getFieldValue('casesensitive');
            options.wrap = this.getFieldValue('wrap');

            return options;
        };

        window.getFieldValue = function (field) {
            var form, value;

            form = this.fp.getForm();
            value = form.getFieldValues(field)[field];

            return value;
        };

        var tabs = window.findByType('modx-tabs')[0];
        tabs.on({
            tabchange: (function(tabs, item){
                var form, buttons;

                form = window.fp.getForm();
                buttons = window.fbar.items;

                switch (item.name)
                {
                    case 'find-tab':
                        window.setTitle( _('ui_ace.find') );
                        form.findField('replacement').hide();
                        form.findField('needle').focus(false, 0);

                        buttons.filter('name', 'find-button').first().show();
                        buttons.filter('name', 'replaceall-button').first().hide();
                        buttons.filter('name', 'replace-button').first().hide();
                        break;
                    case 'replace-tab':
                        window.setTitle( _('ui_ace.replace') );
                        form.findField('replacement').show();
                        form.findField('needle').focus(false, 0);

                        buttons.filter('name', 'find-button').first().hide();
                        buttons.filter('name', 'replaceall-button').first().show();
                        buttons.filter('name', 'replace-button').first().show();
                        break;
                }
            }).bind(this)
        });

        return window;
    },

    createGotoLineWindow: function () {
        var window = MODx.load({
            xtype: 'modx-window',
            title: _('ui_ace.goto_line')
            ,resizable: false
            ,maximizable: false
            ,allowDrop: false
            ,width: 300
            ,buttons: [{
                text: _('ui_ace.go')
                ,scope: this
                ,handler: this.doGotoLine
            },{
                text: _('ui_ace.close')
                ,scope: this
                ,handler: function() { window.hide(); }
            }]
            ,keys: [{
                key: Ext.EventObject.ENTER
                ,fn: this.doGotoLine
                ,scope: this
            }]
            ,action: 'gotoline'
            ,listeners: {
                'hide': {fn: this.focus, scope: this}
            }
            ,fields: [{
                xtype: 'textfield'
                ,validator: function (value) {
                    return !isNaN(value);
                }
                ,fieldLabel: _('ui_ace.goto_line')
                ,name: 'line'
                ,anchor: '100%'
                ,value:  ''
            }]
        });

        return window;
    },

    setMode : function (mode){
        var editor = this.editor;
        this.editor.session.setMode( 'ace/mode/' + mode );
        this.editor.session.on('changeMode' ,function(data){
            var mode = this.getMode();
            if (mode.modx) return;
            mode.modx = true;

            var rules = mode.getTokenizer().states;
            for (var state in rules)
            {
                rules[state].unshift({
                    token : "support.constant", // opening tag
                    regex : "\\[\\[",
                    next : state+'-modxtag-start'
                });
                rules[state].unshift({
                    token : "comment", // opening tag
                    regex : "\\[\\[\\-",
                    next : state+'-modxtag-comment',
                    merge: true
                });
                var modxRules = {};
                /*
                    state"start" : [
                    {
                    token : "comment.tag", // opening tag
                    regex : "\\[\\[\\-.*?\\]\\]"
                    }, {
                    token : "meta.tag", // opening tag
                    regex : "\\[\\[",
                    next : 'tag-start'
                    }, {
                    token : "text",
                    regex : "\\s+"
                } ],*/
                modxRules[state + '-modxtag-comment'] = [
                
                {
                    token : "comment",
                    regex : "[^\\[\\]]+",
                    merge : true
                },{
                    token : "comment",
                    regex : "\\[\\[\\-.*?\\]\\]"
                },{
                    token : "comment",
                    regex : "\\s+",
                    merge : true
                },
                {
                    token : "comment",
                    regex : "\\]\\]",
                    next: state
                }
                ];
                modxRules[state + '-modxtag-start'] = [
                {
                    token : "variable",
                    regex : "!?(?:[%|*|~|\\+|\\$]|(?:\\+\\+)|(?:\\*#))?(?:[-_a-zA-Z0-9\\.]+|\\[\\[.*?\\]\\])",
                    next : state + '-modxtag-propertyset'
                },
                {
                    token : "text",
                    regex : "\\s+"
                },
                {
                    token : "support.constant",
                    regex : "\\]\\]",
                    next: state
                }
                ];
                modxRules[state + '-modxtag-propertyset'] = [
                {
                    token : ['keyword.operator', "support.class"],
                    regex : "(@)([-_a-zA-Z0-9\\.]+|\\[\\[.*?\\]\\])",
                    next : state + '-modxtag-filter'
                },
                {
                    token : "text",
                    regex : "\\s+"
                },
                {
                    token: "empty",
                    regex: "",
                    next: state + "-modxtag-filter"
                }
                ];
                modxRules[state + '-modxtag-filter'] = [
                {
                    token : ['keyword.operator', "support.function"],
                    regex : "(:)([-_a-zA-Z0-9]+|\\[\\[.*?\\]\\])",
                    next : state + '-modxtag-filter-eq'
                },
                {
                    token : "text",
                    regex : "\\s+"
                },
                {
                    token: "empty",
                    regex: "",
                    next: state + "-modxtag-attributes"
                }
                ];
                modxRules[state + '-modxtag-filter-eq'] =
                [
                {
                    token : ["keyword.operator"],
                    regex : "="
                    },{
                    token : 'string',
                    regex : '`',
                    next: state + "-modxtag-filter-value"
                },
                {
                    token : "text",
                    regex : "\\s+"
                },
                {
                    token: "empty",
                    regex: "",
                    next: state + "-modxtag-filter"
                }
                ];
                modxRules[state + "-modxtag-attributes"] = [
                {
                    token : "string",
                    regex : '`',
                    next : state + "-modxtag-attribute-value"
                    }, {
                    token : "keyword.operator",
                    regex : "="
                    }, {
                    token : "entity.other.attribute-name",
                    regex : "[&\\?](?:[-_a-zA-Z0-9]+|\\[\\[.*?\\]\\])"
                },
                {
                    token : "comment",
                    regex : "\\[\\[\\-.*?\\]\\]"
                }
                ,{
                    token : "constant.buildin",
                    regex : "\\[\\[.*?\\]\\]"
                },
                {
                    token : "support.constant",
                    regex : "\\]\\]",
                    next: state
                    }, {
                    token : "text",
                    regex : "\\s+"
                }];
                modxRules[state + "-modxtag-attribute-value"] = [
                {
                    token : "string",
                    regex : "[^`\\[]+",
                    merge : true
                    },{
                    token : "string",
                    regex : "\\[\\[.*?\\]\\]",
                    merge : true
                    },{
                    token : "string",
                    regex : "[^`]+",
                    merge : true
                    }, {
                    token : "string",
                    regex : "\\\\$",
                    next  : state + "-modxtag-attribute-value",
                    merge : true
                    }, {
                    token : "string",
                    regex : "`",
                    next  : state + "-modxtag-attributes",
                    merge : true
                }
                ];
                modxRules[state + "-modxtag-filter-value"] = [
                {
                    token : "string",
                    regex : "[^`\\[]+",
                    merge : true
                    },{
                    token : "string",
                    regex : "\\[\\[.*?\\]\\]",
                    merge : true
                    }, {
                    token : "string",
                    regex : "\\\\$",
                    next  : state + "-modxtag-filter-value",
                    merge : true
                    }, {
                    token : "string",
                    regex : "`",
                    next  : state + "-modxtag-filter",
                    merge : true
                }
                ];

                for (var modxState in modxRules){
                    rules[modxState] = modxRules[modxState]
                }
                var last = rules[state][rules[state].length-1];
                if (last.regex == '[^<]+'){
                    last.regex = '[^<\\[]+';
                }
            }
            var Tokenizer = ace.require("ace/tokenizer").Tokenizer;
            var Behaviour = ace.require("ace/mode/behaviour").Behaviour;
            mode.$tokenizer = new Tokenizer(rules);
            this.bgTokenizer.setTokenizer(mode.$tokenizer);
            mode.$behaviour = mode.$behaviour || new Behaviour();
            mode.$behaviour.add("brackets", "insertion", function (state, action, editor, session, text) {
                if (text == '[') {
                    var selection = editor.getSelectionRange();
                    var selected = session.doc.getTextRange(selection);
                    if (selected !== "") {
                        return {
                            text: '[' + selected + ']',
                            selection: false
                        };
                    } else {
                        return {
                            text: '[]',
                            selection: [1, 1]
                        };
                    }
                } else if (text == ']') {
                    var cursor = editor.getCursorPosition();
                    var line = session.doc.getLine(cursor.row);
                    var rightChar = line.substring(cursor.column, cursor.column + 1);
                    if (rightChar == ']') {
                        var matching = session.$findOpeningBracket(']', {column: cursor.column + 1, row: cursor.row});
                        if (matching !== null) {
                            return {
                                text: '',
                                selection: [1, 1]
                            };
                        }
                    }
                }
            });
            
            mode.$behaviour.add("brackets", "deletion", function (state, action, editor, session, range) {
                var selected = session.doc.getTextRange(range);
                if (!range.isMultiLine() && selected == '[') {
                    var line = session.doc.getLine(range.start.row);
                    var rightChar = line.substring(range.start.column + 1, range.start.column + 2);
                    if (rightChar == ']') {
                        range.end.column++;
                        return range;
                    }
                }
            });
            mode.$behaviour.add("string_apostrophes", "insertion", function (state, action, editor, session, text) {
                if (text == '`') {
                    var quote = "`";
                    var selection = editor.getSelectionRange();
                    var selected = session.doc.getTextRange(selection);
                    if (selected !== "") {
                        return {
                            text: quote + selected + quote,
                            selection: false
                        };
                    } else {
                        var cursor = editor.getCursorPosition();
                        var line = session.doc.getLine(cursor.row);
                        var leftChar = line.substring(cursor.column-1, cursor.column);

                        // Find what token we're inside.
                        var tokens = session.getTokens(selection.start.row);
                        var col = 0, token;
                        var quotepos = -1; // Track whether we're inside an open quote.

                        for (var x = 0; x < tokens.length; x++) {
                            token = tokens[x];
                            if (token.type == "string") {
                                quotepos = -1;
                            } else if (quotepos < 0) {
                                quotepos = token.value.indexOf(quote);
                            }
                            if ((token.value.length + col) > selection.start.column) {
                                break;
                            }
                            col += tokens[x].value.length;
                        }

                        // Try and be smart about when we auto insert.
                        if (!token || (quotepos < 0 && token.type !== "comment" && (token.type !== "string" || ((selection.start.column !== token.value.length+col-1) && token.value.lastIndexOf(quote) === token.value.length-1)))) {
                            return {
                                text: quote + quote,
                                selection: [1,1]
                            };
                        } else if (token && token.type === "string") {
                            // Ignore input and move right one if we're typing over the closing quote.
                            var rightChar = line.substring(cursor.column, cursor.column + 1);
                            if (rightChar == quote) {
                                return {
                                    text: '',
                                    selection: [1, 1]
                                };
                            }
                        }
                    }
                }
            });
            mode.$behaviour.add("string_apostrophes", "deletion", function (state, action, editor, session, range) {
                var selected = session.doc.getTextRange(range);
                if (!range.isMultiLine() && (selected == '`')) {
                    var line = session.doc.getLine(range.start.row);
                    var rightChar = line.substring(range.start.column + 1, range.start.column + 2);
                    if (rightChar == '`') {
                        range.end.column++;
                        return range;
                    }
                }
            });
        }.bind(this.editor.session));
    }
});

Ext.reg('modx-texteditor',MODx.ux.Ace);