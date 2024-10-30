<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.vikasroy.com
 * @since      1.0.0
 *
 * @package    Listen2it
 * @subpackage Listen2it/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Listen2it
 * @subpackage Listen2it/admin
 * @author     Vikas Roy <vikas@listen2.it>
 */
class Listen2it_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/listen2it-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script('momentjs', plugin_dir_url( __FILE__ ) . 'js/moment.min.js', array( 'jquery' ), $this->version, false );

		if( get_option('l2it_org_id') && get_option('l2it_integration_id') && get_option('l2it_api_key') ){

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/listen2it-admin.js', array( 'jquery' ), $this->version, false );
			wp_localize_script( $this->plugin_name, 'ajax_object', array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'content_url' => 'https://dashboard.getlisten2it.com/organisation/' . get_option('l2it_org_id') . '/integration/' . get_option('l2it_integration_id') . '/content/'
			) );

		}

	}

	public function display_general_account() {

		$html = '<p>Following settings are required to enable the plugin.<p>';
		$html .= '<p>You will find these settings on the <strong>installation</strong> page of your project in your <strong>Listen2It account</strong>. In case you have not created an account yet, <a href="https://dashboard.getlisten2it.com" target="_blank">click here</a> to signup.</p>';

		echo $html;

	}

	public function render_settings_field($args) {

		if($args['wp_data'] == 'option'){

			$wp_data_value = get_option($args['name']);

		} elseif($args['wp_data'] == 'post_meta'){

			$wp_data_value = get_post_meta($args['post_id'], $args['name'], true );

		}

		switch ($args['type']) {

			case 'input':

				$value = ($args['value_type'] == 'serialized') ? serialize($wp_data_value) : $wp_data_value;

				if($args['subtype'] != 'checkbox'){

					$prependStart = (isset($args['prepend_value'])) ? '<div class="input-prepend"> <span class="add-on">'.$args['prepend_value'].'</span>' : '';
					$prependEnd = (isset($args['prepend_value'])) ? '</div>' : '';

					$step = (isset($args['step'])) ? 'step="'.$args['step'].'"' : '';
					$min = (isset($args['min'])) ? 'min="'.$args['min'].'"' : '';
					$max = (isset($args['max'])) ? 'max="'.$args['max'].'"' : '';

					if(isset($args['disabled'])){

						echo $prependStart.'<input type="'.esc_attr($args['subtype']).'" id="'.esc_attr($args['id']).'_disabled" '.esc_attr($step).' '.esc_attr($max).' '.esc_attr($min).' name="'.esc_attr($args['name']).'_disabled" size="40" disabled value="' . esc_attr($value) . '" /><input type="hidden" id="'.esc_attr($args['id']).'" '.esc_attr($step).' '.esc_attr($max).' '.esc_attr($min).' name="'.esc_attr($args['name']).'" size="40" value="' . esc_attr($value) . '" />'.$prependEnd;

					} else {

						echo $prependStart.'<input type="'.esc_attr($args['subtype']).'" id="'.esc_attr($args['id']).'" "'.esc_attr($args['required']).'" '.esc_attr($step).' '.esc_attr($max).' '.esc_attr($min).' name="'.esc_attr($args['name']).'" size="40" value="' . esc_attr($value) . '" />'.$prependEnd;

					}

				} else {

					$checked = ($value) ? 'checked' : '';
					echo '<input type="'.esc_attr($args['subtype']).'" id="'.esc_attr($args['id']).'" "'.esc_attr($args['required']).'" name="'.esc_attr($args['name']).'" size="40" value="1" '.esc_attr($checked).' />';

				}

				break;

			default:
				break;
		}
	}

	public function register_and_build_fields() {

		add_settings_section(
			'l2it_general_section',
			'',
			array( $this, 'display_general_account' ),
			'l2it_general_settings'
		);

		add_settings_field(
			'l2it_org_id',
			'Organisation ID',
			array( $this, 'render_settings_field' ),
			'l2it_general_settings',
			'l2it_general_section',
			array (
				'type'      => 'input',
				'subtype'   => 'text',
				'id'    => 'l2it_org_id',
				'name'      => 'l2it_org_id',
				'required' => 'true',
				'get_options_list' => '',
				'value_type'=>'normal',
				'wp_data' => 'option'
			)
		);

		add_settings_field(
			'l2it_integration_id',
			'Project ID',
			array( $this, 'render_settings_field' ),
			'l2it_general_settings',
			'l2it_general_section',
			array (
				'type'      => 'input',
				'subtype'   => 'text',
				'id'    => 'l2it_integration_id',
				'name'      => 'l2it_integration_id',
				'required' => 'true',
				'get_options_list' => '',
				'value_type'=>'normal',
				'wp_data' => 'option'
			)
		);

		add_settings_field(
			'l2it_api_key',
			'API Key',
			array( $this, 'render_settings_field' ),
			'l2it_general_settings',
			'l2it_general_section',
			array (
				'type'      => 'input',
				'subtype'   => 'text',
				'id'    => 'l2it_api_key',
				'name'      => 'l2it_api_key',
				'required' => 'true',
				'get_options_list' => '',
				'value_type'=>'normal',
				'wp_data' => 'option'
			)
		);

        add_settings_field(
            'l2it_has_paywall',
            'Website requires login or behind a paywall?',
            array( $this, 'render_settings_field' ),
            'l2it_general_settings',
            'l2it_general_section',
            array (
                'type'      => 'input',
                'subtype'   => 'checkbox',
                'id'    => 'l2it_has_paywall',
                'name'      => 'l2it_has_paywall',
                'required' => 'false',
                'get_options_list' => '',
                'value_type'=>'normal',
                'wp_data' => 'option'
            )
        );

		register_setting(
			'l2it_general_settings',
			'l2it_org_id'
		);

		register_setting(
			'l2it_general_settings',
			'l2it_integration_id'
		);

		register_setting(
			'l2it_general_settings',
			'l2it_api_key'
		);

		register_setting(
			'l2it_general_settings',
			'l2it_has_paywall'
		);

	}

	public function settings_messages($error_message){

		switch ($error_message) {
			case '1':
				$message = __( 'There was an error adding this setting. Please try again.  If this persists, shoot us an email.', 'listen2it' );
				$err_code = esc_attr( 'l2it_org_id' );
				$setting_field = 'l2it_org_id';
				break;
		}

		$type = 'error';

		add_settings_error(
			$setting_field,
			$err_code,
			$message,
			$type
		);
	}

	public function display_plugin_admin_dashboard() {

		if(isset($_GET['error_message'])){
			add_action('admin_notices', array($this,'settings_messages'));
			do_action( 'admin_notices', $_GET['error_message'] );
		}

		require_once 'partials/'.$this->plugin_name.'-admin-display.php';

	}

	public function options_page(){

		add_menu_page(
			$this->plugin_name,
			'Listen2It',
			'administrator',
			$this->plugin_name,
			array( $this, 'display_plugin_admin_dashboard' ),
			plugin_dir_url(__FILE__) . 'images/icon.svg',
			26
		);

	}

	public function add_audio_column($columns){

		return array_merge($columns, ['audio' => 'Listen2It']);

	}

	public function get_content($content_id){

		$response = wp_remote_get( 'https://api.getlisten2it.com/organisation/' . get_option('l2it_org_id') . '/integration/' . get_option('l2it_integration_id') . '/content/' . $content_id . '?origin=wordpress',
			array(
				'headers' => array(
					'Content-Type' => 'application/json',
					'X-API-Key' => get_option('l2it_api_key')
				)
			)
		);

		if( 200 != wp_remote_retrieve_response_code( $response )){

			return false;

		}

		$body = json_decode(wp_remote_retrieve_body( $response ), true);

		return $body;

	}

	public function ajax_get_audio_status(){

		$content_id = sanitize_text_field($_POST['content_id']);
		$post_id = sanitize_text_field($_POST['post_id']);

		$content = $this->get_content($content_id);

		if(!$content){

			echo json_encode([
				'success' => false
			]);

			wp_die();

			return;

		}

		if(false == $content['success']){

			echo json_encode([
				'success' => false
			]);

			wp_die();

			return;

		}

		$data = $content['data'];

		$data['post_last_modified_at'] = get_post_modified_time('Y-m-d H:i:s', false, $post_id);

		echo json_encode([
			'success' => true,
			'data' => $data
		]);

		wp_die();

	}

	public function display_audio_column($column, $post_id){

		if('audio' == $column){

			$content_id =  md5(get_permalink($post_id,  false ));

			echo '<div class="audio-actions" data-content-id="' . esc_attr($content_id) . '" data-post-id="' . esc_attr($post_id) . '"></div>';

		}

	}

}
