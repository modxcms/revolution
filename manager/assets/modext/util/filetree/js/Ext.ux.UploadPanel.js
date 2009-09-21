// vim: ts=4:sw=4:nu:fdc=4:nospell
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

/*global Ext */

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
//					+ '<div class="ux-up-indicator">&#160;</div>'
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
