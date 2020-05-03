$(document).ready(function(){
  $('.dropdown-submenu a.test').on("click", function(e){
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
  });
});

$(".gameCard").click(function(){
  window.location.href = "/html/Player - Game Detail.html";
});

$(".showGameCard").click(function(){
  $("#gameDetailModal").modal("show")
});