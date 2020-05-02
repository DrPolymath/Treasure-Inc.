//navbar dropdown
$(document).ready(function(){
  $('.dropdown-submenu a.test').on("click", function(e){
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
  });
});

//Organiser - Add Game
$('#customFile').on('change',function(){
  //get the file name
  var fileName = $(this).val();
  //replace the "Choose a file" label
  $(this).next('.custom-file-label').html(fileName);
})

$(".gameCard").click(function(){
  window.location.href = "/html/Organiser - Game Detail.html";
});

$("#teamList").click(function(){
  $("#teamListPanel").slideToggle();
});