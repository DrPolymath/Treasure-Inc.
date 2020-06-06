<?php
  include_once("DatabaseConnection.php");
  
  session_start();
  $success = true;

  if (!empty($_POST['TeamName'])&&!empty($_POST['name'])&&!empty($_POST['role'])&&!empty($_POST['ICNumber'])&&!empty($_POST['phoneNumber'])&&!empty($_POST['email'])){
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

  } else if (isset($_GET['PlayerRegisteredGame'])) {

    $sql = "SELECT DISTINCT GameID FROM gameregistration WHERE UserID='".$_SESSION['UserID']."'";
    $result = $pdo->query($sql);
    $listRegisteredGame = array();
    while($res = $result->fetch()){
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
										<p>Group	:	Wisdom Hunter</p>
									</div>
								</div>
								<div class="col-5 d-flex">
									<div class="m-auto">
										<button class="btn btnTicket showGameCard my-2">Game Detail</button><br>
										<button class="btn btnTicket showRegistration my-2">Update Registration</button>
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
      }
    }

  } else if (isset($_GET['ReadPlayersDetail'])) {
	//Stuck fix this
	$sql = "SELECT DISTINCT TeamName FROM gameregistration WHERE GameID='".$_GET['GameID']."'";
	$result = $pdo->query($sql);

	echo '<div class="carousel-item active">';

	while($res = $result->fetch()){
		echo $res['TeamName'];
	}
	echo'bodo';
	echo '</div>';

  } else {
    echo 'failed';
  }
  
  $pdo = null;
?>