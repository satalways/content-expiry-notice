<?php
/**
 * Plugin Name: Content Expiry Notice
 * Plugin URI: https://github.com/satalways/content-expiry-notice.git
 * Description: Add expiration dates to your posts and pages and display customizable notices when content is approaching expiration or has expired.
 * Version: 1.0.0
 * Author: Shakeel Ahmed Siddiqi
 * Author URI: https://shakeel.pk
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: content-expiry-notice
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('CEN_VERSION', '1.0.0');
define('CEN_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CEN_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Register the settings page
 */
function cen_add_admin_menu() {
    add_options_page(
        'Content Expiry Notice Settings',
        'Content Expiry Notice',
        'manage_options',
        'content-expiry-notice',
        'cen_options_page'
    );
}
add_action('admin_menu', 'cen_add_admin_menu');

/**
 * Register settings
 */
function cen_settings_init() {
    register_setting('cen_settings_group', 'cen_settings');

    add_settings_section(
        'cen_settings_section',
        __('Content Expiry Notice Settings', 'content-expiry-notice'),
        'cen_settings_section_callback',
        'content-expiry-notice'
    );

    add_settings_field(
        'cen_post_types',
        __('Post Types', 'content-expiry-notice'),
        'cen_post_types_render',
        'content-expiry-notice',
        'cen_settings_section'
    );

    add_settings_field(
        'cen_notice_days_before',
        __('Days Before Expiration', 'content-expiry-notice'),
        'cen_notice_days_before_render',
        'content-expiry-notice',
        'cen_settings_section'
    );

    add_settings_field(
        'cen_notice_position',
        __('Notice Position', 'content-expiry-notice'),
        'cen_notice_position_render',
        'content-expiry-notice',
        'cen_settings_section'
    );

    add_settings_field(
        'cen_notice_style',
        __('Notice Style', 'content-expiry-notice'),
        'cen_notice_style_render',
        'content-expiry-notice',
        'cen_settings_section'
    );

    add_settings_field(
        'cen_upcoming_notice_text',
        __('Upcoming Expiry Notice Text', 'content-expiry-notice'),
        'cen_upcoming_notice_text_render',
        'content-expiry-notice',
        'cen_settings_section'
    );

    add_settings_field(
        'cen_expired_notice_text',
        __('Expired Notice Text', 'content-expiry-notice'),
        'cen_expired_notice_text_render',
        'content-expiry-notice',
        'cen_settings_section'
    );
}
add_action('admin_init', 'cen_settings_init');

/**
 * Settings section callback
 */
function cen_settings_section_callback() {
    echo __('Configure your content expiry notice settings below.', 'content-expiry-notice');
}

/**
 * Post types field
 */
function cen_post_types_render() {
    $options = get_option('cen_settings');
    $post_types = get_post_types(['public' => true], 'objects');
    
    foreach ($post_types as $post_type) {
        if ($post_type->name === 'attachment') {
            continue;
        }
        
        $checked = isset($options['post_types'][$post_type->name]) ? checked($options['post_types'][$post_type->name], 1, false) : '';
        echo '<label><input type="checkbox" name="cen_settings[post_types][' . $post_type->name . ']" value="1" ' . $checked . '> ' . $post_type->label . '</label><br>';
    }
}

/**
 * Days before expiration field
 */
function cen_notice_days_before_render() {
    $options = get_option('cen_settings');
    $days = isset($options['days_before']) ? $options['days_before'] : 7;
    echo '<input type="number" min="1" max="90" name="cen_settings[days_before]" value="' . $days . '"> ' . __('days', 'content-expiry-notice');
    echo '<p class="description">' . __('Show the upcoming expiry notice this many days before the expiration date.', 'content-expiry-notice') . '</p>';
}

/**
 * Notice position field
 */
