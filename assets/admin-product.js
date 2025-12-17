jQuery( function ( $ ) {
	// Promoted options.
	function show_or_hide_promoted_product_fields( isPromotedProductEnabled ) {
		const $promotedProductFields = $( '.promoted_fields' );
		$promotedProductFields.toggle( isPromotedProductEnabled );
	}

	$( 'input#wlc_promoted_product_id' )
		.on( 'change', function () {
			const isPromotedProductEnabled = $( this ).is( ':checked' );
			show_or_hide_promoted_product_fields( isPromotedProductEnabled );

			$( 'input#wlc_promoted_product_expiry' ).trigger( 'change' );
		} )
		.trigger( 'change' );

	// Expiry options.
	function show_or_hide_expiry_fields( isExpiryEnabled ) {
		const $expiryFields = $( '.expiry_fields' );
		$expiryFields.toggle( isExpiryEnabled );
	}

	$( 'input#wlc_promoted_product_expiry' )
		.on( 'change', function () {
			const isExpiryEnabled = $( this ).is( ':checked' );
			show_or_hide_expiry_fields( isExpiryEnabled );
		} )
		.trigger( 'change' );

	// DateTime Picker.
	$( document.body )
		.on( 'wlc-init-datetimepickers', function () {
			$( '.datetime-picker-field, .datetime-picker' ).datetimepicker( {
				dateFormat: 'yy-mm-dd',
				timeFormat: 'HH:mm',
				timeOnlyTitle: wlc_admin_product_params.i18n_time_only_title,
				timeText: wlc_admin_product_params.i18n_time_text,
				hourText: wlc_admin_product_params.i18n_hour_text,
				minuteText: wlc_admin_product_params.i18n_minute_text,
				secondText: wlc_admin_product_params.i18n_second_text,
				currentText: wlc_admin_product_params.i18n_current_text,
				closeText: wlc_admin_product_params.i18n_close_text,
			} );
		} )
		.trigger( 'wlc-init-datetimepickers' );
} );
