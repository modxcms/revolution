<?php
/*
 * OpenExpedio (xPDO)
 * Copyright (C) 2006 Jason Coward <xpdo@opengeek. com>
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 */

/**
 * A utility class for creating tabular views of xPDO objects.
 * 
 * Includes support for pagination, sorting by any column, providing optional
 * header arrays, providing classes for styling the table, rows, and cells
 * (including alternate row styling), as well as adding form controls to each
 * row.  Use in conjunction with {@link MakeForm} to create great administration
 * forms, search forms, and object navigation grids in MODx 0.9.x.  Many
 * features of this class depend on {@link http://modxcms.com/ MODx CMS 0.9.x}.
 * 
 * @package xpdo.table
 */
class MakeTable {
    var $xpdo= null;
    var $actionField= '';
    var $actionFieldKey= '';
    var $cellAction= '';
    var $linkAction= '';
    var $tableWidth= '';
    var $tableClass= '';
    var $rowHeaderClass= '';
    var $columnHeaderClass= '';
    var $rowRegularClass= '';
    var $rowAlternateClass= 'mtAltRow';
    var $formName= 'mtDefaultForm';
    var $formAction= '[~[*id*]~]';
    var $formElementType= '';
    var $formElementName= '';
    var $rowAlternatingScheme= 'EVEN';
    var $fieldHeaders= array ();
    var $excludeFields= array ();
    var $selectedValues= array ();
    var $columnWidths= null;
    var $allOption= 0;
    var $pageNav= '';
    var $totalRecords= 0;
    var $displayRecords= 25;
    var $currentPage= 1;
    var $orderBy= '';
    var $orderDir= '';
    var $extra= '';
    var $noRecordsMsg= 'No records found.';
    var $pageNavDivAttr= '';
    var $displayRecordsDivAttr= '';
    var $pageSizes= array (10, 25, 50, 100, 250);
    var $pageNavAtTop= false;
    var $pageNavAtBottom= true;
    
    function MakeTable(& $xpdo) {
        $this->xpdo= & $xpdo;
        if (isset ($_REQUEST['orderby'])) {
            $this->orderBy= $_REQUEST['orderby'];
        }
        if (isset ($_REQUEST['orderdir'])) {
            $this->orderDir= $_REQUEST['orderdir'];
        }
        if (isset ($_REQUEST['page'])) {
            $this->currentPage= $_REQUEST['page'];
        }
        if (isset ($_REQUEST['pageSize'])) {
            $this->displayRecords= intval($_REQUEST['pageSize']);
            $_SESSION['maketable.pageSize']= $this->displayRecords;
        } elseif (isset ($_SESSION['maketable.pageSize'])) {
            $this->displayRecords= $_SESSION['maketable.pageSize'];
        }
    }
    
    /**
     * Sets the default link href for all cells in the table.
     * 
     * @param $value A URL to execute when table cells are clicked.
     */
    function setCellAction($value) {
        $this->cellAction= $this->prepareLink($value);
    }
    
    /**
     * Sets the default link href for the text presented in a cell.
     * 
     * @param $value A URL to execute when text within table cells are clicked.
     */
    function setLinkAction($value) {
        $this->linkAction= $this->prepareLink($value);
    }
    
    /**
     * Sets the width attribute of the main HTML TABLE.
     * 
     * @param $value A valid width attribute for the HTML TABLE tag
     */
    function setTableWidth($value) {
        $this->tableWidth= $value;
    }
    
    /**
     * Sets the class attribute of the main HTML TABLE.
     * 
     * @param $value A class for the main HTML TABLE. 
     */
    function setTableClass($value) {
        $this->tableClass= $value;
    }
    
    /**
     * Sets the class attribute of the table header row.
     * 
     * @param $value A class for the table header row.
     */
    function setRowHeaderClass($value) {
        $this->rowHeaderClass= $value;
    }
    
    /**
     * Sets the class attribute of the column header row.
     * 
     * @param $value A class for the column header row.
     */
    function setColumnHeaderClass($value) {
        $this->columnHeaderClass= $value;
    }
    
    /**
     * Sets the class attribute of regular table rows.
     * 
     * @param $value A class for regular table rows.
     */
    function setRowRegularClass($value) {
        $this->rowRegularClass= $value;
    }
    
