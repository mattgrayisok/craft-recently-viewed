<?php
/**
 * Recently Viewed plugin for Craft CMS 3.x
 *
 * Track your user's recently viewed entries and surface them on subsequent pages.
 *
 * @link      https://mattgrayisok.com
 * @copyright Copyright (c) 2019 Matt Gray
 */

namespace mattgrayisok\recentlyviewed\services;

use mattgrayisok\recentlyviewed\RecentlyViewed;
use mattgrayisok\recentlyviewed\elements\db\RecentlyViewedEntryQuery;
use mattgrayisok\recentlyviewed\elements\db\RecentlyViewedUserQuery;
use mattgrayisok\recentlyviewed\elements\db\RecentlyViewedAssetQuery;
use mattgrayisok\recentlyviewed\elements\db\RecentlyViewedCategoryQuery;
use mattgrayisok\recentlyviewed\elements\db\RecentlyViewedTagQuery;

use Craft;
use craft\base\Component;
use craft\elements\Entry;
use craft\elements\User;
use craft\elements\Asset;
use craft\elements\Category;
use craft\elements\Tag;
use craft\elements\db\EntryQuery;
use craft\base\ElementInterface;

/**
 * @author    Matt Gray
 * @package   RecentlyViewed
 * @since     1.0.0
 */
class Queries extends Component
{

    public function clear()
    {
        Craft::$app->getSession()->set('rv-recent-ids', []);
    }

    public function track(ElementInterface $element)
    {
        if (!is_null($element)) {
            $id = $element->getId();
            $this->trackId($id);
        }
    }

    public function trackId($id)
    {
        if (!is_null($id)) {
            $id = intval($id);
            $recentIds = Craft::$app->getSession()->get('rv-recent-ids');
            if(is_null($recentIds)){
                $recentIds = [];
            }
            if (false !== $key = array_search($id, $recentIds)) {
                unset($recentIds[$key]);
            }
            $recentIds[] = $id;
            Craft::$app->getSession()->set('rv-recent-ids', $recentIds);
        }
    }
}
