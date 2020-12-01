<?php

namespace Sunnysideup\Recipes\Pages;

use FeaturedProductImage;


use GridFieldSendToBottomAction;


use SilverStripe\Assets\File;


use SilverStripe\Assets\Image;
use SilverStripe\Blog\Model\BlogCategory;
use SilverStripe\Blog\Model\BlogPost;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use Sunnysideup\PdfUpload\Forms\PDFUploadField;
use Sunnysideup\PerfectCmsImages\Forms\PerfectCmsImagesUploadField;

class Recipe extends BlogPost
{
    private static $can_create = true;

    private static $hide_ancestor = BlogPost::class;

    /**
     * creates links to other objects
     * create a has_many or has_one on the other side
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
        'FeaturedVideo'  => 'Varchar(200)',
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
     * create a has_many or has_one on the other side
     *
     * @var array
     */
    private static $has_one = [
        'GrandFeaturedHomePageImage' => Image::class,
        'RecipePDF' => File::class,
        'FeaturedImage2' => Image::class,
        'FeaturedImage3' => Image::class,
        'FeaturedImage4' => Image::class,
        'FeaturedImage5' => Image::class
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
     * create a has_many or has_one on the other side
     *
     * @var array
     */
    private static $casting = [
        'RecipesHTML' => 'HTMLText',
        'Excerpt' => 'HTMLText',
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        if ($this->IsRecipe()) {
            $fields->insertBefore(new Tab('RecipeMoreDetails', 'Recipe More Details'), 'PostOptions');
            $fields->insertBefore(new Tab('Slideshow', 'Slideshow'), 'PostOptions');
            $fields->addFieldsToTab(
                'Root.Main',
                [
                    HeaderField::create('RecipeHeader', 'Recipe'),
                    TextField::create('CuisineType', 'Cuisine Type')->setRightTitle('The cuisine of the recipe (eg, French or Ethiopian)'),
                    TextField::create('Ingredients1Header', 'Ingredients Header')
                        ->setRightTitle('Usually this is simply ingredients, but if you like to enter more than one list then you can call it, for example, ingredients for filling, bun, or sauce - to distinguish it from the second ingredient list.'),
                    TextareaField::create('Ingredients1', 'Ingredients')
                        ->setRows(12)
                        ->setRightTitle('Separate each entry with a new line, no other formatting needed. Please note that if you have more than one list (e.g. base and topping) then you can use the RECIPE MORE DETAILS tab to enter separate lists.'),
                    TextField::create('DirectionsHeader', 'Directions Header')->setRightTitle('This defaults to "Directions" but can be set to something else or left blank if desired.'),
                ],
                'UploadDirRulesNotes'
            );

            $fields->addFieldsToTab(
                'Root.RecipeMoreDetails',
                [
                    HeaderField::create('MyProducts', 'Related Products'),

                    HeaderField::create('ContributorHeader', 'Contributor'),
                    TextField::create('ContributorTitle', 'Contributor Title'),
                    TextField::create('RecipeContributorLink', 'Recipe Contributor Link'),

                    HeaderField::create('ServesHeader', 'Servings'),

                    NumericField::create('Serves', 'Serves')
                        ->setRightTitle('e.g. 4')->setScale(0),
                    TextField::create('ServesDescription', 'Serves Description')
                        ->setRightTitle('optional ... e.g. entree sized servings, serves four children, etc.... '),

                    HeaderField::create('PrepTimeHeader', 'Time Required'),
                    NumericField::create('PrepTimeInMinutes', 'Prep Time in Minutes')
                        ->setRightTitle('e.g. 60')->setScale(0),

                    NumericField::create('CookingTimeInMinutes', 'Cooking Time in Minutes')
                        ->setRightTitle('e.g. 20')->setScale(0),

                    HeaderField::create('DownloadHeader', 'Download'),
                    PDFUploadField::create('RecipePDF', 'Recipe PDF'),

                    HeaderField::create('IngredientListN2', 'Ingredient Lists - 2'),
                    TextField::create('Ingredients2Header', 'Ingredients #2 Header')
                        ->setRightTitle('Optional ingredient list for second section'),
                    TextareaField::create('Ingredients2', 'Ingredients #2')
                        ->setRows(12)
                        ->setRightTitle('Separate each entry with a new line, no other formatting needed.'),

                    HeaderField::create('IngredientListN3', 'Ingredient Lists - 3'),
                    TextField::create('Ingredients3Header', 'Ingredients #3 Header')
                        ->setRightTitle('Optional ingredient list for third'),
                    TextareaField::create('Ingredients3', 'Ingredients #3')
                        ->setRows(12)
                        ->setRightTitle('Separate each entry with a new line, no other formatting needed.'),

                    HeaderField::create('IngredientListN4', 'Ingredient Lists - 4'),
                    TextField::create('Ingredients4Header', 'Ingredients #4 Header')
                        ->setRightTitle('Optional ingredient for fourth list'),
                    TextareaField::create('Ingredients4', 'Ingredients #4')
                        ->setRows(12)
                        ->setRightTitle('Separate each entry with a new line, no other formatting needed.'),

                    HeaderField::create('IngredientListN5', 'Ingredient Lists - 5'),
                    TextField::create('Ingredients5Header', 'Ingredients #5 Header')
                        ->setRightTitle('Optional ingredient list for fifth section'),
                    TextareaField::create('Ingredients5', 'Ingredients #5')
                        ->setRows(12)
                        ->setRightTitle('Separate each entry with a new line, no other formatting needed.'),

                ]
            );

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

                if (count($reviewFields)) {
                    array_unshift($reviewFields, HeaderField::create('SEOReviewHeader', 'The following recomendations from schema.org can improve the SEO of this recipe.'));
                    $fields->addFieldsToTab(
                        'Root.SEO Review',
                        $reviewFields
                    );
                }
            }
        }
        //do nothing

        //remove old toggle field

        $fields->removeFieldFromTab('Root.Main', 'CustomSummary');
        $fields->removeFieldFromTab('Root.Main', 'Metadata');

        //create new summary field
        $summary = HTMLEditorField::create('Summary', 'Blog Entry Summary')
            ->setRows(5)
            ->setDescription('Summarise the entry in around 30 words...');
        $fields->addFieldToTab('Root.Main', $summary, 'Content');
        $fields->addFieldsToTab(
            'Root.Slideshow',
            [
                $uploadField2 = PerfectCmsImagesUploadField::create($name = 'FeaturedImage2', $title = 'Featured Image 2', null, 'FeaturedImage'),
                $uploadField3 = PerfectCMSImagesUploadField::create($name = 'FeaturedImage3', $title = 'Featured Image 3', null, 'FeaturedImage'),
                $uploadField4 = PerfectCMSImagesUploadField::create($name = 'FeaturedImage4', $title = 'Featured Image 4', null, 'FeaturedImage'),
                $uploadField5 = PerfectCMSImagesUploadField::create($name = 'FeaturedImage5', $title = 'Featured Image 5', null, 'FeaturedImage'),
            ]
        );
        $uploadField2->getValidator()->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif']);
        $uploadField3->getValidator()->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif']);
        $uploadField4->getValidator()->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif']);
        $uploadField5->getValidator()->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif']);

        // replace standard FeaturedImage CMS field with PerfectCMSImagesUploadField
        $fields->removeByName('FeaturedImage');
        $featuredImage = PerfectCMSImagesUploadField::create(
            $name = 'FeaturedImage',
            $title = 'Featured Image'
        )->setRightTitle('The main image for the blog entry.');

        $fields->addFieldToTab('Root.Main', $featuredImage, 'Summary');

        $fields->addFieldToTab(
            'Root.Main',
            CheckboxField::create('HideFeaturedImageOnEntryPage', 'Hide Image On Post Page')->setDescription('Check this box if the featured image should only be displayed with the summary on the main blog holder page'),
            'Summary'
        );

        $fields->removeByName('Ratings');

        $contentField = HTMLEditorField::create('Content', 'Content')->setRows(17);

        $contentField->setTitle('Directions')->setDescription('Make sure to only enter the directions for the recipe without any header. Ingredients can be added at the top of this tab and all the other details can be added in the RECIPE MORE DETAILS tab.');
        $fields->addFieldsToTab(
            'Root.Main',
            [
                $contentField,
                TextField::create('FeaturedVideo', 'YouTube link')->setRightTitle('The YouTube ID for the video, for example Hri1yBUR_CI. You can also paste the YouTube URL of the video.'),
            ],
            'UploadDirRulesNotes'
        );


        $fields->addFieldToTab('Root.Main', HeaderField::create('SummaryHeader', 'Summary'), 'Title');
        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        $this->IsGrandFeaturedPicsBlogEntry = false;
        if (strlen($this->GrandFeaturedHomePageIntro) > 10) {
            if ($this->GrandFeaturedHomePageImageID || $this->GrandFeatureYouTubeLink) {
                $this->IsGrandFeaturedPicsBlogEntry = true;
            }
        }
    }

