<?php
    include_once("DatabaseConnection.php");
    session_start();
    //Insert Game Data
    if(isset($_FILES['file']['tmp_name'])&&isset($_POST['GameName'])&&isset($_POST['GameDescription'])&&isset($_POST['RegistrationFee'])&&isset($_POST['Venue'])&&isset($_POST['Date'])&&isset($_POST['Time'])&&isset($_POST['PlayerPerTeam'])&&isset($_POST['TeamRequired'])){
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
            $sql = "INSERT INTO treasurehuntgames (GameImage,GameName,GameDescription,RegistrationFee,Venue,Date,Time,PlayerPerTeam,TeamRequired,TotalTeamJoined,TotalPlayer,UserID) VALUES ('$GameImage','$GameName','$GameDescription','$RegistrationFee','$Venue','$Date','$Time','$PlayerPerTeam','$TeamRequired','$TotalTeamJoined','$TotalPlayer','".$_SESSION['UserID']."')";
            $pdo->query($sql);
            echo "Treasure Hunt Game successfully added!";
            $pdo->commit();
        } catch (Exception $e) {
            echo $e->getMessage();
            echo "Treasure Hunt Game failed to be added!";
            $pdo->rollback();
        }

    
    } 
    //Update Game Data
    else if (isset($_POST['UpdateGame'])&&isset($_POST['UpdateGameNameData'])&&isset($_POST['UpdateGameDescriptionData'])&&isset($_POST['UpdateRegistrationFeeData'])&&isset($_POST['UpdateVenueData'])&&isset($_POST['UpdateDateData'])&&isset($_POST['UpdateTimeData'])&&isset($_POST['UpdatePlayerPerTeamData'])&&isset($_POST['UpdateTeamRequiredData'])) {

        $GameID = $_POST['UpdateGame'];
        $GameName = $_POST['UpdateGameNameData'];
        $GameDescription = $_POST['UpdateGameDescriptionData'];
        $RegistrationFee = $_POST['UpdateRegistrationFeeData'];
        $Venue = $_POST['UpdateVenueData'];
        $Date = $_POST['UpdateDateData'];
        $Time = $_POST['UpdateTimeData'];
        $PlayerPerTeam = $_POST['UpdatePlayerPerTeamData'];
        $TeamRequired = $_POST['UpdateTeamRequiredData'];
        
        try {
            $pdo->beginTransaction();
            if(isset($_FILES['file']['tmp_name'])){
                $GameImage = addslashes($_FILES['file']['tmp_name']);
                $GameImage = file_get_contents($GameImage);
                $GameImage = base64_encode($GameImage);
                $sql = "UPDATE treasurehuntgames SET GameImage='$GameImage', GameName='$GameName', GameDescription='$GameDescription', RegistrationFee='$RegistrationFee', Venue='$Venue', Date='$Date', Time='$Time', PlayerPerTeam='$PlayerPerTeam', TeamRequired='$TeamRequired' WHERE GameID='$GameID'";
            } else {
                $sql = "UPDATE treasurehuntgames SET GameName='$GameName', GameDescription='$GameDescription', RegistrationFee='$RegistrationFee', Venue='$Venue', Date='$Date', Time='$Time', PlayerPerTeam='$PlayerPerTeam', TeamRequired='$TeamRequired' WHERE GameID='$GameID'";
            }
            
            $pdo->query($sql);
            echo "Treasure Hunt Game successfully updated!";
            $pdo->commit();
        } catch (Exception $e) {
            echo $e->getMessage();
            echo "Treasure Hunt Game failed to be updated!";
            $pdo->rollback();
        }
    } 
    //Delete Game Data
    else if (isset($_GET['DeleteGame'])) {
            
        try {
            $pdo->beginTransaction();
            $sql = "DELETE FROM treasurehuntgames WHERE GameName='".$_GET['GameName']."'";
            $pdo->query($sql);
            echo "success";
            $pdo->commit();
        } catch (Exception $e) {
            echo "fail";
            $pdo->rollback();
        }

    } 
    //Display Game Detail at Organiser - Game Detail.html
    else if(isset($_GET['GameDetailOrganiser'])) {
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
                                        <input type="text" class="form-control inputGameData" value="RM'.$res['RegistrationFee'].'" readonly>
                                        <input type="number" class="form-control inputGameData" id="RegistrationFeeData" value="'.$res['RegistrationFee'].'" readonly hidden>
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
                            <input type="text" class="form-control inputGameData" id="gameID" value="'.$res['GameID'].'" hidden>
                        </div>
                        <div class="form-group" align="center">
                            <button id="teamList" type="button" class="btn button btnOGD">Team List</button>
                            <button type="button" class="btn button btnOGD" data-toggle="modal" data-target="#createNotificationModal">Create Notification</button>
                            <button type="button" class="btn button btnOGD" onclick="deleteGame(\''.$res['GameName'].'\')">Delete Game</button>
                            <button id="DisplayToUpdateGame" type="button" class="btn button btnOGD">Update Game</button>
                        </div>
                    </div>
                </div>
            </form>
            ';

            echo'
            <script>
            $("#DisplayToUpdateGame").click(function(){
                document.getElementById("UpdateGameIDData").value = document.getElementById("gameID").value
                document.getElementById("UpdateGameNameData").value = document.getElementById("GameNameData").innerHTML;
                document.getElementById("UpdateGameImageDataUpload").src = document.getElementById("GameImageData").src;
                document.getElementById("UpdateGameDescriptionData").value = document.getElementById("GameDescriptionData").value;
                document.getElementById("UpdateVenueData").value = document.getElementById("VenueData").value;
                document.getElementById("UpdateDateData").value = document.getElementById("DateData").value;
                document.getElementById("UpdateTimeData").value = document.getElementById("TimeData").value;
                document.getElementById("UpdateRegistrationFeeData").value = document.getElementById("RegistrationFeeData").value;
                document.getElementById("UpdateTeamRequiredData").value = document.getElementById("TeamRequiredData").value;
                document.getElementById("UpdatePlayerPerTeamData").value = document.getElementById("PlayerPerTeamData").value;
                $("#updateGameModal").modal("show");
            });

            $("#teamList").click(function(){
                $("#teamListPanel").slideToggle();
            });

            function deleteGame(GameName){
                if (confirm("Do you really want to delete this game?")) {
                    var form_data = "DeleteGame=Yes&GameName=" + GameName;
                    $.ajax({
                        type: "GET",
                        url: "../php/CRUD Game.php",
                        data:form_data,
                        success: function(data){
                            if(data == "success"){
                            alert("Game data has been deleted successfully!");
                            document.location.href="../html/Organiser.html";
                            } else {
                            alert("Game data failed to be deleted!");
                            }
                        }
                    });
                } 
            }

            
            </script>
            ';
        }
    //Display Game Card at Player.html
    } else if(isset($_GET['PlayerCardGame'])) {
        
        $sql = "SELECT * FROM treasurehuntgames";
        $result = $pdo->query($sql);
        $counter = 1;
        echo'<div class="row px-5 py-3 cardRow">';

        while ($res = $result->fetch()) {
            if($counter%4==1&&$counter>3){
                echo "</div>";
                echo'<div class="row px-5 py-3 cardRow">';
            }

            echo'
            <div class="col">
                <div class="card displayGameCard gameCard shadow w-100 border-0" onclick="passDatatoGameDetail(\''.$res['GameImage'].'\',\''.$res['GameName'].'\',\''.$res['GameDescription'].'\',\''.$res['Venue'].'\',\''.$res['Date'].'\',\''.$res['Time'].'\',\''.$res['RegistrationFee'].'\',\''.$res['TeamRequired'].'\',\''.$res['PlayerPerTeam'].'\',\''.$res['TotalTeamJoined'].'\',\''.$res['TotalPlayer'].'\')">
                    <div class="card-header border-0 p-0 cardPic">
                        <img src="data:image;base64,'.$res['GameImage'].'" class="img-fluid imgCard">
                        <div class="overlay">
                            <div class="text">RM '.$res['RegistrationFee'].'</div>
                        </div>
                    </div>
                    <div class="card-body generalColor">
                        <h3 class="font-weight-bold">'.$res['GameName'].'</h3>
                        <p>Venue				:	'.$res['Venue'].'</p>
                        <p>Date					:	'.date('d F Y', strtotime($res['Date'])).'</p>
                        <p>Time					:	'.date('h:i A', strtotime($res['Time'])).'</p>
                        <p>Total Team Joined	:	'.$res['TotalTeamJoined'].'/'.$res['TeamRequired'].'</p>
                        <p>Player per Team		:	'.$res['PlayerPerTeam'].'</p>
                    </div>
                </div>
            </div>
            ';
            $counter++;
        }

        echo "</div>";
        
        echo '
        <script>
        function passDatatoGameDetail(GameImage,GameName,GameDescription,Venue,Date,Time,RegistrationFee,TeamRequired,PlayerPerTeam,TotalTeamJoined,TotalPlayer){
            var queryString = "?GameName=" + GameName + "&Venue=" + Venue + "&TotalTeamJoined=" + TotalTeamJoined + "&TeamRequired=" + TeamRequired;
            window.location.href = "Player - Game Detail.html" + queryString;
        }
        </script>
        ';
    //Display Game Detail at Player - Game Detail.html
    } else if(isset($_GET['GameDetailPlayer'])) {
        
        $sql = "SELECT * FROM treasurehuntgames WHERE GameName='".$_GET['GameName']."' AND Venue='".$_GET['Venue']."'";
        $result = $pdo->query($sql);

        while ($res = $result->fetch()) {
            echo'
            <form>
                <div class="row">
                    <div class="col-sm-4">
                        <img src="data:image;base64,'.$res['GameImage'].'" class="img-fluid p-4">
                        <div class="row font-weight-bold m-2 d-flex centerItem" align="center">
                            <div class="col m-auto text-center generalColor">
                                <p>Total Team Joined</p>
                            </div>
                            <div class="col m-auto text-center">
                                <p class="teamPlayer">'.$res['TotalTeamJoined'].'/'.$res['TeamRequired'].'</p>
                            </div>
                            <div class="col m-auto text-center generalColor">
                                <p>Total Player</p>
                            </div>
                            <div class="col m-auto text-center">
                                <p class="teamPlayer">'.$res['TotalPlayer'].'</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8 px-0 py-3">
                        <div class="row px-3">
                            <div class="col px-0">
                                <div class="form-group row px-0">
                                    <label for="gameVenue" class="col-form-label col-sm-3 generalColor">Venue</label>
                                    <div class="col-sm-9 mx-0">
                                        <input type="text" class="form-control inputGameData" id="gameVenue"  value="'.$res['Venue'].'" readonly>
                                    </div>
                                </div>
                                <div class="form-group row px-0">
                                    <label for="gameDate" class="col-form-label col-sm-3 generalColor">Date</label>
                                    <div class="col-sm-9 mx-0">
                                        <input type="date" class="form-control inputGameData" id="gameDate" value="'.$res['Date'].'" readonly>
                                    </div>
                                </div>
                                <div class="form-group row px-0">
                                    <label for="gameTime" class="col-form-label col-sm-3 generalColor">Time</label>
                                    <div class="col-sm-9 mx-0">
                                        <input type="time" class="form-control inputGameData" id="gameTime" value="'.$res['Time'].'" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col px-0">
                                <div class="form-group row px-0">
                                    <label for="gameFee" class="col-form-label col-sm-4 generalColor">Fee</label>
                                    <div class="col-sm-8 mx-0">
                                        <input type="number" class="form-control inputGameData" id="gameFee" value="'.$res['RegistrationFee'].'" readonly>
                                    </div>
                                </div>
                                <div class="form-group row px-0">
                                    <label for="gameTeam" class="col-form-label col-sm-4 generalColor">Team Required</label>
                                    <div class="col-sm-8 mx-0">
                                        <input type="number" class="form-control inputGameData" id="gameTeam" value="'.$res['TeamRequired'].'" readonly>
                                    </div>
                                </div>
                                <div class="form-group row px-0">
                                    <label for="gamePlayer" class="col-form-label col-sm-4 generalColor">Player per Team</label>
                                    <div class="col-sm-8 mx-0">
                                        <input type="number" class="form-control inputGameData" id="gamePlayer" name="gamePlayer" value="'.$res['PlayerPerTeam'].'" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group px-3 my-0">
                            <label for="gameDesc" class="generalColor">Game Description</label>
                            <textarea class="form-control inputGameData" id="gameDesc" rows="7" readonly>'.$res['GameDescription'].'</textarea>
                            <input type="text" class="form-control inputGameData" id="gameID" value="'.$res['GameID'].'" hidden>
                        </div>
                    </div>
                </div>
            </form>
            ';
        }
    //Display Organiser Registered Game at Organiser - Profile.html
    } else if (isset($_GET['DisplayOrganiserRegisteredGame'])) {

        $sql = "SELECT * FROM treasurehuntgames WHERE UserID='".$_SESSION['UserID']."'";
        $result = $pdo->query($sql);
        $counter = 1;

        echo '
        <div class="carousel-item active">
        <div class="row p-0 my-3" align="center">
        ';
        while ($res = $result->fetch()) {
            if($counter%3==1&&$counter>3){
                echo "</div>";
                echo "</div>";
                echo "<div class='carousel-item'>";
                echo "<div class='row p-0 my-3' align='center'>";
            }
            echo'
            <div class="col-sm-4">
				<div class="card showGamesCard gamesCard m-0" onclick="passtoGameModal(\''.$res['GameImage'].'\',\''.$res['GameName'].'\',\''.$res['GameDescription'].'\',\''.$res['Venue'].'\',\''.$res['Date'].'\',\''.$res['Time'].'\',\''.$res['RegistrationFee'].'\',\''.$res['TeamRequired'].'\',\''.$res['PlayerPerTeam'].'\',\''.$res['TotalTeamJoined'].'\',\''.$res['TotalPlayer'].'\')">
					<img src="data:image;base64,'.$res['GameImage'].'" class="card-img-top" height="100">
					<div>
						<h5 class="font-weight-bold py-2">'.$res['GameName'].'</h5>
						<p>
                            '.$res['Venue'].'<br>
							'.date('d F Y', strtotime($res['Date'])).'
						</p>
					</div>
				</div>
			</div>
            ';
            $counter++;
        }
        
        echo "</div>";
        echo "</div>";

        echo'
        <script>
        $(".showGamesCard").click(function(){
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
        </script>
        ';
    
    //Display Player Registered Game at Player - Profile.html
    } else if (isset($_GET['DisplayPlayerRegisteredGame'])) {
        
        $sql = "SELECT DISTINCT GameID FROM gameregistration WHERE UserID='".$_SESSION['UserID']."'";
        $result = $pdo->query($sql);
        $counter = 1;

        $listRegisteredGame = array();

        while($res = $result->fetch()){
            array_push($listRegisteredGame, $res['GameID']);
        }

        $lengthListRegisteredGame = count($listRegisteredGame);

        echo '
        <div class="row px-3">
	    <div class="col">
		<h4 class="font-weight-bold generalColor">Registered Games</h4>
		</div>
		<div class="col control-top" align="right">
		<a class="btn-floating" href="#multi-item-example" data-slide="prev"><i class="fa fa-chevron-circle-left"></i></a>
		<a class="btn-floating" href="#multi-item-example" data-slide="next"><i class="fa fa-chevron-circle-right"></i></a>
		</div>
		</div>
		<!--Carousel Wrapper-->
		<div id="multi-item-example" class="carousel slide carousel-multi-item px-3" data-ride="carousel" align="center">
				
		<!--Slides-->
		<div class="carousel-inner" role="listbox">
        <div class="carousel-item active">
        <div class="row p-0 my-3" align="center">
        ';

        $totalGameRegistered = 0;
        $totalGamePlayed = 0;
        $totalUpcomingGame = 0;

        for($i = 0;$i < $lengthListRegisteredGame;$i++){
            if($counter%3==1&&$counter>3){
                echo "</div>";
                echo "</div>";
                echo "<div class='carousel-item'>";
                echo "<div class='row p-0 my-3' align='center'>";
            }
            $sql = "SELECT * FROM treasurehuntgames WHERE GameID='".$listRegisteredGame[$i]."'";
            $result = $pdo->query($sql);

            while($res = $result->fetch()){
                $totalGameRegistered++;
                if(date("Y-m-d")>$res['Date']){
                    $totalGamePlayed++;
                } else {
                    $totalUpcomingGame++;
                }
                echo'
                <div class="col-sm-4">
                    <div class="card showGamesCard gamesCard m-0" onclick="passtoGameModal(\''.$res['GameImage'].'\',\''.$res['GameName'].'\',\''.$res['GameDescription'].'\',\''.$res['Venue'].'\',\''.$res['Date'].'\',\''.$res['Time'].'\',\''.$res['RegistrationFee'].'\',\''.$res['TeamRequired'].'\',\''.$res['PlayerPerTeam'].'\',\''.$res['TotalTeamJoined'].'\',\''.$res['TotalPlayer'].'\')">
                        <img src="data:image;base64,'.$res['GameImage'].'" class="card-img-top" height="100">
                        <div class="generalColor">
                            <h5 class="font-weight-bold py-2">'.$res['GameName'].'</h5>
                            <p>
                                '.$res['Venue'].'<br>
                                '.date('d F Y', strtotime($res['Date'])).'
                            </p>
                        </div>
                    </div>
                </div>
                ';
            }
            $counter++;
        }

        echo '
        </div>
        </div>
        </div>
		<!--/.Slides-->

		</div>
	    <!--/.Carousel Wrapper-->

		<div class="row d-flex">
		<div class="col-sm-4 m-auto text-center">
		<p class="font-weight-bold generalColor">Total Games Registered</p>
		<p class="font-weight-bold generalColor circleBG mx-5">'.$totalGameRegistered.'</p>
		</div>
		<div class="col-sm-4 m-auto text-center">
		<p class="font-weight-bold generalColor">Total Games Played</p>
		<p class="font-weight-bold generalColor circleBG mx-5">'.$totalGamePlayed.'</p>
		</div>
		<div class="col-sm-4 m-auto text-center">
		<p class="font-weight-bold generalColor">Total Upcoming Games</p>
		<p class="font-weight-bold generalColor circleBG mx-5">'.$totalUpcomingGame.'</p>
		</div>
		</div>
        ';
        

        echo'
        <script>
        $(".showGamesCard").click(function(){
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
        </script>
        ';
    
    } 
    //Display Game Card at Organiser.html
    else if(isset($_GET['Card'])) {
        $sql = "SELECT * FROM treasurehuntgames WHERE UserID='".$_SESSION['UserID']."'";
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
				<div class="showGameCard gameCard card mb-2" onclick="passDatatoGameDetail(\''.$res['GameID'].'\',\''.$res['GameImage'].'\',\''.$res['GameName'].'\',\''.$res['GameDescription'].'\',\''.$res['Venue'].'\',\''.$res['Date'].'\',\''.$res['Time'].'\',\''.$res['RegistrationFee'].'\',\''.$res['TeamRequired'].'\',\''.$res['PlayerPerTeam'].'\',\''.$res['TotalTeamJoined'].'\',\''.$res['TotalPlayer'].'\')">
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

        function passDatatoGameDetail(GameID,GameImage,GameName,GameDescription,Venue,Date,Time,RegistrationFee,TeamRequired,PlayerPerTeam,TotalTeamJoined,TotalPlayer){
            var queryString = "?GameName=" + GameName + "&Venue=" + Venue + " &GameID=" + GameID;
            window.location.href = "Organiser - Game Detail.html" + queryString;
        }
        </script>
        ';
    }
    
    $pdo = null;
?>