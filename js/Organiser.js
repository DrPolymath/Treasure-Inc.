//Navbar Dropdown
$(document).ready(function(){
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      if(this.responseText=="Not logged in!"){
        document.location.href='../html/SessionError.html';
      } else {
        document.getElementById("greetingContainer").innerHTML=this.responseText;
      }
    }
  }
  xmlhttp.open("GET","../php/Session.php",true);
  xmlhttp.send();

  $('.dropdown-submenu a.test').on("click", function(e){
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
  });
});

$(".notificationMessage").click(function(){
  $("#messageModal").modal("show")
});

//Organiser

$(document).ready(function(){
  $.ajax({
    url: "../php/CRUD Game.php",
    type: 'GET',
    data: "Card=Yes",
    success: function (data) {
      $('#CarouselInner').html(data);
    },
  });
});

// $(".showGameCard").click(function(){
//   window.location.href = "../html/Organiser - Game Detail.html";
// });

//Organiser - Add Game

function triggerClick(){
  document.querySelector("#GameImage").click();
}

function displayImage(e) {
  if (e.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e){
      document.querySelector('#GameImageUpload').setAttribute('src', e.target.result);
    }
    reader.readAsDataURL(e.files[0]);
  }
}

$('form#AddGameForm').submit(function(e) {
  e.preventDefault();    
  var formData = new FormData(this);
  var files = $('#GameImage')[0].files[0];
  formData.append('file',files);
  $.ajax({
    url: "../php/CRUD Game.php",
    type: 'POST',
    data: formData,
    contentType: false,
    cach: false,
    processData: false,
    success: function (data) {
        alert(data);
        document.location.href='../html/Organiser - Add Game.html';
    },
  });
});

//Organiser - Game Detail

function triggerClickUpdate(){
  $("#updateGameModal").modal("hide")
  document.querySelector("#UpdateGameImageData").click();
}

function displayImageUpdate(e) {
  if (e.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e){
      document.querySelector('#UpdateGameImageDataUpload').setAttribute('src', e.target.result);
    }
    reader.readAsDataURL(e.files[0]);
  }
  $("#updateGameModal").modal("show")
}

$(document).ready(function(){
  $("#searchFilter").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#data tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });

  var queryString = decodeURIComponent(window.location.search);
  queryString = queryString.substring(1);
  var queries = queryString.split("&");
  document.getElementById("GameNameData").innerHTML = queries[0].split("=")[1];
  $.ajax({
    type: "GET",
    url: "../php/CRUD Game.php",
    data:"GameDetailOrganiser=Yes&"+queries[0]+"&"+queries[1],
    success: function(data){
      $('#GameDetailCard').html(data);
    }
  });
});

//Organiser - Profile

$(document).ready(function(event){
  //Get User Data
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("userInfo").innerHTML=this.responseText;
    }
  }
  xmlhttp.open("GET","../php/CRUD User.php?ReadUser=Yes",true);
  xmlhttp.send();

  //Get Registered Game
  $.ajax({
    url: "../php/CRUD Game.php",
    type: 'GET',
    data: "DisplayOrganiserRegisteredGame=Yes",
    success: function (data) {
      $('#OrganiserRegisteredGameProfile').html(data);
    },
  });
});

function displayUserDetail(){
  document.getElementById('UserName').value = document.getElementById('updateUserName').innerHTML;
  document.getElementById('Email').value = document.getElementById('updateEmail').innerHTML;
  document.getElementById('PhoneNumber').value = document.getElementById('updatePhoneNumber').innerHTML;
  document.getElementById('BirthDate').value = document.getElementById('updateBirthDate').innerHTML;
  document.getElementById('Address').value = document.getElementById('updateAddress').innerHTML;
  document.getElementById('UpdateUser').value = document.getElementById('updateEmail').innerHTML;
}

function deleteUserDetail(){
  document.getElementById('DeleteUser').value = document.getElementById('updateEmail').innerHTML;
}

$('#UpdateUserForm').on('submit', function(event){
  event.preventDefault();
  var form_data = $(this).serialize();
  $.ajax({
    type: "POST",
    url: "../php/CRUD User.php",
    data:form_data,
    success: function(data){
      if(data == "success"){
        alert("User data has been updated successfully!");
      } else {
        alert("User data failed to be updated!");
      }
      document.location.href='../html/Organiser - Profile.html';
    }
  });
});

$('#DeleteAccountForm').on('submit', function(event){
  if (window.confirm("Do you really want to delete this account?")) { 
    event.preventDefault();
    var form_data = $(this).serialize();
    $.ajax({
      type: "GET",
      url: "../php/CRUD User.php",
      data:form_data,
      success: function(data){
        if(data == "success"){
          alert("User data has been deleted successfully!");
          document.location.href='../php/Session.php?Logout=Yes';
        } else {
          alert("User data failed to be deleted!");
        }
      }
    });
  }
});

// $(".showGamesCard").click(function(){
//   $("#gameDetailModal").modal("show")
// });

// $('#customFile').on('change',function(){
//   //get the file name
//   var fileName = $(this).val();
//   //replace the "Choose a file" label
//   $(this).next('.custom-file-label').html(fileName);
// })