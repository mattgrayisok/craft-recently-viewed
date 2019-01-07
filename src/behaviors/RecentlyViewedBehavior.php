<?php
/**
 * Recently Viewed plugin for Craft CMS 3.x
 *
 * Track your user's recently viewed entries and surface them on subsequent pages.
 *
 * @link      https://mattgrayisok.com
 * @copyright Copyright (c) 2019 Matt Gray
 */

namespace mattgrayisok\recentlyviewed\behaviors;

use yii\base\Behavior;
use craft\elements\db\ElementQuery;
use Craft;

class RecentlyViewedBehavior extends Behavior {

    public $recentlyViewed = false;
    public $orderByDateViewed = false;

    public function recentlyViewed()
    {
        $this->recentlyViewed = true;
        return $this->owner;
    }

    public function orderByDateViewed()
    {
        $this->orderByDateViewed = true;
        return $this->owner;
    }

    public function events()
    {
        return [
            ElementQuery::EVENT_BEFORE_PREPARE => 'beforePrepare',
        ];
    }

    public function beforePrepare(): bool
    {
        if($this->recentlyViewed) {
            $recentIds = Craft::$app->getSession()->get('rv-recent-ids');
            $this->owner->query->where(['elements.id' => $recentIds]);
            if ($this->orderByDateViewed) {
                $recentIds = Craft::$app->getSession()->get('rv-recent-ids');
                $this->owner->query->orderBy([new \yii\db\Expression(
                'FIELD (elements.id, ' . implode(',', array_reverse($recentIds)) . ')'
                )]);
            }
        }
        return true;
    }
}
