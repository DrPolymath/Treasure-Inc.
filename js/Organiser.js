//navbar dropdown
$(document).ready(function(){
  $('.dropdown-submenu a.test').on("click", function(e){
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
  });
  $("#searchFilter").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#data tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});

//Organiser - Add Game
$('#customFile').on('change',function(){
  //get the file name
  var fileName = $(this).val();
  //replace the "Choose a file" label
  $(this).next('.custom-file-label').html(fileName);
})

$(".showGameCard").click(function(){
  window.location.href = "/html/Organiser - Game Detail.html";
});

$("#teamList").click(function(){
  $("#teamListPanel").slideToggle();
});

$(".notificationMessage").click(function(){
  $("#messageModal").modal("show")
});

function deleteGame(){
  if (window.confirm("Do you really want to delete this game?")) { 
  }
}

function deleteAccount(){
  if (window.confirm("Do you really want to delete this account?")) { 
  }
}

$(".showGamesCard").click(function(){
  $("#gameDetailModal").modal("show")
});