Ext.namespace('Ext.ux.form');

/**
 * @class Ext.ux.form.BrowseButton
 * @extends Ext.Button
 * Ext.Button that provides a customizable file browse button.
 * Clicking this button, pops up a file dialog box for a user to select the file to upload.
 * This is accomplished by having a transparent <input type="file"> box above the Ext.Button.
 * When a user thinks he or she is clicking the Ext.Button, they're actually clicking the hidden input "Browse..." box.
 * Note: this class can be instantiated explicitly or with xtypes anywhere a regular Ext.Button can be except in 2 scenarios:
 * - Panel.addButton method both as an instantiated object or as an xtype config object.
 * - Panel.buttons config object as an xtype config object.
 * These scenarios fail because Ext explicitly creates an Ext.Button in these cases.
 * Browser compatibility:
 * Internet Explorer 6:
 * - no issues
 * Internet Explorer 7:
 * - no issues
 * Firefox 2 - Windows:
 * - pointer cursor doesn't display when hovering over the button.
 * Safari 3 - Windows:
 * - no issues.
 * @author loeppky - based on the work done by MaximGB in Ext.ux.UploadDialog (http://extjs.com/forum/showthread.php?t=21558)
 * The follow the curosr float div idea also came from MaximGB.
 * @see http://extjs.com/forum/showthread.php?t=29032
 * @constructor
 * Create a new BrowseButton.
 * @param {Object} config Configuration options
 */
