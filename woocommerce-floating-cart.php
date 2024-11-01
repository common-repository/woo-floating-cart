<?php
/**
* Plugin Name: Side Cart Woocommerce
* Description: This plugin allows you to Create Sidebar cart in WooCommerce.
* Version: 2.0
* Author: Ocean Infotech
* Author URI: https://www.xeeshop.com
* Copyright: 2019 
*/


if (!defined('ABSPATH')) {
  die('-1');
}
if (!defined('WFC_PLUGIN_NAME')) {
  define('WFC_PLUGIN_NAME', 'Side Cart Woocommerce');
}
if (!defined('WFC_PLUGIN_VERSION')) {
  define('WFC_PLUGIN_VERSION', '2.0.0');
}
if (!defined('WFC_PLUGIN_FILE')) {
  define('WFC_PLUGIN_FILE', __FILE__);
}
if (!defined('WFC_PLUGIN_DIR')) {
  define('WFC_PLUGIN_DIR',plugins_url('', __FILE__));
}
if (!defined('WFC_DOMAIN')) {
  define('WFC_DOMAIN', 'wfc');
}

//Main class
//Load required js,css and other files
if (!class_exists('WFC')) {

  	class WFC {

    	protected static $WFC_instance;

        /**
       	* Constructor.
       	*
       	* @version 3.2.3
       	*/


      	function __construct() {
        	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        	//check plugin activted or not
        	add_action('admin_init', array($this, 'WFC_check_plugin_state'));
      	}


	    //Add JS and CSS on Backend
	    function WFC_load_admin_script_style() {
	      	wp_enqueue_style( 'WFC_admin_css', WFC_PLUGIN_DIR . '/css/wfc_admin_style.css', false, '1.0.0' );
	      	wp_enqueue_script( 'WFC_admin_js', WFC_PLUGIN_DIR . '/js/wfc_admin_js.js', array( 'jquery', 'select2') );
	      	wp_localize_script( 'ajaxloadpost', 'ajax_postajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	      	wp_enqueue_style( 'woocommerce_admin_styles-css', WP_PLUGIN_URL. '/woocommerce/assets/css/admin.css',false,'1.0',"all");
	    }


	    function WFC_load_script_style() {
	    	wp_enqueue_script( 'owlcarousel', WFC_PLUGIN_DIR . '/owlcarousel/owl.carousel.js', false, '1.0.0' );
	    	wp_enqueue_style( 'owlcarousel-min', WFC_PLUGIN_DIR . '/owlcarousel/assets/owl.carousel.min.css', false, '1.0.0' );
	      	wp_enqueue_style( 'owlcarousel-theme', WFC_PLUGIN_DIR . '/owlcarousel/assets/owl.theme.default.min.css', false, '1.0.0' );
	      	wp_enqueue_style( 'WFC_front_css', WFC_PLUGIN_DIR . '/css/wfc_front_style.css', false, '1.0.0' );
	      	wp_enqueue_script( 'WFC_front_js', WFC_PLUGIN_DIR . '/js/wfc_front_js.js', false, '1.0.0' );
	      	wp_localize_script( 'WFC_front_js', 'ajax_postajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	        wp_enqueue_script( 'jquery-effects-core' );
	      	
	      	
	    }


    	function WFC_show_notice() {
        	if ( get_transient( get_current_user_id() . 'wfcerror' ) ) {

          		deactivate_plugins( plugin_basename( __FILE__ ) );

          		delete_transient( get_current_user_id() . 'wfcerror' );

          		echo '<div class="error"><p> This plugin is deactivated because it require <a href="plugin-install.php?tab=search&s=woocommerce">WooCommerce</a> plugin installed and activated.</p></div>';
        	}
    	}


    	function WFC_check_plugin_state(){
      		if ( ! ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) ) {
        		set_transient( get_current_user_id() . 'wfcerror', 'message' );
      		}
    	}


	    function init() {
	      	add_action( 'admin_notices', array($this, 'WFC_show_notice'));
	      	add_action( 'admin_enqueue_scripts', array($this, 'WFC_load_admin_script_style'));
	      	add_action( 'wp_enqueue_scripts',  array($this, 'WFC_load_script_style'));
	    }

	    //Load all includes files
	    function includes() {
	      	include_once('includes/wfc_backend.php');
	      	include_once('includes/wfc_front.php');
	    }

	    //Plugin Rating
	    public static function WFC_do_activation() {
	      	set_transient('wfc-first-rating', true, MONTH_IN_SECONDS);
	    }

	    public static function WFC_instance() {
	      	if (!isset(self::$OCWCP_instance)) {
	        	self::$WFC_instance = new self();
	        	self::$WFC_instance->init();
	        	self::$WFC_instance->includes();
	      	}
	      	return self::$WFC_instance;
	    }
  	}

  	add_action('plugins_loaded', array('WFC', 'WFC_instance'));

  	register_activation_hook(WFC_PLUGIN_FILE, array('WFC', 'WFC_do_activation'));
}


