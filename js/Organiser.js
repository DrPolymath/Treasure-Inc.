//Navbar Dropdown

$(document).ready(function(){
  $('.dropdown-submenu a.test').on("click", function(e){
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
  });

  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("greetingContainer").innerHTML=this.responseText;
    }
  }
  xmlhttp.open("GET","../php/Session.php",true);
  xmlhttp.send();
});

$(".notificationMessage").click(function(){
  $("#messageModal").modal("show")
});

//Organiser

$(".showGameCard").click(function(){
  window.location.href = "../html/Organiser - Game Detail.html";
});

//Organiser - Add Game

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

//Organiser - Game Detail

function triggerClickUpdate(){
  $("#updateGameModal").modal("hide")
  document.querySelector("#gamePictureUpdate").click();
}

function displayImageUpdate(e) {
  if (e.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e){
      document.querySelector('#gamePicUploadUpdate').setAttribute('src', e.target.result);
    }
    reader.readAsDataURL(e.files[0]);
  }
  $("#updateGameModal").modal("show")
}

function deleteGame(){
  if (window.confirm("Do you really want to delete this game?")) { 
  }
}

$("#teamList").click(function(){
  $("#teamListPanel").slideToggle();
});

$(document).ready(function(){
  $("#searchFilter").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#data tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});

//Organiser - Profile

$(".showGamesCard").click(function(){
  $("#gameDetailModal").modal("show")
});

function deleteAccount(){
  if (window.confirm("Do you really want to delete this account?")) { 
  }
}


// $('#customFile').on('change',function(){
//   //get the file name
//   var fileName = $(this).val();
//   //replace the "Choose a file" label
//   $(this).next('.custom-file-label').html(fileName);
// })