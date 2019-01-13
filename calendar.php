<!DOCTYPE html>
<html>
	<head>
	<style>
		*{
			font-family:Verdana;
		}
		table{
			border-collapse:collapse;
			width:33%;
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
			function calendar($l,$y){ //function to print calendar
				$month=array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December"); //number representation of month to textual representation
				$n=intval(date('N',strtotime(date($y.'-'.$l.'-1')))); //day of week of first day of month
				$m=cal_days_in_month(CAL_GREGORIAN,$l,$y); //number of days in month
				$h=date('m',strtotime(date($y.'-'.$l.'-1'))); //current month
				$h=$month[$h]; //full textual representation of current month
				$days=array(
					array("","","","","","",""),
					array("","","","","","",""),
					array("","","","","","",""),
					array("","","","","","",""),
					array("","","","","","",""),
					array("","","","","","","")
				);
				for($i=$n;$i<=$m+$n-1;$i++){
					$j=$i%7;
					$k=$i/7;
					$days[$k][$j]=$i-$n+1;
				}
				echo "<table>";
					echo "<tr>";
						echo "<th colspan=7>$h $y</th>";
					echo "</tr>";
					echo "<tr><td>Sun</td><td>Mon</td><td>Tue</td><td>Wed</td><td>Thu</td><td>Fri</td><td>Sat</td></tr>";
					for($i=0;$i<6;$i++){
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
			calendar($l,$y);
		?>
		<table>
			<tr>
				<td style="text-align:center" colspan=2><form action="calendar.php" method="post"><input type="hidden" id="r" name="r" value="0"><input type="submit" value="<?php  echo (isset($_POST["r"]))?(($_POST["r"]!=0)?"Back to ".date('M Y'):""):"";?>"></form></td> <!--go back to current month-->
			</tr>
		</table>
	</body>
</html>
