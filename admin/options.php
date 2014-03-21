<div class="wrap">
<h2>WP Github</h2>

<form method="post" action="options.php">
    <?php settings_fields('wp-github'); ?>
    <?php do_settings_sections('wp-github'); ?>
    <table class="form-table">
        <tr valign="top">
			<th scope="row">Cache Time</th>
			<td>
				<input type="text" name="wpgithub_cache_time" value="<?php echo get_option('wpgithub_cache_time', 600); ?>" />
				<p class="description">This value goes in seconds. For example: 600 seconds is 10 minutes.</p>
			</td>
        </tr>
    </table>
	<p></p>	
	<table class="form-table">
		<tr valign="top">
			<th scope="row">Clear Cache</th>
			<td>
				<label>
					<input type="checkbox" name="wpgithub_clear_cache" value="1" /> Delete all data retrieved and saved from Github.
				</label>
			</td>
        </tr>
    </table>
    <?php submit_button(); ?>

</form>
</div>