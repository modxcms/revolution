<div class="container" id="help-content-here-people"></div>
<div id="modx-page-help-content">

    <h2>{$_lang.help_page_title}</h2>

    <div id="helpBanner">
        <h3>{$_lang.help_main_head}</h3>

        <p>{$_lang.help_main_subhead}</p>
    </div>

    <div id="managerbuttons">
        <ul>

            <li>
                <a href="{$_lang.forums_link}" target="_blank" title="{$_lang.forums_title}">
                    <span class="icon"><i class="icon icon-comments icon-3x"></i></span>
                    <span class="headline">{$_lang.forums}</span>
                    <span class="subline">{$_lang.forums_description}</span>
                </a>
            </li>

            <li>
                <a href="{$_lang.docs_link}" target="_blank" title="{$_lang.docs_title}">
                    <span class="icon"><i class="icon icon-book icon-3x"></i></span>
                    <span class="headline">{$_lang.docs}</span>
                    <span class="subline">{$_lang.docs_description}</span>
                </a>
            </li>

            <li>
                <a href="{$_lang.bugs_link}" target="_blank" title="{$_lang.bugs_title}">
                    <span class="icon"><i class="icon icon-exclamation-circle icon-3x"></i></span>
                    <span class="headline">{$_lang.bugs}</span>
                    <span class="subline">{$_lang.bugs_description}</span>
                </a>
            </li>

            <li>
                <a href="{$_lang.support_link}" target="_blank" class="supportTicket" title="{$_lang.support_title}">
                    <span class="icon"><i class="icon icon-credit-card icon-3x"></i></span>
                    <span class="headline">{$_lang.support}</span>
                    <span class="subline">{$_lang.support_description}</span>
                </a>
            </li>

        </ul>
    </div>

    <div id="contactus">
        <h3>{$_lang.email_sub} </h3>
        <p>{$_lang.email_sub_description}</p>
        <form id="mcsignup" action="http://modxcms.list-manage.com/subscribe/post" method="post">
            <input type="hidden" name="u" value="08b25a8de68a29fe03a483720"/>
            <input type="hidden" name="id" value="848cf40420"/>

            <input type="hidden" name="MERGE7" value="[[++site_url]] Manager" id="MERGE7">

            <input type="email" placeholder="{$_lang.email_placeholder}" required id="MERGE0" name="MERGE0" value=""
                   class="textbox"/>
            <input type="submit" class="x-btn primary-button" name="Submit" value="{$_lang.email_sub_button}"/>
        </form>

        <p>{$_lang.social_follows}</p>

        <p><a href="https://twitter.com/modx" target="_blank"><i class="icon icon-2x icon-twitter"></i>Twitter: twitter.com/modx</a></p>

        <p><a href="https://www.facebook.com/modxcms" target="_blank"><i class="icon icon-2x icon-facebook"></i>Facebook: www.facebook.com/modxcms </a></p>

        <p><a href="https://www.linkedin.com/groups/MODX-697477" target="_blank"><i class="icon icon-2x icon-linkedin"></i>LinkedIn: google.com/+modx </a></p>

        <p><a href="http://www.pinterest.com/modx/" target="_blank"><i class="icon icon-2x icon-pinterest"></i>Pinterest: pinterest.com/modx </a></p>

        <p><a href="https://plus.google.com/+modx/" target="_blank"><i class="icon icon-2x icon-google-plus"></i>Google+: google.com/+modx </a></p>



    </div>

    <div id="aboutMODX">
        <p>{$_lang.help_about}</p>

        <p>{$_lang.help_credit}</p>
    </div>
</div>
