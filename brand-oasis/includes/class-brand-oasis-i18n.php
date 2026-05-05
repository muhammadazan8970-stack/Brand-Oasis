<?php

/**
 * Define the internationalization functionality.
 */
class Brand_Oasis_i18n {

	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'brand-oasis',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

}
