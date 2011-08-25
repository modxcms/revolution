<?php
/*
 * Copyright 2010 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 *  http://aws.amazon.com/apache2.0
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

/*
	PREREQUISITES:
	In order to run this sample, I'll assume a few things:

	* You already have a valid Amazon Web Services developer account, and are
	  signed up to use Amazon SimpleDB <http://aws.amazon.com/simpledb>.

	* You already understand the fundamentals of object-oriented PHP.

	* You've verified that your PHP environment passes the SDK Compatibility Test.

	* You've already added your credentials to your config.inc.php file, as per the
	  instructions in the Getting Started Guide.

	TO RUN:
	* Run this file on your web server by loading it in your browser. It will generate HTML output.
*/


/*%******************************************************************************************%*/
// SETUP

	// Enable full-blown error reporting. http://twitter.com/rasmus/status/7448448829
	error_reporting(-1);

	// Set HTML headers
	header("Content-type: text/html; charset=utf-8");

	// Include the SDK
	require_once '../sdk.class.php';


/*%******************************************************************************************%*/
// ADD DATA TO SIMPLEDB

	// Instantiate the AmazonSDB class
	$sdb = new AmazonSDB();

	// Store the name of the domain
	$domain = 'php-sdk-getting-started';

	// Create the domain
	$new_domain = $sdb->create_domain($domain);

	// Was the domain created successfully?
	if ($new_domain->isOK())
	{
		// Add a batch of item-key-values to your domain
		$add_attributes = $sdb->batch_put_attributes($domain, array(
			'Item_01' => array(
				'Category'    => 'Clothes',
				'Subcategory' => 'Sweater',
				'Name'        => 'Cathair Sweater',
				'Color'       => 'Siamese',
				'Size'        => array('Small', 'Medium', 'Large')
			),
			'Item_02' => array(
				'Category'    => 'Clothes',
				'Subcategory' => 'Pants',
				'Name'        => 'Designer Jeans',
				'Color'       => 'Paisley Acid Wash',
				'Size'        => array('30x32', '32x32', '32x34')
			),
			'Item_03' => array(
				'Category'    => 'Clothes',
				'Subcategory' => 'Pants',
				'Name'        => 'Sweatpants',
				'Color'       => array('Blue', 'Yellow', 'Pink'),
				'Size'        => 'Large',
				'Year'        => array('2006', '2007')
			),
			'Item_04' => array(
				'Category'    => 'Car Parts',
				'Subcategory' => 'Engine',
				'Name'        => 'Turbos',
				'Make'        => 'Audi',
				'Model'       => 'S4',
				'Year'        => array('2000', '2001', '2002')
			),
			'Item_05' => array(
				'Category'    => 'Car Parts',
				'Subcategory' => 'Emissions',
				'Name'        => 'O2 Sensor',
				'Make'        => 'Audi',
				'Model'       => 'S4',
				'Year'        => array('2000', '2001', '2002')
			),
		));

		// Were the attributes added successfully?
		if ($add_attributes->isOK())
		{
			// Add an additional size to Item_01
			$append_attributes = $sdb->put_attributes($domain, 'Item_01', array(
				'Size' => 'Extra Large'
			));

			// Were the new attributes appended successfully?
			if ($append_attributes->isOK())
			{
			 	// Use a SELECT expression to query the data.
				// Notice the use of backticks around the domain name.
				$results = $sdb->select("SELECT * FROM `{$domain}` WHERE Category = 'Clothes'");

				// Get all of the <Item> nodes in the response
				$items = $results->body->Item();

				// Re-structure the data so access is easier (see helper function below)
				$data = reorganize_data($items);

				// Generate <table> HTML from the data (see helper function below)
				$html = generate_html_table($data);
			}
		}
	}


/*%******************************************************************************************%*/
// HELPER FUNCTIONS

	function reorganize_data($items)
	{
		// Collect rows and columns
		$rows = array();
		$columns = array();

		// Loop through each of the items
		foreach ($items as $item)
		{
			// Let's append to a new row
			$row = array();
			$row['id'] = (string) $item->Name;

			// Loop through the item's attributes
			foreach ($item->Attribute as $attribute)
			{
				// Store the column name
				$column_name = (string) $attribute->Name;

				// If it doesn't exist yet, create it.
				if (!isset($row[$column_name]))
				{
					$row[$column_name] = array();
				}

				// Append the new value to any existing values
				// (Remember: Entries can have multiple values)
				$row[$column_name][] = (string) $attribute->Value;
				natcasesort($row[$column_name]);

				// If we've not yet collected this column name, add it.
				if (!in_array($column_name, $columns, true))
				{
					$columns[] = $column_name;
				}
			}

			// Append the row we created to the list of rows
			$rows[] = $row;
		}

		// Return both
		return array(
			'columns' => $columns,
			'rows' => $rows,
		);
	}

	function generate_html_table($data)
	{
		// Retrieve row/column data
		$columns = $data['columns'];
		$rows = $data['rows'];

		// Generate shell of HTML table
		$output = '<table cellpadding="0" cellspacing="0" border="0">' . PHP_EOL;
		$output .= '<thead>';
		$output .= '<tr>';
		$output .= '<th></th>'; // Corner of the table headers

		// Add the table headers
		foreach ($columns as $column)
		{
			$output .= '<th>' . $column . '</th>';
		}

		// Finish the <thead> tag
		$output .= '</tr>';
		$output .= '</thead>' . PHP_EOL;
		$output .= '<tbody>';

		// Loop through the rows
		foreach ($rows as $row)
		{
			// Display the item name as a header
			$output .= '<tr>' . PHP_EOL;
			$output .= '<th>' . $row['id'] . '</th>';

			// Pull out the data, in column order
			foreach ($columns as $column)
			{
				// If we have a value, concatenate the values into a string. Otherwise, nothing.
				$output .= '<td>' . (isset($row[$column]) ? implode(', ', $row[$column]) : '') . '</td>';
			}

			$output .= '</tr>' . PHP_EOL;
		}

		// Close out our table
		$output .= '</tbody>';
		$output .= '</table>';

		return $output;
	}


?><!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title>sdb_create_domain_data</title>
		<style type="text/css" media="screen">
		body {
			margin: 0;
			padding: 0;
			font: 14px/1.5em "Helvetica Neue", "Lucida Grande", Verdana, Arial, sans;
			background-color: #fff;
			color: #333;
		}
		table {
			margin: 50px auto 0 auto;
			padding: 0;
			border-collapse: collapse;
		}
		table th {
			background-color: #eee;
		}
		table td,
		table th {
			padding: 5px 10px;
			border: 1px solid #eee;
		}
		table td {
			border: 1px solid #ccc;
		}
		</style>
	</head>
	<body>

		<!-- Display HTML table -->
		<?php echo $html; ?>

	</body>
</html>