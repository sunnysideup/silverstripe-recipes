<?php

namespace Sunnysideup\Recipes\Pages;

use SilverStripe\Blog\Model\Blog;
use SilverStripe\Blog\Model\BlogCategory;
use SilverStripe\Blog\Model\BlogTag;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use SilverStripe\Forms\Tab;
use SilverStripe\Lumberjack\Forms\GridFieldSiteTreeState;
use SilverStripe\ORM\DataList;
use SilverStripe\Versioned\Versioned;
use Sunnysideup\GridFieldSendToBottomAction\Forms\GridField\GridFieldSendToBottomAction;
use Sunnysideup\Recipes\Forms\GridField\GridFieldSiteTreeStateExtension;
use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;

/**
 * Class \Sunnysideup\Recipes\Pages\RecipeHolder
 *
 */
class RecipeHolder extends Blog
{
    private static $table_name = 'RecipeHolder';

    private static $db = [
    ];

    private static $can_be_root = true;

    private static $allowed_children = [
        Recipe::class,
    ];

    private static $hide_ancestor = Blog::class;

    private static $singular_name = 'Recipe Holder Page';

    private static $plural_name = 'Recipies Holder Pages';

    public function i18n_singular_name()
    {
        return self::$singular_name;
    }

    public function i18n_plural_name()
    {
        return self::$plural_name;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('Ratings');

        $fields->insertBefore('Main', new Tab('PublishedPosts', 'Published Posts'));

        $publishedPosts = Versioned::get_by_stage(Recipe::class, 'Live')->filter(
            [
                'ParentID' => $this->ID,
            ]
        );

        $fields->addFieldsToTab(
            'Root.PublishedPosts',
            [
                GridField::create(
                    'PublishedBlogPosts',
                    'Published Recipes',
                    $publishedPosts,
                    $config = GridFieldConfig_RecordEditor::create()
                ),
            ]
        );

        $fieldToChange = $fields->fieldByName('Root.ChildPages.ChildPages');
        if ($fieldToChange) {
            $childPagesConfig = $fieldToChange->getConfig();
            $childPagesConfig->removeComponentsByType(GridFieldSiteTreeState::class)
                ->addComponent(new GridFieldSiteTreeStateExtension())
                ->removeComponentsByType(GridFieldDeleteAction::class)
            ;
            $paginator = $childPagesConfig->getComponentByType(GridFieldPaginator::class);
            $paginator->setItemsPerPage(999);
        }

        $fields->removeByName('ChildPages');
        $fields->insertBefore('Main', new Tab('Recipes', 'Recipes'));
        $fields->addFieldsToTab(
            'Root.Recipes',
            $fieldToChange->setList($fieldToChange->getList()->sort('PublishDate DESC'))
        );

        $fields->removeByName('PublishedPosts');


        return $fields;
    }

    /**
     * overrules has_many method
     * to return ALL categories.
     *
     * @return DataList
     */
    public function Tags()
    {
        return $this->getTags();
    }

    /**
     * overrules has_many method
     * to return ALL categories.
     *
     * @return DataList
     */
    public function Categories()
    {
        return $this->getCategories();
    }

    /**
     * overrules has_many method
     * to return ALL categories.
     *
     * @return DataList
     */
    public function getTags()
    {
        return BlogTag::get()->filter(['ShowOnSite' => true]);
    }

    /**
     * overrules has_many method
     * to return ALL categories.
     *
     * @return DataList
     */
    public function getCategories()
    {
        return BlogCategory::get()->filter(['ShowOnSite' => true]);
    }

    public function onAfterWrite()
    {
        parent::onAfterWrite();
        $validIDs = [];
        /** @var DataList $blogs */
        $blogs = Blog::get();
        foreach ($blogs as $blog) {
            if ($blog->isPublished()) {
                $validIDs[$blog->ID] = $blog->ID;
            }
        }
    }

    public function canCreate($member = null, $context = [])
    {
        return ! (bool) RecipeHolder::get()->count();
    }

    protected function onBeforeWrite()
    {
        parent::onBeforeWrite();
        $this->URLSegment = 'recipes';
        $this->ParentID = 0;
    }
}
