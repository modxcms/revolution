/* Fix ExtJS 3.4 issue with new timezones */
Ext.override(Ext.form.TimeField, {
    initDate: '2/1/2008'
});

Ext.ns('Ext.ux.form');

/**
 * Creates new DateTime
 * @constructor
 * @param {Object} config A config object
 */
Ext.ux.form.DateTime = Ext.extend(Ext.form.Field, {
    /**
     * @cfg {Function} dateValidator A custom validation function to be called during date field
     * validation (defaults to null)
     */
    dateValidator:null
    /**
     * @cfg {String/Object} defaultAutoCreate DomHelper element spec
     * Let superclass to create hidden field instead of textbox. Hidden will be submittend to server
     */
    ,defaultAutoCreate:{tag:'input', type:'hidden'}
    /**
     * @cfg {String} dtSeparator Date - Time separator. Used to split date and time (defaults to ' ' (space))
     */
    ,dtSeparator:' '
    /**
     * @cfg {String} hiddenFormat Format of datetime used to store value in hidden field
     * and submitted to server (defaults to 'Y-m-d H:i:s' that is mysql format)
     */
    ,hiddenFormat:'Y-m-d H:i:s'
    /**
     * @cfg {Boolean} otherToNow Set other field to now() if not explicly filled in (defaults to true)
     */
    ,otherToNow:true
    /**
     * @cfg {Boolean} emptyToNow Set field value to now on attempt to set empty value.
     * If it is true then setValue() sets value of field to current date and time (defaults to false)
     */
    /**
     * @cfg {String} timePosition Where the time field should be rendered. 'right' is suitable for forms
     * and 'below' is suitable if the field is used as the grid editor (defaults to 'right')
     */
    ,timePosition:'right' // valid values:'below', 'right'
    /**
     * @cfg {Function} timeValidator A custom validation function to be called during time field
     * validation (defaults to null)
     */
    ,timeValidator:null
    /**
     * @cfg {Number} timeWidth Width of time field in pixels (defaults to 100)
     */
    ,timeWidth:100
    /**
     * @cfg {String} dateFormat Format of DateField. Can be localized. (defaults to 'm/y/d')
     */
    ,dateFormat:'m/d/y'
    /**
     * @cfg {String} timeFormat Format of TimeField. Can be localized. (defaults to 'g:i A')
     */
    ,timeFormat:'g:i A'
    /**
     * @cfg {Object} dateConfig Config for DateField constructor.
     */
    /**
     * @cfg {Object} timeConfig Config for TimeField constructor.
     */
    ,maxDateValue: ''
    ,minDateValue: ''
    ,timeIncrement: 15
    ,maxTimeValue: null
    ,minTimeValue: null
    ,disabledDates: null
    ,hideTime: false


    /**
     * @private
     * creates DateField and TimeField and installs the necessary event handlers
     */
    ,initComponent:function() {
        // call parent initComponent
        Ext.ux.form.DateTime.superclass.initComponent.call(this);

        // offset time
        if (!this.hasOwnProperty('offset_time') || isNaN(this.offset_time)) {
            this.offset_time = 0;
        }

        // create DateField
        var dateConfig = Ext.apply({}, {
            id:this.id + '-date'
            ,format:this.dateFormat || Ext.form.DateField.prototype.format
            ,width:this.timeWidth
            ,selectOnFocus:this.selectOnFocus
            ,validator:this.dateValidator
            ,disabledDates: this.disabledDates || null
            ,disabledDays: this.disabledDays || []
            ,showToday: this.showToday || true
            ,maxValue: this.maxDateValue || ''
            ,minValue: this.minDateValue || ''
            ,startDay: this.startDay || 0
            ,allowBlank: this.allowBlank
            ,msgTarget: this.msgTarget
            ,listeners:{
                blur:{scope:this, fn:this.onBlur}
                ,focus:{scope:this, fn:this.onFocus}
            }
        }, this.dateConfig);
        this.df = new Ext.form.DateField(dateConfig);
        this.df.ownerCt = this;
        delete(this.dateFormat);
        delete(this.disabledDates);
        delete(this.disabledDays);
        delete(this.maxDateValue);
        delete(this.minDateValue);
        delete(this.startDay);

        // create TimeField
        var timeConfig = Ext.apply({}, {
            id:this.id + '-time'
            ,format:this.timeFormat || Ext.form.TimeField.prototype.format
            ,width:this.timeWidth
            ,selectOnFocus:this.selectOnFocus
            ,validator:this.timeValidator
            ,increment: this.timeIncrement || 15
            ,maxValue: this.maxTimeValue || null
            ,minValue: this.minTimeValue || null
            ,hidden: this.hideTime
            ,allowBlank: this.allowBlank
            ,msgTarget: this.msgTarget
            ,listeners:{
                blur:{scope:this, fn:this.onBlur}
                ,focus:{scope:this, fn:this.onFocus}
            }
        }, this.timeConfig);
        this.tf = new Ext.form.TimeField(timeConfig);
        this.tf.ownerCt = this;
        delete(this.timeFormat);
        delete(this.maxTimeValue);
        delete(this.minTimeValue);
        delete(this.timeIncrement);

        // relay events
        this.relayEvents(this.df, ['focus', 'specialkey', 'invalid', 'valid']);
        this.relayEvents(this.tf, ['focus', 'specialkey', 'invalid', 'valid']);

        this.on('specialkey', this.onSpecialKey, this);

    }
    /**
     * @private
     * Renders underlying DateField and TimeField and provides a workaround for side error icon bug
     */
    ,onRender:function(ct, position) {
        // don't run more than once
        if(this.isRendered) {
            return;
        }

        // render underlying hidden field
        Ext.ux.form.DateTime.superclass.onRender.call(this, ct, position);

        // render DateField and TimeField
        // create bounding table
        var t;
        if('below' === this.timePosition || 'bellow' === this.timePosition) {
            t = Ext.DomHelper.append(ct, {tag:'table',style:'border-collapse:collapse',children:[
                {tag:'tr',children:[{tag:'td', style:'padding-bottom:1px', cls:'ux-datetime-date'}]}
                ,{tag:'tr',children:[{tag:'td', cls:'ux-datetime-time'}]}
            ]}, true);
        }
        else {
            t = Ext.DomHelper.append(ct, {tag:'table',style:'border-collapse:collapse',children:[
                {tag:'tr',children:[
                    {tag:'td',style:'padding-right:4px', cls:'ux-datetime-date'},{tag:'td', cls:'ux-datetime-time'}
                ]}
            ]}, true);
        }

        this.tableEl = t;
        this.wrap = t.wrap({cls:'x-form-field-wrap x-datetime-wrap'});
        this.wrap.on("mousedown", this.onMouseDown, this, {delay:10});

        // render DateField & TimeField
        this.df.render(t.child('td.ux-datetime-date'));
        this.tf.render(t.child('td.ux-datetime-time'));

        this.df.el.swallowEvent(['keydown', 'keypress']);
        this.tf.el.swallowEvent(['keydown', 'keypress']);

        switch (this.msgTarget) {
            // create icon for side invalid errorIcon
            /*
                Note: This, intentionally or not, creates a single icon node
                positioned at the end of the entire datetime element. Without this
                case block, the default behaviour inserts two nodes (one at the end of
                both the date and time elements).
            */
            case 'side':
                const elp = this.el.findParent('.x-form-element', 10, true);
                if (elp) {
                    this.errorIcon = elp.createChild({cls: 'x-form-invalid-icon'});
                }
                const o = {
                    errorIcon: this.errorIcon,
                    msgTarget: 'side',
                    alignErrorIcon: this.alignErrorIcon.createDelegate(this)
                };
                Ext.apply(this.df, o);
                Ext.apply(this.tf, o);
                break;

            // create custom message targets for date and time fields
            case 'under':
                const dateMsgElId = `ux-datetime-date-msg-${this.itemId}`,
                      dateMsgWidth = Math.ceil(this.dateWidth - 30),
                      dateMsgEl = Ext.DomHelper.append(this.df.container, {
                          tag: 'div',
                          cls: 'x-form-invalid-msg',
                          style: `display: none; width: ${dateMsgWidth}px;`,
                          id: dateMsgElId
                      }),
                      timeMsgElId = `ux-datetime-time-msg-${this.itemId}`,
                      timeMsgWidth = Math.ceil(this.timeWidth - 30),
                      timeMsgEl = Ext.DomHelper.append(this.tf.container, {
                          tag: 'div',
                          cls: 'x-form-invalid-msg',
                          style: `display: none; width: ${timeMsgWidth}px;`,
                          id: timeMsgElId
                      })
                ;
                this.df.container.appendChild(dateMsgEl);
                this.tf.container.appendChild(timeMsgEl);
                this.df.msgTarget = dateMsgElId;
                this.tf.msgTarget = timeMsgElId;
                break;
            // no default
        }

        // setup name for submit
        this.el.dom.name = this.hiddenName || this.name || this.id;

        // prevent helper fields from being submitted
        this.df.el.dom.removeAttribute("name");
        this.tf.el.dom.removeAttribute("name");

        // we're rendered flag
        this.isRendered = true;

        // update hidden field
        this.updateHidden();

    }
    /**
     * @private
     */
    ,adjustSize:Ext.BoxComponent.prototype.adjustSize
    /**
     * @private
     */
    ,alignErrorIcon:function() {
        this.errorIcon.alignTo(this.tableEl, 'tl-tr', [2, 0]);
    }
    /**
     * @private initializes internal dateValue
     */
    ,initDateValue:function() {
        this.dateValue = this.otherToNow ? new Date() : new Date(1970, 0, 1, 0, 0, 0);
    }
    /**
     * Calls clearInvalid on the DateField and TimeField
     */
    ,clearInvalid:function(){
        this.df.clearInvalid();
        this.tf.clearInvalid();
    }
    /**
     * Calls markInvalid on both DateField and TimeField
     * @param {String} msg Invalid message to display
     */
    ,markInvalid:function(msg){
        this.df.markInvalid(msg);
        this.tf.markInvalid(msg);
    }
    /**
     * @private
     * called from Component::destroy.
     * Destroys all elements and removes all listeners we've created.
     */
    ,beforeDestroy:function() {
        if(this.isRendered) {
            this.wrap.removeAllListeners();
            this.wrap.remove();
            this.tableEl.remove();
            this.df.destroy();
            this.tf.destroy();
        }
    }
    /**
     * Disable this component.
     * @return {Ext.Component} this
     */
    ,disable:function() {
        if(this.isRendered) {
            this.df.disabled = this.disabled;
            this.df.onDisable();
            this.tf.onDisable();
        }
        this.disabled = true;
        this.df.disabled = true;
        this.tf.disabled = true;
        this.fireEvent("disable", this);
        return this;
    }
    /**
     * Enable this component.
     * @return {Ext.Component} this
     */
    ,enable:function() {
        if(this.rendered){
            this.df.onEnable();
            this.tf.onEnable();
        }
        this.disabled = false;
        this.df.disabled = false;
        this.tf.disabled = false;
        this.fireEvent("enable", this);
        return this;
    }
    /**
     * @private Focus date filed
     */
    ,focus:function() {
        this.df.focus();
    }
    /**
     * @private
     */
    ,getPositionEl:function() {
        return this.wrap;
    }
    /**
     * @private
     */
    ,getResizeEl:function() {
        return this.wrap;
    }
    /**
     * @return {Date/String} Returns value of this field
     */
    ,getValue:function() {
        // create new instance of date
        return this.dateValue ? new Date(this.dateValue) : '';
    }
    /**
     * @return {Boolean} true = valid, false = invalid
     * @private Calls isValid methods of underlying DateField and TimeField and returns the result
     */
    ,isValid:function() {
        return this.df.isValid() && this.tf.isValid();
    }
    /**
     * Returns true if this component is visible
     * @return {boolean}
     */
    ,isVisible : function(){
        return this.df.rendered && this.df.getActionEl().isVisible();
    }
    /**
     * @private Handles blur event
     */
    ,onBlur:function(f) {
        // called by both DateField and TimeField blur events

        // revert focus to previous field if clicked in between
        if(this.wrapClick) {
            f.focus();
            this.wrapClick = false;
        }

        // update underlying value
        if (f === this.df) {
            this.updateDate();
        } else {
            this.updateTime();
        }
        this.updateHidden();

        this.validate();

        // fire events later
        (function() {
            if(!this.df.hasFocus && !this.tf.hasFocus) {
                var v = this.getValue();
                if(String(v) !== String(this.startValue)) {
                    this.fireEvent("change", this, v, this.startValue);
                }
                this.hasFocus = false;
                this.fireEvent('blur', this);
            }
        }).defer(100, this);

    }
    /**
     * @private Handles focus event
     */
    ,onFocus:function() {
        if(!this.hasFocus){
            this.hasFocus = true;
            this.startValue = this.getValue();
            this.fireEvent("focus", this);
        }
    }
    /**
     * @private Just to prevent blur event when clicked in the middle of fields
     */
    ,onMouseDown:function(e) {
        if(!this.disabled) {
            this.wrapClick = 'td' === e.target.nodeName.toLowerCase();
        }
    }
    /**
     * @private
     * Handles Tab and Shift-Tab events
     */
    ,onSpecialKey:function(t, e) {
        var key = e.getKey();
        if(key === e.TAB) {
            if(t === this.df && !e.shiftKey) {
                this.onBlur(t);
                e.stopEvent();
                this.tf.focus();
            }
            if(t === this.tf && e.shiftKey) {
                this.onBlur(t);
                e.stopEvent();
                this.df.focus();
            }
            this.updateValue();
        }
        // otherwise it misbehaves in editor grid
        if(key === e.ENTER) {
            this.updateValue();
        }

    }
    /**
     * Resets the current field value to the originally loaded value
     * and clears any validation messages. See Ext.form.BasicForm.trackResetOnLoad
     */
    ,reset:function() {
        this.df.setValue(this.originalValue);
        this.tf.setValue(this.originalValue);
    }
    /**
     * @private Sets the value of DateField
     */
    ,setDate:function(date) {
        if (date && this.offset_time != 0) {
            date = date.add(Date.MINUTE, 60 * new Number(this.offset_time));
        }
        this.df.setValue(date);
    }
    /**
     * @private Sets the value of TimeField
     */
    ,setTime:function(date) {
        if (date && this.offset_time != 0) {
            date = date.add(Date.MINUTE, 60 * new Number(this.offset_time));
        }
        this.tf.setValue(date);
    }
    /**
     * @private
     * Sets correct sizes of underlying DateField and TimeField
     * With workarounds for IE bugs
     */
    ,setSize:function(w, h) {
        if(!w) {
            return;
        }
        if('below' === this.timePosition) {
            this.df.setSize(w, h);
            this.tf.setSize(w, h);
            if(Ext.isIE) {
                this.df.el.up('td').setWidth(w);
                this.tf.el.up('td').setWidth(w);
            }
        }
        else {
            this.df.setSize(w - this.timeWidth - 4, h);
            this.tf.setSize(this.timeWidth, h);

            if(Ext.isIE) {
                this.df.el.up('td').setWidth(w - this.timeWidth - 4);
                this.tf.el.up('td').setWidth(this.timeWidth);
            }
        }
    }
    /**
     * @param {Mixed} val Value to set
     * Sets the value of this field
     */
    ,setValue:function(val) {
        if(!val && true === this.emptyToNow) {
            this.setValue(new Date());
            return;
        }
        else if(!val) {
            this.setDate('');
            this.setTime('');
            this.updateValue();
            return;
        }
        if ('number' === typeof val) {
            val = new Date(val);
        }
        else if('string' === typeof val && this.hiddenFormat) {
            val = Date.parseDate(val, this.hiddenFormat);
        }
        val = val ? val : new Date(1970, 0 ,1, 0, 0, 0);
        var da;
        if(val instanceof Date) {
            this.setDate(val);
            this.setTime(val);
            this.dateValue = new Date(Ext.isIE ? val.getTime() : val);
        } else {
            da = val.split(this.dtSeparator);
            this.setDate(da[0]);
            if(da[1]) {
                if(da[2]) {
                    // add am/pm part back to time
                    da[1] += da[2];
                }
                this.setTime(da[1]);
            }
        }
        this.updateValue();
    }
    /**
     * Hide or show this component by boolean
     * @return {Ext.Component} this
     */
    ,setVisible: function(visible){
        if(visible) {
            this.df.show();
            this.tf.show();
        }else{
            this.df.hide();
            this.tf.hide();
        }
        return this;
    }
    ,show:function() {
        return this.setVisible(true);
    }
    ,hide:function() {
        return this.setVisible(false);
    }
    /**
     * @private Updates the date part
     */
    ,updateDate:function() {

        var d = this.df.getValue();
        if(d) {
            if(!(this.dateValue instanceof Date)) {
                this.initDateValue();
                if(!this.tf.getValue()) {
                    this.setTime(this.dateValue);
                }
            }
            this.dateValue.setMonth(0); // because of leap years
            this.dateValue.setFullYear(d.getFullYear());
            this.dateValue.setMonth(d.getMonth(), d.getDate());
        }
        else {
            this.dateValue = '';
            this.setTime('');
        }
    }
    /**
     * @private
     * Updates the time part
     */
    ,updateTime: function() {
        let t = this.tf.getValue();
        if (t && !(t instanceof Date)) {
            t = Date.parseDate(t, this.tf.format);
        }
        if (t && !this.df.getValue()) {
            this.initDateValue();
            this.setDate(this.dateValue);
        }
        if (this.dateValue instanceof Date) {
            if (t && !this.hideTime) {
                this.dateValue.setHours(t.getHours());
                this.dateValue.setMinutes(t.getMinutes());
                this.dateValue.setSeconds(t.getSeconds());
            } else {
                this.dateValue.setHours(0);
                this.dateValue.setMinutes(0);
                this.dateValue.setSeconds(0);
            }
        }
    }
    /**
     * @private Updates the underlying hidden field value
     */
    ,updateHidden:function() {
        if(this.isRendered) {
            var value = '';
            if (this.dateValue instanceof Date) {
                value = this.dateValue.add(Date.MINUTE, 0 - 60 * new Number(this.offset_time)).format(this.hiddenFormat);
            }
            this.el.dom.value = value;
        }
    }
    /**
     * @private Updates all of Date, Time and Hidden
     */
    ,updateValue:function() {
        this.updateDate();
        this.updateTime();
        this.updateHidden();
    }
    /**
     * @return {Boolean} true = valid, false = invalid
     * calls validate methods of DateField and TimeField
     */
    ,validate:function() {
        return this.df.validate() && this.tf.validate();
    }
    /**
     * Returns renderer suitable to render this field
     * @param {Object} Column model config
     */
    ,renderer: function(field) {
        var format = field.editor.dateFormat || Ext.ux.form.DateTime.prototype.dateFormat;
        format += ' ' + (field.editor.timeFormat || Ext.ux.form.DateTime.prototype.timeFormat);

        return function(val) {
            return Ext.util.Format.date(val, format);
        };
    }

});

// register xtype
Ext.reg('xdatetime', Ext.ux.form.DateTime);
