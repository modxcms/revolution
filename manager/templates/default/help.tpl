<div class="modx-page-header">
<h2>{$_lang.help}</h2>
</div>
<div class="modx-page">
{literal}<style>
.about li {
    list-style-type: disc;
    margin-left: 1.5em;
}
.about h3 {
    margin-top: 1em;
}
</style>{/literal}

<div id="tabs_div"></div>

<div id="modx-tab-about" class="padding x-hide-display">
    <h2>{$_lang.about_title}</h2>
    <p>{$_lang.about_msg}</p>
</div>

<div id="modx-tab-help" class="padding x-hide-display">
    <h2>{$_lang.help}</h2>
    <p>{$_lang.help_msg}</p>
</div>

<div id="modx-tab-credits" class="padding x-hide-display">

<h2>{$_lang.credits}</h2>
<table width="500" border="0" cellspacing="0" cellpadding="0" class="about">
<tbody> 
<tr>
    <td align="center">
		<a href="http://www.php.net" target="_blank">
			<img src="templates/{$_config.manager_theme}/images/credits/php.gif" alt="php" />
		</a>
	</td>
    <td>{$_lang.credits_php}</td>
  </tr>
  <tr>
    <td align="center">
		<a href="http://www.mysql.com" target="_blank">
			<img src="templates/{$_config.manager_theme}/images/credits/mysql.gif" alt="mysql" />
		</a>
	</td>
    <td>{$_lang.credits_mysql}</td>
  </tr>
  <tr>
    <td></td>
    <td>{$_lang.credits_xpdo}</td>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td valign="top">{$_lang.credits_shouts_title}</td>
	<td>{$_lang.credits_shouts_msg}</td>
  </tr>
</tbody>
</table>
</div>

</div>