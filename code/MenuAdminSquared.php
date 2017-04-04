<?php

class MenuAdminSquared extends DataExtension
{

    public function updateEditForm(CMSForm $form)
    {
        $fields = $form->Fields();
        $MenuSet = $fields->dataFieldByName('MenuSet');

        if ($MenuSet instanceof GridField) {
            $MenuSetConfig = $MenuSet->getConfig();
            $MenuSetConfig->removeComponentsByType('GridFieldAddNewButton');
        }
    }

}
