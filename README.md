# SilverStripe MenuManger Squared #

## Installation ##
---
### Composer ###

Installing from composer is easy,

Create or edit a composer.json file in the root of your SilverStripe project, and make sure the following is present.
~~~
{
    "require": {
        "marketo/silverstripe-menumanager-squared": "~1.0.0"
    }
}
~~~
After completing this step, navigate in Terminal or similar to the SilverStripe root directory and run `composer install` or `composer update marketo/silverstripe-menumanager-squared` depending on whether or not you have composer already in use.

## Usage ##
---
~~~
MenuSet:
  default_sets:
    - Main
    - Main2
    - Footer
  Main:
    depth: 2
  Main2:
    depth: 1
  Footer:
    depth: 1
~~~
