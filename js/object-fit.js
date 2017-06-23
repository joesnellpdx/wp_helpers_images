/**
 * Fallback for css object-fit declaration
 *
 * uses data attribute value 'data-fallback-img' to place background image on parent
 *
 */

(function ($) {
	'use strict';

	var objectFit = '';

	// use if Modernizr feature detection is made available, otherwise use below.
	if ( !Modernizr.objectfit ) {
		objectFit = true;
	}

	// Use the following if Modernizr feature detection is not made avialable and remove above
	//if('object-fit' in document.body.style) {
	//	objectFit = true;
	//}

	var elems = {
		imgfit: document.querySelectorAll('.img-fit')
	};

	var msHasClass = function( el, cls ) {
		return el.className && new RegExp( '(\\s|^)' + cls + '(\\s|$)' ).test( el.className );
	};

	var imgFit = function () {

		if( !objectFit ) {
			var elements = elems.imgfit;

			for (var i = 0; i < elements.length; i++) {
				if ( msHasClass(elements[i], 'compat-object-fit') ) {
					// do nothing
				} else {
					var el = elements[i],
						fbimg = $( elements[i]).find('img').attr('data-fallback-img'),
						fbimgsm = $( elements[i]).find('img').attr('data-fallback-img-sm');

					if (fbimgsm && fbimgsm !== '' && window.matchMedia( '(max-width: 768px)' ).matches) {
						el
							.classList
							.add('compat-object-fit');
						el.style.backgroundImage = 'url( "' + fbimgsm + '" )';
					} else if (fbimg !== '') {
						el
							.classList
							.add('compat-object-fit');
						el.style.backgroundImage = 'url( "' + fbimg + '" )';
					}
				}

			}
		}
	};

	imgFit();

})(jQuery, document );