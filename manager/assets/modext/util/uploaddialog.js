/**
 * This namespace should be in another file but I dicided to put it here for consistancy.
 */
Ext.namespace('Ext.ux.Utils');

/**
 * This class implements event queue behaviour.
 *
 * @class Ext.ux.Utils.EventQueue
 * @param function  handler  Event handler.
 * @param object    scope    Handler scope.
 */
Ext.ux.Utils.EventQueue = function(handler, scope) {
  if (!handler) {
    throw 'Handler is required.';
  }
  this.handler = handler;
  this.scope = scope || window;
  this.queue = [];
  this.is_processing = false;
  
  /**
   * Posts event into the queue.
   * 
   * @access public
   * @param mixed event Event identificator.
   * @param mixed data  Event data.
   */
  this.postEvent = function(event, data) {
    data = data || null;
    this.queue.push({event: event, data: data});
    if (!this.is_processing) {
      this.process();
    }
  }
  
  this.flushEventQueue = function() {
    this.queue = [];
  },
  
  /**
   * @access private
   */
  this.process = function() {
    while (this.queue.length > 0) {
      this.is_processing = true;
      var event_data = this.queue.shift();
      this.handler.call(this.scope, event_data.event, event_data.data);
    }
    this.is_processing = false;
  }
};

/**
 * This class implements Mili's finite state automata behaviour.
 *  
 *  Transition / output table format:
 *  {
 *    'state_1' : {
 *      'event_1' : [
 *        {
 *          p|predicate: function,    // Transition predicate, optional, default to true.
 *                                    // If array then conjunction will be applyed to the operands.
 *                                    // Predicate signature is (data, event, this).
 *          a|action: function|array, // Transition action, optional, default to Ext.emptyFn.
 *                                    // If array then methods will be called sequentially.
 *                                    // Action signature is (data, event, this).
 *          s|state: 'state_x',       // New state - transition destination, optional, default to 
 *                                    // current state.
 *          scope: object             // Predicate and action scope, optional, default to 
 *                                    // trans_table_scope or window.
 *        }
 *      ]
 *    },
 *
 *    'state_2' : {
 *      ...
 *    }
 *    ...
 *  }
 *
 *  @param  mixed initial_state Initial state.
 *  @param  object trans_table Transition / output table.
 *  @param  trans_table_scope Transition / output table's methods scope.
 */
Ext.ux.Utils.FSA = function(initial_state, trans_table, trans_table_scope) {
  this.current_state = initial_state;
  this.trans_table = trans_table || {};
  this.trans_table_scope = trans_table_scope || window;
  Ext.ux.Utils.FSA.superclass.constructor.call(this, this.processEvent, this);
};
Ext.extend(Ext.ux.Utils.FSA, Ext.ux.Utils.EventQueue,{
  current_state : null,
  trans_table : null,  
  trans_table_scope : null,
  
  /**
   * Returns current state
   * 
   * @access public
   * @return mixed Current state.
   */
  state : function() {
    return this.current_state;
  },
  
  /**
   * @access public
   */
  processEvent : function(event, data) {
    var transitions = this.currentStateEventTransitions(event);
    if (!transitions) {
      throw "State '" + this.current_state + "' has no transition for event '" + event + "'.";
    }
    for (var i = 0, len = transitions.length; i < len; i++) {
      var transition = transitions[i];

      var predicate = transition.predicate || transition.p || true;
      var action = transition.action || transition.a || Ext.emptyFn;
      var new_state = transition.state || transition.s || this.current_state;
      var scope = transition.scope || this.trans_table_scope;
      
      if (this.computePredicate(predicate, scope, data, event)) {
        this.callAction(action, scope, data, event);
        this.current_state = new_state; 
        return;
      }
    }
    
    throw "State '" + this.current_state + "' has no transition for event '" + event + "' in current context";
  },
  
  /**
   * @access private
   */
  currentStateEventTransitions : function(event) {
    return this.trans_table[this.current_state] ? 
      this.trans_table[this.current_state][event] || false
      :
      false;
  },
  
  /**
   * @access private
   */
  computePredicate : function(predicate, scope, data, event) {
    var result = false; 
    
    switch (Ext.type(predicate)) {
     case 'function':
       result = predicate.call(scope, data, event, this);
       break;
     case 'array':
       result = true;
       for (var i = 0, len = predicate.length; result && (i < len); i++) {
         if (Ext.type(predicate[i]) == 'function') {
           result = predicate[i].call(scope, data, event, this);
         }
         else {
           throw [
             'Predicate: ',
             predicate[i],
             ' is not callable in "',
             this.current_state,
             '" state for event "',
             event
           ].join('');
         }
       }
       break;
     case 'boolean':
       result = predicate;
       break;
     default:
       throw [
         'Predicate: ',
         predicate,
         ' is not callable in "',
         this.current_state,
         '" state for event "',
         event
       ].join('');
    }
    return result;
  },
  
  /**
   * @access private
   */
  callAction : function(action, scope, data, event)
  {
    switch (Ext.type(action)) {
       case 'array':
       for (var i = 0, len = action.length; i < len; i++) {
         if (Ext.type(action[i]) == 'function') {
           action[i].call(scope, data, event, this);
         }
         else {
           throw [
             'Action: ',
             action[i],
             ' is not callable in "',
             this.current_state,
             '" state for event "',
             event
           ].join('');
         }
       }
         break;
     case 'function':
       action.call(scope, data, event, this);
       break;
     default:
       throw [
         'Action: ',
         action,
         ' is not callable in "',
         this.current_state,
         '" state for event "',
         event
       ].join('');
    }
  }
});

