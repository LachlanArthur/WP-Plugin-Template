<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('LA_Plugin_Template_Settings')) {
	class LA_Plugin_Template_Settings {

		static $option_name = 'la_plugin_template'; // Option name saved in wp_options table, also used as POST param name.
		private $options;

		public function __construct() {
			add_action('admin_menu', array($this, 'add_options_pages'));
			add_action('admin_init', array($this, 'options_page_1_init'));
		}

		public function add_options_pages() {
			$this->options = self::get_options();
			add_options_page(
				'Plugin Template', // Page title
				'Plugin Template', // Menu Title
				'manage_options', // Capability to access page
				'la_plugin_template_options_page_1', // Page ID (used in URL)
				array($this, 'render_page_1') // Render callback
			);
		}

		public static function get_options() {
			$defaults = array(
				'foo' => '',
				'bar' => '',
			);
			$values = get_option(self::$option_name);
			return wp_parse_args($values, $defaults);
		}

		public function render_page_1() {
			?>
			<div class="wrap">
				<h2>Plugin Template Settings</h2>
				<form method="post" action="options.php">
					<?php
					settings_fields(self::$option_name); // Option group name set in register_setting
					do_settings_sections('la_plugin_template_options_page_1'); // Page ID
					submit_button();
					?>
				</form>
			</div>
			<?php
		}

		public function options_page_1_init() {
			register_setting(
				self::$option_name, // Options group name (use the option name for simplicity)
				self::$option_name, // Post param name
				array($this, 'sanitize') // Value sanitisation
			);

			add_settings_section(
				'section_1', // Section ID
				'Section 1', // Title
				array($this, 'section_info_1'), // Section info callback
				'la_plugin_template_options_page_1' // Page ID
			);

			add_settings_field(
				'foo', // Field ID
				'Foo', // Label
				array($this, 'render_field_foo'), // Render callback
				'la_plugin_template_options_page_1', // Page ID
				'section_1', // Section ID
				array('id' => 'foo') // Extra params for render callback
			);

			add_settings_section(
				'section_2', // Section ID
				'Section 2', // Title
				array($this, 'section_info_2'), // Section info callback
				'la_plugin_template_options_page_1' // Page ID
			);

			add_settings_field(
				'bar', // Field ID
				'Bar', // Label
				array($this, 'render_field_bar'), // Render callback
				'la_plugin_template_options_page_1', // Page ID
				'section_2', // Section ID
				array('id' => 'bar') // Extra params for render callback
			);
		}

		public function sanitize($input) {
			$sanitised = array();

			if (isset($input['foo'])) {
				$sanitised['foo'] = sanitize_text_field($input['foo']);
			}

			if (isset($input['bar'])) {
				$sanitised['bar'] = absint($input['bar']);
			}

			return $sanitised;
		}

		public function section_info_1() {
			?>
			Section 1 info. Can use <em>HTML</em>.
			<?php
		}

		public function section_info_2() {
			?>
			Section 2 info.
			<?php
		}

		public function render_field_foo($args) {
			$value = $this->options[$args['id']];
			printf(
				'<input type="text" id="%2$s" name="%1$s[%2$s]" value="%3$s" />',
				self::$option_name,
				$args['id'],
				isset($value) ? esc_attr($value) : ''
			);
		}

		public function render_field_bar($args) {
			$value = $this->options[$args['id']];
			printf(
				'<input type="checkbox" id="%2$s" name="%1$s[%2$s]" value="1" %3$s />',
				self::$option_name,
				$args['id'],
				checked(1, $value, false)
			);
		}
	}

	if (is_admin()) {
		new LA_Plugin_Template_Settings();
	}
}
