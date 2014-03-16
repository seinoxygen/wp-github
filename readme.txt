=== WP Github ===
Contributors: seinoxygen
Donate link: http://www.seinoxygen.com/projects/wp-github
Tags: github, repositories, commits, issues, gists, widget, shortcode
Requires at least: 3.0.1
Tested up to: 3.8.1
Stable tag: 1.0
License: MIT License
License URI: http://opensource.org/licenses/MIT

Display users Github public repositories, commits, issues and gists.

== Description ==

WP Github provides three sidebar widgets which can be configured to display public repositories, commits, issues and gists from github in the sidebar. You can have as many widgets as you want configured to display different repositories.

Currently the plugin can list:

*   Repositories
*   Commits
*   Issues
*   Gists

### Using CSS

The plugin uses a basic unordered lists to enumerate. In the future will be implemented a simple template system to increase the customization.

### Caching

The plugin caches all the data retrieved from Github every 10 minutes to avoid exceed the limit of api calls.

You can clear the cache manually deleting the files from the folder /wp-content/plugins/wp-github/cache.

In the next releases I'll add a settings panel to do this in a fancy way.

### Support

If you have found a bug/issue or have a feature request please report here: https://github.com/seinoxygen/wp-github/issues

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add the widget through the 'Widgets' menu in WordPress or add the desired shortcode in your posts and pages.

== Frequently Asked Questions ==

= Which shortcodes are available? =

You can use the following codes to display repositories, commits, issues and gists:
List last 10 repositories:
`[github-repos username="seinoxygen" limit="10"]`
List last 10 commits from a specific repository:
`[github-commits username="seinoxygen" repository="wp-github" limit="10"]`
List last 10 issues from a specific repository:
`[github-issues username="seinoxygen" repository="wp-github" limit="10"]`
List last 10 gists from a specific user:
`[github-gists username="seinoxygen" limit="10"]`

== Screenshots ==

1. Setting up the widget. repos-widget.png
2. Repositories widget in action! repos-sidebar.png
3. Repositories embedded in a page. repos-page.png

== Changelog ==

= 1.0 =
* First release