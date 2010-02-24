Ext.ns('MODx.rte');

MODx.rte.RTE = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        anchor: '97%'
        ,hideLabel: true
        ,enableFormat: true
        ,enableLists: true
        ,enableAlignments: true
        
        ,enableColors: false
        ,enableFont: false
        ,enableFontSize: false
        ,enableLinks: false
        ,enableSourceEdit: true
        ,iFrameName: config.id+'-iframe'
        
        
        ,buttons: [{
            itemId:'sourceedit'
            ,enableToggle: true
            ,handler: function(btn) {
                this.toggleSourceEdit(!this.sourceEditMode);
            }
            ,tooltip: {
                title: _('btn_sourceedit')
                ,text: _('btn_sourceedit_desc')
            }
        }
        ,'-'
        ,{
            itemId:'bold'
            ,tooltip: {
                title: _('btn_bold')
                ,text: _('btn_bold_desc')
            }
        },{
            itemId:'italic'
            ,tooltip: {
                title: _('btn_italic')
                ,text: _('btn_italic_desc')
            }
        },{
            itemId:'underline'
            ,tooltip: {
                title: _('btn_underline')
                ,text: _('btn_underline_desc')
            }
        },{
            itemId:'strikethrough'
            ,tooltip: {
                title: _('btn_strikethrough')
                ,text: _('btn_strikethrough_desc')
            }
        },{
            itemId:'subscript'
            ,tooltip: {
                title: _('btn_subscript')
                ,text: _('btn_subscript_desc')
            }
        },{
            itemId:'superscript'
            ,tooltip: {
                title: _('btn_superscript')
                ,text: _('btn_superscript_desc')
            }
        }
        ,'-',
        {
            itemId:'increasefontsize'
            ,enableToggle: false
            ,handler: this.adjustFont
            ,tooltip: {
                title: _('btn_growtext')
                ,text: _('btn_growtext_desc')
            }
        },{
            itemId:'decreasefontsize'
            ,enableToggle: false
            ,handler: this.adjustFont
            ,tooltip: {
                title: _('btn_shrinktext')
                ,text: _('btn_shrinktext_desc')
            }
        }
        ,'-',
        {
            itemId:'justifyleft'
            ,tooltip: {
                title: _('btn_alignleft')
                ,text: _('btn_alignleft_desc')
            }
        },{
            itemId:'justifycenter'
            ,tooltip: {
                title: _('btn_aligncenter')
                ,text: _('btn_aligncenter_desc')
            }
        },{
            itemId:'justifyright'
            ,tooltip: {
                title: _('btn_alignright')
                ,text: _('btn_alignright_desc')
            }
        },{
            itemId:'justifyfull'
            ,tooltip: {
                title: _('btn_alignfull')
                ,text: _('btn_alignfull_desc')
            }
        }
        ,'-',
        {
            itemId:'createlink'
            ,tooltip: {
                title: _('btn_addlink')
                ,text: _('btn_addlink_desc')
            }
            ,enableToggle: false
            ,enableOnSelection: true
            ,handler: this.loadLinkWindow
            ,scope: this
        },{
            itemId: 'unlink'
            ,enableToggle: false
            ,enableOnSelection: true
            ,tooltip: {
                title: _('btn_removelink')
                ,text: _('btn_removelink_desc')
            }
        },{
            itemId: 'image'
            ,tooltip: {
                title: _('btn_image')
                ,text: _('btn_image_desc')
            }
            ,enableToggle: false
            ,handler: this.loadImageWindow
            ,scope: this
        }
        ,'-'
        ,{
            itemId:'insertorderedlist'
            ,tooltip: {
                title: _('btn_list_bullet')
                ,text: _('btn_list_bullet_desc')
            }
        },{
            itemId:'insertunorderedlist'
            ,tooltip: {
                title: _('btn_list_numbered')
                ,text: _('btn_list_numbered_desc')
            }
        },{
            itemId: 'indent'
            ,tooltip: {
                title: _('btn_indent')
                ,text: _('btn_indent_text')
            }
        },{
            itemId: 'outdent'
            ,tooltip: {
                title: _('btn_outdent')
                ,text: _('btn_outdent_desc')
            }
        }
        ,'-',
        {
            itemId: 'table'
            ,tooltip: {
                title: _('btn_table')
            }
            ,overflowText: _('btn_table_ovf')
            ,enableToggle: false
            ,handler: this.loadTableWindow
            ,scope: this
        },{
            itemId: 'hr'
            ,tooltip: {
                title: _('btn_hr')
            }
            ,overflowText: _('btn_hr_ovf')
            ,enableToggle: false
            ,handler: this.loadHrWindow
            ,scope: this
        },{
            itemId: 'specialchar'
            ,tooltip: {
                title: _('btn_specchar')
                ,text: _('btn_specchar_desc')
            }
            ,enableToggle: false
            ,handler: this.loadSpecialChar
            ,scope: this
        }
        ,'-',
        {
            itemId: 'removeformat'
            ,enableToggle: false
            ,enableOnSelection: true
            ,tooltip: {
                title: _('btn_removeformat')
                ,text: _('btn_removeformat_desc')
            }
        },{
            itemId: 'wordpaste'
            ,iconCls: 'x-edit-wordpaste'
            ,enableToggle: true
            ,pressed: true
            ,handler: function() { this.wordPasteEnabled = !this.wordPasteEnabled; }
            ,scope: this
            ,tooltip: {
                title: _('btn_wordpaste')
                ,text: _('btn_wordpaste_desc')
            }
        }]
    });
    MODx.rte.RTE.superclass.constructor.call(this,config);
    this.config = config;
    if (config.formpanel) {
        this.on('sync',this.fireChange,this);
    }    
    this.on('render',this.onRteRender,this);
    this.on('initialize',this.onRteInit,this);
};
Ext.extend(MODx.rte.RTE,Ext.form.HtmlEditor,{
    windows: {}
    ,iFrameName: false
    
    ,loadMenu: function() {
        var iframe = Ext.get(this.iframe);        
        this.menu = new Ext.menu.Menu({
            autoScroll: false
            ,enableScroll: false
            ,floating: true
            ,plain: true
            ,items:[]
        });
        this.on('activate',this.activateMenu,this);
    }
    ,activateMenu: function() {
        this.cel = Ext.get(this.getEditorBody());
        this.cel.on('contextmenu',  function(e){
            e.stopEvent();
            e.stopPropagation();
        });
        var opts = {buffer:100};
        if (!Ext.isIE){
            Ext.apply(opts,{
                stopEvent: true
                ,stopPropagation: true 
                ,preventDefault: true
            });
        }
        this.cel.on('contextmenu',this.showMenu, this, opts);
        
        /* needed for chrome/webkit to show context menu in the entire area */
        var sz = this.el.getSize();
        this.setSize(sz.width, this.height || sz.height);
    }
    
    ,showMenu: function(e){
                
        var tagNames = this.selection.getTagsInSelection(true);
        var tags = this.selection.getTagsInSelection();
        
        
        var itms = [];
        itms.push({
            text: _('cm_cut')
            ,handler: function() {
                this.relayCmd('cut');
            }
            ,scope:this
        });
        itms.push('-');
        if (tagNames.indexOf('A') != -1) {
            var a = this.selection.getFirstTagInSelection('A',tags);
            this.selectionAttr = this.selection.getTagAttributes(a);
            itms.push({
                text: _('cm_link_edit')
                ,handler: function() {
                    this.loadLinkWindow(this.selectionAttr);
                }
                ,hidden: Ext.isIE ? true : false
                ,scope:this
            });
            itms.push({
                text: _('cm_unlink')
                ,handler: function() {
                    this.relayCmd('unlink');
                }
                ,scope:this
            });
        } else {
            itms.push({
                text: _('cm_link_add')
                ,handler: function() {
                    this.loadLinkWindow();
                }
                ,hidden: Ext.isIE ? true : false
                ,scope:this
            });
        }
        if (tagNames.indexOf('IMG') != -1) {
            var img = this.selection.getFirstTagInSelection('IMG',tags);
            var attr = this.selection.getTagAttributes(img);
            itms.push('-');
            itms.push({
                text: _('cm_image_edit')
                ,handler: function() { 
                    this.loadImageWindow(attr);
                }
                ,scope: this
            });
        } else {
            itms.push('-');
            itms.push({
                text: _('cm_image_add')
                ,handler: function() { this.loadImageWindow(); }
                ,scope: this
            });
        }

             
        this.menu.removeAll();
        var l = itms.length;
        for(var i = 0; i < l; i++) {
            this.menu.add(itms[i]);
        }
        
        if (!Ext.isIE) { e.stopEvent(); }
        var pos = e.getPoint();
        var iframe = Ext.get(this.iframe);
        pos[0] += iframe.getX();
        pos[1] += iframe.getY();
        this.menu.showAt(pos);
    }
    
    ,getMenuItems: function(opts) {
        opts = opts || {};
        return itms;
    }
    
    ,fireChange: function() {
        var fp = Ext.getCmp(this.config.formpanel);
        if (fp) { fp.fireEvent('fieldChange'); }
    }
    
    ,onRteInit: function() {
        Ext.EventManager.on(this.getDoc(), {
            'keyup': this.checkIfPaste
            ,scope: this
        });
        this.wlastValue = this.getRawValue();
        this.wcurLength = this.wlastValue.length;
        this.wlastLength = this.wlastValue.length;
        this.selection = new MODx.rte.Selection(this);
        
        var prepareDoc = function() {
            var doc = (this.iframe.contentWindow || this.iframe.contentDocument);
            if (doc.document) { doc = doc.document; }            
            var imgs = doc.getElementsByTagName('img');
            
            for (var i = 0; i < imgs.length; i++) { 
                img = Ext.get(imgs.item(i));
                img.addClass('modx-rte-image');
                img.on('mouseover',function() {},this);
            }
        };
        /*prepareDoc.defer(1000,this);*/
        
        
    }
    ,onRteRender: function() {
        this.loadMenu();
        /* drag/drop */
        var ifr = Ext.get(this.iframe);
        MODx.load({
            xtype: 'modx-treedrop'
            ,target: Ext.get(this.iframe)
            ,targetEl: this.iframe
            ,iframe: ifr ? true : false
            ,iframeEl: ifr ? ifr.dom : false
            ,ed: this
            ,onInsert: function(v,cfg) {
                cfg.ed.insertAtCursor(v);
                return;
            }
        });
    }
    
    
    ,getDocMarkup : function(){
        var ss = MODx.config.manager_url+'templates/'+MODx.config.manager_theme+'/css/rte.css?'+Ext.id();
        return '<html><head>'
            + '<base href="'+MODx.config.site_url+'" />'
            + '<link href="' + ss + '"  rel="stylesheet" type="text/css" />'
            + (this.config.stylesheet ? '<link href="' + this.config.stylesheet + '"  rel="stylesheet" type="text/css" />' : '')
            + '</head><body></body></html>';
    }
    ,createIFrame: function(){
        var iframe = document.createElement('iframe');
        iframe.name = this.iFrameName || Ext.id();
        iframe.frameBorder = '0';
        iframe.src = Ext.isIE ? Ext.SSL_SECURE_URL : "javascript:;";
        this.wrap.dom.appendChild(iframe);

        this.iframe = iframe;

        this.monitorTask = Ext.TaskMgr.start({
            run: this.checkDesignMode,
            scope: this,
            interval:100
        });
    }

    ,fixKeys : function(){ // load time branching for fastest keydown performance

       if(Ext.isIE){
            return function(e){
                var k = e.getKey(), r;
                if(k == e.TAB){
                    e.stopEvent();
                    r = this.doc.selection.createRange();
                    if(r){
                        r.collapse(true);
                        r.pasteHTML('&nbsp;&nbsp;&nbsp;&nbsp;');
                        this.deferFocus();
                    }
                }else if(k == e.ENTER){
                    r = this.doc.selection.createRange();
                    if(r){
                        var target = r.parentElement();
                        if(!target || target.tagName.toLowerCase() != 'li'){
                            e.stopEvent();
                            r.pasteHTML('<br />');
                            r.collapse(false);
                            r.select();
                        }
                    }
                }
            };
        }else if(Ext.isOpera){
            return function(e){
                var k = e.getKey();
                if(k == e.TAB){
                    e.stopEvent();
                    this.win.focus();
                    this.execCmd('InsertHTML','&nbsp;&nbsp;&nbsp;&nbsp;');
                    this.deferFocus();
                }
            };
        }else if(Ext.isWebKit || Ext.Safari){
            return function(e){
                var k = e.getKey();
                if(k == e.TAB){
                    e.stopEvent();
                    this.execCmd('InsertText','\t');
                    this.deferFocus();
                }
             };
        }
    }()
    
    ,toggleSourceEdit : function(sourceEditMode){
        if(sourceEditMode === undefined){
            sourceEditMode = !this.sourceEditMode;
        }
       this.sourceEditMode = sourceEditMode === true;
        var btn = this.tb.items.get('sourceedit');
        if(btn.pressed !== this.sourceEditMode){
            btn.toggle(this.sourceEditMode);
            if(!btn.xtbHidden){
                return;
            }
        }
        if(this.sourceEditMode){            
            this.disableItems(true);
            this.syncValue();
            this.iframe.className = 'x-hidden';
            this.el.removeClass('x-hidden');
            this.el.dom.removeAttribute('tabIndex');
            this.el.focus();
        }else{
            if(this.initialized){
                this.disableItems(false);
            }
            this.pushValue();
            this.iframe.className = '';
            this.el.addClass('x-hidden');
            this.el.dom.setAttribute('tabIndex', -1);
            /*this.deferFocus();*/
        }
        var lastSize = this.lastSize;
        if(lastSize){
            delete this.lastSize;
            this.setSize(lastSize);
        }
        this.fireEvent('editmodechange', this, this.sourceEditMode);
    }
    ,pushValue : function(){
        if(this.initialized){
            var v = this.el.dom.value;
            if(!this.activated && v.length < 1){
                v = this.defaultValue;
            }
            if(this.fireEvent('beforepush', this, v) !== false){
                v = Ext.util.Format.htmlDecode(v);
                this.getEditorBody().innerHTML = v;
                if(Ext.isGecko){
                    // Gecko hack, see: https://bugzilla.mozilla.org/show_bug.cgi?id=232791#c8
                    var d = this.doc,
                        mode = d.designMode.toLowerCase();
                    
                    d.designMode = mode.toggle('on', 'off');
                    d.designMode = mode;
                }
                this.fireEvent('push', this, v);
            }
        }
    }
    
    ,cleanHtml : function(html){
        html = String(html);
        if(html.length > 5){
            if(Ext.isWebKit){ /* strip safari nonsense */
                html = html.replace(/\sclass="(?:Apple-style-span|khtml-block-placeholder)"/gi, '');
            }
        }
        /* fix firefox bug with encoded chars */
        if (Ext.isGecko) {
            html = Ext.util.Format.htmlDecode(html);
            html = html.replace('%28','(');
            html = html.replace('%29',')');
            html = html.replace('%60','`');
            html = html.replace('%5E','^');
            html = html.replace('%7E','~');
            html = html.replace('%7B','{');
            html = html.replace('%7C','|');
            html = html.replace('%7D','}');
        } else if (Ext.isIE) {
            var exp = new RegExp(MODx.config.http_host_remote+MODx.config.base_url,'g');
            html = html.replace(exp,'');
        }
        html = html.replace('<br>','<br />');
        
        html = html.replace('modx-rte-image','');
        
        if(html == this.defaultValue){
            html = '';
        }
        return html;
    }
    
    ,tbtns: []
    ,createToolbar : function(editor){
        // build the toolbar
        var tipsEnabled = Ext.QuickTips && Ext.QuickTips.isEnabled();
        var btns = this.config.buttons || [];
        
        this.tb = new Ext.Toolbar({
            renderTo:this.wrap.dom.firstChild
            ,cls: 'modx-rte-tb'
        });
        
        var btn = {};
        for (var i=0;i< btns.length;i++) {
            btn = btns[i];
            if (btn == '-' || btn == '->' || btn == '<-') {
                this.tbtns.push(btn);
                var b = this.tb.add(btn);
                continue;
            }
            Ext.applyIf(btn,{
                cls: 'x-btn-icon'
                ,iconCls: 'x-edit-'+btn.itemId
                ,enableToggle: btn.toggle !== false
                ,scope: btn.scope || (btn.handler || this)
                ,handler: btn.handler || this.relayBtnCmd
                ,scope: btn.scope || this
                ,clickEvent: 'mousedown'
                ,overflowText: btn.tooltip.title || undefined
                ,enableOnSelection: false
                ,tabIndex: -1
            });
            var b = this.tb.addButton(btn);
            if (btn.enableOnSelection) { b.disable(); }
            this.tbtns.push(b);
        }

        // stop form submits
        this.mon(this.tb.el, 'click', function(e){
            e.preventDefault();
        });
        return this.tb;
    }
    
    
    ,checkIfPaste: function(e){
        
        var diffAt = 0;
        this.wcurLength = this.getValue().length;
        
        if (e.V == e.getKey() && e.ctrlKey && this.wordPasteEnabled){
            
            this.suspendEvents();
            
            diffAt = this.findValueDiffAt(this.getValue());
            var parts = [
                this.getValue().substr(0, diffAt),
                this.fixWordPaste(this.getValue().substr(diffAt, (this.wcurLength - this.wlastLength))),
                this.getValue().substr((this.wcurLength - this.wlastLength)+diffAt, this.wcurLength)
            ];
            this.setValue(parts.join(''));
            
            this.resumeEvents();
        }
        
        this.wlastLength = this.getValue().length;
        this.wlastValue = this.getValue();
        
    }
    ,findValueDiffAt: function(val) {        
        for (i=0;i<this.wcurLength;i++){
            if (this.wlastValue[i] != val[i]){
                return i;           
            }
        }
        
    }
    ,fixWordPaste: function(wordPaste) {        
        var removals = [/&nbsp;/ig, /[\r\n]/g, /<(xml|style)[^>]*>.*?<\/\1>/ig, /<\/?(meta|object|span)[^>]*>/ig,
            /<\/?[A-Z0-9]*:[A-Z]*[^>]*>/ig, /(lang|class|type|href|name|title|id|clear)=\"[^\"]*\"/ig, /style=(\'\'|\"\")/ig, /<![\[-].*?-*>/g, 
            /MsoNormal/g, /<\\?\?xml[^>]*>/g, /<\/?o:p[^>]*>/g, /<\/?v:[^>]*>/g, /<\/?o:[^>]*>/g, /<\/?st1:[^>]*>/g, /&nbsp;/g, 
            /<\/?SPAN[^>]*>/g, /<\/?FONT[^>]*>/g, /<\/?STRONG[^>]*>/g, /<\/?H1[^>]*>/g, /<\/?H2[^>]*>/g, /<\/?H3[^>]*>/g, /<\/?H4[^>]*>/g, 
            /<\/?H5[^>]*>/g, /<\/?H6[^>]*>/g, /<\/?P[^>]*><\/P>/g, /<!--(.*)-->/g, /<!--(.*)>/g, /<!(.*)-->/g, /<\\?\?xml[^>]*>/g, 
            /<\/?o:p[^>]*>/g, /<\/?v:[^>]*>/g, /<\/?o:[^>]*>/g, /<\/?st1:[^>]*>/g, /style=\"[^\"]*\"/g, /style=\'[^\"]*\'/g, /lang=\"[^\"]*\"/g, 
            /lang=\'[^\"]*\'/g, /class=\"[^\"]*\"/g, /class=\'[^\"]*\'/g, /type=\"[^\"]*\"/g, /type=\'[^\"]*\'/g, /href=\'#[^\"]*\'/g, 
            /href=\"#[^\"]*\"/g, /name=\"[^\"]*\"/g, /name=\'[^\"]*\'/g, / clear=\"all\"/g, /id=\"[^\"]*\"/g, /title=\"[^\"]*\"/g, 
            /<span[^>]*>/g, /<\/?span[^>]*>/g, /class=/g];
                    
        Ext.each(removals, function(s){
            wordPaste = wordPaste.replace(s, "");
        });
        
        // keep the divs in paragraphs
        wordPaste = wordPaste.replace(/<div[^>]*>/g, "<p>");
        wordPaste = wordPaste.replace(/<\/?div[^>]*>/g, "</p>");
        return wordPaste;
        
    }
    
    /* special char */
    ,loadSpecialChar: function() {
        this.storeRange();
        if (!this.windows.spchar) {
            this.windows.spchar = MODx.load({
                xtype: 'modx-rte-window-special-char'
                ,listeners: {
                    'submit': {fn:function(vs) {
                        if (vs.chars) {
                            Ext.each(vs.chars, function(rec){
                                var c = rec.get('char');
                                if (c) {
                                    this.insertAtCursor(c);
                                }
                            }, this);
                        }
                    },scope:this}
                }
            });
        } else {
            this.windows.spchar.getEl().frame();
        }
        this.windows.spchar.show();
    }
        
    /* hr */
    ,loadHrWindow: function(){
        this.storeRange();
        if (!this.windows.hr) {
            this.windows.hr = MODx.load({ 
                xtype: 'modx-rte-window-hr'
                ,listeners: {
                    'submit': {fn:function(vs) {
                        var w = vs.hrwidth;
                        if (w) {
                            this.insertAtCursor('<hr width="' + w + '">');
                        } else {
                            this.insertAtCursor('<hr width="100%">');
                        }
                    },scope:this}
                }
            });
        } else {
            this.windows.hr.getEl().frame();
        }
        this.windows.hr.show();
    }
    
    ,storeRange: function() {
        this.oldRange = null;
        if (Ext.isIE) {
            this.oldRange = this.getDoc().selection.createRange();
        }
    }
    
    /* links */
    ,loadLinkWindow: function(r){
        r = r || {};
        this.storeRange();
        if (!this.windows.link) {
            this.windows.link = MODx.load({ 
                xtype: 'modx-rte-window-link'
                ,record: r
                ,listeners: {
                    'submit': {fn:function(vs) {
                        var href = vs.href;
                        if (Ext.isIE) {
                            href = href.replace(MODx.config.site_url,'');
                        }
                        
                        var a = '<a href="'+href+'"';
                        if (vs.title != '') { a += ' title="'+vs.title+'"'; }
                        if (vs['class'] != '') { a += ' class="'+vs['class']+'"'; }
                        if (vs.style != '') { a += ' style="'+vs.style+'"'; }
                        if (vs.id != '') { a += ' id="'+vs.id+'"'; }
                        if (vs.rel != '') { a += ' rel="'+vs.rel+'"'; }
                        if (vs.rev != '') { a += ' rev="'+vs.rev+'"'; }
                        a += '>';
                        
                        this.insertTag(a,'</a>');
                    },scope:this}
                }
            });
        }
        this.windows.link.setValues(r);
        this.windows.link.show();
    }
    
    ,insertTag: function(startTag,endTag,rng) {
        var sel = false;
        if (window.getSelection) {
            sel = this.getWin().getSelection();
        } else if (document.getSelection) {
            sel = this.getDoc().getSelection();
        }
        
        if (sel.anchorNode && sel.anchorNode.nodeType == 1) {
            var n = this.selection.get();
            if (n && n.dom) {
                sel = n.dom.outerHTML != '' ? n.dom.outerHTML : n.dom.innerHTML;
            }
        }
        if (sel) {
            this.insertAtCursor(startTag+sel+endTag);
        } else if (document.selection) {
            var rng;
            if (!this.oldRange) {
                rng = this.getDoc().selection.createRange();
            } else {
                rng = this.oldRange;
            }
            rng.pasteHTML(startTag+rng.text+endTag);
        }
    }
                
    /* used to get text values of selections */
    ,getDocSelection: function() {
        var sel = false;
        if (window.getSelection) {
            sel = this.getWin().getSelection();
            if (sel.focusNode && sel.focusNode.innerHtml) {
                sel = sel.focusNode.innerHtml;
            }
        } else if (document.getSelection) {
            sel = this.getDoc().getSelection();
        }
        if (sel) {} else if (document.selection) {
            sel = this.getDoc().selection.createRange().text;
        }
        return sel;
    }
        
    
    /* table */
    ,loadTableWindow: function(){
        this.storeRange();
        if (!this.windows.table) {
            this.windows.table = MODx.load({ 
                xtype: 'modx-rte-window-table'
                ,listeners: {
                    'submit': {fn:function(vs) {
                        var border = vs.border;
                        var rowcol = [vs.row || 1,vs.col || 1];
                        if (rowcol.length == 2 && rowcol[0] > 0 && rowcol[0] < 10 && rowcol[1] > 0 && rowcol[1] < 10) {
                            var html = '<table ';
                            if (vs.cellpadding != '') { html += ' cellpadding="'+vs.cellpadding+'"'; }
                            if (vs.cellspacing != '') { html += ' cellspacing="'+vs.cellspacing+'"'; }
                            if (vs.align != '') { html += ' align="'+vs.align+'"'; }
                                                        
                            var style = '';
                            if (vs.width) { style += 'width: '+vs.width+';'; }
                            if (vs.height) { style += 'height: '+vs.height+';'; }
                            if (vs.style) { style += vs.style; }
                            html += ' style="'+style+'border-collapse: collapse;"';
                            if (vs['class']) { html += ' class="'+vs['class']+'"'; }
                            
                            if (vs.id) { html += ' id="'+vs.id+'"'; }
                            if (vs.summary) { html += ' summary="'+vs.summary+'"'; }
                            
                            html += '>';
                            for (var row = 0; row < rowcol[0]; row++) {
                                html += "<tr>";
                                for (var col = 0; col < rowcol[1]; col++) {
                                    html += "<td style=\"border: " + border + ";\">" + row + "-" + col + "</td>";
                                }
                                html += "</tr>";
                            }
                            html += "</table>";
                            this.insertAtCursor(html);
                        }
                    },scope:this}
                }
            });
        } else {
            this.windows.table.getEl().frame();
        }
        this.windows.table.show();
    }
    
    ,onEditorEvent: function(e){
        var doc = this.getDoc();
        Ext.each(this.tbtns, function(b,i) {
            if (b.enableOnSelection || b.disableOnSelection) {                
                var rng = this.selection.getRange();            
                if (rng) {
                    if ((b.enableOnSelection && rng.collapsed == false) || (b.disableOnSelection && rng.collapsed == true)) {
                        b.enable();
                    } else {
                        b.disable();
                    }
                } else {
                    b.disable();
                }
            }
            if (b.monitorCmdState) {
                b.toggle(doc.queryCommandState(b.cmd));
            }
        }, this);
        this.updateToolbar();
    }
        
    ,loadImageWindow: function(r) {
        r = r || {};
        this.storeRange();
        if (!this.windows.image) {
            this.windows.image = MODx.load({ 
                xtype: 'modx-rte-window-image'
                ,record: r
                ,listeners: {
                    'submit': {fn:function(vs) {
                        var url = vs.src;
                        var a = '<img src="'+url+'"';
                        if (vs.title != '') { a += ' title="'+vs.title+'"'; }
                        if (vs.alt != '') { a += ' alt="'+vs.alt+'"'; }
                        
                        var cls = '';
                        if (vs['class'] != '') {
                            cls += ' '+vs['class'];
                        }
                        a += ' class="'+cls+'"';
                        
                        if (vs.width != '') { a += ' title="'+vs.width+'"'; }
                        if (vs.height != '') { a += ' height="'+vs.height+'"'; }
                        if (vs.border != '') { a += ' border="'+vs.border+'"'; }
                        if (vs.align != '') { a += ' align="'+vs.align+'"'; }
                        
                        if (vs.id != '') { a += ' id="'+vs.id+'"'; }
                        if (vs.style != '') { a += ' style="'+vs.style+'"'; }
                        
                        if (vs.other != '') { a += vs.other; }
                        a += ' />';
                        this.insertAtCursor(a);
                    },scope:this}
                }
            });
        }
        this.windows.image.setValues(r);
        this.windows.image.show();
    }
        
    ,addContextMenu: function(myHandler, myTarget) {
        
        var stopDefault = function(e){
            e.stopEvent();
            e.stopPropagation();    
        };
        Ext.EventManager.addListener(myTarget, 'contextmenu', stopDefault, this,{
            stopEvent: true, 
            stopPropagation: true, 
            preventDefault: true
        });
        
        if (Ext.isIE){
            Ext.EventManager.addListener(myTarget, 'contextmenu', myHandler, this, {
                buffer: 100
            });
        } else {
            Ext.EventManager.addListener(myTarget, 'contextmenu', myHandler, this, {
                stopEvent: true, 
                stopPropagation: true, 
                preventDefault: true, 
                buffer: 100
            });
        }

        return true;
    }
});
Ext.reg('modx-richtext',MODx.rte.RTE);