<?php

namespace Sunnysideup\Recipes\Pages;

use BlogPost;
use Tab;
use HeaderField;
use TextField;
use TextareaField;
use DropdownField;
use FeaturedProductImage;
use NumericField;
use PDFUploadField;
use LiteralField;
use HTMLEditorField;
use PerfectCMSImagesUploadField;
use CheckboxField;
use YouTubeField;
use GridFieldSendToBottomAction;
use BlogCategory;
use PicsBlogRecipes;
use ArrayList;
use ArrayData;


/**
 *
 */

class Recipe extends BlogPost
{
    private static $can_create = true;

    private static $hide_ancestor = 'BlogPost';



    /**
     * creates links to other objects
     * create a has_many or has_one on the other side
     *
     * @var array
     */

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * OLD: private static $db (case sensitive)
  * NEW: 
    private static $table_name = '[SEARCH_REPLACE_CLASS_NAME_GOES_HERE]';

    private static $db (COMPLEX)
  * EXP: Check that is class indeed extends DataObject and that it is not a data-extension!
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
    
    private static $table_name = 'Recipe';

    private static $db = [
        'FeaturedVideo' => 'Varchar(255)',
        'FeatureOnHomePage' => 'Boolean',
        'HideFeaturedImageOnEntryPage' => 'Boolean',
        'GrandFeaturedHomePageIntro' => 'HTMLText',
        'GrandFeatureYouTubeLink' => 'Varchar(100)',
        'IsGrandFeaturedPicsBlogEntry' => 'Boolean',
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
        'DirectionsHeader' => 'Directions'
    ];
    /**
     * creates links to other objects
     * create a has_many or has_one on the other side
     *
     * @var array
     */
    private static $has_one = [

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: automated upgrade
  * OLD:  => 'Image' (case sensitive)
  * NEW:  => 'Image' (COMPLEX)
  * EXP: you may want to add ownership (owns)
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
        'GrandFeaturedHomePageImage' => 'Image',
        'RecipePDF' => 'File',

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: automated upgrade
  * OLD:  => 'Image' (case sensitive)
  * NEW:  => 'Image' (COMPLEX)
  * EXP: you may want to add ownership (owns)
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
        'FeaturedImage2' => 'Image',

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: automated upgrade
  * OLD:  => 'Image' (case sensitive)
  * NEW:  => 'Image' (COMPLEX)
  * EXP: you may want to add ownership (owns)
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
        'FeaturedImage3' => 'Image',

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: automated upgrade
  * OLD:  => 'Image' (case sensitive)
  * NEW:  => 'Image' (COMPLEX)
  * EXP: you may want to add ownership (owns)
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
        'FeaturedImage4' => 'Image',

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: automated upgrade
  * OLD:  => 'Image' (case sensitive)
  * NEW:  => 'Image' (COMPLEX)
  * EXP: you may want to add ownership (owns)
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
        'FeaturedImage5' => 'Image',
        'RecommendedProduct1' => 'FeaturedProductImage',
        'RecommendedProduct2' => 'FeaturedProductImage',
        'RecommendedProduct3' => 'FeaturedProductImage'
    ];
    /**
     * creates links to other objects
     * create a has_many or has_one on the other side
     *
     * @var array
     */
    private static $casting = [
        'RecipesHTML' => 'HTMLText',
        'Excerpt' => 'HTMLText'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        if ($this->IsRecipe()) {
            $fields->insertBefore(new Tab('RecipeMoreDetails', 'Recipe More Details'), 'Translations');
            $fields->insertBefore(new Tab('Slideshow', 'Slideshow'), 'Translations');
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
                array(
                    HeaderField::create('MyProducts', 'Related Products'),
                    DropdownField::create(
                        'RecommendedProduct1ID',
                        'Recommended Pics Product 1',
                        array('' => '-- please select one --') + FeaturedProductImage::get()->map()->toArray()
                    ),
                    DropdownField::create(
                        'RecommendedProduct2ID',
                        'Recommended Pics Product 2',
                        array('' => '-- please select one --') + FeaturedProductImage::get()->map()->toArray()
                    ),
                    DropdownField::create(
                        'RecommendedProduct3ID',
                        'Recommended Pics Product 3',
                        array('' => '-- please select one --') + FeaturedProductImage::get()->map()->toArray()
                    ),

                    HeaderField::create('ContributorHeader', 'Contributor'),
                    TextField::create('ContributorTitle', 'Contributor Title'),
                    TextField::create('RecipeContributorLink', 'Recipe Contributor Link'),

                    HeaderField::create('ServesHeader', 'Servings'),

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: automated upgrade
  * OLD: NumericField::create (case sensitive)
  * NEW: NumericField::create (COMPLEX)
  * EXP: check the number of decimals required and add as ->setScale(2)
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
                    NumericField::create('Serves', 'Serves')
                        ->setRightTitle('e.g. 4'),
                    TextField::create('ServesDescription', 'Serves Description')
                        ->setRightTitle('optional ... e.g. entree sized servings, serves four children, etc.... '),

                    HeaderField::create('PrepTimeHeader', 'Time Required'),

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: automated upgrade
  * OLD: NumericField::create (case sensitive)
  * NEW: NumericField::create (COMPLEX)
  * EXP: check the number of decimals required and add as ->setScale(2)
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
                    NumericField::create('PrepTimeInMinutes', 'Prep Time in Minutes')
                        ->setRightTitle('e.g. 60'),

/**
  * ### @@@@ START REPLACEMENT @@@@ ###
  * WHY: automated upgrade
  * OLD: NumericField::create (case sensitive)
  * NEW: NumericField::create (COMPLEX)
  * EXP: check the number of decimals required and add as ->setScale(2)
  * ### @@@@ STOP REPLACEMENT @@@@ ###
  */
                    NumericField::create('CookingTimeInMinutes', 'Cooking Time in Minutes')
                        ->setRightTitle('e.g. 20'),

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

                )
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

                if(!$this->FeaturedImage()->exists()){
                    $reviewFields[] = LiteralField::create(
                        'FeaturedImageError',
                        '<p class="message error">A value for the <strong>Feature Image</strong> field is required.</p>'
                    );
                }

                if($this->Categories()->count() < 1){
                    $reviewFields[] = LiteralField::create(
                        'RecipeCategoryError',
                        '<p class="message warning">You have not added any <strong>Categories</strong> yet, pease add at least one category if possible.</p>'
                    );
                }

                foreach ($dbFieldsToReview as $dbField => $label) {
                    if(!$this->$dbField){
                        $reviewFields[] = LiteralField::create(
                            $dbField . 'Warning',
                            '<p class="message warning">The <strong>' . $label . '</strong> field is recommended. Please provide a value if available.</p>'
                        );
                    }
                }

                if(count($reviewFields)){
                    array_unshift($reviewFields, HeaderField::create('SEOReviewHeader', 'The following recomendations from schema.org can improve the SEO of this recipe.'));
                    $fields->addFieldsToTab(
                        'Root.SEO Review',
                        $reviewFields
                    );
                }
            }
        } else {
            //do nothing
        }
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
                array(
                    $uploadField2 = PerfectCMSImagesUploadField::create($name = 'FeaturedImage2', $title = 'Featured Image 2', null, 'FeaturedImage'),
                    $uploadField3 = PerfectCMSImagesUploadField::create($name = 'FeaturedImage3', $title = 'Featured Image 3', null, 'FeaturedImage'),
                    $uploadField4 = PerfectCMSImagesUploadField::create($name = 'FeaturedImage4', $title = 'Featured Image 4', null, 'FeaturedImage'),
                    $uploadField5 = PerfectCMSImagesUploadField::create($name = 'FeaturedImage5', $title = 'Featured Image 5', null, 'FeaturedImage'),
                )
            );
        $uploadField2->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));
        $uploadField3->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));
        $uploadField4->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));
        $uploadField5->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));


        // replace standard FeaturedImage CMS field with PerfectCMSImagesUploadField
        $fields->removeByName('FeaturedImage');
        $featuredImage =  PerfectCMSImagesUploadField::create(
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
        $fields->addFieldsToTab(
                'Root.Images',
                array(
                    HeaderField::create("GalleryHeading", "Add a popup Gallery to your Blog Entry")
                ),
                'Images'
            );
        $fields->fieldByName('Root.Images')->setTitle('Gallery');
        $fields->fieldByName('Root.Images.Images')->setRightTitle(
            'You can upload multiple images to this field at once.</br>
             To do this: click "From you computer", then select all the images you want to upload, then click open.</br>
             <strong>File size of each image uploaded needs to be less than 2MB.</strong>'
            );

        $contentField = HTMLEditorField::create('Content', 'Content')->setRows(17);


        if($this->IsRecipe()){
            $contentField->setTitle('Directions')->setDescription('Make sure to only enter the directions for the recipe without any header. Ingredients can be added at the top of this tab and all the other details can be added in the RECIPE MORE DETAILS tab.');
            $fields->addFieldsToTab(
                'Root.Main',
                [
                    $contentField,
                    YouTubeField::create('FeaturedVideo', 'YouTube link')->setRightTitle('The YouTube ID for the video, for example Hri1yBUR_CI. You can also paste the YouTube URL of the video.')
                ],
                'UploadDirRulesNotes'
            );
        }
        else {
            $fields->addFieldToTab('Root.Main', $contentField, 'UploadDirRulesNotes');
            $fields->insertBefore(
                'FeaturedImage',
                YouTubeField::create('FeaturedVideo', 'YouTube link')->setRightTitle(
                    'The YouTube ID for the video, for example Hri1yBUR_CI. You can also paste the YouTube URL of the video.<br>
                    <strong>If a YouTube Video is provided then the Featured Image will only be used for the summary on the main blog holder page.</strong>'
                )
            );
        }

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
        if ($this->IsRecipe()) {
            GridFieldSendToBottomAction::sendToBottomOfList('SiteTree', 'Sort', $this->ID);
        }
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
        } else {
            return parent::Excerpt($wordsToDisplay);
        }
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
        return $this->Parent() && $this->Parent() instanceof PicsBlogRecipes;
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
        if (!in_array($number, array(1,2,3,4,5))) {
            user_error('need to set a number between 1 and 5.');
        }
        //remove white space
        $field = 'Ingredients'.$number;
        $fieldTitle = 'Ingredients'.$number.'Header';
        $string =  trim($this->$field);
        $array = explode("\n", $string);
        $al = ArrayList::create();
        foreach ($array as $item) {
            $item = trim($item);
            if ($item) {
                $al->push(new ArrayData(array('Ingredient' => $item)));
            }
        }
        $arrayData = ArrayData::create(
            array(
                'Title' => $this->$fieldTitle,
                'Ingredients' => $al
            )
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

