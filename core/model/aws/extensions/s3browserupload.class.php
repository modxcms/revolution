<?php
/*
 * Copyright 2010-2011 Amazon.com, Inc. or its affiliates. All Rights Reserved.
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


class S3BrowserUpload extends AmazonS3
{
	/**
	 * The <code>POST</code> operation adds an object to a specified bucket using HTML forms. POST is an alternate
	 * form of <code>PUT</code> that enables browser-based uploads as a way of putting objects in buckets.
	 * Parameters that are passed to <code>PUT</code> via HTTP headers are instead passed as form fields to
	 * <code>POST</code> in the <code>multipart/form-data</code> encoded message body. You must have
	 * <code>WRITE</code> access on a bucket to add an object to it. Amazon S3 never stores partial objects: if
	 * you receive a successful response, you can be confident the entire object was stored.
	 *
	 * @param string $bucket (Required) The name of the bucket to use.
	 * @param string|integer $expires (Optional) The point in time when the upload form field should expire. The default value is <code>+1 hour</code>.
	 * @param array $opt (Optional) An associative array of parameters that can have the following keys: <ul>
	 * 	<li><code>acl</code> - <code>string</code> - Optional - The access control setting to apply to the uploaded file. Accepts any of the following constants: [Allowed values: <code>AmazonS3::ACL_PRIVATE</code>, <code>AmazonS3::ACL_PUBLIC</code>, <code>AmazonS3::ACL_OPEN</code>, <code>AmazonS3::ACL_AUTH_READ</code>, <code>AmazonS3::ACL_OWNER_READ</code>, <code>AmazonS3::ACL_OWNER_FULL_CONTROL</code>].</li>
	 * 	<li><code>Cache-Control</code> - <code>string</code> - Optional - The Cache-Control HTTP header value to apply to the uploaded file. To use a <code>starts-with</code> comparison instead of an <code>equals</code> comparison, prefix the value with a <code>^</code> (carat) character.</li>
	 * 	<li><code>Content-Disposition</code> - <code>string</code> - Optional - The Content-Disposition HTTP header value to apply to the uploaded file. To use a <code>starts-with</code> comparison instead of an <code>equals</code> comparison, prefix the value with a <code>^</code> (carat) character.</li>
	 * 	<li><code>Content-Encoding</code> - <code>string</code> - Optional - The Content-Encoding HTTP header value to apply to the uploaded file. To use a <code>starts-with</code> comparison instead of an <code>equals</code> comparison, prefix the value with a <code>^</code> (carat) character.</li>
	 * 	<li><code>Content-Type</code> - <code>string</code> - Optional - The Content-Type HTTP header value to apply to the uploaded file. The default value is <code>application/octet-stream</code>. To use a <code>starts-with</code> comparison instead of an <code>equals</code> comparison, prefix the value with a <code>^</code> (carat) character.</li>
	 * 	<li><code>Expires</code> - <code>string</code> - Optional - The Expires HTTP header value to apply to the uploaded file. To use a <code>starts-with</code> comparison instead of an <code>equals</code> comparison, prefix the value with a <code>^</code> (carat) character.</li>
	 * 	<li><code>key</code> - <code>string</code> - Optional - The location where the file should be uploaded to. The default value is <code>${filename}</code>.</li>
	 * 	<li><code>success_action_redirect</code> - <code>string</code> - Optional - The URI for Amazon S3 to redirect to upon successful upload.</li>
	 * 	<li><code>success_action_status</code> - <code>integer</code> - Optional - The status code for Amazon S3 to return upon successful upload.</li>
	 * 	<li><code>x-amz-storage-class</code> - <code>string</code> - Optional - The storage setting to apply to the object. [Allowed values: <code>AmazonS3::STORAGE_STANDARD</code>, <code>AmazonS3::STORAGE_REDUCED</code>]. The default value is <code>AmazonS3::STORAGE_STANDARD</code>.</li>
	 * 	<li>x-amz-meta-*</li>
	 * </ul>
	 * @return array An array of fields that can be converted into markup.
	 * @link http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectPOST.html POST Object
	 */
	public function generate_upload_parameters($bucket, $expires = '+1 hour', $opt = null)
	{
		if (!$opt) $opt = array();

		// Policy document
		$policy = array(
			'conditions' => array(
				array('bucket' => $bucket),
			)
		);

		// Basic form
		$form = array();
		$form['form'] = array(
			'action' => $bucket . '.s3.amazonaws.com',
			'method' => 'POST',
			'enctype' => 'multipart/form-data'
		);

		// Inputs
		$form['inputs'] = array(
			'AWSAccessKeyId' => $this->key
		);

		// Expires
		if ($expires)
		{
			if (is_numeric($expires))
			{
				$expires = gmdate('j M Y, g:i a Z', (integer) $expires);
			}

			$expires = $this->util->convert_date_to_iso8601($expires);
			$policy['expiration'] = (string) $expires;
		}

		// Default values
		if (!isset($opt['key']))
		{
			$opt['key'] = '${filename}';
		}

		// Success Action Status
		if (isset($opt['success_action_status']) && !empty($opt['success_action_status']))
		{
			$form['inputs']['success_action_status'] = (string) $opt['success_action_status'];
			$policy['conditions'][] = array(
				'success_action_status' => (string) $opt['success_action_status']
			);
			unset($opt['success_action_status']);
		}

		// Other parameters
		foreach ($opt as $param_key => $param_value)
		{
			if ($param_value[0] === '^')
			{
				$form['inputs'][$param_key] = substr((string) $param_value, 1);
				$param_value = preg_replace('/\$\{(\w*)\}/', '', (string) $param_value);
				$policy['conditions'][] = array('starts-with', '$' . $param_key, (substr((string) $param_value, 1) ? substr((string) $param_value, 1) : ''));
			}
			else
			{
				$form['inputs'][$param_key] = (string) $param_value;
				$policy['conditions'][] = array(
					$param_key => (string) $param_value
				);
			}
		}

		// Add policy
		$json_policy = json_encode($policy);
		$json_policy_b64 = base64_encode($json_policy);
		$form['inputs']['policy'] = $json_policy_b64;
		$form['metadata']['json_policy'] = $json_policy;

		// Add signature
		$form['inputs']['signature'] = base64_encode(hash_hmac('sha1', $json_policy_b64, $this->secret_key, true));

		return $form;
	}


	/*%******************************************************************************************%*/
	// HELPERS

	/**
	 * Returns the protocol of the web page that this script is currently running on. This method only works
	 * correctly when run from a publicly-accessible web page.
	 */
	public static function protocol()
	{
		return (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) === 'on') ? 'https://' : 'http://';
	}

	/**
	 * Returns the domain (and port) of the web page that this script is currently running on. This method
	 * only works correctly when run from a publicly-accessible web page.
	 */
	public static function domain()
	{
		if (isset($_SERVER['SERVER_NAME']) && isset($_SERVER['SERVER_PORT']))
		{
			return $_SERVER['SERVER_NAME'] . ((integer) $_SERVER['SERVER_PORT'] === 80 ? '' : ':' . $_SERVER['SERVER_PORT']);
		}

		return null;
	}

	/**
	 * Returns the URI of the web page that this script is currently running on. This method only works
	 * correctly when run from a publicly-accessible web page.
	 */
	public static function current_uri()
	{
		if (isset($_SERVER['REQUEST_URI']))
		{
			$uri = self::protocol();
			$uri .= self::domain();
			$uri .= $_SERVER['REQUEST_URI'];
			return $uri;
		}

		return null;
	}
}
