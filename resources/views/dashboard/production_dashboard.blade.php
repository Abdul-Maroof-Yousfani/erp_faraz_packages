<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;
use App\Helpers\FinanceHelper;
?>
@extends('layouts.default')

@section('content')
<div class="well_N">    
    <?php

    $AccYearDate = DB::table('company')->select('accyearfrom','accyearto')->where('id',Session::get('run_company'))->first();
    $AccYearFrom = $AccYearDate->accyearfrom;
    $AccYearTo = $AccYearDate->accyearto;


    $start = $month = strtotime($AccYearFrom);
    $end = strtotime($AccYearTo);




    function draw_calendar($month,$year){

        /* draw table */
        $calendar = '<table cellpadding="0" cellspacing="0" class="calendar table table-bordered" style="height: 350px !important;">';

        /* table headings */
        $headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
        $calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

        /* days and weeks vars now ... */
        $running_day = date('w',mktime(0,0,0,$month,1,$year));
        $days_in_month = date('t',mktime(0,0,0,$month,1,$year));
        $days_in_this_week = 1;
        $day_counter = 0;
        $dates_array = array();

        /* row for week one */
        $calendar.= '<tr class="calendar-row text-center">';

        /* print "blank" days until the first of the current week */
        for($x = 0; $x < $running_day; $x++):
            $calendar.= '<td class="calendar-day-np text-center"> </td>';
            $days_in_this_week++;
        endfor;

        /* keep going with days.... */
        $Color='';
        for($list_day = 1; $list_day <= $days_in_month; $list_day++):
            $curr_dt = $year.'-'.$month.'-'.$list_day;
            if($curr_dt == date('Y-m-d'))
                {$Color = 'aqua';}else{$Color = '';}
            $calendar.= '<td class="calendar-day text-center" style="background-color: '.$Color.';">';
            /* add in the day number */
            $calendar.= '<div class="day-number">'.$list_day.'</div>';

            /** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
            $calendar.= str_repeat('<p> </p>',2);

            $calendar.= '</td>';
            if($running_day == 6):
                $calendar.= '</tr>';
                if(($day_counter+1) != $days_in_month):
                    $calendar.= '<tr class="calendar-row text-center">';
                endif;
                $running_day = -1;
                $days_in_this_week = 0;
            endif;
            $days_in_this_week++; $running_day++; $day_counter++;
        endfor;

        /* finish the rest of the days in the week */
        if($days_in_this_week < 8):
            for($x = 1; $x <= (8 - $days_in_this_week); $x++):
                $calendar.= '<td class="calendar-day-np text-center"> </td>';
            endfor;
        endif;

        /* final row */
        $calendar.= '</tr>';

        /* end the table */
        $calendar.= '</table>';

        /* all done, return result */
        return $calendar;
    }

    /* sample usages */

    while($month < $end):?>
    <?php
            $DtArray = explode('-',date("Y-m-d", $month));
    echo '<div class="col-sm-4">';
    echo '<h2> '.date('F Y', $month), PHP_EOL.'</h2>';
    echo draw_calendar($DtArray[1],$DtArray[0],$DtArray[0].'-'.$DtArray[1]);
    $month = strtotime("+1 month", $month);
    echo '</div>';
    endwhile;



    ?>

</div>

@endsection