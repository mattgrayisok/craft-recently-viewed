<?php
/**
 * Recently Viewed plugin for Craft CMS 3.x
 *
 * Track your user's recently viewed entries and surface them on subsequent pages.
 *
 * @link      https://mattgrayisok.com
 * @copyright Copyright (c) 2019 Matt Gray
 */

namespace mattgrayisok\recentlyviewed;

use mattgrayisok\recentlyviewed\services\Queries;
use mattgrayisok\recentlyviewed\behaviors\RecentlyViewedBehavior;
use mattgrayisok\recentlyviewed\models\Settings;


use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\twig\variables\CraftVariable;
use craft\web\View;


use yii\base\Event;
use craft\events\DefineBehaviorsEvent;
use craft\base\Element;
use craft\base\ElementInterface;
use craft\elements\db\ElementQuery;

/**
 * Class RecentlyViewed
 *
 * @author    Matt Gray
 * @package   RecentlyViewed
 * @since     1.0.0
 *
 * @property  TrackingService $tracking
 */
class RecentlyViewed extends Plugin
{

    /**
     * @var RecentlyViewed
     */
    public static $plugin;

    /**
     * @var string
     */
    public $schemaVersion = '1.0.2';

    public $hasCpSettings = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $this->registerComponentsAndServices();

        Event::on(View::class, View::EVENT_AFTER_RENDER_TEMPLATE, function(Event $e) {
            if ($this->getSettings()->autoTrack) {
                $urlManager = Craft::$app->getUrlManager();
                $matchedEntry = $urlManager->getMatchedElement();
                if (!is_null($matchedEntry) && is_a($matchedEntry, ElementInterface::class)) {
                    self::$plugin->queries->track($matchedEntry);
                }
            }
        });

        Event::on(ElementQuery::class, ElementQuery::EVENT_DEFINE_BEHAVIORS, function(DefineBehaviorsEvent $event) {
            $event->behaviors[] = RecentlyViewedBehavior::class;
        });

        Craft::info(
            Craft::t(
                'recently-viewed',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );

    }

    public function registerComponentsAndServices()
    {
        $this->setComponents([
            'queries' => Queries::class,
        ]);

        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function(Event $e) {
            /** @var CraftVariable $variable */
            $variable = $e->sender;

            // Attach a service:
            $variable->set('recentlyViewed', Queries::class);
        });
    }

    protected function createSettingsModel()
    {
        return new Settings();
    }

    protected function settingsHtml()
    {
        return \Craft::$app->getView()->renderTemplate('recently-viewed/settings', [
            'settings' => $this->getSettings()
        ]);
    }
}
