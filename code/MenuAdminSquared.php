<?php

class MenuItemSquared extends DataExtension
{

    public function updateEditForm(CMSForm $form)
    {
        $fields = $form->Fields();
        $MenuSet = $fields->dataFieldByName('MenuSet');

        if ($MenuSet instanceof GridField) {
            $MenuSetConfig = $MenuSet->getConfig();
            $MenuSetConfig->removeComponentsByType('GridFieldAddNewButton');

            $multiClass = new GridFieldAddNewMultiClass();
            $classes = array_combine(
                ClassInfo::dataClassesFor('MenuItem'),
                array_map(
                    function ($Class) {
                        return $Class::get_user_friendly_name();
                    },
                    ClassInfo::dataClassesFor('MenuItem')
                )
            );
            $multiClass->setClasses($classes);
            $MenuSetConfig->addComponent($multiClass);
        }
    }

}
