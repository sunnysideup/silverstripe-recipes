<?php

/**
 *
 */

class RecipeHolder extends Blog
{

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
        $validIDs = array();
        $blogs = Blog::get();
        foreach ($blogs as $blog) {
            if ($blog->isPublished()) {
                $validIDs[$blog->ID] = $blog->ID;
            }
        }
    }
}

class RecipeHolder_Controller extends Blog_Controller
{


    /**
     * @var array
     */
    private static $allowed_actions = array(
        'category',
        'tag',
    );

    public function index()
    {
        if(!$this->isAjaxRecipeRequest()){
            return parent::index();
        }
        $this->blogPosts = $this->dataRecord->getBlogPosts();

        return $this->renderAjaxRecipes();
    }

    public function init()
    {
        parent::init();

    }

    public function category()
    {
        if(!$this->isAjaxRecipeRequest()){
            return parent::category();
        }

        $category = $this->getCurrentCategory();

        if ($category) {
            $this->blogPosts = $category->BlogPosts();
            return $this->renderAjaxRecipes();
        }

        return $this->httpError(404, 'Not Found');
    }

    /**
     * Renders the blog posts for a given tag.
     *
     * @return null|SS_HTTPResponse
     */
    public function tag()
    {
        $tag = $this->getCurrentTag();

        if ($tag) {
            $this->Title = "Search for: ".$tag->Title;
        }
        return parent::tag();
    }

    protected function isAjaxRecipeRequest()
    {
        return ($this->request->isAjax() && $this->dataRecord instanceof RecipeHolder);
    }

    protected function isStandardAction()
    {
        return (
            ($this->request->param("Action")=="index") ||
            ($this->request->param("Action")=="category") ||
            (! $this->request->param("Action"))
        );
    }

    protected function isNotStandardAction()
    {
        return ! $this->isStandardAction();
    }


    public function renderAjaxRecipes(){
        return $this->customise(
            [
                'PaginatedRecipes' => $this->PaginatedRecipes
            ]
        )->renderWith('RecipeHolder_Ajax');
    }

    public function PaginatedRecipes(){
        $allPosts = $this->blogPosts->sort('Sort ASC') ?: new ArrayList();

        $recipes = PaginatedList::create($allPosts);

        // Set appropriate page size
        if ($this->PostsPerPage > 0) {
            $pageSize = $this->PostsPerPage;
        } elseif ($count = $allPosts->count()) {
            $pageSize = $count;
        } else {
            $pageSize = 99999;
        }
        $recipes->setPageLength($pageSize);

        // Set current page
        $start = (int)$this->request->getVar($recipes->getPaginationGetVar());
        $recipes->setPageStart($start);

        return $recipes;
    }
}
