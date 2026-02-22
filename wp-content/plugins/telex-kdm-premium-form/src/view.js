
( function () {
	'use strict';

	function initForms() {
		var blocks = document.querySelectorAll(
			'.wp-block-telex-block-telex-kdm-premium-form'
		);

		var loadedTimestamp = Math.floor( Date.now() / 1000 );

		blocks.forEach( function ( block ) {
			var form = block.querySelector( '.kdm-ajax-form' );
			if ( ! form ) {
				return;
			}

			// Set the load timestamp into the hidden field for minimum input delay check.
			var loadedField = form.querySelector( 'input[name="_kdm_loaded"]' );
			if ( loadedField ) {
				loadedField.value = String( loadedTimestamp );
			}

			form.addEventListener( 'submit', function ( e ) {
				e.preventDefault();
				handleSubmit( block, form );
			} );
		} );
	}

	function handleSubmit( block, form ) {
		var submitBtn = form.querySelector( '.kdm-lp-form-submit' );

		// Prevent double-click: if already submitting, bail out.
		if ( submitBtn.disabled ) {
			return;
		}

		if ( ! submitBtn.getAttribute( 'data-original-label' ) ) {
			submitBtn.setAttribute( 'data-original-label', submitBtn.innerHTML );
		}
		var statusEl = form.querySelector( '.kdm-form-status' );
		var successMessage =
			block.getAttribute( 'data-success-message' ) ||
			'Thank you! Your message has been sent successfully.';

		if ( ! validateForm( form ) ) {
			showStatus(
				statusEl,
				'Please fill in all required fields correctly.',
				'error'
			);
			return;
		}

		submitBtn.disabled = true;
		submitBtn.textContent = 'Sending\u2026';
		hideStatus( statusEl );

		var formData = new FormData( form );
		formData.append( 'action', 'kdm_form_submit' );
		formData.append(
			'nonce',
			typeof kdmFormData !== 'undefined' ? kdmFormData.nonce : ''
		);

		var ajaxUrl =
			typeof kdmFormData !== 'undefined'
				? kdmFormData.ajaxUrl
				: '/wp-admin/admin-ajax.php';

		var xhr = new XMLHttpRequest();
		xhr.open( 'POST', ajaxUrl, true );

		xhr.onload = function () {
			var response;
			try {
				response = JSON.parse( xhr.responseText );
			} catch ( err ) {
				showStatus(
					statusEl,
					'An unexpected error occurred. Please try again.',
					'error'
				);
				resetButton( submitBtn );
				return;
			}

			if ( response.success ) {
				showStatus(
					statusEl,
					response.data.message || successMessage,
					'success'
				);
				form.reset();

				// Re-set the loaded timestamp after reset so subsequent submissions still work.
				var loadedField = form.querySelector( 'input[name="_kdm_loaded"]' );
				if ( loadedField ) {
					loadedField.value = String( Math.floor( Date.now() / 1000 ) );
				}

				// The submission token is consumed server-side. The user must
				// refresh the page (or we can fetch a new token) to submit again.
				// For now, show a message and keep the button disabled briefly.
				var tokenField = form.querySelector( 'input[name="_kdm_token"]' );
				if ( tokenField ) {
					tokenField.value = '';
				}

				resetTurnstile( form );

				// Re-enable the button after a short delay so user sees feedback.
				setTimeout( function () {
					resetButton( submitBtn );
				}, 2000 );
			} else {
				showStatus(
					statusEl,
					response.data.message ||
						'Something went wrong. Please try again.',
					'error'
				);
				resetButton( submitBtn );
			}
		};

		xhr.onerror = function () {
			showStatus(
				statusEl,
				'Network error. Please check your connection and try again.',
				'error'
			);
			resetButton( submitBtn );
		};

		xhr.send( formData );
	}

	function validateForm( form ) {
		var firstName = form.querySelector( '[name="first_name"]' );
		var lastName = form.querySelector( '[name="last_name"]' );
		var email = form.querySelector( '[name="email"]' );
		var message = form.querySelector( '[name="message"]' );

		if (
			! firstName.value.trim() ||
			! lastName.value.trim() ||
			! email.value.trim() ||
			! message.value.trim()
		) {
			return false;
		}

		// Validate email format.
		var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		if ( ! emailPattern.test( email.value.trim() ) ) {
			return false;
		}

		// Client-side length checks (mirrors server-side limits).
		if ( firstName.value.trim().length > 100 ) {
			return false;
		}
		if ( lastName.value.trim().length > 100 ) {
			return false;
		}
		if ( email.value.trim().length > 254 ) {
			return false;
		}
		if ( message.value.trim().length > 5000 ) {
			return false;
		}

		var phone = form.querySelector( '[name="phone"]' );
		if ( phone && phone.value.trim().length > 30 ) {
			return false;
		}

		return true;
	}

	function showStatus( el, message, type ) {
		if ( ! el ) {
			return;
		}
		el.textContent = message;
		el.className = 'kdm-form-status';
		el.classList.add(
			type === 'success' ? 'kdm-status-success' : 'kdm-status-error'
		);
		el.style.display = 'block';
	}

	function hideStatus( el ) {
		if ( ! el ) {
			return;
		}
		el.style.display = 'none';
		el.textContent = '';
		el.className = 'kdm-form-status';
	}

	function resetButton( btn ) {
		btn.disabled = false;
		btn.innerHTML = btn.getAttribute( 'data-original-label' ) || 'Send Message \u2192';
	}

	function resetTurnstile( form ) {
		var turnstileEl = form.querySelector( '.cf-turnstile' );
		if ( turnstileEl && typeof turnstile !== 'undefined' ) {
			turnstile.reset( turnstileEl );
		}
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initForms );
	} else {
		initForms();
	}
} )();
