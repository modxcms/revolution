<?php
/**
 * Sources English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['access'] = 'पहुँच अनुमति';
$_lang['base_path'] = 'आधार पथ';
$_lang['base_path_relative'] = 'आधार पथ रिश्तेदार?';
$_lang['base_url'] = 'आधार URL';
$_lang['base_url_relative'] = 'आधार यूआरएल रिश्तेदार?';
$_lang['minimum_role'] = 'कम से कम भूमिका';
$_lang['path_options'] = 'पथ विकल्प';
$_lang['policy'] = 'नीति';
$_lang['source'] = 'मीडिया स्रोत';
$_lang['source_access_add'] = 'User Group जोड़ें';
$_lang['source_access_remove'] = 'एक्सेस हटाएं';
$_lang['source_access_remove_confirm'] = 'क्या आप बकाई इस उपयोगकर्ता समूह के लिए इस स्त्रोत तक पहुंच हटाना चाहते हैं।';
$_lang['source_access_update'] = 'एक्सेस संपादित करें';
$_lang['source_description_desc'] = 'मीडिया के स्रोत का एक छोटा वर्णन।';
$_lang['source_err_ae_name'] = 'इस नाम के साथ एक मीडिया स्रोत पहले से मौजूद है! कृपया कोई नया नाम निर्दिष्ट करें।';
$_lang['source_err_nf'] = 'मीडिया स्रोत नहीं पाया!';
$_lang['source_err_init'] = 'Could not initialize "[[+source]]" Media Source!';
$_lang['source_err_nfs'] = 'कोई मीडिया स्रोत आईडी के साथ पाया जा सकता है: [[+id]]।';
$_lang['source_err_ns'] = 'कृपया मीडिया स्रोत निर्दिष्ट करें।';
$_lang['source_err_ns_name'] = 'कृपया मीडिया के स्रोत के लिए कोई नाम निर्दिष्ट करें।';
$_lang['source_name_desc'] = 'मीडिया के स्रोत का नाम।';
$_lang['source_properties.intro_msg'] = 'नीचे इस स्रोत के लिए संपत्तियों का प्रबंधन।';
$_lang['source_remove_confirm'] = 'Are you sure you want to delete this Media Source? This might break any TVs you have assigned to this source.';
$_lang['source_remove_multiple_confirm'] = 'क्या वाकई आप इन मीडिया स्रोतों को नष्ट करने के लिए चाहते हैं? यह आप इन सूत्रों को असाइन किया गया है किसी भी टीवी टूट सकता है।';
$_lang['source_type'] = 'स्रोत का प्रकार';
$_lang['source_type_desc'] = 'प्रकार, या ड्रायवर, मीडिया के स्रोत की। स्रोत जब इसकी डेटा एकत्र करने के लिए कनेक्ट करने के लिए इस ड्राइवर का उपयोग करेंगे। उदाहरण के लिए: फ़ाइल सिस्टम फ़ाइलों को फ़ाइल सिस्टम से पकड़ लेना होगा। S3 फ़ाइलें एक S3 बाल्टी से मिल जाएगा।';
$_lang['source_type.file'] = 'फ़ाइल सिस्टम';
$_lang['source_type.file_desc'] = 'आपके सर्वर की फ़ाइलों navigates कि एक फाइल सिस्टम आधारित स्रोत है।';
$_lang['source_type.s3'] = 'अमेजिंन S3';
$_lang['source_type.s3_desc'] = 'एक Amazon S3 बाल्टी Navigates';
$_lang['source_type.ftp'] = 'फाइल ट्रांसफर प्रोटोकॉल';
$_lang['source_type.ftp_desc'] = 'Navigates an FTP रिमोट सर्वर.';
$_lang['source_types'] = 'स्रोत प्रकार';
$_lang['source_types.intro_msg'] = 'यह आपको इस ModX उदाहरण पर है सभी स्थापित मीडिया स्रोत प्रकार की एक सूची है।';
$_lang['source.access.intro_msg'] = 'यहाँ आप विशिष्ट User Groups के लिए एक मीडिया स्रोत प्रतिबंधित कर सकते हैं और उन User Groups के लिए नीतियाँ लागू करें। एक मीडिया स्रोत के साथ कोई User Groups से जुड़ी सभी manager users के लिए उपलब्ध है।';
$_lang['sources'] = 'मीडिया स्रोतों';
$_lang['sources.intro_msg'] = 'सभी अपने मीडिया के सूत्रों का प्रबंधन।';
$_lang['user_group'] = 'उपयोगकर्ता समूह';

/* file source type */
$_lang['allowedFileTypes'] = 'अनुमत फाइल प्रकार';
$_lang['prop_file.allowedFileTypes_desc'] = 'यदि सेट, केवल निर्दिष्ट एक्सटेंशन दिखाया फाइलों को प्रतिबंधित करेगा। कृपया एक्सटेंशन के पूर्ववर्ती डॉट्स के बिना एक कॉमा सेपरेटेड सूची में निर्दिष्ट करें।';
$_lang['basePath'] = 'बेसपथ';
$_lang['prop_file.basePath_desc'] = 'The file path to point the Source to, for example: assets/images/<br>The path may depend on the "basePathRelative" parameter';
$_lang['basePathRelative'] = 'आधारपथसापेक्ष';
$_lang['prop_file.basePathRelative_desc'] = 'अगर ऊपर बेस पथ सेटिंग MODX स्थापना पथ से संबंधित नहीं है, यह सेट नं. को';
$_lang['baseUrl'] = 'बेस यूआरएल';
$_lang['prop_file.baseUrl_desc'] = 'The URL that this source can be accessed from, for example: assets/images/<br>The path may depend on the "baseUrlRelative" parameter';
$_lang['baseUrlPrependCheckSlash'] = 'बेस यूआरएलएल प्रिपेंड चेकस्लेश';
$_lang['prop_file.baseUrlPrependCheckSlash_desc'] = 'यदि कोई फ़ॉरवर्ड स्लैश (/) जब टीवी प्रतिपादन URL की शुरुआत में पाया जाता है अगर यह सच है, MODX केवल baseUrl आगे यह जोड़ें जाएगा। BaseUrl के बाहर एक TV मान सेट करने के लिए उपयोगी है।';
$_lang['baseUrlRelative'] = 'बेस यूआरएल संबंधित';
$_lang['prop_file.baseUrlRelative_desc'] = 'इसके बाद के संस्करण की स्थापना आधार URL यूआरएल स्थापित ModX के सापेक्ष नहीं है, तो नंबर करने के लिए इस सेट';
$_lang['imageExtensions'] = 'छवि एक्सटेंशन';
$_lang['prop_file.imageExtensions_desc'] = 'एक कॉमा सेपरेटेड सूची के रूप में छवियों का उपयोग करने के लिए फ़ाइल एक्सटेंशन। MODX इन एक्सटेंशन के साथ फ़ाइलों के थंबनेल बनाने के प्रयास करेंगे।';
$_lang['skipFiles'] = 'स्किप फाइल';
$_lang['prop_file.skipFiles_desc'] = 'एक अल्पविराम-पृथक सूची। MODX पर छोड़ और छिपा फ़ाइलों और फ़ोल्डरों कि इनमें से कोई भी मैच होगा।';
$_lang['thumbnailQuality'] = 'थंबलेनगुणवत्ता';
$_lang['prop_file.thumbnailQuality_desc'] = '0-100 से पैमाने में रेंडर किए गए थंबनेल की गुणवत्ता।';
$_lang['thumbnailType'] = 'थंबलेन प्रकार ';
$_lang['prop_file.thumbnailType_desc'] = 'छवि प्रकार थंबनेल के रूप में रेंडर करने के लिए।';
$_lang['prop_file.visibility_desc'] = 'Default visibility for new files and folders.';
$_lang['no_move_folder'] = 'The Media Source driver does not support moving of folders at this time.';

