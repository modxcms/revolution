(function(){
    var maxFileSize = parseInt(MODx.config['upload_maxsize'], 10);
    var permittedFileTypes = MODx.config['upload_files'].toLowerCase().split(',');

    FileAPI.debug = false;
    FileAPI.staticPath = MODx.config['manager_url'] + 'assets/fileapi/';

    var api = {
        humanFileSize: function(bytes, si) {
            var thresh = si ? 1000 : 1024;
            if(bytes < thresh) return bytes + ' B';
            var units = si ? ['kB','MB','GB','TB','PB','EB','ZB','YB'] : ['KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB'];
            var u = -1;
            do {
                bytes /= thresh;
                ++u;
            } while(bytes >= thresh);
            return bytes.toFixed(1)+' '+units[u];
        },

        getFileExtension: function(filename)
        {
            var result = '';
            var parts = filename.split('.');
            if (parts.length > 1) {
                result = parts.pop();
            }
            return result;
        },

        isFileSizePermitted: function(size){
            return (size <= maxFileSize);
        },

        formatBytes: function(size, unit){
            unit = unit || FileAPI.MB;
            return Math.round(((size / unit) + 0.00001) * 100) / 100;
        }
    };

    Ext.namespace('MODx.util.MultiUploadDialog');

    /**
     * File upload browse button.
     *
     * @class MODx.util.MultiUploadDialog.BrowseButton
     */
    MODx.util.MultiUploadDialog.BrowseButton = Ext.extend(Ext.Button,{
        input_name : 'file',
        input_file : null,
        original_handler : null,
        original_scope : null,

        /**
        * @access private
        */
        initComponent : function()
        {
            MODx.util.MultiUploadDialog.BrowseButton.superclass.initComponent.call(this);
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
            MODx.util.MultiUploadDialog.BrowseButton.superclass.onRender.call(this, ct, position);
            this.createInputFile();
        },

        /**
        * @access private
        */
        createInputFile : function()
        {
            var button_container = this.el.child('button').wrap();

            // button_container.position('relative');
            this.input_file = button_container.createChild({
                tag: 'input',
                type: 'file',
                size: 1,
                name: this.input_name || Ext.id(this.el),
                style: "cursor: pointer; display: inline-block; opacity: 0; position: absolute; top: 0; left: 0; width: 100%; height: 100%;",
                multiple: true
            });
            // this can all be done via the inline css above
            // doing it like this prevents a too big hidden file field creating weird hover behavoir on the buttons
            // this.input_file.setOpacity(0.0);

            // var button_box = this.el.getBox();
            // this.input_file.setStyle('font-size', (button_box.height * 1) + 'px');

            // var adj = {x: -3, y: -3};

            // this.input_file.setLeft(adj.x + 'px');
            // this.input_file.setTop(adj.y + 'px');
            // this.input_file.setOpacity(0.0);

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

            if (typeof this.tooltip == 'object') {
                Ext.QuickTips.unregister(this.input_file);
            }
            else {
                this.input_file.dom[this.tooltipType] = null;
            }

            this.input_file.removeAllListeners();
            this.input_file = null;

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
            MODx.util.MultiUploadDialog.BrowseButton.superclass.disable.call(this);
            this.input_file.dom.disabled = true;
        },

        /**
        * @access public
        */
        enable : function()
        {
            MODx.util.MultiUploadDialog.BrowseButton.superclass.enable.call(this);
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

            MODx.util.MultiUploadDialog.BrowseButton.superclass.destroy.call(this);
        },

        /**
        * @access public
        */
        reset : function()
        {
            var form = new Ext.Element(document.createElement('form'));
            var buttonParent = this.input_file.parent();
            form.appendChild(this.input_file);
            form.dom.reset();
            buttonParent.appendChild(this.input_file);
        },

        /**
        * @access private
        */
        onInputFileChange : function(ev)
        {
            if (this.original_handler) {
                this.original_handler.call(this.original_scope, this, ev);
            }
            this.fireEvent('click', this, ev);
        }
    });
    Ext.reg('multiupload-browse-btn', MODx.util.MultiUploadDialog.BrowseButton);


    MODx.util.MultiUploadDialog.FilesGrid = function(config) {
        config = config || {};
        Ext.applyIf(config,{
            height: 300
            ,autoScroll: true
            ,border: false
            ,fields: ['name', 'size', 'file', 'permitted', 'message', 'uploaded']
            ,paging: false
            ,remoteSort: false
            ,viewConfig: {
                forceFit: true
                ,getRowClass: function(record, index, rowParams){
                    if(!record.get('permitted')){
                        return 'upload-error';
                    }
                    else if(record.get('uploaded')){
                        return 'upload-success';
                    }
                }
            }
            ,sortInfo: {
                field: 'name'
                ,direction: 'ASC'
            }
            ,deferRowRender: true
            ,anchor: '100%'
            ,autoExpandColumn: 'state'
            ,columns: [{
                header: _('upload.columns.file')
                ,dataIndex: 'name'
                ,sortable: true
                ,width: 200
                ,renderer: function(value, meta, record){
                    var id = Ext.id();

                    FileAPI.Image(record.get('file'))
                        .resize(100, 50, 'max')
                        .get(function (err, img){
                            if(!err){
                                img = new Ext.Element(img).addClass('upload-thumb');
                                Ext.get(id).insertFirst(img);
                            }
                        });

                    return '<div id="' + id + '"><p>' + value + '</p></div>';
                }
            }
            ,{
                header: _('upload.columns.state')
                ,id: 'state'
                ,width: 100
                ,renderer: function(value, meta, record) {
                    if(!record.get('permitted') || record.get('uploaded')){
                        return '<p class="upload-status-text">' + record.get('message') + '</p>';
                    }
                    else{
                        var id = Ext.id();
                        (function() {
                            record.progressbar = new Ext.ProgressBar({
                                renderTo: id
                                ,value: 0
                                ,text : '0 / ' + record.get('size')
                            });
                        }).defer(25);
                        return '<div id="' + id + '"></div>';
                    }
                }
            }]
            ,getMenu: function() {
                return [{
                    text: _('upload.contextmenu.remove_entry')
                    ,handler: this.removeFile
                }];
            }
        });
        MODx.util.MultiUploadDialog.FilesGrid.superclass.constructor.call(this,config);
    };


    Ext.extend(MODx.util.MultiUploadDialog.FilesGrid,MODx.grid.LocalGrid,{
        removeFile: function() {
            var selected = this.getSelectionModel().getSelections();
            this.getStore().remove(selected);
        }
    });
    Ext.reg('multiupload-grid-files',MODx.util.MultiUploadDialog.FilesGrid);



    MODx.util.MultiUploadDialog.Dialog = function(config) {
        this.filesGridId = Ext.id();

        config = config || {};
        Ext.applyIf(config,{
            permitted_extensions: permittedFileTypes
            ,autoHeight: true
            ,width: 600
            ,closeAction: 'hide'
            ,layout: 'anchor'
            ,listeners: {
                'show': {fn: this.onShow}
                ,'hide': {fn: this.onHide}
            }
            ,items: [{
                    xtype: 'multiupload-grid-files'
                    ,id: this.filesGridId
                    ,anchor: '100%'
                }
            ]
            ,buttons: [
                {
                    xtype: 'multiupload-browse-btn'
                    ,text: _('upload.buttons.choose')
                    ,cls: 'primary-button'
                    ,listeners: {
                        'click': {
                            scope: this,
                            fn: function(btn, ev){
                                var files = FileAPI.getFiles(ev);
                                this.addFiles(files);
                                btn.reset();
                            }
                        }
                    }
                }
                ,{
                    xtype: 'splitbutton'
                    ,text: _('upload.buttons.clear')
                    ,listeners: {
                        'click': {
                            scope: this,
                            fn: this.clearStore
                        }
                    }
                    ,menu: new Ext.menu.Menu({
                        items: [
                            {
                                text: _('upload.clear_list.all')
                                ,listeners: {
                                    'click': {
                                        scope: this,
                                        fn: this.clearStore
                                    }
                                }
                            }
                            ,{
                                text: _('upload.clear_list.notpermitted')
                                ,listeners: {
                                    'click': {
                                        scope: this,
                                        fn: this.clearNotPermittedItems
                                    }
                                }
                            }
                        ]
                    })
                }
                ,{
                    xtype: 'button'
                    ,text: _('upload.buttons.upload')
                    ,cls: 'primary-button'
                    ,listeners: {
                        'click': {
                            scope: this
                            ,fn: this.startUpload
                        }
                    }
                }
                ,{
                    xtype: 'button'
                    ,text: _('upload.buttons.close')
                    ,listeners: {
                        'click': {
                            scope: this,
                            fn: this.hideDialog
                        }
                    }
                }

            ]
        });
        MODx.util.MultiUploadDialog.Dialog.superclass.constructor.call(this,config);
    };

    var originalWindowOnShow = Ext.Window.prototype.onShow;
    var originalWindowOnHide = Ext.Window.prototype.onHide;

    Ext.extend(MODx.util.MultiUploadDialog.Dialog, Ext.Window, {
        addFiles: function(files){
            var store = Ext.getCmp(this.filesGridId).getStore();
            var dialog = this;
            FileAPI.each(files, function(file){
                var permitted = true;
                var message = '';

                if(!api.isFileSizePermitted(file.size)){
                    message = _('upload.notpermitted.filesize', {
                        'size': api.humanFileSize(file.size),
                        'max':  api.humanFileSize(maxFileSize)
                    });
                    permitted = false;
                }

                if(!dialog.isFileTypePermitted(file.name)){
                    message = _('upload.notpermitted.extension', {
                        'ext': api.getFileExtension(file.name)
                    });
                    permitted = false;
                }

                var data = {
                    'name': file.name,
                    'size': api.humanFileSize(file.size),
                    'file': file,
                    'permitted': permitted,
                    'message': message,
                    'uploaded': false
                };

                var p = new store.recordType(data);
                store.insert(0, p);
            });
        },

        startUpload: function(){
            var dialog = this;
            var files = [];
            var params = Ext.apply(this.base_params, {
                'HTTP_MODAUTH': MODx.siteId
            });
            var store = Ext.getCmp(this.filesGridId).getStore();

            store.each(function(){
                var file = this.get('file');
                if(this.get('permitted') && !this.get('uploaded')){
                    file.record = this;
                    files.push(file);
                }
            });


            var xhr = FileAPI.upload({
                url: this.url
                ,data: params
                ,files: { file: files }
                ,fileprogress: function(evt, file){
                    file.record.progressbar.updateProgress(
                        evt.loaded/evt.total,
                        _('upload.upload_progress', {
                            'loaded': api.humanFileSize(evt.loaded),
                            'total':  file.record.get('size')
                        }),
                        true);
                }
                ,filecomplete: function (err, xhr, file, options){
                    if( !err ){
                        var resp = Ext.util.JSON.decode(xhr.response);
                        if(resp.success){
                            file.record.set('uploaded', true);
                            file.record.set('message', _('upload.upload.success'))
                        }
                        else{
                            file.record.set('permitted', false);
                            file.record.set('message', resp.message);
                        }
                    }
                    else{
                        if(xhr.status !== 401){
                            MODx.msg.alert(_('upload.msg.title.error'), err);
                        }
                    }
                }
                ,complete: function(err, xhr){
                    dialog.fireEvent('uploadsuccess');
                }
            });
        },

        removeEntry: function(record){
            Ext.getCmp(this.filesGridId).getStore().remove(record);
        },

        clearStore: function(){
            Ext.getCmp(this.filesGridId).getStore().removeAll();
        },

        clearNotPermittedItems: function(){
            var store = Ext.getCmp(this.filesGridId).getStore();
            var notPermitted = store.query('permitted', false);
            store.remove(notPermitted.getRange());
        },

        hideDialog: function(){
            this.hide();
        },

        onDDrag: function(ev){
            ev && ev.preventDefault();
        },

        onDDrop: function(ev){
            ev && ev.preventDefault();

            var dialog = this;

            FileAPI.getDropFiles(ev.browserEvent, function(files){
                if( files.length ){
                    dialog.addFiles(files);
                }
            });
        },

        onShow: function(){
            // make sure the window is shown with the reworked windows
            var ret = originalWindowOnShow.apply(this,arguments);

            var store = Ext.getCmp(this.filesGridId).getStore();
            store.removeAll();

            if(!this.isDDSet){
                this.el.on('dragenter', this.onDDrag, this);
                this.el.on('dragover', this.onDDrag, this);
                this.el.on('dragleave', this.onDDrag, this);
                this.el.on('drop', this.onDDrop, this);

                this.isDDSet = true;
            }

            return ret;
        },

        onHide: function(){
            // make sure the window is hidden with the reworked windows
            var ret = originalWindowOnHide.apply(this,arguments);

            this.el.un('dragenter', this.onDDrag, this);
            this.el.un('dragover', this.onDDrag, this);
            this.el.un('dragleave', this.onDDrag, this);
            this.el.un('drop', this.onDDrop, this);
            this.isDDSet = false;

            return ret;
        },

        setBaseParams: function(params){
            this.base_params = params;
            this.setTitle(_('upload.title.destination_path', {'path': this.base_params['path']}));
        },

        isFileTypePermitted: function(filename){
            var ext = api.getFileExtension(filename);
            return (this.permitted_extensions.indexOf(ext.toLowerCase()) > -1);
        }
    });
    Ext.reg('multiupload-window-dialog', MODx.util.MultiUploadDialog.Dialog);

})();