function cen_notice_position_render() {
    $options = get_option('cen_settings');
    $position = isset($options['position']) ? $options['position'] : 'top';
    ?>
    <select name="cen_settings[position]">
        <option value="top" <?php selected($position, 'top'); ?>><?php _e('Above Content', 'content-expiry-notice'); ?></option>
        <option value="bottom" <?php selected($position, 'bottom'); ?>><?php _e('Below Content', 'content-expiry-notice'); ?></option>
    </select>
    <?php
}

/**
 * Notice style field
 */
function cen_notice_style_render() {
    $options = get_option('cen_settings');
    $style = isset($options['style']) ? $options['style'] : 'warning';
    ?>
    <select name="cen_settings[style]">
        <option value="info" <?php selected($style, 'info'); ?>><?php _e('Info (Blue)', 'content-expiry-notice'); ?></option>
        <option value="warning" <?php selected($style, 'warning'); ?>><?php _e('Warning (Yellow)', 'content-expiry-notice'); ?></option>
        <option value="error" <?php selected($style, 'error'); ?>><?php _e('Error (Red)', 'content-expiry-notice'); ?></option>
    </select>
    <?php
}

/**
 * Upcoming notice text field
 */
function cen_upcoming_notice_text_render() {
    $options = get_option('cen_settings');
    $text = isset($options['upcoming_text']) ? $options['upcoming_text'] : __('This content will expire on %expiry_date%.', 'content-expiry-notice');
    echo '<textarea name="cen_settings[upcoming_text]" rows="3" cols="50">' . esc_textarea($text) . '</textarea>';
    echo '<p class="description">' . __('Use %expiry_date% as a placeholder for the expiration date.', 'content-expiry-notice') . '</p>';
}

/**
 * Expired notice text field
 */
function cen_expired_notice_text_render() {
    $options = get_option('cen_settings');
    $text = isset($options['expired_text']) ? $options['expired_text'] : __('This content expired on %expiry_date%.', 'content-expiry-notice');
    echo '<textarea name="cen_settings[expired_text]" rows="3" cols="50">' . esc_textarea($text) . '</textarea>';
    echo '<p class="description">' . __('Use %expiry_date% as a placeholder for the expiration date.', 'content-expiry-notice') . '</p>';
}

/**
 * Settings page
 */
function cen_options_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action='options.php' method='post'>
            <?php
            settings_fields('cen_settings_group');
            do_settings_sections('content-expiry-notice');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Add meta box to post editor
 */
function cen_add_meta_box() {
    $options = get_option('cen_settings');
    
    if (empty($options['post_types'])) {
        return;
    }
    
    $post_types = array_keys(array_filter($options['post_types']));
    
    add_meta_box(
        'cen_expiry_date',
        __('Content Expiry Date', 'content-expiry-notice'),
        'cen_meta_box_callback',
        $post_types,
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'cen_add_meta_box');

/**
 * Meta box callback
 */
function cen_meta_box_callback($post) {
    wp_nonce_field('cen_save_meta_box_data', 'cen_meta_box_nonce');
    
    $expiry_date = get_post_meta($post->ID, '_cen_expiry_date', true);
    
    echo '<p>';
    echo '<label for="cen_expiry_date">' . __('Expiry Date:', 'content-expiry-notice') . '</label> ';
    echo '<input type="date" id="cen_expiry_date" name="cen_expiry_date" value="' . esc_attr($expiry_date) . '">';
    echo '</p>';
    
    echo '<p class="description">' . __('Leave empty for no expiration.', 'content-expiry-notice') . '</p>';
}

/**
 * Save meta box data
 */
function cen_save_meta_box_data($post_id) {
    if (!isset($_POST['cen_meta_box_nonce'])) {
        return;
    }
    
    if (!wp_verify_nonce($_POST['cen_meta_box_nonce'], 'cen_save_meta_box_data')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['cen_expiry_date'])) {
        $expiry_date = sanitize_text_field($_POST['cen_expiry_date']);
        
        if (empty($expiry_date)) {
            delete_post_meta($post_id, '_cen_expiry_date');
        } else {
            update_post_meta($post_id, '_cen_expiry_date', $expiry_date);
        }
    }
}
add_action('save_post', 'cen_save_meta_box_data');

