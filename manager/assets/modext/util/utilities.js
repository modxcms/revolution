Ext.namespace('MODx.util.Progress');
/**
 * A JSON Reader specific to MODExt
 * 
 * @class MODx.util.JSONReader
 * @extends Ext.util.JSONReader
 * @param {Object} config An object of configuration properties
 * @xtype modx-json-reader
 */
MODx.util.JSONReader = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        successProperty:'success'
        ,totalProperty: 'total'
        ,root: 'data'
    });
    MODx.util.JSONReader.superclass.constructor.call(this,config,['id','msg']);
};
Ext.extend(MODx.util.JSONReader,Ext.data.JsonReader);
Ext.reg('modx-json-reader',MODx.util.JSONReader);

/**
 * @class MODx.util.Progress 
 */
MODx.util.Progress = {
    id: 0
    ,time: function(v,id,msg) {
        msg = msg || _('saving');
        if (MODx.util.Progress.id === id && v < 11) {
            Ext.MessageBox.updateProgress(v/10,msg);
        }
    }
    ,reset: function() {
        MODx.util.Progress.id = MODx.util.Progress.id + 1;
    }
};

/** Adds a lock mask to an element */
MODx.LockMask = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        msg: _('locked')
        ,msgCls: 'modx-lockmask'
    });
    MODx.LockMask.superclass.constructor.call(this,config.el,config);
};
Ext.extend(MODx.LockMask,Ext.LoadMask,{
    locked: false
    ,toggle: function() {
        if (this.locked) {
            this.hide();
            this.locked = false;
        } else {
            this.show();
            this.locked = true;
        }
    }
    ,lock: function() { this.locked = true; this.show(); }
    ,unlock: function() { this.locked = false; this.hide(); }
});
Ext.reg('modx-lockmask',MODx.LockMask);

