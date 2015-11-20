WP Github
=========

Display users public Github profile, repositories, commits and issues.

## Usage

You can install the widgets in your sidebars from the widgets section in wordpress once the plugin ins installed and enabled.

You also can use shortcodes to display github content on your posts and pages.

## Shortcodes

#### Profile
```html
[github-profile]
```
Argument | Example | Description
--- | --- | ---
username | `[github-profile username="seinoxygen"]` | Displays the profile box for the user seinoxygen.

#### Repositories
```html
[github-repos]
```
Argument | Example | Description
--- | --- | ---
username | `[github-repos username="seinoxygen"]` | Lists up to 5 repositories from the user seinoxygen.
limit | `[github-repos username="seinoxygen" limit="10"]` | Lists up to 10 repositories from the user seinoxygen.

#### Commits
```html
[github-commits]
```
Argument | Example | Description
--- | --- | ---
repository | `[github-commits username="seinoxygen" repository="wp-github"]` | Lists up to 5 commits from the repository wp-github.
limit | `[github-commits username="seinoxygen" repository="wp-github" limit="10"]` | Lists up to 10 commits from the repository wp-github.

For correct listing username and repository **_are_** required.

#### Issues
```html
[github-issues]
```
Argument | Example | Description
--- | --- | ---
repository | `[github-issues username="seinoxygen" repository="wp-github"]` | Lists up to 5 issues from the repository wp-github.
limit | `[github-issues username="seinoxygen" repository="wp-github" limit="10"]` | Lists up to 10 issues from the repository wp-github.

For correct listing username and repository **_are_** required.

#### Gists
```html
[github-gists]
```
Argument | Example | Description
--- | --- | ---
username | `[github-gists username="seinoxygen"]` | Lists up to 5 gists from the user seinoxygen.
limit | `[github-gists username="seinoxygen" limit="10"]` | Lists up to 10 gists from the user seinoxygen.

#### File content
```html
[github-contents]
```
Argument | Example | Description
--- | --- | ---
username | `[github-contents username="seinoxygen"]` | Lists up to 5 gists from the user seinoxygen.
repository | `[github-contents username="seinoxygen" repository="wp-github"]` | pick up a repository from the user seinoxygen.
filepath | `[github-contents username="seinoxygen" repository="wp-github" filepath="wp-github.css"]` | Select a file with full path in the repository.
language | `[github-contents username="seinoxygen" repository="wp-github" filepath="wp-github.css" language="css"]` | Help JsHighlighter if exists, will generate code inside <pre><code> tags.