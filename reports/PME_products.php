<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>products</title>
<style type="text/css">
	hr.pme-hr		     { border: 0px solid; padding: 0px; margin: 0px; border-top-width: 1px; height: 1px; }
	table.pme-main 	     { border: #004d9c 1px solid; border-collapse: collapse; border-spacing: 0px; width: 100%; }
	table.pme-navigation { border: #004d9c 0px solid; border-collapse: collapse; border-spacing: 0px; width: 100%; }
	th.pme-header	     { border: #004d9c 1px solid; padding: 4px; background: #add8e6; }
	td.pme-key-0, td.pme-value-0, td.pme-help-0, td.pme-navigation-0, td.pme-cell-0,
	td.pme-key-1, td.pme-value-1, td.pme-help-0, td.pme-navigation-1, td.pme-cell-1,
	td.pme-sortinfo, td.pme-filter { border: #004d9c 1px solid; padding: 3px; }
	td.pme-buttons { text-align: left;   }
	td.pme-message { text-align: center; }
	td.pme-stats   { text-align: right;  }
</style>
<!--<link rel="stylesheet" href="../src/style.css" type="text/css" />-->
</head>
<body>
<h3>products</h3>
<?php

/*
 * IMPORTANT NOTE: This generated file contains only a subset of huge amount
 * of options that can be used with phpMyEdit. To get information about all
 * features offered by phpMyEdit, check official documentation. It is available
 * online and also for download on phpMyEdit project management page:
 *
 * http://platon.sk/projects/main_page.php?project_id=5
 *
 * This file was generated by:
 *
 *                    phpMyEdit version: 5.6
 *       phpMyEdit.class.php core class: 1.188
 *            phpMyEditSetup.php script: 1.48
 *              generating setup script: 1.48
 */

// MySQL host name, user name, password, database, and table
$opts['hn'] = 'localhost';
$opts['un'] = 'michell3_she';
$opts['pw'] = 'm3ssimba33**';
$opts['db'] = 'michell3_is4c_op';
$opts['tb'] = 'products';

// Name of field which is the unique key
$opts['key'] = 'id';

// Type of key field (int/real/string/date etc.)
$opts['key_type'] = 'int';

// Sorting field(s)
$opts['sort_field'] = array('id');

// Number of records to display on the screen
// Value of -1 lists all records in a table
$opts['inc'] = -1;

// Options you wish to give the users
// A - add,  C - change, P - copy, V - view, D - delete,
// F - filter, I - initial sort suppressed
$opts['options'] = 'ACPVDF';

// Number of lines to display on multiple selection filters
$opts['multiple'] = '4';

// Navigation style: B - buttons (default), T - text links, G - graphic links
// Buttons position: U - up, D - down (default)
$opts['navigation'] = 'BD';

// Display special page elements
$opts['display'] = array(
	'form'  => true,
	'query' => true,
	'sort'  => true,
	'time'  => true,
	'tabs'  => true
);

// Set default prefixes for variables
$opts['js']['prefix']               = 'PME_js_';
$opts['dhtml']['prefix']            = 'PME_dhtml_';
$opts['cgi']['prefix']['operation'] = 'PME_op_';
$opts['cgi']['prefix']['sys']       = 'PME_sys_';
$opts['cgi']['prefix']['data']      = 'PME_data_';

/* Get the user's default language and use it if possible or you can
   specify particular one you want to use. Refer to official documentation
   for list of available languages. */
$opts['language'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];

/* Table-level filter capability. If set, it is included in the WHERE clause
   of any generated SELECT statement in SQL query. This gives you ability to
   work only with subset of data from table.

$opts['filters'] = "column1 like '%11%' AND column2<17";
$opts['filters'] = "section_id = 9";
$opts['filters'] = "PMEtable0.sessions_count > 200";
*/
if(@$_POST['allDepts'] == 1) {
	$_SESSION['deptArray'] = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,40";
	$opts['filters'] = "department IN(".$_SESSION['deptArray'].")";
}
if(is_array(@$_POST['dept'])) {
	$_SESSION['deptArray'] = implode(",",$_POST['dept']);
	$opts['filters'] = "department IN(".$_SESSION['deptArray'].")";
} 

/* Field definitions
   
Fields will be displayed left to right on the screen in the order in which they
appear in generated list. Here are some most used field options documented.

['name'] is the title used for column headings, etc.;
['maxlen'] maximum length to display add/edit/search input boxes
['trimlen'] maximum length of string content to display in row listing
['width'] is an optional display width specification for the column
          e.g.  ['width'] = '100px';
['mask'] a string that is used by sprintf() to format field output
['sort'] true or false; means the users may sort the display on this column
['strip_tags'] true or false; whether to strip tags from content
['nowrap'] true or false; whether this field should get a NOWRAP
['select'] T - text, N - numeric, D - drop-down, M - multiple selection
['options'] optional parameter to control whether a field is displayed
  L - list, F - filter, A - add, C - change, P - copy, D - delete, V - view
            Another flags are:
            R - indicates that a field is read only
            W - indicates that a field is a password field
            H - indicates that a field is to be hidden and marked as hidden
['URL'] is used to make a field 'clickable' in the display
        e.g.: 'mailto:$value', 'http://$value' or '$page?stuff';
['URLtarget']  HTML target link specification (for example: _blank)
['textarea']['rows'] and/or ['textarea']['cols']
  specifies a textarea is to be used to give multi-line input
  e.g. ['textarea']['rows'] = 5; ['textarea']['cols'] = 10
['values'] restricts user input to the specified constants,
           e.g. ['values'] = array('A','B','C') or ['values'] = range(1,99)
['values']['table'] and ['values']['column'] restricts user input
  to the values found in the specified column of another table
['values']['description'] = 'desc_column'
  The optional ['values']['description'] field allows the value(s) displayed
  to the user to be different to those in the ['values']['column'] field.
  This is useful for giving more meaning to column values. Multiple
  descriptions fields are also possible. Check documentation for this.
*/

$opts['fdd']['upc'] = array(
  'name'     => 'Upc',
  'select'   => 'T',
  'options'  => 'LFR',
  'maxlen'   => 13,
  'sort'     => true
);
$opts['fdd']['description'] = array(
  'name'     => 'Description',
  'select'   => 'T',
  'options'  => 'LF',
  'maxlen'   => 30,
  'sort'     => true
);
$opts['fdd']['normal_price'] = array(
  'name'     => 'Normal price',
  'select'   => 'T',
  'maxlen'   => 22,
  'sort'     => true
);
$opts['fdd']['department'] = array(
  'name'     => 'Department',
  'select'   => 'T',
  'maxlen'   => 6,
  'sort'     => true,
  'values'	 => array(
	'table' 	=> 'departments',
	'column' 	=> 'dept_no',
	'description' => 'dept_name'
  )
);
$opts['fdd']['subdept'] = array(
  'name'     => 'Subdept',
  'select'   => 'T',
  'maxlen'   => 4,
  'sort'     => true,
  'values'	 => array(
	'table'		=> 'subdepts',
	'column' 	=> 'subdept_no',
	'description' => 'subdept_name'
  )
);
$opts['fdd']['size'] = array(
  'name'     => 'Size',
  'select'   => 'T',
  'maxlen'   => 9,
  'sort'     => true
);
$opts['fdd']['foodstamp'] = array(
  'name'     => 'Foodstamp',
  'select'   => 'T',
  'maxlen'   => 4,
  'sort'     => true
);
$opts['fdd']['scale'] = array(
  'name'     => 'Scale',
  'select'   => 'T',
  'maxlen'   => 4,
  'sort'     => true
);
$opts['fdd']['modified'] = array(
  'name'     => 'Modified',
  'select'   => 'T',
  'maxlen'   => 19,
  'sort'     => true,
  'options'	 => 'H'
);
$opts['fdd']['inUse'] = array(
  'name'     => 'InUse',
  'select'   => 'T',
  'maxlen'   => 4,
  'sort'     => true
);
$opts['fdd']['special_price'] = array(
  'name'     => 'Special price',
  'select'   => 'T',
  'maxlen'   => 22,
  'sort'     => true
);
$opts['fdd']['id'] = array(
  'name'     => 'ID',
  'select'   => 'T',
  'options'  => 'AVCPDR', // auto increment
  'maxlen'   => 11,
  'default'  => '0',
  'sort'     => true
);

// Now important call to phpMyEdit
require_once $_SERVER["DOCUMENT_ROOT"].'/src/phpMyEdit/phpMyEdit.class.php';
new phpMyEdit($opts);

/*
//
// PHP INPUT DEBUG SCRIPT  -- very helpful!
//

function debug_p($var, $title) 
{
    print "<p>$title</p><pre>";
    print_r($var);
    print "</pre>";
}  

debug_p($_REQUEST, "all the data coming in");

*/
?>


</body>
</html>
