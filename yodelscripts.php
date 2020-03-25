<?php 
/**
* Plugin Name: Yodel Scripts
* Description: Simple Echo Scripts from WP Backend to Header or Footer
* Version: 0.1.0
* Author: Doug Little
* Text Domain: ce-add-scripts
*/

if(! defined('ABSPATH')){
	exit;
}

if(!class_exists('ce_add_scripts')){

	class ce_add_scripts{

		function __construct(){
			add_action('admin_init', array(&$this,'ceas_admin_init'));
			add_action('admin_menu', array(&$this,'ceas_admin_menu'));
			add_action('wp_head', array(&$this,'ceas_wp_head'));
			add_action('wp_footer', array(&$this,'ceas_wp_footer'));
		}

		function ceas_admin_menu(){
			add_submenu_page(
				'tools.php', 
				'CE Add Scripts', 
				'CE Add Scripts', 
				'update_plugins', 
				'ceas_menu_page', 
				array(&$this,'ceas_page_callback') );
		}

		function ceas_admin_init(){
			$args= array(
				'type'=>'string',
				'default'=>NULL,);

			register_setting(
				'ceas_options', 
				'ceas_main_settings', 
				$args);

			add_settings_section(
				'ceas_options_section',
				'Insert Scripts to Head or Footer',
				array(&$this,'ceas_section_display'),
				'ceas_menu_page');

			add_settings_field(
				'ceas_options_field_0',
				'Head Script Entry',
				'ceas_head_script',
				'CE Add Scripts',
				'ceas_options_section');

			add_settings_field(
				'ceas_options_field_1',
				'Footer Script Entry',
				'ceas_footer_script',
				'CE Add Scripts',
				'ceas_options_section');
		}

		function ceas_section_display(){
			echo 'Add scripts to the Head or Footer';
		}

		function ceas_head_script(){
			$options = get_option('ceas_main_settings');
			echo "<h3>Add Head Scripts Below</h3>";
			echo "<textarea cols='40' rows='5' name='ceas_main_settings[ceas_options_field_0]'>";
			echo $options['ceas_options_field_0'];
			echo "</textarea>";
		}

		function ceas_footer_script(){
			$options = get_option('ceas_main_settings');
			echo "<h3>Add Footer Scripts Below</h3>
				<p>(These will be inserted directly above the closing body tag)</p>";
			echo "<textarea cols='40' rows='5' name='ceas_main_settings[ceas_options_field_1]'>";
			echo $options['ceas_options_field_1'];
			echo "</textarea>";
		}

		function ceas_page_callback(){
			 
			echo "<div class='wrap'>

			<form action='options.php' method='post'>

			<h1>CE Add Scripts Settings</h1>";

			do_settings_sections('ceas_menu_page');
			settings_fields("ceas_options");
			echo "<br>";
			$this->ceas_head_script();
			echo "<br>";
            $this->ceas_footer_script();
			submit_button();
				  

			echo "</form> 
			</div>";
		}
		
		function ceas_wp_head(){
		    $script = get_option('ceas_main_settings','');
			echo $script['ceas_options_field_0'];
		}
		
		function ceas_wp_footer(){
			$script = get_option('ceas_main_settings','');
			if (is_page('350')) {
			    echo $script['ceas_options_field_1'];
			}
		}

	}
	
	$ce_actualize= new ce_add_scripts();
}
