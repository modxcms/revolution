<div class="modx-page" style="padding: 17px 1.5em 1em 1em;">

    <div id="helpBanner">
This will be fed with amazing informations via the Ajaxes with a local fallback. Included will be important notices, MODX Professionals Ads, Site Sponsor Ads, etc. Likely using the Orbit Ad Server: http://orbitopenadserver.com
    </div>


    <div id="managerbuttons">
        <ul>

            <li>
                <a href="{$_lang.forums_link}" target="_blank" title="{$_lang.forums_title}">
                    <span class="icon"><i class="icon-comments icon-3x"></i></span>
                    <span class="headline">{$_lang.forums}</span>
                    <span class="subline">{$_lang.forums_description}</span>
                </a>
            </li>

            <li>
                <a href="{$_lang.docs_link}" target="_blank" title="{$_lang.docs_title}">
                    <span class="icon"><i class="icon-book icon-3x"></i></span>
                    <span class="headline">{$_lang.docs}</span>
                    <span class="subline">{$_lang.docs_description}</span>
                </a>
            </li>

            <li>
                <a href="{$_lang.bugs_link}" target="_blank" title="{$_lang.bugs_title}">
                    <span class="icon"><i class="icon-exclamation-sign icon-3x"></i></span>
                    <span class="headline">{$_lang.bugs}</span>
                    <span class="subline">{$_lang.bugs_description}</span>
                </a>
            </li>

            <li>
                <a href="{$_lang.support_link}" target="_blank" title="{$_lang.support_title}">
                    <span class="icon"><i class="icon-credit-card icon-3x"></i></span>
                    <span class="headline">{$_lang.support}</span>
                    <span class="subline">{$_lang.support_description}</span>
                </a>
            </li>

        </ul>
    </div>

    <div id="contactus">
    {$_lang.email_sub} 
           <form id="mcsignup" action="http://modxcms.list-manage.com/subscribe/post" method="post">
                <input type="hidden" name="u" value="08b25a8de68a29fe03a483720" />
                <input type="hidden" name="id" value="848cf40420" />

                <input type="hidden" name="MERGE7" value="[[++site_url]] Manager" id="MERGE7">

                <input type="email" placeholder="you@example.com" required id="MERGE0" name="MERGE0" value="" class="textbox" />
                <input  type="submit" name="Submit" value="{$_lang.email_sub_button}" />
            </form>

    <a href="">{$_lang.follow} <i class="icon-large icon-twitter-sign"></i></a> 
    <a href="">{$_lang.like} <i class="icon-large icon-facebook-sign"></i></a> 
    <a href="">{$_lang.circle} <i class="icon-large icon-google-plus-sign"></i></a> 

    </div>

    <div id="aboutMODX">
    <p>{$_lang.help_about}</p>

    <p>{$_lang.help_credit}</p>

</div>