    /**
     * Sets the class attribute of alternate table rows.
     * 
     * @param $value A class for alternate table rows.
     */ 
    function setRowAlternateClass($value) {
        $this->rowAlternateClass= $value;
    }
    
    /**
     * Sets the type of INPUT form element to be presented as the first column.
     * 
     * @param $value Indicates the INPUT form element type attribute.
     */
    function setFormElementType($value) {
        $this->formElementType= $value;
    }
    
    /**
     * Sets the name of the INPUT form element to be presented as the first column.
     * 
     * @param $value Indicates the INPUT form element name attribute.
     */
    function setFormElementName($value) {
        $this->formElementName= $value;
    }
    
    /**
     * Sets the name of the FORM to wrap the table in when a form element has 
     * been indicated.
     * 
     * @param $value Indicates the FORM name attribute.
     */
    function setFormName($value) {
        $this->formName= $value;
    }
    
    /**
     * Sets the action of the FORM element.
     * 
     * @param $value Indicates the FORM action attribute.
     */
    function setFormAction($value) {
        $this->formAction= $value;
    }
    
    /**
     * Excludes fields from the table by array key.
     * 
     * @param $value An Array of field keys to exclude from the table.
     */
    function setExcludeFields($value) {
        $this->excludeFields= $value;
    }

    /**
     * Sets the table to provide alternate row colors using ODD or EVEN rows
     * 
     * @param $value 'ODD' or 'EVEN' to indicate the alternate row scheme.
     */
    function setRowAlternatingScheme($value) {
        $this->rowAlternatingScheme= $value;
    }
    
    /**
     * Sets the default field value to be used when appending query parameters
     * to link actions.
     * 
     * @param $value The key of the field to add as a query string parameter.
     */
    function setActionField($value, $alias= '') {
        $this->actionField= $value;
        $this->actionFieldKey= !empty ($alias)? $alias: $value;
    }
    
    /**
     * Sets the width attribute of each column in the array.
     * 
     * @param $value An Array of column widths in the order of the keys in the
     *          source table array.
     */
    function setColumnWidths($widthArray) {
        $this->columnWidths= $widthArray;
    }
    
    /**
     * An optional array of values that can be preselected when using 
     * 
     * @param $value Indicates the INPUT form element type attribute.
     */
    function setSelectedValues($valueArray) {
        $this->selectedValues= $valueArray;
    }
    
    /**
     * Sets extra content to be presented following the table (but within
     * the form, if a form is being rendered with the table).
     * 
     * @param $value A string of additional content.
     */
    function setExtra($value) {
        $this->extra= $value;
    }
    
    /**
     * Sets the total record count for a query without regard to paging limits.
     * 
     * @param integer $value The integer value representing the total records.
     */
    function setTotalRecords($value) {
        $this->totalRecords= $value;
        if (!$this->displayRecords) {
            $this->displayRecords= $this->totalRecords;
        }
    }
    
    function setDisplayRecords($value) {
        if (!$value= intval($value)) {
            $this->displayRecords= $this->totalRecords;
        } else {
            $this->displayRecords= $value;
        }
    }
    
    function setPageNavAttributes($attr= array ()) {
        if (is_array($attr)) {
            $this->pageNavDivAttr= '';
            foreach ($attr as $k => $a)
                $this->pageNavDivAttr.= " {$k}=\"{$a}\"";
        }
    }
    
    function setPageSizeAttributes($attr= array ()) {
        if (is_array($attr)) {
            $this->displayRecordsDivAttr= '';
            foreach ($attr as $k => $a)
                $this->displayRecordsDivAttr.= " {$k}=\"{$a}\"";
        }
    }
    
    /**
     * Retrieves the width of a specific table column by index position.
     * 
     * @param $columnPosition The index of the column to get the width for.
     */
    function getColumnWidth($columnPosition) {
        $currentWidth= '';
        if (is_array($this->columnWidths)) {
            $currentWidth= $this->columnWidths[$columnPosition] ? ' width="'.$this->columnWidths[$columnPosition].'" ' : '';
        }
        return $currentWidth;
    }
    
