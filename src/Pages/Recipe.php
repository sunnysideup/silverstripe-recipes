<?php

namespace Sunnysideup\Recipes\Pages;

use SilverStripe\Assets\File;
use SilverStripe\Assets\Image;
use SilverStripe\Blog\Model\BlogCategory;
use SilverStripe\Blog\Model\BlogPost;
use SilverStripe\Blog\Model\BlogTag;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\DatetimeField;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataList;
use SilverStripe\View\ArrayData;
use Sunnysideup\PdfUpload\Forms\PDFUploadField;
use Sunnysideup\PerfectCmsImages\Forms\PerfectCmsImagesUploadField;
use Page;

/**
 * Class \Sunnysideup\Recipes\Pages\Recipe
 *
 * @property string $FeaturedVideo
 * @property bool $FeatureOnHomePage
 * @property bool $HideFeaturedImageOnEntryPage
 * @property string $ContributorTitle
 * @property string $RecipeContributorLink
 * @property int $Serves
 * @property int $PrepTimeInMinutes
 * @property int $CookingTimeInMinutes
 * @property string $ServesDescription
 * @property string $Ingredients1Header
 * @property string $Ingredients1
 * @property string $Ingredients2Header
 * @property string $Ingredients2
 * @property string $Ingredients3Header
 * @property string $Ingredients3
 * @property string $Ingredients4Header
 * @property string $Ingredients4
 * @property string $Ingredients5Header
 * @property string $Ingredients5
 * @property string $DirectionsHeader
 * @property string $CuisineType
 * @property int $GrandFeaturedHomePageImageID
 * @property int $RecipePDFID
 * @property int $FeaturedImage2ID
 * @property int $FeaturedImage3ID
 * @property int $FeaturedImage4ID
 * @property int $FeaturedImage5ID
 * @method Image GrandFeaturedHomePageImage()
 * @method File RecipePDF()
 * @method Image FeaturedImage2()
 * @method Image FeaturedImage3()
 * @method Image FeaturedImage4()
 * @method Image FeaturedImage5()
 */
class Recipe extends BlogPost
{
    private static $default_sort = '"PublishDate" IS NULL DESC, "PublishDate" DESC';

    private static $can_create = true;

    private static $hide_ancestor = BlogPost::class;

    /**
     * creates links to other objects
     * create a has_many or has_one on the other side.
     *
     * @var array
     */
    private static $table_name = 'Recipe';

    private static $db = [
        'FeaturedVideo' => 'Varchar(255)',
        'FeatureOnHomePage' => 'Boolean',
        'HideFeaturedImageOnEntryPage' => 'Boolean',
        'ContributorTitle' => 'Varchar(200)',
        'RecipeContributorLink' => 'Varchar(200)',
        'Serves' => 'Int',
        'PrepTimeInMinutes' => 'Int',
        'CookingTimeInMinutes' => 'Int',
        'ServesDescription' => 'Varchar(200)',
        'Ingredients1Header' => 'Varchar',
        'Ingredients1' => 'Text',
        'Ingredients2Header' => 'Varchar',
        'Ingredients2' => 'Text',
        'Ingredients3Header' => 'Varchar',
        'Ingredients3' => 'Text',
        'Ingredients4Header' => 'Varchar',
        'Ingredients4' => 'Text',
        'Ingredients5Header' => 'Varchar',
        'Ingredients5' => 'Text',
        'DirectionsHeader' => 'Varchar',
        'CuisineType' => 'Varchar(200)',
    ];

    /**
     * @var bool
     */
    private static $defaults = [
        'Ingredients1Header' => 'Ingredients',
        'DirectionsHeader' => 'Directions',
    ];

    /**
     * creates links to other objects
     * create a has_many or has_one on the other side.
     *
     * @var array
     */
    private static $has_one = [
        'GrandFeaturedHomePageImage' => Image::class,
        'RecipePDF' => File::class,
        'FeaturedImage2' => Image::class,
        'FeaturedImage3' => Image::class,
        'FeaturedImage4' => Image::class,
        'FeaturedImage5' => Image::class,
    ];

    private static $owns = [
        'GrandFeaturedHomePageImage',
        'RecipePDF',
        'FeaturedImage2',
        'FeaturedImage3',
        'FeaturedImage4',
        'FeaturedImage5',
    ];

