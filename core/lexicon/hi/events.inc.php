<?php
/**
 * System Events English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['events'] = 'आयोजन';
$_lang['system_event'] = 'तरीके वाले आयोजन';
$_lang['system_events'] = 'सिस्टम ईवेंट्स';
$_lang['system_events.desc'] = 'System Events are the events in MODX that Plugins are registered to. They are "fired" throughout the MODX code, allowing Plugins to interact with MODX code and add custom functionality without hacking core code. You can create your own events for your custom project here too. You cannot delete core events, only your own.';
$_lang['system_events.search_by_name'] = 'घटना के नाम से खोजें';
$_lang['system_events.name_desc'] = 'घटना का नाम | जिसका आपको &डॉलर;modx->invokeEvent(name, properties) कॉल में उपयोग करना चाहिए
';
$_lang['system_events.groupname'] = 'समूह';
$_lang['system_events.groupname_desc'] = 'The name of the group where the event belongs to. Select an existing one or type a new group name.';
$_lang['system_events.plugins'] = 'प्लगइन्स';
$_lang['system_events.plugins_desc'] = 'घटना से जुड़े प्लगिंस की सूची | ऐसे प्लगइंस चुने जिन्हे इवेंट से जोड़ा जाना चाहिए';

$_lang['system_events.service'] = 'सेवा';
$_lang['system_events.service_1'] = 'पार्सर सेवा कार्यक्रम';
$_lang['system_events.service_2'] = 'प्रतिबंधक द्वारा जारी कार्यक्रम';
$_lang['system_events.service_3'] = 'वेब द्वारा कार्यकम सेवा';
$_lang['system_events.service_4'] = 'कैस सेवा कार्यक्रम';
$_lang['system_events.service_5'] = 'पोस्टर सेवा कार्यक्रम';
$_lang['system_events.service_6'] = 'उपयोगकर्ता परिभाषित घटनाए';

$_lang['system_events.remove_confirm'] = 'Are you sure you want to delete the <b>[[+name]]</b> event? This is irreversible!';

$_lang['system_events_err_ns'] = 'सिस्टम इवेंट का नाम निर्दिष्ट नहीं है|';
$_lang['system_events_err_ae'] = 'सिस्टम इवेंट का नाम पहले से मौजूद हैं|';
$_lang['system_events_err_startint'] = 'नाम को अंक से शुरू करने की अनुमति नहीं है';
$_lang['system_events_err_remove_not_allowed'] = 'You\'re not allowed to delete this System Event.';
