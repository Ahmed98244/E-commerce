$(function () {
  "use strict";

  // fire the selectboxit
  $("select").selectBoxIt({
    autoWidth: false,
  });

  // switch between login & signup
  $(".login-page h1 span").click(function () {
    $(this).addClass("selected").siblings().removeClass("selected");

    $(".login-page form").hide();

    $("." + $(this).data("class")).fadeIn(100);
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
  // $("input").each(function () {
  // if ($(this).attr("required") === "required") {
  //   $(this).after('<span class="asterisk">*</span>');
  // }
  // });

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
});

// live-review
$(".live").keyup(function () {
  $($(this).data("class")).text($(this).val());
});