    /**
     * creates links to other objects
     * create a has_many or has_one on the other side.
     *
     * @var array
     */
    private static $casting = [
        'RecipesHTML' => 'HTMLText',
        'Excerpt' => 'HTMLText',
    ];

    public function getCMSFields()
    {
        //copied from blog post:

        $fields = Page::getCMSFields();
        // $fields->insertBefore(new Tab('RecipeMoreDetails', 'Recipe More Details'), 'PostOptions');
        // $fields->insertBefore(new Tab('Slideshow', 'Slideshow'), 'PostOptions');

        $fields->addFieldsToTab(
            'Root.RecipeSummary',
            [
                PerfectCMSImagesUploadField::create('FeaturedImage', 'Featured Image', )
                    ->setDescription('The main image for the recipe entry.')
                    ->selectFormattingStandard('FeaturedImage'),
                CheckboxField::create('HideFeaturedImageOnEntryPage', 'Hide Image On Post Page')
                    ->setDescription('Check this box if the featured image should only be displayed with the summary on the main recipe holder page'),
                HTMLEditorField::create('Summary', 'Recipe Summary')
                    ->setRows(20)
                    ->setDescription('
                        Summarise the entry in around 30 words...
                        If no summary is specified the first 30 words will be used from the Directions.
                '),
                HTMLEditorField::create('Content', 'Directions')
                    ->setDescription('
                        Make sure to only enter the directions for the recipe without any header.
                        Ingredients can be added at the top of this tab and all the other details can be added in the RECIPE MORE DETAILS tab.'),
            ]
        );
        $fields->addFieldsToTab(
            'Root.RecipeMoreDetails',
            [
                HeaderField::create('RecipeHeader', 'Recipe'),
                TextField::create('CuisineType', 'Cuisine Type')
                    ->setDescription('The cuisine of the recipe (eg, French or Ethiopian)'),
                TextField::create('Ingredients1Header', 'Ingredients Header')
                    ->setDescription('Usually this is simply ingredients, but if you like to enter more than one list then you can call it, for example, ingredients for filling, bun, or sauce - to distinguish it from the second ingredient list.'),
                TextareaField::create('Ingredients1', 'Ingredients')
                    ->setDescription('Separate each entry with a new line, no other formatting needed. Please note that if you have more than one list (e.g. base and topping) then you can use the RECIPE MORE DETAILS tab to enter separate lists.'),
                TextField::create('DirectionsHeader', 'Directions Header')->setDescription('This defaults to "Directions" but can be set to something else or left blank if desired.'),
                TextField::create('FeaturedVideo', 'YouTube link')
                    ->setDescription('The YouTube ID for the video, for example Hri1yBUR_CI. You can also paste the YouTube URL of the video.'),

                HeaderField::create('MyProducts', 'Related Products'),

                HeaderField::create('ContributorHeader', 'Contributor'),
                TextField::create('ContributorTitle', 'Contributor Title'),
                TextField::create('RecipeContributorLink', 'Recipe Contributor Link'),

                HeaderField::create('ServesHeader', 'Servings'),

                NumericField::create('Serves', 'Serves')
                    ->setDescription('e.g. 4')->setScale(0),
                TextField::create('ServesDescription', 'Serves Description')
                    ->setDescription('optional ... e.g. entree sized servings, serves four children, etc.... '),

                HeaderField::create('PrepTimeHeader', 'Time Required'),
                NumericField::create('PrepTimeInMinutes', 'Prep Time in Minutes')
                    ->setDescription('e.g. 60')->setScale(0),

                NumericField::create('CookingTimeInMinutes', 'Cooking Time in Minutes')
                    ->setDescription('e.g. 20')->setScale(0),

                HeaderField::create('DownloadHeader', 'Download'),
                PDFUploadField::create('RecipePDF', 'Recipe PDF'),

                HeaderField::create('IngredientListN2', 'Ingredient Lists - 2'),
                TextField::create('Ingredients2Header', 'Ingredients #2 Header')
                    ->setDescription('Optional ingredient list for second section'),
                TextareaField::create('Ingredients2', 'Ingredients #2')
                    ->setDescription('Separate each entry with a new line, no other formatting needed.'),

                HeaderField::create('IngredientListN3', 'Ingredient Lists - 3'),
                TextField::create('Ingredients3Header', 'Ingredients #3 Header')
                    ->setDescription('Optional ingredient list for third'),
                TextareaField::create('Ingredients3', 'Ingredients #3')
                    ->setDescription('Separate each entry with a new line, no other formatting needed.'),

                HeaderField::create('IngredientListN4', 'Ingredient Lists - 4'),
                TextField::create('Ingredients4Header', 'Ingredients #4 Header')
                    ->setDescription('Optional ingredient for fourth list'),
                TextareaField::create('Ingredients4', 'Ingredients #4')
                    ->setDescription('Separate each entry with a new line, no other formatting needed.'),

                HeaderField::create('IngredientListN5', 'Ingredient Lists - 5'),
                TextField::create('Ingredients5Header', 'Ingredients #5 Header')
                    ->setDescription('Optional ingredient list for fifth section'),
                TextareaField::create('Ingredients5', 'Ingredients #5')
                    ->setDescription('Separate each entry with a new line, no other formatting needed.'),
            ]
        );
        $fields->addFieldsToTab(
            'Root.TagsAndCats',
            [
                CheckboxSetField::create(
                    'Categories',
                    'Categories',
                    BlogCategory::get()->map()
                ),
                CheckboxSetField::create(
                    'Tags',
                    'Tags',
                    BlogTag::get()->map()
                ),
            ]
        );
        $fields->addFieldsToTab(
            'Root.Slideshow',
            [
                $field2 = PerfectCmsImagesUploadField::create('FeaturedImage2', 'Featured Image 2', null, 'FeaturedImage'),
                $field3 = PerfectCMSImagesUploadField::create('FeaturedImage3', 'Featured Image 3', null, 'FeaturedImage'),
                $field4 = PerfectCMSImagesUploadField::create('FeaturedImage4', 'Featured Image 4', null, 'FeaturedImage'),
                $field5 = PerfectCMSImagesUploadField::create('FeaturedImage5', 'Featured Image 5', null, 'FeaturedImage'),
            ]
        );
        $field2->getValidator()->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif']);
        $field3->getValidator()->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif']);
        $field4->getValidator()->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif']);
        $field5->getValidator()->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif']);

        $publishDate = DatetimeField::create('PublishDate', _t(__CLASS__ . '.PublishDate', 'Publish Date'));

        if ('' === $this->PublishDate) {
            $publishDate->setDescription(
                _t(
                    __CLASS__ . '.PublishDate_Description',
                    'Will be set to "now" if published without a value.'
                )
            );
        }
        // // Get categories and tags
        // $parent = $this->Parent();
        // $categories = $parent instanceof Blog
        //     ? $parent->Categories()
        //     : BlogCategory::get();
        // $tags = $parent instanceof Blog
        //     ? $parent->Tags()
        //     : BlogTag::get();

        // @todo: Reimplement the sidebar
        // $options = BlogAdminSidebar::create(
        $fields->addFieldsToTab(
            'Root.PostOptions',
            [
                $publishDate,
            ]
        );
        // )->setTitle('Post Options');
        // $options->setName('blog-admin-sidebar');
        // $fields->insertBefore($options, 'Root');

        $fields->fieldByName('Root.PostOptions')
            ->setTitle(_t(__CLASS__ . '.PostOptions', 'Post Options'))
        ;

        if ($this->exists()) {
            $dbFieldsToReview = [
                'CookingTimeInMinutes' => 'Cooking Time in Minutes',
                'PrepTimeInMinutes' => 'Prep Time in Minutes',
                'Summary' => 'Blog Entry Summary',
                'CuisineType' => 'Cuisine Type',
                'Ingredients1' => 'Ingredients',
                'Content' => 'Directions',
                'FeaturedVideo' => 'Video',
            ];

            $reviewFields = [];

            if (! $this->FeaturedImage()->exists()) {
                $reviewFields[] = LiteralField::create(
                    'FeaturedImageError',
                    '<p class="message error">A value for the <strong>Feature Image</strong> field is required.</p>'
                );
            }

            if ($this->Categories()->count() < 1) {
                $reviewFields[] = LiteralField::create(
                    'RecipeCategoryError',
                    '<p class="message warning">You have not added any <strong>Categories</strong> yet, pease add at least one category if possible.</p>'
                );
            }

            foreach ($dbFieldsToReview as $dbField => $label) {
                if (! $this->{$dbField}) {
                    $reviewFields[] = LiteralField::create(
                        $dbField . 'Warning',
                        '<p class="message warning">The <strong>' . $label . '</strong> field is recommended. Please provide a value if available.</p>'
                    );
                }
            }

            if (count($reviewFields) > 0) {
                array_unshift(
                    $reviewFields,
                    HeaderField::create('SEOReviewHeader', 'The following recomendations from schema.org can improve the SEO of this recipe.')
                );
                $fields->addFieldsToTab(
                    'Root.SEO Review',
                    $reviewFields
                );
            }
        }
        //do nothing

        //remove old toggle field

        // $fields->removeFieldFromTab('Root.Main', 'CustomSummary');
        // $fields->removeFieldFromTab('Root.Main', 'Metadata');

        //create new summary field

        // replace standard FeaturedImage CMS field with PerfectCMSImagesUploadField


        return $fields;
    }

    public function validate()
    {
        if (0 === RecipeHolder::get()->count()) {
            user_error('You need to set up a recipe holder first!');
        }
        if (0 === $this->ParentID) {
            $this->ParentID = RecipeHolder::get()->first()->ID;
        }

        return parent::validate();
    }

    public function onAfterUnpublish()
    {
        $gridFieldAction = new \Sunnysideup\GridFieldSendToBottomAction\Forms\GridField\GridFieldSendToBottomAction('Sort');
        $gridFieldAction->sendToBottomOfList(SiteTree::class, 'Sort', $this->ID);
    }

    /**
     * Returns the post excerpt.
     *
     * @param int $wordsToDisplay
     *
     * @return string
     */
    public function Excerpt($wordsToDisplay = 30)
    {
        if ($this->Summary) {
            return $this->Summary;
        }

        return parent::Excerpt($wordsToDisplay);
    }

    /**
     * return all the categories.
     *
     * @return DataList
     */
    public function MyCategories()
    {
        return BlogCategory::get()->filter(['ShowOnSite' => true]);
    }

    /**
     * @return bool
     */
    public function IsRecipe()
    {
        return $this->Parent() && $this->Parent() instanceof RecipeHolder;
    }

    /**
     * alias.
     *
     * @param int $number
     *
     * @return string (html)
     */
    public function IngredientList($number = 1)
    {
        return $this->getIngredientList($number);
    }

    /**
     * @param int $number
     *
     * @return string (html)
     */
    public function getIngredientList($number)
    {
        $number = (int) $number;
        if (! in_array($number, [1, 2, 3, 4, 5], true)) {
            user_error('need to set) a number between 1 and 5.');
        }
        //remove white space
        $field = 'Ingredients' . $number;
        $fieldTitle = 'Ingredients' . $number . 'Header';
        $string = trim((string) $this->obj($field));
        $string = strip_tags($string);
        $array = explode("\n", $string);
        $al = ArrayList::create();
        foreach ($array as $item) {
            $item = trim((string) $item);
            if ('' !== $item) {
                $al->push(new ArrayData(['Ingredient' => $item]));
            }
        }
        $arrayData = ArrayData::create(
            [
                'Title' => $this->{$fieldTitle},
                'Ingredients' => $al,
            ]
        );

        return $arrayData->RenderWith('Sunnysideup\Recipes\Includes\ComputedIngredientsList');
    }

    protected function onBeforeWrite()
    {
        parent::onBeforeWrite();
        $this->ParentID = RecipeHolder::get()->first()->ID;
        $this->IsGrandFeaturedBlogEntry = false;
        if (strlen((string) $this->GrandFeaturedHomePageIntro) > 10) {
            if ($this->GrandFeaturedHomePageImageID || $this->GrandFeatureYouTubeLink) {
                $this->IsGrandFeaturedBlogEntry = true;
            }
        }
        $this->ParentID = RecipeHolder::get()->first()->ID;
    }
    public function populateDefaults()
    {
        parent::populateDefaults();
        $this->ParentID = RecipeHolder::get()->first()?->ID ?: 0;
    }
}