/** add clearDirty to basicform */
Ext.override(Ext.form.BasicForm,{
    clearDirty : function(nodeToRecurse){
        nodeToRecurse = nodeToRecurse || this;
        nodeToRecurse.items.each(function(f){
            if(f.items){
                this.clearDirty(f);
            } else if(f.originalValue != f.getValue()){
                f.originalValue = f.getValue();
            }
        },this);
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
Ext.reg('statictextfield',MODx.StaticTextField);

/** 
 * Static Boolean
 */
MODx.StaticBoolean = Ext.extend(Ext.form.TextField, {
    fieldClass: 'x-static-text-field',

    onRender: function(tf) {
        this.readOnly = true;
        this.disabled = !this.initialConfig.submitValue;
        MODx.StaticBoolean.superclass.onRender.apply(this, arguments);
        this.on('change',this.onChange,this);
    }
    
    ,setValue: function(v) {
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
Ext.reg('staticboolean',MODx.StaticBoolean);


/****************************************************************************
 *    Ext-specific overrides/extensions                                     *
 ****************************************************************************/

Array.prototype.in_array = function(p_val) {
    for(var i=0,l=this.length;i<l;i=i+1) {
        if(this[i] === p_val) {
            return true;
        }
    }
    return false;
};


Ext.form.setCheckboxValues = function(form,id,mask) {
    var f, n=0;
    while ((f = form.findField(id+n)) !== null) {
        f.setValue((mask & (1<<n))?'true':'false');
        n=n+1;
    } 
};

Ext.form.getCheckboxMask = function(cbgroup) {
    var mask='';
    if (typeof(cbgroup) !== 'undefined') {
        if ((typeof(cbgroup)==='string')) { 
            mask = cbgroup+'';
        } else {
            for(var i=0,len=cbgroup.length;i<len;i=i+1) {
                mask += (mask !== '' ? ',' : '')+(cbgroup[i]-0);
            }
        }
    }
    return mask;
};


Ext.form.BasicForm.prototype.append = function() {
  var layout = new Ext.form.Layout();
  var fields = [];
  layout.stack.push.apply(layout.stack, arguments);
  for(var i = 0; i < arguments.length; i=i+1) {
    if(arguments[i].isFormField) {
      fields.push(arguments[i]);
    }
  }
  layout.render(this.el);

  if(fields.length > 0) {
    this.items.addAll(fields);
    for(var f=0;f<fields.length;f=f+1) {
      fields[f].render('x-form-el-' + fields[f].id);
    }
  }
  return this;
};


Ext.form.AMPMField = function(id,v) {
    return new Ext.form.ComboBox({
        store: new Ext.data.SimpleStore({
            fields: ['ampm']
            ,data: [['am'],['pm']]
        })
        ,displayField: 'ampm'
        ,hiddenName: id
        ,mode: 'local'
        ,editable: false
        ,forceSelection: true
        ,triggerAction: 'all'
        ,width: 60
        ,value: v || 'am'
    });
};

Ext.form.HourField = function(id,name,v){
    return new Ext.form.ComboBox({
        store: new Ext.data.SimpleStore({
            fields: ['hour']
            ,data: [[1],[2],[3],[4],[5],[6],[7],[8],[9],[10],[11],[12]]
        })
        ,displayField: 'hour'
        ,mode: 'local'
        ,triggerAction: 'all'
        ,width: 60
        ,forceSelection: true
        ,rowHeight: false
        ,editable: false
        ,value: v || 1
        ,transform: id
    }); 
};


Ext.override(Ext.tree.TreeNodeUI,{
    hasClass : function(className){
        var el = Ext.fly(this.elNode);
        return className && (' '+el.dom.className+' ').indexOf(' '+className+' ') !== -1;
    }
});


/* allows for messages in JSON responses */
Ext.override(Ext.form.Action.Submit,{         
    handleResponse : function(response){        
        var m = Ext.decode(response.responseText); /* shaun 7/11/07 */
        if (this.form.errorReader) {
            var rs = this.form.errorReader.read(response);
            var errors = [];
            if (rs.records) {
                for(var i = 0, len = rs.records.length; i < len; i=i+1) {
                    var r = rs.records[i];
                    errors[i] = r.data;
                }
            }
            if (errors.length < 1) { errors = null; }
            return {
                success : rs.success
                ,message : m.message /* shaun 7/11/07 */
                ,object : m.object /* shaun 7/18/07 */
                ,errors : errors
            };
        }
        return Ext.decode(response.responseText);
    }
});

/* QTips to form fields */
Ext.form.Field.prototype.afterRender = Ext.form.Field.prototype.afterRender.createSequence(function() { 
    if (this.description) {
        Ext.QuickTips.register({
            target:  this.getEl()
            ,text: this.description
            ,enabled: true
        });
        var label = Ext.form.Field.findLabel(this);
        if(label){
            Ext.QuickTips.register({
                target:  label
                ,text: this.description
                ,enabled: true
            });
        }
    }
});
Ext.applyIf(Ext.form.Field,{
    findLabel: function(field) {
        var wrapDiv = null;
        var label = null;
        wrapDiv = field.getEl().up('div.x-form-element');
        if(wrapDiv){
            label = wrapDiv.child('label');
        }
        if(label){
            return label;
        }
        wrapDiv = field.getEl().up('div.x-form-item');
        if(wrapDiv) {
            label = wrapDiv.child('label');        
        }
        if(label){
            return label;
        }
    }
});

/* allow copying to clipboard */
MODx.util.Clipboard = function() {
    return {
        escape: function(text){
            text = encodeURIComponent(text);
            return text.replace(/%0A/g, "%0D%0A");
        }
        
        ,copy: function(text){
            if (Ext.isIE) {
                window.clipboardData.setData("Text", text);
            } else {
                var flashcopier = 'flashcopier';
                if (!document.getElementById(flashcopier)) {
                    var divholder = document.createElement('div');
                    divholder.id = flashcopier;
                    document.body.appendChild(divholder);
                }                
                document.getElementById(flashcopier).innerHTML = '';                
                var divinfo = '<embed src="' + MODx.config.manager_url
                    + 'assets/modext/_clipboard.swf" FlashVars="clipboard=' 
                    + MODx.util.Clipboard.escape(text)
                    + '" width="0" height="0" type="application/x-shockwave-flash"></embed>';
                document.getElementById(flashcopier).innerHTML = divinfo;
            }
        }
    };
}();

/* rowactions plugin */
Ext.ns('Ext.ux.grid');if('function'!==typeof RegExp.escape){RegExp.escape=function(s){if('string'!==typeof s){return s}return s.replace(/([.*+?\^=!:${}()|\[\]\/\\])/g,'\\$1')}}Ext.ux.grid.RowActions=function(a){Ext.apply(this,a);this.addEvents('beforeaction','action','beforegroupaction','groupaction');Ext.ux.grid.RowActions.superclass.constructor.call(this)};Ext.extend(Ext.ux.grid.RowActions,Ext.util.Observable,{actionEvent:'click',autoWidth:true,dataIndex:'',editable:false,header:'',isColumn:true,keepSelection:false,menuDisabled:true,sortable:false,tplGroup:'<tpl for="actions">'+'<div class="ux-grow-action-item<tpl if="\'right\'===align"> ux-action-right</tpl> '+'{cls}" style="{style}" qtip="{qtip}">{text}</div>'+'</tpl>',tplRow:'<div class="ux-row-action">'+'<tpl for="actions">'+'<div class="ux-row-action-item {cls} <tpl if="text">'+'ux-row-action-text</tpl>" style="{hide}{style}" qtip="{qtip}">'+'<tpl if="text"><span qtip="{qtip}">{text}</span></tpl></div>'+'</tpl>'+'</div>',hideMode:'visibility',widthIntercept:4,widthSlope:21,init:function(g){this.grid=g;this.id=this.id||Ext.id();var h=g.getColumnModel().lookup;delete(h[undefined]);h[this.id]=this;if(!this.tpl){this.tpl=this.processActions(this.actions)}if(this.autoWidth){this.width=this.widthSlope*this.actions.length+this.widthIntercept;this.fixed=true}var i=g.getView();var j={scope:this};j[this.actionEvent]=this.onClick;g.afterRender=g.afterRender.createSequence(function(){i.mainBody.on(j);g.on('destroy',this.purgeListeners,this)},this);if(!this.renderer){this.renderer=function(a,b,c,d,e,f){b.css+=(b.css?' ':'')+'ux-row-action-cell';return this.tpl.apply(this.getData(a,b,c,d,e,f))}.createDelegate(this)}if(i.groupTextTpl&&this.groupActions){i.interceptMouse=i.interceptMouse.createInterceptor(function(e){if(e.getTarget('.ux-grow-action-item')){return false}});i.groupTextTpl='<div class="ux-grow-action-text">'+i.groupTextTpl+'</div>'+this.processActions(this.groupActions,this.tplGroup).apply()}if(true===this.keepSelection){g.processEvent=g.processEvent.createInterceptor(function(a,e){if('mousedown'===a){return!this.getAction(e)}},this)}},getData:function(a,b,c,d,e,f){return c.data||{}},processActions:function(b,c){var d=[];Ext.each(b,function(a,i){if(a.iconCls&&'function'===typeof(a.callback||a.cb)){this.callbacks=this.callbacks||{};this.callbacks[a.iconCls]=a.callback||a.cb}var o={cls:a.iconIndex?'{'+a.iconIndex+'}':(a.iconCls?a.iconCls:''),qtip:a.qtipIndex?'{'+a.qtipIndex+'}':(a.tooltip||a.qtip?a.tooltip||a.qtip:''),text:a.textIndex?'{'+a.textIndex+'}':(a.text?a.text:''),hide:a.hideIndex?'<tpl if="'+a.hideIndex+'">'+('display'===this.hideMode?'display:none':'visibility:hidden')+';</tpl>':(a.hide?('display'===this.hideMode?'display:none':'visibility:hidden;'):''),align:a.align||'right',style:a.style?a.style:''};d.push(o)},this);var e=new Ext.XTemplate(c||this.tplRow);return new Ext.XTemplate(e.apply({actions:d}))},getAction:function(e){var a=false;var t=e.getTarget('.ux-row-action-item');if(t){a=t.className.replace(/ux-row-action-item /,'');if(a){a=a.replace(/ ux-row-action-text/,'');a=a.trim()}}return a},onClick:function(e,a){var b=this.grid.getView();var c=e.getTarget('.x-grid3-row');var d=b.findCellIndex(a.parentNode.parentNode);var f=this.getAction(e);if(false!==c&&false!==d&&false!==f){var g=this.grid.store.getAt(c.rowIndex);if(this.callbacks&&'function'===typeof this.callbacks[f]){this.callbacks[f](this.grid,g,f,c.rowIndex,d)}if(true!==this.eventsSuspended&&false===this.fireEvent('beforeaction',this.grid,g,f,c.rowIndex,d)){return}else if(true!==this.eventsSuspended){this.fireEvent('action',this.grid,g,f,c.rowIndex,d)}}t=e.getTarget('.ux-grow-action-item');if(t){var h=b.findGroup(a);var i=h?h.id.replace(/ext-gen[0-9]+-gp-/,''):null;var j;if(i){var k=new RegExp(RegExp.escape(i));j=this.grid.store.queryBy(function(r){return r._groupId.match(k)});j=j?j.items:[]}f=t.className.replace(/ux-grow-action-item (ux-action-right )*/,'');if('function'===typeof this.callbacks[f]){this.callbacks[f](this.grid,j,f,i)}if(true!==this.eventsSuspended&&false===this.fireEvent('beforegroupaction',this.grid,j,f,i)){return false}this.fireEvent('groupaction',this.grid,j,f,i)}}});Ext.reg('rowactions',Ext.ux.grid.RowActions);

/* switchbutton */
Ext.SwitchButton=Ext.extend(Ext.Component,{initComponent:function(){Ext.SwitchButton.superclass.initComponent.call(this);var a=new Ext.util.MixedCollection();a.addAll(this.items);this.items=a;this.addEvents('change');if(this.handler){this.on('change',this.handler,this.scope||this)}},onRender:function(a,b){var c=document.createElement('table');c.cellSpacing=0;c.className='x-rbtn';c.id=this.id;var d=document.createElement('tr');c.appendChild(d);var e=this.items.length;var f=e-1;this.activeItem=this.items.get(this.activeItem);for(var i=0;i<e;i++){var g=this.items.itemAt(i);var h=d.appendChild(document.createElement('td'));h.id=this.id+'-rbi-'+i;var j=i===0?'x-rbtn-first':(i==f?'x-rbtn-last':'x-rbtn-item');g.baseCls=j;if(this.activeItem==g){j+='-active'}h.className=j;var k=document.createElement('button');k.innerHTML='&#160;';k.className=g.iconCls;k.qtip=g.tooltip;h.appendChild(k);g.cell=h}this.el=Ext.get(a.dom.appendChild(c));this.el.on('click',this.onClick,this)},getActiveItem:function(){return this.activeItem},setActiveItem:function(a){if(typeof a!='object'&&a!==null){a=this.items.get(a)}var b=this.getActiveItem();if(a!=b){if(b){Ext.fly(b.cell).removeClass(b.baseCls+'-active')}if(a){Ext.fly(a.cell).addClass(a.baseCls+'-active')}this.activeItem=a;this.fireEvent('change',this,a)}return a},onClick:function(e){var a=e.getTarget('td',2);if(!this.disabled&&a){this.setActiveItem(parseInt(a.id.split('-rbi-')[1],10))}}});Ext.reg('switch',Ext.SwitchButton);

/* upload panel */
Ext.namespace('Ext.ux.form');Ext.ux.form.BrowseButton=Ext.extend(Ext.Button,{inputFileName:'file',debug:false,FLOAT_EL_WIDTH:60,FLOAT_EL_HEIGHT:18,buttonCt:null,clipEl:null,floatEl:null,inputFileEl:null,originalHandler:null,originalScope:null,initComponent:function(){Ext.ux.form.BrowseButton.superclass.initComponent.call(this);this.originalHandler=this.handler||null;this.originalScope=this.scope||window;this.handler=null;this.scope=null},onRender:function(a,b){Ext.ux.form.BrowseButton.superclass.onRender.call(this,a,b);this.buttonCt=this.el.child('.x-btn-mc em');this.buttonCt.position('relative');var c={position:'absolute',overflow:'hidden',top:'0px',left:'0px'};if(Ext.isIE){Ext.apply(c,{left:'-3px',top:'-3px'})}else if(Ext.isGecko){Ext.apply(c,{left:'-3px',top:'-3px'})}else if(Ext.isSafari){Ext.apply(c,{left:'-4px',top:'-2px'})}this.clipEl=this.buttonCt.createChild({tag:'div',style:c});this.setClipSize();this.clipEl.on({'mousemove':this.onButtonMouseMove,'mouseover':this.onButtonMouseMove,scope:this});this.floatEl=this.clipEl.createChild({tag:'div',style:{position:'absolute',width:this.FLOAT_EL_WIDTH+'px',height:this.FLOAT_EL_HEIGHT+'px',overflow:'hidden'}});if(this.debug){this.clipEl.applyStyles({'background-color':'green'});this.floatEl.applyStyles({'background-color':'red'})}else{this.clipEl.setOpacity(0.0)}this.createInputFile()},setClipSize:function(){if(this.clipEl){var a=this.buttonCt.getWidth();var b=this.buttonCt.getHeight();if(Ext.isIE){a=a+5;b=b+5}else if(Ext.isGecko){a=a+6;b=b+6}else if(Ext.isSafari){a=a+6;b=b+6}this.clipEl.setSize(a,b)}},createInputFile:function(){this.inputFileEl=this.floatEl.createChild({tag:'input',type:'file',size:1,name:this.inputFileName||Ext.id(this.el),style:{position:'absolute',cursor:'pointer',right:'0px',top:Ext.isIE?'10px':'0px'}});this.inputFileEl=this.inputFileEl.child('input')||this.inputFileEl;this.inputFileEl.on({'click':this.onInputFileClick,'change':this.onInputFileChange,scope:this});if(this.tooltip){if(typeof this.tooltip=='object'){Ext.QuickTips.register(Ext.apply({target:this.inputFileEl},this.tooltip))}else{this.inputFileEl.dom[this.tooltipType]=this.tooltip}}},onButtonMouseMove:function(e){var a=e.getXY();a[0]-=this.FLOAT_EL_WIDTH/2;a[1]-=this.FLOAT_EL_HEIGHT/2;this.floatEl.setXY(a)},onInputFileClick:function(e){e.stopPropagation()},onInputFileChange:function(){if(this.originalHandler){this.originalHandler.call(this.originalScope,this)}},detachInputFile:function(a){var b=this.inputFileEl;if(typeof this.tooltip=='object'){Ext.QuickTips.unregister(this.inputFileEl)}else{this.inputFileEl.dom[this.tooltipType]=null}this.inputFileEl.removeAllListeners();this.inputFileEl=null;if(!a){this.createInputFile()}return b},getInputFile:function(){return this.inputFileEl},disable:function(){Ext.ux.form.BrowseButton.superclass.disable.call(this);this.inputFileEl.dom.disabled=true},enable:function(){Ext.ux.form.BrowseButton.superclass.enable.call(this);this.inputFileEl.dom.disabled=false}});Ext.reg('browsebutton',Ext.ux.form.BrowseButton);Ext.ux.FileUploader=function(a){Ext.apply(this,a);Ext.ux.FileUploader.superclass.constructor.apply(this,arguments);this.addEvents('beforeallstart','allfinished','beforefilestart','filefinished','progress')};Ext.extend(Ext.ux.FileUploader,Ext.util.Observable,{baseParams:{cmd:'upload',dir:'.'},concurrent:true,enableProgress:true,jsonErrorText:'Cannot decode JSON object',maxFileSize:524288,progressIdName:'UPLOAD_IDENTIFIER',progressInterval:2000,progressUrl:'progress.php',progressMap:{bytes_total:'bytesTotal',bytes_uploaded:'bytesUploaded',est_sec:'estSec',files_uploaded:'filesUploaded',speed_average:'speedAverage',speed_last:'speedLast',time_last:'timeLast',time_start:'timeStart'},singleUpload:false,unknownErrorText:'Unknown error',upCount:0,createForm:function(a){var b=parseInt(Math.random()*1e10,10);var c=Ext.getBody().createChild({tag:'form',action:this.url,method:'post',cls:'x-hidden',id:Ext.id(),cn:[{tag:'input',type:'hidden',name:'APC_UPLOAD_PROGRESS',value:b},{tag:'input',type:'hidden',name:this.progressIdName,value:b},{tag:'input',type:'hidden',name:'MAX_FILE_SIZE',value:this.maxFileSize}]});if(a){if(Ext.isIE)a.set('form',undefined);else a.set('form',c);a.set('progressId',b)}else{this.progressId=b}return c},deleteForm:function(a,b){a.remove();if(b){b.set('form',null)}},fireFinishEvents:function(a){if(true!==this.eventsSuspended&&!this.singleUpload){this.fireEvent('filefinished',this,a&&a.record)}if(true!==this.eventsSuspended&&0===this.upCount){this.stopProgress();this.fireEvent('allfinished',this)}},getIframe:function(a){var b=null;var c=a.get('form');if(c&&c.dom&&c.dom.target){b=Ext.get(c.dom.target)}return b},getOptions:function(a,b){var o={url:this.url,method:'post',isUpload:true,scope:this,callback:this.uploadCallback,record:a,params:this.getParams(a,b)};return o},getParams:function(a,b){var p={path:this.path};Ext.apply(p,this.baseParams||{},b||{});return p},processSuccess:function(a,b,o){var c=false;if(this.singleUpload){this.store.each(function(r){r.set('state','done');r.set('error','');r.commit()})}else{c=a.record;c.set('state','done');c.set('error','');c.commit()}this.deleteForm(a.form,c)},processFailure:function(b,c,d){var f=b.record;var g;if(this.singleUpload){g=this.store.queryBy(function(r){return'done'!==r.get('state')});g.each(function(a){var e=d.errors?d.errors[a.id]:this.unknownErrorText;if(e){a.set('state','failed');a.set('error',e);Ext.getBody().appendChild(a.get('input'))}else{a.set('state','done');a.set('error','')}a.commit()},this);this.deleteForm(b.form)}else{if(d&&'object'===Ext.type(d)){f.set('error',d.errors&&d.errors[f.id]?d.errors[f.id]:this.unknownErrorText)}else if(d){f.set('error',d)}else if(c&&c.responseText){f.set('error',c.responseText)}else{f.set('error',this.unknownErrorText)}f.set('state','failed');f.commit()}},requestProgress:function(){var d,p;var o={url:this.progressUrl,method:'post',params:{},scope:this,callback:function(a,b,c){var o;if(true!==b){return}try{o=Ext.decode(c.responseText)}catch(e){return}if('object'!==Ext.type(o)||true!==o.success){return}if(this.singleUpload){this.progress={};for(p in o){if(this.progressMap[p]){this.progress[this.progressMap[p]]=parseInt(o[p],10)}}if(true!==this.eventsSuspended){this.fireEvent('progress',this,this.progress)}}else{for(p in o){if(this.progressMap[p]&&a.record){a.record.set(this.progressMap[p],parseInt(o[p],10))}}if(a.record){a.record.commit();if(true!==this.eventsSuspended){this.fireEvent('progress',this,a.record.data,a.record)}}}this.progressTask.delay(this.progressInterval)}};if(this.singleUpload){o.params[this.progressIdName]=this.progressId;o.params.APC_UPLOAD_PROGRESS=this.progressId;Ext.Ajax.request(o)}else{d=this.store.query('state','uploading');d.each(function(r){o.params[this.progressIdName]=r.get('progressId');o.params.APC_UPLOAD_PROGRESS=o.params[this.progressIdName];o.record=r;(function(){Ext.Ajax.request(o)}).defer(250)},this)}},setPath:function(a){this.path=a},setUrl:function(a){this.url=a},startProgress:function(){if(!this.progressTask){this.progressTask=new Ext.util.DelayedTask(this.requestProgress,this)}this.progressTask.delay.defer(this.progressInterval/2,this.progressTask,[this.progressInterval])},stopProgress:function(){if(this.progressTask){this.progressTask.cancel()}},stopAll:function(){var a=this.store.query('state','uploading');a.each(this.stopUpload,this)},stopUpload:function(a){var b=false;if(a){b=this.getIframe(a);this.stopIframe(b);this.upCount--;this.upCount=0>this.upCount?0:this.upCount;a.set('state','stopped');this.fireFinishEvents({record:a})}else if(this.form){b=Ext.fly(this.form.dom.target);this.stopIframe(b);this.upCount=0;this.fireFinishEvents()}},stopIframe:function(a){if(a){try{a.dom.contentWindow.stop();a.remove.defer(250,a)}catch(e){}}},upload:function(){var a=this.store.queryBy(function(r){return'done'!==r.get('state')});if(!a.getCount()){return}if(true!==this.eventsSuspended&&false===this.fireEvent('beforeallstart',this)){return}if(this.singleUpload){this.uploadSingle()}else{a.each(this.uploadFile,this)}if(true===this.enableProgress){this.startProgress()}},uploadCallback:function(a,b,c){var o;this.upCount--;this.form=false;if(true===b){try{o=Ext.decode(c.responseText)}catch(e){this.processFailure(a,c,this.jsonErrorText);this.fireFinishEvents(a);return}if(true===o.success){this.processSuccess(a,c,o)}else{this.processFailure(a,c,o)}}else{this.processFailure(a,c)}this.fireFinishEvents(a)},uploadFile:function(a,b){if(true!==this.eventsSuspended&&false===this.fireEvent('beforefilestart',this,a)){return}var c=this.createForm(a);var d=a.get('input');d.set({name:d.id});c.appendChild(d);var o=this.getOptions(a,b);o.form=c;a.set('state','uploading');a.set('pctComplete',0);this.upCount++;Ext.Ajax.request(o);this.getIframe.defer(100,this,[a])},uploadSingle:function(){var c=this.store.queryBy(function(r){return'done'!==r.get('state')});if(!c.getCount()){return}var d=this.createForm();c.each(function(a){var b=a.get('input');b.set({name:b.id});d.appendChild(b);a.set('state','uploading')},this);var o=this.getOptions();o.form=d;this.form=d;this.upCount++;Ext.Ajax.request(o)}});Ext.reg('fileuploader',Ext.ux.FileUploader);Ext.ux.UploadPanel=Ext.extend(Ext.Panel,{addIconCls:'icon-plus',addText:_('file_cm_addText'),bodyStyle:'padding:2px',buttonsAt:'tbar',clickRemoveText:_('file_cm_clickRemoveText'),clickStopText:_('file_cm_clickStopText'),emptyText:_('file_cm_emptyText'),enableProgress:true,errorText:'Error',fileCls:'file',fileQueuedText:_('file_cm_fileQueuedText'),fileDoneText:_('file_cm_fileDoneText'),fileFailedText:_('file_cm_fileFailedText'),fileStoppedText:_('file_cm_fileStoppedText'),fileUploadingText:_('file_cm_fileUploadingText'),maxFileSize:10485760,maxLength:18,removeAllIconCls:'icon-cross',removeAllText:_('file_cm_removeAllText'),removeIconCls:'icon-minus',removeText:_('file_cm_removeText'),selectedClass:'ux-up-item-selected',singleUpload:false,stopAllText:_('file_cm_stopAllText'),stopIconCls:'icon-stop',uploadText:_('file_cm_uploadText'),uploadIconCls:'icon-upload',workingIconCls:'icon-working',initComponent:function(){var a={xtype:'browsebutton',text:this.addText+'...',iconCls:this.addIconCls,scope:this,handler:this.onAddFile};var b={xtype:'button',iconCls:this.uploadIconCls,text:this.uploadText,scope:this,handler:this.onUpload,disabled:true};var c={xtype:'button',iconCls:this.removeAllIconCls,tooltip:this.removeAllText,scope:this,handler:this.onRemoveAllClick,disabled:true};if('body'!==this.buttonsAt){this[this.buttonsAt]=[a,b,'->',c]}var d=[{name:'id',type:'text',system:true},{name:'shortName',type:'text',system:true},{name:'fileName',type:'text',system:true},{name:'filePath',type:'text',system:true},{name:'fileCls',type:'text',system:true},{name:'input',system:true},{name:'form',system:true},{name:'state',type:'text',system:true},{name:'error',type:'text',system:true},{name:'progressId',type:'int',system:true},{name:'bytesTotal',type:'int',system:true},{name:'bytesUploaded',type:'int',system:true},{name:'estSec',type:'int',system:true},{name:'filesUploaded',type:'int',system:true},{name:'speedAverage',type:'int',system:true},{name:'speedLast',type:'int',system:true},{name:'timeLast',type:'int',system:true},{name:'timeStart',type:'int',system:true},{name:'pctComplete',type:'int',system:true}];if(Ext.isArray(this.customFields)){d.push(this.customFields)}this.store=new Ext.data.SimpleStore({id:0,fields:d,data:[]});Ext.apply(this,{items:[{xtype:'dataview',itemSelector:'div.ux-up-item',store:this.store,selectedClass:this.selectedClass,singleSelect:true,emptyText:this.emptyText,tpl:this.tpl||new Ext.XTemplate('<tpl for=".">'+'<div class="ux-up-item">'+'<div class="ux-up-icon-file {fileCls}">&#160;</div>'+'<div class="ux-up-text x-unselectable" qtip="{fileName}">{shortName}</div>'+'<div id="remove-{[values.input.id]}" class="ux-up-icon-state ux-up-icon-{state}"'+'qtip="{[this.scope.getQtip(values)]}">&#160;</div>'+'</div>'+'</tpl>',{scope:this}),listeners:{click:{scope:this,fn:this.onViewClick}}}],width:300});Ext.ux.UploadPanel.superclass.initComponent.apply(this,arguments);this.view=this.items.itemAt(0);this.addEvents('beforefileadd','fileadd','beforefileremove','fileremove','beforequeueclear','queueclear','beforeupload');this.relayEvents(this.view,['beforeclick','beforeselect','click','containerclick','contextmenu','dblclick','selectionchange']);var e={store:this.store,singleUpload:this.singleUpload,maxFileSize:this.maxFileSize,enableProgress:this.enableProgress,url:this.url,path:this.path};if(this.baseParams){e.baseParams=this.baseParams}this.uploader=new Ext.ux.FileUploader(e);this.relayEvents(this.uploader,['beforeallstart','allfinished','progress']);this.on({beforeallstart:{scope:this,fn:function(){this.uploading=true;this.updateButtons()}},allfinished:{scope:this,fn:function(){this.uploading=false;this.updateButtons()}},progress:{fn:this.onProgress.createDelegate(this)}})},onRender:function(){Ext.ux.UploadPanel.superclass.onRender.apply(this,arguments);var a='tbar'===this.buttonsAt?this.getTopToolbar():this.getBottomToolbar();this.addBtn=Ext.getCmp(a.items.first().id);this.uploadBtn=Ext.getCmp(a.items.itemAt(1).id);this.removeAllBtn=Ext.getCmp(a.items.last().id)},getQtip:function(a){var b='';switch(a.state){case'queued':b=String.format(this.fileQueuedText,a.fileName);b+='<br>'+this.clickRemoveText;break;case'uploading':b=String.format(this.fileUploadingText,a.fileName);b+='<br>'+a.pctComplete+'% done';b+='<br>'+this.clickStopText;break;case'done':b=String.format(this.fileDoneText,a.fileName);b+='<br>'+this.clickRemoveText;break;case'failed':b=String.format(this.fileFailedText,a.fileName);b+='<br>'+this.errorText+':'+a.error;b+='<br>'+this.clickRemoveText;break;case'stopped':b=String.format(this.fileStoppedText,a.fileName);b+='<br>'+this.clickRemoveText;break}return b},getFileName:function(a){return a.getValue().split(/[\/\\]/).pop()},getFilePath:function(a){return a.getValue().replace(/[^\/\\]+$/,'')},getFileCls:function(a){var b=a.split('.');if(1===b.length){return this.fileCls}else{return this.fileCls+'-'+b.pop().toLowerCase()}},onAddFile:function(a){if(true!==this.eventsSuspended&&false===this.fireEvent('beforefileadd',this,a.getInputFile())){return}var b=a.detachInputFile();b.addClass('x-hidden');var c=this.getFileName(b);var d=new this.store.recordType({input:b,fileName:c,filePath:this.getFilePath(b),shortName:Ext.util.Format.ellipsis(c,this.maxLength),fileCls:this.getFileCls(c),state:'queued'},b.id);d.commit();this.store.add(d);this.syncShadow();this.uploadBtn.enable();this.removeAllBtn.enable();this.ownerCt.doLayout();if(true!==this.eventsSuspended){this.fireEvent('fileadd',this,this.store,d)}},onDestroy:function(){if(this.uploader){this.uploader.stopAll();this.uploader.purgeListeners();this.uploader=null}if(this.view){this.view.purgeListeners();this.view.destroy();this.view=null}if(this.store){this.store.purgeListeners();this.store.destroy();this.store=null}},onProgress:function(a,b,c){var d,bytesUploaded,pctComplete,state,idx,item,width,pgWidth;if(c){state=c.get('state');d=c.get('bytesTotal')||1;bytesUploaded=c.get('bytesUploaded')||0;if('uploading'===state){pctComplete=Math.round(1000*bytesUploaded/d)/10}else if('done'==='state'){pctComplete=100}else{pctComplete=0}c.set('pctComplete',pctComplete);idx=this.store.indexOf(c);item=Ext.get(this.view.getNode(idx));if(item){width=item.getWidth();item.applyStyles({'background-position':width*pctComplete/100+'px'})}}},onRemoveFile:function(a){if(true!==this.eventsSuspended&&false===this.fireEvent('beforefileremove',this,this.store,a)){return}var b=a.get('input');var c=b.up('em');b.remove();if(c){c.remove()}this.store.remove(a);var d=this.store.getCount();this.uploadBtn.setDisabled(!d);this.removeAllBtn.setDisabled(!d);if(true!==this.eventsSuspended){this.fireEvent('fileremove',this,this.store);this.syncShadow()}},onRemoveAllClick:function(a){if(true===this.uploading){this.stopAll()}else{this.removeAll()}},stopAll:function(){this.uploader.stopAll()},onViewClick:function(a,b,c,e){var t=e.getTarget('div:any(.ux-up-icon-queued|.ux-up-icon-failed|.ux-up-icon-done|.ux-up-icon-stopped)');if(t){this.onRemoveFile(this.store.getAt(b))}t=e.getTarget('div.ux-up-icon-uploading');if(t){this.uploader.stopUpload(this.store.getAt(b))}},onUpload:function(){if(true!==this.eventsSuspended&&false===this.fireEvent('beforeupload',this)){return false}this.uploader.upload()},setUrl:function(a){this.url=a;this.uploader.setUrl(a)},setPath:function(a){this.uploader.setPath(a)},updateButtons:function(){if(true===this.uploading){this.addBtn.disable();this.uploadBtn.disable();this.removeAllBtn.setIconClass(this.stopIconCls);this.removeAllBtn.getEl().child(this.removeAllBtn.buttonSelector).dom[this.removeAllBtn.tooltipType]=this.stopAllText}else{this.addBtn.enable();this.uploadBtn.enable();this.removeAllBtn.setIconClass(this.removeAllIconCls);this.removeAllBtn.getEl().child(this.removeAllBtn.buttonSelector).dom[this.removeAllBtn.tooltipType]=this.removeAllText}},removeAll:function(){var a=this.eventsSuspended;if(false!==this.eventsSuspended&&false===this.fireEvent('beforequeueclear',this,this.store)){return false}this.suspendEvents();this.store.each(this.onRemoveFile,this);this.eventsSuspended=a;if(true!==this.eventsSuspended){this.fireEvent('queueclear',this,this.store)}this.syncShadow()},syncShadow:function(){if(this.contextmenu&&this.contextmenu.shadow){this.contextmenu.getEl().shadow.show(this.contextmenu.getEl())}}});Ext.reg('uploadpanel',Ext.ux.UploadPanel);

Ext.onReady(function() {
    MODx.util.JSONReader = MODx.load({ xtype: 'modx-json-reader' });
    MODx.form.Handler = MODx.load({ xtype: 'modx-form-handler' });
    MODx.msg = MODx.load({ xtype: 'modx-msg' });
});