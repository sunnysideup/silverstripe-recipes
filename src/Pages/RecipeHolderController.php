<?php

namespace Sunnysideup\Recipes\Pages;

use SilverStripe\Blog\Model\BlogController;
use SilverStripe\Blog\Model\BlogTag;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\PaginatedList;

/**
 * Class \Sunnysideup\Recipes\Pages\RecipeHolderController
 *
 * @property RecipeHolder $dataRecord
 * @method RecipeHolder data()
 * @mixin RecipeHolder
 */
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
        if (!$this->isAjaxRecipeRequest()) {
            return parent::index($request);
        }
        $this->blogPosts = $this->dataRecord->getBlogPosts();

        return $this->renderAjaxRecipes();
    }

    public function category()
    {
        if (!$this->isAjaxRecipeRequest()) {
            return parent::category();
        }

        $category = $this->getCurrentCategory();

        if (null !== $category) {
            $this->blogPosts = $category->BlogPosts();

            return $this->renderAjaxRecipes();
        }

        return $this->httpError(404, 'Not Found');
    }

    /**
     * Renders the blog posts for a given tag.
     *
     * @return null|BlogTag
     */
    public function tag()
    {
        $tag = $this->getCurrentTag();

        if (null !== $tag) {
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
        )->RenderWith('Sunnysideup\Recipes\Includes\RecipeHolder_Ajax');
    }

    public function PaginatedRecipes()
    {

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: automated upgrade
  * OLD: ->sort(
  * NEW: ->sort( ...  (COMPLEX)
  * EXP: This method no longer accepts raw sql, only known field names.  If you have raw SQL then use ->orderBy
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
        $allPosts = $this->blogPosts->sort('Sort ASC') ?: new ArrayList();

        $recipes = PaginatedList::create($allPosts);

        // Set appropriate page size
        $pageSize = 99999;
        if ($this->PostsPerPage > 0) {
            $pageSize = $this->PostsPerPage;
        } else {
            $count = $allPosts->count();
            if ($count > 0) {
                $pageSize = $count;
            }
        }
        $recipes->setPageLength($pageSize);

        // Set current page
        $start = (int) $this->request->getVar($recipes->getPaginationGetVar());
        $recipes->setPageStart($start);

        return $recipes;
    }

    public function getCategoriesWithCurrent()
    {
        $list = $this->getCategories()->filter(['ShowInMenus' => true, 'ShowOnSite' => true]);
        $current = $this->getCurrentCategory();
        $id = 0;
        if($current) {
            $id = $current->ID;
        }
        $al = ArrayList::create();
        foreach($list as $item) {
            $isCurrent = $id === $item->ID;
            $item->LinkingMode = $isCurrent ? 'current' : 'link';
            $al->push($item);
        }
        return $al;
    }

    public function PaginatedRecipesByPublishDate()
    {

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: automated upgrade
  * OLD: ->sort(
  * NEW: ->sort( ...  (COMPLEX)
  * EXP: This method no longer accepts raw sql, only known field names.  If you have raw SQL then use ->orderBy
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
        $allPosts = $this->blogPosts->sort('PublishDate DESC') ?: new ArrayList();

        $recipes = PaginatedList::create($allPosts);

        // Set appropriate page size
        $pageSize = 99999;
        if ($this->PostsPerPage > 0) {
            $pageSize = $this->PostsPerPage;
        } else {
            $count = $allPosts->count();
            if ($count > 0) {
                $pageSize = $count;
            }
        }
        $recipes->setPageLength($pageSize);

        // Set current page
        $start = (int) $this->request->getVar($recipes->getPaginationGetVar());
        $recipes->setPageStart($start);

        return $recipes;
    }

    protected function isAjaxRecipeRequest()
    {
        return $this->request->isAjax() && $this->dataRecord instanceof RecipeHolder;
    }

    protected function isStandardAction()
    {
        return ('index' === $this->request->param('Action')) ||
            ('category' === $this->request->param('Action')) ||
            (!$this->request->param('Action'))
        ;
    }

    protected function isNotStandardAction()
    {
        return !$this->isStandardAction();
    }
}
