<p>{$_lang['widget_shortcuts_intro']}</p>
<br/>
<div class="table-wrapper">
    <table class="table">
        <thead>
        <tr>
            <th>{$_lang['widget_shortcuts_description']}</th>
            <th>{$_lang['widget_shortcuts_shortcut']}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $shortcuts as $shortcut}
            <tr>
                <td>{$shortcut['description']}</td>
                <td>{$shortcut['shortcut']}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>