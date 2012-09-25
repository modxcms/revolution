Ext.onReady(function() {
    if (MODx.config.manager_language == 'en') return false;
    
    Date.dayNames = [
        _('sunday')
        ,_('monday')
        ,_('tuesday')
        ,_('wednesday')
        ,_('thursday')
        ,_('friday')
        ,_('saturday')
    ];
    Date.monthNames = [
        _('january')
        ,_('february')
        ,_('march')
        ,_('april')
        ,_('may')
        ,_('june')
        ,_('july')
        ,_('august')
        ,_('september')
        ,_('october')
        ,_('november')
        ,_('december')
    ];
    Ext.apply(Ext.grid.GridView.prototype, {
        sortAscText: _('ext_sortasc')
        ,sortDescText: _('ext_sortdesc')
        ,lockText: _('ext_column_lock')
        ,unlockText: _('ext_column_unlock')
        ,columnsText: _('ext_columns')
        ,emptyText: _('ext_emptymsg')
    });
    Ext.apply(Ext.DatePicker.prototype, {
        todayText: _('today')
        ,todayTip: _('ext_today_tip')
        ,minText: _('ext_mindate')
        ,maxText: _('ext_maxdate')
        ,monthNames: Date.monthNames
        ,dayNames: Date.dayNames
        ,nextText: _('ext_nextmonth')
        ,prevText: _('ext_prevmonth')
        ,monthYearText: _('ext_choosemonth')
    });
    
    Ext.MessageBox.buttonText = {
        yes: _('yes')
        ,no: _('no')
        ,ok: _('ok')
        ,cancel: _('cancel')
    };
    Ext.apply(Ext.PagingToolbar.prototype,{
        afterPageText: _('ext_afterpage')
        ,beforePageText: _('ext_beforepage')
        ,displayMsg: _('ext_displaying')
        ,emptyMsg: _('ext_emptymsg')
        ,firstText: _('ext_first')
        ,prevText: _('ext_prev')
        ,nextText: _('ext_next')
        ,lastText: _('ext_last')
        ,refreshText: _('ext_refresh')
    });
    Ext.apply(Ext.Updater.prototype,{
        text: _('loading')
    });
    Ext.apply(Ext.LoadMask.prototype,{
        msg : _('loading')
    });
    Ext.apply(Ext.layout.BorderLayout.SplitRegion.prototype,{
        splitTip: _('ext_splittip')
    });
    Ext.apply(Ext.form.BasicForm.prototype,{
        waitTitle: _('please_wait')
    });
    Ext.apply(Ext.form.ComboBox.prototype,{
        loadingText: _('loading')
    });
    Ext.apply(Ext.form.Field.prototype,{
        invalidText: _('ext_invalidfield')
    });    
    Ext.apply(Ext.form.TextField.prototype,{
        minLengthText: _('ext_minlenfield')
        ,maxLengthText: _('ext_maxlenfield')
        ,invalidText: _('ext_invalidfield')
        ,blankText: _('field_required') 
    });
    Ext.apply(Ext.form.NumberField.prototype,{
        minText: _('ext_minvalfield')
        ,maxText: _('ext_maxvalfield')
        ,nanText: _('ext_nanfield')
    });
    Ext.apply(Ext.form.DateField.prototype,{
        disabledDaysText: _('disabled')
        ,disabledDatesText: _('disabled')
        ,minText: _('ext_datemin')
        ,maxText: _('ext_datemax')
        ,invalidText: _('ext_dateinv')
    });
    Ext.apply(Ext.form.VTypes,{
        emailText: _('ext_inv_email')
        ,urlText: _('ext_inv_url')
        ,alphaText: _('ext_inv_alpha')
        ,alphanumText: _('ext_inv_alphanum')
    });
    Ext.apply(Ext.grid.GroupingView.prototype,{
        emptyGroupText: _('ext_emptygroup')
        ,groupByText: _('ext_groupby')
        ,showGroupsText: _('ext_showgroups')
    });
    Ext.apply(Ext.grid.PropertyColumnModel.prototype,{
        nameText: _('name')
        ,valueText: _('value')
    });
    Ext.apply(Ext.form.CheckboxGroup.prototype,{
        blankText: _('ext_checkboxinv')
    });
    Ext.apply(Ext.form.RadioGroup.prototype,{
        blankText: _('ext_checkboxinv')
    });
    Ext.apply(Ext.form.TimeField.prototype, {
        minText: _('ext_timemin')
        ,maxText: _('ext_timemax')
        ,invalidText: _('ext_timeinv')
    });
});