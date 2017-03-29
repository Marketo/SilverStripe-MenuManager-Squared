<?php

class MenuItemSquared extends DataExtension
{

    private static $db = [
    ];

    private static $has_one = [
        'Image'      => 'Image',
        'ParentItem' => 'MenuItem',
    ];

    private static $has_many = [
        'ChildItems' => 'MenuItem',
    ];

    public function updateCMSFields(FieldList $fields)
    {
        if (!$this->owner->config()->disable_image) {
            $fields->push(new UploadField('Image', 'Image'));
        }

        if (!$this->owner->config()->disable_hierarchy) {
            if ($this->owner->ID != null) {
                $AllParentItems = $this->owner->getAllParentItems();
                $TopMenuSet     = $this->owner->TopMenuSet();
                $depth          = 1;

                if (
                    is_array(MenuSet::config()->{$TopMenuSet->Name}) &&
                    isset(MenuSet::config()->{$TopMenuSet->Name}['depth']) &&
                    is_numeric(MenuSet::config()->{$TopMenuSet->Name}['depth']) &&
                    MenuSet::config()->{$TopMenuSet->Name}['depth'] > 1
                ) {
                    $depth = MenuSet::config()->{$TopMenuSet->Name}['depth'];
                }

                if (!empty($AllParentItems) && count($AllParentItems) > $depth) {
                    $fields->push(new LabelField('MenuItems', 'Max Sub Menu Depth Limit'));
                } else {
                    $fields->push(
                        new GridField(
                            'MenuItems',
                            'Sub Menu Items',
                            $this->owner->ChildItems(),
                            $config = GridFieldConfig_RecordEditor::create()
                        )
                    );
                    $config->addComponent(new GridFieldOrderableRows('Sort'));
                }
            } else {
                $fields->push(new LabelField('MenuItems', 'Save This Menu Item Before Adding Sub Menu Items'));
            }
        }
    }

    public function TopMenuSet()
    {
        $AllParentItems = $this->owner->getAllParentItems();
        if (!empty($AllParentItems)) {
            return end($AllParentItems)->MenuSet();
        }
        return $this->owner->MenuSet();
    }

    public function getAllParentItems()
    {
        $WorkingItem = $this->owner;
        $ParentItems = [];

        while ($WorkingItem->ParentItemID && $WorkingItem->ParentItem() && $WorkingItem->ParentItem()->ID && !isset($ParentItems[$WorkingItem->ParentItem()->ID])) {
            $ParentItems[$WorkingItem->ID] = $WorkingItem->ParentItem();
            $WorkingItem                   = $ParentItems[$WorkingItem->ID];
        }
        return $ParentItems;
    }

    public function onBeforeWrite()
    {
        if (!$this->owner->Sort) {
            $this->owner->Sort = MenuItem::get()->max('Sort') + 1;
        }
        parent::onBeforeWrite();
    }

    public static function get_user_friendly_name() {
        $title = Config::inst()->get(get_called_class(), 'user_friendly_title');
        return $title ?: FormField::name_to_label(get_called_class());
    }

}
