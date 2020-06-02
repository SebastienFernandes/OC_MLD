<?php

namespace Tests\PROJET\PlatformBundle\Price;

use PHPUnit\Framework\TestCase;
use PROJET\PlatformBundle\Price\Price;

Class PriceTest extends TestCase
{
	private $price;

	public function setUp()
	{
		$this->price = new Price();
	}

	public function testCalculateRateTypeReturnsOldWhenAgeSupToSixty()
	{

		$this->assertEquals(Price::RATE_OLD, $this->price->calculateRateType(65));

	}

	public function testCalculateRateReturns500WhenReducedOnDayTypeTrue()
	{

		$this->assertEquals(500, $this->price->calculateRate(Price::RATE_REDUCED, true));

	}

	public function testCalculateAge()
	{		
		$now 		= new \DateTime();
		$BirthDate 	= new \Datetime('1988-03-14');
        $diff 		= $now->diff($BirthDate);
        $age 		= $diff->y;

		$this->assertEquals($age, $this->price->calculateAge($BirthDate));

	}

	public function testCalculateRateTypeExceptionLess()
	{
		$this->expectException(\InvalidArgumentException::class);

		$this->price->calculateRateType(-2);

	}

	public function testCalculateRateTypeExceptionMore()
	{
		$this->expectException(\InvalidArgumentException::class);

		$this->price->calculateRateType(121);

	}
}