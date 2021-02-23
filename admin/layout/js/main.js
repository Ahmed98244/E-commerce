$(function () {
  "use strict";

  // dashboard
  $(".icon").click(function () {
    $(this)
      .toggleClass("selected")
      .parent()
      .next(".list-group")
      .fadeToggle(150);

    if ($(this).hasClass("selected")) {
      $(this).html('<i class="fa fa-minus fa-lg"></i>');
    } else {
      $(this).html('<i class="fa fa-plus fa-lg"></i>');
    }
  });

  // fire the selectboxit
  $("select").selectBoxIt({
    autoWidth: false,
  });

  // hide place holder on focus

  $("[placeholder]")
    .focus(function () {
      $(this).attr("data-text", $(this).attr("placeholder"));
      $(this).attr("placeholder", "");
    })
    .blur(function () {
      $(this).attr("placeholder", $(this).attr("data-text"));
    });

  // add asterisk to required filed
  $("input").each(function () {
    if ($(this).attr("required") === "required") {
      $(this).after('<span class="asterisk">*</span>');
    }
  });

  // convert password field to text field on hover
  var passFiled = $(".password");

  $(".show-pass").hover(
    function () {
      passFiled.attr("type", "text");
    },
    function () {
      passFiled.attr("type", "password");
    }
  );

  // confirmation message on button
  $(".confirm").click(function () {
    return confirm("Are you sure?");
  });

  // category view option  not work
  $(".cat h3").click(function () {
    $(this).next(".full-view").fadeToggle(200);
  });

  $(".option span").click(function () {
    $(this).addClass("active").siblings("span").removeClass("active");

    if ($(this).data("view") === "full") {
      $(".cat .full-view").fadeIn(200);
    } else {
      $(".cat .full-view").fadeOut(200);
    }
  });
  // show delete button on chiled cat

  $(".child-link").hover(
    function () {
      $(this).find(".show-delete").fadeIn(400);
    },
    function () {
      $(this).find(".show-delete").fadeOut(400);
    }
  );
});