Ext.ux.form.BrowseButton = Ext.extend(Ext.Button, {
	/*
	 * Config options:
	 */
	/**
	 * @cfg {String} inputFileName
	 * Name to use for the hidden input file DOM element.  Deaults to "file".
	 */
	inputFileName: 'file',
	/**
	 * @cfg {Boolean} debug
	 * Toggle for turning on debug mode.
	 * Debug mode doesn't make clipEl transparent so that one can see how effectively it covers the Ext.Button.
	 * In addition, clipEl is given a green background and floatEl a red background to see how well they are positioned.
	 */
	debug: false,
	
	
	/*
	 * Private constants:
	 */
	/**
	 * @property FLOAT_EL_WIDTH
	 * @type Number
	 * The width (in pixels) of floatEl.
	 * It should be less than the width of the IE "Browse" button's width (65 pixels), since IE doesn't let you resize it.
	 * We define this width so we can quickly center floatEl at the mouse cursor without having to make any function calls.
	 * @private
	 */
	FLOAT_EL_WIDTH: 60,
	
	/**
	 * @property FLOAT_EL_HEIGHT
	 * @type Number
	 * The heigh (in pixels) of floatEl.
	 * It should be less than the height of the "Browse" button's height.
	 * We define this height so we can quickly center floatEl at the mouse cursor without having to make any function calls.
	 * @private
	 */
	FLOAT_EL_HEIGHT: 18,
	
	
	/*
	 * Private properties:
	 */
	/**
	 * @property buttonCt
	 * @type Ext.Element
	 * Element that contains the actual Button DOM element.
	 * We store a reference to it, so we can easily grab its size for sizing the clipEl.
	 * @private
	 */
	buttonCt: null,
	/**
	 * @property clipEl
	 * @type Ext.Element
	 * Element that contains the floatEl.
	 * This element is positioned to fill the area of Ext.Button and has overflow turned off.
	 * This keeps floadEl tight to the Ext.Button, and prevents it from masking surrounding elements.
	 * @private
	 */
	clipEl: null,
	/**
	 * @property floatEl
	 * @type Ext.Element
	 * Element that contains the inputFileEl.
	 * This element is size to be less than or equal to the size of the input file "Browse" button.
	 * It is then positioned wherever the user moves the cursor, so that their click always clicks the input file "Browse" button.
	 * Overflow is turned off to preven inputFileEl from masking surrounding elements.
	 * @private
	 */
	floatEl: null,
	/**
	 * @property inputFileEl
	 * @type Ext.Element
	 * Element for the hiden file input.
	 * @private
	 */
	inputFileEl: null,
	/**
	 * @property originalHandler
	 * @type Function
	 * The handler originally defined for the Ext.Button during construction using the "handler" config option.
	 * We need to null out the "handler" property so that it is only called when a file is selected.
	 * @private
	 */
	originalHandler: null,
	/**
	 * @property originalScope
	 * @type Object
	 * The scope originally defined for the Ext.Button during construction using the "scope" config option.
	 * While the "scope" property doesn't need to be nulled, to be consistent with originalHandler, we do.
	 * @private
	 */
	originalScope: null,
	
	
	/*
	 * Protected Ext.Button overrides
	 */
	/**
	 * @see Ext.Button.initComponent
	 */
	initComponent: function(){
		Ext.ux.form.BrowseButton.superclass.initComponent.call(this);
		// Store references to the original handler and scope before nulling them.
		// This is done so that this class can control when the handler is called.
		// There are some cases where the hidden file input browse button doesn't completely cover the Ext.Button.
		// The handler shouldn't be called in these cases.  It should only be called if a new file is selected on the file system.  
		this.originalHandler = this.handler || null;
		this.originalScope = this.scope || window;
		this.handler = null;
		this.scope = null;
	},
	
	/**
	 * @see Ext.Button.onRender
	 */
	onRender: function(ct, position){
		Ext.ux.form.BrowseButton.superclass.onRender.call(this, ct, position); // render the Ext.Button
		//this.buttonCt = this.el.child('.x-btn-center em'); /* shaun 20090921 */
        this.buttonCt = this.el.child('.x-btn-mc em');
		this.buttonCt.position('relative'); // this is important!
		var styleCfg = {
			position: 'absolute',
			overflow: 'hidden',
			top: '0px', // default
			left: '0px' // default
		};
		// browser specifics for better overlay tightness
		if (Ext.isIE) {
			Ext.apply(styleCfg, {
				left: '-3px',
				top: '-3px'
			});
		} else if (Ext.isGecko) {
			Ext.apply(styleCfg, {
				left: '-3px',
				top: '-3px'
			});
		} else if (Ext.isSafari) {
			Ext.apply(styleCfg, {
				left: '-4px',
				top: '-2px'
			});
		}
		this.clipEl = this.buttonCt.createChild({
			tag: 'div',
			style: styleCfg
		});
		this.setClipSize();
		this.clipEl.on({
			'mousemove': this.onButtonMouseMove,
			'mouseover': this.onButtonMouseMove,
			scope: this
		});
		
		this.floatEl = this.clipEl.createChild({
			tag: 'div',
			style: {
				position: 'absolute',
				width: this.FLOAT_EL_WIDTH + 'px',
				height: this.FLOAT_EL_HEIGHT + 'px',
				overflow: 'hidden'
			}
		});
		
		
		if (this.debug) {
			this.clipEl.applyStyles({
				'background-color': 'green'
			});
			this.floatEl.applyStyles({
				'background-color': 'red'
			});
		} else {
			this.clipEl.setOpacity(0.0);
		}
		
		this.createInputFile();
	},
	
	
	/*
	 * Private helper methods:
	 */
	/**
	 * Sets the size of clipEl so that is covering as much of the button as possible.
	 * @private
	 */
	setClipSize: function(){
		if (this.clipEl) {
			var width = this.buttonCt.getWidth();
			var height = this.buttonCt.getHeight();
			if (Ext.isIE) {
				width = width + 5;
				height = height + 5;
			} else if (Ext.isGecko) {
				width = width + 6;
				height = height + 6;
			} else if (Ext.isSafari) {
				width = width + 6;
				height = height + 6;
			}
			this.clipEl.setSize(width, height);
		}
	},
	
	/**
	 * Creates the input file element and adds it to inputFileCt.
	 * The created input file elementis sized, positioned, and styled appropriately.
	 * Event handlers for the element are set up, and a tooltip is applied if defined in the original config.
	 * @private
	 */
	createInputFile: function(){
	
		this.inputFileEl = this.floatEl.createChild({
			tag: 'input',
			type: 'file',
			size: 1, // must be > 0. It's value doesn't really matter due to our masking div (inputFileCt).  
			name: this.inputFileName || Ext.id(this.el),
			// Use the same pointer as an Ext.Button would use.  This doesn't work in Firefox.
			// This positioning right-aligns the input file to ensure that the "Browse" button is visible.
			style: {
				position: 'absolute',
				cursor: 'pointer',
				right: '0px',
                top: Ext.isIE ? '10px' :'0px' // Also another IE fix
			}
		});
		this.inputFileEl = this.inputFileEl.child('input') || this.inputFileEl;
		
		// setup events
		this.inputFileEl.on({
			'click': this.onInputFileClick,
			'change': this.onInputFileChange,
			scope: this
		});
		
		// add a tooltip
		if (this.tooltip) {
			if (typeof this.tooltip == 'object') {
				Ext.QuickTips.register(Ext.apply({
					target: this.inputFileEl
				}, this.tooltip));
			} else {
				this.inputFileEl.dom[this.tooltipType] = this.tooltip;
			}
		}
	},
	
	/**
	 * Handler when the cursor moves over the clipEl.
	 * The floatEl gets centered to the cursor location.
	 * @param {Event} e mouse event.
	 * @private
	 */
	onButtonMouseMove: function(e){
		var xy = e.getXY();
		xy[0] -= this.FLOAT_EL_WIDTH / 2;
		xy[1] -= this.FLOAT_EL_HEIGHT / 2;
		this.floatEl.setXY(xy);
	},
	
	/**
	 * Handler when inputFileEl's "Browse..." button is clicked.
	 * @param {Event} e click event.
	 * @private
	 */
	onInputFileClick: function(e){
		e.stopPropagation();
	},
	
	/**
	 * Handler when inputFileEl changes value (i.e. a new file is selected).
	 * @private
	 */
	onInputFileChange: function(){
		if (this.originalHandler) {
			this.originalHandler.call(this.originalScope, this);
		}
	},
	
	
	/*
	 * Public methods:
	 */
	/**
	 * Detaches the input file associated with this BrowseButton so that it can be used for other purposed (e.g. uplaoding).
	 * The returned input file has all listeners and tooltips applied to it by this class removed.
	 * @param {Boolean} whether to create a new input file element for this BrowseButton after detaching.
	 * True will prevent creation.  Defaults to false.
	 * @return {Ext.Element} the detached input file element.
	 */
	detachInputFile: function(noCreate){
		var result = this.inputFileEl;
		
		if (typeof this.tooltip == 'object') {
			Ext.QuickTips.unregister(this.inputFileEl);
		} else {
			this.inputFileEl.dom[this.tooltipType] = null;
		}
		this.inputFileEl.removeAllListeners();
		this.inputFileEl = null;
		
		if (!noCreate) {
			this.createInputFile();
		}
		return result;
	},
	
	/**
	 * @return {Ext.Element} the input file element attached to this BrowseButton.
	 */
	getInputFile: function(){
		return this.inputFileEl;
	},
	
	/**
	 * @see Ext.Button.disable
	 */
	disable: function(){
		Ext.ux.form.BrowseButton.superclass.disable.call(this);
		this.inputFileEl.dom.disabled = true;
	},
	
	/**
	 * @see Ext.Button.enable
	 */
	enable: function(){
		Ext.ux.form.BrowseButton.superclass.enable.call(this);
		this.inputFileEl.dom.disabled = false;
	}
});

Ext.reg('browsebutton', Ext.ux.form.BrowseButton);

/**
 * Ext.ux.FileUploader
 *
 * @author  Ing. Jozef Sakáloš
 * @version $Id: Ext.ux.FileUploader.js 83 2008-03-21 12:54:35Z jozo $
 * @date    15. March 2008
 *
 * @license Ext.ux.FileUploader is licensed under the terms of
 * the Open Source LGPL 3.0 license.  Commercial use is permitted to the extent
 * that the code/component(s) do NOT become part of another Open Source or Commercially
 * licensed development library or toolkit without explicit permission.
 * 
 * License details: http://www.gnu.org/licenses/lgpl.html
 */

/**
 * @class Ext.ux.FileUploader
 * @extends Ext.util.Observable
 * @constructor
 */
