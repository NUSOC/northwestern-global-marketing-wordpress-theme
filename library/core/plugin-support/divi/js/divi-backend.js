// Extend Backbone so that we can get the view tied to a specific element
(function($) {

    // Proxy the original Backbone.View setElement method:
    // See: http://backbonejs.org/#View-setElement

    var backboneSetElementOriginal = Backbone.View.prototype.setElement;

    Backbone.View.prototype.setElement = function(element) {
        if (this.el != element) {
            $(this.el).backboneView('unlink');
        }

        $(element).backboneView(this);

        return backboneSetElementOriginal.apply(this, arguments);
    };

    // Create a custom selector to search for the presence of a 'backboneView' data entry:
    // This avoids a dependency on a data selector plugin...

    $.expr[':'].backboneView = function(element, intStackIndex, arrProperties, arrNodeStack) {
        return $(element).data('backboneView') !== undefined;
    };

    // Plugin internal functions:

    var registerViewToElement = function($el, view) {
        $el.data('backboneView', view);
    };

    var getClosestViewFromElement = function($el, viewType) {
        var ret = null;

        viewType = viewType || Backbone.View;

        while ($el.length) {
            $el = $el.closest(':backboneView');
            ret = $el.length ? $el.data('backboneView') : null;

            if (ret instanceof viewType) {
                break;
            }
            else {
                $el = $el.parent();
            }
        }

        return ret;
    };

    // Extra methods:

    var methods = {

        unlink: function($el) {
            $el.removeData('backboneView');
        }

    };

    // Plugin:

    $.fn.backboneView = function() {
        var ret = this;
        var args = Array.prototype.slice.call(arguments, 0);

        if ($.isFunction(methods[args[0]])) {
            methods[args[0]](this);
        }
        else if (args[0] && args[0] instanceof Backbone.View) {
            registerViewToElement(this.first(), args[0]);
        }
        else {
            ret = getClosestViewFromElement(this.first(), args[0]);
        }

        return ret;
    }

})(jQuery);

(function($){
  $(document).ready( function() {
    // Clear Divi local storage to prevent caching of dynamic fields
    for(var prop in localStorage) {
        if(prop.match(/^et_pb_templates_et_pb_nu_gm_[a-z_]+$/)) {
            localStorage.removeItem(prop);
        }
    }

    setTimeout( function(){
      // Get Backbone AppView from main element
      var pageBuilderView = $('#et_pb_main_container').backboneView();

      // Store original createLayoutFromContent function from AppView
      pageBuilderView.createLayoutFromContentOriginal = pageBuilderView.createLayoutFromContent;

      // Override createLayoutFromContent function with one that rewrites the content parameter
      pageBuilderView.createLayoutFromContent = function( content, parent_cid, inner_shortcodes, additional_options ) {
        // Prevent standard sections from being the defaut, instead default to fullwidth section
        if( typeof content !== 'undefined' && content == '[et_pb_section][et_pb_row][/et_pb_row][/et_pb_section]' )
          content = '[et_pb_section fullwidth="on" specialty="off" admin_label="Section"][/et_pb_section]';

        // Call original version of createLayoutFromContent function with updated content parameter
        pageBuilderView.createLayoutFromContentOriginal( content, parent_cid, inner_shortcodes, additional_options );
      };
    }, 200 );
  });
})(jQuery);
