'use strict';

( function( $ ) {
	function expandBtn() {
		$( 'button.mc-expand svg' ).html( '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 99.79 108.63" style="enable-background:new 0 0 99.79 108.63;" xml:space="preserve"><style type="text/css">.st0{fill: #FFFFFF;}</style> <g><path class="st0" d="M59.12,24.39h-6.91v6.91h-4.6v-6.91H40.7v-4.6h6.91v-6.91h4.6v6.91h6.91V24.39z M49.47,105L49.47,105c-17.22,0-31.99-12.61-33.19-31.54c11.5-10.75,13.22-18.44,14.68-28.32c9.67,12.83,18.67,17.02,33.67,18.19c9.53,0.75,14.34-1.24,16.66-3.66C88.1,83.73,71.27,105.24,49.47,105z M78.05,66.59c-4.72,1.48-10.18,1.45-13.67,1.34c-10.9-0.34-23-4.93-30.71-12.56c-2.47,8.74-7.42,14.75-12.62,19.88c1.56,14.38,13.6,25.11,28.42,25.14C65.07,100.36,80.14,87.64,78.05,66.59z M38.84,71.35c-2.06,0-3.74,1.67-3.74,3.74c0,2.07,1.67,3.74,3.74,3.74c2.07,0,3.75-1.67,3.75-3.74C42.58,73.02,40.9,71.35,38.84,71.35z M59.9,71.35c-2.06,0-3.74,1.68-3.74,3.74c0,2.07,1.68,3.74,3.74,3.74c2.07,0,3.74-1.68,3.74-3.74C63.64,73.03,61.96,71.35,59.9,71.35z M99.15,25.11L86.22,46.87c2.39,3.97,4.32,8.32,5.58,13.05c1.33,4.98,3.67,21.75,3.67,27.58c0,6.62-2.05,12.21-5.78,15.74c-3.4,3.27-8.39,5.39-12.72,5.39c-0.32,0-4.94-0.02-4.94-0.02c-1.27,0-2.3-1.03-2.3-2.3c0-1.27,1.03-2.3,2.3-2.3c0,0,4.74,0.02,4.94,0.02c3.15,0,6.98-1.65,9.54-4.12c2.81-2.66,4.35-7.07,4.35-12.41c0-5.32-2.3-21.86-3.51-26.4c-1.23-4.63-3.14-8.89-5.55-12.71c-3.31-0.9-17.91-5.58-31.9-5.58c-14,0-27.98,4.67-32.03,5.61c-2.34,3.78-4.24,8.01-5.56,12.68c-1.24,4.38-2.95,21.45-2.95,26.29c0,3.11,0.57,8.85,4.35,12.44c2.68,2.58,6.31,4.13,9.71,4.16h4.2c1.27,0,2.3,1.03,2.3,2.3c0,1.27-1.03,2.3-2.3,2.3h-4.22c-4.57-0.04-9.37-2.07-12.87-5.44C5.5,98.41,4.75,91.24,4.75,87.4c0-5.31,1.75-22.68,3.13-27.55c1.35-4.78,3.28-9.14,5.63-13.08C11.8,43.91,2.23,28.95,0.65,26.29c-1.08-1.81-0.79-4.12,0.7-5.61C14.49,7.54,32.19,0,49.9,0c18.71,0,35.95,6.93,48.55,19.51C99.93,20.99,100.22,23.3,99.15,25.11z M95.19,22.76C83.59,11.17,67.31,4.6,49.9,4.6c-17.41,0-33.69,7.73-45.29,19.33c3.2,5.37,9.18,14.33,12.53,19.99c6.37-1.41,18.02-5.71,32.76-5.71c14.75,0,26.41,4.29,32.76,5.68L95.19,22.76z"/></g> <g><path class="st0" d="M37.35,89.35c3.99,2.56,8.59,4.14,13.33,4.41c4.26,0.25,9.94,0.04,12.8-3.67c0.65-0.84,0.37-1.92-0.62-2.32c-1.03-0.41-2.46-0.04-3.14,0.84c-0.81,1.05-2.11,1.33-3.36,1.51c-1.75,0.26-3.56,0.29-5.32,0.11c-3.66-0.38-7.18-1.59-10.28-3.57c-0.97-0.62-2.31-0.49-3.2,0.23C36.83,87.47,36.39,88.74,37.35,89.35L37.35,89.35z"/></g></svg>' ).css( 'height', '50px' ).css( 'width', '55px' );
	}

	if ( -1 < window.location.href.indexOf( 'pay-my-bill' ) ) {
		$( document ).ready( function() {
			$( '.system-notification-wrapper ul' ).each( function( i, el ) {
				$( el ) // Wrap each list in a dropdown div
					.wrap( '<div class="dropdown"></div>' ) // Add dropdown-menu class to list
					.addClass( 'dropdown-menu' ) // Edit the CSS for each list so that it works visually as a dropdown
					.css( {
						'width': '100%',
						'padding': 0,
						'margin': 0,
						'list-style': 'none'
					} ) // Find each list item and add a border to it
					.find( 'li' ).css( 'border-bottom', '1px solid #eeeeee' );
				$( el ).attr( 'aria-labelledby', 'notificationDropdown' ); // Insert the button that makes it work

				$( '<button id="notificationDropdown" class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Select your facility <i class="fa fa-caret-down" aria-hidden="true"></i></button>' ).insertBefore( $( el ) ); // Finally, if the page is viewed within an iframe…

				if ( window.location !== window.parent.location ) {
					$( el ).find( 'li a' ).attr( 'target', '_blank' );
				}
			} );
		} );
	}
} )( jQuery );