Ext.ux.FileUploader = function(config) {
    Ext.apply(this, config);

    // call parent
    Ext.ux.FileUploader.superclass.constructor.apply(this, arguments);

    // add events
    // {{{
    this.addEvents(
        /**
         * @event beforeallstart
         * Fires before an upload (of all files) is started. Return false to cancel the event.
         * @param {Ext.ux.FileUploader} this
         */
         'beforeallstart'
        /**
         * @event allfinished
         * Fires after upload (of all files) is finished
         * @param {Ext.ux.FileUploader} this
         */
        ,'allfinished'
        /**
         * @event beforefilestart
         * Fires before the file upload is started. Return false to cancel the event.
         * Fires only when singleUpload = false
         * @param {Ext.ux.FileUploader} this
         * @param {Ext.data.Record} record upload of which is being started
         */
        ,'beforefilestart'
        /**
         * @event filefinished
         * Fires when file finished uploading.
         * Fires only when singleUpload = false
         * @param {Ext.ux.FileUploader} this
         * @param {Ext.data.Record} record upload of which has finished
         */
        ,'filefinished'
        /**
         * @event progress
         * Fires when progress has been updated
         * @param {Ext.ux.FileUploader} this
         * @param {Object} data Progress data object
         * @param {Ext.data.Record} record Only if singleUpload = false
         */
        ,'progress'
    );
    // }}}

}; // eo constructor

