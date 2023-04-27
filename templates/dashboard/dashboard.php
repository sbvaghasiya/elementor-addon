<?php
/**
 * @since: 1.0.0
 * @author: WiderThemes
 * @create: 29-October-2019
 */
?>

<div class="evolt-dashboard">
	<div class="evolt-dashboard-inner clearfix">
		<div class="evolt-dashboard-item">
			<div class="evolt-dashboard-item-inner">
				<i class="dashicon dashicons-before dashicons-format-aside"></i>
				<h3><?php echo esc_html__('Documentation', EVOLT_TEXT_DOMAIN)?></h3>
				<p><?php echo esc_html__('Extensive documentation including up-to-date changelog.', EVOLT_TEXT_DOMAIN)?></p>
				<a href="<?php echo esc_url($docs_link) ?>" target="_blank"><?php echo esc_html__('Read Docs', EVOLT_TEXT_DOMAIN)?><i class="dashicons-before dashicons-arrow-right-alt2"></i></a>
			</div>
		</div>
		<div class="evolt-dashboard-item video-tutorial">
			<div class="evolt-dashboard-item-inner">
				<i class="dashicon dashicons-before dashicons-format-video"></i>
				<h3><?php echo esc_html__('Video Tutorials', EVOLT_TEXT_DOMAIN)?></h3>
				<p><?php echo esc_html__('The fastest and easiest way to learn more about', EVOLT_TEXT_DOMAIN)?> <?php echo esc_attr($this->theme_name) ?>.</p>
				<a href="<?php echo esc_url($video_link) ?>" target="_blank"><?php echo esc_html__('Watch Now', EVOLT_TEXT_DOMAIN)?><i class="dashicons-before dashicons-arrow-right-alt2"></i></a>
				<?php
                echo $video_link !== "#" ? '<div class="dash-deactivate"></div>':'';
                ?>

			</div>
		</div>
		<div class="evolt-dashboard-ticket">
            <a href="https://casethemes.ticksy.com/submit/" target="_blank"><?php echo esc_html__("Couldn't find what you're looking for? submit a ticket", EVOLT_TEXT_DOMAIN)?></a>
		</div>
	</div>
</div>