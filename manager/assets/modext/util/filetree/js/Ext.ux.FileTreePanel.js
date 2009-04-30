// vim: ts=4:sw=4:nu:fdc=4:nospell
/**
 * Ext.ux.FileTreePanel
 *
 * @author  Ing. Jozef Sakáloš
 * @version $Id: Ext.ux.FileTreePanel.js 112 2008-03-28 21:11:17Z jozo $
 * @date    13. March 2008
 *
 * @license Ext.ux.FileTreePanel is licensed under the terms of
 * the Open Source LGPL 3.0 license.  Commercial use is permitted to the extent
 * that the code/component(s) do NOT become part of another Open Source or Commercially
 * licensed development library or toolkit without explicit permission.
 * 
 * License details: http://www.gnu.org/licenses/lgpl.html
 */

/*global Ext, window, document, setTimeout */

/**
 * @class Ext.ux.FileTreePanel
 * @extends Ext.tree.TreePanel
 */

Ext.ux.FileTreePanel = Ext.extend(Ext.tree.TreePanel, {
	// config variables overridable from outside
	// {{{
	/**
	 * @cfg {Object} baseParams This object is not used directly by FileTreePanel but it is
	 * propagated to lower level objects instead. Included here for convenience.
	 */

	/**
	 * @cfg {String} confirmText Text to display as title of confirmation message box
	 */
	 confirmText:'Confirm'

	/**
	 * @cfg {Boolean} containerScroll true to register 
	 * this container with ScrollManager (defaults to true)
	 */
	,containerScroll:true

	/**
	 * @cfg {String} deleteText Delete text (for message box title or other displayed texts)
	 */
	,deleteText:'Delete'

	/**
	 * @cfg {String} deleteUrl URL to use when deleting; this.url is used if not set (defaults to undefined)
	 */

	/**
	 * @cfg {String} downloadUrl URL to use when downloading; this.url is used if not set (defaults to undefined)
	 */

	/**
	 * @cfg {Boolean} enableDD true to enable drag & drop of files and folders (defaults to true)
	 */
	,enableDD:true

	/**
	 * @cfg {Boolean) enableDelete true to enable to delete files and directories. 
	 * If false context menu item is not shown (defaults to true)
	 */
	,enableDelete:true

	/**
	 * @cfg {Boolean) enableNewDir true to enable to create new directory. 
	 * If false context menu item is not shown (defaults to true)
	 */
	,enableNewDir:true

	/**
	 * @cfg {Boolean) enableOpen true to enable open submenu
	 * If false context menu item is not shown (defaults to true)
	 */
	,enableOpen:true

	/**
	 * @cfg {Boolean} enableProgress true to enable querying server for progress information
	 * Passed to underlying uploader. Included here for convenience.
	 */
	,enableProgress:true

	/**
	 * @cfg {Boolean) enableRename true to enable to rename files and directories. 
	 * If false context menu item is not shown (defaults to true)
	 */
	,enableRename:true

	/**
	 * @cfg {Boolean} enableSort true to enable sorting of tree. See also folderSort (defaults to true)
	 */
	,enableSort:true

	/**
	 * @cfg {Boolean) enableUpload true to enable to upload files. 
	 * If false context menu item is not shown (defaults to true)
	 */
	,enableUpload:true

	/**
	 * @cfg {String} errorText Text to display for an error
	 */
	,errorText:'Error'

	/**
	 * @cfg {String} existsText Text to display in message box if file exists
	 */
	,existsText:'File <b>{0}</b> already exists'

	/**
	 * @cfg {Boolean} true to expand root node on FileTreePanel render (defaults to true)
	 */
	,expandOnRender:true

	/**
	 * @cfg {String} fileCls class prefix to add to nodes. "-extension" is appended to
	 * this prefix to form filetype class, for example: file-odt, file-pdf. These classes
	 * are used to display correct filetype icons in the tree. css file and icons must
	 * exist of course.
	 */
	,fileCls:'file'

	/**
	 * @cfg {String} fileText
	 */
	,fileText:'File'

	/**
	 * @cfg {Boolean} focusPopup true to focus new browser popup window for 'popup' openMode
	 * (defaults to true)
	 */
	,focusPopup:true

	/**
	 * @cfg {Boolean} folderSort true to place directories at the top of the tree (defaults to true)
	 */
	,folderSort:true

	/**
	 * @cfg {String} hrefPrefix Text to prepend before file href for file open command. 
	 * (defaults to '')
	 */
	,hrefPrefix:''

	/**
	 * @cfg {String} hrefSuffix Text to append to file href for file open command. 
	 * (defaults to '')
	 */
	,hrefSuffix:''

	/**
	 * @cfg {String} layout Layout to use for this panel (defaults to 'fit')
	 */
	,layout:'fit'

	/**
	 * @cfg {String} loadingText Text to use for load mask msg
	 */
	,loadingText:'Loading'

	/**
	 * @cfg {Boolean} loadMask True to mask tree panel while loading
	 */
	,loadMask:false

	/**
	 * @cfg {Number} maxFileSize Maximum upload file size in bytes
	 * This config property is propagated down to uploader for convenience
	 */
	,maxFileSize:524288

	/**
	 * @cfg {Number} maxMsgLen Maximum message length for message box (defaults to 2000).
	 * If message is longer Ext.util.Format.ellipsis is used to truncate it and append ...
	 */
	,maxMsgLen:2000

	/**
	 * @cfg {String} method Method to use when posting to server. Other valid value is 'get'
	 * (defaults to 'post')
	 */
	,method:'post'

	/**
	 * @cfg {String} newdirText Default name for new directories (defaults to 'New Folder')
	 */
	,newdirText:'New Folder'

	/**
	 * @cfg {String} newdirUrl URL to use when creating new directory; 
	 * this.url is used if not set (defaults to undefined)
	 */

	/**
	 * @cfg {String} openMode Default file open mode. This mode is used when user dblclicks 
	 * a file. Other valid values are '_self', '_blank' and 'download' (defaults to 'popup')
	 */
	,openMode:'popup'

	/**
	 * @cfg {String} overwriteText Text to use in overwrite confirmation message box
	 */
	,overwriteText:'Do you want to overwrite it?'

	/**
	 * @cfg {String} popupFeatures Features for new browser window opened by popup open mode
	 */
	,popupFeatures:'width=800,height=600,dependent=1,scrollbars=1,resizable=1,toolbar=1'

	/**
	 * @cfg {Boolean} readOnly true to disable write operations. treeEditor and context menu
	 * are not created if true (defaults to false)
	 */
	,readOnly:false

	/**
	 * @cfg {String} reallyWantText Text to display for that question
	 */
	,reallyWantText:'Do you really want to'

	/**
	 * @cfg {String} renameUrl URL to use when renaming; this.url is used if not set (defaults to undefined)
	 */

	/**
	 * @cfg {String} rootPath Relative path pointing to the directory that is root of this tree (defaults to 'root')
	 */
	,rootPath:'root'

	/**
	 * @cfg {String} rootText Text to display for root node (defaults to 'Tree Root')
	 */
	,rootText:'Tree Root'

	/**
	 * @cfg {Boolean} selectOnEdit true to select the edited text on edit start (defaults to true)
	 */
	,selectOnEdit:true

	/**
	 * @cfg {Boolean} singleUpload true to upload files in one form, false to upload one by one
	 * This config property is propagated down to uploader for convenience
	 */
	,singleUpload:false

	/**
	 * @cfg {Boolean} topMenu true to create top toolbar with menu in addition to contextmenu
	 */
	,topMenu:false

	/**
	 * @cfg {String} url URL to use when communicating with server
	 */
	,url:'filetree.php'
	// }}}

	// overrides
	// {{{
	/**
	 * called by Ext when instantiating
	 * @private
	 * @param {Object} config Configuration object
	 */
	,initComponent:function() {

		// {{{
		Ext.apply(this, {

			// create root node
			 root:new Ext.tree.AsyncTreeNode({
				 text:this.rootText
				,path:this.rootPath
				,allowDrag:false
			})

			// create treeEditor
			,treeEditor:!this.readOnly ? new Ext.tree.TreeEditor(this, {
				 allowBlank:false
				,cancelOnEsc:true
				,completeOnEnter:true
				,ignoreNoChange:true
				,selectOnFocus:this.selectOnEdit
			}) : undefined

			// drop config
			,dropConfig:this.dropConfig ? this.dropConfig : {
				 ddGroup:this.ddGroup || 'TreeDD'
				,appendOnly:this.enableSort
				,expandDelay:3600000 // do not expand on drag over node
			}

			// create treeSorter
			,treeSorter:this.enableSort ? new Ext.tree.TreeSorter(this, {folderSort:this.folderSort}) : undefined

			// {{{
			,keys:[{
				// Enter = open
				 key:Ext.EventObject.ENTER, scope:this
				,fn:function(key, e) {
					var sm = this.getSelectionModel();
					var node = sm.getSelectedNode();
					if(node && 0 !== node.getDepth() && node.isLeaf()) {
						this.openNode(node);
					}
			}},{
				// F2 = edit
				 key:113, scope:this
				,fn:function(key, e) {
					var sm = this.getSelectionModel();
					var node = sm.getSelectedNode();
					if(node && 0 !== node.getDepth() && this.enableRename && this.readOnly !== true) {
						this.treeEditor.triggerEdit(node);
					}
			}},{
				// Delete Key = Delete
				 key:46, stopEvent:true, scope:this
				,fn:function(key, e) {
					var sm = this.getSelectionModel();
					var node = sm.getSelectedNode();
					if(node && 0 !== node.getDepth() && this.enableDelete && this.readOnly !== true) {
						this.deleteNode(node);
					}
			}},{
				// Ctrl + E = reload
				 key:69, ctrl:true, stopEvent:true, scope:this
				,fn:function(key, e) {
					var sm = this.getSelectionModel();
					var node = sm.getSelectedNode();
					if(node) {
						node = node.isLeaf() ? node.parentNode : node;
						sm.select(node);
						node.reload();
					}
			}},{
				// Ctrl + -> = expand deep
				 key:39, ctrl:true, stopEvent:true, scope:this
				,fn:function(key, e) {
					var sm = this.getSelectionModel();
					var node = sm.getSelectedNode();
					if(node && !node.isLeaf()) {
						sm.select(node);
						node.expand.defer(1, node, [true]);
					}
				}},{
				// Ctrl + <- = collapse deep
				 key:37, ctrl:true, scope:this, stopEvent:true
				,fn:function(key, e) {
					var sm = this.getSelectionModel();
					var node = sm.getSelectedNode();
					if(node && !node.isLeaf()) {
						sm.select(node);
						node.collapse.defer(1, node, [true]);
					}
				}},{
				// Ctrl + N = New Directory
				 key:78, ctrl:true, scope:this, stopEvent:true
				,fn:function(key, e) {
					var sm, node;
					sm = this.getSelectionModel();
					node = sm.getSelectedNode();
					if(node && this.enableNewDir && this.readOnly !== true) {
						node = node.isLeaf() ? node.parentNode : node;
						this.createNewDir(node);
					}
			}}]
			// }}}

		}); // eo apply
		// }}}
		// {{{
		// create loader
		if(!this.loader) {
			this.loader = new Ext.tree.TreeLoader({
				 url:this.url
				,baseParams:{cmd:'get'}
				,listeners:{
					beforeload:{scope:this, fn:function(loader, node) {
						loader.baseParams.path = this.getPath(node);
					}}
				}
			});
		}
		// }}}
		// {{{
		// install top menu if configured
		if(true === this.topMenu) {
			this.tbar = [{
				 text:this.fileText
				,disabled:true
				,scope:this
				,menu:this.getContextMenu()
			}];
		}
		// }}}

		// call parent
		Ext.ux.FileTreePanel.superclass.initComponent.apply(this, arguments);

		// {{{
		// install treeEditor event handlers 
		if(this.treeEditor) {
			// do not enter edit mode on selected node click
			this.treeEditor.beforeNodeClick = function(node,e){return true;};

			// treeEditor event handlers
			this.treeEditor.on({
				 complete:{scope:this, fn:this.onEditComplete}
				,beforecomplete:{scope:this, fn:this.onBeforeEditComplete}
			});
		}
		// }}}
		// {{{
		// install event handlers
		this.on({
			 contextmenu:{scope:this, fn:this.onContextMenu, stopEvent:true}
			,dblclick:{scope:this, fn:this.onDblClick}
			,beforenodedrop:{scope:this, fn:this.onBeforeNodeDrop}
			,nodedrop:{scope:this, fn:this.onNodeDrop}
			,nodedragover:{scope:this, fn:this.onNodeDragOver}
		});

		// }}}
		// {{{
		// add events
		this.addEvents(
			/**
			 * @event beforeopen
			 * Fires before file open. Return false to cancel the event
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {String} fileName name of the file being opened
			 * @param {String} url url of the file being opened
			 * @param {String} mode open mode
			 */
			 'beforeopen'
			/**
			 * @event open
			 * Fires after file open has been initiated
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {String} fileName name of the file being opened
			 * @param {String} url url of the file being opened
			 * @param {String} mode open mode
			 */
			,'open'
			/**
			 * @event beforerename
			 * Fires after the user completes file name editing 
			 * but before the file is renamed. Return false to cancel the event
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {Ext.tree.AsyncTreeNode} node being renamed
			 * @param {String} newPath including file name 
			 * @param {String} oldPath including file name 
			 */
			,'beforerename'
			/**
			 * @event rename
			 * Fires after the file has been successfully renamed
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {Ext.tree.AsyncTreeNode} node that has been renamed
			 * @param {String} newPath including file name 
			 * @param {String} oldPath including file name 
			 */
			,'rename'
			/**
			 * @event renamefailure
			 * Fires after a failure when renaming file
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {Ext.tree.AsyncTreeNode} node rename of which failed
			 * @param {String} newPath including file name 
			 * @param {String} oldPath including file name 
			 */
			,'renamefailure'
			/**
			 * @event beforedelete
			 * Fires before a file or directory is deleted. Return false to cancel the event.
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {Ext.tree.AsyncTreeNode} node being deleted
			 */
			,'beforedelete'
			/**
			 * @event delete
			 * Fires after a file or directory has been deleted
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {String} path including file name that has been deleted
			 */
			,'delete'
			/**
			 * @event deletefailure
			 * Fires if node delete failed
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {Ext.tree.AsyncTreeNode} node delete of which failed
			 */
			,'deletefailure'
			/**
			 * @event beforenewdir
			 * Fires before new directory is created. Return false to cancel the event
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {Ext.tree.AsyncTreeNode} node under which the new directory is being created
			 */
			,'beforenewdir'
			/**
			 * @event newdir
			 * Fires after the new directory has been successfully created
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {Ext.tree.AsyncTreeNode} new node/directory that has been created
			 */
			,'newdir'
			/**
			 * @event newdirfailure
			 * Fires if creation of new directory failed
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {String} path creation of which failed
			 */
			,'newdirfailure'
		); // eo addEvents
		// }}}

	} // eo function initComponent
	// }}}
	// {{{
	/**
	 * onRender override - just expands root node if configured
	 * @private
	 */
	,onRender:function() {
		// call parent
		Ext.ux.FileTreePanel.superclass.onRender.apply(this, arguments);

		if(true === this.topMenu) {
			this.topMenu = Ext.getCmp(this.getTopToolbar().items.itemAt(0).id);
			this.getSelectionModel().on({
				 scope:this
				,selectionchange:function(sm, node) {
					var disable = node ? false : true;
					disable = disable || this.readOnly;
					this.topMenu.setDisabled(disable);
				}
			});
			Ext.apply(this.topMenu, {
				 showMenu:function() {
					this.showContextMenu(false);
				}.createDelegate(this)
//				,menu:this.getContextMenu()
			});
		}

		// expand root node if so configured
		if(this.expandOnRender) {
			this.root.expand();
		}

		// prevent default browser context menu to appear 
		this.el.on({
			contextmenu:{fn:function(){return false;},stopEvent:true}
		});

		// setup loading mask if configured
		if(true === this.loadMask) {
			this.loader.on({
				 scope:this.el
				,beforeload:this.el.mask.createDelegate(this.el, [this.loadingText + '...'])
				,load:this.el.unmask
				,loadexception:this.el.unmask
			});
		}

	} // eo function onRender
	// }}}

	// new methods
	// {{{
	/**
	 * runs after an Ajax requested command has completed/failed
	 * @private
	 * @param {Object} options Options used for the request
	 * @param {Boolean} success true if ajax call was successful (cmd may have failed)
	 * @param {Object} response ajax call response object
	 */
	,cmdCallback:function(options, success, response) {
		var i, o, node;
		var showMsg = true;

		// process Ajax success
		if(true === success) {

			// try to decode JSON response
			try {
				o = Ext.decode(response.responseText);
			}
			catch(ex) {
				this.showError(response.responseText);
			}

			// process command success
			if(true === o.success) {
				switch(options.params.cmd) {
					case 'delete':
						if(true !== this.eventsSuspended) {
							this.fireEvent('delete', this, this.getPath(options.node));
						}
						options.node.parentNode.removeChild(options.node);
					break;

					case 'newdir':
						if(true !== this.eventsSuspended) {
							this.fireEvent('newdir', this, options.node);
						}
					break;

					case 'rename':
						this.updateCls(options.node, options.params.oldname);
						if(true !== this.eventsSuspended) {
							this.fireEvent('rename', this, options.node, options.params.newname, options.params.oldname);
						}
					break;
				}
			} // eo process command success
			// process command failure
			else {
				switch(options.params.cmd) {

					case 'rename':
						// handle drag & drop rename error
						if(options.oldParent) {
							options.oldParent.appendChild(options.node);
						}
						// handle simple rename error
						else {
							options.node.setText(options.oldName);
						}
						// signal failure to onNodeDrop
						if(options.e) {
							options.e.failure = true;
						}
						if(true !== this.eventsSuspended) {
							this.fireEvent('renamefailure', this, options.node, options.params.newname, options.params.oldname);
						}
					break;

					case 'newdir':
						if(false !== this.eventsSuspended) {
							this.fireEvent('newdirfailure', this, options.params.dir);
						}
						options.node.parentNode.removeChild(options.node);
					break;

					case 'delete':
						if(true !== this.eventsSuspended) {
							this.fireEvent('deletefailure', this, options.node);
						}
						options.node.parentNode.reload.defer(1, options.node.parentNode);
					break;

					default:
						this.root.reload();
					break;
				}

				// show default message box with server error
				this.showError(o.error || response.responseText);
			} // eo process command failure
		} // eo process Ajax success

		// process Ajax failure
		else {
			this.showError(response.responseText);
		}
	} // eo function cmdCallback
	// }}}
	// {{{
	/**
	 * displays overwrite confirm msg box and runs passed callback if response is yes
	 * @private
	 * @param {String} filename File to overwrite
	 * @param {Function} callback Function to call on yes response
	 * @param {Object} scope Scope for callback (defaults to this)
	 */
	,confirmOverwrite:function(filename, callback, scope) {
		Ext.Msg.show({
			 title:this.confirmText
			,msg:String.format(this.existsText, filename) + '. ' + this.overwriteText
			,icon:Ext.Msg.QUESTION
			,buttons:Ext.Msg.YESNO
			,fn:callback.createDelegate(scope || this)
		});
	}
	// }}}
	// {{{
	/**
	 * creates new directory (node)
	 * @private
	 * @param {Ext.tree.AsyncTreeNode} node
	 */
	,createNewDir:function(node) {

		// fire beforenewdir event
		if(true !== this.eventsSuspended && false === this.fireEvent('beforenewdir', this, node)) {
			return;
		}

		var treeEditor = this.treeEditor;
		var newNode;

		// get node to append the new directory to
		var appendNode = node.isLeaf() ? node.parentNode : node;

		// create new folder after the appendNode is expanded
		appendNode.expand(false, false, function(n) {
			// create new node
			newNode = n.appendChild(new Ext.tree.AsyncTreeNode({text:this.newdirText, iconCls:'folder'}));

			// setup one-shot event handler for editing completed
			treeEditor.on({
				complete:{
					 scope:this
					,single:true
					,fn:this.onNewDir
				}}
			);

			// creating new directory flag
			treeEditor.creatingNewDir = true;

			// start editing after short delay
			(function(){treeEditor.triggerEdit(newNode);}.defer(10));
		// expand callback needs to run in this context
		}.createDelegate(this));

	} // eo function creatingNewDir
	// }}}
	// {{{
	/**
	 * deletes the passed node
	 * @private
	 * @param {Ext.tree.AsyncTreeNode} node
	 */
	,deleteNode:function(node) {
		// fire beforedelete event
		if(true !== this.eventsSuspended && false === this.fireEvent('beforedelete', this, node)) {
			return;
		}

		Ext.Msg.show({
			 title:this.deleteText
			,msg:this.reallyWantText + ' ' + this.deleteText.toLowerCase()  + ' <b>' + node.text + '</b>?'
			,icon:Ext.Msg.WARNING
			,buttons:Ext.Msg.YESNO
			,scope:this
			,fn:function(response) {
				// do nothing if answer is not yes
				if('yes' !== response) {
					this.getEl().dom.focus();
					return;
				}
				// setup request options
				var options = {
					 url:this.deleteUrl || this.url
					,method:this.method
					,scope:this
					,callback:this.cmdCallback
					,node:node
					,params:{
						 cmd:'delete'
						,file:this.getPath(node)
					}
				};
				Ext.Ajax.request(options);
			}
		});
	} // eo function deleteNode
	// }}}
	// {{{
	/**
	 * requests file download from server
	 * @private
	 * @param {String} path Full path including file name but relative to server root path
	 */
	,downloadFile:function(path) {

		// create hidden target iframe
		var id = Ext.id();
		var frame = document.createElement('iframe');
		frame.id = id;
		frame.name = id;
		frame.className = 'x-hidden';
		if(Ext.isIE) {
			frame.src = Ext.SSL_SECURE_URL;
		}

		document.body.appendChild(frame);

		if(Ext.isIE) {
			document.frames[id].name = id;
		}

		var form = Ext.DomHelper.append(document.body, {
			 tag:'form'
			,method:'post'
			,action:this.downloadUrl || this.url
			,target:id
		});

		document.body.appendChild(form);

		var hidden;

		// append cmd to form
		hidden = document.createElement('input');
		hidden.type = 'hidden';
		hidden.name = 'cmd';
		hidden.value = 'download';
		form.appendChild(hidden);

		// append path to form
		hidden = document.createElement('input');
		hidden.type = 'hidden';
		hidden.name = 'path';
		hidden.value = path;
		form.appendChild(hidden);

		var callback = function() {
			Ext.EventManager.removeListener(frame, 'load', callback, this);
			setTimeout(function() {document.body.removeChild(form);}, 100);
			setTimeout(function() {document.body.removeChild(frame);}, 110);
		};
		
		Ext.EventManager.on(frame, 'load', callback, this);

		form.submit();
	}
	// }}}
	// {{{
	/**
	 * returns (and lazy create) the context menu
	 * @private
	 */
	,getContextMenu:function() {
		// lazy create context menu
		if(!this.contextmenu) {
			var config = {
				 singleUpload:this.singleUpload
				,maxFileSize:this.maxFileSize
				,enableProgress:this.enableProgress
			};
			if(this.baseParams) {
				config.baseParams = this.baseParams;
			}
			this.contextmenu = new Ext.ux.FileTreeMenu(config);
			this.contextmenu.on({click:{scope:this, fn:this.onContextClick}});

			this.uploadPanel = this.contextmenu.getItemByCmd('upload-panel').component;
			this.uploadPanel.on({
				 beforeupload:{scope:this, fn:this.onBeforeUpload}
				,allfinished:{scope:this, fn:this.onAllFinished}
			});
			this.uploadPanel.setUrl(this.uploadUrl || this.url);
		}
		return this.contextmenu;
	} // eo function getContextMenu
	// }}}
	// {{{
	/**
	 * returns file class based on name extension
	 * @private
	 * @param {String} name File name to get class of
	 */
	,getFileCls:function(name) {
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
	 * returns path of node (file/directory)
	 * @private
	 */
	,getPath:function(node) {
		var path, p, a;

		// get path for non-root node
		if(node !== this.root) {
			p = node.parentNode;
			a = [node.text];
			while(p && p !== this.root) {
				a.unshift(p.text);
				p = p.parentNode;
			}
			a.unshift(this.root.attributes.path || '');
			path = a.join(this.pathSeparator);
		}

		// path for root node is it's path attribute
		else {
			path = node.attributes.path || '';
		}

		// a little bit of security: strip leading / or .
		// full path security checking has to be implemented on server
		path = path.replace(/^[\/\.]*/, '');
		return path;
	} // eo function getPath
	// }}}
	// {{{
	/**
	 * returns true if node has child with the specified name (text)
	 * @private
	 * @param {Ext.data.Node} node
	 * @param {String} childName
	 */
	,hasChild:function(node, childName) {
		return (node.isLeaf() ? node.parentNode : node).findChild('text', childName) !== null;
	}
	// }}}
	// {{{
	/**
	 * Hides context menu
	 * @return {Ext.ux.FileTreeMenu} this
	 */
	,hideContextMenu:function() {
		if(this.contextmenu && this.contextmenu.isVisible()) {
			this.contextmenu.hide();
		}
		return this;
	} // eo function hideContextMenu
	// }}}
	// {{{
	/**
	 * called before editing is completed - allows edit cancellation
	 * @private
	 * @param {TreeEditor} editor
	 * @param {String} newName
	 * @param {String} oldName
	 */
	,onBeforeEditComplete:function(editor, newName, oldName) {
		if(editor.cancellingEdit) {
			editor.cancellingEdit = false;
			return;
		}
		var oldPath = this.getPath(editor.editNode);
		var newPath = oldPath.replace(/\/[^\\]+$/, '/' + newName);

		if(false === this.fireEvent('beforerename', this, editor.editNode, newPath, oldPath)) {
			editor.cancellingEdit = true;
			editor.cancelEdit();
			return false;
		}
	}
	// }}}
	// {{{
	/**
	 * runs before node is dropped
	 * @private
	 * @param {Object} e dropEvent object
	 */
	,onBeforeNodeDrop:function(e) {

		// source node, node being dragged
		var s = e.dropNode;

		// destination node (dropping on this node)
		var d = e.target.leaf ? e.target.parentNode : e.target;

		// node has been dropped within the same parent
		if(s.parentNode === d) {
			return false;
		}

		// check if same name exists in the destination
		// this works only if destination node is loaded
		if(this.hasChild(d, s.text) && undefined === e.confirmed) {
			this.confirmOverwrite(s.text, function(response) {
				e.confirmed = 'yes' === response;
				this.onBeforeNodeDrop(e);
			});
			return false;
		}
		if(false === e.confirmed) {
			return false;
		}

		e.confirmed = undefined;
		e.oldParent = s.parentNode;

		var oldName = this.getPath(s);
		var newName = this.getPath(d) + '/' + s.text;

		// fire beforerename event
		if(true !== this.eventsSuspended && false === this.fireEvent('beforerename', this, s, newName, oldName)) {
			return false;
		}

		var options = {
			 url:this.renameUrl || this.url
			,method:this.method
			,scope:this
			,callback:this.cmdCallback
			,node:s
			,oldParent:s.parentNode
			,e:e
			,params:{
				 cmd:'rename'
				,oldname:oldName
				,newname:newName
			}
		};
		Ext.Ajax.request(options);
		return true;
	}
	// }}}
	// {{{
	/**
	 * sets uploadPanel's destination path
	 * @private
	 */
	,onBeforeUpload:function(uploadPanel) {

		var menu = this.getContextMenu();
		var path = this.getPath(menu.node);
		if(menu.node.isLeaf()) {
			path = path.replace(/\/[^\/]+$/, '', path);
		}
		uploadPanel.setPath(path);

	} // eo function onBeforeUpload
	// }}}
	// {{{
	/**
	 * reloads tree node on upload finish
	 * @private
	 */
	,onAllFinished:function(uploader) {
		var menu = this.getContextMenu();
		(menu.node.isLeaf() ? menu.node.parentNode : menu.node).reload();
	} // eo function onAllFinished
	// }}}
	// {{{
	/**
	 * @private
	 * context menu click handler
	 * @param {Ext.menu.Menu} context menu
	 * @param {Ext.menu.Item} item clicked
	 * @param {Ext.EventObject} raw event
	 */
	,onContextClick:function(menu, item, e) {
		if(item.disabled) {
			return;
		}
		var node = menu.node;
		if(!node) {
			node = menu.parentMenu.node;
		}
		switch(item.cmd) {
			case 'reload':
				node.reload();
			break;

			case 'expand':
				node.expand(true);
			break;

			case 'collapse':
				node.collapse(true);
			break;

			case 'open':
				this.openNode(node);
			break;

			case 'open-self':
				this.openNode(node, '_self');
			break;

			case 'open-popup':
				this.openNode(node, 'popup');
			break;

			case 'open-blank':
				this.openNode(node, '_blank');
			break;

			case 'open-dwnld':
				this.openNode(node, 'download');
			break;

			case 'rename':
				this.treeEditor.triggerEdit(node);
			break;

			case 'delete':
				this.deleteNode(node);
			break;

			case 'newdir':
				this.createNewDir(node);
			break;

			default:
			break;
		}
	} // eo function onContextClick
	// }}}
	// {{{
	/**
	 * contextmenu event handler
	 * @private
	 */
	,onContextMenu:function(node, e) {
		if(this.readOnly) {
			return false;
		}
		this.showContextMenu(node);

		return false;
	} // eo function onContextMenu
	// }}}
	// {{{
	/**
	 * dblclick handlers
	 * @private
	 */
	,onDblClick:function(node, e) {
		this.openNode(node);
	} // eo function onDblClick
	// }}}
	// {{{
	/**
	 * Destroys the FileTreePanel and sub-components
	 * @private
	 */
	,onDestroy:function() {

		// destroy contextmenu
		if(this.contextmenu) {
			this.contextmenu.purgeListeners();
			this.contextmenu.destroy();
			this.contextmenu = null;
		}

		// destroy treeEditor
		if(this.treeEditor) {
			this.treeEditor.purgeListeners();
			this.treeEditor.destroy();
			this.treeEditor = null;
		}

		// remover reference to treeSorter
		if(this.treeSorter) {
			this.treeSorter = null;
		}

		// call parent
		Ext.ux.FileTreePanel.superclass.onDestroy.call(this);

	} // eo function onDestroy
	// }}}
	// {{{
	/**
	 * runs when editing of a node (rename) is completed
	 * @private
	 * @param {Ext.Editor} editor
	 * @param {String} newName
	 * @param {String} oldName
	 */
	,onEditComplete:function(editor, newName, oldName) {

		var node = editor.editNode;

		if(newName === oldName || editor.creatingNewDir) {
			editor.creatingNewDir = false;
			return;
		}
		var path = this.getPath(node.parentNode);
		var options = {
			 url:this.renameUrl || this.url
			,method:this.method
			,scope:this
			,callback:this.cmdCallback
			,node:node
			,oldName:oldName
			,params:{
				 cmd:'rename'
				,oldname:path + '/' + oldName
				,newname:path + '/' + newName
			}
		};
		Ext.Ajax.request(options);
	}
	// }}}
	// {{{
	/**
	 * create new directory handler
	 * @private
	 * runs after editing of new directory name is completed
	 * @param {Ext.Editor} editor
	 */
	,onNewDir:function(editor) {
		var path = this.getPath(editor.editNode);
		var options = {
			 url:this.newdirUrl || this.url
			,method:this.method
			,scope:this
			,node:editor.editNode
			,callback:this.cmdCallback
			,params:{
				 cmd:'newdir'
				,dir:path
			}
		};
		Ext.Ajax.request(options);
	}
	// }}}
	// {{{
	/**
	 * called while dragging over, decides if drop is allowed
	 * @private
	 * @param {Object} dd event
	 */
	,onNodeDragOver:function(e) {
		e.cancel = e.target.disabled || e.dropNode.parentNode === e.target.parentNode && e.target.isLeaf();
	} // eo function onNodeDragOver
	// }}}
	// {{{
	/**
	 * called when node is dropped
	 * @private
	 * @param {Object} dd event
	 */
	,onNodeDrop:function(e) {

		// failure can be signalled by cmdCallback
		// put drop node to the original parent in that case
		if(true === e.failure) {
			e.oldParent.appendChild(e.dropNode);
			return;
		}

		// if we already have node with the same text, remove the duplicate
		var sameNode = e.dropNode.parentNode.findChild('text', e.dropNode.text);
		if(sameNode && sameNode !== e.dropNode) {
			sameNode.parentNode.removeChild(sameNode);
		}
	}
	// }}}
	// {{{
	/**
	 * Opens node
	 * @param {Ext.tree.AsyncTreeNode} node
	 * @param {String} mode Can be "_self", "_blank", or "popup". Defaults to (this.openMode)
	 */
	,openNode:function(node, mode) {

		if(!this.enableOpen) {
			return;
		}

		mode = mode || this.openMode;

		var url;
		var path;
		if(node.isLeaf()) {
			path = this.getPath(node);
			url = this.hrefPrefix + path + this.hrefSuffix;

			// fire beforeopen event
			if(true !== this.eventsSuspended && false === this.fireEvent('beforeopen', this, node.text, url, mode)) {
				return;
			}

			switch(mode) {
				case 'popup':
					if(!this.popup || this.popup.closed) {
						this.popup = window.open(url, this.hrefTarget, this.popupFeatures);
					}
					this.popup.location = url;
					if(this.focusPopup) {
						this.popup.focus();
					}
				break;

				case '_self':
					window.location = url;
				break;

				case '_blank':
					window.open(url);
				break;

				case 'download':
					this.downloadFile(path);
				break;
			}

			// fire open event
			if(true !== this.eventsSuspended) {
				this.fireEvent('open', this, node.text, url, mode);
			}
		}

	}
	// }}}
	// {{{
	/**
	 * Sets/Unsets delete of files/directories disabled/enabled
	 * @param {Boolean} disabled
	 * @return {Ext.ux.FileTreePanel} this
	 */
	,setDeleteDisabled:function(disabled) {
		disabled = !(!disabled);
		if(!this.enableDelete === disabled) {
			return this;
		}
		this.hideContextMenu();
		this.enableDelete = !disabled;
	} // eo function setDeleteDisabled
	// }}}
	// {{{
	/**
	 * Sets/Unsets creation of new directory disabled/enabled
	 * @param {Boolean} disabled
	 * @return {Ext.ux.FileTreePanel} this
	 */
	,setNewdirDisabled:function(disabled) {
		disabled = !(!disabled);
		if(!this.enableNewDir === disabled) {
			return this;
		}
		this.hideContextMenu();
		this.enableNewDir = !disabled;

	} // eo function setNewdirDisabled
	// }}}
	// {{{
	/**
	 * Sets/Unsets open files disabled/enabled
	 * @param {Boolean} disabled
	 * @return {Ext.ux.FileTreePanel} this
	 */
	,setOpenDisabled:function(disabled) {
		disabled = !(!disabled);
		if(!this.enableOpen === disabled) {
			return this;
		}
		this.hideContextMenu();
		this.enableOpen = !disabled;

		return this;
	} // eo function setOpenDisabled
	// }}}
	// {{{
	/**
	 * Sets/Unsets this tree to/from readOnly state
	 * @param {Boolean} readOnly
	 * @return {Ext.ux.FileTreePanel} this
	 */
	,setReadOnly:function(readOnly) {
		readOnly = !(!readOnly);
		if(this.readOnly === readOnly) {
			return this;
		}
		this.hideContextMenu();
		if(this.dragZone) {
			this.dragZone.locked = readOnly;
		}
		this.readOnly = readOnly;

		return this;

	} // eo function setReadOnly
	// }}}
	// {{{
	/**
	 * Sets/Unsets rename of files/directories disabled/enabled
	 * @param {Boolean} disabled
	 * @return {Ext.ux.FileTreePanel} this
	 */
	,setRenameDisabled:function(disabled) {
		disabled = !(!disabled);
		if(!this.enableRename === disabled) {
			return this;
		}
		this.hideContextMenu();
		if(this.dragZone) {
			this.dragZone.locked = disabled;
		}
		this.enableRename = !disabled;

		return this;
	} // eo function setRenameDisabled
	// }}}
	// {{{
	/**
	 * Sets/Unsets uploading of files disabled/enabled
	 * @param {Boolean} disabled
	 * @return {Ext.ux.FileTreePanel} this
	 */
	,setUploadDisabled:function(disabled) {
		disabled = !(!disabled);
		if(!this.enableUpload === disabled) {
			return this;
		}
		this.hideContextMenu();
		this.enableUpload = !disabled;

		return this;
	} // of function setUploadDisabled
	// }}}
	// {{{
	/**
	 * adjusts context menu depending on many things and shows it
	 * @private
	 * @param {Ext.tree.AsyncTreeNode} node Node on which was right-clicked
	 */
	,showContextMenu:function(node) {

		// setup node alignment
		var topAlign = false;
		var alignEl = this.topMenu ? this.topMenu.getEl() : this.body;

		if(!node) {
			node = this.getSelectionModel().getSelectedNode();
			topAlign = true;
		}
		else {
			alignEl = node.getUI().getEl();
		}
		if(!node) {
			return;
		}

		var menu = this.getContextMenu();
		menu.node = node;

		// set node name
		menu.getItemByCmd('nodename').setText(Ext.util.Format.ellipsis(node.text, 22));

		// enable/disable items depending on node clicked
		menu.setItemDisabled('open', !node.isLeaf());
		menu.setItemDisabled('reload', node.isLeaf());
		menu.setItemDisabled('expand', node.isLeaf());
		menu.setItemDisabled('collapse', node.isLeaf());
		menu.setItemDisabled('delete', node === this.root || node.disabled);
		menu.setItemDisabled('rename', this.readOnly || node === this.root || node.disabled);
		menu.setItemDisabled('newdir', this.readOnly || (node.isLeaf() ? node.parentNode.disabled : node.disabled));
		menu.setItemDisabled('upload', node.isLeaf() ? node.parentNode.disabled : node.disabled);
		menu.setItemDisabled('upload-panel', node.isLeaf() ? node.parentNode.disabled : node.disabled);
		
		// show/hide logic
		menu.getItemByCmd('open').setVisible(this.enableOpen);
		menu.getItemByCmd('delete').setVisible(this.enableDelete);
		menu.getItemByCmd('newdir').setVisible(this.enableNewDir);
		menu.getItemByCmd('rename').setVisible(this.enableRename);
		menu.getItemByCmd('upload').setVisible(this.enableUpload);
		menu.getItemByCmd('upload-panel').setVisible(this.enableUpload);
		menu.getItemByCmd('sep-upload').setVisible(this.enableUpload);
		menu.getItemByCmd('sep-collapse').setVisible(this.enableNewDir || this.enableDelete || this.enableRename);

		// select node
		node.select();

		// show menu
		if(topAlign) {
			menu.showAt(menu.getEl().getAlignToXY(alignEl, 'tl-bl?'));
		}
		else {
			menu.showAt(menu.getEl().getAlignToXY(alignEl, 'tl-tl?', [0, 18]));
		}
	} // eo function 
	// }}}
	// {{{
	/**
	 * universal show error function
	 * @private
	 * @param {String} msg message
	 * @param {String} title title
	 */
	,showError:function(msg, title) {
		Ext.Msg.show({
			 title:title || this.errorText
			,msg:Ext.util.Format.ellipsis(msg, this.maxMsgLen)
			,fixCursor:true
			,icon:Ext.Msg.ERROR
			,buttons:Ext.Msg.OK
			,minWidth:1200 > String(msg).length ? 360 : 600
		});
	} // eo function showError
	// }}}
	// {{{
	/**
	 * updates class of leaf after rename
	 * @private
	 * @param {Ext.tree.AsyncTreeNode} node Node to update class of
	 * @param {String} oldName Name the node had before
	 */
	,updateCls:function(node, oldName) {
		if(node.isLeaf()) {
			Ext.fly(node.getUI().iconNode).removeClass(this.getFileCls(oldName));
			Ext.fly(node.getUI().iconNode).addClass(this.getFileCls(node.text));
		}
	}
	// }}}

}); // eo extend

// register xtype
Ext.reg('filetreepanel', Ext.ux.FileTreePanel);

// eof
