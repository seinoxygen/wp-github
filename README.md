WP Github
=========

Display users public Github profile, repositories, commits and issues.

## Usage

You can install the widgets in your sidebars from the widgets section in wordpress once the plugin ins installed and enabled.

You also can use shortcodes to display github content on your posts and pages.

## Shortcodes

Admin option will let you add a default usernamme and repository so you don't have to add it in the shortcode eachtime.

#### Profile
```html
[github-profile]
```
Argument | Example | Description
--- | --- | ---
username | `[github-profile username="seinoxygen"]` | Displays the profile box for the user seinoxygen.

#### Repository clone urls
```html
[github-clone]
```
Argument | Example | Description
--- | --- | ---
username | `[github-clone username="seinoxygen"]` | Show ssh & https url.
limit | `[github-clone username="seinoxygen" repository="wp-github"]` | From a selected repo.

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
username | `[github-commits username="seinoxygen"]` | main username
repository | `[github-commits username="seinoxygen" repository="wp-github"]` | Lists up to 5 commits from the repository wp-github.
limit | `[github-commits username="seinoxygen" repository="wp-github" limit="10"]` | Lists up to 10 commits.

For correct listing username and repository **_are_** required.

#### Issues
```html
[github-issues]
```
Argument | Example | Description
--- | --- | ---
username | `[github-issues username="seinoxygen"]` | main username
repository | `[github-issues username="seinoxygen" repository="wp-github"]` | Lists up to 5 issues from the repository wp-github.
limit | `[github-issues username="seinoxygen" repository="wp-github" limit="10"]` | Lists up to 10 issues from the repository wp-github.


#### Issue
```html
[github-issue]
```
Argument | Example | Description
--- | --- | ---
username | `[github-issue username="seinoxygen"]` | main username
repository | `[github-issue username="seinoxygen" repository="wp-github"]` | selected repo
number | `[github-issue username="seinoxygen" repository="wp-github" number="14"]` | issue number

#### Pull request
```html
[github-pulls]
```
Argument | Example | Description
--- | --- | ---
username | `[github-pulls username="seinoxygen"]` | main username
repository | `[github-pulls username="seinoxygen" repository="wp-github"]` | from the repository wp-github.
limit | `[github-pulls username="seinoxygen" repository="wp-github" limit="10"]` | Lists up to 10 pull request from the repository wp-github.

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
username | `[github-contents username="seinoxygen"]` |Add username (except if filled in wp BO.
repository | `[github-contents username="seinoxygen" repository="wp-github"]` | pick up a repository from the user seinoxygen.
filepath | `[github-contents username="seinoxygen" repository="wp-github" filepath="wp-github.css"]` | Select a file with full path in the repository.
language | `[github-contents username="seinoxygen" repository="wp-github" filepath="wp-github.css" language="css"]` | Help JsHighlighter if exists, will generate code inside PRE and CODE tags.

#### Releases
```html
[github-releases]
```
Argument | Example | Description
--- | --- | ---
username | `[github-releases username="seinoxygen"]` |Add username (except if filled in wp BO.
repository | `[github-releases username="seinoxygen" repository="wp-github"]` | pick up a repository from the user seinoxygen.
limit | `[github-releases username="seinoxygen" repository="wp-github" limit="10"]` | Lists up to 10 releases.
 
#### Latest release
 ```html
 [github-releaseslatest ]
 ```
 Argument | Example | Description
 --- | --- | ---
 username | `[github-releaseslatest username="seinoxygen"]` |Add username (except if filled in wp BO.
 repository | `[github-releaseslatest username="seinoxygen" repository="wp-github"]` | pick up a repository from the user seinoxygen.
