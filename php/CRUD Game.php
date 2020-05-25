<?php
    include_once("DatabaseConnection.php");
    session_start();
    if(isset($_POST['GameName'])){
        $GameImage = addslashes($_FILES['file']['tmp_name']);
        $GameImage = file_get_contents($GameImage);
        $GameImage = base64_encode($GameImage);

        $GameName = $_POST['GameName'];
        $GameDescription = $_POST['GameDescription'];
        $RegistrationFee = $_POST['RegistrationFee'];
        $Venue = $_POST['Venue'];
        $Date = $_POST['Date'];
        $Time = $_POST['Time'];
        $PlayerPerTeam = $_POST['PlayerPerTeam'];
        $TeamRequired = $_POST['TeamRequired'];
        $TotalTeamJoined = 0;
        $TotalPlayer = 0;

        try {
            $pdo->beginTransaction();
            $sql = "INSERT INTO treasurehuntgames (GameImage,GameName,GameDescription,RegistrationFee,Venue,Date,Time,PlayerPerTeam,TeamRequired,TotalTeamJoined,TotalPlayer,OrganizerName) VALUES ('$GameImage','$GameName','$GameDescription','$RegistrationFee','$Venue','$Date','$Time','$PlayerPerTeam','$TeamRequired','$TotalTeamJoined','$TotalPlayer','".$_SESSION['UserName']."')";
            $pdo->query($sql);
            echo "Treasure Hunt Game successfully added!";
            $pdo->commit();
        } catch (Exception $e) {
            echo $e->getMessage();
            echo "Treasure Hunt Game failed to be added!";
            $pdo->rollback();
        }
    } else if(isset($_GET['GameDetailOrganiser'])) {
        $sql = "SELECT * FROM treasurehuntgames WHERE GameName='".$_GET['GameName']."' AND Venue='".$_GET['Venue']."'";
        $result = $pdo->query($sql);

        while ($res = $result->fetch()) {
            echo'
            <form>
                <div class="row">
                    <div class="col-sm-4">
                        <img id="GameImageData" src="data:image;base64,'.$res['GameImage'].'" class="img-fluid p-4">
                        <div class="row font-weight-bold m-2 d-flex centerItem" align="center">
                            <div class="col m-auto text-center">
                                <p>Total Team Joined</p>
                            </div>
                            <div class="col m-auto text-center">
                                <p id="TotalTeamJoinedData" class="teamPlayer">'.$res['TotalTeamJoined'].'/'.$res['TeamRequired'].'</p>
                            </div>
                            <div class="col m-auto text-center">
                                <p>Total Player</p>
                            </div>
                            <div class="col m-auto text-center">
                                <p id="TotalPlayerData" class="teamPlayer">'.$res['TotalPlayer'].'</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8 px-0 py-3">
                        <div class="row px-3">
                            <div class="col-6 px-0">
                                <div class="form-group row px-0">
                                    <label for="VenueData" class="col-form-label col-sm-3">Venue</label>
                                    <div class="col-sm-9 mx-0">
                                        <input type="text" class="form-control inputGameData" id="VenueData" value="'.$res['Venue'].'" readonly>
                                    </div>
                                </div>
                                <div class="form-group row px-0">
                                    <label for="DateData" class="col-form-label col-sm-3">Date</label>
                                    <div class="col-sm-9 mx-0">
                                        <input type="date" class="form-control inputGameData" id="DateData" value="'.$res['Date'].'" readonly>
                                    </div>
                                </div>
                                <div class="form-group row px-0">
                                    <label for="TimeData" class="col-form-label col-sm-3">Time</label>
                                    <div class="col-sm-9 mx-0">
                                        <input type="time" class="form-control inputGameData" id="TimeData" value="'.$res['Time'].'" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 px-0">
                                <div class="form-group row px-0">
                                    <label for="RegistrationFeeData" class="col-form-label col-sm-4">Fee</label>
                                    <div class="col-sm-8 mx-0">
                                        <input type="number" class="form-control inputGameData" id="RegistrationFeeData" value="'.$res['RegistrationFee'].'" readonly>
                                    </div>
                                </div>
                                <div class="form-group row px-0">
                                    <label for="TeamRequiredData" class="col-form-label col-sm-4">Team Required</label>
                                    <div class="col-sm-8 mx-0">
                                        <input type="number" class="form-control inputGameData" id="TeamRequiredData" value="'.$res['TeamRequired'].'" readonly>
                                    </div>
                                </div>
                                <div class="form-group row px-0">
                                    <label for="PlayerPerTeamData" class="col-form-label col-sm-4">Player per Team</label>
                                    <div class="col-sm-8 mx-0">
                                        <input type="number" class="form-control inputGameData" id="PlayerPerTeamData" value="'.$res['PlayerPerTeam'].'" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group px-3 my-0">
                            <label for="GameDescriptionData" class="py-2">Game Description</label>
                            <textarea class="form-control inputGameData" id="GameDescriptionData" rows="4" readonly>'.$res['GameDescription'].'</textarea>
                        </div>
                        <div class="form-group" align="center">
                            <button id="teamList" type="button" class="btn btnOGD">Team List</button>
                            <button type="button" class="btn btnOGD" data-toggle="modal" data-target="#createNotificationModal">Create Notification</button>
                            <button type="submit" class="btn btnOGD" onclick="deleteGame()">Delete Game</button>
                            <button type="button" class="btn btnOGD" data-toggle="modal" data-target="#updateGameModal">Update Game</button>
                        </div>
                    </div>
                </div>
            </form>
            ';

            echo'
            <script>
            function deleteGame(){
                if (confirm("Do you really want to delete this game?")) { 
                } 
            }
            </script>
            ';
            //$GameArray($_GET['GameName'],$_GET['GameImage'],$_GET['GameDescription'],$_GET['Venue'],$_GET['Date'],$_GET['Time'],$_GET['RegistrationFee'],$_GET['TeamRequired'],$_GET['PlayerPerTeam'],$_GET['TotalTeamJoined'],$_GET['TotalPlayer']);
        }

        // echo json_encode($GameArray);

    }else {
        $sql = "SELECT * FROM treasurehuntgames";
        $result = $pdo->query($sql);
        $counter = 1;

        echo "<div class='carousel-item active'>";
        echo "<div class='row px-3'>";

        while ($res = $result->fetch()) {
            if($counter%3==1&&$counter>3){
                echo "</div>";
                echo "</div>";
                echo "<div class='carousel-item'>";
                echo "<div class='row px-3'>";
            }
            echo'
            <div class="col-md-4 px-0">
				<div class="showGameCard gameCard card mb-2" onclick="passDatatoGameDetail(\''.$res['GameImage'].'\',\''.$res['GameName'].'\',\''.$res['GameDescription'].'\',\''.$res['Venue'].'\',\''.$res['Date'].'\',\''.$res['Time'].'\',\''.$res['RegistrationFee'].'\',\''.$res['TeamRequired'].'\',\''.$res['PlayerPerTeam'].'\',\''.$res['TotalTeamJoined'].'\',\''.$res['TotalPlayer'].'\')">
					<img class="gameImage card-img-top" src="data:image;base64,'.$res['GameImage'].'" alt="">
					<div class="card-body" align="left">
						<h4 class="card-title font-weight-bold">'.$res['GameName'].'</h4>
						<p class="card-text">
							Venue				:	'.$res['Venue'].'<br>
							Date				:	'.date('d F Y', strtotime($res['Date'])).'<br>
							Time				:	'.date('h:i A', strtotime($res['Time'])).'<br>
							Total Team Joined	:	'.$res['TotalTeamJoined'].'/'.$res['TeamRequired'].'<br>
							Total Player		:	'.$res['TotalPlayer'].'<br>
						</p>
					</div>
				</div>
			</div>
            ';
            $counter++;
        }

        echo "</div>";
        echo "</div>";

        echo '
        <script>
        // $(".showGameCard").click(function(){
        //     window.location.href = "Organiser - Game Detail.html";
        // });

        function passDatatoGameDetail(GameImage,GameName,GameDescription,Venue,Date,Time,RegistrationFee,TeamRequired,PlayerPerTeam,TotalTeamJoined,TotalPlayer){
            var queryString = "?GameName=" + GameName + "&Venue=" + Venue;
            window.location.href = "Organiser - Game Detail.html" + queryString;
            // document.getElementById("GameNameData").innerHTML = GameName;
            // document.getElementById("GameImageData").src = "data:image;base64," + GameImage;
            // document.getElementById("GameDescriptionData").value = GameDescription;
            // document.getElementById("VenueData").value = Venue;
            // document.getElementById("DateData").value = Date;
            // document.getElementById("TimeData").value = Time;
            // document.getElementById("RegistrationFeeData").value = "RM " + RegistrationFee;
            // document.getElementById("TeamRequiredData").value = TeamRequired;
            // document.getElementById("PlayerPerTeamData").value = PlayerPerTeam;
            // document.getElementById("TotalTeamJoinedData").innerHTML = TotalTeamJoined + "/" + TeamRequired;
            // document.getElementById("TotalPlayerData").innerHTML = TotalPlayer;
        }
        </script>
        ';
    }
    

    $pdo = null;
?>