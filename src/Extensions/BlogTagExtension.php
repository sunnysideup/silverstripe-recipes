<?php

namespace Sunnysideup\Recipes\Extensions;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Core\Extension;
use Sunnysideup\CMSNiceties\Forms\CMSNicetiesEasyRelationshipField;
use Sunnysideup\Recipes\Pages\Recipe;

/**
 * Class \Sunnysideup\Recipes\Extensions\BlogTagExtension
 *
 * @property BlogTag|BlogTagExtension $owner
 * @property bool $ShowOnSite
 */
class BlogTagExtension extends Extension
{
    private static $db = [
        'ShowOnSite' => 'Boolean(1)',
    ];

    private static $default_sort = 'ShowOnSite DESC, Title ASC';

    private static $searchable_fields = [
        'Title' => 'PartialMatchFilter',
        'ShowOnSite' => 'ExactMatchFilter',
    ];

    private static $summary_fields = [
        'Title' => 'Tag',
        'BlogPosts.Count' => 'Recipe Count',
        'ShowOnSite.NiceAndColourfull' => 'Show on Site',
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab(
            'Root.Main',
            [
                CheckboxField::create('ShowOnSite', 'Show on Site'),
            ]
        );

        $list = Recipe::get()->sort('Title', 'ASC');
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
}
