<?php

namespace Sunnysideup\Recipes\Pages;





use GridfieldConfig_RecordEditor;

use GridFieldSortableRows;
use GridFieldSendToBottomAction;


use Sunnysideup\Recipes\Pages\Recipe;
use SilverStripe\Blog\Model\Blog;
use SilverStripe\Forms\Tab;
use SilverStripe\Versioned\Versioned;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Lumberjack\Forms\GridFieldSiteTreeState;
use Sunnysideup\Recipes\Forms\GridField\GridFieldSiteTreeStateExtension;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Blog\Model\BlogTag;
use SilverStripe\Blog\Model\BlogCategory;



/**
 *
 */

class RecipeHolder extends Blog
{


/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * OLD: private static $db (case sensitive)
  * NEW: 
    private static $table_name = '[SEARCH_REPLACE_CLASS_NAME_GOES_HERE]';

    private static $db (COMPLEX)
  * EXP: Check that is class indeed extends DataObject and that it is not a data-extension!
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
    
    private static $table_name = 'RecipeHolder';

    private static $db = [
        'RecipesSideBarVideo' => 'Varchar(255)',
    ];

    private static $can_create = true;

    private static $can_be_root = true;

    private static $allowed_children = array(
        Recipe::class
    );

    private static $hide_ancestor = Blog::class;

    private static $singular_name = 'Recipe Holder Page';
    public function i18n_singular_name()
    {
        return self::$singular_name;
    }

    private static $plural_name = 'Recipies Holder Pages';
    public function i18n_plural_name()
    {
        return self::$plural_name;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName('Ratings');

        $fields->insertBefore(new Tab('PublishedPosts', 'Published Posts'), 'Main');

        $publishedPosts = Versioned::get_by_stage(Recipe::class, 'Live')->filter(
                [
                    'ParentID' => $this->ID
                ]
            );

        $fields->addFieldsToTab(
            'Root.PublishedPosts',
            array(
                GridField::create(
                    'PublishedBlogPosts',
                    'Published Blog Posts',
                    $publishedPosts,
                    $config = GridfieldConfig_RecordEditor::create()
                )
            )
        );

        $fieldToChange = $fields->fieldByName('Root.ChildPages.ChildPages');
        if($fieldToChange) {
            $childPagesConfig = $fieldToChange->getConfig();
            $childPagesConfig->removeComponentsByType(GridFieldSiteTreeState::class)
                ->addComponent(new GridFieldSiteTreeStateExtension())
                ->addComponent($sortable = new GridFieldSortableRows('Sort'))
                ->addComponent(new GridFieldSendToBottomAction('Sort', 'Live'));
            $sortable->setUpdateVersionedStage('Live');

            $paginator = $childPagesConfig->getComponentByType(GridFieldPaginator::class);
            $paginator->setItemsPerPage(200);


        }


        $config->removeComponentsByType(GridFieldDeleteAction::class);

        return $fields;
    }

    /**
     * overrules has_many method
     * to return ALL categories
     *
     * @return DataList
     */
    public function Tags()
    {
        return BlogTag::get();
    }

    /**
     * overrules has_many method
     * to return ALL categories
     *
     * @return DataList
     */
    public function Categories()
    {
        return BlogCategory::get();
    }

    /**
     * overrules has_many method
     * to return ALL categories
     *
     * @return DataList
     */
    public function getTags()
    {
        return BlogTag::get();
    }

    /**
     * overrules has_many method
     * to return ALL categories
     *
     * @return DataList
     */
    public function getCategories()
    {
        return BlogCategory::get();
    }

    public function onAfterWrite()
    {
        parent::onAfterWrite();
        $validIDs = [];
        $blogs = Blog::get();
        foreach ($blogs as $blog) {
            if ($blog->isPublished()) {
                $validIDs[$blog->ID] = $blog->ID;
            }
        }
    }
}

