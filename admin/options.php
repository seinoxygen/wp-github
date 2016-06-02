<div class="wrap">
<h1>WP Github<a target="_blank" href="https://github.com/seinoxygen/wp-github" class="page-title-action">Fork this on Github</a></h1>

<form method="post" action="options.php">
    <?php settings_fields('wp-github'); ?>
    <?php do_settings_sections('wp-github'); ?>
    <table class="form-table fixed">
        <tr valign="top">
			<th scope="row"><label for="wpgithub_cache_time"><?php _e('Cache Time','wp-github'); ?></label></th>
			<td>
				<input id="wpgithub_cache_time" type="number" name="wpgithub_cache_time" value="<?php echo get_option('wpgithub_cache_time', 600); ?>" />
				<p class="description"><?php _e('This value goes in seconds. For example: 600 seconds is 10 minutes.','wp-github'); ?></p>
			</td>
        </tr>

		<tr valign="top">
			<th scope="row"><?php _e('Clear Cache','wp-github'); ?></th>
			<td>
				<label>
					<input type="checkbox" name="wpgithub_clear_cache" value="1" /> <?php _e('Delete all data retrieved and saved from Github.','wp-github'); ?>
				</label>
			</td>
        </tr>

		<tr valign="top">
			<th scope="row"><?php _e('Default Github User','wp-github'); ?></th>
			<td>
				<input type="text" placeholder="Github UserName" name="wpgithub_defaultuser" value="<?php echo get_option('wpgithub_defaultuser', 'seinoxygen'); ?>" />
				<p class="description"><?php _e('If you specifiy a default user, no need to add the value in the shortcodes','wp-github'); ?></p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e('Default Github Repo','wp-github'); ?></th>
			<td>
				<input type="text" placeholder="Github Repo" name="wpgithub_defaultrepo" value="<?php echo get_option('wpgithub_defaultrepo', 'wp-github'); ?>" />
				<p class="description"><?php _e('If you specifiy a default repo, no need to add the value in the shortcodes','wp-github'); ?></p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e('Syntax highlighter',''); ?></th>
			<td>
				<label>
					<input type="checkbox" name="wpgithub_addPrismJs" <?php echo get_option('wpgithub_addPrismJs', 'checked'); ?> value="<?php echo get_option('wpgithub_addPrismJs', 'checked'); ?>" /> <?php _e('Include necessary files.','wp-github'); ?><a href="http://prismjs.com" target="_blank"> Visit PrismJs website</a>, ( markup,css,clike, javascript, c,csharp, java, markdown, objectivec, php, python, sql )
				</label>
			</td>
		</tr>

</table>

	<h3><?php _e('User Authentification','wp-github'); ?></h3>
	<p>
		<?php _e('you can overpass the rate limit from GITHUB with an authentification, prefer an access token, get one here :','wp-github'); ?>
		<a href="https://github.com/settings/developers" target="_blank">https://github.com/settings/tokens</a>
	</p>

	<p>
		<?php _e('You can also use a client ID and Client Secret , get yours at : ','wp-github'); ?>
		<a href="https://github.com/settings/developers" target="_blank">https://github.com/settings/developers</a>
	</p>
	<table>

		<tr valign="top">
			<th scope="row"><?php _e('Personal access tokens','wp-github'); ?></th>
			<td>
				<input type="password" placeholder="XXXXXXX" name="wpgithub_access_token" value="<?php echo get_option('wpgithub_access_token', ''); ?>" />

			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php _e('Client ID', 'wp-github'); ?></th>
			<td>
				<input type="text" placeholder="Client ID" name="wpgithub_clientID" value="<?php echo get_option('wpgithub_clientID', ''); ?>" />

			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e('Client Secret','wp-github'); ?></th>
			<td>
				<input type="password" placeholder="Client SECRET" name="wpgithub_clientSecret" value="<?php echo get_option('wpgithub_clientSecret', ''); ?>" />

			</td>
		</tr>


	</table>

    <?php submit_button(); ?>

</form>

<div class="postbox ">
<h3><?php _e('Shortcodes Instructions','wp-github'); ?></h3>

  <div class="inside">
<p>
  <strong><?php _e('Embeed profile:','wp-github'); ?></strong>
