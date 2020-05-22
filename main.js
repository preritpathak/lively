$(document).ready(function() {
  $(window).on("load", function() {
    $(".preloader")
      .delay(350)
      .fadeOut("slow"); // will fade out the white DIV that covers the website.
    $(".main-content")
      .delay(350)
      .fadeIn("slow");
  });
  var img1 = new Image();
  var img2 = new Image();
  var img3 = new Image();
  var img4 = new Image();
  var img5 = new Image();
  var img6 = new Image();
  // start preloading
  img1.src = "/images/00.jpg";
  img2.src = "/images/11.jpg";
  img3.src = "/images/22.jpg";
  img4.src = "/images/33.jpg";
  img5.src = "/images/44.jpg";
  img6.src = "/images/55.jpg";
  var n = 0;
  // $("form").submit(function(e){
  //   return false;
  // });
  $("#addInput").click(function() {
    $("<label>Enter Link #" + (n + 1) + "</label>")
      .attr({
        type: "text",
        for: "input-" + (n + 1),
        class: "input-label",
        label: "Input-label",
        name: "label-" + (n + 1)
      })
      .appendTo(".inputs");
    $("<input>")
      .attr({
        type: "text",
        class: "form-input",
        id: "input-" + (n + 1),
        name: "input-" + (n + 1)
      })
      .appendTo(".inputs");
    $("#inputsNumber").attr("value", n + 1);
    n++;
  });
  $("#start-live").click(function() {
    $(".live-form").css("display", "block");
    $(this).css("display", "none");
    $("#addInput").click();
  });
  var body = $(".background");
  const backgrounds = [
    "url(/images/00.jpg)",
    "url(/images/11.jpg)",
    "url(/images/22.jpg)",
    "url(/images/33.jpg)",
    "url(/images/44.jpg)",
    "url(/images/55.jpg)"
  ];
  var current = 0;

  function nextBackground() {
    body.css(
      "background-image",
      backgrounds[(current = ++current % backgrounds.length)]
    );
    setTimeout(nextBackground, 3000);
  }
  setTimeout(nextBackground, 3000);
  body.css("background-image", backgrounds[0]);
});
