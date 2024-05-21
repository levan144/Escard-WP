<?php
/**
 * Setting Page
 * @package   Mofect On-Site Search
 * @since 1.0.0
 */

if(!isset($_GET['tab']) || $_GET['tab']!=='settings'){
	return;
}
?>
<section id="dashboard" class="mofect-admin-page">
    <form method="post" action="options.php"> 
	<?php 
		settings_fields( 'moss_option_group' );
		do_action('moss_settings');
		submit_button('Save Settings','primary','moss_save_settings');
	?>
	</form>
</section>