<?php if(!defined('ABSPATH')) { die(); } // Include in all php files, to prevent direct execution
/**
 * Plugin Name: FA Menu Icons
 * Description: Make Font Awesome icons available for use in menu classes
 * Author: Pierce Gresham
 * Author URI:
 * Version: 0.1.1
 * Text Domain: fa-menu-icons
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 **/
 if( !class_exists('AvailableFAMenus') ) {
	class AvailableFAMenus {
		private static $version = '0.1.0';
		private static $_this;
		private $settings;
		
		private $capability = 'manage_options';

		public static function Instance() {
			static $instance = null;
			if ($instance === null) {
				$instance = new self();
			}
			return $instance;
		}

		private function __construct() {
			register_activation_hook( __FILE__, array( $this, 'register_activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'register_deactivation' ) );
			
			$this->initialize_settings();

			add_action( 'admin_menu' , array( $this, 'add_afa_fa_page'));
			add_action( 'admin_menu' , array( $this, 'admin_enqueues'));
			add_action( 'admin_init', array( $this, 'admin_init') );
			
			add_action('wp_head', array( $this, 'conditional_enqueue_FA' ));
			add_action('wp_head', array( $this, 'enqueue_front_end' ));
		}

	

		// PUBLIC STATIC FUNCTIONS
		public static function get_version() {
			return AvailableFAMenus::$version;
		}
		
		public function admin_enqueues() {
			wp_enqueue_script( 'afa-admin-js', plugin_dir_url( __FILE__ ). 'js/admin-screen.js', array('jquery'), null, false );	
			
			$this->conditional_enqueue_FA();

		}

		public function enqueue_front_end() {
				wp_enqueue_script( 'afa-front-end-js', plugin_dir_url( __FILE__ ). 'js/front-end.js', array('jquery'), null, false );
		}

		public function admin_init() {
			register_setting( 'afa_settings', 'afa-include' );
		}
		
		
		public function register_activation() {
		}

		public function register_deactivation() {
		}


		// Function to display tool menu addition 
		public function add_afa_fa_page() {

			add_submenu_page( 
				'options-general.php', 
				__( 'FA Menu Icons', 
					'fa-menu-icons' ), 
				__( 'FA Menu Icons', 
				'fa-menu-icons' ), 
				$this->capability, 
				'fa-menu-icons', 
				array( $this, 'render_available_fa_page' ) );
		}


		public function render_available_fa_page() {
			?>
			<style>
				#afa-settings-window,
				#afa-settings-window p {
					color: #555;
					font-size: 1.1em;
				}
				#afa-settings-window h1,
				#afa-settings-window h2,
				#afa-settings-window h3 {
					color: #555;
				}
				.fa-settings {
					margin-top: 2em;
				}
				.afa-usage {
					background-color: #fff;
					display: inline-block;
					padding: 20px;
				}
				.afa-included {
					color: green;
				}
				.afa-not-included {
					color: red;
				}
				.afa-usage ul {
					list-style-type: disc;
					margin-left: 30px;
				}
				.include-select {
					font-size: 1.25rem;
				}
				.afa-bold {
					font-weight: bold;
				}
				.afa-in,
				.afa-out {
					display: inline-block;
					padding: 10px;
					border-radius: 5px;
					margin-bottom: 20px;
				}
				.afa-in { background-color: rgba(139, 203, 174, 0.8); }
				.afa-out { background-color: rgba(138, 154, 173, 0.8); }
				span.fa {
					font-family: inherit;
				}

			</style>

			<?php
				$include = get_option('afa-include'); 
			?>
			<div id="afa-settings-window">
				
				
				<h1><i class="fa fa-cogs" aria-hidden="true"></i> FA Menu Icons Settings</h1>	
				<div class="fa-settings">
					<form method="post" action="options.php" id="afa-settings-submit">
						<?php settings_fields( 'afa_settings' ); ?>
						<?php do_settings_sections( 'afa_settings' ); ?>
						<div>
						    <input <?php echo ($include == "1") ? "checked" : "" ?>  type="checkbox" id="afa-include" name="afa-include" value="1">
						    <label for="afa-include">Enqueue FontAwesome 4.7.0?</label>
						  </div>
					</form>
					<?php 

						if( $include && $include == "1") {
							echo "<div class='afa-in'><span>Font Awesome <b>is</b> currently being included by the plugin.</span></div>";
						} else {
							update_option('afa-include', "0");
							echo "<div class='afa-out'><span>Font Awesome <b>is not</b> currently being included by the plugin.</span></div>";
						}
					?>
				</div>
				
				<div class="afa-usage">
					<h2>Usage</h3>
					<p>
						To use Font Awesome menu icons, follow the outlined steps:
					</p>
					<ul>
						<li>If Font Awesome is not already enqueued by your theme, select 'Include Font Awesome' from the Select field above and save changes.</li>
						<li>Navigate to the 'Appearance &gt; Menus' admin screen.</li>
						<li>Locate the CSS Classes input under the Menu Item you would like to add an icon to.</li>
						<li>Add the class name in the <b>appropriate format</b>.</li>
						<li>To add a blank space before a menu item to provide consistent spacing, add <b>'icon-blank'</b> as a class in its CSS class input field.</li>
					</ul>
					<div>
						<h3>Examples</h4>
						<ul>
							<li><span class="afa-bold">icon-home</span> = fa-home</li>
							<li><span class="afa-bold">icon-pencil</span> = fa-pencil</li>
							<li><span class="afa-bold">icon-blank</span> - This will add a formatted blank in place of an icon.</li>
						</ul>
					</div>
				</div>
			</div>
			
			<?php
	
		} 

		public function conditional_enqueue_FA() {
			$include = get_option( 'afa-include' );

			if( $include && $include == "1" ) {
				wp_enqueue_style( 'available-fa-css', plugin_dir_url( __FILE__ ) . "resources/fa-4.7.0/css/font-awesome.min.css" );
			}	else {	
			}
			?>
			<?php

		}

		private function initialize_settings() {
		}	
	}

	AvailableFAMenus::Instance();
}
?>