<pre>[github-profile username="<?php echo get_option('wpgithub_defaultuser', 'seinoxygen'); ?>"]</pre>
</p>

    <p>
      <strong><?php _e('Embed Clone utilities for a repository','wp-github'); ?></strong>
      <pre>[github-clone username="<?php echo get_option('wpgithub_defaultuser', 'seinoxygen'); ?>" repository="<?php echo get_option('wpgithub_defaultrepo', 'wp-github'); ?>"]
    </pre>
    </p>
<p>
	<strong><?php _e('List last 10 repositories:','wp-github'); ?></strong>

<pre>[github-repos username="<?php echo get_option('wpgithub_defaultuser', 'seinoxygen'); ?>" limit="10"]</pre>
</p>

<p>
	<strong><?php _e('List last 10 commits from all repositories:','wp-github'); ?></strong>

<pre>[github-commits username="<?php echo get_option('wpgithub_defaultuser', 'seinoxygen'); ?>" repository="" limit="10"]</pre>
</p>
<p><strong><?php _e('List last 10 commits from a specific repository:','wp-github'); ?></strong>

<pre>[github-commits username="<?php echo get_option('wpgithub_defaultuser', 'seinoxygen'); ?>" repository="<?php echo get_option('wpgithub_defaultrepo', 'wp-github'); ?>" limit="10"]</pre>
</p>
<p><strong><?php _e('List last 10 issues from all repositories:','wp-github'); ?></strong>

<pre>[github-issues username="<?php echo get_option('wpgithub_defaultuser', 'seinoxygen'); ?>" repository="" limit="10"]</pre>
</p>
<p><strong><?php _e('List last 10 issues from a specific repository:','wp-github'); ?></strong>

<pre>[github-issues username="<?php echo get_option('wpgithub_defaultuser', 'seinoxygen'); ?>" repository="<?php echo get_option('wpgithub_defaultrepo', 'wp-github'); ?>" limit="10"]</pre>

</p>


	  <p><strong><?php _e('single issue from a specific repository:','wp-github'); ?></strong>

	  <pre>[github-issue username="<?php echo get_option('wpgithub_defaultuser', 'seinoxygen'); ?>" repository="<?php echo get_option('wpgithub_defaultrepo', 'wp-github'); ?>" number="14"]</pre>

	  </p>

	  <p><strong><?php _e('List last 10 pull request from a specific repository:','wp-github'); ?></strong>

	  <pre>[github-pulls username="<?php echo get_option('wpgithub_defaultuser', 'seinoxygen'); ?>" repository="<?php echo get_option('wpgithub_defaultrepo', 'wp-github'); ?>" limit="10"]</pre>

	  </p>

<p><strong><?php _e('List last 10 gists from a specific user:','wp-github'); ?></strong>

<pre>[github-gists username="<?php echo get_option('wpgithub_defaultuser', 'seinoxygen'); ?>" limit="10"]</pre>
</p>
<p>
	<strong><?php _e('List releases from a specific repo :','wp-github'); ?> </strong>
	<pre>[github-releases username="<?php echo get_option('wpgithub_defaultuser', 'seinoxygen'); ?>" repository="<?php echo get_option('wpgithub_defaultrepo', 'wp-github'); ?>" limit="10"]</pre>
</p>

<p>
	<strong><?php _e('List latest release from a specific repo :', 'wp-github'); ?> </strong>
	<pre>[github-releaseslatest username="<?php echo get_option('wpgithub_defaultuser', 'seinoxygen'); ?>" repository="<?php echo get_option('wpgithub_defaultrepo', 'wp-github'); ?>" ]</pre>
</p>

	<p>
		<strong><?php _e('Get content from a file or a directory :','wp-github'); ?></strong>
	<pre>[github-contents username="<?php echo get_option('wpgithub_defaultuser', 'seinoxygen'); ?>" repository="<?php echo get_option('wpgithub_defaultrepo', 'wp-github'); ?>" filepath="README.md" language="markdown"]</pre>
		<em>
			<ul>
				<li><?php _e('the "filepath" is the path of the file you are trying to embed.','wp-github'); ?><br />
<?php _e('Eg : src/tables/tests/manual/tables.html','wp-github'); ?></li>
				<li><?php _e('language : lowerCase if intergrated with <a target="_blank" href="http://prismjs.com"> PrimJs</a>','wp-github'); ?>
                  <br /> Languages supported = markup+css+clike+javascript+c+csharp+java+markdown+objectivec+php+python+sql
				</li>
			</ul>


		</em>
	</p>
</div>
</div>
</div>




