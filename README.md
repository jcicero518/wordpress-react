# wordpress-react
A single-page [Wordpress](https://wordpress.com/) bootstrapped theme built with [React](https://facebook.github.io/react/) and [Flux](https://facebook.github.io/flux/), routing with [react-router](https://github.com/ReactTraining/react-router/tree/master/packages/react-router), and bundling with [Webpack](https://github.com/webpack/webpack).

## Features
- No requirement to install any Wordpress plugins
- Separate `dev` and `production` builds <i>(accessed with the `?dev` URL query param)</i> (See [Examples](#examples) below)
- Maintain non-WP URL query params thoughout the app (See [Query param examples](#query-param-examples) below)
- Ability to create regular Wordpress custom templates, and include shortcodes, JS, and PHP
- Requested pages are cached
- Ability to differ cached pages from non-cached
- Ability to access pages not in the menu and maintain routing
- Ability to access pages via any permalink type (See [Notes](#notes) below)
- Dynamic `<head>` tag, `wp_head()` and `wp_footer()` hooks
- Ability install and use WP plugins regularly (See [Plugin test examples](#plugin-test-examples) below)

----

This is <b>work-in-progress.</b> Not recommended for use on a live site. Several Wordpress features are yet to be implemented.

----

## Installation

1. Download or clone this repository into your Wordpress 'themes' folder
2. Activate the theme in wp-admin
3. `cd` into this theme folder
4. Run `npm install`
5. Build with webpack:

    a. `npm run dev` to build the <b>dev</b> version

    b. `npm run prod` to build the <b>production</b> version

6. Set your WP <b>Permalinks</b> settings to <b>Post name</b>

That's all!

----

## Notes

For internal routing purposes it's recommended above to set Permalinks to Post name, but a user should still be able to access any page if they arrive to the site under other permalink types.

----

## Examples

(Use `username`/`password`: `wp` / `wp` for below links)

Example of <b>production</b> build (default):

[https://zenitht.com/wp/](https://zenitht.com/wp/)

Example of <b>dev</b> build accessed with `dev` param:

[https://zenitht.com/wp/?dev](https://zenitht.com/wp/?dev)

Example of page not in the menu:

[https://zenitht.com/wp/page-not-in-menu/](https://zenitht.com/wp/page-not-in-menu/)

Example of accessing page via Plain permalink type:

[https://zenitht.com/wp/?p=35](https://zenitht.com/wp/?p=35)

Example of page with JS alert in content in Text editing mode:

[https://zenitht.com/wp/level-1/](https://zenitht.com/wp/level-1/)

Example of [custom template](page-CustomPage1.php) page, with multiple JS tags, and PHP:

[https://zenitht.com/wp/page-using-custom-template/](https://zenitht.com/wp/page-using-custom-template/)

<b>Query param examples</b>

Example of <b>production</b> build accessed with `page_id&someParam` params, maintaining `someParam` param:

[https://zenitht.com/wp/?page_id=35&someParam=123123](https://zenitht.com/wp/?page_id=35&someParam=123123)

Example of <b>dev</b> build accessed with `dev&p` params, maintaining `dev` param:

[https://zenitht.com/wp/?p=35&dev](https://zenitht.com/wp/?p=35&dev)

Example of <b>dev</b> build accessed with `dev&page_id` params, maintaining `dev` param:

[https://zenitht.com/wp/?page_id=35&dev](https://zenitht.com/wp/?page_id=35&dev)

Example of <b>dev</b> build accessed with `page_id&dev&p&someParam` params, maintaining `dev&someParam` params:

[https://zenitht.com/wp/?page_id=35&dev&someParam=123123](https://zenitht.com/wp/?page_id=35&dev&someParam=123123)

<b>Plugin test examples</b>

Plugin test using BWS Captcha WP plugin:

[https://zenitht.com/wp/bws-captcha-shortcode-plugin-test/](https://zenitht.com/wp/bws-captcha-shortcode-plugin-test/)

Plugin test using ConvertPlug WP plugin:

[https://zenitht.com/wp/wp-plugin-test-convertplug/](https://zenitht.com/wp/wp-plugin-test-convertplug/)

Plugin test using Ninja Forms WP plugin:

[https://zenitht.com/wp/plugin-test-ninja-forms/](https://zenitht.com/wp/plugin-test-ninja-forms/)

----

## License ##

This package is licensed under MIT license. See [LICENSE](LICENSE) for details.
