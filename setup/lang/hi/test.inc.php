<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'जाँच अगर <span class="mono"> [[+file]]</span> मौजूद है और लेखन योग्य है : ';
$_lang['test_config_file_nw'] = 'नई Linux/Unix स्थापित करता है के लिए, अपने ModX कोर में एक खाली नामक फ़ाइल <span class="mono"> [[+key]]। Inc.php </span> बनाने कृपया <span class="mono">config/</span> पीएचपी द्वारा लिखने योग्य होना तय अनुमतियों के साथ directory।';
$_lang['test_db_check'] = 'डेटाबेस के लिए कनेक्शन बना रहा है: ';
$_lang['test_db_check_conn'] = 'कनेक्शन जानकारी की जाँच करें और पुन: प्रयास करें।';
$_lang['test_db_failed'] = 'डेटाबेस कनेक्शन विफल रहा!';
$_lang['test_db_setup_create'] = 'सेटअप डेटाबेस बनाने का प्रयास करेंगे।';
$_lang['test_dependencies'] = 'PHP के लिए zlib निर्भरता की जाँच: ';
$_lang['test_dependencies_fail_zlib'] = 'अपने PHP अधिष्ठापन "zlib" एक्सटेंशन स्थापित नहीं हैं। यह एक्सटेंशन MODX चलाने के लिए आवश्यक है। कृपया इसे जारी रखने के लिए सक्षम करें।';
$_lang['test_directory_exists'] = 'जाँच यदि <span class="mono"> [[+dir]]</span> directory मौजूद है: ';
$_lang['test_directory_writable'] = 'जाँच की यदि <span class="mono"> [[+dir]]</span> directory writable है: ';
$_lang['test_memory_limit'] = 'यदि स्मृति सीमा कम से कम 24M करने के लिए सेट है की जाँच: ';
$_lang['test_memory_limit_fail'] = 'MODX अपने memory_limit सेटिंग में किया जा करने के लिए [[+memory]], 24 मीटर की अनुशंसित सेटिंग के नीचे पाया गया। MODX memory_limit 24 मीटर करने के लिए सेट करने का प्रयास किया, लेकिन असफल रहा था। कृपया memory_limit सेटिंग सेट करने के लिए कम से कम 24 M या उच्चतर आगे बढ़ने से पहले अपने php.ini फ़ाइल में। यदि आप अभी भी मुसीबत (जैसे एक खाली सफेद पर्दे पर स्थापित हो रही है) कर रहे हैं, 32M, 64M के लिए सेट करें या उच्चतर।';
$_lang['test_memory_limit_success'] = 'ठीक है! सेट करने के लिए [[+memory]]';
$_lang['test_mysql_version_5051'] = 'MODX अपने MySQL संस्करण ([[+version]]), इस संस्करण पर PDO चालकों से संबंधित कई bugs की वजह पर मुद्दों होगा। कृपया इन समस्याओं पैच करने के लिए MySQL का उन्नयन। यहां तक कि अगर आप MODX का उपयोग नहीं करने के लिए चुनें, यह अनुशंसित है आप अपनी स्वयं की वेबसाइट की स्थिरता और सुरक्षा के लिए इस संस्करण के लिए उन्नयन।';
$_lang['test_mysql_version_client_nf'] = 'MySQL क्लाइंट संस्करण का पता नहीं लगा सकता है!';
$_lang['test_mysql_version_client_nf_msg'] = 'ModX mysql_get_client_info के माध्यम से अपने MySQL ग्राहक संस्करण का पता नहीं लगा सकता है ()। मैन्युअल रूप से अपने MySQL ग्राहक संस्करण आगे बढ़ने से पहले कम से कम 4.1.20 है कि सुनिश्चित करें।';
$_lang['test_mysql_version_client_old'] = 'आप एक बहुत पुरानी MySQL के ग्राहक संस्करण का उपयोग कर रहे हैं क्योंकि ModX मुद्दों हो सकता है ([[+version]])';
$_lang['test_mysql_version_client_old_msg'] = 'MODX स्थापना यह MySQL क्लाइंट संस्करण का उपयोग करने की अनुमति देगा, लेकिन हम गारंटी नहीं दे सकते सभी कार्यक्षमता ठीक से MySQL क्लायंट लायब्रेरीज़ के पुराने संस्करणों का उपयोग करते समय उपलब्ध या काम हो जाएगा।';
$_lang['test_mysql_version_client_start'] = 'MySQL क्लाइंट संस्करण की जाँच:';
$_lang['test_mysql_version_fail'] = 'आप MySQL [[+version]] पर चल रहे हैं, और ModX क्रांति MySQL के 4.1.20 या बाद में की आवश्यकता है। कम से कम 4.1.20 करने के लिए MySQL अपग्रेड करें।';
$_lang['test_mysql_version_server_nf'] = 'MySQL सर्वर संस्करण का पता नहीं लगा सकता है!';
$_lang['test_mysql_version_server_nf_msg'] = 'ModX mysql_get_server_info के माध्यम से अपने सर्वर संस्करण का पता नहीं लगा सकता है ()। स्वयं अपने सर्वर संस्करण आगे बढ़ने से पहले कम से कम 4.1.20 है कि सुनिश्चित करें।';
$_lang['test_mysql_version_server_start'] = 'MySQL सर्वर संस्करण की जाँच:';
$_lang['test_mysql_version_success'] = 'ठीक है! रनिंग: [[+version]]';
$_lang['test_nocompress'] = 'हम CSS/JS संपीड़न को निष्क्रिय करना चाहिए अगर जाँच हो रही है:';
$_lang['test_nocompress_disabled'] = 'ठीक है! विकलांग।';
$_lang['test_nocompress_skip'] = 'परीक्षण लंघन, चयनित नहीं।';
$_lang['test_php_version_fail'] = 'आप PHP पर चल रहे हैं [[+version]], और ModX क्रांति पीएचपी 5.1.1 या बाद में की आवश्यकता है। कम से कम 5.1.1 करने के लिए PHP अपग्रेड करें। ModX कम से कम 5.3.2+ के उन्नयन की सिफारिश की।';
$_lang['test_php_version_start'] = 'PHP संस्करण की जाँच:';
$_lang['test_php_version_success'] = 'ठीक है! रनिंग: [[+version]]';
$_lang['test_safe_mode_start'] = 'Safe_mode बंद है सुनिश्चित करने के लिए जाँच:';
$_lang['test_safe_mode_fail'] = 'MODX safe_mode पर होना करने के लिए मिला है। आप आगे बढ़ने के लिए अपने PHP विन्यास में safe_mode को अक्षम करना होगा।';
$_lang['test_sessions_start'] = 'जाँच यदि सत्र ठीक से कॉन्फ़िगर किया गया हैं:';
$_lang['test_simplexml'] = 'SimpleXML के लिए की जाँच:';
$_lang['test_simplexml_nf'] = 'SimpleXML ढूँढ नहीं सकता है!';
$_lang['test_simplexml_nf_msg'] = 'ModX अपने PHP पर्यावरण पर SimpleXML नहीं मिल सकता है। Package Management और अन्य कार्यक्षमता स्थापित इस के बिना काम नहीं करेगा। आप स्थापना के साथ जारी रख सकते हैं, लेकिन ModX उन्नत सुविधाओं और कार्यक्षमता के लिए SimpleXML को सक्षम करने की सिफारिश की।';
$_lang['test_suhosin'] = 'Suhosin मुद्दों के लिए जाँच:';
$_lang['test_suhosin_max_length'] = 'Suhosin अधिकतम मूल्य भी कम मिलता है!';
$_lang['test_suhosin_max_length_err'] = 'वर्तमान में, आप PHP suhosin एक्सटेंशन का उपयोग कर रहे हैं, और ModX ठीक से प्रबंधक जे एस फ़ाइलें सेक करने के लिए अपने suhosin.get.max_value_length बहुत कम सेट है। ModX 4096 के लिए है कि मूल्य बढ़ा सिफारिश की गई है; तब तक, ModX स्वचालित रूप से त्रुटियों को रोकने के लिए शून्य करने के लिए अपने JS संपीड़न (compress_js सेटिंग) की स्थापना की जाएगी।';
$_lang['test_table_prefix'] = 'तालिका उपसर्ग \'[[+prefix]]\' की जाँच: ';
$_lang['test_table_prefix_inuse'] = 'तालिका उपसर्ग इस डेटाबेस में उपयोग में पहले से ही है!';
$_lang['test_table_prefix_inuse_desc'] = 'यह पहले से ही आपके द्वारा निर्दिष्ट उपसर्ग के साथ टेबल में शामिल है के रूप में स्थापना, चयनित डेटाबेस में स्थापित नहीं कर सका। एक नए table_prefix चुनते हैं, और सेटअप फिर से चलाएँ।';
$_lang['test_table_prefix_nf'] = 'तालिका उपसर्ग इस डेटाबेस में मौजूद नहीं है!';
$_lang['test_table_prefix_nf_desc'] = 'यह आप उन्नत किया जा करने के लिए निर्दिष्ट उपसर्ग के साथ मौजूदा टेबल शामिल नहीं करता है के रूप में स्थापना, चयनित डेटाबेस में स्थापित नहीं कर सका। एक मौजूदा table_prefix चुनते हैं, और सेटअप फिर से चलाएँ।';
$_lang['test_zip_memory_limit'] = 'यदि स्मृति सीमा ज़िप एक्सटेंशन के लिए कम से कम 24M करने के लिए सेट है की जाँच: ';
$_lang['test_zip_memory_limit_fail'] = 'ModX 24m की सिफारिश की सेटिंग से नीचे होने के लिए अपने memory_limit सेटिंग पाया। ModX 24m को memory_limit सेट करने का प्रयास किया, लेकिन असफल रहा था। ज़िप एक्सटेंशन ठीक से काम कर सकते हैं, जिससे कि आगे बढ़ने से पहले 24m या इससे अधिक के लिए अपनी फाइल में memory_limit सेटिंग सेट करें।';