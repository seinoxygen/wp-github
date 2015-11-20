<div class="wrap">
<h2>WP Github</h2>

<form method="post" action="options.php">
    <?php settings_fields('wp-github'); ?>
    <?php do_settings_sections('wp-github'); ?>
    <table class="form-table fixed">
        <tr valign="top">
			<th scope="row">Cache Time</th>
			<td>
				<input type="number" name="wpgithub_cache_time" value="<?php echo get_option('wpgithub_cache_time', 600); ?>" />
				<p class="description">This value goes in seconds. For example: 600 seconds is 10 minutes.</p>
			</td>
        </tr>

		<tr valign="top">
			<th scope="row">Clear Cache</th>
			<td>
				<label>
					<input type="checkbox" name="wpgithub_clear_cache" value="1" /> Delete all data retrieved and saved from Github.
				</label>
			</td>
        </tr>

		<tr valign="top">
			<th scope="row">Default Github User</th>
			<td>
				<input type="text" placeholder="Github UserName" name="wpgithub_defaultuser" value="<?php echo get_option('wpgithub_defaultuser', 'wp-github'); ?>" />
				<p class="description">If you specifiy a default user, no need to add the value in the shortcodes</p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Default Github Repo</th>
			<td>
				<input type="text" placeholder="Github Repo" name="wpgithub_defaultrepo" value="<?php echo get_option('wpgithub_defaultrepo', 'wp-github'); ?>" />
				<p class="description">If you specifiy a default repo, no need to add the value in the shortcodes</p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Syntax highlighter</th>
			<td>
				<label>
					<input type="checkbox" name="wpgithub_addPrismJs" <?php echo get_option('wpgithub_addPrismJs', 'checked'); ?> value="<?php echo get_option('wpgithub_addPrismJs', 'checked'); ?>" /> Include necessary files.<a href="http://prismjs.com" target="_blank"> Visit PrismJs website</a>
				</label>
			</td>
		</tr>

		<tr>
			<td>
				<h3><?php _e('User Authentification','wp-github'); ?></h3>
				<p>If not specified, you can get a rate limit from GITHUB.Get yours at : https://github.com/settings/developers</p>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Client ID</th>
			<td>
				<input type="text" placeholder="Client ID" name="wpgithub_clientID" value="<?php echo get_option('wpgithub_clientID', ''); ?>" />

			</td>
		</tr>
		<tr valign="top">
			<th scope="row">Client Secret</th>
			<td>
				<input type="password" placeholder="Client SECRET" name="wpgithub_clientSecret" value="<?php echo get_option('wpgithub_clientSecret', ''); ?>" />

			</td>
		</tr>

	</table>

    <?php submit_button(); ?>

</form>

<div class="postbox ">
<h3>Shortcodes Instructions</h3>
	<div class="inside">
<p>
Embeed profile:
<pre>[github-profile username="<?php echo get_option('wpgithub_defaultuser', 'wp-github'); ?>"]</pre>


<p>
	<strong>List last 10 repositories:</strong>

<pre>[github-repos username="<?php echo get_option('wpgithub_defaultuser', 'wp-github'); ?>" limit="10"]</pre>
</p>

<p>
	<strong>List last 10 commits from all repositories:</strong>

<pre>[github-commits username="<?php echo get_option('wpgithub_defaultuser', 'wp-github'); ?>" limit="10"]</pre>
</p>
<p><strong>List last 10 commits from a specific repository:</strong>

<pre>[github-commits username="<?php echo get_option('wpgithub_defaultuser', 'wp-github'); ?>" repository="<?php echo get_option('wpgithub_defaultrepo', 'wp-github'); ?>" limit="10"]</pre>
</p>
<p><strong>List last 10 issues from all repositories:</strong>

<pre>[github-issues username="<?php echo get_option('wpgithub_defaultuser', 'wp-github'); ?>" limit="10"]</pre>
</p>
<p><strong>List last 10 issues from a specific repository:</strong>

<pre>[github-issues username="<?php echo get_option('wpgithub_defaultuser', 'wp-github'); ?>" repository="<?php echo get_option('wpgithub_defaultrepo', 'wp-github'); ?>" limit="10"]</pre>

</p>
<p><strong>List last 10 gists from a specific user:</strong>

<pre>[github-gists username="<?php echo get_option('wpgithub_defaultuser', 'wp-github'); ?>" limit="10"]</pre>
</p>
<p>
	<strong>List releases from a specific repo : </strong>
	<pre>[github-releases username="<?php echo get_option('wpgithub_defaultuser', 'wp-github'); ?>" repository="<?php echo get_option('wpgithub_defaultrepo', 'wp-github'); ?>" limit="10"]</pre>
</p>

<p>
	<strong>List latest release from a specific repo : </strong>
	<pre>[github-releaseslatest username="<?php echo get_option('wpgithub_defaultuser', 'wp-github'); ?>" repository="<?php echo get_option('wpgithub_defaultrepo', 'wp-github'); ?>" ]</pre>
</p>

	<p>
		<strong>Get content from a file or a directory :</strong>
	<pre>[github-contents username="<?php echo get_option('wpgithub_defaultuser', 'wp-github'); ?>" repository="<?php echo get_option('wpgithub_defaultrepo', 'wp-github'); ?>" filepath="src/tables/css/tables.css" language="css"]</pre>
		<em>
			<ul>
				<li>the "filepath" is the path of the file you are trying to embed.<br />
					Eg : src/tables/tests/manual/tables.html</li>
				<li>language : lowerCase if intergrated with <a target="_blank" href="http://prismjs.com"> PrimJs</a></li>
			</ul>


		</em>
	</p>
</div>
</div>
</div>




