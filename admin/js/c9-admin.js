(function($) {
  "use strict";

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