// ---------------------------------------------------------------------------------------------- //

/**
 * Ext.ux.UploadDialog namespace.
 */
Ext.namespace('Ext.ux.UploadDialog');

/**
 * File upload browse button.
 *
 * @class Ext.ux.UploadDialog.BrowseButton
 */ 
Ext.ux.UploadDialog.BrowseButton = Ext.extend(Ext.Button,{
  input_name : 'file',
  
  input_file : null,
  
  original_handler : null,
  
  original_scope : null,
  
  /**
   * @access private
   */
  initComponent : function()
  {
    Ext.ux.UploadDialog.BrowseButton.superclass.initComponent.call(this);
    this.original_handler = this.handler || null;
    this.original_scope = this.scope || window;
    this.handler = null;
    this.scope = null;
  },
  
  /**
   * @access private
   */
  onRender : function(ct, position)
  {
    Ext.ux.UploadDialog.BrowseButton.superclass.onRender.call(this, ct, position);
    this.createInputFile();
  },
  
  /**
   * @access private
   */
  createInputFile : function()
  {
    var button_container = this.el;
        button_container.position('relative');
       this.wrap = this.el.wrap({cls:'tbody'});    
       this.input_file = this.wrap.createChild({
           tag: 'input',
            type: 'file',
            size: 1,
            name: this.input_name || Ext.id(this.el),
            style: "position: absolute; display: block; border: none; cursor: pointer"
        });
        this.input_file.setOpacity(0.0);
    
    var button_box = button_container.getBox();
    this.input_file.setStyle('font-size', (button_box.width * 0.5) + 'px');

    var input_box = this.input_file.getBox();
    var adj = {x: 3, y: 3}
    if (Ext.isIE) {
      adj = {x: 0, y: 3}
    }
    
    this.input_file.setLeft(button_box.width - input_box.width + adj.x + 'px');
    this.input_file.setTop(button_box.height - input_box.height + adj.y + 'px');
    this.input_file.setOpacity(0.0);
        
    if (this.handleMouseEvents) {
      this.input_file.on('mouseover', this.onMouseOver, this);
        this.input_file.on('mousedown', this.onMouseDown, this);
    }
    
    if(this.tooltip){
      if(typeof this.tooltip == 'object'){
        Ext.QuickTips.register(Ext.apply({target: this.input_file}, this.tooltip));
      } 
      else {
        this.input_file.dom[this.tooltipType] = this.tooltip;
        }
      }
    
    this.input_file.on('change', this.onInputFileChange, this);
    this.input_file.on('click', function(e) { e.stopPropagation(); }); 
  },
  
  /**
   * @access public
   */
  detachInputFile : function(no_create)
  {
    var result = this.input_file;
    
    no_create = no_create || false;
    
    if (typeof this.tooltip == 'object') {
      Ext.QuickTips.unregister(this.input_file);
    }
    else {
      this.input_file.dom[this.tooltipType] = null;
    }
    this.input_file.removeAllListeners();
    this.input_file = null;
    
    if (!no_create) {
      this.createInputFile();
    }
    return result;
  },
  
  /**
   * @access public
   */
  getInputFile : function()
  {
    return this.input_file;
  },
  
  /**
   * @access public
   */
  disable : function()
  {
    Ext.ux.UploadDialog.BrowseButton.superclass.disable.call(this);  
    this.input_file.dom.disabled = true;
  },
  
  /**
   * @access public
   */
  enable : function()
  {
    Ext.ux.UploadDialog.BrowseButton.superclass.enable.call(this);
    this.input_file.dom.disabled = false;
  },
  
  /**
   * @access public
   */
  destroy : function()
  {
    var input_file = this.detachInputFile(true);
    input_file.remove();
    input_file = null;
    Ext.ux.UploadDialog.BrowseButton.superclass.destroy.call(this);      
  },
  
  /**
   * @access private
   */
  onInputFileChange : function()
  {
    if (this.original_handler) {
      this.original_handler.call(this.original_scope, this);
    }
  }  
});

/**
 * Toolbar file upload browse button.
 *
 * @class Ext.ux.UploadDialog.TBBrowseButton
 */
