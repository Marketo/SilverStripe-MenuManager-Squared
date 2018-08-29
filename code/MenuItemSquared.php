<?php

/**
 * Class MenuItemSquared
 *
 * @method HasManyList ChildItems
 * @method MenuItem ParentItem
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
        /** @var MenuItem|MenuItemSquared $owner */
        $owner = $this->owner;
        if (!$owner->config()->disable_image) {
            $fields->push(new UploadField('Image', 'Image'));
        }

        if (!$owner->config()->disable_hierarchy) {
            if ($owner->ID != null) {
                $ascendants = $owner->getAllParentItems();
                $topMenuSet = $owner->TopMenuSet();
                $topMenuName = $topMenuSet->Name;

                $config = MenuSet::config();
                $maxDepth = 1;
                if (is_array($config->$topMenuName) && isset($config->{$topMenuName}['depth'])) {
                    $maxDepth = $config->{$topMenuName}['depth'];
                }

                if (!is_numeric($maxDepth) || $maxDepth < 0) {
                    $maxDepth = 1;
                }

                $gridFieldConfig = new MenuItemSquaredGridFieldConfig();
                if (count($ascendants) >= $maxDepth) {
                    $fields->push(new LabelField('MenuItems', 'Max Depth Limit Reached, Update Config to Add Sub Menu Items'));
                    $gridFieldConfig->removeComponentsByType(GridFieldAddNewMultiClass::class);
                }
                // Keep GridField in case of import or max depth changed.
                $fields->push(new GridField(
                    'MenuItems',
                    'Sub Menu Items',
                    $owner->ChildItems(),
                    $gridFieldConfig
                ));
            } else {
                $fields->push(new LabelField('MenuItems', 'Save This Menu Item Before Adding Sub Menu Items'));
            }
        }
    }

    /**
     * @return MenuSet
     */
    public function TopMenuSet()
    {
        $ascendants = $this->owner->getAllParentItems();
        if (!empty($ascendants)) {
            return end($ascendants)->MenuSet();
        }

        return $this->owner->MenuSet();
    }

    /**
     * Create a key value pair of ChildID => Parent relationships.
     * Starts with itself, stops at circular relationships.
     *
     * @return array
     */
    public function getAllParentItems()
    {
        /** @var MenuItem|MenuItemSquared $current */
        $current = $this->owner;
        $parents = [];

        while ($current->ParentItemID && $current->ParentItem() && $current->ParentItem()->ID && !isset($parents[$current->ParentItem()->ID])) {
            $parents[$current->ID] = $current->ParentItem();
            $current = $parents[$current->ID];
        }

        return $parents;
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
        /** @var MenuItem $childItem */
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