Ext.extend(Ext.ux.FileUploader, Ext.util.Observable, {
    
    // configuration options
    // {{{
    /**
     * @cfg {Object} baseParams baseParams are sent to server in each request.
     */
     baseParams:{cmd:'upload',dir:'.'}

    /**
     * @cfg {Boolean} concurrent true to start all requests upon upload start, false to start
     * the next request only if previous one has been completed (or failed). Applicable only if
     * singleUpload = false
     */
    ,concurrent:true

    /**
     * @cfg {Boolean} enableProgress true to enable querying server for progress information
     */
    ,enableProgress:true

    /**
     * @cfg {String} jsonErrorText Text to use for json error
     */
    ,jsonErrorText:'Cannot decode JSON object'

    /**
     * @cfg {Number} Maximum client file size in bytes
     */
    ,maxFileSize:524288

    /**
     * @cfg {String} progressIdName Name to give hidden field for upload progress identificator
     */
    ,progressIdName:'UPLOAD_IDENTIFIER'

    /**
     * @cfg {Number} progressInterval How often (in ms) is progress requested from server
     */
    ,progressInterval:2000

    /**
     * @cfg {String} progressUrl URL to request upload progress from
     */
    ,progressUrl:'progress.php'

    /**
     * @cfg {Object} progressMap Mapping of received progress fields to store progress fields
     */
    ,progressMap:{
         bytes_total:'bytesTotal'
        ,bytes_uploaded:'bytesUploaded'
        ,est_sec:'estSec'
        ,files_uploaded:'filesUploaded'
        ,speed_average:'speedAverage'
        ,speed_last:'speedLast'
        ,time_last:'timeLast'
        ,time_start:'timeStart'
    }
    /**
     * @cfg {Boolean} singleUpload true to upload files in one form, false to upload one by one
     */
    ,singleUpload:false
    
    /**
     * @cfg {Ext.data.Store} store Mandatory. Store that holds files to upload
     */

    /**
     * @cfg {String} unknownErrorText Text to use for unknow error
     */
    ,unknownErrorText:'Unknown error'

    /**
     * @cfg {String} url Mandatory. URL to upload to
     */

    // }}}

    // private
    // {{{
    /**
     * uploads in progress count
     * @private
     */
    ,upCount:0
    // }}}

    // methods
    // {{{
    /**
     * creates form to use for upload.
     * @private
     * @return {Ext.Element} form
     */
    ,createForm:function(record) {
        var progressId = parseInt(Math.random() * 1e10, 10);
        var form = Ext.getBody().createChild({
             tag:'form'
            ,action:this.url
            ,method:'post'
            ,cls:'x-hidden'
            ,id:Ext.id()
            ,cn:[{
                 tag:'input'
                ,type:'hidden'
                ,name:'APC_UPLOAD_PROGRESS'
                ,value:progressId
            },{
                 tag:'input'
                ,type:'hidden'
                ,name:this.progressIdName
                ,value:progressId
            },{
                 tag:'input'
                ,type:'hidden'
                ,name:'MAX_FILE_SIZE'
                ,value:this.maxFileSize
            }]
        });
        if(record) {
            //record.set('form', form);
            if(Ext.isIE) record.set('form', undefined);  // IE fix, without this it throws an exception
            else record.set('form', form); 
            record.set('progressId', progressId);
        }
        else {
            this.progressId = progressId;
        }
        return form;

    } // eo function createForm
    // }}}
    // {{{
    ,deleteForm:function(form, record) {
        form.remove();
        if(record) {
            record.set('form', null);
        }
    } // eo function deleteForm
    // }}}
    // {{{
    /**
     * Fires event(s) on upload finish/error
     * @private
     */
    ,fireFinishEvents:function(options) {
        if(true !== this.eventsSuspended && !this.singleUpload) {
            this.fireEvent('filefinished', this, options && options.record);
        }
        if(true !== this.eventsSuspended && 0 === this.upCount) {
            this.stopProgress();
            this.fireEvent('allfinished', this);
        }
    } // eo function fireFinishEvents
    // }}}
    // {{{
    /**
     * Geg the iframe identified by record
     * @private
     * @param {Ext.data.Record} record
     * @return {Ext.Element} iframe or null if not found
     */
    ,getIframe:function(record) {
        var iframe = null;
        var form = record.get('form');
        if(form && form.dom && form.dom.target) {
            iframe = Ext.get(form.dom.target);
        }
        return iframe;
    } // eo function getIframe
    // }}}
    // {{{
    /**
     * returns options for Ajax upload request
     * @private
     * @param {Ext.data.Record} record
     * @param {Object} params params to add
     */
    ,getOptions:function(record, params) {
        var o = {
             url:this.url
            ,method:'post'
            ,isUpload:true
            ,scope:this
            ,callback:this.uploadCallback
            ,record:record
            ,params:this.getParams(record, params)
        };
        return o;
    } // eo function getOptions
    // }}}
    // {{{
    /**
     * get params to use for request
     * @private
     * @return {Object} params
     */
    ,getParams:function(record, params) {
        var p = {path:this.path};
        Ext.apply(p, this.baseParams || {}, params || {});
        return p;
    }
    // }}}
    // {{{
    /**
     * processes success response
     * @private
     * @param {Object} options options the request was called with
     * @param {Object} response request response object
     * @param {Object} o decoded response.responseText
     */
    ,processSuccess:function(options, response, o) {
        var record = false;
        // all files uploadded ok
        if(this.singleUpload) {
            this.store.each(function(r) {
                r.set('state', 'done');
                r.set('error', '');
                r.commit();
            });
        }
        else {
            record = options.record;
            record.set('state', 'done');
            record.set('error', '');
            record.commit();
        }

        this.deleteForm(options.form, record);

    } // eo processSuccess
    // }}}
    // {{{
    /**
     * processes failure response
     * @private
     * @param {Object} options options the request was called with
     * @param {Object} response request response object
     * @param {String/Object} error Error text or JSON decoded object. Optional.
     */
    ,processFailure:function(options, response, error) {
        var record = options.record;
        var records;

        // singleUpload - all files uploaded in one form
        if(this.singleUpload) {
            // some files may have been successful
            records = this.store.queryBy(function(r){return 'done' !== r.get('state');});
            records.each(function(record) {
                var e = error.errors ? error.errors[record.id] : this.unknownErrorText;
                if(e) {
                    record.set('state', 'failed');
                    record.set('error', e);
                    Ext.getBody().appendChild(record.get('input'));
                }
                else {
                    record.set('state', 'done');
                    record.set('error', '');
                }
                record.commit();
            }, this);

            this.deleteForm(options.form);
        }
        // multipleUpload - each file uploaded in it's own form
        else {
            if(error && 'object' === Ext.type(error)) {
                record.set('error', error.errors && error.errors[record.id] ? error.errors[record.id] : this.unknownErrorText);
            }
            else if(error) {
                record.set('error', error);
            }
            else if(response && response.responseText) {
                record.set('error', response.responseText);
            }
            else {
                record.set('error', this.unknownErrorText);
            }
            record.set('state', 'failed');
            record.commit();
        }
    } // eof processFailure
    // }}}
    // {{{
    /**
     * Delayed task callback
     */
    ,requestProgress:function() {
        var records, p;
        var o = {
             url:this.progressUrl
            ,method:'post'
            ,params:{}
            ,scope:this
            ,callback:function(options, success, response) {
                var o;
                if(true !== success) {
                    return;
                }
                try {
                    o = Ext.decode(response.responseText);
                }
                catch(e) {
                    return;
                }
                if('object' !== Ext.type(o) || true !== o.success) {
                    return;
                }

                if(this.singleUpload) {
                    this.progress = {};
                    for(p in o) {
                        if(this.progressMap[p]) {
                            this.progress[this.progressMap[p]] = parseInt(o[p], 10);
                        }
                    }
                    if(true !== this.eventsSuspended) {
                        this.fireEvent('progress', this, this.progress);
                    }

                }
                else {
                    for(p in o) {
                        if(this.progressMap[p] && options.record) {
                            options.record.set(this.progressMap[p], parseInt(o[p], 10));
                        }
                    }
                    if(options.record) {
                        options.record.commit();
                        if(true !== this.eventsSuspended) {
                            this.fireEvent('progress', this, options.record.data, options.record);
                        }
                    }
                }
                this.progressTask.delay(this.progressInterval);
            }
        };
        if(this.singleUpload) {
            o.params[this.progressIdName] = this.progressId;
            o.params.APC_UPLOAD_PROGRESS = this.progressId;
            Ext.Ajax.request(o);
        }
        else {
            records = this.store.query('state', 'uploading');
            records.each(function(r) {
                o.params[this.progressIdName] = r.get('progressId');
                o.params.APC_UPLOAD_PROGRESS = o.params[this.progressIdName];
                o.record = r;
                (function() {
                    Ext.Ajax.request(o);
                }).defer(250);
            }, this);
        }
    } // eo function requestProgress
    // }}}
    // {{{
    /**
     * path setter
     * @private
     */
    ,setPath:function(path) {
        this.path = path;
    } // eo setPath
    // }}}
    // {{{
    /**
     * url setter
     * @private
     */
    ,setUrl:function(url) {
        this.url = url;
    } // eo setUrl
    // }}}
    // {{{
    /**
     * Starts progress fetching from server
     * @private
     */
    ,startProgress:function() {
        if(!this.progressTask) {
            this.progressTask = new Ext.util.DelayedTask(this.requestProgress, this);
        }
        this.progressTask.delay.defer(this.progressInterval / 2, this.progressTask, [this.progressInterval]);
    } // eo function startProgress
    // }}}
    // {{{
    /**
     * Stops progress fetching from server
     * @private
     */
    ,stopProgress:function() {
        if(this.progressTask) {
            this.progressTask.cancel();
        }
    } // eo function stopProgress
    // }}}
    // {{{
    /**
     * Stops all currently running uploads
     */
    ,stopAll:function() {
        var records = this.store.query('state', 'uploading');
        records.each(this.stopUpload, this);
    } // eo function stopAll
    // }}}
    // {{{
    /**
     * Stops currently running upload
     * @param {Ext.data.Record} record Optional, if not set singleUpload = true is assumed
     * and the global stop is initiated
     */
    ,stopUpload:function(record) {
        // single abord
        var iframe = false;
        if(record) {
            iframe = this.getIframe(record);
            this.stopIframe(iframe);
            this.upCount--;
            this.upCount = 0 > this.upCount ? 0 : this.upCount;
            record.set('state', 'stopped');
            this.fireFinishEvents({record:record});
        }
        // all abort
        else if(this.form) {
            iframe = Ext.fly(this.form.dom.target);
            this.stopIframe(iframe);
            this.upCount = 0;
            this.fireFinishEvents();
        }

    } // eo function abortUpload
    // }}}
    // {{{
    /**
     * Stops uploading in hidden iframe
     * @private
     * @param {Ext.Element} iframe
     */
    ,stopIframe:function(iframe) {
        if(iframe) {
            try {
                iframe.dom.contentWindow.stop();
                iframe.remove.defer(250, iframe);
            }
            catch(e){}
        }
    } // eo function stopIframe
    // }}}
    // {{{
    /**
     * Main public interface function. Preforms the upload
     */
    ,upload:function() {
        
        var records = this.store.queryBy(function(r){return 'done' !== r.get('state');});
        if(!records.getCount()) {
            return;
        }

        // fire beforeallstart event
        if(true !== this.eventsSuspended && false === this.fireEvent('beforeallstart', this)) {
            return;
        }
        if(this.singleUpload) {
            this.uploadSingle();
        }
        else {
            records.each(this.uploadFile, this);
        }
        
        if(true === this.enableProgress) {
            this.startProgress();
        }

    } // eo function upload
    // }}}
    // {{{
    /**
     * called for both success and failure. Does nearly nothing
     * @private
     * but dispatches processing to processSuccess and processFailure functions
     */
    ,uploadCallback:function(options, success, response) {

        var o;
        this.upCount--;
        this.form = false;

        // process ajax success
        if(true === success) {
            try {
                o = Ext.decode(response.responseText);
            }
            catch(e) {
                this.processFailure(options, response, this.jsonErrorText);
                this.fireFinishEvents(options);
                return;
            }
            // process command success
            if(true === o.success) {
                this.processSuccess(options, response, o);
            }
            // process command failure
            else {
                this.processFailure(options, response, o);
            }
        }
        // process ajax failure
        else {
            this.processFailure(options, response);
        }

        this.fireFinishEvents(options);

    } // eo function uploadCallback
    // }}}
    // {{{
    /**
     * Uploads one file
     * @param {Ext.data.Record} record
     * @param {Object} params Optional. Additional params to use in request.
     */
    ,uploadFile:function(record, params) {
        // fire beforestart event
        if(true !== this.eventsSuspended && false === this.fireEvent('beforefilestart', this, record)) {
            return;
        }

        // create form for upload
        var form = this.createForm(record);

        // append input to the form
        var inp = record.get('input');
        inp.set({name:inp.id});
        form.appendChild(inp);

        // get params for request
        var o = this.getOptions(record, params);
        o.form = form;

        // set state 
        record.set('state', 'uploading');
        record.set('pctComplete', 0);

        // increment active uploads count
        this.upCount++;

        // request upload
        Ext.Ajax.request(o);

        // todo:delete after devel
        this.getIframe.defer(100, this, [record]);

    } // eo function uploadFile
    // }}}
    // {{{
    /**
     * Uploads all files in single request
     */
    ,uploadSingle:function() {

        // get records to upload
        var records = this.store.queryBy(function(r){return 'done' !== r.get('state');});
        if(!records.getCount()) {
            return;
        }

        // create form and append inputs to it
        var form = this.createForm();
        records.each(function(record) {
            var inp = record.get('input');
            inp.set({name:inp.id});
            form.appendChild(inp);
            record.set('state', 'uploading');
        }, this);

        // create options for request
        var o = this.getOptions();
        o.form = form;

        // save form for stop
        this.form = form;

        // increment active uploads counter
        this.upCount++;

        // request upload
        Ext.Ajax.request(o);
    
    } // eo function uploadSingle
    // }}}

}); // eo extend

