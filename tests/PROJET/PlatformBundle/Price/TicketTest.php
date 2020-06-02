<?php

namespace Tests\PROJET\PlatformBundle\Entity;

use PHPUnit\Framework\TestCase;
use Doctrine\ORM\Mapping as ORM;
use PROJET\PlatformBundle\Entity\Ticket;

Class TicketTest extends TestCase
{
	public function testLastNameIsBoB()
	{
		$Ticket = new Ticket();
		$Ticket->setLastName('BoB');

		$this->assertEquals("BoB", $Ticket->getLastName());

	}

	public function testBirthDate()
	{
		$Ticket    = new Ticket();
		$BirthDate = new \Datetime('1988-03-14');
		$Ticket->setBirthDate($BirthDate);

		$this->assertEquals($BirthDate, $Ticket->getBirthDate());

	}
}