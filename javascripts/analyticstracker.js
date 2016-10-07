/*
 * Custom Analytics Tracker scripts
 */
;
(function ($) {
	//"use strict";

	var analyticstracker_ga = {

		// Start Functions
		startAt: function () {
			analyticstracker_ga.EventUrl();
		},

		//Event for Download, Email, Phone number, Outbound links
		EventUrl: function () {
			$('a').click(function(e) {
				var this_at = $(this);
				var href_at = this_at.prop('href').split('?')[0];
				var ext_at = href_at.split('.').pop();
				if ('7z, ai, avi, cbr, csv, doc, docx, exe, gz, jar, midi, mov, mp3, pdf, pdn, pez, pot, ppt, pptx, psd, pub, rar, tar, torrent, tsv, txt, wav, wma, wmv, wwf, xls, xlsx, zip'.indexOf(ext_at) !== -1) {
					ga('send', 'event', 'Download', ext_at, href_at);
				}
				else if (href_at.toLowerCase().indexOf('mailto:') === 0) {
					ga('send', 'event', 'Email', href_at.substr(7));
				}
				else if (href_at.toLowerCase().indexOf('tel:') === 0) {
					ga('send', 'event', 'Phone number', href_at.substr(4));
				}
				else if ((this.protocol === 'http:' || this.protocol === 'https:') && this.hostname.indexOf(document.location.hostname) === -1) {
					ga('send', 'event', 'Outbound', this.hostname, this.pathname);
				}

			});
			//Event for Error 404
			if($('body').hasClass('error404')) {
				ga('send', 'event', 'Error', '404', 'page: ' + document.location.pathname + document.location.search + ' ref: ' + document.referrer, {'nonInteraction': 1});
			}
			//Event for Scroll Depth
			$.scrollDepth({
				pixelDepth: false,
				userTiming: false,
				gtmOverride: false,
			});
		},
	};

	$(document).ready(function () {
		analyticstracker_ga.startAt();
	});
})(jQuery);
