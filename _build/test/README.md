# Running phpunit tests
To execute tests there needs to be an existing modx database set up for the tests to be run in. It is best to copy an existing installation to a new database.

Copy `_build/test/properties.sample.inc.php` to `_build/test/properties.inc.php` and update:
* `$properties['config_key'] = 'test` to be the config key of the existing modx installation (default is `config`)
* `$properties['mysql_string_dsn_test']= 'mysql:host=localhost;dbname=revo_test;charset=utf8';`  set the host and dbname to the new database created to run tests in
* `$properties['mysql_string_username']= '';` database username
* `$properties['mysql_string_password']= '';` database password

From the commandline at the root of the project, run `composer run-script phpunit`
