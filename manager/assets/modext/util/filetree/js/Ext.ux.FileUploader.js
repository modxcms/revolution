// vim: ts=4:sw=4:nu:fdc=4:nospell
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

/*global Ext */

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

 // eof
