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
	* Run this file on your web server by loading it in your browser, OR...
	* Run this file from the command line with `php cli-s3_get_urls_for_uploads.php`.
*/


/*%******************************************************************************************%*/
// SETUP

	// Enable full-blown error reporting. http://twitter.com/rasmus/status/7448448829
	error_reporting(-1);

	// Set plain text headers
	header("Content-type: text/plain; charset=utf-8");

	// Include the SDK
	require_once '../sdk.class.php';


/*%******************************************************************************************%*/
// UPLOAD FILES TO S3

	// Instantiate the AmazonS3 class
	$s3 = new AmazonS3();

	// Determine a completely unique bucket name (all lowercase)
	$bucket = 'php-sdk-getting-started-' . strtolower($s3->key) . '-' . time();

	// Create our new bucket in the US-West region.
	$create_bucket_response = $s3->create_bucket($bucket, AmazonS3::REGION_US_W1);

	// Provided that the bucket was created successfully...
	if ($create_bucket_response->isOK())
	{
		/* Since AWS follows an "eventual consistency" model, sleep and poll
		   until the bucket is available. */
		$exists = $s3->if_bucket_exists($bucket);
		while (!$exists)
		{
			// Not yet? Sleep for 1 second, then check again
			sleep(1);
			$exists = $s3->if_bucket_exists($bucket);
		}

		/*
			Get a list of files to upload. We'll use some helper functions we've
			defined below. This assumes that you have a directory called "test_files"
			that actually contains some files you want to upload.
		*/
		$list_of_files = filter_file_list(glob('./test_files/*'));

		// Prepare to hold the individual filenames
		$individual_filenames = array();

		// Loop over the list, referring to a single file at a time
		foreach ($list_of_files as $file)
		{
			// Grab only the filename part of the path
			$filename = explode(DIRECTORY_SEPARATOR, $file);
			$filename = array_pop($filename);

			// Store the filename for later use
			$individual_filenames[] = $filename;

			/* Prepare to upload the file to our new S3 bucket. Add this
			   request to a queue that we won't execute quite yet. */
			$s3->batch()->create_object($bucket, $filename, array(
				'fileUpload' => $file
			));
		}

		/* Execute our queue of batched requests. This may take a few seconds to a
		   few minutes depending on the size of the files and how fast your upload
		   speeds are. */
		$file_upload_response = $s3->batch()->send();

		/* Since a batch of requests will return multiple responses, let's
		   make sure they ALL came back successfully using `areOK()` (singular
		   responses use `isOK()`). */
		if ($file_upload_response->areOK())
		{
			// Loop through the individual filenames
			foreach ($individual_filenames as $filename)
			{
				/* Display a URL for each of the files we uploaded. Since uploads default to
				   private (you can choose to override this setting when uploading), we'll
				   pre-authenticate the file URL for the next 5 minutes. */
				echo $s3->get_object_url($bucket, $filename, '5 minutes') . PHP_EOL . PHP_EOL;
			}
		}
	}


/*%******************************************************************************************%*/
// HELPER FUNCTIONS

	// Filters the list for only files
	function filter_file_list($arr)
	{
		return array_values(array_filter(array_map('file_path', $arr)));
	}

	// Callback used by filter_file_list()
	function file_path($file)
	{
		return !is_dir($file) ? realpath($file) : null;
	}
