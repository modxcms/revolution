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
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'Allow_tags_in_post Context सेटिंग mgr Context के बाहर अपनी स्थापना में सक्षम है। MODX अनुशंसा करता है जब तक कि आपको स्पष्ट रूप से MODX tags, आंकिक निकायों या HTML script tags पोस्ट विधि एक के रूप में आपकी साइट के लिए के माध्यम से प्रस्तुत करने के लिए प्रयोक्ताओं की अनुमति यह सेटिंग अक्षम हो जाएगा। यह आम तौर पर छोड़कर mgr Context में निष्क्रिय किया जाना चाहिए.';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'allow_tags_in_post सिस्टम सेटिंग सक्षम किया गया';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'Allow_tags_in_post सिस्टम सेटिंग आपकी स्थापना में सक्षम है। MODX अनुशंसा करता है जब तक कि आपको स्पष्ट रूप से MODX tags, आंकिक निकायों या HTML script tags POST विधि एक के रूप में आपकी साइट के लिए के माध्यम से प्रस्तुत करने के लिए प्रयोक्ताओं की अनुमति यह सेटिंग अक्षम हो जाएगा। यह इस context विशिष्ट contexts के लिए सेटिंग्स के माध्यम से सक्षम करने के लिए बेहतर है।';
$_lang['configcheck_cache'] = 'Cache directory नहीं लिखने योग्य';
$_lang['configcheck_cache_msg'] = 'MODX cannot write to the cache directory. MODX will still function as expected, but no caching will take place. To solve this, make the /cache/ directory writable.';
$_lang['configcheck_configinc'] = 'Config file अभी भी लेखन योग्य!';
$_lang['configcheck_configinc_msg'] = 'अपनी साइट पर जो साइट को नुकसान का एक बहुत कुछ कर सकता है हैकर के लिए असुरक्षित है। कृपया अपने config file केवल पढ़ने के लिए करने के लिए! यदि आप साइट व्यवस्थापक नहीं हैं, तो कृपया किसी सिस्टम व्यवस्थापक से संपर्क करें और उन्हें इस संदेश के बारे में चेतावनी दें! यह स्थित है [[+path]]';
$_lang['configcheck_default_msg'] = 'एक अनिर्दिष्ट चेतावनी पाया गया था। जो अजीब है।';
$_lang['configcheck_errorpage_unavailable'] = 'आपकी साइट की त्रुटि पृष्ठ उपलब्ध नहीं है।';
$_lang['configcheck_errorpage_unavailable_msg'] = 'इसका मतलब यह है कि आपके Error page सामान्य web surfers करने के लिए पहुँच योग्य नहीं है या मौजूद नहीं है। यह एक recursive looping condition और आपकी साइट लॉग में कई त्रुटियों के लिए नेतृत्व कर सकते हैं। सुनिश्चित करें कि कोई webuser समूह पृष्ठ करने के लिए असाइन किए गए हैं।';
$_lang['configcheck_errorpage_unpublished'] = 'आपकी साइट की त्रुटि पृष्ठ प्रकाशित नहीं है या मौजूद नहीं है।';
$_lang['configcheck_errorpage_unpublished_msg'] = 'इसका मतलब यह है कि आपके Error page आम जनता के लिए दुर्गम है। पृष्ठ प्रकाशित करें या सुनिश्चित करें कि यह आपकी साइट ट्री में कोई मौजूदा दस्तावेज़ के लिए प्रणाली में सौंपा है &gt; System Settings menu.';
$_lang['configcheck_htaccess'] = 'कोर फोल्डर वेब द्वारा पहुंचा जा सकता हैं';
$_lang['configcheck_htaccess_msg'] = 'MODX ने पाया कि आपका मुख्य फ़ोल्डर (आंशिक रूप से) जनता के लिए सुलभ है!
<strong>यह अनुशंसित नहीं है और सुरक्षा जोखिम है!</strong>
यदि आपका MODX संस्थापन Apache वेबसर्वर पर चल रहा है
आपको कम से कम .htaccess फ़ाइल को मुख्य फ़ोल्डर <em>[[+fileLocation]]</em> के अंदर सेट करना चाहिए!
यह मौजूदा ht.access उदाहरण फ़ाइल का नाम बदलकर .htaccess में आसानी से किया जा सकता है!
<p>ऐसी अन्य विधियां और वेबसर्वर हैं जिनका आप उपयोग कर सकते हैं, कृपया <a href="https://docs.modx.com/3.x/en/getting-started/maintenance/securing-modx">Hardening MODX पढ़ें गाइड</a>
आपकी साइट को सुरक्षित करने के बारे में अधिक जानकारी के लिए!</p>
यदि आप सब कुछ सही ढंग से सेटअप करते हैं, तो ब्राउज़िंग उदा. <a href="[[+checkUrl]]"
target="_blank">चेंजलॉग</a> . पर
आपको 403 (अनुमति अस्वीकृत) या बेहतर 404 (नहीं मिला) देना चाहिए। यदि आप चैंज देख सकते हैं
ब्राउज़र में, अभी भी कुछ गड़बड़ है और इसे हल करने के लिए आपको फिर से कॉन्फ़िगर करने या किसी विशेषज्ञ को कॉल करने की आवश्यकता है!';
$_lang['configcheck_images'] = 'Images directory लिखने योग्य नहीं';
$_lang['configcheck_images_msg'] = 'images directory लिखने योग्य नहीं है, या मौजूद नहीं है। इसका मतलब यह Image Manager कार्य संपादक में काम नहीं करेगा!';
$_lang['configcheck_installer'] = 'इंस्टॉलर अभी भी मौजूद';
$_lang['configcheck_installer_msg'] = 'The setup/ directory contains the installer for MODX. Just imagine what might happen if an evil person finds this folder and runs the installer! They probably won\'t get too far, because they\'ll need to enter some user information for the database, but it\'s still best to delete this folder from your server. It is located at: [[+path]]';
$_lang['configcheck_lang_difference'] = 'language file में entries की ग़लत संख्या';
$_lang['configcheck_lang_difference_msg'] = 'वर्तमान में चयनित भाषा डिफ़ॉल्ट भाषा से प्रविष्टियों का एक अलग संख्या है। जरूरी नहीं कि एक समस्या है जबकि, यह मतलब हो सकता है language file अद्यतन की जरूरत है।';
$_lang['configcheck_notok'] = 'एक या एक से अधिक configuration details ठीक बाहर की जाँच नहीं किया: ';
$_lang['configcheck_phpversion'] = 'पीएचपी संस्करण पुराना है';
$_lang['configcheck_phpversion_msg'] = 'आपका PHP संस्करण [[+PHP version]] अब PHP डेवलपर्स द्वारा अनुरक्षित नहीं है, जिसका अर्थ है की कोई सुरक्षा अघतन उपलब्ध नही है | यह भी संभावना है कि एमओडीएक्स या एक अतिरिक पैकेज अभी या निकट भविष्य में संस्करण का समर्थन नही करेगा | कृपया अपनी साइड को सुरक्षित करने के लिए अपने परिवेश को कम से कम PHP [[+PHP required]] पर यथाशीघ्र अपडेट करें|';
$_lang['configcheck_register_globals'] = 'register_globals अपने php. ini configuration file में सेट करने के लिए पर है';
$_lang['configcheck_register_globals_msg'] = 'इस विन्यास आपकी साइट बहुत अधिक Cross Site Scripting (XSS) हमलों के लिए अतिसंवेदनशील बनाता है। आप अपने host करने के लिए क्या आप इस सेटिंग को अक्षम करने के लिए कर सकते हैं के बारे में बात करनी चाहिए।';
$_lang['configcheck_title'] = 'Configuration जाँच';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'आपकी साइट के अनधिकृत पृष्ठ प्रकाशित नहीं है या मौजूद नहीं है।';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'इसका मतलब यह है कि आपके Error page सामान्य web surfers करने के लिए पहुँच योग्य नहीं है या मौजूद नहीं है। यह एक recursive looping condition और आपकी साइट लॉग में कई त्रुटियों के लिए नेतृत्व कर सकते हैं। सुनिश्चित करें कि कोई webuser समूह पृष्ठ करने के लिए असाइन किए गए हैं।';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'अनधिकृत site configuration सेटिंग्स में Unauthorized page प्रकाशित नहीं है।';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'इसका मतलब यह है कि आपके Error page आम जनता के लिए दुर्गम है। पृष्ठ प्रकाशित करें या सुनिश्चित करें कि यह आपकी साइट ट्री में कोई मौजूदा दस्तावेज़ के लिए प्रणाली में सौंपा है &gt; System Settings menu.';
$_lang['configcheck_warning'] = 'Configuration चेतावनी:';
$_lang['configcheck_what'] = 'इसका क्या मतलब है?';
