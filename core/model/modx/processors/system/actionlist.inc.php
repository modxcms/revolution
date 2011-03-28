<?php
/**
 * @deprecated
 * @package modx
 * @subpackage processors.system
 */
if (!function_exists('getAction')) {
    function getAction($lastaction, $itemid='') {
        switch($lastaction) {
            case "1" : return "Loading a frame(set)"; break;
            case "2" : return "Viewing home page/ online users"; break;
            case "3" : return "Viewing data for document $itemid"; break;
            case "4" : return "Creating a document"; break;
            case "5" : return "Saving document $itemid"; break;
            case "6" : return "Deleting document $itemid"; break;
            case "7" : return "Waiting while MODX cleans up"; break;
            case "8" : return "Logged out"; break;
            case "9" : return "Viewing help"; break;
            case "10" : return "Viewing/ composing messages"; break;
            case "11" : return "Creating a user"; break;
            case "12" : return "Editing user $itemid"; break;
            case "13" : return "Viewing logging"; break;
            case "14" : return "Editing a parser"; break;
            case "15" : return "Saving a parser"; break;
            case "16" : return "Editing template $itemid"; break;
            case "17" : return "Editing settings"; break;
            case "18" : return "Viewing Credits :)"; break;
            case "19" : return "Creating a new template"; break;
            case "20" : return "Saving template $itemid"; break;
            case "21" : return "Deleting template $itemid"; break;
            case "22" : return "Editing Snippet $itemid"; break;
            case "23" : return "Creating a new Snippet"; break;
            case "24" : return "Saving Snippet $itemid"; break;
            case "25" : return "Deleting Snippet $itemid"; break;
            case "26" : return "Refreshing site"; break;
            case "27" : return "Editing document $itemid"; break;
            case "28" : return "Changing password"; break;
            case "29" : return "Error"; break;
            case "30" : return "Saving settings"; break;
            case "31" : return "Using file manager"; break;
            case "32" : return "Saving user $itemid"; break;
            case "33" : return "Deleting user $itemid"; break;
            case "34" : return "Saving new password"; break;
            case "35" : return "Editing role $itemid"; break;
            case "36" : return "Saving role $itemid"; break;
            case "37" : return "Deleting role $itemid"; break;
            case "38" : return "Creating new role"; break;
            case "40" : return "Editing Access Permissions"; break;
            case "41" : return "Editing Access Permissions"; break;
            case "42" : return "Editing Access Permissions"; break;
            case "43" : return "Editing Access Permissions"; break;
            case "44" : return "Editing Access Permissions"; break;
            case "45" : return "Idle"; break;
            case "46" : return "Editing Access Permissions"; break;
            case "47" : return "Editing Access Permissions"; break;
            case "48" : return "Editing Access Permissions"; break;
            case "49" : return "Editing Access Permissions"; break;
            case "50" : return "Editing Access Permissions"; break;
            case "51" : return "Moving document $itemid"; break;
            case "52" : return "Moved document $itemid"; break;
            case "53" : return "Viewing system info"; break;
            case "54" : return "Optimizing a table"; break;
            case "55" : return "Empty logs"; break;
            case "56" : return "Refresh document tree"; break;
            case "57" : return "Refresh menu"; break;
            case "58" : return "Logged in"; break;
            case "59" : return "About MODX"; break;
            case "60" : return "Emptying Recycle Bin"; break;
            case "61" : return "Publishing a document"; break;
            case "62" : return "Un-publishing a document"; break;
            case "63" : return "Un-deleting a document"; break;
            case "64" : return "Removing deleted content"; break;
            case "65" : return "Deleting a message"; break;
            case "66" : return "Sending a message"; break;
            case "67" : return "Removing locks"; break;
            case "68" : return "Viewing site logging"; break;
            case "69" : return "Viewing online visitors"; break;
            case "70" : return "Viewing site schedule"; break;
            case "71" : return "Searching"; break;
            case "72" : return "Adding a weblink"; break;
            case "73" : return "Editing a weblink"; break;
            //case "74" : return "Changing personal preferences"; break;
            case "75" : return "User/ role management"; break;
            case "76" : return "Resource management"; break;
            case "77" : return "Creating a new Chunk (HTML Snippet)"; break;
            case "78" : return "Editing Chunk (HTML Snippet) $itemid"; break;
            case "79" : return "Saving Chunk (HTML Snippet) $itemid"; break;
            case "80" : return "Deleting Chunk (HTML Snippet) $itemid"; break;
            case "83" : return "Exporting a document to HTML"; break;
            case "84" : return "Load Resource Selector"; break;
            case "85" : return "Create Folder"; break;
            case "86" : return "Role management"; break;
            case "87" : return "Create new web user"; break;
            case "88" : return "Editing web user $itemid"; break;
            case "89" : return "Saving web user $itemid"; break;
            case "90" : return "Deleting web user $itemid"; break;
            case "91" : return "Editing Web Access Permissions"; break;
            case "92" : return "Editing Access Permissions"; break;
            case "93" : return "Backup Manager"; break;
            case "94" : return "Duplicate Document $itemid"; break;
            case "95" : return "Importing documents from HTML"; break;
            case "96" : return "Duplicate Template $itemid"; break;
            case "97" : return "Duplicate Chunk (HTML Snippet) $itemid"; break;
            case "98" : return "Duplicate Snippet $itemid"; break;
            case "99" : return "Manage Web Users"; break;
            case "100" : return "Previewing document $itemid"; break;
            case "101" : return "Create new plugin $itemid"; break;
            case "102" : return "Edit plugin $itemid"; break;
            case "103" : return "Saving plugin $itemid"; break;
            case "104" : return "Delete plugin $itemid"; break;
            case "105" : return "Duplicate plugin $itemid"; break;
            case "114" : return "View event log"; break;
            case "115" : return "View event log details $itemid"; break;
            case "116" : return "Delete event log $itemid"; break;

            case "300" : return "Create Template Variable"; break;
            case "301" : return "Edit Template Variable"; break;
            case "302" : return "Save Template Variable"; break;
            case "303" : return "Delete Template Variable"; break;
            case "304" : return "Duplicate Template Variable $itemid"; break;

            case "200" : return "Viewing phpInfo()"; break;
            case "998" : return "Viewing web page $itemid"; break;
            case "999" : return "Viewing test page"; break;
            default : return "Idle";
        }
    }
}
