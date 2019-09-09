(function($) {
  "use strict";

  /**
   * All of the code for your admin-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */
  $(function() {
    $("body.block-editor-page").addClass("folded");

    $('.folded #adminmenuwrap').hoverIntent({
      timeout: 400,
      over: showNav,
      out: hideNav,
    })

		function showNav(event) {
			var menuHeight = $("body:not(.folded) #adminmenuwrap").height();
        $("body").removeClass("folded sticky-menu");
        $("#wpcontent").css("min-height", menuHeight);
		}
		function hideNav() {
      $("body").addClass("folded sticky-menu");
    }

    // $(".folded #adminmenuwrap")
    //   .on("mouseover mouseenter touchstart", function() {
    //     var menuHeight = $("body:not(.folded) #adminmenuwrap").height();
    //     $("body").removeClass("folded sticky-menu");
    //     $("#wpcontent").css("min-height", menuHeight);
    //   })
    //   .on("mouseleave mouseout touchend touchcancel", function() {
    //     $("body").addClass("folded sticky-menu");
    //   });

    // $(".wp-menu-open").hoverIntent({
    //   over: showSub,
    //   out: hideSub
    // });

    function showSub(event) {
      console.log(event.target);
      $(event.target).closest("ul").addClass("covertnine-menu-in");
    }

    function hideSub(event) {
      console.log("cool");
      // $(event.target).closest("ul").removeClass("covertnine-menu-in");
    }

  });
})(jQuery);