    /**
     * Determines what class the current row should have applied.
     * 
     * @param $value The position of the current row being rendered.
     */
    function determineRowClass($position) {
        switch ($this->rowAlternatingScheme) {
            case 'ODD' :
                $modRemainder= 1;
                break;
            case 'EVEN' :
                $modRemainder= 0;
                break;
        }
        if ($position % 2 == $modRemainder) {
            $currentClass= $this->rowRegularClass;
        } else {
            $currentClass= $this->rowAlternateClass;
        }
        return ' class="'.$currentClass.'"';
    }
    
    /**
     * Generates an onclick action applied to the current cell, to execute 
     * any specified cell actions.
     * 
     * @param $value Indicates the INPUT form element type attribute.
     */
    function getCellAction($currentActionFieldValue) {
        $cellAction= '';
        if ($this->cellAction) {
            $cellAction= ' onClick="javascript:window.location=\''.$this->cellAction.$this->actionFieldKey.'='.urlencode($currentActionFieldValue).'\'" ';
        }
        return $cellAction;
    }
    
    /**
     * Generates the cell content, including any specified action fields values.
     * 
     * @param $currentActionFieldValue The value to be applied to the link action.
     * @param $value The value of the cell.
     */
    function createCellText($currentActionFieldValue, $value) {
        $cell= $value;
        if ($this->linkAction) {
            $cell= '<a href="'.$this->linkAction.$this->actionFieldKey.'='.urlencode($currentActionFieldValue).'">'.$cell.'</a>';
        }
        return $cell;
    }
    
    /**
     * Sets an option to generate a check all link when checkbox is indicated 
     * as the table formElementType.
     */
    function setAllOption() {
        $this->allOption= 1;
    }
    
    /**
     * Function to prepare a link generated in the table cell/link actions.
     * 
     * @param $value Indicates the INPUT form element type attribute.
     */
    function prepareLink($link) {
        if (strstr($link, '?')) {
            $end= '&';
        } else {
            $end= '?';
        }
        return $link.$end;
    }
    