/* s3 source type */
$_lang['bucket'] = 'बाल्टी';
$_lang['prop_s3.bucket_desc'] = 'S3 बाल्टी से अपने डेटा को लोड करने के लिए।';
$_lang['prop_s3.key_desc'] = 'Amazon key बाल्टी के लिए प्रमाणन के लिए।';
$_lang['prop_s3.imageExtensions_desc'] = 'एक कॉमा सेपरेटेड सूची के रूप में छवियों का उपयोग करने के लिए फ़ाइल एक्सटेंशन। MODX इन एक्सटेंशन के साथ फ़ाइलों के थंबनेल बनाने के प्रयास करेंगे।';
$_lang['prop_s3.secret_key_desc'] = 'Amazon secret key बाल्टी के लिए प्रमाणन के लिए।';
$_lang['prop_s3.skipFiles_desc'] = 'एक अल्पविराम-पृथक सूची। MODX पर छोड़ और छिपा फ़ाइलों और फ़ोल्डरों कि इनमें से कोई भी मैच होगा।';
$_lang['prop_s3.thumbnailQuality_desc'] = '0-100 से पैमाने में रेंडर किए गए थंबनेल की गुणवत्ता।';
$_lang['prop_s3.thumbnailType_desc'] = 'छवि प्रकार थंबनेल के रूप में रेंडर करने के लिए।';
$_lang['prop_s3.url_desc'] = 'अमेज़न S3 इंस्टेंस का URL.';
$_lang['prop_s3.endpoint_desc'] = 'Alternative S3-compatible endpoint URL, e.g., "https://s3.<region>.example.com". Review your S3-compatible provider’s documentation for the endpoint location. Leave empty for Amazon S3';
$_lang['prop_s3.region_desc'] = 'बाल्टी का क्षेत्र | उदाहरण: हमें-पश्चिम - 1';
$_lang['prop_s3.prefix_desc'] = 'वैकल्पिक पथ/फोल्डर उपसर्ग';
$_lang['s3_no_move_folder'] = 'फ़ोल्डर्स के इस समय में चलती S3 ड्रायवर समर्थन नहीं करता।';

/* ftp source type */
$_lang['prop_ftp.host_desc'] = 'सर्वर होस्टनाम या IP address';
$_lang['prop_ftp.username_desc'] = 'प्रमाणिकरण के लिए उपयोगकर्ता नाम! "गुमनाम" हो सकता है!';
$_lang['prop_ftp.password_desc'] = 'उपयोकर्ता का पासवर्ड. अनाम उपयोगकर्ता के लिए खाली छोड़ दें.';
$_lang['prop_ftp.url_desc'] = 'If this FTP is has a public URL, you can enter its public http-address here. This will also enable image previews in the media browser.';
$_lang['prop_ftp.port_desc'] = 'सर्वर का पोर्ट, डिफाल्ट 21 है.';
$_lang['prop_ftp.root_desc'] = 'The root folder, it will be opened after connection';
$_lang['prop_ftp.passive_desc'] = 'निष्क्रिय ftp मोड को सक्षम या असक्षम करें';
$_lang['prop_ftp.ssl_desc'] = 'ssl कनेक्शन सक्षम या अक्षम करें';
$_lang['prop_ftp.timeout_desc'] = 'सेकंड में कनेक्शन के लिए समयबाह्य.';

/* file type */
$_lang['PNG'] = 'पीएनजी';
$_lang['JPG'] = 'जेपीजी';
$_lang['GIF'] = 'जीआईएफ';
