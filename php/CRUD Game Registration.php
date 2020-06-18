<?php
  include_once("DatabaseConnection.php");
  
  session_start();
  $success = true;

  if (!empty($_POST['RegisterGame'])&&!empty($_POST['TeamName'])&&!empty($_POST['name'])&&!empty($_POST['role'])&&!empty($_POST['ICNumber'])&&!empty($_POST['phoneNumber'])&&!empty($_POST['email'])){
    $TeamName = $_POST['TeamName'];
    $GameID = $_POST['GameID'];
    $UserID = $_SESSION['UserID'];
    $counter = count($_POST['name']);
    try {
      $pdo->beginTransaction();
      for($i=0; $i < $counter; $i++){
        $MemberName = $_POST['name'][$i];
        $Role = $_POST['role'][$i];
        $ICNumber = $_POST['ICNumber'][$i];
        $PhoneNumber = $_POST['phoneNumber'][$i];
        $Email = $_POST['email'][$i];
        $sql = "INSERT INTO gameregistration (TeamName,MemberName,Role,ICNumber,PhoneNumber,Email,GameID,UserID) VALUES ('$TeamName','$MemberName','$Role','$ICNumber','$PhoneNumber','$Email','$GameID','$UserID')";
        $pdo->query($sql);
	  }
	  
	  $sql = "SELECT TotalTeamJoined, TotalPlayer FROM treasurehuntgames WHERE GameID='$GameID'";
	  $result = $pdo->query($sql);

	  while($res = $result->fetch()){
		$TotalTeamJoined = $res['TotalTeamJoined'];
		$TotalPlayer = $res['TotalPlayer'];
	  }

	  $TotalTeamJoined++;
	  $TotalPlayer = $TotalPlayer + $counter;
	  
	  $sql = "UPDATE treasurehuntgames SET TotalTeamJoined='$TotalTeamJoined', TotalPlayer='$TotalPlayer' WHERE GameID='$GameID'";
	  $pdo->query($sql);

      echo 'success';
      $pdo->commit();
    } catch (Exception $e) {
      echo 'failed';
      $pdo->rollback();
    }
  // Ticket at Player.html
  } else if (isset($_GET['PlayerRegisteredGame'])) {

    $sql = "SELECT DISTINCT TeamName, GameID FROM gameregistration WHERE UserID='".$_SESSION['UserID']."'";
	$result = $pdo->query($sql);
	$listRegisteredTeam = array();
    $listRegisteredGame = array();
    while($res = $result->fetch()){
	  array_push($listRegisteredTeam,$res['TeamName']);
      array_push($listRegisteredGame,$res['GameID']);
    }

    $lengthListRegisteredGame = count($listRegisteredGame);

    for($i = 0;$i < $lengthListRegisteredGame;$i++){
      $sql = "SELECT * FROM treasurehuntgames WHERE GameID='".$listRegisteredGame[$i]."'";
      $result = $pdo->query($sql);
      
      while($res = $result->fetch()){
        echo'
				<div class="card shadow my-3 mx-1">
					<div class="row border-0">
						<div class="col-2 eventDateContainer">
							<section>
								<h1 class="font-weight-bold day m-0">'.date('d', strtotime($res['Date'])).'</h1>
								<span class="font-weight-bold month m-0">'.date('F', strtotime($res['Date'])).'</span>
							</section>
						</div>
						<div class="col-3 p-0">
							<img src="data:image;base64,'.$res['GameImage'].'" class="img-fluid ticketImgH">
						</div>
						<div class="col-4 generalColor">
							<div class="row">
								<div class="col-7 py-2">
									<h3 class="font-weight-bold p-3">'.$res['GameName'].'</h3>
									<div class="px-4">
										<p>Venue	:	'.$res['Venue'].'</p>
										<p>Time		:	'.date('h:i A', strtotime($res['Time'])).'</p>
										<p>Group	:	'.$listRegisteredTeam[$i].'</p>
									</div>
								</div>
								<div class="col-5 d-flex">
									<div class="m-auto">
										<button class="btn btnTicket showGameCard my-2" onclick="passtoGameModal(\''.$res['GameImage'].'\',\''.$res['GameName'].'\',\''.$res['GameDescription'].'\',\''.$res['Venue'].'\',\''.$res['Date'].'\',\''.$res['Time'].'\',\''.$res['RegistrationFee'].'\',\''.$res['TeamRequired'].'\',\''.$res['PlayerPerTeam'].'\',\''.$res['TotalTeamJoined'].'\',\''.$res['TotalPlayer'].'\')">Game Detail</button><br>
										<button class="btn btnTicket showRegistration my-2" onclick="displayPlayer(\''.$res['GameID'].'\',\''.$_SESSION['UserID'].'\',\''.$listRegisteredTeam[$i].'\',\''.$res['PlayerPerTeam'].'\')">Update Registration</button>
										<button class="btn btnTicket cancelGame my-2" onclick="cancelGameRegistration(\''.$res['GameID'].'\',\''.$_SESSION['UserID'].'\',\''.$listRegisteredTeam[$i].'\')">Cancel Participation</button>
									</div>
								</div>
							</div>
						</div> 
						<div class="col-3 m-auto" align="center">
							<div class="row font-weight-bold m-auto text-center d-flex p-2">
								<div class="col generalColor m-auto text-center">
									<p>Total Team Joined</p>
								</div>
								<div class="col m-auto text-center">
									<p class="circleBG">'.$res['TotalTeamJoined'].'/'.$res['TeamRequired'].'</p>
								</div>
							</div>
							<div class="row font-weight-bold m-auto text-center d-flex p-2">
								<div class="col generalColor m-auto text-center">
									<p>Total Player</p>
								</div>
								<div class="col m-auto text-center">
									<p class="circleBG">'.$res['TotalPlayer'].'</p>
								</div>
							</div>
						</div>
					</div>
				</div>
		';
		
		echo'
		<script>
		$(".showGameCard").click(function(){
			$("#gameDetailModal").modal("show")
		});

		function passtoGameModal(GameImage,GameName,GameDescription,Venue,Date,Time,RegistrationFee,TeamRequired,PlayerPerTeam,TotalTeamJoined,TotalPlayer){
            document.getElementById("GameNameDataModal").innerHTML = GameName;
            document.getElementById("GameImageDataModal").src = "data:image;base64," + GameImage;
            document.getElementById("GameDescriptionDataModal").value = GameDescription;
            document.getElementById("VenueDataModal").value = Venue;
            document.getElementById("DateDataModal").value = Date;
            document.getElementById("TimeDataModal").value = Time;
            document.getElementById("RegistrationFeeDataModal").value = "RM " + RegistrationFee;
            document.getElementById("TeamRequiredDataModal").value = TeamRequired;
            document.getElementById("PlayerPerTeamDataModal").value = PlayerPerTeam;
            document.getElementById("TotalTeamJoinedDataModal").innerHTML = TotalTeamJoined + "/" + TeamRequired;
            document.getElementById("TotalPlayerDataModal").innerHTML = TotalPlayer;
		}

		function displayPlayer(GameID,UserID,TeamName,PlayerPerTeam){
			$.ajax({
				url: "../php/CRUD Game Registration.php",
				type: "GET",
				data: "ReadTeamMember=Yes&GameID="+GameID+"&UserID="+UserID+"&TeamName="+TeamName+"&PlayerPerTeam="+PlayerPerTeam,
				success: function (data) {
				  $("#DisplayTeamMember").html(data);
				},
			  });
			$("#registrationModal").modal("show")
		}

		function cancelGameRegistration(GameID,UserID,TeamName){
			if (window.confirm("Do you really want to withdraw from the game?")) {
				$.ajax({
					url: "../php/CRUD Game Registration.php",
					type: "GET",
					data: "CancelGameRegistration=Yes&GameID="+GameID+"&UserID="+UserID+"&TeamName="+TeamName,
					success: function (data) {
						if(data == "success"){
							alert("Game Registration is cancelled successfully!");
							document.location.href="../html/Player.html";
						} else {
							alert("Game Registration Cancellation is failed!");
						}
					},
				});
			}
		}
		</script>
		';
      }
    }
  //View Team List at Organiser - Game detail.html
  } else if (isset($_GET['ReadPlayersDetail'])) {
	
	$sql = "SELECT DISTINCT TeamName FROM gameregistration WHERE GameID='".$_GET['GameID']."'";
	$result = $pdo->query($sql);

	$j = 0;
	while($res = $result->fetch()){
		if($j==0){
			echo '<div class="carousel-item active">';
		} else {
			echo '<div class="carousel-item">';
		}
		echo '
		<div class="mx-3" align="left">
			<h4 id="TeamName">'.$res['TeamName'].'</h4>
		</div>
		';

		echo '
		<table align ="center" class="table shadow">
			<tr>
				<th>No</th>
				<th>Name</th>
				<th>Role</th>
				<th>Identification Number</th>
				<th>Phone Number</th>
				<th>E-mail</th>
			</tr>
			<tbody id="data">
		';

		$sql = "SELECT DISTINCT MemberName,Role,ICNumber,PhoneNumber,Email FROM gameregistration WHERE GameID='".$_GET['GameID']."' AND TeamName='".$res['TeamName']."'";
		$rows = $pdo->query($sql);
		$i=1;
		while($row = $rows->fetch()){
			echo '
			<tr>
				<td>'.$i.'</td>
				<td>'.$row['MemberName'].'</td>
				<td>'.$row['Role'].'</td>
				<td>'.$row['ICNumber'].'</td>
				<td>'.$row['PhoneNumber'].'</td>
				<td>'.$row['Email'].'</td>
			</tr>
			';
			$i++;
		}

		echo '
			</tbody>
		</table>
		';
		echo '</div>';
		$j++;
	}
  //Registration Modal at Player.html
  } else if(isset($_GET['ReadTeamMember'])) {
	echo'
	<div>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<h1 id="ANMTeamMember" class="modal-title p-4 font-weight-bold generalColor">'.$_GET['TeamName'].'</h1>
	<input type="number" class="form-control inputGameData" id="PlayerPerTeam" value="'.$_GET['PlayerPerTeam'].'" hidden>
	<p id="ANMGameID" hidden>'.$_GET['GameID'].'</p>
	<table align ="center" class="table">
		<tr>
			<th>No</th>
			<th>Name</th>
			<th>Role</th>
			<th>Identification Number</th>
			<th>Phone Number</th>
			<th>E-mail</th>
			<th></th>
			<th></th>
		</tr>
		<tbody>
	';

	$sql = "SELECT MemberName,Role,ICNumber,PhoneNumber,Email,GameID,UserID FROM gameregistration WHERE TeamName='".$_GET['TeamName']."' AND GameID='".$_GET['GameID']."' AND UserID='".$_GET['UserID']."'";
	$result = $pdo->query($sql);
	$i = 1;
	while($res = $result->fetch()){
		echo'
			<tr>
				<td>'.$i.'</td>
				<td>'.$res['MemberName'].'</td>
				<td>'.$res['Role'].'</td>
				<td>'.$res['ICNumber'].'</td>
				<td>'.$res['PhoneNumber'].'</td>
				<td>'.$res['Email'].'</td>
				<td><button class="btn editTeamMember" onclick="passDatatoEditMemberModal(\''.$_GET['TeamName'].'\',\''.$res['MemberName'].'\',\''.$res['Role'].'\',\''.$res['ICNumber'].'\',\''.$res['PhoneNumber'].'\',\''.$res['Email'].'\',\''.$res['GameID'].'\',\''.$res['UserID'].'\')">Edit</button></td>
				<td><button class="btn removeTeamMember" onclick="passDatatoDelete(\''.$_GET['TeamName'].'\',\''.$res['ICNumber'].'\',\''.$res['GameID'].'\',\''.$res['UserID'].'\')">Remove</button></td>
			</tr>
		';
		$i++;
	}
	$i--;
	echo'
		</tbody>
	</table>
	<input type="number" class="form-control inputGameData" id="TotalPlayer" value="'.$i.'" hidden>
	<div id="AddNewMemberButton">
		<button class="btn">Add New Member</button>
	</div>
	<div id="AddNewMemberPanel" style="display:none;">
		<form>
			<hr>
			<div>
				<div class="row my-3">
					<div class="col-2">
						
					</div>
					<div class="col-4">
						<input type="text" class="form-control" id="ANMname" name="name" placeholder="Name" required>
					</div>
					<div class="col-6">
						<input type="number" class="form-control" id="ANMICNumber" name="ICNumber" placeholder="Identification Number" min="0" required>
					</div>
				</div>
				<div class="row my-3">
					<div class="col-2">
						<label class="generalColor">Member</label>
					</div>
					<div class="col-10">
						<input type="email" class="form-control" id="ANMemail" name="email" placeholder="Email" required>
					</div>
				</div>
				<div class="row my-3">
					<div class="col-2">
						
					</div>
					<div class="col-4">
						<input type="number" class="form-control" id="ANMphoneNumber" name="phoneNumber" placeholder="Phone Number" min="0" required>
					</div>
					<div class="col-6">
						<select class="form-control form-control-register" id="ANMrole" name="role" required>
							<option selected disabled>Role</option>
							<option value="Leader">Leader</option>
							<option value="Member">Member</option>
						</select>
					</div>
				</div>
				<hr>
				<button id="SubmitNewMember" type="button" class="btn">Save</button>
			</div>
		</form>
	</div>
	';

	echo'
	<script>

	$(document).ready(function(){
		$("#AddNewMemberButton").click(function(){
			if(document.getElementById("TotalPlayer").value<document.getElementById("PlayerPerTeam").value){
				$("#AddNewMemberPanel").slideToggle("slow");
			} else {
				alert("You cannot add anymore member.")
			}
		});

		$("#SubmitNewMember").on("click", function(){
			var TeamName = document.getElementById("ANMTeamMember").innerHTML;
			var MemberName = document.getElementById("ANMname").value;
			var ICNumber = document.getElementById("ANMICNumber").value;
			var Email = document.getElementById("ANMemail").value;
			var PhoneNumber = document.getElementById("ANMphoneNumber").value;
			var Role = document.getElementById("ANMrole").value;
			var GameID = document.getElementById("ANMGameID").innerHTML;
			var form_data = "AddNewMember=Yes&TeamName="+TeamName+"&MemberName="+MemberName+"&ICNumber="+ICNumber+"&Email="+Email+"&PhoneNumber="+PhoneNumber+"&Role="+Role+"&GameID="+GameID;
			$.ajax({
			  type: "POST",
			  url: "../php/CRUD Game Registration.php",
			  data:form_data,
			  success: function(data){
				if(data == "success"){
				  alert("A new member successfully added!");
				  document.location.href="../html/Player.html";
				} else {
				  alert("Member addition failed!");
				}
			  }
			});
		});
	});

	function passDatatoEditMemberModal(TeamName,MemberName,Role,ICNumber,PhoneNumber,Email,GameID,UserID){
		document.getElementById("TeamName").value = TeamName;
		document.getElementById("MemberName").value = MemberName;
		document.getElementById("Role").value = Role;
		document.getElementById("ICNumber").value = ICNumber;
		document.getElementById("OldICNumber").value = ICNumber;
		document.getElementById("PhoneNumber").value = PhoneNumber;
		document.getElementById("Email").value = Email;
		document.getElementById("GameID").value = GameID;
		document.getElementById("UserID").value = UserID;
		$("#registrationModal").modal("hide")
  		$("#editMemberModal").modal("show")
	}

	function passDatatoDelete(TeamName,ICNumber,GameID,UserID){
		if (window.confirm("Do you really want to remove this team member?")) {
			$.ajax({
				type: "POST",
				url: "../php/CRUD Game Registration.php",
				data:"DeleteTeamMember=Yes&TeamName="+TeamName+"&ICNumber="+ICNumber+"&GameID="+GameID+"&UserID="+UserID,
				success: function(data){
					if(data == "success"){
						alert("Team Member successfully removed!");
						document.location.href="../html/Player.html";
					} else if(data == "OneMember") {
						alert("You cannot remove anymore member. At least 1 member are needed to participate the game!");
					} else {
						alert("Team Member failed to be removed!");
					}
				}
			});
		}
	}
	</script>
	';

  } else if(isset($_POST['UpdateTeamMember'])){
   
	try {
		$pdo->beginTransaction();
		$sql = "UPDATE gameregistration SET MemberName='".$_POST['MemberName']."', Role='".$_POST['Role']."', ICNumber='".$_POST['ICNumber']."', PhoneNumber='".$_POST['PhoneNumber']."', Email='".$_POST['Email']."' WHERE TeamName='".$_POST['TeamName']."' AND GameID='".$_POST['GameID']."' AND UserID='".$_POST['UserID']."' AND ICNumber='".$_POST['OldICNumber']."'";
		$pdo->query($sql);
		echo "success";
		$pdo->commit();
	} catch (Exception $e) {
		echo "fail";
		$pdo->rollback();
	}

  } else if (isset($_POST['DeleteTeamMember'])) { 

	$sql = "SELECT MemberName FROM gameregistration WHERE TeamName='".$_POST['TeamName']."' AND GameID='".$_POST['GameID']."' AND UserID='".$_POST['UserID']."'";
	$result = $pdo->query($sql);
	if ($result->rowCount()==1){
		echo 'OneMember'; 
	} else {

		try {
			$pdo->beginTransaction();
			$sql = "DELETE FROM gameregistration WHERE ICNumber='".$_POST['ICNumber']."'";
			$pdo->query($sql);
			$sql = "SELECT TotalPlayer FROM treasurehuntgames WHERE GameID='".$_POST['GameID']."'";
			$result = $pdo->query($sql);
			while($res = $result->fetch()){
				$OldTotalPlayer = $res['TotalPlayer'];
			}
			$OldTotalPlayer--;
			$sql = "UPDATE treasurehuntgames SET TotalPlayer='$OldTotalPlayer' WHERE GameID='".$_POST['GameID']."'";
			$pdo->query($sql);
			echo "success";
			$pdo->commit();
		} catch (Exception $e) {
			echo "fail";
			$pdo->rollback();
		}

	}
  
  } else if (isset($_GET['CancelGameRegistration'])) { 
	
	try {
		$pdo->beginTransaction();
		$sql = "DELETE FROM gameregistration WHERE TeamName='".$_GET['TeamName']."' AND GameID='".$_GET['GameID']."' AND UserID='".$_GET['UserID']."'";
		$result = $pdo->query($sql);
		$num = $result->rowCount();
		
		$sql = "SELECT TotalTeamJoined,TotalPlayer FROM treasurehuntgames WHERE GameID='".$_GET['GameID']."'";
		$result = $pdo->query($sql);
		while($res = $result->fetch()){
			$TotalTeamJoined = $res['TotalTeamJoined'];
			$TotalPlayer = $res['TotalPlayer'];
		}
		$TotalTeamJoined--;
		$TotalPlayer = $TotalPlayer-$num;
		$sql = "UPDATE treasurehuntgames SET TotalTeamJoined='$TotalTeamJoined', TotalPlayer='$TotalPlayer' WHERE GameID='".$_GET['GameID']."'";
		$pdo->query($sql);
		echo "success";
		$pdo->commit();
	} catch (Exception $e) {
		echo "fail";
		$pdo->rollback();
	}

  } else if (isset($_POST['AddNewMember'])&&!empty($_POST['MemberName'])&&!empty($_POST['Role'])&&!empty($_POST['ICNumber'])&&!empty($_POST['PhoneNumber'])&&!empty($_POST['Email'])) {
	
	try {
		$pdo->beginTransaction();

		$TeamName = $_POST['TeamName'];
		$MemberName = $_POST['MemberName'];
		$Role = $_POST['Role'];
		$ICNumber = $_POST['ICNumber'];
		$PhoneNumber = $_POST['PhoneNumber'];
		$Email = $_POST['Email'];
		$GameID = $_POST['GameID'];
    	$UserID = $_SESSION['UserID'];
		
		$sql = "INSERT INTO gameregistration (TeamName,MemberName,Role,ICNumber,PhoneNumber,Email,GameID,UserID) VALUES ('$TeamName','$MemberName','$Role','$ICNumber','$PhoneNumber','$Email','$GameID','$UserID')";
		$pdo->query($sql);
		
		$sql = "SELECT TotalPlayer FROM treasurehuntgames WHERE GameID='$GameID'";
		$result = $pdo->query($sql);
  
		while($res = $result->fetch()){
		  $TotalPlayer = $res['TotalPlayer'];
		}
  
		$TotalPlayer++;
		
		$sql = "UPDATE treasurehuntgames SET TotalPlayer='$TotalPlayer' WHERE GameID='$GameID'";
		$pdo->query($sql);
  
		echo 'success';
		$pdo->commit();
	  } catch (Exception $e) {
		echo 'failed';
		$pdo->rollback();
	  }

  } else {
    echo 'failed';
  }
  
  $pdo = null;
?>