<?php
    include_once("DatabaseConnection.php");
    // Select all game details to be display in the form of card
    if(isset($_GET['Card'])){
        $sql = "SELECT * FROM treasurehuntgames";
        $result = $pdo->query($sql);
        $counter = 1;

        echo "<div class='carousel-item active'>";
        echo "<div class='row'>";

        while ($res = $result->fetch()) {
            if($counter%3==1&&$counter>3){
                echo "</div>";
                echo "</div>";
                echo "<div class='carousel-item'>";
                echo "<div class='row'>";
            }
            echo'
            <div class="col-md-4 hoverCard">
				<div class="showGameCard gameCard card mb-2 generalColor" onclick="passtoGameModal(\''.$res['GameImage'].'\',\''.$res['GameName'].'\',\''.$res['GameDescription'].'\',\''.$res['Venue'].'\',\''.$res['Date'].'\',\''.$res['Time'].'\',\''.$res['RegistrationFee'].'\',\''.$res['TeamRequired'].'\',\''.$res['PlayerPerTeam'].'\',\''.$res['TotalTeamJoined'].'\',\''.$res['TotalPlayer'].'\')">
					<img class="gameImage card-img-top" src="data:image;base64,'.$res['GameImage'].'" alt="">
					<div class="card-body" align="left">
						<h4 class="card-title">'.$res['GameName'].'</h4>
						<p class="card-text">
							Venue				:	'.$res['Venue'].'<br>
							Date				:	'.date('d F Y', strtotime($res['Date'])).'<br>
							Time				:	'.date('h:i A', strtotime($res['Time'])).'<br>
						</p>
					</div>
				</div>
			</div>
            ';
            $counter++;
        }
        echo "</div>";
        echo "</div>";
    // Select all game details to be display in the form of table
    } else {
        $sql = "SELECT * FROM treasurehuntgames";
        $result = $pdo->query($sql);

        $counter = 1;
        while ($res = $result->fetch()) {
            echo '
            <tr class="gameTableData" onclick="passtoGameModal(\''.$res['GameImage'].'\',\''.$res['GameName'].'\',\''.$res['GameDescription'].'\',\''.$res['Venue'].'\',\''.$res['Date'].'\',\''.$res['Time'].'\',\''.$res['RegistrationFee'].'\',\''.$res['TeamRequired'].'\',\''.$res['PlayerPerTeam'].'\',\''.$res['TotalTeamJoined'].'\',\''.$res['TotalPlayer'].'\')">
                <td>'.$counter.'</td>
                <td><img src="data:image;base64,'.$res['GameImage'].'" width="100"></td>
                <td>'.$res['GameName'].'</td>
                <td>'.$res['Venue'].'</td>
                <td>'.date('d F Y', strtotime($res['Date'])).'</td>
                <td>'.date('h:i A', strtotime($res['Time'])).'</td>
                <td>RM '.$res['RegistrationFee'].'</td>
                <td>'.$res['TeamRequired'].'</td>
                <td>'.$res['PlayerPerTeam'].'</td>
                <td>'.$res['TotalTeamJoined'].'</td>
                <td>'.$res['TotalPlayer'].'</td>
            </tr>
            ';
            $counter++;
        }
    }
    // Game Detail Modal
    echo'
        <div class="modal fade" id="gameDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content p-2">
                    <div class="modal-body px-5">
                        <div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <h3 id="GameNameDataModal" align="left" class="modal-title font-weight-bold py-3 generalColor"></h3>
                        <img id="GameImageDataModal" src="" class="img-fluid py-3" width="900">
                        <div class="d-flex py-5">
                            <div class="row font-weight-bold m-auto text-center d-flex p-2">
                                <div class="col generalColor m-auto text-center">
                                    <h4 class="font-weight-bold">Total Team Joined</h4>
                                </div>
                                <div class="col m-auto text-center">
                                    <h4 id="TotalTeamJoinedDataModal" class="circleBG font-weight-bold"></h4>
                                </div>
                                <div class="col generalColor m-auto text-center">
                                    <h4 class="font-weight-bold">Total Player</h4>
                                </div>
                                <div class="col m-auto text-center">
                                    <h4 id="TotalPlayerDataModal" class="circleBG font-weight-bold"></h4>
                                </div>
                            </div>
                        </div>
                        <form class="py-3">
                            <div class="form-group row">
                                <label for="VenueDataModal" class="col-form-label col-sm-3">Venue</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control inputGameData" id="VenueDataModal" name="VenueDataModal" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="DateDataModal" class="col-form-label col-sm-3">Date</label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control inputGameData" id="DateDataModal" name="DateDataModal" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="TimeDataModal" class="col-form-label col-sm-3">Time</label>
                                <div class="col-sm-9">
                                    <input type="time" class="form-control inputGameData" id="TimeDataModal" name="TimeDataModal" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="RegistrationFeeDataModal" class="col-form-label col-sm-3">Registration Fee</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control inputGameData" id="RegistrationFeeDataModal" name="RegistrationFeeDataModal" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="TeamRequiredDataModal" class="col-form-label col-sm-3">Team Required</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control inputGameData" id="TeamRequiredDataModal" name="TeamRequiredDataModal" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="PlayerPerTeamDataModal" class="col-form-label col-sm-3">Player per Team</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control inputGameData" id="PlayerPerTeamDataModal" name="PlayerPerTeamDataModal" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="GameDescriptionDataModal" class="col-form-label col-sm-3" >Game Description</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control inputGameData" id="GameDescriptionDataModal" rows="6" readonly></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    ';
    // function to pass data from table list to game detail modal and open the game detail modal
    echo'

        <script>
        $(".showGameCard, .gameTableData").click(function(){
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
    
    $pdo = null;
?>