(function($){

  $(document).ready(function () {

      // adds .responsive-table wrapper to table classes
      $("table").each(function () {
          $(this).wrap('<div class="responsive-table"></div>');
      });

      // zebra striping for tables
      $("table tr").mouseover(function () {
          $(this).addClass("hover");
      }).mouseout(function () {
          $(this).removeClass("hover");
      });
      $("table tr:odd").addClass("stripe");

      function switchTab(target) {
          $parent = target.parents('#tabs-container');
          $parent.find('#tab-content>div').hide();
          $parent.find('#tabs a').removeClass('active').attr('aria-selected', 'false');
          $(target.attr('href')).show();
          target.addClass('active').attr('aria-selected', 'true');
      }

      // tab clicks
      $('#tabs a[aria-controls]').click(function (e) {
          switchTab($(this));
          e.preventDefault();
      });

      // select first tab when page loads
      if ($('#tabs a[aria-controls]').length) {
      	$('#tabs').each(function(index, el) {
          switchTab($(this).find('[aria-controls]').first());
        });
      }

      // flip image on hover
      $('.photo-feature').hover(
          function () {
            if($(this).find('.front').length && $(this).find('.back').length) {
              flipCard($(this));
            }
          },
          function () {
            if($(this).find('.front').length && $(this).find('.back').length) {
              unflipCard($(this));
            }
          }
      );
      $('.photo-feature').click(function (e) {
          if ($(this).find('.front').is(":visible") && $(this).find('.back').length) {
              flipCard($(this)); // hover was not triggered, so flip on click
              e.preventDefault();
          } // else goodbye!
      });
      function flipCard(cardObj) {
          // var img_height = cardObj.find('img').css("height");
          var img_height = cardObj.find('img')[0].getBoundingClientRect().height;
          cardObj.find('.front').height(img_height);
          cardObj.find('.back').height(img_height);
          cardObj.find('.front').hide();
          cardObj.find('.back').show();
      }
      function unflipCard(cardObj) {
          // var img_height = cardObj.find('img').css("height");
          var img_height = cardObj.find('img')[0].getBoundingClientRect().height;
          cardObj.find('.front').height('auto');
          cardObj.find('.back').height('auto');
          cardObj.find('.front').show();
          cardObj.find('.back').hide();
      }

      // expandable mobile elements
  	var _time = 100; // transition time

  	// open search
  	$(".mobile-search-link").click(function (e) {
  		// close menu
  		$("#mobile-nav").slideUp(_time).attr({
  			'aria-expanded': 'false',
  			'aria-hidden': 'true'
  		});
  		$(".mobile-nav-link").removeClass('open').children(":first").html('Open menu');
  		var el = $("#mobile-search");
  		// open search
  		if ($(el).is(":hidden")) {
  			$(el).slideDown(_time).attr({
  				'aria-expanded': 'true',
  				'aria-hidden': 'false'
  			});
  			$(".mobile-search-link").addClass('open').children(":first").attr({'aria-label': 'close search'});
              $("#q-mobile").focus();

  		// close search
  		} else {
  			$(el).slideUp(_time).attr({
  				'aria-expanded': 'false',
  				'aria-hidden': 'true'
  			});
  			$(".mobile-search-link").removeClass('open').children(":first").attr({'aria-label': 'open search'});
  		}
  		e.preventDefault();
  	});

      // mobile nav hidden
      $('#mobile-nav').attr({
  		'aria-expanded': 'false',
  		'aria-hidden': 'true'
  	});

  	// open menu
  	$(".mobile-nav-link").click(function (e) {
          e.preventDefault();

  		// close search
  		$("#mobile-search").slideUp(_time).attr({
  			'aria-expanded': 'false',
  			'aria-hidden': 'true'
  		});
  		$(".mobile-search-link").removeClass('open').children(":first").attr({'aria-label': 'open search'});
  		var el = $("#mobile-nav");
  		// open menu
  		if ($(el).is(":hidden")) {
      		$(el).slideDown(_time, function() {
  				$('#mobile-nav').children('ul:first-child').children('li:first-child').children('a:first-child').focus();
  			});
  			$(el).attr({
  				'aria-expanded': 'true',
  				'aria-hidden': 'false'
  			});
  			$(".mobile-nav-link").addClass('open').children(":first").html('Close Menu');
  		// close menu
  		} else {
  			$(el).slideUp(_time).attr({
  				'aria-expanded': 'false',
  				'aria-hidden': 'true'
  			});
  			$(".mobile-nav-link").removeClass('open').children(":first").html('Open menu');
  		}
	});
	  
	// scroll to top arrow
	$('main').append('<a href="#nu" id="scrollup" aria-label="Return to the top of the page">Back to Top</a>');
	var amountScrolled = 200; // pixels scrolled before button appears
	$(window).scroll(function () {
		if ($(window).scrollTop() > amountScrolled) {
			$('a#scrollup').fadeIn('slow');
		} else {
			$('a#scrollup').fadeOut('slow');
		}
	});
	$('a#scrollup').click(function () {
		$('html, body').animate({
			scrollTop: 0
		}, 400); // speed
		return false;
	});

  	// close mobile search, nav on window resize
  	$(window).on('resize', function () {
  		if ($('#mobile-links').is(":hidden")) {
  			$("#mobile-search").hide();
  			$("#mobile-nav").hide();
  			$('.mobile-search-link').removeClass('open');
  			$('.mobile-nav-link').removeClass('open');
  		}
  	});

      // mobile drill down navigation
      $('.arrow a').click(function(e) {
          e.preventDefault();
  		var clicked = $(this);
  		// hide all
  		var parents = $(clicked).parentsUntil('#mobile-nav', 'ul');
  		var lists = $('.arrow a').parent().next('ul').not(parents);

  		$.each(lists,(function(index, obj) {
              $(obj).parent().find('.open').removeClass('open');
  			$(obj).slideUp('fast');
  		}));
  		// open the clicked item
  		var item = clicked.parent().next('ul');
  		if (item.is(':hidden')) {
  			item.slideDown('fast', function() {
  				item.children('li:first-child').children('a').focus();
  			});
              item.attr({
      			'aria-hidden': 'false',
             	    'aria-expanded': 'true'
  			});
  			clicked.parent().addClass('open');
  			clicked.find('span').html('Collapse');

  		} else {
  			item.slideUp('fast', function() {
  				clicked.closest('li').children(':first-child').focus();
  			});
              item.attr({
      			'aria-hidden': 'true',
             	    'aria-expanded': 'false'
  			});
  			clicked.parent().removeClass('open');
  			clicked.find('span').html('Expand');
  		}
  	});

	// expand/collapse for faq's
	$('.expander .plus, .expander .minus').click(function () {
		$(this).toggleClass('plus minus');
		$(this).next('div').toggleClass('showme hideme');
	});

	// expander matching hash
	$('.expander ' + location.hash).click();
	
	// Responsive iframe wrappers
	$('iframe').each(function () {
		var $iframe = $(this);
		var parser = document.createElement('a');
		parser.href = $iframe.attr('src');
		var hostname = parser.hostname;
		var wwwRe = /^(?:www\.)?(.*)$/;
		hostname = hostname.replace(wwwRe, "$1");
		switch (hostname) {
			case 'vimeo.com':
			case 'youtube.com':
				if (!$iframe.parents('.responsive-container').length) {
					$iframe.wrap('<div class="iframe-video-wrapper responsive-container"></div>');
				}
				break;
			default:
				break;
		}
	});

  });

  // accessible quick links dropdown in #top-bar #right
  $(document).ready(function() {
      $("#right").accessibleDropDown();
  });
  $.fn.accessibleDropDown = function ()
  {
      var el = $(this);
      $("a", el).focus(function() {
          $(this).parents("li").addClass("hover");
      }).blur(function() {
          $(this).parents("li").removeClass("hover");
      });
  }

})(jQuery);
