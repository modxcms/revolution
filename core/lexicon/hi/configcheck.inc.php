<?php
/**
 * Config Check English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'कृपया किसी सिस्टम व्यवस्थापक से संपर्क करें और उन्हें इस संदेश के बारे में चेतावनी दें!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'allow_tags_in_post \'mgr\' बाहर Context सेटिंग सक्षम किया गया';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'Allow_tags_in_post Context सेटिंग mgr Context के बाहर अपनी स्थापना में सक्षम है। MODX अनुशंसा करता है जब तक कि आपको स्पष्ट रूप से MODX tags, आंकिक निकायों या HTML script tags पोस्ट विधि एक के रूप में आपकी साइट के लिए के माध्यम से प्रस्तुत करने के लिए प्रयोक्ताओं की अनुमति यह सेटिंग अक्षम हो जाएगा। यह आम तौर पर छोड़कर mgr Context में निष्क्रिय किया जाना चाहिए।';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'allow_tags_in_post सिस्टम सेटिंग सक्षम किया गया';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'Allow_tags_in_post सिस्टम सेटिंग आपकी स्थापना में सक्षम है। MODX अनुशंसा करता है जब तक कि आपको स्पष्ट रूप से MODX tags, आंकिक निकायों या HTML script tags POST विधि एक के रूप में आपकी साइट के लिए के माध्यम से प्रस्तुत करने के लिए प्रयोक्ताओं की अनुमति यह सेटिंग अक्षम हो जाएगा। यह इस context विशिष्ट contexts के लिए सेटिंग्स के माध्यम से सक्षम करने के लिए बेहतर है।';
$_lang['configcheck_cache'] = 'Cache directory नहीं लिखने योग्य';
$_lang['configcheck_cache_msg'] = 'MODX Cache directory करने के लिए नहीं लिख सकता। MODX अभी भी अपेक्षित रूप से कार्य करेगा, लेकिन कोई कैशिंग जगह नहीं ले जाएगा। इस को हल करने के लिए, /_cache/ directory को लिखने योग्य बनाएँ।';
$_lang['configcheck_configinc'] = 'Config file अभी भी लेखन योग्य!';
$_lang['configcheck_configinc_msg'] = 'अपनी साइट पर जो साइट को नुकसान का एक बहुत कुछ कर सकता है हैकर के लिए असुरक्षित है। कृपया अपने config file केवल पढ़ने के लिए करने के लिए! यदि आप साइट व्यवस्थापक नहीं हैं, तो कृपया किसी सिस्टम व्यवस्थापक से संपर्क करें और उन्हें इस संदेश के बारे में चेतावनी दें! यह स्थित है [[+path]]';
$_lang['configcheck_default_msg'] = 'एक अनिर्दिष्ट चेतावनी पाया गया था। जो अजीब है।';
$_lang['configcheck_errorpage_unavailable'] = 'आपकी साइट की त्रुटि पृष्ठ उपलब्ध नहीं है।';
$_lang['configcheck_errorpage_unavailable_msg'] = 'इसका मतलब यह है कि आपके Error page सामान्य web surfers करने के लिए पहुँच योग्य नहीं है या मौजूद नहीं है। यह एक recursive looping condition और आपकी साइट लॉग में कई त्रुटियों के लिए नेतृत्व कर सकते हैं। सुनिश्चित करें कि कोई webuser समूह पृष्ठ करने के लिए असाइन किए गए हैं।';
$_lang['configcheck_errorpage_unpublished'] = 'आपकी साइट की त्रुटि पृष्ठ प्रकाशित नहीं है या मौजूद नहीं है।';
$_lang['configcheck_errorpage_unpublished_msg'] = 'इसका मतलब यह है कि आपके Error page आम जनता के लिए दुर्गम है। पृष्ठ प्रकाशित करें या सुनिश्चित करें कि यह आपकी साइट ट्री में कोई मौजूदा दस्तावेज़ के लिए प्रणाली में सौंपा है &gt; System Settings menu.';
$_lang['configcheck_htaccess'] = 'Core folder is accessible by web';
$_lang['configcheck_htaccess_msg'] = 'MODX detected that your core folder is (partially) accessible to the public.
<strong>This is not recommended and a security risk.</strong>
If your MODX installation is running on a Apache webserver
you should at least set up the .htaccess file inside the core folder <em>[[+fileLocation]]</em>.
This can be easily done by renaming the existing ht.access example file there to .htaccess.
<p>There are other methods and webservers you may use, please read the <a href="https://docs.modx.com/current/en/getting-started/maintenance/securing-modx">Hardening MODX Guide</a>
for further information about securing your site.</p>
If you setup everything correctly, browsing e.g. to the <a href="[[+checkUrl]]" target="_blank">Changelog</a>
should give you a 403 (permission denied) or better a 404 (not found). If you can see the changelog
there in the browser, something is still wrong and you need to reconfigure or call an expert to solve this.';
$_lang['configcheck_images'] = 'Images directory लिखने योग्य नहीं';
$_lang['configcheck_images_msg'] = 'images directory लिखने योग्य नहीं है, या मौजूद नहीं है। इसका मतलब यह Image Manager कार्य संपादक में काम नहीं करेगा!';
$_lang['configcheck_installer'] = 'इंस्टॉलर अभी भी मौजूद';
$_lang['configcheck_installer_msg'] = 'setup/ directory MODX के लिए installer होता है। जरा सोचो क्या हो सकता है यदि एक दुष्ट व्यक्ति इस फ़ोल्डर को पाता है और installer चलाता है! वे शायद क्योंकि वे कुछ उपयोगकर्ता जानकारी के लिए databaseमें प्रवेश करने की आवश्यकता होगी, लेकिन यह अभी भी यह फ़ोल्डर अपने server से निकालने के लिए सबसे अच्छा है, बहुत दूर प्राप्त नहीं होगा। यह स्थित है: [[+path]]';
$_lang['configcheck_lang_difference'] = 'language file में entries की ग़लत संख्या';
$_lang['configcheck_lang_difference_msg'] = 'वर्तमान में चयनित भाषा डिफ़ॉल्ट भाषा से प्रविष्टियों का एक अलग संख्या है। जरूरी नहीं कि एक समस्या है जबकि, यह मतलब हो सकता है language file अद्यतन की जरूरत है।';
$_lang['configcheck_notok'] = 'एक या एक से अधिक configuration details ठीक बाहर की जाँच नहीं किया: ';
$_lang['configcheck_ok'] = 'Check passed OK - कोई चेतावनियाँ रिपोर्ट को पारित कर दिया।';
$_lang['configcheck_phpversion'] = 'PHP version is outdated';
$_lang['configcheck_phpversion_msg'] = 'Your PHP version [[+phpversion]] is no longer maintained by the PHP developers, which means no security updates are available. It is also likely that MODX or an extra package now or in the near future will no longer support this version. Please update your environment at least to PHP [[+phprequired]] as soon as possible to secure your site.';
$_lang['configcheck_register_globals'] = 'register_globals अपने php. ini configuration file में सेट करने के लिए पर है';
$_lang['configcheck_register_globals_msg'] = 'इस विन्यास आपकी साइट बहुत अधिक Cross Site Scripting (XSS) हमलों के लिए अतिसंवेदनशील बनाता है। आप अपने host करने के लिए क्या आप इस सेटिंग को अक्षम करने के लिए कर सकते हैं के बारे में बात करनी चाहिए।';
$_lang['configcheck_title'] = 'Configuration जाँच';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'आपकी साइट के अनधिकृत पृष्ठ प्रकाशित नहीं है या मौजूद नहीं है।';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'इसका मतलब यह है कि आपके Error page सामान्य web surfers करने के लिए पहुँच योग्य नहीं है या मौजूद नहीं है। यह एक recursive looping condition और आपकी साइट लॉग में कई त्रुटियों के लिए नेतृत्व कर सकते हैं। सुनिश्चित करें कि कोई webuser समूह पृष्ठ करने के लिए असाइन किए गए हैं।';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'अनधिकृत site configuration सेटिंग्स में Unauthorized page प्रकाशित नहीं है।';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'इसका मतलब यह है कि आपके Error page आम जनता के लिए दुर्गम है। पृष्ठ प्रकाशित करें या सुनिश्चित करें कि यह आपकी साइट ट्री में कोई मौजूदा दस्तावेज़ के लिए प्रणाली में सौंपा है &gt; System Settings menu.';
$_lang['configcheck_warning'] = 'Configuration चेतावनी:';
$_lang['configcheck_what'] = 'इसका क्या मतलब है?';
