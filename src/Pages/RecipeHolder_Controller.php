<?php

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


/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * OLD:     public function init() (ignore case)
  * NEW:     protected function init() (COMPLEX)
  * EXP: Controller init functions are now protected  please check that is a controller.
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
    protected function init()
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

