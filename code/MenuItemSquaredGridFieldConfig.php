<?php

/**
 * Class MenuItemSquaredGridFieldConfig
 */
class MenuItemSquaredGridFieldConfig extends GridFieldConfig_RecordEditor
{
    /**
     * @param int $itemsPerPage
     */
    public function __construct($itemsPerPage = 25)
    {
        parent::__construct($itemsPerPage);

        $this->removeComponentsByType('GridFieldAddNewButton');

        $this->addComponent(new GridFieldOrderableRows('Sort'));
        $multiClass = new GridFieldAddNewMultiClass();
        $classes = ClassInfo::subclassesFor('MenuItem');
        $multiClass->setClasses($classes);

        $this->addComponent($multiClass);
    }
}
