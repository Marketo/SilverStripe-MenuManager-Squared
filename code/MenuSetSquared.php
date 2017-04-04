<?php

class MenuSetSquared extends DataExtension
{

    public function updateCMSFields(FieldList $fields)
    {
        $MenuItem = $fields->dataFieldByName('MenuItems');

        if ($MenuItem instanceof GridField) {
            $MenuItemConfig = $MenuItem->getConfig();
            $MenuItemConfig->removeComponentsByType('GridFieldAddNewButton');

            $multiClass = new GridFieldAddNewMultiClass();
            $classes = ClassInfo::subclassesFor('MenuItem');
//            $classes = array_combine(
//                ClassInfo::dataClassesFor('MenuItem'),
//                array_map(
//                    function ($Class) {
//                        return $Class::get_user_friendly_name();
//                    },
//                    ClassInfo::dataClassesFor('MenuItem')
//                )
//            );
            $multiClass->setClasses($classes);
            $MenuItemConfig->addComponent($multiClass);
        }
    }

}
