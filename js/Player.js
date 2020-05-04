$(document).ready(function(){
  $('.dropdown-submenu a.test').on("click", function(e){
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
  });

  $("#searchFilter").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#gameData .cardRow .gameCard").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});

$(".gameCard").click(function(){
  window.location.href = "/html/Player - Game Detail.html";
});

$(".showGameCard").click(function(){
  $("#gameDetailModal").modal("show")
});

$(".notificationMessage").click(function(){
  $("#messageModal").modal("show")
});

function deleteAccount(){
  if (window.confirm("Do you really want to delete this account?")) { 
  }
}

$(".gamesCard").click(function(){
  $("#gameDetailModal").modal("show")
});
