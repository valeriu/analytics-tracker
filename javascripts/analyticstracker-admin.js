/*
 * Admin scripts for the "Other Products" page.
 * Fetches and renders external product recommendations.
 */
;(function ($) {
  //"use strict";

  var analyticstracker_ga_admin = {
    safeUrl: function (url) {
      if (typeof url !== 'string') {
        return '';
      }
      var trimmed = url.trim();
      if (/^https?:\/\//i.test(trimmed)) {
        return trimmed;
      }
      return '';
    },

    // Start Functions
    startSwp: function () {
      analyticstracker_ga_admin.ShowProduct();
    },

    //Init
    ShowProduct: function () {

        $.ajax( {
          url: 'https://dailydesigncafe.com/wp-json/wp/v2/post_product-api',
          data: {
			  per_page: 1,
			  filter: {
			  'orderby': 'rand'
		  	},
          },
          dataType: 'json',
          type: 'GET',
	          success: function ( data ) {
	            var $container = $( '.dailydesign_container' );
	            $container.empty();

	            $.each(data, function(i) {
                var item = data[i] || {};
                var title = (item.title && item.title.rendered) ? String(item.title.rendered) : '';
                var author = (item.product_meta && item.product_meta.ktnl_tf_author_username) ? String(item.product_meta.ktnl_tf_author_username) : '';
                var imageUrl = analyticstracker_ga_admin.safeUrl(item.ktnl_featured_image && item.ktnl_featured_image.media_details && item.ktnl_featured_image.media_details.sizes && item.ktnl_featured_image.media_details.sizes.medium && item.ktnl_featured_image.media_details.sizes.medium.source_url);
                var affiliateUrl = analyticstracker_ga_admin.safeUrl(item.product_meta && item.product_meta.ktnl_affiliate_url);
                var authorUrl = analyticstracker_ga_admin.safeUrl(item.product_meta && item.product_meta.ktnl_tf_author_url);

                if (!affiliateUrl || !authorUrl || !imageUrl) {
                  return;
                }

                var $themeWrap = $('<div/>', { class: 'dailydesign' });
                var $themeLink = $('<a/>', {
                  target: '_blank',
                  rel: 'noopener noreferrer',
                  title: title,
                  href: affiliateUrl + '?ref=stylishwp'
                });
                var $img = $('<img/>', {
                  src: imageUrl,
                  alt: title
                });

                $themeLink.append($img);
                $themeWrap.append($themeLink);

                var $authorWrap = $('<div/>', { class: 'dailydesign_by' });
                $authorWrap.append(document.createTextNode('Premium WordPress Theme created by: '));
                var $authorLink = $('<a/>', {
                  target: '_blank',
                  rel: 'noopener noreferrer',
                  title: author,
                  href: authorUrl + '?ref=stylishwp',
                  text: ' ' + author
                });
                $authorWrap.append($authorLink);

                $container.append($themeWrap, $authorWrap);
	            });
	          },
          cache: true
        } );

    },

  };

  jQuery(document).ready(function () {
    analyticstracker_ga_admin.startSwp();
  });

})(jQuery);