/**
 * Enqueue styles and scripts
 */
function cen_enqueue_scripts() {
    wp_enqueue_style('cen-styles', CEN_PLUGIN_URL . 'css/content-expiry-notice.css', [], CEN_VERSION);
}
add_action('wp_enqueue_scripts', 'cen_enqueue_scripts');

/**
 * Generate notice HTML
 */
function cen_generate_notice($post_id) {
    $expiry_date = get_post_meta($post_id, '_cen_expiry_date', true);
    
    if (empty($expiry_date)) {
        return '';
    }
    
    $options = get_option('cen_settings');
    $days_before = isset($options['days_before']) ? intval($options['days_before']) : 7;
    $style = isset($options['style']) ? $options['style'] : 'warning';
    
    $expiry_timestamp = strtotime($expiry_date);
    $current_timestamp = current_time('timestamp');
    $formatted_date = date_i18n(get_option('date_format'), $expiry_timestamp);
    
    // Content has expired
    if ($current_timestamp > $expiry_timestamp) {
        $text = isset($options['expired_text']) ? $options['expired_text'] : __('This content expired on %expiry_date%.', 'content-expiry-notice');
        $notice_class = 'cen-expired cen-' . $style;
    } 
    // Content will expire soon
    elseif ($current_timestamp > ($expiry_timestamp - ($days_before * DAY_IN_SECONDS))) {
        $text = isset($options['upcoming_text']) ? $options['upcoming_text'] : __('This content will expire on %expiry_date%.', 'content-expiry-notice');
        $notice_class = 'cen-upcoming cen-' . $style;
    } 
    // Not near expiration yet
    else {
        return '';
    }
    
    $text = str_replace('%expiry_date%', $formatted_date, $text);
    
    $output = '<div class="cen-notice ' . esc_attr($notice_class) . '">';
    $output .= wp_kses_post($text);
    $output .= '</div>';
    
    return $output;
}

/**
 * Add notice to content
 */
function cen_add_notice_to_content($content) {
    if (!is_singular()) {
        return $content;
    }
    
    $post_id = get_the_ID();
    $options = get_option('cen_settings');
    
    // Check if this post type is enabled
    if (empty($options['post_types'][get_post_type()])) {
        return $content;
    }
    
    $notice = cen_generate_notice($post_id);
    
    if (empty($notice)) {
        return $content;
    }
    
    $position = isset($options['position']) ? $options['position'] : 'top';
    
    if ($position === 'top') {
        return $notice . $content;
    } else {
        return $content . $notice;
    }
}
add_filter('the_content', 'cen_add_notice_to_content');

/**
 * Register shortcode
 */
function cen_shortcode($atts) {
    $atts = shortcode_atts([
        'id' => get_the_ID(),
    ], $atts, 'content_expiry_notice');
    
    return cen_generate_notice($atts['id']);
}
add_shortcode('content_expiry_notice', 'cen_shortcode');

/**
 * Plugin activation hook
 */
function cen_activate() {
    // Set default options
    $default_options = [
        'post_types' => [
            'post' => 1,
            'page' => 1
        ],
        'days_before' => 7,
        'position' => 'top',
        'style' => 'warning',
        'upcoming_text' => __('This content will expire on %expiry_date%.', 'content-expiry-notice'),
        'expired_text' => __('This content expired on %expiry_date%.', 'content-expiry-notice')
    ];
    
    add_option('cen_settings', $default_options);
}
register_activation_hook(__FILE__, 'cen_activate');

/**
 * Plugin deactivation hook
 */
function cen_deactivate() {
    // Cleanup if needed
}
register_deactivation_hook(__FILE__, 'cen_deactivate');