    /**
     * Generates the table content.
     * 
     * @param $fieldsArray The associative array representing the table rows 
     * and columns.
     * @param $fieldHeadersArray An optional array of values for providing
     * alternative field headers; this is an associative arrays of keys from
     * the $fieldsArray where the values represent the alt heading content
     * for each column.
     */
    function create($fieldsArray, $fieldHeadersArray=array()) {
        if (is_array($fieldsArray)) {
            $i= 0;
            $table= '';
            $header= '';
            if (!empty ($fieldsArray)) {
                foreach ($fieldsArray as $fieldName => $fieldValue) {
                    if (is_object($fieldValue) && $fieldValue instanceof xPDOObject) {
                        $fieldValue= $fieldValue->toArray();
                    }
                    $table .= "\t<tr".$this->determineRowClass($i).">\n";
                    $currentActionFieldValue= isset ($fieldValue[$this->actionField])? $fieldValue[$this->actionField]: '';
                    if (is_array($this->selectedValues)) {
                        $isChecked= array_search($currentActionFieldValue, $this->selectedValues)===false? 0 : 1;
                    } else {
                        $isChecked= false;
                    }
                    $table .= $this->addFormField($currentActionFieldValue, $isChecked);
                    $colPosition= 0;
                    foreach ($fieldValue as $key => $value) {
                        if (!in_array($key, $this->excludeFields)) {
                            $table .= "\t\t<td".$this->getCellAction($currentActionFieldValue).">";
                            $table .= $this->createCellText($currentActionFieldValue, $value);
                            $table .= "</td>\n";
                            if ($i == 0) {
                                if (empty ($header) && $this->formElementType) {
                                    $header .= "\t\t<th style=\"width:32px\">". ($this->allOption ? '<a href="javascript:clickAll()">all</a>' : '')."</th>\n";
                                }
                                $headerText= array_key_exists($key, $fieldHeadersArray)? $fieldHeadersArray[$key]: $key;
                                $header .= "\t\t<th".$this->getColumnWidth($colPosition).">".$headerText."</th>\n";
                            }
                            $colPosition ++;
                        }
                    }
                    $i ++;
                    $table .= "\t</tr>\n";
                }
            } else {
                $colPosition= 0;
                foreach ($fieldHeadersArray as $headerKey => $headerText) {
                    $header .= "\t\t<th".$this->getColumnWidth($colPosition).">".$headerText."</th>\n";
                    $colPosition++;
                }
                $msgColspan= '';
                if ($numCols= count($fieldHeadersArray)) {
                    $msgColspan= ' colspan="'.$numCols.'"';
                }
                $table= '';
                $table .= "\t<tr".$this->determineRowClass($i).">\n";
                $table .= "\t\t<td{$msgColspan}>{$this->noRecordsMsg}</td>\n";
                $table .= "\t</tr>\n";
            }
            $table= "\n".'<table'. ($this->tableWidth ? ' width="'.$this->tableWidth.'"' : ''). ($this->tableClass ? ' class="'.$this->tableClass.'"' : '').">\n". ($header ? "\t<thead><tr class=\"".$this->rowHeaderClass."\">\n".$header."\t</tr></thead>\n" : '')."<tbody>".$table."</tbody></table>\n";
            if ($this->formElementType) {
                $table= "\n".'<form id="'.$this->formName.'" name="'.$this->formName.'" action="'.$this->formAction.'" method="POST">'.$table;
            }
            if ($this->pageNav) {
                $table .= '<div'.$this->displayRecordsDivAttr.'><select style="display:inline" onchange="javascript:updatePageSize(this[this.selectedIndex].value);">' . "\n";
                foreach ($this->pageSizes as $pageSize) {
                    $table .= '<option value="'.$pageSize.'"';
                    $table .= $this->displayRecords == $pageSize ? ' selected ' : '';
                    $table .= '>'.$pageSize.'</option>' . "\n";
                }
                $table .= '</select> per page</div>';
                if ($this->pageNavAtTop) $table = '<div'.$this->pageNavDivAttr.'><ul>'.$this->pageNav.'</ul></div>' . "\n" . $table;
                if ($this->pageNavAtBottom) $table .= '<div'.$this->pageNavDivAttr.'><ul>'.$this->pageNav.'</ul></div>' . "\n";
                $table .= '<script type="text/javascript">function updatePageSize(size){window.location = \''.$_SERVER['REQUEST_URI']. (strpos($_SERVER['REQUEST_URI'], '?') ? '&' : '?').'pageSize=\'+size;}</script>' . "\n";
            }
            if ($this->allOption) {
                $table .= '
<script type="text/javascript">
    toggled = 0;
    function clickAll() {
        myform = document.getElementById("'.$this->formName.'");
        for(i=0;i<myform.length;i++) {
            if(myform.elements[i].type==\'checkbox\') {
                myform.elements[i].checked=(toggled?false:true);
            }
        }
        toggled = (toggled?0:1);
    }
</script>
';
            }
            if ($this->formElementType) {
                if ($this->extra) {
                    $table.= "\n".$this->extra."\n";
                }
                $table.= "\n".'</form>'."\n";
            }
            return $table;
        }
    }
    
    /**
     * Generates optional paging navigation controls for the table.
     * 
     * @param $qs An optional query string to be appended to the paging links
     */
    function createPagingNavigation($qs='', $currentURL= '') {
        $nav= '';
        $currentPage= (is_numeric($this->currentPage) ? $this->currentPage : 1);
        if ($this->xpdo->getDebug() === true) $this->xpdo->_log(XPDO_LOG_LEVEL_DEBUG, "Total records: {$this->totalRecords}, Display records: {$this->displayRecords}");
        if (!$this->totalRecords) {
            return $nav;
        }
        if (!$this->displayRecords) {
            $this->displayRecords= $this->totalRecords;
        }
        $numPages= ceil($this->totalRecords / $this->displayRecords);
        if ($numPages > 1) {
            $currentURL.= empty($qs)? '': '?'.$qs;
         if (!empty ($this->orderBy)) { 
            $currentURL.= strpos($currentURL, '?') !== false? '&': '?';
            $currentURL.= 'orderby=' . $this->orderBy;
            if (!empty ($this->orderDir)) {
               $currentURL.= '&orderdir=' . $this->orderDir;
            }
         }
            if ($currentPage > 6) {
                $nav .= $this->createPageLink($currentURL, 1, 'First');
            }
            if ($currentPage != 1) {
                $nav .= $this->createPageLink($currentURL, $currentPage -1, '&lt;&lt;');
            }
            $offset= -4 + ($currentPage < 5 ? (5 - $currentPage) : 0);
            $i= 1;
            while ($i < 10 && ($currentPage + $offset <= $numPages)) {
                if ($currentPage == $currentPage + $offset)
                    $nav .= $this->createPageLink($currentURL, $currentPage + $offset, $currentPage + $offset, true);
                else
                    $nav .= $this->createPageLink($currentURL, $currentPage + $offset, $currentPage + $offset);
                $i ++;
                $offset ++;
            }
            if ($currentPage < $numPages) {
                $nav .= $this->createPageLink($currentURL, $currentPage +1, '&gt;&gt;');
            }
            if ($currentPage != $numPages) {
                $nav .= $this->createPageLink($currentURL, $numPages, 'Last');
            }
        }
        $this->pageNav= ' '.$nav;
    }
    
