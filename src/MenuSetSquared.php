<?php

namespace Marketo\MenuManagerSquared;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldAddNewMultiClass;

class MenuSetSquared extends DataExtension
{

    public function updateCMSFields(FieldList $fields)
    {
        $menuItem = $fields->dataFieldByName('MenuItems');

        if ($menuItem instanceof GridField) {
            $menuItemConfig = $menuItem->getConfig();
            $menuItemConfig->removeComponentsByType('GridFieldAddNewButton');

            $multiClass = new GridFieldAddNewMultiClass();
            $classes = ClassInfo::subclassesFor('MenuItem');

            $multiClass->setClasses($classes);
            $menuItemConfig->addComponent($multiClass);

            $menuItemConfig->removeComponentsByType('GridFieldDeleteAction');
            $menuItemConfig->addComponent(new GridFieldDeleteAction());
        }
    }

}
