$(document).ready(function(){
    $('#ForgotPasswordForm').on('submit', function(event){
        event.preventDefault();
        var form_data = $(this).serialize();
        form_data = "ForgotPassword=Yes&"+form_data;
        $.ajax({
          type: "POST",
          url: "../phpmailer/Email.php",
          data:form_data,
          success: function(data){
              alert(data);
            // if(data == "success"){
            //   alert("Notification successfully sent!");
            //   document.location.href="../html/Login.html";
            // } else {
            //   alert("Notification sending failed!");
            // }
          }
        });
    });
});