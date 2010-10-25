<div id="modx-panel-welcome-div"></div>

<div id="welcome_tabs"></div>

<div id="modx-news">
    {foreach from=$newsfeed item=article}
    <div class="news_article" style="padding: 15px;">
        <h2><a href="{$article.link}" target="_blank">{$article.title}</a></h2>
        <p>{$article.description}</p>
        <span class="date_stamp">{$article.pubdate|date_format}</span>
    </div>
    {/foreach}
</div>

<div id="modx-security">
    {foreach from=$securefeed item=article}
    <div class="news_article" style="padding: 15px;">
        <h2><a href="{$article.link}" target="_blank">{$article.title}</a></h2>
        <br />{$article.description}
        <span class="date_stamp">{$article.pubdate|date_format}</span>
    </div>
    {/foreach}
</div>

<!-- system check -->
<div id="modx-config">
    {$config_check_results}
</div>

<!-- user info -->
<div id="modx-info">
    {$_lang.yourinfo_message}
    <br /><br />
    <table class="classy" style="width: 100%;">
    <thead>
    <tr>
        <th>{$_lang.yourinfo_username}</th>
        <th>{$_lang.yourinfo_previous_login}</th>
        <th>{$_lang.yourinfo_total_logins}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>{$modx->getLoginUserName()}</td>
        <td>{$previous_login}</td>
        <td>{$smarty.session.mgrLogincount+1}</td>
    </tr>
    </tbody>
    </table>
</div>

<!-- online info -->
<div id="modx-online">
    {$online_users_msg}
    <br /><br />

    <table class="classy" style="width: 100%;">
    <thead>
    <tr>
        <th>{$_lang.onlineusers_user}</th>
        <th>{$_lang.onlineusers_userid}</th>
        <th>{$_lang.onlineusers_ipaddress}</th>
        <th>{$_lang.onlineusers_lasthit}</th>
        <th>{$_lang.onlineusers_action}</th>
    </tr>
    </thead>
    <tbody>
    {foreach from=$ausers item=user name='au'}
    <tr class="{cycle values=',odd'}">
            <td>{$user->username}</td>
            <td>{$user->internalKey}</td>
            <td>{$user->ip}</td>
            <td>{$user->get('lastseen')}</td>
            <td>{$user->get('currentaction')}</td>
    </tr>
    {foreachelse}
    <tr>
        <th colspan="5">{$_lang.active_users_none}</th>
    </tr>
    {/foreach}
    </tbody>
    </table>
</div>