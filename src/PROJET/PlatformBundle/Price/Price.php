<?php

namespace PROJET\PlatformBundle\Price;


class Price
{
    public function calculateAge($BirthDate)
    {
        $date           = new \Datetime();
        $year           = date_format($date,'Y');
        $month          = date_format($date,'md');
        $BirthDateYear  = date_format($BirthDate,'Y');
        $BirthDateMonth = date_format($BirthDate,'md');
        $age            = $year - $BirthDateYear;
        if ($month < $BirthDateMonth){
            $age = $age - 1;
        }

        return $age;
    }

    public function calculateRateType($age, $reduced)
    {
        if ($age < 4){
            $rateType = 'free';
        }elseif (12 <= $age && $reduced == true){
            $rateType = 'reduced';      
        }elseif (4 <= $age && $age < 12){
            $rateType = 'child';
        }elseif (12 <= $age && $age < 60){
            $rateType = 'normal';
        }elseif (60 <= $age){
            $rateType = 'old';
        }

        return $rateType;
    }

    public function calculateRate($rateType, $dayType)
    {
        if ($rateType == 'free'){
            $rate = 0;
        }elseif ($rateType == 'reduced'){
            $rate = 1000;      
        }elseif ($rateType == 'child'){
            $rate = 800;
        }elseif ($rateType == 'normal'){
            $rate = 1600;
        }elseif ($rateType == 'old'){
            $rate = 1200;
        }

        if ($dayType == true){
            $rate = $rate / 2;
        }

        return $rate;
    }
}
