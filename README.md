# Recently Viewed for Craft CMS 3

Track what your users view and resurface it as you please.

Easily create lists of recently viewed articles, commerce products or anything else
that you've modeled as an Element in Craft, even custom Element types.

## Contents

- [License](#license)
- [Requirements](#installation)
- [Usage](#usage)
- [Filtering](#filtering)
- [Tracking](#tracking)
- [Clearing](#clearing)
- [Support](#support)
- [Credits](#credits)

## License

This plugin requires a commercial license which can be purchased through the Craft Plugin Store.  
The license fee is $9 plus \$4 per subsequent year for updates (optional).

## Requirements

This plugin requires Craft CMS 3.0.0 or later.

## Usage

Install the plugin from the Craft Plugin Store in your site's control panel or manually using composer:

```
composer require mattgrayisok/craft-recently-viewed
```

## Filtering

Once the plugin is installed you'll have access to two additional functions on all of your fluent
element queries:

* `recentlyViewed()` - Filter by recently viewed elements only
* `orderByDateViewed()` - Order recently viewed elements by view date/time, most recent first

E.G.

```
{% set recentBlogPosts = craft.entries.recentlyViewed().orderByDateViewed().all() %}
```

```
{% set recentProducts = craft.products.recentlyViewed().orderByDateViewed().all() %}
```

## Tracking

By default any element which is linked to a specific URL will be auto tracked. I.E. if you
visit `/blog/article-slug` and Craft has been configured to automatically inject the `entry` variable
into the corresponding template the entry will be auto-tracked.

You can disable this auto tracking behaviour in the plugin settings.

You can also manually track views in your twig templates using:

`{% do craft.recentlyViewed.track($element) %}`

OR

`{% do craft.recentlyViewed.trackId($elementId) %}`

You can pass _any_ element type into the former function as long as it implements `craft\base\ElementInterface`.

## Clearing

If you would like to clear the recently viewed history for any reason you can call:

`{% do craft.recentlyViewed.clear() %}`

## Support

If you encounter any issues during the use of this plugin please let me know by:

* Creating an issue on GitHub
* Dropping me an email: matt at mattgrayisok dot com
* Finding me in the Craft Slack: @Matt
* DMing me on Twitter: @mattgrayisok

I'll respond to critical issues as quickly as I can.

## Credits

Created by [mattgrayisok](https://mattgrayisok.com/).

---

Icon made by [Smashicons](https://www.flaticon.com/authors/smashicons) from
[www.flaticon.com](https://www.flaticon.com/) is licensed by
[CC 3.0 BY](http://creativecommons.org/licenses/by/3.0/)
