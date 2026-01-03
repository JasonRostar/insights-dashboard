<?php
/**
 * Plugin Name: Site Insights Dashboard
 * Description: Adds a React-powered admin dashboard for site insights.
 * Version: 0.1.0
 * Author: You
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register the admin menu item.
 *
 * Why this exists:
 * - This adds the "Insights" item in the WordPress admin sidebar.
 *
 * If removed:
 * - The plugin can still be active, but you won’t have a page to click into.
 */
function insights_dashboard_register_menu() {
    add_menu_page(
        'Site Insights Dashboard',        // Page title
        'Insights',                       // Menu label
        'manage_options',                 // Capability (admins only)
        'insights-dashboard',             // Menu slug
        'insights_dashboard_render_page', // Callback that renders the page HTML
        'dashicons-chart-area',           // Icon
        3                                 // Position
    );
}
add_action( 'admin_menu', 'insights_dashboard_register_menu' );

/**
 * Enqueue the built React assets on ONLY our plugin admin page.
 *
 * Why this exists:
 * - Vite outputs hashed filenames (like index-ABC123.js).
 * - WordPress can’t guess those names.
 * - Vite writes a manifest.json that tells us the real filenames.
 *
 * If removed:
 * - The admin page still loads, but React never loads, so the UI stays blank.
 */
function insights_dashboard_enqueue_assets( $hook_suffix ) {
    // Only load scripts/styles on our plugin admin page.
    // WordPress generates this hook suffix from the menu slug:
    // "insights-dashboard" => "toplevel_page_insights-dashboard"
    if ( $hook_suffix !== 'toplevel_page_insights-dashboard' ) {
        return;
    }

    $dist_dir = plugin_dir_path( __FILE__ ) . 'assets/dist/';
    $dist_url = plugin_dir_url( __FILE__ ) . 'assets/dist/';
    $manifest_path = $dist_dir . 'manifest.json';

    // If the build output doesn't exist yet, don't break the admin.
    if ( ! file_exists( $manifest_path ) ) {
        return;
    }

    $manifest = json_decode( file_get_contents( $manifest_path ), true );
    if ( ! is_array( $manifest ) ) {
        return;
    }

    // Vite's main entry is typically keyed by "index.html".
    if ( ! isset( $manifest['index.html'] ) ) {
        return;
    }

    $entry = $manifest['index.html'];

    // Enqueue CSS (if present)
    if ( ! empty( $entry['css'] ) && is_array( $entry['css'] ) ) {
        foreach ( $entry['css'] as $css_file ) {
            wp_enqueue_style(
                'insights-dashboard-style',
                $dist_url . $css_file,
                array(),
                null
            );
        }
    }

    // Enqueue JS
    if ( ! empty( $entry['file'] ) ) {
        wp_enqueue_script(
            'insights-dashboard-app',
            $dist_url . $entry['file'],
            array(),
            null,
            true
        );
    }
}
add_action( 'admin_enqueue_scripts', 'insights_dashboard_enqueue_assets' );

/**
 * Render the admin page.
 *
 * Why this exists:
 * - This outputs the mount point <div> that React attaches to.
 *
 * If removed:
 * - The menu still exists, but the page will be blank/broken.
 */
function insights_dashboard_render_page() {
    ?>
    <div class="wrap">
        <h1>Site Insights Dashboard</h1>
        <div id="insights-dashboard-root"></div>
    </div>
    <?php
}

add_filter(
    'script_loader_tag',
    function ( $tag, $handle, $src ) {
        if ( $handle === 'insights-dashboard-app' ) {
            return '<script type="module" src="' . esc_url( $src ) . '"></script>';
        }

        return $tag;
    },
    10,
    3
);