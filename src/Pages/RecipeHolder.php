<?php

namespace Sunnysideup\Recipes\Pages;

use Blog;
use Tab;
use Versioned;
use GridField;
use GridfieldConfig_RecordEditor;
use GridFieldSiteTreeStateExtension;
use GridFieldSortableRows;
use GridFieldSendToBottomAction;
use BlogTag;
use BlogCategory;


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
        'Recipe'
    );

    private static $hide_ancestor = "Blog";

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

        $publishedPosts = Versioned::get_by_stage('Recipe', 'Live')->filter(
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
            $childPagesConfig->removeComponentsByType('GridFieldSiteTreeState')
                ->addComponent(new GridFieldSiteTreeStateExtension())
                ->addComponent($sortable = new GridFieldSortableRows('Sort'))
                ->addComponent(new GridFieldSendToBottomAction('Sort', 'Live'));
            $sortable->setUpdateVersionedStage('Live');

            $paginator = $childPagesConfig->getComponentByType('GridFieldPaginator');
            $paginator->setItemsPerPage(200);


        }


        $config->removeComponentsByType('GridFieldDeleteAction');

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

