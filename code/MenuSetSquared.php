<?php

/**
 * Class MenuSetSquared
 *
 * @see MenuSet
 */
class MenuSetSquared extends DataExtension
{
    private static $singular_name = 'Menu';

    private static $plural_name = 'Menus';

    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        $menuItem = $fields->dataFieldByName('MenuItems');

        if ($menuItem instanceof GridField) {
            $menuItem->setConfig(new MenuItemSquaredGridFieldConfig());
        }
    }
}
