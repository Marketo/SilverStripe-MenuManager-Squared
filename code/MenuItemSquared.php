<?php

class MenuItemSquared extends DataExtension {

  private static $db = [
  ];

  private static $has_one = [
    'Image' => 'Image',
    'MenuItem' => 'MenuItem',
  ];
  
  private static $has_many = array(
    'MenuItems' => 'MenuItem'
  );

  public function updateCMSFields( FieldList $fields ) {
    $fields->push(new UploadField('Image', 'Image'));
    if ($this->owner->ID != null) {
      $fields->push(
          $menuItems = new GridField(
              'MenuItems',
              'Sub Menu Items',
              $this->owner->MenuItems(),
              $config = GridFieldConfig_RelationEditor::create()
          )
      );
      $config->addComponent(new GridFieldOrderableRows('Sort'));
    } 
    else {
      $fields->push(new LabelField('MenuItems', 'Save This Menu Item Before Adding Sub Menu Items'));
    }
  }
  public function onBeforeWrite() {
      if (!$this->owner->Sort) {
          $this->owner->Sort = MenuItem::get()->max('Sort') + 1;
      }
      parent::onBeforeWrite();
  }
}