// register xtype
Ext.reg('fileuploader', Ext.ux.FileUploader);

/**
 * Ext.ux.form.UploadPanel
 *
 * @author  Ing. Jozef Sakáloš
 * @version $Id: Ext.ux.UploadPanel.js 94 2008-03-24 01:04:27Z jozo $
 * @date    13. March 2008
 *
 * @license Ext.ux.form.UploadPanel is licensed under the terms of
 * the Open Source LGPL 3.0 license.  Commercial use is permitted to the extent
 * that the code/component(s) do NOT become part of another Open Source or Commercially
 * licensed development library or toolkit without explicit permission.
 * 
 * License details: http://www.gnu.org/licenses/lgpl.html
 */

/**
 * @class Ext.ux.UploadPanel
 * @extends Ext.Panel
 */
Ext.ux.UploadPanel = Ext.extend(Ext.Panel, {

    // configuration options overridable from outside
    // {{{
    /**
     * @cfg {String} addIconCls icon class for add (file browse) button
     */
     addIconCls:'icon-plus'

    /**
     * @cfg {String} addText Text on Add button
     */
    ,addText:_('file_cm_addText')

    /**
     * @cfg {Object} baseParams This object is not used directly by FileTreePanel but it is
     * propagated to lower level objects instead. Included here for convenience.
     */

    /**
     * @cfg {String} bodyStyle style to use for panel body
     */
    ,bodyStyle:'padding:2px'

    /**
     * @cfg {String} buttonsAt Where buttons are placed. Valid values are tbar, bbar, body (defaults to 'tbar')
     */
    ,buttonsAt:'tbar'

    /**
     * @cfg {String} clickRemoveText
     */
    ,clickRemoveText:_('file_cm_clickRemoveText')

    /**
     * @cfg {String} clickStopText
     */
    ,clickStopText:_('file_cm_clickStopText')

    /**
     * @cfg {String} emptyText empty text for dataview
     */
    ,emptyText:_('file_cm_emptyText')

    /**
     * @cfg {Boolean} enableProgress true to enable querying server for progress information
     * Passed to underlying uploader. Included here for convenience.
     */
    ,enableProgress:true

    /**
     * @cfg {String} errorText
     */
    ,errorText:'Error'

    /**
     * @cfg {String} fileCls class prefix to use for file type classes
     */
    ,fileCls:'file'

    /**
     * @cfg {String} fileQueuedText File upload status text
     */
    ,fileQueuedText:_('file_cm_fileQueuedText')

    /**
     * @cfg {String} fileDoneText File upload status text
     */
    ,fileDoneText:_('file_cm_fileDoneText')

    /**
     * @cfg {String} fileFailedText File upload status text
     */
    ,fileFailedText:_('file_cm_fileFailedText')

    /**
     * @cfg {String} fileStoppedText File upload status text
     */
    ,fileStoppedText:_('file_cm_fileStoppedText')

    /**
     * @cfg {String} fileUploadingText File upload status text
     */
    ,fileUploadingText:_('file_cm_fileUploadingText')

    /**
     * @cfg {Number} maxFileSize Maximum upload file size in bytes
     * This config property is propagated down to uploader for convenience
     */
    ,maxFileSize:10485760

    /**
     * @cfg {Number} Maximum file name length for short file names
     */
    ,maxLength:18

    /**
     * @cfg {String} removeAllIconCls iconClass to use for Remove All button (defaults to 'icon-cross'
     */
    ,removeAllIconCls:'icon-cross'

    /**
     * @cfg {String} removeAllText text to use for Remove All button tooltip
     */
    ,removeAllText:_('file_cm_removeAllText')

    /**
     * @cfg {String} removeIconCls icon class to use for remove file icon
     */
    ,removeIconCls:'icon-minus'

    /**
     * @cfg {String} removeText Remove text
     */
    ,removeText:_('file_cm_removeText')

    /**
     * @cfg {String} selectedClass class for selected item of DataView
     */
    ,selectedClass:'ux-up-item-selected'

    /**
     * @cfg {Boolean} singleUpload true to upload files in one form, false to upload one by one
     * This config property is propagated down to uploader for convenience
     */
    ,singleUpload:false

    /**
     * @cfg {String} stopAllText
     */
    ,stopAllText:_('file_cm_stopAllText')

    /** 
     * @cfg {String} stopIconCls icon class to use for stop
     */
    ,stopIconCls:'icon-stop'

    /**
     * @cfg {String/Ext.XTemplate} tpl Template for DataView.
     */

    /**
     * @cfg {String} uploadText Upload text
     */
    ,uploadText:_('file_cm_uploadText')

    /**
     * @cfg {String} uploadIconCls icon class to use for upload button
     */
    ,uploadIconCls:'icon-upload'

    /**
     * @cfg {String} workingIconCls iconClass to use for busy indicator
     */
    ,workingIconCls:'icon-working'

    // }}}

    // overrides
    // {{{
    ,initComponent:function() {

        // {{{
        // create buttons
        // add (file browse button) configuration
        var addCfg = {
             xtype:'browsebutton'
            ,text:this.addText + '...'
            ,iconCls:this.addIconCls
            ,scope:this
            ,handler:this.onAddFile
        };

        // upload button configuration
        var upCfg = {
             xtype:'button'
            ,iconCls:this.uploadIconCls
            ,text:this.uploadText
            ,scope:this
            ,handler:this.onUpload
            ,disabled:true
        };

        // remove all button configuration
        var removeAllCfg = {
             xtype:'button'
            ,iconCls:this.removeAllIconCls
            ,tooltip:this.removeAllText
            ,scope:this
            ,handler:this.onRemoveAllClick
            ,disabled:true
        };

        // todo: either to cancel buttons in body or implement it
        if('body' !== this.buttonsAt) {
            this[this.buttonsAt] = [addCfg, upCfg, '->', removeAllCfg];
        }
        // }}}
        // {{{
        // create store
        // fields for record
        var fields = [
             {name:'id', type:'text', system:true}
            ,{name:'shortName', type:'text', system:true}
            ,{name:'fileName', type:'text', system:true}
            ,{name:'filePath', type:'text', system:true}
            ,{name:'fileCls', type:'text', system:true}
            ,{name:'input', system:true}
            ,{name:'form', system:true}
            ,{name:'state', type:'text', system:true}
            ,{name:'error', type:'text', system:true}
            ,{name:'progressId', type:'int', system:true}
            ,{name:'bytesTotal', type:'int', system:true}
            ,{name:'bytesUploaded', type:'int', system:true}
            ,{name:'estSec', type:'int', system:true}
            ,{name:'filesUploaded', type:'int', system:true}
            ,{name:'speedAverage', type:'int', system:true}
            ,{name:'speedLast', type:'int', system:true}
            ,{name:'timeLast', type:'int', system:true}
            ,{name:'timeStart', type:'int', system:true}
            ,{name:'pctComplete', type:'int', system:true}
        ];

        // add custom fields if passed
        if(Ext.isArray(this.customFields)) {
            fields.push(this.customFields);
        }

        // create store
        this.store = new Ext.data.SimpleStore({
             id:0
            ,fields:fields
            ,data:[]
        });
        // }}}
        // {{{
        // create view
        Ext.apply(this, {
            items:[{
                 xtype:'dataview'
                ,itemSelector:'div.ux-up-item'
                ,store:this.store
                ,selectedClass:this.selectedClass
                ,singleSelect:true
                ,emptyText:this.emptyText
                ,tpl: this.tpl || new Ext.XTemplate(
                      '<tpl for=".">'
                    + '<div class="ux-up-item">'
//                  + '<div class="ux-up-indicator">&#160;</div>'
                    + '<div class="ux-up-icon-file {fileCls}">&#160;</div>'
                    + '<div class="ux-up-text x-unselectable" qtip="{fileName}">{shortName}</div>'
                    + '<div id="remove-{[values.input.id]}" class="ux-up-icon-state ux-up-icon-{state}"'
                    + 'qtip="{[this.scope.getQtip(values)]}">&#160;</div>'
                    + '</div>'
                    + '</tpl>'
                    , {scope:this}
                )
                ,listeners:{click:{scope:this, fn:this.onViewClick}}

            }]
            //,forceLayout: true
            //,autoHeight: true
            ,width: 300
        });
        // }}}

        // call parent
        Ext.ux.UploadPanel.superclass.initComponent.apply(this, arguments);

        // save useful references
        this.view = this.items.itemAt(0);

        // {{{
        // add events
        this.addEvents(
            /**
             * Fires before the file is added to store. Return false to cancel the add
             * @event beforefileadd
             * @param {Ext.ux.UploadPanel} this
             * @param {Ext.Element} input (type=file) being added
             */
            'beforefileadd'
            /**
             * Fires after the file is added to the store
             * @event fileadd
             * @param {Ext.ux.UploadPanel} this
             * @param {Ext.data.Store} store
             * @param {Ext.data.Record} Record (containing the input) that has been added to the store
             */
            ,'fileadd'
            /**
             * Fires before the file is removed from the store. Return false to cancel the remove
             * @event beforefileremove
             * @param {Ext.ux.UploadPanel} this
             * @param {Ext.data.Store} store
             * @param {Ext.data.Record} Record (containing the input) that is being removed from the store
             */
            ,'beforefileremove'
            /**
             * Fires after the record (file) has been removed from the store
             * @event fileremove
             * @param {Ext.ux.UploadPanel} this
             * @param {Ext.data.Store} store
             */
            ,'fileremove'
            /**
             * Fires before all files are removed from the store (queue). Return false to cancel the clear.
             * Events for individual files being removed are suspended while clearing the queue.
             * @event beforequeueclear
             * @param {Ext.ux.UploadPanel} this
             * @param {Ext.data.Store} store
             */
            ,'beforequeueclear'
            /**
             * Fires after the store (queue) has been cleared
             * Events for individual files being removed are suspended while clearing the queue.
             * @event queueclear
             * @param {Ext.ux.UploadPanel} this
             * @param {Ext.data.Store} store
             */
            ,'queueclear'
            /**
             * Fires after the upload button is clicked but before any upload is started
             * Return false to cancel the event
             * @param {Ext.ux.UploadPanel} this
             */
            ,'beforeupload'
        );
        // }}}
        // {{{
        // relay view events
        this.relayEvents(this.view, [
             'beforeclick'
            ,'beforeselect'
            ,'click'
            ,'containerclick'
            ,'contextmenu'
            ,'dblclick'
            ,'selectionchange'
        ]);
        // }}}

        // create uploader
        var config = {
             store:this.store
            ,singleUpload:this.singleUpload
            ,maxFileSize:this.maxFileSize
            ,enableProgress:this.enableProgress
            ,url:this.url
            ,path:this.path
        };
        if(this.baseParams) {
            config.baseParams = this.baseParams;
        }
        this.uploader = new Ext.ux.FileUploader(config);

        // relay uploader events
        this.relayEvents(this.uploader, [
             'beforeallstart'
            ,'allfinished'
            ,'progress'
        ]);

        // install event handlers
        this.on({
             beforeallstart:{scope:this, fn:function() {
                this.uploading = true;
                this.updateButtons();
            }}
            ,allfinished:{scope:this, fn:function() {
                this.uploading = false;
                this.updateButtons();
            }}
            ,progress:{fn:this.onProgress.createDelegate(this)}
        });
    } // eo function initComponent
    // }}}
    // {{{
    /**
     * onRender override, saves references to buttons
     * @private
     */
    ,onRender:function() {
        // call parent
        Ext.ux.UploadPanel.superclass.onRender.apply(this, arguments);

        // save useful references
        var tb = 'tbar' === this.buttonsAt ? this.getTopToolbar() : this.getBottomToolbar();
        this.addBtn = Ext.getCmp(tb.items.first().id);
        this.uploadBtn = Ext.getCmp(tb.items.itemAt(1).id);
        this.removeAllBtn = Ext.getCmp(tb.items.last().id);
        
        //tb.doLayout(false,true);
    } // eo function onRender
    // }}}

    // added methods
    // {{{
    /**
     * called by XTemplate to get qtip depending on state
     * @private
     * @param {Object} values XTemplate values
     */
    ,getQtip:function(values) {
        var qtip = '';
        switch(values.state) {
            case 'queued':
                qtip = String.format(this.fileQueuedText, values.fileName);
                qtip += '<br>' + this.clickRemoveText;
            break;

            case 'uploading':
                qtip = String.format(this.fileUploadingText, values.fileName);
                qtip += '<br>' + values.pctComplete + '% done';
                qtip += '<br>' + this.clickStopText;
            break;

            case 'done':
                qtip = String.format(this.fileDoneText, values.fileName);
                qtip += '<br>' + this.clickRemoveText;
            break;

            case 'failed':
                qtip = String.format(this.fileFailedText, values.fileName);
                qtip += '<br>' + this.errorText + ':' + values.error;
                qtip += '<br>' + this.clickRemoveText;
            break;

            case 'stopped':
                qtip = String.format(this.fileStoppedText, values.fileName);
                qtip += '<br>' + this.clickRemoveText;
            break;
        }
        return qtip;
    } // eo function getQtip
    // }}}
    // {{{
    /**
     * get file name
     * @private
     * @param {Ext.Element} inp Input element containing the full file path
     * @return {String}
     */
    ,getFileName:function(inp) {
        return inp.getValue().split(/[\/\\]/).pop();
    } // eo function getFileName
    // }}}
    // {{{
    /**
     * get file path (excluding the file name)
     * @private
     * @param {Ext.Element} inp Input element containing the full file path
     * @return {String}
     */
    ,getFilePath:function(inp) {
        return inp.getValue().replace(/[^\/\\]+$/,'');
    } // eo function getFilePath
    // }}}
    // {{{
    /**
     * returns file class based on name extension
     * @private
     * @param {String} name File name to get class of
     * @return {String} class to use for file type icon
     */
    ,getFileCls: function(name) {
        var atmp = name.split('.');
        if(1 === atmp.length) {
            return this.fileCls;
        }
        else {
            return this.fileCls + '-' + atmp.pop().toLowerCase();
        }
    }
    // }}}
    // {{{
    /**
     * called when file is added - adds file to store
     * @private
     * @param {Ext.ux.BrowseButton}
     */
    ,onAddFile:function(bb) {
        if(true !== this.eventsSuspended && false === this.fireEvent('beforefileadd', this, bb.getInputFile())) {
            return;
        }
        var inp = bb.detachInputFile();
        inp.addClass('x-hidden');
        var fileName = this.getFileName(inp);

        // create new record and add it to store
        var rec = new this.store.recordType({
             input:inp
            ,fileName:fileName
            ,filePath:this.getFilePath(inp)
            ,shortName: Ext.util.Format.ellipsis(fileName, this.maxLength)
            ,fileCls:this.getFileCls(fileName)
            ,state:'queued'
        }, inp.id);
        rec.commit();
        this.store.add(rec);

        this.syncShadow();

        this.uploadBtn.enable();
        this.removeAllBtn.enable();
        
        this.ownerCt.doLayout();  /* shaun 20090921 */

        if(true !== this.eventsSuspended) {
            this.fireEvent('fileadd', this, this.store, rec);
        }

    } // eo onAddFile
    // }}}
    // {{{
    /**
     * destroys child components
     * @private
     */
    ,onDestroy:function() {

        // destroy uploader
        if(this.uploader) {
            this.uploader.stopAll();
            this.uploader.purgeListeners();
            this.uploader = null;
        }

        // destroy view
        if(this.view) {
            this.view.purgeListeners();
            this.view.destroy();
            this.view = null;
        }

        // destroy store
        if(this.store) {
            this.store.purgeListeners();
            this.store.destroy();
            this.store = null;
        }

    } // eo function onDestroy
    // }}}
    // {{{
    /**
     * progress event handler
     * @private
     * @param {Ext.ux.FileUploader} uploader
     * @param {Object} data progress data
     * @param {Ext.data.Record} record
     */
    ,onProgress:function(uploader, data, record) {
        var bytesTotal, bytesUploaded, pctComplete, state, idx, item, width, pgWidth;
        if(record) {
            state = record.get('state');
            bytesTotal = record.get('bytesTotal') || 1;
            bytesUploaded = record.get('bytesUploaded') || 0;
            if('uploading' === state) {
                pctComplete = Math.round(1000 * bytesUploaded/bytesTotal) / 10;
            }
            else if('done' === 'state') {
                pctComplete = 100;
            }
            else {
                pctComplete = 0;
            }
            record.set('pctComplete', pctComplete);

            idx = this.store.indexOf(record);
            item = Ext.get(this.view.getNode(idx));
            if(item) {
                width = item.getWidth();
                item.applyStyles({'background-position':width * pctComplete / 100 + 'px'});
            }
        }
    } // eo function onProgress
    // }}}
    // {{{
    /**
     * called when file remove icon is clicked - performs the remove
     * @private
     * @param {Ext.data.Record}
     */
    ,onRemoveFile:function(record) {
        if(true !== this.eventsSuspended && false === this.fireEvent('beforefileremove', this, this.store, record)) {
            return;
        }

        // remove DOM elements
        var inp = record.get('input');
        var wrap = inp.up('em');
        inp.remove();
        if(wrap) {
            wrap.remove();
        }

        // remove record from store
        this.store.remove(record);

        var count = this.store.getCount();
        this.uploadBtn.setDisabled(!count);
        this.removeAllBtn.setDisabled(!count);

        if(true !== this.eventsSuspended) {
            this.fireEvent('fileremove', this, this.store);
            this.syncShadow();
        }
    } // eo function onRemoveFile
    // }}}
    // {{{
    /**
     * Remove All/Stop All button click handler
     * @private
     */
    ,onRemoveAllClick:function(btn) {
        if(true === this.uploading) {
            this.stopAll();
        }
        else {
            this.removeAll();
        }
    } // eo function onRemoveAllClick

    ,stopAll:function() {
        this.uploader.stopAll();
    } // eo function stopAll
    // }}}
    // {{{
    /**
     * DataView click handler
     * @private
     */
    ,onViewClick:function(view, index, node, e) {
        var t = e.getTarget('div:any(.ux-up-icon-queued|.ux-up-icon-failed|.ux-up-icon-done|.ux-up-icon-stopped)');
        if(t) {
            this.onRemoveFile(this.store.getAt(index));
        }
        t = e.getTarget('div.ux-up-icon-uploading');
        if(t) {
            this.uploader.stopUpload(this.store.getAt(index));
        }
    } // eo function onViewClick
    // }}}
    // {{{
    /**
     * tells uploader to upload
     * @private
     */
    ,onUpload:function() {
        if(true !== this.eventsSuspended && false === this.fireEvent('beforeupload', this)) {
            return false;
        }
        this.uploader.upload();
    } // eo function onUpload
    // }}}
    // {{{
    /**
     * url setter
     */
    ,setUrl:function(url) {
        this.url = url;
        this.uploader.setUrl(url);
    } // eo function setUrl
    // }}}
    // {{{
    /**
     * path setter
     */
    ,setPath:function(path) {
        this.uploader.setPath(path);
    } // eo function setPath
    // }}}
    // {{{
    /**
     * Updates buttons states depending on uploading state
     * @private
     */
    ,updateButtons:function() {
        if(true === this.uploading) {
            this.addBtn.disable();
            this.uploadBtn.disable();
            this.removeAllBtn.setIconClass(this.stopIconCls);
            this.removeAllBtn.getEl().child(this.removeAllBtn.buttonSelector).dom[this.removeAllBtn.tooltipType] = this.stopAllText;
        }
        else {
            this.addBtn.enable();
            this.uploadBtn.enable();
            this.removeAllBtn.setIconClass(this.removeAllIconCls);
            this.removeAllBtn.getEl().child(this.removeAllBtn.buttonSelector).dom[this.removeAllBtn.tooltipType] = this.removeAllText;
        }
    } // eo function updateButtons
    // }}}
    // {{{
    /**
     * Removes all files from store and destroys file inputs
     */
    ,removeAll:function() {
        var suspendState = this.eventsSuspended;
        if(false !== this.eventsSuspended && false === this.fireEvent('beforequeueclear', this, this.store)) {
            return false;
        }
        this.suspendEvents();

        this.store.each(this.onRemoveFile, this);

        this.eventsSuspended = suspendState;
        if(true !== this.eventsSuspended) {
            this.fireEvent('queueclear', this, this.store);
        }
        this.syncShadow();
    } // eo function removeAll
    // }}}
    // {{{
    /**
     * synchronize context menu shadow if we're in contextmenu
     * @private
     */
    ,syncShadow:function() {
        if(this.contextmenu && this.contextmenu.shadow) {
            this.contextmenu.getEl().shadow.show(this.contextmenu.getEl());
        }
    } // eo function syncShadow
    // }}}

}); // eo extend

// register xtype
Ext.reg('uploadpanel', Ext.ux.UploadPanel);

// eof 
