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

function insights_dashboard_register_menu() {
    add_menu_page(
        'Site Insights Dashboard',
        'Site Insights',
        'manage_options',
        'insights-dashboard',
        'insights_dashboard_render_page',
        'dashicons-chart-area',
        3
    );
}
add_action( 'admin_menu', 'insights_dashboard_register_menu' );

function insights_dashboard_enqueue_assets( $hook_suffix ) {
    if ( $hook_suffix !== 'toplevel_page_insights-dashboard' ) {
        return;
    }

    $dist_dir = plugin_dir_path( __FILE__ ) . 'assets/dist/';
    $dist_url = plugin_dir_url( __FILE__ ) . 'assets/dist/';
    $manifest_path = $dist_dir . 'manifest.json';

    if ( ! file_exists( $manifest_path ) ) {
        return;
    }

    $manifest = json_decode( file_get_contents( $manifest_path ), true );
    if ( ! is_array( $manifest ) || ! isset( $manifest['index.html'] ) ) {
        return;
    }

    $entry = $manifest['index.html'];

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

function insights_dashboard_render_page() {
    ?>
    <div class="wrap">
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
