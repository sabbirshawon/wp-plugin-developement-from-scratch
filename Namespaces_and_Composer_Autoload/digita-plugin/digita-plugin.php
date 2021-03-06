<?php
/**
* @package DigitaPlugin
*/
/*
Plugin Name: Digita Plugin
Plugin URI: http://digitainteractive.com/plugin
Description: This is my first attemt on writng a custom Plugin for Digita Interactive
Version: 1.0.0
Author: "Sabbir Shawon"
Author URI: https://sabbirshawon.github.io/
License: GPLv2 or Later
Text Domain: Digita-Plugin
*/
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
Copyright 2005-2018 Automattic, Inc
*/
//	if ( ! defined('ABSPATH')){	
//		die;
//	}
defined ('ABSPATH') or die ('Hey, you can\t access this file, you silly human!');
/*if (! function_exists('add_action')){
	echo 'Hey, you can\t access this file, you silly human!';
	exit;
}*/
if ( file_exists( dirname(__FILE__).'/vendor/autoload.php' ) ){
	require_once dirname(__FILE__).'/vendor/autoload.php';
}

use Inc\Activate;

use Inc\Deactivate;

use Inc\Admin\AdminPages;


if ( !class_exists( 'DigitaPlugins' ) ) {

	class DigitaPlugins
	{

		public $plugin;

		function __construct(){
			$this->plugin = plugin_basename( __FILE__ );
		}
		function register() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		
			add_action( 'admin_menu', array($this,'add_admin_pages') );

			plugin_basename( __FILE__ );

			add_filter("plugin_action_links_$this->plugin",array($this, 'settings_link'));

			// echo $this->plugin;


		}

		public function settings_link($links){
			// add custom settings link


			$settings_link = '<a href="admin.php?page=digita_plugin">Settings</a>';
			array_push($links, $settings_link);

			return $links;



		}

		public function add_admin_pages(){
			add_menu_page( 'Digita Plugin', 'Digita', 'manage_options' ,'digita_plugin', array($this, 'admin_index'), 'dashicons-store', 110 );
		}

		public function admin_index(){
			// require template

			require_once plugin_dir_path( __FILE__ ) . 'templates/admin.php';



		}


		protected function create_post_type() {
			add_action( 'init', array( $this, 'custom_post_type' ) );
		}
		function custom_post_type() {
			register_post_type( 'pharma', ['public' => true, 'label' => 'Pharma'] );
		}
		function enqueue() {
			// enqueue all our scripts
			wp_enqueue_style('mypluginstyle',plugins_url( '/assets/mystyle.css', __FILE__ ));
		    wp_enqueue_script('mypluginscript',plugins_url( '/assets/myscript.js', __FILE__ ));
		}
		function activate() {
			//require_once plugin_dir_path( __FILE__ ) . 'inc/digita-plugin-activate.php';
			Activate::activate();
		}
	}
	$digitaPlugin = new DigitaPlugins();
	$digitaPlugin->register();
	// activation
	register_activation_hook( __FILE__, array( $digitaPlugin, 'activate' ) );
	// deactivation
	//require_once plugin_dir_path( __FILE__ ) . 'inc/digita-plugin-deactivate.php';
	register_deactivation_hook( __FILE__, array( 'Deactivate', 'deactivate' ) );
}