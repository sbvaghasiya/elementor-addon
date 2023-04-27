<?php
/**
 * @Template: Import demo page
 * @version: 1.0.0
 * @author: WiderThemes
 * @descriptions: Display for import demo page in Dashboard framework
 */
?>
<div class="wrap">
    <div class="evolt-dashboard">
        <header class="evolt-dashboard-header">
            <div class="evolt-dashboard-title">
                <h1><?php echo esc_attr($this->theme_name) ?></h1>
            </div>
        </header>
        <div class="evolt-import-demos">
            <h2><?php echo esc_html__('Import Demos', EVOLT_TEXT_DOMAIN) ?></h2>
        </div>
        <?php
        if (!empty($export_mode)) {
            get_template_part('core/templates/dashboard/export-page.php');
        }
        ?>
    </div>
</div>
