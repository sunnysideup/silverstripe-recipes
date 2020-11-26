<?php

namespace Sunnysideup\Recipes\Extensions;

use SilverStripe\Assets\Image;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBField;
use Sunnysideup\CMSNiceties\Forms\CMSNicetiesEasyRelationshipField;
use Sunnysideup\PerfectCmsImages\Forms\PerfectCmsImagesUploadField;
use Sunnysideup\Recipes\Pages\Recipe;

/**
 * Class \Sunnysideup\Recipes\Extensions\BlogCategoryExtension
 *
 * @property BlogCategory|BlogCategoryExtension $owner
 * @property bool $ShowOnSite
 * @property bool $ShowInMenus
 * @property int $MenuImageID
 * @method Image MenuImage()
 */
class BlogCategoryExtension extends DataExtension
{
    private static $db = [
        'ShowOnSite' => 'Boolean(1)',
        'ShowInMenus' => 'Boolean',
    ];

    private static $has_one = [
        'MenuImage' => Image::class,
    ];

    private static $owns = [
        'MenuImage',
    ];

    private static $casting = [
        'HasMenuImage' => 'Boolean',
    ];

    private static $searchable_fields = [
        'Title' => 'PartialMatchFilter',
        'ShowOnSite' => 'ExactMatchFilter',
        'ShowInMenus' => 'ExactMatchFilter',
    ];

    private static $default_sort = 'ShowInMenus DESC, ShowOnSite DESC, Title ASC';

    private static $summary_fields = [
        'Title' => 'Category',
        'BlogPosts.Count' => 'Recipe Count',
        'ShowOnSite.NiceAndColourfull' => 'Show on Site',
        'ShowInMenus.NiceAndColourfull' => 'Show in Menus',
        'HasMenuImage.NiceAndColourfull' => 'Has Image',
    ];

    public function getHasMenuImage()
    {
        return DBField::create_field('Boolean', $this->owner->MenuImageID);
    }

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab(
            'Root.Main',
            [
                CheckboxField::create('ShowOnSite', 'Show on Site?')
                    ->setDescription('You can have categories that are not shown on site at all (internal use only).'),
                CheckboxField::create('ShowInMenus', 'Show in Menus?')
                    ->setDescription('Is this one of the items shown on the menu?'),
                PerfectCmsImagesUploadField::create('MenuImage', 'Menu Image', null, 'MenuImage'),
            ]
        );

        $list = Recipe::get()->sort(['Title' => 'ASC']);
        $fields->addFieldsToTab(
            'Root.Recipes',
            [
                CMSNicetiesEasyRelationshipField::create($this->owner, 'BlogPosts')
                    ->setSortField('')
                    ->setLabelForField('Recipes')
                    ->setHasEditRelation(true) //defaults to TRUE
                    ->setHasUnlink(true) //defaults to TRUE
                    ->setHasDelete(false) //defaults to TRUE
                    ->setHasAdd(false) //defaults to TRUE
                    ->setHasAddExisting(true) //defaults to TRUE
                    ->setMaxItemsForCheckBoxSet(999) //defaults to 150
                    ->setDataListForCheckboxSetField($list), //defaults to 150
                // ->setDataColumns(['Title' => 'My Title'])
                // ->setSearchFields(['Title', 'Header'])
                // ->setSearchOutputFormat(''),
                // CheckboxSetField::create('BlogPosts', 'Recipes', Recipe::get()->sort('Title')->map())
            ]
        );

        return $fields;
    }

    public function MenuTitle()
    {
        return $this->getOwner()->Title;
    }

    public function onBeforeWrite()
    {
        if ($this->getOwner()->ShowInMenus) {
            $this->getOwner()->ShowOnSite = true;
        }
    }
}
