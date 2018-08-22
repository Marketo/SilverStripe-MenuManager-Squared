<?php

/**
 * Class MenuItemSquared
 *
 * @see MenuItem
 */
class MenuItemSquared extends DataExtension
{
    private static $db = [
        'Name' => 'Varchar(255)',
    ];

    private static $has_one = [
        'Image'      => 'Image',
        'ParentItem' => 'MenuItem',
    ];

    private static $has_many = [
        'ChildItems' => 'MenuItem',
    ];

    private static $summary_fields = [
        'MenuTitle'   => 'Title',
        'Page.Title'  => 'Page Title',
        'Link'        => 'Link',
        'IsNewWindow' => 'Open in New Window',
    ];

    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        if (!$this->owner->config()->disable_image) {
            $fields->push(new UploadField('Image', 'Image'));
        }

        if (!$this->owner->config()->disable_hierarchy) {
            if ($this->owner->ID != null) {
                $AllParentItems = $this->owner->getAllParentItems();
                $topMenuSet = $this->owner->TopMenuSet();
                $topMenuName = $topMenuSet->Name;

                $config = MenuSet::config();
                $depth = 1;
                if (is_array($config->$topMenuName) && isset($config->{$topMenuName}['depth'])) {
                    $depth = $config->{$topMenuName}['depth'];
                }

                if (!is_numeric($depth) || $depth < 0) {
                    $depth = 1;
                }

                if (!empty($AllParentItems) && count($AllParentItems) >= $depth) {
                    $fields->push(new LabelField('MenuItems', 'Max Sub Menu Depth Limit'));
                } else {
                    $fields->push(
                        new GridField(
                            'MenuItems',
                            'Sub Menu Items',
                            $this->owner->ChildItems(),
                            new MenuItemSquaredGridFieldConfig()
                        )
                    );
                }
            } else {
                $fields->push(new LabelField('MenuItems', 'Save This Menu Item Before Adding Sub Menu Items'));
            }
        }
    }

    /**
     * @return mixed
     */
    public function TopMenuSet()
    {
        $AllParentItems = $this->owner->getAllParentItems();
        if (!empty($AllParentItems)) {
            return end($AllParentItems)->MenuSet();
        }

        return $this->owner->MenuSet();
    }

    /**
     * @return array
     */
    public function getAllParentItems()
    {
        $WorkingItem = $this->owner;
        $ParentItems = [];

        while ($WorkingItem->ParentItemID && $WorkingItem->ParentItem() && $WorkingItem->ParentItem()->ID && !isset($ParentItems[$WorkingItem->ParentItem()->ID])) {
            $ParentItems[$WorkingItem->ID] = $WorkingItem->ParentItem();
            $WorkingItem = $ParentItems[$WorkingItem->ID];
        }

        return $ParentItems;
    }

    public function onBeforeWrite()
    {
        if (!$this->owner->Sort) {
            $this->owner->Sort = MenuItem::get()->max('Sort') + 1;
        }
        if ($this->owner->MenuTitle) {
            $this->owner->Name = $this->owner->MenuTitle;
        }
        parent::onBeforeWrite();
    }

    public function onBeforeDelete()
    {
        foreach ($this->owner->ChildItems() as $childItem) {
            $childItem->delete();
        }
        parent::onBeforeDelete();
    }

    /**
     * @return string
     */
    public static function get_user_friendly_name()
    {
        $title = Config::inst()->get(get_called_class(), 'user_friendly_title');

        return $title ?: FormField::name_to_label(get_called_class());
    }
}