    /**
     * Creates an individual page link for the paging navigation.
     * 
     * @param $link The link for the page, defaulted to the current document.
     * @param $pageNum The page number of the link.
     * @param $displayText The text of the link.
     * @param $currentPage Indicates if the link is to the current page.
     * @param $qs And optional query string to be appended to the link.
     */
    function createPageLink($link='', $pageNum, $displayText, $currentPage=false, $qs='') {
        global $modx;
        $nav= '';
        $orderBy= !empty($this->orderBy)? '&orderby=' . $this->orderBy: '';
        $orderDir= !empty($this->orderDir)? '&orderdir=' . $this->orderDir: '';
        if (!empty($qs)) $qs= "?$qs";
        $link= empty($link)? $modx->makeUrl($modx->resourceIdentifier, '', $qs . "page=$pageNum$orderBy$orderDir"): $this->prepareLink($link) . "page=$pageNum";
        $nav .= '<li'.($currentPage? ' class="currentPage"': '').'><a'.($currentPage? ' class="currentPage"': '').' href="'.$link.'">'.$displayText.'</a></li>'."\n";
        return $nav;
    }
    
    /**
     * Adds an INPUT form element column to the table.
     * 
     * @param $value The value attribute of the element.
     * @param $isChecked Indicates if the checked attribute should apply to the 
     * element.
     */
    function addFormField($value, $isChecked) {
        $field= '';
        if ($this->formElementType) {
            $checked= $isChecked? "checked ": "";
            $field= "\t\t".'<td><input type="'.$this->formElementType.'" name="'. ($this->formElementName ? $this->formElementName : $value).'"  value="'.$value.'" '.$checked.'/></td>'."\n";
        }
        return $field;
    }
    
    /**
     * Generates the proper LIMIT clause for queries to retrieve paged results in
     * a MakeTable $fieldsArray.
     */
    function handlePaging() {
        $offset= (is_numeric($this->currentPage) && $this->currentPage > 0) ? $this->currentPage - 1 : 0;
        $limitClause= ' LIMIT '. ($offset * $this->displayRecords).', '.$this->displayRecords;
        return $limitClause;
    }
    
    /**
     * Generates the SORT BY clause for queries used to retrieve a MakeTable 
     * $fieldsArray
     * 
     * @param $natural_order If true, the results are returned in natural order.
     */
    function handleSorting($natural_order=false) {
        $orderByClause= '';
        if (!$natural_order) {
            $orderby= !empty($this->orderBy)? $this->orderBy: "id";
            $orderdir= !empty($this->orderDir)? $this->orderDir: "DESC";
            $orderbyClause= !empty($orderby)? ' ORDER BY ' . $orderby . ' ' . $orderdir . ' ': "";
        }
        return $orderbyClause;
    }
    
    /**
     * Generates a link to order by a specific $fieldsArray key; use to generate
     * sort by links in the MakeTable $fieldHeadingsArray values.
     * 
     * @param $key The $fieldsArray key for the column to sort by.
     * @param $text The text for the link (e.g. table column header).
     * @param $qs An optional query string to append to the order by link.
     */
    function prepareOrderByLink($key, $text, $qs='', $link= '') {
        global $modx;
        if (!empty($this->orderDir)) {
            $orderDir= strtolower($this->orderDir)=='desc'? '&orderdir=asc': '&orderdir=desc';
        } else {
            $orderDir= '&orderdir=asc';
        }
        if (!$link) {
            $link= $modx->makeUrl($modx->resourceIdentifier);
        }
		$orderUrl = $this->prepareLink($link) . $qs.'orderby='.$key.$orderDir;
		return '<a href="' . $orderUrl . '">'.$text.'</a>';
    }
}
