[[%onlineusers_message? &curtime=`[[+curtime]]`]]
<br /><br />

<table class="classy" style="width: 100%;">
<thead>
<tr>
    <th>[[%onlineusers_user]]</th>
    <th>[[%onlineusers_userid]]</th>
    <th>[[%onlineusers_lasthit]]</th>
    <th>[[%onlineusers_action]]</th>
</tr>
</thead>
<tbody>
[[+users:is=``:then=`
<tr>
    <th colspan="5">[[%active_users_none]]</th>
</tr>`:else=`[[+users]]`]]
</tbody>
</table>