Ext.ux.UploadDialog.TBBrowseButton = Ext.extend(Ext.ux.UploadDialog.BrowseButton,{
  hideParent : true
  ,onDestroy : function() {
    Ext.ux.UploadDialog.TBBrowseButton.superclass.onDestroy.call(this);
    if(this.container) {
      this.container.remove();
      }
  }
});

/**
 * Record type for dialogs grid.
 *
 * @class Ext.ux.UploadDialog.FileRecord 
 */
Ext.ux.UploadDialog.FileRecord = Ext.data.Record.create([
  {name: 'filename'},
  {name: 'state', type: 'int'},
  {name: 'note'},
  {name: 'input_element'}
]);

Ext.ux.UploadDialog.FileRecord.STATE_QUEUE = 0;
Ext.ux.UploadDialog.FileRecord.STATE_FINISHED = 1;
Ext.ux.UploadDialog.FileRecord.STATE_FAILED = 2;
Ext.ux.UploadDialog.FileRecord.STATE_PROCESSING = 3;

/**
 * Dialog class.
 *
 * @class Ext.ux.UploadDialog.Dialog
 */
Ext.ux.UploadDialog.Dialog = function(config) {
  var default_config = {
    border: false,
    width: 600,
    height: 350,
    // minWidth: 450,
    minHeight: 350,
    plain: true,
    constrainHeader: true,
    draggable: true,
    closable: true,
    maximizable: false,
    minimizable: false,
    resizable: true,
    
        layout:'fit',
        region:'center',
    autoDestroy: true,
    closeAction: 'hide',
    title: this.i18n.title,
    cls: 'ext-ux-uploaddialog-dialog',
    // --------
    url: '',
    base_params: {},
    permitted_extensions: [],
    reset_on_hide: true,
    allow_close_on_upload: false,
    upload_autostart: false,
    Make_Reload: false,
    post_var_name: 'file'
  };
  config = Ext.applyIf(config || {}, default_config);
  config.layout = 'absolute';
  
  Ext.ux.UploadDialog.Dialog.superclass.constructor.call(this, config);
};

