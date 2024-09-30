<?php

namespace Sunnysideup\Recipes\Admin;

use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Blog\Forms\GridField\GridFieldConfigBlogPost;
use SilverStripe\Blog\Model\BlogCategory;
use SilverStripe\Blog\Model\BlogTag;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\Requirements;
use Sunnysideup\Recipes\Pages\Recipe;

/**
 * Class \Sunnysideup\Recipes\Admin\RecipeAdmin
 *
 */
class RecipeAdmin extends ModelAdmin
{
    public $showImportForm = true;

    /**
     * @config
     *
     * @var int Amount of results to show per page
     */
    private static $page_length = 999;

    private static $managed_models = [
        Recipe::class,
        BlogCategory::class,
        BlogTag::class,
    ];

    private static $url_segment = 'recipes';

    private static $menu_title = 'Recipes';

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);

        //This check is simply to ensure you are on the managed model you want adjust accordingly
        if (Recipe::class === $this->modelClass && $gridField = $form->Fields()->dataFieldByName($this->sanitiseClassName($this->modelClass))) {
            //This is just a precaution to ensure we got a GridField from dataFieldByName() which you should have
            if ($gridField instanceof GridField) {
                $gridField->setConfig(GridFieldConfigBlogPost::create(999));
                $source = $gridField->getList();
                $source = $source->sort('Created', 'Desc');
                $gridField->setList($source);
            }
        }
        Requirements::customCSS('
            body {
                overflow: hidden;
            }
        ');

        return $form;
    }

    // public function getList()
    // {
    //     $list = parent::getList();
    //     return $list;
    // }
    //
    // public function getEditForm($id = null, $fields = null)
    // {
    //     $form = parent::getEditForm($id, $fields);
    //
    //     return $form;
    // }
    //
    // /**
    //  * @param DataObject $record
    //  *
    //  * @return Form
    //  */
    // public function oneItemForm($record)
    // {
    //     $form = LeftAndMain::getEditForm($record);
    //     return $form;
    // }
}
