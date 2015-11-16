<div class="wrap">
<h2>WP Github</h2>

<form method="post" action="options.php">
    <?php settings_fields('wp-github'); ?>
    <?php do_settings_sections('wp-github'); ?>
    <table class="form-table">
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
				<input type="text" placeholder="Github UserName" name="wpgithub_defaultuser" value="<?php echo get_option('wpgithub_defaultuser', 'payzen'); ?>" />
				<p class="description">If you specifiy a default user, no need to add the value in the shortcodes</p>
			</td>
		</tr>
	</table>

    <?php submit_button(); ?>

</form>


<h2>Shortcodes Instructions</h2>
<p>
Embeed profile:
<pre>[github-profile username="<?php echo get_option('wpgithub_defaultuser', 'payzen'); ?>"]</pre>


<p>
	List last 10 repositories:

<pre>[github-repos username="<?php echo get_option('wpgithub_defaultuser', 'payzen'); ?>" limit="10"]</pre>
</p>

<p>
	List last 10 commits from all repositories:

<pre>[github-commits username="<?php echo get_option('wpgithub_defaultuser', 'payzen'); ?>" limit="10"]</pre>
</p>
<p>List last 10 commits from a specific repository:

<pre>[github-commits username="<?php echo get_option('wpgithub_defaultuser', 'payzen'); ?>" repository="wp-github" limit="10"]</pre>
</p>
<p>List last 10 issues from all repositories:

<pre>[github-issues username="<?php echo get_option('wpgithub_defaultuser', 'payzen'); ?>" limit="10"]</pre>
</p>
<p>List last 10 issues from a specific repository:

<pre>[github-issues username="<?php echo get_option('wpgithub_defaultuser', 'payzen'); ?>" repository="wp-github" limit="10"]</pre>

</p>
<p>List last 10 gists from a specific user:

<pre>[github-gists username="<?php echo get_option('wpgithub_defaultuser', 'payzen'); ?>" limit="10"]</pre>
</p>
<p>
	List releases from a specific repo : 
	<pre>[github-releases username="yahoo" repository="pure" limit="10"]</pre>
</p>

<p>
	List latest release from a specific repo : 
	<pre>[github-releaseslatest username="yahoo" repository="pure" ]</pre>
</p>
</div>




