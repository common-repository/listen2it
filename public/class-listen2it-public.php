<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.vikasroy.com
 * @since      1.0.0
 *
 * @package    Listen2it
 * @subpackage Listen2it/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Listen2it
 * @subpackage Listen2it/public
 * @author     Vikas Roy <vikas@listen2.it>
 */
class Listen2it_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Listen2it_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Listen2it_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/listen2it-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Listen2it_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Listen2it_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if( get_option('l2it_org_id') && get_option('l2it_integration_id') && is_singular('post') ){

		    $has_paywall = (1 == get_option('l2it_has_paywall')) ? "true" : "false";

			wp_enqueue_script( 'l2it-widget', '//widget.getlisten2it.com/widget.min.js', array('jquery'), $this->version, false);
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/listen2it-public.js', array( 'l2it-widget' ), $this->version, false );
			wp_localize_script( $this->plugin_name, 'l2it',
				array(
					'org_id' =>  get_option('l2it_org_id', ''),
					'integration_id' => get_option('l2it_integration_id', ''),
					'container' => '#l2it-audio-player',
                    'has_paywall' => $has_paywall
				)
			);

		}

	}

	public function embed_player($content){

		if ( is_singular() && get_option('l2it_org_id') && get_option('l2it_integration_id') ) {
			return '<div id="l2it-audio-player"></div>' . $content;
		}

		return $content;

	}

}