    public function onAfterUnpublish()
    {
        parent::onAfterUnpublish();
        //GridFieldSendToBottomAction::sendToBottomOfList(SiteTree::class, 'Sort', $this->ID);
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
     * return all the categories
     * @return DataList
     */
    public function MyCategories()
    {
        BlogCategory::get();
    }

    /**
     * @return bool
     */
    public function IsRecipe()
    {
        return $this->Parent() && $this->Parent() instanceof RecipeHolder;
    }

    /**
     * alias
     * @param int $number
     * @return html
     */
    public function IngredientList($number = 1)
    {
        return $this->getIngredientList($number);
    }

    /**
     * @param int $number
     * @return html
     */
    public function getIngredientList($number)
    {
        if (! in_array($number, [1, 2, 3, 4, 5], true)) {
            user_error('need to set a number between 1 and 5.');
        }
        //remove white space
        $field = 'Ingredients' . $number;
        $fieldTitle = 'Ingredients' . $number . 'Header';
        $string = trim($this->{$field});
        $array = explode("\n", $string);
        $al = ArrayList::create();
        foreach ($array as $item) {
            $item = trim($item);
            if ($item) {
                $al->push(new ArrayData(['Ingredient' => $item]));
            }
        }
        $arrayData = ArrayData::create(
            [
                'Title' => $this->{$fieldTitle},
                'Ingredients' => $al,
            ]
        );

        /**
         * ### @@@@ START REPLACEMENT @@@@ ###
         * WHY: automated upgrade
         * OLD: ->RenderWith( (ignore case)
         * NEW: ->RenderWith( (COMPLEX)
         * EXP: Check that the template location is still valid!
         * ### @@@@ STOP REPLACEMENT @@@@ ###
         */
        return $arrayData->RenderWith('ComputedIngredientList');
    }

    public function onAfterWrite()
    {
        //do nothing...
        //parent::onAfterWrite();
    }
}
