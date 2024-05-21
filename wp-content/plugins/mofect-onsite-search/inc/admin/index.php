<?php
/**
 * Dashboard Page
 * @package   Mofect On-Site Search
 * @since 1.0.0
 */

do_action('moss_admin_page_header');?>
<?php 
if(!isset($_GET['tab']) || $_GET['tab']=='dashboard'):
?>
<section id="dashboard" class="mofect-admin-page">

	<div class="box">
		<input class="box-tab" id="box-recent-tab" type="radio" name="tabs" checked>
      	<label class="box-tab-lable" for="box-recent-tab"><?php esc_html_e('Recent','moss');?></label>
        
      	<input class="box-tab" id="box-popular-tab" type="radio" name="tabs">
      	<label class="box-tab-lable" for="box-popular-tab"><?php esc_html_e('Popular Keywords','moss');?></label>

		<section class="box-tab-content" id="content1">
			<div id="recent-keywords-table">
				<ul class="table">
					<li class="th"><?php esc_html_e('Keyword', 'moss');?></li>
					<li class="th"><?php esc_html_e('Status', 'moss');?></li>
					<li class="th"><?php esc_html_e('Search Volume', 'moss');?></li>
					<li class="th action"><?php esc_html_e('Action', 'moss');?></li>
				</ul>
			</div>
		</section>
		<section class="box-tab-content" id="content2">
			<div id="popular-keywords-table">
				<ul class="table">
					<li class="th"><?php esc_html_e('Keyword', 'moss');?></li>
					<li class="th"><?php esc_html_e('Status', 'moss');?></li>
					<li class="th"><?php esc_html_e('Search Volume', 'moss');?></li>
					<li class="th action"><?php esc_html_e('Action', 'moss');?></li>
				</ul>
			</div>
		</section> 
	</div>

	<div class="box col2">
	   <h2 class="title"><?php esc_html_e('Devices','moss');?>
	   </h2>
	   <canvas id="device-chart"></canvas>
	</div>

	<div class="box col2">
		<h2 class="title"><?php esc_html_e('Location','moss');?></h2>
	   	<div id="location-echart"></div>
	</div>
</section>
<?php endif;?>

<?php
	$tracked_data = MOSS_tracking::retrive_user_track_data();
	$tracked_json_data = json_encode($tracked_data);

	$tracked_recent_data = MOSS_tracking::retrive_rencent_user_track_data();
	$tracked_recent_json_data = json_encode($tracked_recent_data);
	echo "<div id='tracked-data' data-track-statistics='$tracked_json_data' data-track-recent-statistics='$tracked_recent_json_data'></div>";
?>
<?php do_action('moss_admin_page_footer');?>