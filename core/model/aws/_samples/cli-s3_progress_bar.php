#! /usr/bin/env php
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
	  signed up to use Amazon S3 <http://aws.amazon.com/s3>.

	* You already understand the fundamentals of object-oriented PHP.

	* You've verified that your PHP environment passes the SDK Compatibility Test.

	* You've already added your credentials to your config.inc.php file, as per the
	  instructions in the Getting Started Guide.

	TO RUN:
	* Run this file from the command line with `php cli-s3_progress_bar.php`.
*/


/*%******************************************************************************************%*/
// SETUP

	// Enable full-blown error reporting. http://twitter.com/rasmus/status/7448448829
	error_reporting(-1);

	// Set plain text headers
	header("Content-type: text/plain; charset=utf-8");

	// Include the SDK
	require_once '../sdk.class.php';

	// Include PEAR Console_ProgressBar
	require_once 'lib/ProgressBar.php';


/*%******************************************************************************************%*/
// DOWNLOAD SAMPLE FILE FROM S3

	// Instantiate the AmazonS3 class
	$s3 = new AmazonS3();

	// Instantiate a new progress bar.
	// We won't know the max number of bytes until the download starts, so we'll handle that in our callback.
	$progress_bar = new Console_ProgressBar('* %fraction% KB [%bar%] %percent%', '=>', ' ', 100, 1);
	$progress_bar->UPDATED = false;

	// Register a callback function to execute when a stream is written locally.
	$s3->register_streaming_write_callback('write_callback');

	function write_callback($curl_handle, $length)
	{
		// Import from global scope
		$progress_bar = $GLOBALS['progress_bar'];

		// Have we updated the format with updated information yet?
		if (!$progress_bar->UPDATED)
		{
			// Add the Content-Length of the file as the max number of bytes.
			$progress_bar->reset('* %fraction% KB [%bar%] %percent%', '=>', ' ', 100,
				curl_getinfo($curl_handle, CURLINFO_CONTENT_LENGTH_DOWNLOAD));
			$progress_bar->UPDATED = true;
		}

		// Update the progress bar with the cumulative number of bytes downloaded.
		$progress_bar->update(curl_getinfo($curl_handle, CURLINFO_SIZE_DOWNLOAD));
	}

	// Add some spacing above the progress bar.
	echo PHP_EOL;

	echo 'Downloading http://aws-sdk-for-php.s3.amazonaws.com/demo/big-buck-bunny.mp4' . PHP_EOL;
	echo 'Writing to ' . realpath('./downloads') . '/big-buck-bunny.mp4' . PHP_EOL;

	// Download a public object.
	$response = $s3->get_object('aws-sdk-for-php', 'demo/big-buck-bunny.mp4', array(
		'fileDownload' => './downloads/big-buck-bunny.mp4'
	));

	// Add some spacing below the progress bar.
	echo PHP_EOL;


/*%******************************************************************************************%*/
// UPLOAD SAMPLE FILE TO S3

	$_100_percent = 0;

	// Create a bucket to upload to
	$bucket = 's3-progress-bar-' . strtolower($s3->key);
	if (!$s3->if_bucket_exists($bucket))
	{
		$response = $s3->create_bucket($bucket, AmazonS3::REGION_US_E1);
		if (!$response->isOK()) die('Could not create `' . $bucket . '`.');
	}

	// Instantiate a new progress bar.
	// We won't know the max number of bytes until the download starts, so we'll handle that in our callback.
	$progress_bar = new Console_ProgressBar('* %fraction% KB [%bar%] %percent%', '=>', ' ', 100, 1);
	$progress_bar->UPDATED = false;

	// Register a callback function to execute when a stream is written locally.
	$s3->register_streaming_read_callback('read_callback');

	function read_callback($curl_handle, $file_handle, $length)
	{
		// Import from global scope
		$progress_bar = $GLOBALS['progress_bar'];

		// Have we updated the format with updated information yet?
		if (!$progress_bar->UPDATED)
		{
			// Store the total size in local & global scope
			$_100_percent = $GLOBALS['_100_percent'] = curl_getinfo($curl_handle, CURLINFO_CONTENT_LENGTH_UPLOAD);

			// Add the Content-Length of the file as the max number of bytes.
			$progress_bar->reset('* %fraction% KB [%bar%] %percent%', '=>', ' ', 100, $_100_percent);
			$progress_bar->UPDATED = true;
		}

		// Update the progress bar with the cumulative number of bytes uploaded.
		$progress_bar->update(curl_getinfo($curl_handle, CURLINFO_SIZE_UPLOAD));
	}

	// Add some spacing above the progress bar.
	echo PHP_EOL;

	echo 'Uploading to http://' . $bucket . '.s3.amazonaws.com/big-buck-bunny.mp4' . PHP_EOL;
	echo 'Reading from ' . realpath('./downloads') . '/big-buck-bunny.mp4' . PHP_EOL;

	// Upload an object.
	$response = $s3->create_object($bucket, 'big-buck-bunny.mp4', array(
		'fileUpload' => './downloads/big-buck-bunny.mp4'
	));

	// The "read" callback doesn't fire after the last bits are uploaded (it could end at 99.x%), so
	// manually set the upload to 100%.
	$progress_bar->update($_100_percent);

	// Add some spacing below the progress bar.
	echo PHP_EOL . PHP_EOL;
