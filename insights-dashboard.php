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
 */
function insights_dashboard_register_menu() {
    add_menu_page(
        'Site Insights Dashboard', // Page title
        'Insights',                // Menu label
        'manage_options',          // Capability (admins only)
        'insights-dashboard',      // Menu slug
        'insights_dashboard_render_page', // Callback function
        'dashicons-chart-area',    // Icon
        3                           // Position
    );
}

add_action( 'admin_menu', 'insights_dashboard_register_menu' );

/**
 * Render the admin page.
 */
function insights_dashboard_render_page() {
    ?>
    <div class="wrap">
        <h1>Site Insights Dashboard</h1>
        <p>This is where the React app will live.</p>
    </div>
    <?php
}
