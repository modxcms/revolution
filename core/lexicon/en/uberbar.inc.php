<?php
/**
 * Uberbar English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

/* Settings */
$_lang['uberbar_mode'] = 'Uberbar mode';
$_lang['uberbar_mode_desc'] = 'Set to simple or advanced.';

$_lang['uberbar_maxresults'] = 'Maximum number of results per page';
$_lang['uberbar_maxresults_desc'] = 'Set to a positive integer (default: 5).';

$_lang['uberbar_simple'] = 'simple';
$_lang['uberbar_advanced'] = 'advanced';

$_lang['uberbar_header'] = 'Advanced search:';

/* Search: step-by-step*/
$_lang['uberbar_pick_element_header'] = '<strong>Step 1:</strong> Enter an element:';
$_lang['uberbar_pick_element_msg'] = 'Welcome to MODX uberbar search. You are using it in <strong>[[+uberbar_mode]]</strong> mode. Finish each step with a colon <strong>(:)</strong>. ';

$_lang['uberbar_pick_fieldname_header'] = '<strong>Step 2:</strong> Enter a field:';
$_lang['uberbar_pick_fieldname_msg'] = 'Your search is limited to these fields.';

$_lang['uberbar_enter_searchstring_header'] = '<strong>Step 3:</strong> Enter a search string:';
$_lang['uberbar_enter_searchstring_msg'] = 'Searching for foo will return an exact match. Use wildcards (*) for different results: *foo*, *foo and foo*.';

$_lang['uberbar_searchresults_header'] = '<strong>Step 4:</strong> Search results:';
$_lang['uberbar_searchresults_yes'] = 'Your search returned <strong>[[+totalResults]] result(s) </strong> in <strong>[[+exec]] seconds</strong>.';
$_lang['uberbar_searchresults_no'] = 'Your search for <strong>[[+your_query]]</strong> returned <strong>0</strong> results. Try again and don\'t forget to make use of wildcards (*foo, *foo*, foo* or just foo).';

/* Errors */
$_lang['uberbar_error_unknown_element_header'] = '<strong>Error:</strong> Unknown element';
$_lang['uberbar_error_unknown_element_msg'] = 'You are searching for an unknown element: <strong>[[+element]]</strong>. Please check your syntax.';

$_lang['uberbar_error_permission_denied_header'] = '<strong>Error:</strong> Permission denied';
$_lang['uberbar_error_permission_denied_msg'] = 'You are searching in an element you are not allowed to view: <strong>[[+element]]</strong>. You might want to have a chat with the person in charge of permissions.';

$_lang['uberbar_error_unknown_field_header'] = '<strong>Error:</strong> Unknown field';
$_lang['uberbar_error_unknown_field_msg'] = 'You are searching inside an unknown field: <strong>[[+field]]</strong>. Please remove it from your search and try something else.';

/* Pagination */
$_lang['uberbar_pagination_header'] = '';
$_lang['uberbar_pagination_msg'] = 'Add pagenumbers to get more results.';
