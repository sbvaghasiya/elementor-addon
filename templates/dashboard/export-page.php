<?php
/**
 * @since: 1.0.0
 * @author: WiderThemes
 * @create: 29-October-2019
 */
?>
<div class="evolt-export-demos">
    <h2><?php echo esc_html__('Export', EVOLT_TEXT_DOMAIN) ?></h2>
    <form method="post" class="evolt-export-contents">
        <div class="evolt-export-name">
            <label for="evolt-ie-id">
                <h4><?php echo esc_html__('Demo Name (*) Enter demo slug (EXP : demo1, demo_1, demo-1...)', EVOLT_TEXT_DOMAIN) ?></h4>
            </label>
            <input type="text" id="evolt-ie-id" name="evolt-ie-id" placeholder="demo-slug">
        </div>
        <div class="evolt-export-link">
            <label for="evolt-ie-link">
                <h4><?php echo esc_html__('Link Demo Preview (*)', EVOLT_TEXT_DOMAIN) ?></h4>
            </label>
            <input type="text" id="evolt-ie-link" name="evolt-ie-link" placeholder="https://casethemes.net/">
        </div>
        <div class="evolt-export-options">
            <h4><?php echo esc_html__('Select data (*):', EVOLT_TEXT_DOMAIN) ?></h4>
            <div class="evolt-export-list-opt">

                <input name="evolt-ie-data-type[]" type="checkbox" value="attachment" checked="checked">
                <label><?php esc_html_e('Media', EVOLT_TEXT_DOMAIN); ?></label>

                <input name="evolt-ie-data-type[]" type="checkbox" value="widgets" checked="checked">
                <label><?php esc_html_e('Widgets', EVOLT_TEXT_DOMAIN); ?></label>

                <input name="evolt-ie-data-type[]" type="checkbox" value="options" checked="checked">
                <label><?php esc_html_e('WP Settings', EVOLT_TEXT_DOMAIN); ?></label>

                <?php if (class_exists('ReduxFramework')): ?>

                    <input name="evolt-ie-data-type[]" type="checkbox" value="settings" checked="checked">
                    <label><?php esc_html_e('Theme Options', EVOLT_TEXT_DOMAIN); ?></label>

                <?php endif; ?>

                <?php if (function_exists('cptui_get_post_type_data')): ?>

                    <input name="evolt-ie-data-type[]" type="checkbox" value="ctp_ui" checked="checked">
                    <label><?php esc_html_e('Post Type', EVOLT_TEXT_DOMAIN); ?></label>

                <?php endif; ?>

                <input name="evolt-ie-data-type[]" type="checkbox" value="content" checked="checked">
                <label><?php esc_html_e('Content', EVOLT_TEXT_DOMAIN); ?></label>

                <?php if (class_exists('RevSlider')): ?>

                    <input name="evolt-ie-data-type[]" type="checkbox" value="revslider" checked="checked">
                    <label><?php esc_html_e('Slider Revolution', EVOLT_TEXT_DOMAIN); ?></label>

                <?php endif; ?>
            </div>
        </div>
        <div class="evolt-export-btn">
            <input type="hidden" name="action" value="abcore_export">
            <button type="submit" class="button button-primary create-demo"><?php esc_html_e('Create Demo', EVOLT_TEXT_DOMAIN); ?></button>
            <button type="button" class="button button-primary download-demo"><?php esc_html_e('Download All Demos', EVOLT_TEXT_DOMAIN); ?></button>
        </div>
    </form>
</div>
