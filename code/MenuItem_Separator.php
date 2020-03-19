<?php

namespace Marketo\Heyday\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\GridField\GridField;
use Marketo\Heyday\Extensions\MenuItemSquaredGridFieldConfig;

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
