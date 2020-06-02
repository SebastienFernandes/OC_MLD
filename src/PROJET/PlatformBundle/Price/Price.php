<?php

namespace PROJET\PlatformBundle\Price;


class Price
{
    public const RATE_FREE      ='free';
    public const RATE_REDUCED   ='reduced';
    public const RATE_CHILD     ='child';
    public const RATE_NORMAL    ='normal';
    public const RATE_OLD       ='old';

    public function calculateAge(\DateTime $BirthDate)
    {
        $now = new \DateTime();
        $diff = $now->diff($BirthDate);

        return $diff->y;
    }

    public function calculateRateType(int $age, bool $reduced = false)
    {

        if($age <= 0 || $age >= 120) {
            throw new \InvalidArgumentException('Merci de donner votre age.');
        }

        switch(true) {
            case $age < 4:
                $rateType = self::RATE_FREE;
                break;
            case 4 <= $age && $age < 12:
                $rateType = self::RATE_CHILD;
                break;
            case 12 <= $age && $age < 60:
                $rateType = self::RATE_NORMAL;
                break;
            case 12 <= $age && $reduced === true:
                $rateType = self::RATE_REDUCED;
                break;
            case 60 <= $age:
                $rateType = self::RATE_OLD;
                break;
            default:
                echo "Merci de donner votre age";
                break;
        }

        return $rateType;
    }

    public function calculateRate($rateType, $dayType)
    {
        switch(true) {
            case $rateType == self::RATE_FREE:
                $rate = 0;
                break;
            case $rateType == self::RATE_REDUCED:
                $rate = 1000;
                break;
            case $rateType == self::RATE_CHILD:
                $rate = 800;
                break;
            case $rateType == self::RATE_NORMAL:
                $rate = 1600;
                break;
            case $rateType == self::RATE_OLD:
                $rate = 1200;
                break;
            default:
                echo "une erreure est survenue.";
                break;
        }

        if ($dayType == true){
            $rate = $rate / 2;
        }

        return $rate;
    }
}
