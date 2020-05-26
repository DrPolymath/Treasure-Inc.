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

//Player

$(document).ready(function(){
  $("#searchFilter").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#gameData .cardRow .gameCard").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });

  $.ajax({
    url: "../php/CRUD Game.php",
    type: 'GET',
    data: "PlayerCardGame=Yes",
    success: function (data) {
      $('#gameData').html(data);
    },
  });
});

$(".showGameCard").click(function(){
  $("#gameDetailModal").modal("show")
});

$(".showRegistration").click(function(){
  $("#registrationModal").modal("show")
});

$(".editTeamMember").click(function(){
  $("#registrationModal").modal("hide")
  $("#editMemberModal").modal("show")
});

$(".removeTeamMember").click(function(){
  if (window.confirm("Do you really want to remove this team member?")) { 
  }
});

$(".displayGameCard").click(function(){
  window.location.href = "../html/Player - Game Detail.html";
});

//Player - Game Detail

$( window ).on( "load", function() {
  var queryString = decodeURIComponent(window.location.search);
  queryString = queryString.substring(1);
  var queries = queryString.split("&");
  document.getElementById("GameNameData").innerHTML = queries[0].split("=")[1];
  $.ajax({
    type: "GET",
    url: "../php/CRUD Game.php",
    data:"GameDetailPlayer=Yes&"+queries[0]+"&"+queries[1],
    success: function(data){
      $('#GameDetailCard').html(data);
    }
  });
});

//Player - Profile

$(document).ready(function(event){
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("userInfo").innerHTML=this.responseText;
    }
  }
  xmlhttp.open("GET","../php/CRUD User.php?ReadUser=Yes",true);
  xmlhttp.send();
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
      document.location.href='../html/Player - Profile.html';
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

$(".showGamesCard").click(function(){
  $("#gameDetailModal").modal("show")
});



