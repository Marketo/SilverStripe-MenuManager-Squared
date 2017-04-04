# SilverStripe Menu Manager Squared #

## Installation ##

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

Add MenuSets to your yaml file with max depth. 

The depth variable demarcates the allowed ChildItems of MenuItems of a particular MenuSet.

A MenuSet with depth 0 can only have MenuItems, a depth of 1 indicates that this MenuSet’s first MenuItems can have Child Items, a depth of 2 indicates that this MenuSet’s MenuItems can have Child Items that can have Child Items of their own etc.

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

MenuSet depth defaults to 1.

### Menu Item ###

Menu Manger Squared adds the following fields to MenuItem:
1. ChildItems
2. Image

Separators can be identified by the class MenuItem_Separator

### Usage in template ###
~~~
<% loop $MenuSet('YourMenuName').MenuItems %>
    <a href="$Link" class="$LinkingMode">$MenuTitle</a>
    <ul>
        <% loop $ChildItems %>
            <% if $ClassName = 'MenuItem' %>
                <li>
                    <label>$MenuTitle</label>
                    $Image
                    <% loop $ChildItems %>
                        <p><a href="$Link" class="$LinkingMode">$MenuTitle</a></p>
                    <% end_loop %>
                </li>
            <% else_if $ClassName = 'MenuItem_Separator' %>
                <hr>
            <% end_if %>
        <% end_loop %>
    </ul>
<% end_loop %>
~~~