<?php
/*
Jalali Date function by Milad Rastian (miladmovie AT yahoo DOT com)

//The main function which convert Gregorian to Jalali calendars is:
// Copyright (C) 2000  Roozbeh Pournader and Mohammad Toossi
//you can see complete note of those function in down of the page

		AND JALAI DATE FUNCTION
this function is simillar than date function in PHP
you can find more about it in http://jdf.farsiprojects.com
		Copyright (C)2003 FARSI PROJECTS GROUP

         //   //\\           //        //\\           //          //////
        //   //  \\         //        //  \\         //            //
       //   //    \\       //        //    \\       //            //
  \\  //   /////\\\\\     //        /////\\\\\     //            //
   \\//   //        \\   ///////   //        \\   //////////   /////

    ///////      //\\         //////   ////////
   //     //    //  \\         //     //
  //     //    //    \\       //     ///////
 //     //    /////\\\\\     //     //
////////     //        \\   //     /////////

*/


function jdate($type,$maket="now")
{
	//set 1 if you want translate number to farsi or if you don't like set 0
	$transnumber=0;
	///chosse your timezone
	$TZhours=0;
	$TZminute=0;

	if($maket=="now"){
		$year=date("Y");
		$month=date("m");
		$day=date("d");
		list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
		$maket=jmaketime(date("h")+$TZhours,date("i")+$TZminute,date("s"),$jmonth,$jday,$jyear);
	}else{
		$maket+=$TZhours*3600+$TZminute*60;
		$date=date("Y-m-d",$maket);
		list( $year, $month, $day ) = preg_split ( '/-/', $date );

		list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
		}

	$need= $maket;
	$year=date("Y",$need);
	$month=date("m",$need);
	$day=date("d",$need);
	$i=0;
	while($i<strlen($type))
	{
		$subtype=substr($type,$i,1);
		switch ($subtype)
		{

			case "A":
				$result1=date("a",$need);
				if($result1=="pm") $result.= "&#1576;&#1593;&#1583;&#1575;&#1586;&#1592;&#1607;&#1585;";
				else $result.="&#1602;&#1576;&#1604;&#8207;&#1575;&#1586;&#1592;&#1607;&#1585;";
				break;

			case "a":
				$result1=date("a",$need);
				if($result1=="pm") $result.= "&#1576;&#46;&#1592;";
				else $result.="&#1602;&#46;&#1592;";
				break;
			case "d":
				list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
				if($jday<10)$result1="0".$jday;
				else 	$result1=$jday;
				if($transnumber==1) $result.=Convertnumber2farsi($result1);
				else $result.=$result1;
				break;
			case "D":
				$result1=date("D",$need);
				if($result1=="Thu") $result1="&#1662;";
				else if($result1=="Sat") $result1="&#1588;";
				else if($result1=="Sun") $result1="&#1609;";
				else if($result1=="Mon") $result1="&#1583;";
				else if($result1=="Tue") $result1="&#1587;";
				else if($result1=="Wed") $result1="&#1670;";
				else if($result1=="Thu") $result1="&#1662;";
				else if($result1=="Fri") $result1="&#1580;";
				$result.=$result1;
				break;
			case"F":
				list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
				$result.=monthname($jmonth);
				break;
			case "g":
				$result1=date("g",$need);
				if($transnumber==1) $result.=Convertnumber2farsi($result1);
				else $result.=$result1;
				break;
			case "G":
				$result1=date("G",$need);
				if($transnumber==1) $result.=Convertnumber2farsi($result1);
				else $result.=$result1;
				break;
				case "h":
				$result1=date("h",$need);
				if($transnumber==1) $result.=Convertnumber2farsi($result1);
				else $result.=$result1;
				break;
			case "H":
				$result1=date("H",$need);
				if($transnumber==1) $result.=Convertnumber2farsi($result1);
				else $result.=$result1;
				break;
			case "i":
				$result1=date("i",$need);
				if($transnumber==1) $result.=Convertnumber2farsi($result1);
				else $result.=$result1;
				break;
			case "j":
				list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
				$result1=$jday;
				if($transnumber==1) $result.=Convertnumber2farsi($result1);
				else $result.=$result1;
				break;
			case "l":
				$result1=date("l",$need);
				if($result1=="Saturday") $result1="&#1588;&#1606;&#1576;&#1607;";
				else if($result1=="Sunday") $result1="&#1610;&#1603;&#1588;&#1606;&#1576;&#1607;";
				else if($result1=="Monday") $result1="&#1583;&#1608;&#1588;&#1606;&#1576;&#1607;";
				else if($result1=="Tuesday") $result1="&#1587;&#1607;&#32;&#1588;&#1606;&#1576;&#1607;";
				else if($result1=="Wednesday") $result1="&#1670;&#1607;&#1575;&#1585;&#1588;&#1606;&#1576;&#1607;";
				else if($result1=="Thursday") $result1="&#1662;&#1606;&#1580;&#1588;&#1606;&#1576;&#1607;";
				else if($result1=="Friday") $result1="&#1580;&#1605;&#1593;&#1607;";
				$result.=$result1;
				break;
			case "m":
				list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
				if($jmonth<10) $result1="0".$jmonth;
				else	$result1=$jmonth;
				if($transnumber==1) $result.=Convertnumber2farsi($result1);
				else $result.=$result1;
				break;
			case "M":
				list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
				$result.=monthname($jmonth);
				break;
			case "n":
				list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
				$result1=$jmonth;
				if($transnumber==1) $result.=Convertnumber2farsi($result1);
				else $result.=$result1;
				break;
			case "s":
				$result1=date("s",$need);
				if($transnumber==1) $result.=Convertnumber2farsi($result1);
				else $result.=$result1;
				break;
			case "S":
				$result.="&#1575;&#1605;";
				break;
			case "t":
				$result.=lastday ($month,$day,$year);
				break;
			case "w":
				$result1=date("w",$need);
				if($transnumber==1) $result.=Convertnumber2farsi($result1);
				else $result.=$result1;
				break;
			case "y":
				list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
				$result1=substr($jyear,2,4);
				if($transnumber==1) $result.=Convertnumber2farsi($result1);
				else $result.=$result1;
				break;
			case "Y":
				list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
				$result1=$jyear;
				if($transnumber==1) $result.=Convertnumber2farsi($result1);
				else $result.=$result1;
				break;
			default:
				$result.=$subtype;
		}
	$i++;
	}
	return $result;
}



