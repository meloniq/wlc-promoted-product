<?php

if ( ! defined( 'WLC_PP_TD' ) ) {
	define( 'WLC_PP_TD', 'wlc-promoted-product' );
}

// Load the composer autoloader to use WP Mock
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// Bootstrap WP_Mock to initialize built-in features
WP_Mock::bootstrap();
