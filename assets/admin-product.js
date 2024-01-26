jQuery( function ( $ ) {

	// DateTime Picker
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
