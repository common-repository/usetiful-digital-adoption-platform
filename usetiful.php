<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.usetiful.com/
 * @since             1.6
 * @package           Usetiful
 *
 * @wordpress-plugin
 * Plugin Name:       Usetiful - Digital Adoption Platform
 * Plugin URI:        https://www.usetiful.com/
 * Description:       Usetiful’s digital adoption platform improves user retention with easy-to-use product tours, smart tooltips, and onboarding checklists.
 * Version:           1.6
 * Author:            usetiful
 * Author URI:        https://www.dobbytec.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       usetiful
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Usetiful
 * Main class to handle the plugin.
 */
class Usetiful
{

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct()
	{
		// admin side page option.
		add_action('admin_menu', array($this, 'usetiful_add_settings_page'));
		// adding the usetiful script
		add_action('wp_footer', array($this, 'usetiful_footer_script'), 99);
		add_action('admin_footer', array($this, 'usetiful_admin_footer_script'), 99);
		add_action('admin_enqueue_scripts', array($this, 'usetiful_add_scripts'));
		add_action('admin_enqueue_scripts', array($this, 'usetiful_add_style'));

		add_filter('usetiful_get_tags_name_filter', array($this, 'usetiful_get_tags_name_callback' ));
		add_filter('usetiful_get_wp_tags_filter', array($this, 'usetiful_get_wp_tags_callback' ));

		add_filter('usetiful_add_wp_tags_filter', array($this, 'usetiful_get_all_wp_meta_callback') );
	}

	/**
	 * Usetiful_add_style.
	 * Admin menu hooks for adding the style in backend side.
	 *
	 * @return void
	 */
	public function usetiful_add_style()
	{
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_style('usetiful_style', plugin_dir_url(__FILE__) . 'assets/css/usetiful_style.css', array(), '1.0');
	}

	/**
	 * Usetiful_add_scripts.
	 * Admin menu hooks for adding the script in backend side.
	 *
	 * @return void
	 */
	public function usetiful_add_scripts()
	{

		wp_enqueue_script('jquery-ui-autocomplete');
		wp_enqueue_script('usetiful_script', plugin_dir_url(__FILE__) . 'assets/js/usetiful_script.js', array(), '1.0', true);
		wp_localize_script(
			'usetiful_script',
			'usetiful_args',
			array(
				'availabletagsName' => $this->usetiful_get_tags_name_callback(),
				'availableWptags' => $this->usetiful_get_wp_tags_callback(),
			)
		);
	}

	/**
	 * Usetiful_get_all_wp_meta_callback.
	 * Admin menu hooks for get tags as wp variable.
	 *
	 * @return void
	 */
	public function usetiful_get_all_wp_meta_callback( $wptagArray )
	{

		global $wpdb;

		$usetiful_user_id = get_current_user_id();

		$table_name 	= $wpdb->prefix . 'usermeta';
		$sql 	 = "SELECT `meta_key` FROM $table_name WHERE `user_id` = $usetiful_user_id";
		$results = $wpdb->get_results( $sql , ARRAY_A);

		foreach ($results as $value) {
			$wptagArray[] = $value['meta_key'];
		}

		return $wptagArray;
	}

	/**
	 * Usetiful_get_wp_tags_callback.
	 * Admin menu hooks for get tags as wp variable.
	 *
	 * @return void
	 */
	public function usetiful_get_wp_tags_callback()
	{

		$wptagArray = array(
			"wp_userId",
			"wp_email",
			"wp_nice_name",
			"wp_display_name",
			"wp_role",
			"wp_firstName",
			"wp_lastName",
			"wp_language",
			"wp_status",
		);

		$value = apply_filters('usetiful_add_wp_tags_filter', $wptagArray);

		return $value;
	}

	/**
	 * Usetiful_get_tags_name_callback.
	 * Admin menu hooks for adding tags name.
	 *
	 * @return void
	 */
	public function usetiful_get_tags_name_callback()
	{

		$tagnameArray = array(
			"userId",
			"email",
			"nice_name",
			"display_name",
			"role",
			"firstName",
			"lastName",
			"language",
			"status",
		);

		$value = apply_filters('usetiful_add_tags_name_filter', $tagnameArray);

		return $value;
	}


	/**
	 * Usetiful_add_settings_page.
	 * Admin menu hooks for adding the setting page in backend side.
	 *
	 * @return void
	 */
	public function usetiful_add_settings_page()
	{
		$settings_page = add_menu_page(
			__('Usetiful', 'usetiful'),
			__('Usetiful Settings', 'usetiful'),
			'edit_theme_options',
			'usetiful-settings',
			array($this, 'usetiful_settings_page'),
			plugin_dir_url(__FILE__) . 'assets/admin_icon.png'
		);
		// add this action to save your setting page data
		add_action("load-{$settings_page}", array($this, 'usetiful_load_settings_page'));
	}


