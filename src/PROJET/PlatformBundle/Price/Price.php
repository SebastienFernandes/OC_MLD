<?php

namespace PROJET\PlatformBundle\Price;


class Price
{
	public function calculateAge($ddn)
	{
		$d    = new \Datetime();
        $d2   = date_format($d,'Y');
        $d3   = date_format($d,'md');
        $ddn2 = date_format($ddn,'Y');
        $ddn3 = date_format($ddn,'md');
        $age  = $d2 - $ddn2;
        if ($d3 < $ddn3){
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
            $rate = 10;      
        }elseif ($rateType == 'child'){
            $rate = 8;
        }elseif ($rateType == 'normal'){
            $rate = 16;
        }elseif ($rateType == 'old'){
            $rate = 12;
        }

        if ($dayType == true){
            $rate = $rate / 2;
        }

        return $rate;
	}
}