function jmaketime($hour,$minute,$second,$jmonth,$jday,$jyear)
{
	list( $year, $month, $day ) = jalali_to_gregorian($jyear, $jmonth, $jday);
	$i=mktime($hour,$minute,$second,$month,$day,$year);
	return $i;
}


///Find Day Begining Of Month
function mstart($month,$day,$year)
{
	list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
	list( $year, $month, $day ) = jalali_to_gregorian($jyear, $jmonth, "1");
	$timestamp=mktime(0,0,0,$month,$day,$year);
	return date("w",$timestamp);
}
//Find Number Of Days In This Month
function lastdayofweek ($month,$day,$year)
{
	$lastdayen=date("d",mktime(0,0,0,$month+1,0,$year));
//	list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
//	$lastdatep=$jday;
//	$jday=$jday2;
	while(date("l",mktime(0,0,0,$month,$day,$year))!="Thursday")
	{
		if($day<$lastdayen)
		{
			$day++;
		}
		else
		{
			$day=0;
			$month++;
			if($month==13)
			{
					$month="1";
					$year++;
			}
		}

	}
	return mktime(0,0,0,$month,$day,$year);
}

//Find Number Of Days In This Month
function lastday ($month,$day,$year)
{
	$lastdayen=date("d",mktime(0,0,0,$month+1,0,$year));
	list( $jyear, $jmonth, $jday ) = gregorian_to_jalali($year, $month, $day);
	$lastdatep=$jday;
	$jday=$jday2;
	while($jday2!="1")
	{
		if($day<$lastdayen)
		{
			$day++;
			list( $jyear, $jmonth, $jday2 ) = gregorian_to_jalali($year, $month, $day);
			if($jdate2=="1") break;
			if($jdate2!="1") $lastdatep++;
		}
		else
		{
			$day=0;
			$month++;
			if($month==13)
			{
					$month="1";
					$year++;
			}
		}

	}
	return $lastdatep-1;
}

//translate number of month to name of month
function monthname($month)
{

    if($month=="01") return "&#1601;&#1585;&#1608;&#1585;&#1583;&#1610;&#1606;";

    if($month=="02") return "&#1575;&#1585;&#1583;&#1610;&#1576;&#1607;&#1588;&#1578;";

    if($month=="03") return "&#1582;&#1585;&#1583;&#1575;&#1583;";

    if($month=="04") return  "&#1578;&#1610;&#1585;";

    if($month=="05") return "&#1605;&#1585;&#1583;&#1575;&#1583;";

    if($month=="06") return "&#1588;&#1607;&#1585;&#1610;&#1608;&#1585;";

    if($month=="07") return "&#1605;&#1607;&#1585;";

    if($month=="08") return "&#1570;&#1576;&#1575;&#1606;";

    if($month=="09") return "&#1570;&#1584;&#1585;";

    if($month=="10") return "&#1583;&#1610;";

    if($month=="11") return "&#1576;&#1607;&#1605;&#1606;";

    if($month=="12") return "&#1575;&#1587;&#1601;&#1606;&#1583;";
}

