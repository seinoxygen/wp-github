=== WP Github ===
Contributors: seinoxygen
Donate link: http://www.seinoxygen.com/projects/wp-github
Tags: github, profile, repositories, commits, issues, gists, widget, shortcode
Requires at least: 3.0.1
Tested up to: 3.9
Stable tag: 1.2
License: MIT License
License URI: http://opensource.org/licenses/MIT

Display users Github public profile, repositories, commits, issues and gists.

== Description ==

WP Github provides three sidebar widgets which can be configured to display public profile, repositories, commits, issues and gists from github in the sidebar. You can have as many widgets as you want configured to display different repositories.

Currently the plugin can list:

*   Profile
*   Repositories
*   Commits
*   Issues
*   Gists

### Using CSS

The plugin uses a basic unordered lists to enumerate. In the future will be implemented a simple template system to increase the customization.

You can apply a customized style to the plugin simply uploading a file called `custom.css` in the plugin folder. It will allow you to upgrade the plugin without loss your custom style.

### Caching

The plugin caches all the data retrieved from Github every 10 minutes to avoid exceed the limit of api calls.

Since version 1.1 you can clear the cache from the plugin settings page located in the Wordpress settings menu.

### Support

If you have found a bug/issue or have a feature request please report here: https://github.com/seinoxygen/wp-github/issues

== Installation ==

1. Upload `wp-github` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add the widget through the 'Widgets' menu in WordPress or add the desired shortcode in your posts and pages.

== Frequently Asked Questions ==

= Which shortcodes are available? =

You can use the following codes to display profile, repositories, commits, issues and gists:

Embeed profile:
`[github-profile username="seinoxygen"]`
List last 10 repositories:
`[github-repos username="seinoxygen" limit="10"]`
List last 10 commits from all repositories:
`[github-commits username="seinoxygen" limit="10"]`
List last 10 commits from a specific repository:
`[github-commits username="seinoxygen" repository="wp-github" limit="10"]`
List last 10 issues from all repositories:
`[github-issues username="seinoxygen" limit="10"]`
List last 10 issues from a specific repository:
`[github-issues username="seinoxygen" repository="wp-github" limit="10"]`
List last 10 gists from a specific user:
`[github-gists username="seinoxygen" limit="10"]`

== Screenshots ==

1. Setting up the widget.
2. Repositories widget in action!
3. Repositories embedded in a page.
4. Profile shortcode.
5. Profile widget.

== Changelog ==

= 1.2 =
* New: Custom styles.
* New: List all issues and commits from all public repositories

= 1.1 =
* New: Added "clear cache" and "cache time" functionality in settings page.
* New: Added profile widget and shortcode.

= 1.0 =
* First release