<?php

//Create headers of the table for cvs
$dates[0] = array("Months", "Salary payment date", "Bonus payment date");

//Find current year
$current_year = date('Y');
//Find current month
$current_month = date('n');

//Returns the date of the salary payment
function salary_dates_calc($month, $year) {
    $days_in_month=cal_days_in_month(CAL_GREGORIAN,$month,$year);
    $date = $month."/".$days_in_month."/".$year;
    $timestamp = strtotime($date);
    $weekday = date('l', $timestamp);

    //Add the second column = salary payment dates
    if ($weekday === "Saturday") {
        $mm = ($month == 12) ? ($month - 11) : ++$month;
        $yy = ($month == 12) ? ++$year : $year;
        return strtotime($mm."/02/".$yy);
    } elseif ($weekday === "Sunday") {
        $mm = ($month == 12) ? ($month - 11) : ++$month;
        $yy = ($month == 12) ? ++$year : $year;
        return strtotime($mm."/01/".$yy);
    } else {
        return strtotime($date);
    }
}

//Returns the date of bonus payment
function bonus_dates_calc($month, $year) {
    $date = $month."/15/".$year;
    $timestamp = strtotime($date);
    $weekday = date('l', $timestamp);

    if ($weekday === "Saturday") {
        return strtotime($month."/19/".$year);
    } elseif ($weekday === "Sunday") {
        return strtotime($month."/18/".$year);
    } else {
        return strtotime($date);
    }
}

//Add to an array date for each month consecutively
for ($month = $current_month; $month < 13; $month++) {
    //Correct the array numbering
    $i = ($month - $current_month + 1);
    //Add the first column = the months
    $dates[$i][] = date('F', strtotime($month."/01/".$current_year));
    //Add the second column = the salary payment dates
    $dates[$i][] = date("d F Y", salary_dates_calc($month, $current_year));
    //Add the third column = the bonus payment dates
    $dates[$i][] = date("d F Y", bonus_dates_calc($month, $current_year));
}

//Create a new or write to already existing csv file the data
$fp = fopen('dates.scv', 'w');
foreach ($dates as $line) {
    fputcsv($fp, $line);
}
fclose($fp);


