<!DOCTYPE html>
<html>
	<head>
		<style>
			*{
				font-family:Verdana;
			}
			table{
				border-collapse:collapse;
				width:70%;
				text-align:center;
			}
			th{
				background-color:#02385c;
				color:white;
			}
			th a:link{
				color:white;
			}
			td{
				padding:5px;
				color:#02385c;
			}
			input[type="submit"]{
				font-size:inherit;
				padding:5px;
				color:#02385c;
				border:none;
				background-color:white;
			}	
		</style>
		<link rel="stylesheet" href="http://www2.dbs.edu.hk/150/bootstrap.min.css">
	</head>
	<body>
		<!-- <div class="col-md-4"> -->
			<?php
				function f($l,$y,$ref){ //function to print calendar
					$month=array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December"); //number representation of month to textual representation
					$n=intval(date('N',strtotime(date($y.'-'.$l.'-1')))); //day of week of first day of month
					$m=cal_days_in_month(CAL_GREGORIAN,$l,$y); //number of days in month
					$h=date('m',strtotime(date($y.'-'.$l.'-1'))); //current month
					$h=$month[$h]; //full textual representation of current month
					$days=array(
						array("","","","","","",""), //first week
						array("","","","","","",""),
						array("","","","","","",""),
						array("","","","","","",""),
						array("","","","","","",""), //second week
						array("","","","","","",""),
						array("","","","","","",""),
						array("","","","","","",""),
						array("","","","","","",""), //third week
						array("","","","","","",""),
						array("","","","","","",""),
						array("","","","","","",""),
						array("","","","","","",""), //fourth week
						array("","","","","","",""),
						array("","","","","","",""),
						array("","","","","","",""),
						array("","","","","","",""), //fifth week
						array("","","","","","",""),
						array("","","","","","",""),
						array("","","","","","",""),
						array("","","","","","",""), //sixth week
						array("","","","","","",""),
						array("","","","","","",""),
						array("","","","","","","")
					);
					for($i=$n;$i<=$m+$n-1;$i++){
						$j=$i%7; //column of element
						$k=$i/7+((int)($i/7))*3; //row of element
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
							echo "<th  width=\"14%\">";
							if($r!=9){
								echo "<a href=\"?r=".($r==1?12:$r-1)."\">Previous</a>";
							}
							echo "</th>";
							echo "<th colspan=5 width=\"70%\" class=\"text-center\">$h $y</th>";
							echo "<th width=\"14%\">";
							if($r!=8){
								echo "<a href=\"?r=".($r==12?1:$r+1)."\">Next</a>";
							}
							echo "</th>";
						echo "</tr>";
						echo "<tr style=\"font-weight: bold\"><td width=\"14%\">S</td><td width=\"14%\">M</td><td width=\"14%\">T</td><td width=\"14%\">W</td><td width=\"14%\">T</td><td width=\"14%\">F</td><td width=\"14%\">S</td></tr>";
						for($i=0;$i<24;$i++){
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
				$time=[];
				$times=[];
				foreach($reff as $i=>$j){
					$time[$i]=strstr($reff[$i]["comment"],':',true); //the part of string before first occurence of ' '
					$time[$i]=str_replace('&',',',$time[$i]); //replace '&' with ','
					$time[$i]=str_replace(' ','',$time[$i]); //replace ' ' with ''
					$reff[$i]["comment"]=strstr($reff[$i]["comment"],':'); //the part of string after (inclusive) first occurence of ' '
					$reff[$i]["comment"]=substr($reff[$i]["comment"],1); //remove first character ' ' from string
				}
				//print_r($time);
				//echo "<br><br>";
				//print_r($reff);
				foreach($reff as $i=>$j){
					$times[$i]=explode(",",$time[$i]); //split string by ','
					foreach($times[$i] as $s=>$t){
						if(strpos($times[$i][$s],'-')!==false){
							$aa=explode("-",$times[$i][$s]); //$aa[0] is start, $aa[1] is end
							$aa[0]=intval($aa[0]);
							$aa[1]=intval($aa[1]);
							//print_r($aa);
							//echo "<br>";
							unset($times[$i][$s]);
							//$bb=[];
							for($o=$aa[0];$o<=$aa[1];$o++){
								array_push($times[$i],$o);
								//array_push($bb,$o);
							}
							//print_r($bb);
							//echo "<br>";
						}
						else{
							$times[$i][$s]=intval($times[$i][$s]);
							//print_r($times[$i][$s]);
							//echo "<br>";
						}
					}
					sort($times[$i]);
				}
				print_r($times);
				echo "<br><br>";
				$aaa=array(array());
				foreach($reff as $i=>$j){
					foreach($times[$i] as $s=>$o){
						if(!empty($reff[$i]["comment"])&&$reff[$i]["comment"]!=" "){
							if(!empty($aaa[$times[$i][$s]])){
								array_push($aaa[$times[$i][$s]],$reff[$i]["comment"]);
							}
							else{
								$aaa[$times[$i][$s]][0]=$reff[$i]["comment"];
							}
						}
					}
				}
				print_r($aaa);
				echo "<br><br>";
				print_r($reff);
				f($r,$y,$reff);
			?>
		<!-- </div> -->
	</body>
</html>