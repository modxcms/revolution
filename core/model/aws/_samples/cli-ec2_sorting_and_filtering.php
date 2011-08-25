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
	  signed up to use Amazon EC2 <http://aws.amazon.com/ec2>.

	* You already understand the fundamentals of object-oriented PHP.

	* You've verified that your PHP environment passes the SDK Compatibility Test.

	* You've already added your credentials to your config.inc.php file, as per the
	  instructions in the Getting Started Guide.

	TO RUN:
	* Run this file on your web server by loading it in your browser, OR...
	* Run this file from the command line with `php cli-ec2_sorting_and_filtering.php`.
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
// THE GOALS & PREPARATION

	/*
		1. The goal of this exercise is to retrieve a list of all image IDs that are prefixed with "aki-".
		2. We should end up with an indexed array of string values (just the image IDs).
	*/

	// Instantiate the AmazonEC2 class
	$ec2 = new AmazonEC2();

	// Get the response from a call to the DescribeImages operation.
	$response = $ec2->describe_images();


/*%******************************************************************************************%*/
// THE LONG WAY

	// Prepare to collect AKIs.
	$akis = array();

	// Loop through the response...
	foreach ($response->body->imagesSet->item as $item)
	{
		// Stringify the value
		$image_id = (string) $item->imageId;

		// Filter the value against a PCRE regular expression.
		if (preg_match('/aki/i', $image_id))
		{
			// If the name matches our pattern, add it to the list.
			$akis[] = $image_id;
		}
	}

	// Display
	print_r($akis);


/*%******************************************************************************************%*/
// THE SHORT WAY

	// Look through the body, grab ALL <imageId> nodes, stringify the values, and filter them with the
	// PCRE regular expression.
	$akis = $response->body->imageId()->map_string('/aki/i');

	// Display
	print_r($akis);
