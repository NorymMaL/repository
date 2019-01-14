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
	</head>
	<body>
		<table>
			<tr>
				<td style="text-align:left"><form action="calendar.php" method="post"><input type="hidden" id="r" name="r" value="<?php echo isset($_POST["r"])?$_POST["r"]-1:-1;?>"><input type="submit" name="Previous" value="Previous"></form></td> <!--go to last month-->
				<td style="text-align:right"><form action="calendar.php" method="post"><input type="hidden" id="r" name="r" value="<?php echo isset($_POST["r"])?$_POST["r"]+1:1;?>"><input type="submit" name="Next" value="Next"></form></td> <!--go to next month-->
			</tr>
		</table>
		<?php		
			function calendar($l,$y,$d_e){ //function to print calendar
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
					$days[$k+1][$j]=$d_e[$i-$n+1][0];
					$days[$k+2][$j]=$d_e[$i-$n+1][1];
					$days[$k+3][$j]=$d_e[$i-$n+1][2];
				}
				echo "<table>";
					echo "<tr>";
						echo "<th colspan=7>$h $y</th>";
					echo "</tr>";
					echo "<tr><td>Sun</td><td>Mon</td><td>Tue</td><td>Wed</td><td>Thu</td><td>Fri</td><td>Sat</td></tr>";
					for($i=0;$i<24;$i++){
						echo "<tr>";
						for($j=0;$j<7;$j++){
							echo "<td>",$days[$i][$j],"</td>";
						}
						echo "</tr>";
					}
				echo "</table>";
			}
			if(isset($_POST["r"])){
				$r=$_POST["r"];
			}
			else{
				$r=0;
			}
			$l=intval(date('m',strtotime("$r month")));
			$y=intval(date('Y',strtotime("$r month")));
			$filename="events_".(string)$l."_".(string)$y.".txt";
			$file=@fopen($filename,"r");
			$events=[];
			$time=[];
			$times=[];
			if($file){
				while(($u=fgets($file))!==false) {
					array_push($events,$u);
				}
				if(!feof($file)) {
					echo "";
				}
				//print_r($events);
				fclose($file);
			}
			foreach($events as $p=>$q){
				$time[$p]=strstr($events[$p],':',true); //the part of string before first occurence of ' '
				$time[$p]=str_replace('&',',',$time[$p]); //replace '&' with ','
				$time[$p]=str_replace(' ','',$time[$p]); //replace ' ' with ''
				$events[$p]=strstr($events[$p],':'); //the part of string after (inclusive) first occurence of ' '
				$events[$p]=substr($events[$p],1); //remove first character ' ' from string
			}
			//print_r($time);
			//echo "<br>";
			//print_r($events);
			//echo "<br>";
			foreach($events as $p=>$q){
				$times[$p]=explode(",",$time[$p]); //split string by ','
				foreach($times[$p] as $r=>$s){
					if(strpos($times[$p][$r],'-')!==false){
						$aa=explode("-",$times[$p][$r]); //$aa[0] is start, $aa[1] is end
						$aa[0]=intval($aa[0]);
						$aa[1]=intval($aa[1]);
						//print_r($aa);
						//echo "<br>";
						unset($times[$p][$r]);
						//$bb=[];
						for($o=$aa[0];$o<=$aa[1];$o++){
							array_push($times[$p],$o);
							//array_push($bb,$o);
						}
						//print_r($bb);
						//echo "<br>";
					}
					else{
						$times[$p][$r]=intval($times[$p][$r]);
						//print_r($times[$p][$r]);
						//echo "<br>";
					}
				}
				sort($times[$p]);
			}
			//print_r($times);
			//print_r($events);
			$d_e=array(array());
			foreach($events as $p=>$q){
				foreach($times[$p] as $r=>$s){
					if(!empty($d_e[$times[$p][$r]])){
						array_push($d_e[$times[$p][$r]],$events[$p]);
					}
					else{
						$d_e[$times[$p][$r]][0]=$events[$p];
					}
				}
			}
			for($d=1;$d<=35;$d++){
				if(!isset($d_e[$d][0])) $d_e[$d][0]="";
				if(!isset($d_e[$d][1])) $d_e[$d][1]="";
				if(!isset($d_e[$d][2])) $d_e[$d][2]="";
			}
			unset($d_e[0]);
			ksort($d_e);
			//print_r($d_e);
			calendar($l,$y,$d_e);
		?>
		<table>
			<tr>
				<td style="text-align:center" colspan=2><form action="calendar.php" method="post"><input type="hidden" id="r" name="r" value="0"><input type="submit" value="<?php  echo (isset($_POST["r"]))?(($_POST["r"]!=0)?"Back to ".date('M Y'):""):"";?>"></form></td> <!--go back to current month-->
			</tr>
		</table>
	</body>
</html>