	/**
	 * Usetiful_load_settings_page.
	 *
	 * @return void
	 */
	public function usetiful_load_settings_page()
	{
		if (isset($_POST['usetiful-settings-submit']) && $_POST['usetiful-settings-submit'] == 'Y') {
			
			$this->usetiful_save_plugin_settings();
			$url_parameters = 'updated=true';
			wp_redirect(admin_url('admin.php?page=usetiful-settings&' . $url_parameters));
			exit;
		}
	}

	/**
	 * Usetiful_save_plugin_settings.
	 *
	 * @return void
	 */
	public function usetiful_save_plugin_settings()
	{
		global $pagenow;

		$settings = get_option('usetiful_plugin_settings');
		if ($pagenow == 'admin.php' && isset($_GET['page']) && $_GET['page'] == 'usetiful-settings') {

			if (isset($_POST['usetiful_setting_nonce']) && wp_verify_nonce(sanitize_key($_POST['usetiful_setting_nonce']), 'usetiful-settings-page')) {

				$usetiful_option_arr = $_POST['usetiful_option'];
				foreach ($usetiful_option_arr as $idx => $row) {
					if (preg_grep('/^$/', $row)) {
						unset($usetiful_option_arr[$idx]);
					}

				}

				$settings['usetiful_key'] = isset($_POST['usetiful_key']) ? sanitize_key($_POST['usetiful_key']) : '';
				$settings['admin_usetiful_key'] = isset($_POST['admin_usetiful_key']) ? sanitize_key($_POST['admin_usetiful_key']) : '';
				$settings['usetiful_tags_option'] = isset($_POST['usetiful_option']) ? array_filter(array_map('array_filter', $usetiful_option_arr)) : '';

				update_option('usetiful_plugin_settings', $settings);
			}
		}
	}

	/**
	 * Usetiful_settings_page.
	 *
	 * @return void
	 */
	public function usetiful_settings_page()
	{

		global $pagenow;
		$settings = get_option('usetiful_plugin_settings');
?>

		<div class="wrap usetiful-content">
			<h2><?php echo esc_html__('Usetiful Plugin Settings', 'usetiful'); ?></h2>
			<?php

			if (isset($_GET['updated']) && 'true' == esc_attr($_GET['updated'])) {
				echo '<div class="updated" ><p>' . esc_html__('Settings updated.', 'usetiful') . '</p></div>';
			}

			?>

			<div id="poststuff" class="usetiful-setting">
				<form method="post" action="<?php admin_url('admin.php?page=usetiful-settings'); ?>">
					<?php
					wp_nonce_field('usetiful-settings-page', 'usetiful_setting_nonce');
					if ($pagenow == 'admin.php' && $_GET['page'] == 'usetiful-settings') {
						include 'admin/setting.php';

						include 'admin/tag-setting.php';
					}
					?>
					<div class="usetiful-submit-section">
						<p class="submit" style="clear: both;">
							<input type="submit" name="Submit" class="button-primary usetiful-submit" value="Update Settings" />
							<input type="hidden" name="usetiful-settings-submit" value="Y" />
						</p>
					</div>
				</form>
			</div>

		</div>
		<?php
	}

	/**
	 * Usetiful_footer_script.
	 * adding the script on website footer
	 *
	 * @return void
	 */
	public function usetiful_footer_script()
	{

		$settings = get_option('usetiful_plugin_settings');
		if (isset($settings['usetiful_key']) && !empty($settings['usetiful_key']) && $this->usetiful_frontend_check()) {

			$usetiful_key     = $settings['usetiful_key'];

			$this->usetiful_usertags_script($usetiful_key);
		}
	}

	/**
	 * Usetiful_admin_footer_script.
	 * adding the script on website footer
	 *
	 * @return void
	 */
	public function usetiful_admin_footer_script()
	{

		$settings = get_option('usetiful_plugin_settings');
		if (isset($settings['admin_usetiful_key']) && !empty($settings['admin_usetiful_key'])) {

			$admin_usetiful_key     = $settings['admin_usetiful_key'];

			$this->usetiful_usertags_script($admin_usetiful_key);
		}
	}

	/**
	 * Usetiful_frontend_check.
	 * Checking frontend
	 *
	 * @return void
	 */
	public function usetiful_frontend_check()
	{

		global $pagenow, $typenow;

		if (!function_exists('is_plugin_active')) {
			include_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}
		$return =  true;

		// Checking Adminside.
		if (is_admin()) {
			$return = false;
		}

		// Checking Ajax.
		if (wp_doing_ajax()) {
			$return = false;
		}

		// Checking Visual Composer.
		if (isset($_GET['vcv-editable']) && $_GET['vcv-editable']) {
			$return = false;
		}

		// Checking WPBakery Page.
		if (isset($_GET['vc_editable']) && $_GET['vc_editable']) {
			$return = false;
		}

		// Checking Elementor.
		if (is_plugin_active('elementor/elementor.php')) {
			if (\Elementor\Plugin::$instance->preview->is_preview_mode()) {
				$return =  false;
			}
		}

		return $return;
	}