Ext.extend(Ext.ux.UploadDialog.Dialog, Ext.Window,{
  fsa : null,
  
  state_tpl : null,
  
  form : null,
  
  grid_panel : null,
  
  progress_bar : null,
  
  is_uploading : false,
  
  initial_queued_count : 0,
  
  upload_frame : null,
  
  /**
   * @access private
   */
  //--------------------------------------------------------------------------------------------- //
  initComponent : function() {
    Ext.ux.UploadDialog.Dialog.superclass.initComponent.call(this);
    
    // Setting automata protocol
    var tt = {
      // --------------
      'created' : {
      // --------------
        'window-render' : [
          {
            action: [this.createForm, this.createProgressBar, this.createGrid],
            state: 'rendering'
          }
        ],
        'destroy' : [
          {
            action: this.flushEventQueue,
            state: 'destroyed'
          }
        ]
      },
      // --------------
      'rendering' : {
      // --------------
        'grid-render' : [
          {
            action: [this.fillToolbar, this.updateToolbar],
            state: 'ready'
          }
        ],
        'destroy' : [
          {
            action: this.flushEventQueue,
            state: 'destroyed'
          }
        ]
      },
      // --------------
      'ready' : {
      // --------------
        'file-selected' : [
          {
            predicate: [this.fireFileTestEvent, this.isPermittedFile],
            action: this.addFileToUploadQueue,
            state: 'adding-file'
          },
          {
            // If file is not permitted then do nothing.
          }
        ],
        'grid-selection-change' : [
          {
            action: this.updateToolbar
          }
        ],
        'remove-files' : [
          {
            action: [this.removeFiles, this.fireFileRemoveEvent]
          }
        ],
        'reset-queue' : [
          {
            action: [this.resetQueue, this.fireResetQueueEvent]
          }
        ],
        'start-upload' : [
          {
            predicate: this.hasUnuploadedFiles,
            action: [
              this.setUploadingFlag, this.saveInitialQueuedCount, this.updateToolbar, 
              this.updateProgressBar, this.prepareNextUploadTask, this.fireUploadStartEvent
            ],
            state: 'uploading'
          },
          {
            // Has nothing to upload, do nothing.
          }
        ],
        'stop-upload' : [
          {
            // We are not uploading, do nothing. Can be posted by user only at this state. 
          }
        ],
        'hide' : [
          {
            predicate: [this.isNotEmptyQueue, this.getResetOnHide],
            action: [this.resetQueue, this.fireResetQueueEvent]
          },
          {
            // Do nothing
          }
        ],
        'destroy' : [
          {
            action: this.flushEventQueue,
            state: 'destroyed'
          }
        ]
      },
      // --------------
      'adding-file' : {
      // --------------
        'file-added' : [
          {
            predicate: this.isUploading,
            action: [this.incInitialQueuedCount, this.updateProgressBar, this.fireFileAddEvent],
            state: 'uploading' 
          },
          {
            predicate: this.getUploadAutostart,
            action: [this.startUpload, this.fireFileAddEvent],
            state: 'ready'
          },
          {
            action: [this.updateToolbar, this.fireFileAddEvent],
            state: 'ready'
          }
        ]
      },
      // --------------
      'uploading' : {
      // --------------
        'file-selected' : [
          {
            predicate: [this.fireFileTestEvent, this.isPermittedFile],
            action: this.addFileToUploadQueue,
            state: 'adding-file'
          },
          {
            // If file is not permitted then do nothing.
          }
        ],
        'grid-selection-change' : [
          {
            // Do nothing.
          }
        ],
        'start-upload' : [
          {
            // Can be posted only by user in this state. 
          }
        ],
        'stop-upload' : [
          {
            predicate: this.hasUnuploadedFiles,
            action: [
              this.resetUploadingFlag, this.abortUpload, this.updateToolbar, 
              this.updateProgressBar, this.fireUploadStopEvent
            ],
            state: 'ready'
          },
          {
            action: [
              this.resetUploadingFlag, this.abortUpload, this.updateToolbar, 
              this.updateProgressBar, this.fireUploadStopEvent, this.fireUploadCompleteEvent
            ],
            state: 'ready'
          }
        ],
        'file-upload-start' : [
          {
            action: [this.uploadFile, this.findUploadFrame, this.fireFileUploadStartEvent]
          }
        ],
        'file-upload-success' : [
          {
            predicate: this.hasUnuploadedFiles,
            action: [
              this.resetUploadFrame, this.updateRecordState, this.updateProgressBar, 
              this.prepareNextUploadTask, this.fireUploadSuccessEvent
            ]
          },
          {
            action: [
              this.resetUploadFrame, this.resetUploadingFlag, this.updateRecordState, 
              this.updateToolbar, this.updateProgressBar, this.fireUploadSuccessEvent, 
              this.fireUploadCompleteEvent
            ],
            state: 'ready'
          }
        ],
        'file-upload-error' : [
          {
            predicate: this.hasUnuploadedFiles,
            action: [
              this.resetUploadFrame, this.updateRecordState, this.updateProgressBar, 
              this.prepareNextUploadTask, this.fireUploadErrorEvent
            ]
          },
          {
            action: [
              this.resetUploadFrame, this.resetUploadingFlag, this.updateRecordState, 
              this.updateToolbar, this.updateProgressBar, this.fireUploadErrorEvent, 
              this.fireUploadCompleteEvent
            ],
            state: 'ready'
          }
        ],
        'file-upload-failed' : [
          {
            predicate: this.hasUnuploadedFiles,
            action: [
              this.resetUploadFrame, this.updateRecordState, this.updateProgressBar, 
              this.prepareNextUploadTask, this.fireUploadFailedEvent
            ]
          },
          {
            action: [
              this.resetUploadFrame, this.resetUploadingFlag, this.updateRecordState, 
              this.updateToolbar, this.updateProgressBar, this.fireUploadFailedEvent, 
              this.fireUploadCompleteEvent
            ],
            state: 'ready'
          }
        ],
        'hide' : [
          {
            predicate: this.getResetOnHide,
            action: [this.stopUpload, this.repostHide]
          },
          {
            // Do nothing.
          }
        ],
        'destroy' : [
          {
            predicate: this.hasUnuploadedFiles,
            action: [
              this.resetUploadingFlag, this.abortUpload,
              this.fireUploadStopEvent, this.flushEventQueue
            ],
            state: 'destroyed'
          },
          {
            action: [
              this.resetUploadingFlag, this.abortUpload,
              this.fireUploadStopEvent, this.fireUploadCompleteEvent, this.flushEventQueue
            ], 
            state: 'destroyed'
          }
        ]
      },
      // --------------
      'destroyed' : {
      // --------------
      }
    };
    this.fsa = new Ext.ux.Utils.FSA('created', tt, this);
    
    // Registering dialog events.
    this.addEvents({
      'filetest': true
      ,'fileadd' : true
      ,'fileremove' : true
      ,'resetqueue' : true
      ,'uploadsuccess' : true
      ,'uploaderror' : true
      ,'uploadfailed' : true
      ,'uploadstart' : true
      ,'uploadstop' : true
      ,'uploadcomplete' : true
      ,'fileuploadstart' : true
    });
    
    // Attaching to window events.
    this.on('render', this.onWindowRender, this);
    this.on('beforehide', this.onWindowBeforeHide, this);
    this.on('hide', this.onWindowHide, this);
    this.on('destroy', this.onWindowDestroy, this);
    
    // Compiling state template.
    this.state_tpl = new Ext.Template(
      "<div class='ext-ux-uploaddialog-state ext-ux-uploaddialog-state-{state}'> </div>"
    ).compile();
  },
  
  createForm : function()
  {
    this.form = Ext.DomHelper.append(this.body, {
      tag: 'form',
      method: 'post',
      action: this.url,
      style: 'position: absolute; left: -100px; top: -100px; width: 100px; height: 100px; clear: both;'
    });
  },
  
  createProgressBar : function()
  {
    this.progress_bar = this.add(
      new Ext.ProgressBar({
        x: 0,
        y: 0,
        anchor: '0',
        value: 0.0,
        text: this.i18n.progress_waiting_text
      })
    );
  },
  
  createGrid : function()
  {
    var store = new Ext.data.Store({
      proxy: new Ext.data.MemoryProxy([]),
      reader: new Ext.data.JsonReader({}, Ext.ux.UploadDialog.FileRecord),
      sortInfo: {field: 'state', direction: 'DESC'},
      pruneModifiedRecords: true
    });
    
    var cm = new Ext.grid.ColumnModel([
      {
        header: this.i18n.state_col_title,
        width: this.i18n.state_col_width,
        resizable: false,
        dataIndex: 'state',
        sortable: true,
        renderer: this.renderStateCell.createDelegate(this)
      },
      {
        header: this.i18n.filename_col_title,
        width: this.i18n.filename_col_width,
        dataIndex: 'filename',
        sortable: true,
        renderer: this.renderFilenameCell.createDelegate(this)
      },
      {
        header: this.i18n.note_col_title,
        width: this.i18n.note_col_width, 
        dataIndex: 'note',
        sortable: true,
        renderer: this.renderNoteCell.createDelegate(this)
      }
    ]);
      this.grid_panel = new Ext.grid.GridPanel({
      ds: store,
      cm: cm,
        layout:'fit',
        height: this.height-100,
        region:'center',
      x: 0,
      y: 22,
      border: true,
      
        viewConfig: {
        autoFill: true,
          forceFit: true
        },
      
      bbar : new Ext.Toolbar()
    });
    this.grid_panel.on('render', this.onGridRender, this);
    
    this.add(this.grid_panel);
    
    this.grid_panel.getSelectionModel().on('selectionchange', this.onGridSelectionChange, this);
  },
  
  fillToolbar : function()
  {
    var tb = this.grid_panel.getBottomToolbar();
    tb.x_buttons = {}
    
    tb.x_buttons.add = tb.addItem(new Ext.ux.UploadDialog.TBBrowseButton({
      input_name: this.post_var_name,
      text: this.i18n.add_btn_text,
      tooltip: this.i18n.add_btn_tip,
      iconCls: 'ext-ux-uploaddialog-addbtn',
      handler: this.onAddButtonFileSelected,
      scope: this
    }));
    tb.x_buttons.remove = tb.addButton({
      text: this.i18n.remove_btn_text,
      tooltip: this.i18n.remove_btn_tip,
      iconCls: 'ext-ux-uploaddialog-removebtn',
      handler: this.onRemoveButtonClick,
      scope: this
    }); 
    tb.x_buttons.reset = tb.addButton({
      text: this.i18n.reset_btn_text,
      tooltip: this.i18n.reset_btn_tip,
      iconCls: 'ext-ux-uploaddialog-resetbtn',
      handler: this.onResetButtonClick,
      scope: this
    });
    tb.x_buttons.upload = tb.addButton({
      text: this.i18n.upload_btn_start_text,
      tooltip: this.i18n.upload_btn_start_tip,
      iconCls: 'ext-ux-uploaddialog-uploadstartbtn',
      handler: this.onUploadButtonClick,
      scope: this
    });
    tb.x_buttons.close = tb.addButton({
      text: this.i18n.close_btn_text,
      tooltip: this.i18n.close_btn_tip,
      handler: this.onCloseButtonClick,
      scope: this
    });
  },
  
  renderStateCell : function(data, cell, record, row_index, column_index, store)
  {
    return this.state_tpl.apply({state: data});
  },
  
  renderFilenameCell : function(data, cell, record, row_index, column_index, store)
  {
    var view = this.grid_panel.getView();
    var f = function() {
      try {
        Ext.fly(
          view.getCell(row_index, column_index)
        ).child('.x-grid3-cell-inner').dom['qtip'] = data;
      }
      catch (e)
      {}
    }
    f.defer(1000);
    return data;
  },
  
  renderNoteCell : function(data, cell, record, row_index, column_index, store)
  {
    var view = this.grid_panel.getView();
    var f = function() {
      try {
        Ext.fly(
          view.getCell(row_index, column_index)
        ).child('.x-grid3-cell-inner').dom['qtip'] = data;
      }
      catch (e)
      {}
      }
    f.defer(1000);
    return data;
  },
  
  getFileExtension : function(filename)
  {
    var result = null;
    var parts = filename.split('.');
    if (parts.length > 1) {
      result = parts.pop();
    }
    return result;
  },
  
  isPermittedFileType : function(filename)
  {
    var result = true;
    if (this.permitted_extensions.length > 0) {
      result = this.permitted_extensions.indexOf(this.getFileExtension(filename)) != -1;
    }
    return result;
  },

  isPermittedFile : function(browse_btn)
  {
    var result = false;
    var filename = browse_btn.getInputFile().dom.value;
    
    if (this.isPermittedFileType(filename)) {
      result = true;
    }
    else {
      Ext.Msg.alert(
        this.i18n.error_msgbox_title, 
        String.format(
          this.i18n.err_file_type_not_permitted,
          filename,
          this.permitted_extensions.join(this.i18n.permitted_extensions_join_str)
        )
      );
      result = false;
    }
    
    return result;
  },
  
  fireFileTestEvent : function(browse_btn)
  {
    return this.fireEvent('filetest', this, browse_btn.getInputFile().dom.value) !== false;
  },
  
  addFileToUploadQueue : function(browse_btn)
  {
    var input_file = browse_btn.detachInputFile();
    
    input_file.appendTo(this.form);
    input_file.setStyle('width', '100px');
    input_file.dom.disabled = true;
    
    var store = this.grid_panel.getStore();
    var fileApi = input_file.dom.files;
    var filename = (typeof fileApi != 'undefined') ? fileApi[0].name : input_file.dom.value.replace("C:\\fakepath\\", "");
    store.add(new Ext.ux.UploadDialog.FileRecord({
          state: Ext.ux.UploadDialog.FileRecord.STATE_QUEUE
          ,filename: filename
          ,note: this.i18n.note_queued_to_upload
          ,input_element: input_file
    }));
    this.fsa.postEvent('file-added', input_file.dom.value);
  },
  
  fireFileAddEvent : function(filename)
  {
    this.fireEvent('fileadd', this, filename);
  },
  
  updateProgressBar : function()
  {
    if (this.is_uploading) {
      var queued = this.getQueuedCount(true);
      var value = 1 - queued / this.initial_queued_count;
      this.progress_bar.updateProgress(value,String.format(this.i18n.progress_uploading_text,this.initial_queued_count - queued,this.initial_queued_count));
    }
    else {
      this.progress_bar.updateProgress(0, this.i18n.progress_waiting_text);
    }
  },
  
  updateToolbar : function() {
    var tb = this.grid_panel.getBottomToolbar();
    if (this.is_uploading) {
      tb.x_buttons.remove.disable();
      tb.x_buttons.reset.disable();
      tb.x_buttons.upload.enable();
      if (!this.getAllowCloseOnUpload()) {
        tb.x_buttons.close.disable();
      }
      tb.x_buttons.upload.setIconClass('ext-ux-uploaddialog-uploadstopbtn');
      tb.x_buttons.upload.setText(this.i18n.upload_btn_stop_text);
      tb.x_buttons.upload.getEl()
        .child(tb.x_buttons.upload.buttonSelector)
        .dom[tb.x_buttons.upload.tooltipType] = this.i18n.upload_btn_stop_tip;
    }
    else {
      tb.x_buttons.remove.enable();
      tb.x_buttons.reset.enable();
      tb.x_buttons.close.enable();
      tb.x_buttons.upload.setIconClass('ext-ux-uploaddialog-uploadstartbtn');
      tb.x_buttons.upload.setText(this.i18n.upload_btn_start_text);
      
      if (this.getQueuedCount() > 0) {
        tb.x_buttons.upload.enable();
      }
      else {
        tb.x_buttons.upload.disable();      
      }
      
      if (this.grid_panel.getSelectionModel().hasSelection()) {
        tb.x_buttons.remove.enable();
      }
      else {
        tb.x_buttons.remove.disable();
      }
      
      if (this.grid_panel.getStore().getCount() > 0) {
        tb.x_buttons.reset.enable();
      }
      else {
        tb.x_buttons.reset.disable();
      }
    }
  },
  
  saveInitialQueuedCount : function()
  {
    this.initial_queued_count = this.getQueuedCount();
  },
  
  incInitialQueuedCount : function()
  {
    this.initial_queued_count++;
  },
  
  setUploadingFlag : function()
  {
    this.is_uploading = true;
  }, 
  
  resetUploadingFlag : function()
  {
    this.is_uploading = false;
  },

  prepareNextUploadTask : function()
  {
    // Searching for first unuploaded file.
    var store = this.grid_panel.getStore();
    var record = null;
    
    store.each(function(r) {
      if (!record && r.get('state') == Ext.ux.UploadDialog.FileRecord.STATE_QUEUE) {
        record = r;
      }
      else {
        r.get('input_element').dom.disabled = true;
      }
    });
    
    record.get('input_element').dom.disabled = false;
    record.set('state', Ext.ux.UploadDialog.FileRecord.STATE_PROCESSING);
    record.set('note', this.i18n.note_processing);
    record.commit();
    
    this.fsa.postEvent('file-upload-start', record);
  },
   
  fireUploadStartEvent : function()
  {
    this.fireEvent('uploadstart', this);
  },
  
  removeFiles : function(file_records)
  {
    var store = this.grid_panel.getStore();
    for (var i = 0, len = file_records.length; i < len; i++) {
      var r = file_records[i];
      r.get('input_element').remove();
      store.remove(r);
    }
  },
  
  fireFileRemoveEvent : function(file_records)
  {
    for (var i = 0, len = file_records.length; i < len; i++) {
      this.fireEvent('fileremove', this, file_records[i].get('filename'));
    }
  },
  
  resetQueue : function() {
    var store = this.grid_panel.getStore();
    store.each(function(r) {
        r.get('input_element').remove();
    });
    store.removeAll();
  },
  
  fireResetQueueEvent : function() {
    this.fireEvent('resetqueue', this);
  }
  
  ,uploadFile : function(record) {
    Ext.Ajax.request({
      url: this.url
      ,params: this.base_params || this.baseParams || this.params
      ,method: 'POST'
      ,form: this.form
      ,isUpload: true
      ,success: this.onAjaxSuccess
      ,failure: this.onAjaxFailure
      ,scope: this
      ,record: record
    });
  },
   
  fireFileUploadStartEvent : function(record)
  {
    this.fireEvent('fileuploadstart', this, record.get('filename'));
  },
  
  updateRecordState : function(data)
  {
    if ('success' in data.response && data.response.success) {
      data.record.set('state', Ext.ux.UploadDialog.FileRecord.STATE_FINISHED);
      data.record.set(
        'note', data.response.message || data.response.error || this.i18n.note_upload_success
      );
    }
    else {
      data.record.set('state', Ext.ux.UploadDialog.FileRecord.STATE_FAILED);
      data.record.set(
        'note', data.response.message || data.response.error || this.i18n.note_upload_error
      );
    }
    
    data.record.commit();
  },
  
  fireUploadSuccessEvent : function(data)
  {
    this.fireEvent('uploadsuccess', this, data.record.get('filename'), data.response);
  },
  
  fireUploadErrorEvent : function(data)
  {
    this.fireEvent('uploaderror', this, data.record.get('filename'), data.response);
  },
  
  fireUploadFailedEvent : function(data)
  {
    this.fireEvent('uploadfailed', this, data.record.get('filename'));
  },
  
  fireUploadCompleteEvent : function()
  {
    this.fireEvent('uploadcomplete', this);
  },
  
  findUploadFrame : function() 
  {
    this.upload_frame = Ext.getBody().child('iframe.x-hidden:last');
  },
  
  resetUploadFrame : function()
  {
    this.upload_frame = null;
  },
  
  removeUploadFrame : function()
  {
    if (this.upload_frame) {
      this.upload_frame.removeAllListeners();
      this.upload_frame.dom.src = 'about:blank';
      this.upload_frame.remove();
    }
    this.upload_frame = null;
  },
  
  abortUpload : function()
  {
    this.removeUploadFrame();
    
    var store = this.grid_panel.getStore();
    var record = null;
    store.each(function(r) {
      if (r.get('state') == Ext.ux.UploadDialog.FileRecord.STATE_PROCESSING) {
        record = r;
        return false;
      }
    });
    
    record.set('state', Ext.ux.UploadDialog.FileRecord.STATE_FAILED);
    record.set('note', this.i18n.note_aborted);
    record.commit();
  },
  
  fireUploadStopEvent : function()
  {
    this.fireEvent('uploadstop', this);
  },
  
  repostHide : function()
  {
    this.fsa.postEvent('hide');
  },
  
  flushEventQueue : function()
  {
    this.fsa.flushEventQueue();
  },
  
  /**
   * @access private
   */
  // -------------------------------------------------------------------------------------------- //
  onWindowRender : function()
  {
    this.fsa.postEvent('window-render');
  },
  
  onWindowBeforeHide : function()
  {
    return this.isUploading() ? this.getAllowCloseOnUpload() : true;
  },
  
  onWindowHide : function()
  {
    this.fsa.postEvent('hide');
  },
  
  onWindowDestroy : function()
  {
    this.fsa.postEvent('destroy');
  },
  
  onGridRender : function()
  {
    this.fsa.postEvent('grid-render');
  },
  
  onGridSelectionChange : function()
  {
    this.fsa.postEvent('grid-selection-change');
  },
  
  onAddButtonFileSelected : function(btn)
  {
    this.fsa.postEvent('file-selected', btn);
  },
  
  onUploadButtonClick : function()
  {
    if (this.is_uploading) {
      this.fsa.postEvent('stop-upload');
    }
    else {
      this.fsa.postEvent('start-upload');
    }
  },
  
  onRemoveButtonClick : function()
  {
    var selections = this.grid_panel.getSelectionModel().getSelections();
    this.fsa.postEvent('remove-files', selections);
  },
  
  onResetButtonClick : function()
  {
    this.fsa.postEvent('reset-queue');
  },
  
  onCloseButtonClick : function()
  {
    this[this.closeAction].call(this);
    if(this.Make_Reload == true){
        document.location.reload();
   }
  },
  
  onAjaxSuccess : function(response, options) {
    var json_response = {
      'success' : false,
      'error' : this.i18n.note_upload_error
    }
    try { 
        var rt = response.responseText;
        var filter = rt.match(/^<pre>((?:.|\n)*)<\/pre>$/i);
        if (filter) {
            rt = filter[1];
        }
        json_response = Ext.util.JSON.decode(rt); 
    } 
    catch (e) {}
    
    var data = {
      record: options.record,
      response: json_response
    }
    
    if ('success' in json_response && json_response.success) {
      this.fsa.postEvent('file-upload-success', data);
    }
    else {
      this.fsa.postEvent('file-upload-error', data);
    }
  },
  
  onAjaxFailure : function(response, options)
  {
    var data = {
      record : options.record,
      response : {
        'success' : false,
        'error' : this.i18n.note_upload_failed
      }
    }

    this.fsa.postEvent('file-upload-failed', data);
  },
  
  /**
   * @access public
   */
  // -------------------------------------------------------------------------------------------- //
  startUpload : function()
  {
    this.fsa.postEvent('start-upload');
  },
  
  stopUpload : function()
  {
    this.fsa.postEvent('stop-upload');
  },
  
  getUrl : function()
  {
    return this.url;
  },
  
  setUrl : function(url)
  {
    this.url = url;
  },
  
  getBaseParams : function()
  {
    return this.base_params;
  },
  
  setBaseParams : function(params)
  {
    this.base_params = params;
  },
  
  getUploadAutostart : function()
  {
    return this.upload_autostart;
  },
  
  setUploadAutostart : function(value)
  {
    this.upload_autostart = value;
  },
  
  ///////////EIGENE ERWEITERUNG RELOAD EXT//////////////////////
  
  getMakeReload : function()
  {
    return this.Make_Reload;
  },
  
  setMakeReload : function(value)
  {
    this.Make_Reload = value;
  }
  
  ///////////EIGENE ERWEITERUNG RELOAD EXT//////////////////////
   
  ,getAllowCloseOnUpload : function() {
    return this.allow_close_on_upload;
  }
  
  ,setAllowCloseOnUpload : function(value) {
    this.allow_close_on_upload;
  }
  
  ,getResetOnHide : function() {
    return this.reset_on_hide;
  }
  
  ,setResetOnHide : function(value) {
    this.reset_on_hide = value;
  }
  
  ,getPermittedExtensions : function() {
    return this.permitted_extensions;
  }
  
  ,setPermittedExtensions : function(value) {
    this.permitted_extensions = value;
  }
  
  ,isUploading : function() {
    return this.is_uploading;
  }
  
  ,isNotEmptyQueue : function() {
    return this.grid_panel.getStore().getCount() > 0;
  }
  
  ,getQueuedCount : function(count_processing) {
    var count = 0;
    var store = this.grid_panel.getStore();
    store.each(function(r) {
      if (r.get('state') == Ext.ux.UploadDialog.FileRecord.STATE_QUEUE) {
        count++;
      }
      if (count_processing && r.get('state') == Ext.ux.UploadDialog.FileRecord.STATE_PROCESSING) {
        count++;
      }
    });
    return count;
  }
  
  ,hasUnuploadedFiles : function() {
    return this.getQueuedCount() > 0;
  }
});

