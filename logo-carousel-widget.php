<?php
/**
 * Plugin Name: Logo Carousel Widget for Elementor
 * Plugin URI:  https://example.com
 * Description: Drag-and-drop supported, smooth scrolling logo showcase widget. Fully compatible with Elementor.
 * Version:     1.0.0
 * Author:      Harun Sekmen
 * Author URI:  https://www.linkedin.com/in/harunsekmen/
 * Text Domain: logo-carousel-widget
 * Requires Plugins: elementor
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'LCW_VERSION', '1.0.0' );
define( 'LCW_FILE', __FILE__ );
define( 'LCW_PATH', plugin_dir_path( __FILE__ ) );
define( 'LCW_URL',  plugin_dir_url( __FILE__ ) );

/**
 * Main plugin class
 */
final class Logo_Carousel_Widget_Plugin {

    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    public function init() {
        // Check if Elementor is loaded
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_elementor' ] );
            return;
        }

        // Load i18n
        load_plugin_textdomain( 'logo-carousel-widget', false, dirname( plugin_basename( LCW_FILE ) ) . '/languages/' );

        // Register Widget
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );

        // Register Assets (Will be enqueued only when widget is used)
        add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts_and_styles' ] );
        add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'register_scripts_and_styles' ] );
    }

    public function register_widgets( $widgets_manager ) {
        require_once LCW_PATH . 'widgets/class-logo-carousel-widget.php';
        $widgets_manager->register( new \LCW_Logo_Carousel_Widget() );
    }

    public function register_scripts_and_styles() {
        wp_register_style(
            'lcw-styles',
            LCW_URL . 'assets/logo-carousel.min.css',
            [],
            LCW_VERSION
        );

        wp_register_script(
            'lcw-scripts',
            LCW_URL . 'assets/logo-carousel.min.js',
            [ 'jquery' ],
            LCW_VERSION,
            true
        );
    }

    public function admin_notice_missing_elementor() {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p><strong>Elementor</strong> must be installed and activated for the <strong>Logo Carousel Widget</strong> plugin to work.</p>
        </div>
        <?php
    }
}

Logo_Carousel_Widget_Plugin::instance();