	/**
	 * usetiful_tags_script.
	 * Add tags and script
	 *
	 * @return void
	 */
	public function usetiful_usertags_script($key = NULL)
	{

		$userTag 	= array();

		if (is_user_logged_in()) {

			$usetiful_user_id = get_current_user_id();
			$user_data        = get_userdata($usetiful_user_id);
			$usetiful_role    = $user_data->roles[0];
			$usetiful_fname   = $user_data->user_firstname;
			$usetiful_lname   = $user_data->user_lastname;
			$usetiful_email   = $user_data->user_email;
			$usetiful_nicename   = $user_data->user_nicename;
			$usetiful_display_name   = $user_data->user_display_name;

			$settings = get_option('usetiful_plugin_settings');

			if (!empty($settings['usetiful_tags_option'])) {
				
				foreach ($settings['usetiful_tags_option'] as $_key => $value) {

					$tag_key = esc_html($value['tag']);

					/* Replace tag_value to actual value */
					if ($value['tag_value'] == 'wp_userId') {
						$value['tag_value'] = str_replace('wp_userId', (string)$usetiful_user_id, $value['tag_value']);
					}else if ($value['tag_value'] == 'wp_firstName') {
						$value['tag_value'] = str_replace('wp_firstName', esc_html($usetiful_fname), $value['tag_value']);
					}else if ($value['tag_value'] == 'wp_lastName') {
						$value['tag_value'] = str_replace('wp_lastName', esc_html($usetiful_lname), $value['tag_value']);
					}else if ($value['tag_value'] == 'wp_role') {
						$value['tag_value'] = str_replace('wp_role', esc_html($usetiful_role), $value['tag_value']);
					}else if ($value['tag_value'] == 'wp_language') {
						$value['tag_value'] = str_replace('wp_language', get_bloginfo("language"), $value['tag_value']);
					}else if ($value['tag_value'] == 'wp_status') {
						$value['tag_value'] = str_replace('wp_status', esc_html('loggedin'), $value['tag_value']);
					}else if ($value['tag_value'] == 'wp_email') {
						$value['tag_value'] = str_replace('wp_email', esc_html($usetiful_email), $value['tag_value']);
					}else if ($value['tag_value'] == 'wp_nicename') {
						$value['tag_value'] = str_replace('wp_nicename', esc_html($usetiful_nicename), $value['tag_value']);
					}else if ($value['tag_value'] == 'wp_display_name') {
						$value['tag_value'] = str_replace('wp_display_name', esc_html($usetiful_display_name), $value['tag_value']);
					}else {

						$finalValue = get_user_meta( $usetiful_user_id, $value['tag_value'], true );
						if( !empty( $finalValue )){

							$value['tag_value'] = str_replace( $value['tag_value'] , esc_html($finalValue), $value['tag_value']);
						}else{

							$value['tag_value'] = esc_html( $value['tag_value'] );
						}
						
						
					}

					$userTag[$tag_key] = esc_html($value['tag_value']);
					
				}
			} else {

				$userTag = array(
					'userId' 	=> (string)$usetiful_user_id,
					'role'		=> esc_html($usetiful_role),
				);

				// Checking First Name tag.
				if (!empty($usetiful_fname)) {
					$userTag['firstName'] = esc_html($usetiful_fname);
				}

				// Checking Last Name. tag
				if (!empty($usetiful_lname)) {
					$userTag['lastName'] = esc_html($usetiful_lname);
				}
			}

		}

		// $encodeUserTag = json_encode( $userTag );
		// <!-- User segmentation start -->
		wp_print_inline_script_tag(
			sprintf('window.usetifulTags = %s;', wp_json_encode($userTag))
		);
		// <!-- User segmentation end -->
		
		if (!empty($key)) { ?>
			<!-- Usetiful script start -->
			<script>
				(function(w, d, s) {
					var a = d.getElementsByTagName('head')[0];
					var r = d.createElement('script');
					r.async = 1;
					r.src = s;
					r.setAttribute('id', 'usetifulScript');
					r.dataset.token = "<?php echo esc_html($key); ?>";
					a.appendChild(r);
				})(window, document, "https://www.usetiful.com/dist/usetiful.js");
			</script>
			<!-- Usetiful script end -->
		<?php }
	}
}
new Usetiful();
