<?php

/*
Copyright 2015 Andy Wilkerson, Jesse Petersen.

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
*/


if (!class_exists('Customizer_Remove_All')) :
class Customizer_Remove_All {
	private static $instance;


  /**
  * Allows only one instance of Customizer_Remove_All in memory.
  */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Customizer_Remove_All ) ) {

			// Start your engines!
			self::$instance = new Customizer_Remove_All;

			// Load the structures to trigger initially
			add_action( 'plugins_loaded', array( self::$instance, 'load_languages' ) );
			add_action( 'init', array( self::$instance, 'init' ), 10 ); // was priority 5
			add_action( 'admin_init', array( self::$instance, 'admin_init' ), 10 ); // was priority 5

		}
		return self::$instance;
	}

	/**
  * Run all plugin stuff on init.
  */
	public function init() {

		// Remove customize capability
		add_filter( 'map_meta_cap', array( self::$instance, 'filter_to_remove_customize_capability'), 10, 4 );
	}

	/**
  * Run all of our plugin stuff on admin init.
  */
	public function admin_init() {

		// Drop some customizer actions
		remove_action( 'plugins_loaded', '_wp_customize_include', 10);
		remove_action( 'admin_enqueue_scripts', '_wp_customize_loader_settings', 11);

		// Manually overrid Customizer behaviors
		add_action( 'load-customize.php', array( self::$instance, 'override_load_customizer_action') );
	}

	/**
  * Remove customize capability
  *
  * This needs to be in public so the admin bar link for 'customize' is hidden.
  */
	public function filter_to_remove_customize_capability( $caps = array(), $cap = '', $user_id = 0, $args = array() ) {
		if ($cap == 'customize') {
			return array('nope'); // thanks @ScreenfeedFr, http://bit.ly/1KbIdPg
		}

		return $caps;
	}

	/**
  * Manually overriding specific Customizer behaviors
  */
	public function override_load_customizer_action() {
		// If accessed directly
		wp_die( __( 'The Customizer is currently disabled.', 'spine' ) );
	}

} // End Class
endif;

/**
* The main function. Use like a global variable, except no need to declare the global.
*/
function Customizer_Remove_All() {
	return Customizer_Remove_All::instance();
}

// GO!
Customizer_Remove_All();
