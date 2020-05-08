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

function triggerClick(){
  document.querySelector("#gamePicture").click();
}

function displayImage(e) {
  if (e.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e){
      document.querySelector('#gamePicUpload').setAttribute('src', e.target.result);
    }
    reader.readAsDataURL(e.files[0]);
  }
}

function triggerClick(){
  $("#updateGameModal").modal("hide")
  document.querySelector("#gamePictureUpdate").click();
}

function displayImage(e) {
  if (e.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e){
      document.querySelector('#gamePicUploadUpdate').setAttribute('src', e.target.result);
    }
    reader.readAsDataURL(e.files[0]);
  }
  $("#updateGameModal").modal("show")
}