////here convert to  number in persian
function Convertnumber2farsi($srting)
{
	$num0="&#1776;";
	$num1="&#1777;";
	$num2="&#1778;";
	$num3="&#1779;";
	$num4="&#1780;";
	$num5="&#1781;";
	$num6="&#1782;";
	$num7="&#1783;";
	$num8="&#1784;";
	$num9="&#1785;";

	$stringtemp="";
	$len=strlen($srting);
	for($sub=0;$sub<$len;$sub++)
	{
	 if(substr($srting,$sub,1)=="0")$stringtemp.=$num0;
	 elseif(substr($srting,$sub,1)=="1")$stringtemp.=$num1;
	 elseif(substr($srting,$sub,1)=="2")$stringtemp.=$num2;
	 elseif(substr($srting,$sub,1)=="3")$stringtemp.=$num3;
	 elseif(substr($srting,$sub,1)=="4")$stringtemp.=$num4;
	 elseif(substr($srting,$sub,1)=="5")$stringtemp.=$num5;
	 elseif(substr($srting,$sub,1)=="6")$stringtemp.=$num6;
	 elseif(substr($srting,$sub,1)=="7")$stringtemp.=$num7;
	 elseif(substr($srting,$sub,1)=="8")$stringtemp.=$num8;
	 elseif(substr($srting,$sub,1)=="9")$stringtemp.=$num9;
	 else $stringtemp.=substr($srting,$sub,1);

	}
return   $stringtemp;

}///end conver to number in persian





// "jalali.php" is convertor to and from Gregorian and Jalali calendars.
// Copyright (C) 2000  Roozbeh Pournader and Mohammad Toossi
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// A copy of the GNU General Public License is available from:
//
//    <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">http://www.gnu.org/copyleft/gpl.html</a>
//


function div($a,$b) {
    return (int) ($a / $b);
}

function gregorian_to_jalali ($g_y, $g_m, $g_d)
{
    $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);





   $gy = $g_y-1600;
   $gm = $g_m-1;
   $gd = $g_d-1;

   $g_day_no = 365*$gy+div($gy+3,4)-div($gy+99,100)+div($gy+399,400);

   for ($i=0; $i < $gm; ++$i)
      $g_day_no += $g_days_in_month[$i];
   if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0)))
      /* leap and after Feb */
      $g_day_no++;
   $g_day_no += $gd;

   $j_day_no = $g_day_no-79;

   $j_np = div($j_day_no, 12053); /* 12053 = 365*33 + 32/4 */
   $j_day_no = $j_day_no % 12053;

   $jy = 979+33*$j_np+4*div($j_day_no,1461); /* 1461 = 365*4 + 4/4 */

   $j_day_no %= 1461;

   if ($j_day_no >= 366) {
      $jy += div($j_day_no-1, 365);
      $j_day_no = ($j_day_no-1)%365;
   }

   for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i)
      $j_day_no -= $j_days_in_month[$i];
   $jm = $i+1;
   $jd = $j_day_no+1;

   return array($jy, $jm, $jd);
}

function jalali_to_gregorian($j_y, $j_m, $j_d) 
{
   $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
   $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);

   $jy = $j_y-979;
   $jm = $j_m-1;
   $jd = $j_d-1;

   $j_day_no = 365*$jy + div($jy, 33)*8 + div($jy%33+3, 4);
   for ($i=0; $i < $jm; ++$i)
      $j_day_no += $j_days_in_month[$i];

   $j_day_no += $jd;

   $g_day_no = $j_day_no+79;

   $gy = 1600 + 400*div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
   $g_day_no = $g_day_no % 146097;

   $leap = true;
   if ($g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */
   {
      $g_day_no--;
      $gy += 100*div($g_day_no,  36524); /* 36524 = 365*100 + 100/4 - 100/100 */
      $g_day_no = $g_day_no % 36524;

      if ($g_day_no >= 365)
         $g_day_no++;
      else
         $leap = false;
   }

   $gy += 4*div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */
   $g_day_no %= 1461;

   if ($g_day_no >= 366) {
      $leap = false;

      $g_day_no--;
      $gy += div($g_day_no, 365);
      $g_day_no = $g_day_no % 365;
   }

   for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
      $g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
   $gm = $i+1;
   $gd = $g_day_no+1;

   return array($gy, $gm, $gd);
}
