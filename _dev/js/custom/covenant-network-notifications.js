( function( $ ) {
	$( document ).ready( function() {
		$( '.system-notification-wrapper ul' ).each( function( i, el ) {
			$( el )

				// Wrap each list in a dropdown div
				.wrap( '<div class="dropdown"></div>' )

				// Add dropdown-menu class to list
				.addClass( 'dropdown-menu notification-menu' )

				// Add the aria-labelledby attr
				.attr( 'aria-labelledby', 'systemNotificationDropdown' )

				// Find each list item and add a border to it
				.find( 'li' ).css( 'border-bottom', '1px solid #eeeeee' );

			// Insert the button that makes it work
			$( '<button id="systemNotificationDropdown" class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Select your facility <i class="fa fa-caret-down" aria-hidden="true"></i></button>' ).insertBefore( $( el ) );

			let listWidth = $( '.notification-menu' ).width(),
				containerWidth = $( '.system-notification-wrapper .dropdown' ).width();

			$( '#systemNotificationDropdown' ).css( {
				width: listWidth,
				'margin-left': ( containerWidth - listWidth ) / 2 + 'px',
				'margin-right': ( containerWidth - listWidth ) / 2 + 'px'
			} );

			$( '.notification-menu' ).css( 'width', $( this ).width() );

			// Finally, if the page is viewed within an iframe…
			if ( window.location !== window.parent.location ) {
				$( el ).find( 'li a' ).attr( 'target', '_blank' );
			}
		} );
	} );
} )( jQuery );
