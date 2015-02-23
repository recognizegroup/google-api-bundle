RecognizeGoogleApiBundle
========================

Use the google apis to find addresses, the driving distance between two locations and more!

Installation
-----------

Add the bundle to your composer.json

```json
# composer.json
{
	"repositories": [
		{
			"type": "git",
			"url":  "git@bitbucket.org:recognize/google-api-bundle.git"
		}
	],
	 "require": {
		"recognize/google-api-bundle": "dev-master",
	}
}
```

Run composer install

```sh
php ./composer.phar install
```

Enable the bundle in the kernel

	<?php
	// app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Recognize\GoogleApiBundle\RecognizeGoogleApiBundle(),
        );
    }
	
Configuration
-------------

Add configuration to config.yml.

To use the bundle, you do not need to supply a Google API key. 
However, this limits the amount of requests you can do daily to about 1000 requests.

The default locale makes sure to return addresses in this locale.
Changing this value will make sure you get 'Nederland' instead of 'Netherlands' for addresses.

```yaml
# config.yml

recognize_google_api:
    api_key: yourapikeyhere
    default_locale: en
```

Testing
--------------

To set up the testing enviroment you have to do two things

  * [Install phpunit][1]
  
  * Install the pre-commit hook


[1]:  https://phpunit.de/manual/current/en/installation.html

##Installing the pre-commit hook

Run the following command in the root directory of this project

**Linux and Mac:**
```sh
cp .hooks/pre-commit-phpunit .git/hooks/pre-commit
chmod 755 .git/hooks/pre-commit
```

**Windows:**
```sh
copy .hooks/pre-commit-phpunit .git/hooks/pre-commit
```

This will make sure the unit tests will be run before each commit.
If you want to disable the unit tests before a commit, you can use the following command

```sh
git commit --no-verify -m "Commit message!"
```