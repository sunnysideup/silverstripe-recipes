<?php

namespace Sunnysideup\Recipes\Pages;

use SilverStripe\Blog\Model\BlogController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\PaginatedList;

class RecipeHolderController extends BlogController
{
    /**
     * @var array
     */
    private static $allowed_actions = [
        'category',
        'tag',
    ];

    public function index(HTTPRequest $request)
    {
        if (! $this->isAjaxRecipeRequest()) {
            return parent::index($request);
        }
        $this->blogPosts = $this->dataRecord->getBlogPosts();

        return $this->renderAjaxRecipes();
    }

    public function category()
    {
        if (! $this->isAjaxRecipeRequest()) {
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
     * @return SS_HTTPResponse|null
     */
    public function tag()
    {
        $tag = $this->getCurrentTag();

        if ($tag) {
            $this->Title = 'Search for: ' . $tag->Title;
        }
        return parent::tag();
    }

    public function renderAjaxRecipes()
    {
        return $this->customise(
            [
                'PaginatedRecipes' => $this->PaginatedRecipes,
            ]
        /**
         * ### @@@@ START REPLACEMENT @@@@ ###
         * WHY: automated upgrade
         * OLD: ->RenderWith( (ignore case)
         * NEW: ->RenderWith( (COMPLEX)
         * EXP: Check that the template location is still valid!
         * ### @@@@ STOP REPLACEMENT @@@@ ###
         */
        )->RenderWith('RecipeHolder_Ajax');
    }

    public function PaginatedRecipes()
    {
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
        $start = (int) $this->request->getVar($recipes->getPaginationGetVar());
        $recipes->setPageStart($start);

        return $recipes;
    }

    protected function init()
    {
        parent::init();
    }

    protected function isAjaxRecipeRequest()
    {
        return $this->request->isAjax() && $this->dataRecord instanceof RecipeHolder;
    }

    protected function isStandardAction()
    {
        return ($this->request->param('Action') === 'index') ||
            ($this->request->param('Action') === 'category') ||
            (! $this->request->param('Action'))
        ;
    }

    protected function isNotStandardAction()
    {
        return ! $this->isStandardAction();
    }
}
