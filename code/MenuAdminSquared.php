<?php

/**
 * Class MenuAdminSquared
 *
 * @see MenuAdmin
 */
class MenuAdminSquared extends DataExtension
{
    private static $model_importers = [
        'MenuItem' => 'CsvBulkLoader',
    ];

    private static $managed_models = [
        'MenuItem',
    ];

    /**
     * @param CMSForm $form
     */
    public function updateEditForm(CMSForm $form)
    {
        $fields = $form->Fields();
        $menuSet = $fields->dataFieldByName('MenuSet');

        if ($menuSet instanceof GridField) {
            $menuSet->setTitle('Menus');
            $config = $menuSet->getConfig();

            $config->removeComponentsByType('GridFieldExportButton');
            $config->removeComponentsByType('GridFieldPrintButton');

            // Only remove add button if set by config.
            if (!empty(MenuSet::config()->get('default_sets'))) {
                $config->removeComponentsByType('GridFieldAddNewButton');
            }
        }

        $menuItems = $fields->dataFieldByName('MenuItem');
        if ($menuItems instanceof GridField) {
            $menuItems->setTitle('Items');
            $config = new MenuItemSquaredGridFieldConfig();
            $menuItems->setConfig($config);

            $config->removeComponentsByType('GridFieldAddNewMultiClass');

            $export = new GridFieldExportButton('buttons-before-left');
            $config->addComponent($export);

            $export->setExportColumns([
                'ID'           => 'ID',
                'ClassName'    => 'ClassName',
                'MenuTitle'    => 'MenuTitle',
                'Link'         => 'Link',
                'Sort'         => 'Sort',
                'IsNewWindow'  => 'IsNewWindow',
                'Name'         => 'Name',
                'PageID'       => 'PageID',
                'ImageID'      => 'ImageID',
                'ParentItemID' => 'ParentItemID',
            ]);
        }
    }
}