// ---------------------------------------------------------------------------------------------- //

var p = Ext.ux.UploadDialog.Dialog.prototype;
p.i18n = {
  title: _('upload_files')
  ,state_col_title: _('upf_state')
  ,state_col_width: 70
  ,filename_col_title: _('upf_filename')
  ,filename_col_width: 230
  ,note_col_title: _('upf_note')
  ,note_col_width: 150
  ,add_btn_text: _('upf_add')
  ,add_btn_tip: _('upf_add_desc')
  ,remove_btn_text: _('upf_remove')
  ,remove_btn_tip: _('upf_remove_desc')
  ,reset_btn_text: _('upf_reset')
  ,reset_btn_tip: _('upf_reset_desc')
  ,upload_btn_start_text: _('upf_upload')
  ,upload_btn_start_tip: _('upf_upload_desc')
  ,upload_btn_stop_text: _('upf_abort')
  ,upload_btn_stop_tip: _('upf_abort_desc')
  ,close_btn_text: _('upf_close')
  ,close_btn_tip: _('upf_close_desc')
  ,progress_waiting_text: _('upf_progress_wait')
  ,progress_uploading_text: _('upf_uploading_desc')
  ,error_msgbox_title: _('upf_error')
  ,permitted_extensions_join_str: ','
  ,err_file_type_not_permitted: _('upf_err_filetype')
  ,note_queued_to_upload: _('upf_queued')
  ,note_processing: _('upf_uploading')
  ,note_upload_failed: _('upf_err_failed')
  ,note_upload_success: _('upf_success')
  ,note_upload_error: _('upf_upload_err')
  ,note_aborted: _('upf_aborted')
};
