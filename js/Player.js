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
    url: "../php/CRUD Game Registration.php",
    type: 'GET',
    data: "PlayerRegisteredGame=Yes",
    success: function (data) {
      $('#PlayerRegisteredGame').html(data);
    },
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

$(document).ready(function(event){
  var i=1;  
  $('#add').click(function(){
    if(i==document.getElementById("gamePlayer").value){
      alert("You cannot add anymore member. The player required for this game is " + document.getElementById("gamePlayer").value);
    } else {
      i++;
      var html = '';
      html += '<div id="row'+i+'">';
      html += '<div class="row my-3">';
      html += '<div class="col-2"></div>';
      html += '<div class="col-4">';
      html += '<input type="text" class="form-control" name="name[]" placeholder="Name" required>';
      html += '</div>';
      html += '<div class="col-4">';
      html += '<input type="number" class="form-control" name="ICNumber[]" placeholder="Identification Number" required>';
      html += '</div>';
      html += '</div>';
      html += '<div class="row my-3">';
      html += '<div class="col-2">';
      html += '<label class="generalColor">Member</label>';
      html += '</div>';
      html += '<div class="col-8">';
      html += '<input type="email" class="form-control" name="email[]" placeholder="Email" required>';
      html += '</div>';
      html += '<div class="col-2">';
      html += '<button type="button" name="remove" id="'+i+'" class="btn btn_remove">X</button>';//
      html += '</div>';
      html += '</div>';
      html += '<div class="row my-3">';
      html += '<div class="col-2"></div>';
      html += '<div class="col-4">';
      html += '<input type="number" class="form-control" name="phoneNumber[]" placeholder="Phone Number" required>';
      html += '</div>';
      html += '<div class="col-4">';
      html += '<input type="text" class="form-control" name="role[]" placeholder="Role" required>';
      html += '</div>';
      html += '</div>';
      html += '<hr>';
      html += '</div>';
      $('#RegisterInput').append(html);
    }
  });  
  $(document).on('click', '.btn_remove', function(){  
    var button_id = $(this).attr("id");   
    $('#row'+button_id+'').remove();
    i--; 
  });
  $('#RegistrationForm').on('submit', function(event){
    event.preventDefault();
    var form_data = $(this).serialize();
    form_data = form_data + "&GameID=" + document.getElementById("gameID").value;
    $.ajax({
      type: "POST",
      url: "../php/CRUD Game Registration.php",
      data:form_data,
      success: function(data){
        if(data == "success"){
          alert("Game Registration is completed!");
          document.location.href='../html/Player.html';
        } else {
          alert("Game Registration is failed!");
        }
      }
    });
  });
});

//Player - Profile

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

  // Get Registered Game
  var xmlHttp=new XMLHttpRequest();
  xmlHttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("PlayerRegisteredGameProfile").innerHTML=this.responseText;
    }
  }
  xmlHttp.open("GET","../php/CRUD Game.php?DisplayPlayerRegisteredGame=Yes",true);
  xmlHttp.send();
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



