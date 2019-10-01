/*
 * Scripts File
 *
 * This file should contain any js scripts you want to add to the site.
 * Instead of calling it in the header or throwing it inside wp_head()
 * this file will be called automatically in the footer so as not to
 * slow the page load.
*/


/*
 * Get Viewport Dimensions
 * returns object with viewport dimensions to match css in width and height properties
 * ( source: http://andylangton.co.uk/blog/development/get-viewport-size-width-and-height-javascript )
*/
function updateViewportDimensions() {
	var w=window,d=document,e=d.documentElement,g=d.getElementsByTagName('body')[0],x=w.innerWidth||e.clientWidth||g.clientWidth,y=w.innerHeight||e.clientHeight||g.clientHeight;
	return { width:x,height:y };
}
// setting the viewport width
var viewport = updateViewportDimensions();


/*
 * Throttle Resize-triggered Events
 * Wrap your actions in this function to throttle the frequency of firing them off, for better performance, esp. on mobile.
 * ( source: http://stackoverflow.com/questions/2854407/javascript-jquery-window-resize-how-to-fire-after-the-resize-is-completed )
*/
var waitForFinalEvent = (function () {
	var timers = {};
	return function (callback, ms, uniqueId) {
		if (!uniqueId) { uniqueId = "Don't call this twice without a uniqueId"; }
		if (timers[uniqueId]) { clearTimeout (timers[uniqueId]); }
		timers[uniqueId] = setTimeout(callback, ms);
	};
})();

// how long to wait before deciding the resize has stopped, in ms. Around 50-100 should work ok.
var timeToWaitForLast = 100;


/*
 * Here's an example so you can see how we're using the above function
 *
 * This is commented out so it won't work, but you can copy it and
 * remove the comments.
 *
 *
 *
 * If we want to only do it on a certain page, we can setup checks so we do it
 * as efficient as possible.
 *
 * if( typeof is_home === "undefined" ) var is_home = $('body').hasClass('home');
 *
 * This once checks to see if you're on the home page based on the body class
 * We can then use that check to perform actions on the home page only
 *
 * When the window is resized, we perform this function
 * $(window).resize(function () {
 *
 *    // if we're on the home page, we wait the set amount (in function above) then fire the function
 *    if( is_home ) { waitForFinalEvent( function() {
 *
 *	// update the viewport, in case the window size has changed
 *	viewport = updateViewportDimensions();
 *
 *      // if we're above or equal to 768 fire this off
 *      if( viewport.width >= 768 ) {
 *        console.log('On home page and window sized to 768 width or more.');
 *      } else {
 *        // otherwise, let's do this instead
 *        console.log('Not on home page, or window sized to less than 768.');
 *      }
 *
 *    }, timeToWaitForLast, "your-function-identifier-string"); }
 * });
 *
*/

/*
 * We're going to swap out the gravatars.
 * In the functions.php file, you can see we're not loading the gravatar
 * images on mobile to save bandwidth. Once we hit an acceptable viewport
 * then we can swap out those images since they are located in a data attribute.
*/
function loadGravatars() {
  // set the viewport using the function above
  viewport = updateViewportDimensions();
  // if the viewport is tablet or larger, we load in the gravatars
  if (viewport.width >= 768) {
  jQuery('.comment img[data-gravatar]').each(function(){
    jQuery(this).attr('src',jQuery(this).attr('data-gravatar'));
  });
	}
}

/*
 * Wrap all widgets in li tags to match GM markup.
*/
function wrapWidgets() {
  jQuery('#left-nav .widget.widget_search').remove();
  jQuery('#left-nav .widget').wrapAll('<ul></ul>');
  jQuery('#left-nav .widget').wrap('<li></li>');
}

/*
 * Apply GM classes to dropdown menu.
*/
function applyGmDropdownStyle() {
  jQuery('#top-nav').attr('aria-label', 'main navigation');
  jQuery('#mobile-nav').attr('aria-label', 'main mobile navigation');
  jQuery('body > footer .footer-content nav').attr('aria-label', 'footer navigation');
  // Make sure we only add intro section to fullwidth dropdowns
  if(jQuery('#top-nav').hasClass('narrow-dropdown')) {
    return;
  }
  jQuery('#top-nav #top-nav-inner > li.menu-item-has-children > ul').focusin(function (e) {
    jQuery(e.currentTarget).attr('aria-expanded', true);
  });
  jQuery('#top-nav #top-nav-inner > li.menu-item-has-children > ul').focusout(function (e) {
    jQuery(e.currentTarget).attr('aria-expanded', false);
  });
  jQuery('#top-nav #top-nav-inner > li.menu-item-has-children').hover(
    function (e) {
      jQuery(e.currentTarget).children('ul.sub-menu').attr('aria-expanded', true);
    },
    function (e) {
      jQuery(e.currentTarget).children('ul.sub-menu').attr('aria-expanded', false);
    },
  )
  jQuery('#top-nav ul.top-nav > li.menu-item-has-children > ul.sub-menu').each(function(){
    var $submenu = jQuery(this);
    var $submenuParentLink = $submenu.prev('a');

    // Generate markup for submenu intro (left side)
    var $submenuIntroLink = $submenuParentLink.clone().addClass('button').removeAttr('aria-haspopup').removeAttr('aria-expanded').attr({
      'aria-hidden': true,
      'tabindex': -1
    });
    $submenuIntroLink.find('span').remove()
    var $submenuIntro = $submenu.find('li.nav-intro').append($submenuIntroLink);
  });
}

/*
 * Make a copy of directory pager and add to top of page
*/
function cloneDirectoryPagerToTop() {
  if(jQuery('body').hasClass('post-type-archive-nu_gm_directory_item')) {
    $topPager = jQuery('#letter-pager-group-bottom').clone();
    $topPager.attr('id', 'letter-pager-group-top');
    $topPager.insertBefore(jQuery('.letter-group').first());
  }
}

/*
 * Put all your regular jQuery in here.
*/
jQuery(document).ready(function($) {
  // Fire Gravitar function
  // loadGravatars();

  // Fire Widget Wrapping Function
  wrapWidgets();

  // Apply GM classes to dropdown menu
  applyGmDropdownStyle();

  // Make a copy of directory pager and add to top of page
  cloneDirectoryPagerToTop();

});
