<?php

namespace Sunnysideup\Recipes\Pages;

use SilverStripe\Blog\Model\BlogPostController;
use SilverStripe\ORM\ArrayList;

class RecipeController extends BlogPostController
{
    /**
     * returns all blog posts that have the same categories (any at all)
     * @return ArrayList
     */
    public function RelatedPosts()
    {
        $al = ArrayList::create();
        $mustHaveIDs = [];
        if ($this->IsRecipe()) {
            foreach ($this->Tags() as $tag) {
                $posts = $tag->BlogPosts()->exclude(['ID' => $this->ID]);
                foreach ($posts as $post) {
                    $mustHaveIDs[$post->ID] = $post->ID;
                }
            }
        }
        foreach ($this->Categories() as $category) {
            $posts = $category->BlogPosts()->exclude(['ID' => $this->ID]);
            foreach ($posts as $post) {
                if (count($mustHaveIDs) === 0 || in_array($post->ID, $mustHaveIDs, true)) {
                    $al->push($post);
                }
            }
        }
        return $al->limit(12);
    }

    public function PrepTimeInMinutesAsISO()
    {
        return $this->timeToISODuration($this->PrepTimeInMinutes . ' minutes');
    }

    public function CookingTimeInMinutesAsIso()
    {
        return $this->timeToISODuration($this->CookingTimeInMinutes . ' minutes');
    }

    /**
     * converts a string into (eg "75 minutes") into ISO 8601 duration format
     * @param string $timeString
     * @return string
     */
    public function timeToISODuration($timeString)
    {
        $time = strtotime($timeString, 0);

        $units = [
            'Y' => 365 * 24 * 3600,
            'D' => 24 * 3600,
            'H' => 3600,
            'M' => 60,
            'S' => 1,
        ];

        $str = 'P';
        $istime = false;

        foreach ($units as $unitName => &$unit) {
            $quot = intval($time / $unit);
            $time -= $quot * $unit;
            $unit = $quot;
            if ($unit > 0) {
                if (! $istime && in_array($unitName, ['H', 'M', 'S'], true)) { // There may be a better way to do this
                    $str .= 'T';
                    $istime = true;
                }
                $str .= strval($unit) . $unitName;
            }
        }

        return $str;
    }

    public function RecipesSideBarVideo()
    {
        if ($this->IsRecipe()) {
            $recipesHolder = RecipeHolder::get()->first();
            if ($recipesHolder) {
                return $recipesHolder->RecipesSideBarVideo;
            }
        }
        return false;
    }
}
