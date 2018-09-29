<?php

namespace Tests\PROJET\PlatformBundle\Price;

use PHPUnit\Framework\TestCase;
use PROJET\PlatformBundle\Price\Price;

Class PriceTest extends TestCase
{
	public function testCalculateRateTypeReturnsOldWhenAgeSupToSixty()
	{
		$price = new Price();

		$this->assertEquals("old", $price->calculateRateType(65));

	}
}