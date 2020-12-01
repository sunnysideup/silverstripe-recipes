<?php

namespace Sunnysideup\Recipes\Forms\GridField;

use SilverStripe\Assets\Image;
use SilverStripe\Lumberjack\Forms\GridFieldSiteTreeState;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\ORM\FieldType\DBField;

class GridFieldSiteTreeStateExtension extends GridFieldSiteTreeState
{
    public function augmentColumns($gridField, &$columns)
    {
        // Ensure Actions always appears as the last column.
        $columns = array_merge(
            ['CMSThumbnail'],
            $columns,
            $this->MyColsCustom(),
            [
                'Created',
                'Categories',
                'Tags',
            ]
        );
        parent::augmentColumns($gridField, $columns);
    }

    public function getColumnsHandled($gridField)
    {
        return array_merge(
            [
                'CMSThumbnail',
                'Created',
                'Categories',
                'Tags',
            ],
            $this->MyColsCustom(),
            parent::getColumnsHandled($gridField)
        );
    }

    public function getColumnMetaData($gridField, $columnName)
    {
        switch ($columnName) {
            case 'CMSThumbnail':
                return [
                    'title' => 'Image',
                ];
            case 'Created':
                return [
                    'title' => 'Created',
                ];
            case 'Categories':
                return [
                    'title' => 'Categories',
                ];
            case 'Tags':
                return [
                    'title' => 'Tags',
                ];
            default:
                return parent::getColumnMetaData($gridField, $columnName);
        }
    }

    public function getColumnContent($gridField, $record, $columnName)
    {
        if ($columnName === 'CMSThumbnail') {
            $image = $record->FeaturedImage();
            if ($image && $image->exists()) {
                $image = $image->CMSThumbnail();
                return '<img src="' . $image->Link() . '" />';
            }
            return $record->FeaturedImage()->CMSThumbnail();
        } elseif ($columnName === 'Created') {
            return DBField::create_field(DBDatetime::class, $record->Created)->Ago();
        } elseif ($columnName === 'Categories') {
            $array = [];
            foreach ($record->Categories() as $tag) {
                $array[$tag->ID] = $tag->Title;
            }
            return '- ' . implode('<br />- ', $array);
        } elseif ($columnName === 'Tags') {
            $array = [];
            foreach ($record->Tags() as $tag) {
                $array[$tag->ID] = $tag->Title;
            }
            return '- ' . implode('<br />- ', $array);
        }
        return parent::getColumnContent($gridField, $record, $columnName);
    }

    protected function MyColsCustom()
    {
        return [];
    }
}
