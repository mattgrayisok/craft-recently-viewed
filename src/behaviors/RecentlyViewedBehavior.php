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
            if(!is_array($recentIds) || sizeof($recentIds) == 0){
                $recentIds = [-1]; //Need at least one element for SQL to be valid
            }
            $this->owner->subQuery->andWhere(['elements.id' => $recentIds]);
            if ($this->orderByDateViewed) {
                if(Craft::$app->db->isMysql){
                    $idList = implode(',', array_reverse($recentIds));
                    $this->owner->subQuery->orderBy([new \yii\db\Expression(
                    'FIELD (elements.id, ' . $idList . ')'
                    )]);
                    $this->owner->query->orderBy([new \yii\db\Expression(
                    'FIELD (elements.id, ' . $idList . ')'
                    )]);
                } else {
                    //Postgres sadness - works in mysql too but less performant
                    $allCases = '';
                    $count = 1;
                    foreach(array_reverse($recentIds) as $anId){
                        $allCases .= 'WHEN elements.id=' . $anId . ' THEN ' . $count . ' ';
                        $count++;
                    }
                    var_dump($allCases);
                    $this->owner->subQuery->orderBy([new \yii\db\Expression(
                    'CASE ' . $allCases . ' END'
                    )]);
                    $this->owner->query->orderBy([new \yii\db\Expression(
                    'CASE ' . $allCases . ' END'
                    )]);
                }
            }
        }
        return true;
    }
}
