<!DOCTYPE html>
<html>
	<head>
		<style>
			/*body {
				font-family: "Verdana";
				padding-top: 6rem;
				background-color: #f0f0f0;
				background-attachment: fixed;
				background-position: center;
				background-repeat: no-repeat;
				background-size: cover;		
			}
			.navbar {
				background-color: #02385c;
			}
			.footer {
				background-color: #02486c;
				opacity: 0.65;
				visibility: hidden;
			}
			.show-footer {
				visibility: visible;
			}
			h3 {
				filter: drop-shadow(1px 1px 1px lightblue);	
			}	
			.scrollable-menu {
				height: auto;
				max-height: 220px;
				overflow-x: hidden;
			}
			.scrollable-menu::-webkit-scrollbar {
				-webkit-appearance: none;
				width: 4px;        
			}    
			.scrollable-menu::-webkit-scrollbar-thumb {
				border-radius: 3px;
				background-color: lightgray;
				-webkit-box-shadow: 0 0 1px rgba(255,255,255,.75);        
			}	
			#trapezium {
				border-bottom: 32px solid #02385c;
				border-left: 30px solid transparent;
				transform: rotateZ(180deg);
				height: 0;
				width: 60%;
				min-width: 300px;
				border-radius: 10px 0;
			}
			#heading {
				color: white;
				transform: rotateZ(180deg);
				text-align: left;
				padding: 1px 20px;
				font-family: "Verdana";
				font-size: 22px;
			}*/		
		</style>
		<!-- <link rel="stylesheet" href="http://www2.dbs.edu.hk/150/bootstrap.min.css"> -->
	</head>
	<body>
		<!-- <div class="col-md-4"> -->
			<?php
				function f($l,$y,$ref){ //function to print calendar
					//$month=array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December"); //number representation of month to textual representation
					$n=intval(date('N',strtotime(date($y.'-'.$l.'-1')))); //day of week of first day of month
					$m=cal_days_in_month(CAL_GREGORIAN,$l,$y); //number of days in month
					$h=date('M',strtotime(date($y.'-'.$l.'-1'))); //current month
					//$h=$month[$h]; //full textual representation of current month
					$days=array(
						array("","","","","","",""), //first week
						array("","","","","","",""), //second week
						array("","","","","","",""), //third week
						array("","","","","","",""), //fourth week
						array("","","","","","",""), //fifth week
						array("","","","","","","") //sixth week
					);
					for($i=$n;$i<=$m+$n-1;$i++){
						$j=$i%7; //column of element
						$k=$i/7; //row of element
						$days[$k][$j]=$i-$n+1;
					}
					echo "<table class=\"table-condensed small text-center\" width=\"98%\">";
						echo "<tr>";
							if(isset($_REQUEST["r"])){
								$r=$_REQUEST["r"];
							}
							else{
								$r=date('m');
							}
							echo "<td  width=\"14%\">";
							if($r!=9){
								echo "<a href=\"?r=".($r==1?12:$r-1)."\">←</a>";
							}
							echo "</td>";
							echo "<td colspan=5 width=\"70%\" class=\"text-center\">$h $y</td>";
							echo "<td width=\"14%\">";
							if($r!=8){
								echo "<a href=\"?r=".($r==12?1:$r+1)."\">→</a>";
							}
							echo "</td>";
						echo "</tr>";
						echo "<tr style=\"font-weight: bold\"><td width=\"14%\">S</td><td width=\"14%\">M</td><td width=\"14%\">T</td><td width=\"14%\">W</td><td width=\"14%\">T</td><td width=\"14%\">F</td><td width=\"14%\">S</td></tr>";
						for($i=0;$i<6;$i++){
							echo "<tr>";
							for($j=0;$j<7;$j++){
								if(is_numeric($days[$i][$j])&&$days[$i][$j]>0){
									if(isset($ref[$days[$i][$j]-1])){
										if($j==0||$ref[$days[$i][$j]-1]["p"]==1){
											echo "<td width=\"14%\"","class=\"text-danger\"><b>",$days[$i][$j],"</b>","</td>";
										}
										else if($ref[$days[$i][$j]-1]["s"]==1){
											echo "<td width=\"14%\"","style=\"color: #FF952B\"><b>",$days[$i][$j],"</b>","</td>";
										}
										else if($ref[$days[$i][$j]-1]["e"]==1){
											echo "<td width=\"14%\"","class=\"text-success\"><b>",$days[$i][$j],"</b>","</td>";
										}
										else{
											echo "<td width=\"14%\"",">",$days[$i][$j],"</td>";
										}
									}
								}
								else{
									echo "<td width=\"14%\"",">",$days[$i][$j],"</td>";
								}
							}
							echo "</tr>";
						}
						echo "<tr><td colspan=\"7\" style=\"color: white\"><span class=\"badge bg-danger\">Public Holiday</span> <span class=\"badge\" style=\"background-color: #ff952b\">School Holiday</span> <span class=\"badge bg-success\">Event</span></td></tr>";
					echo "</table>";
				}
				if(isset($_REQUEST["r"])){
					$r=$_REQUEST["r"];
				}
				else{
					$r=date('m');
				}
				if(date('m')>=9){
					$y=date('Y')+($r<=8);
				}
				else{
					$y=date('Y')-($r>=9);
				}
				$r=intval($r);
				$y=intval($y);
				$files="./".$y."/".$r.".txt";
				//echo $files,"<br>";
				//echo file_get_contents($files),"<br>";
				$file=@fopen($files,"r");
				$ref=[];
				$reff=[[]];
				if($file){
					while(($u=fgets($file))!==false) {
						array_push($ref,$u);
					}
					if(!feof($file)) {
						echo "";
					}
					fclose($file);
				}
				for($i=0;$i<cal_days_in_month(CAL_GREGORIAN,$r,$y);$i++){
					if(isset($ref[$i])){
						//echo $ref[$i],"<br>";
					}
					else{
						$ref[$i]="          ";
						//echo "<br>";
					}
				}
				for($i=0;$i<cal_days_in_month(CAL_GREGORIAN,$r,$y);$i++){
					$ref[$i]=substr($ref[$i],2);
					$reff[$i]["p"]=$ref[$i][0];
					if($reff[$i]["p"]==" ") $reff[$i]["p"]=0;
					$ref[$i]=substr($ref[$i],2);
					$reff[$i]["s"]=$ref[$i][0];
					if($reff[$i]["s"]==" ") $reff[$i]["s"]=0;
					$ref[$i]=substr($ref[$i],2);
					$reff[$i]["e"]=$ref[$i][0];
					if($reff[$i]["e"]==" ") $reff[$i]["e"]=0;
					$ref[$i]=substr($ref[$i],2);
					$reff[$i]["comment"]=$ref[$i];
				}
				//print_r($reff);
				f($r,$y,$reff);
			?>
		<!-- </div> -->
	</body>
</html>