<?php
/*
Plugin Name: Plugin Template
Description: Plugin Template description
Version:     1.0
Author:      Lachlan Arthur
Author URI:  https://lach.la
*/

if (!defined('ABSPATH')) {
	exit;
}

require_once 'settings.php';

if (!class_exists('LA_Plugin_Template')) {
	class LA_Plugin_Template {

		protected static $_instance = null;
		private $options = '';

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			$this->options = LA_Plugin_Template_Settings::get_options();
		}
	}

	new LA_Plugin_Template();
}
