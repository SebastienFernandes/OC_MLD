<?php

namespace PROJET\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TicketCount
 *
 * @ORM\Table(name="ticket_count")
 * @ORM\Entity(repositoryClass="PROJET\PlatformBundle\Repository\TicketCountRepository")
 */
class TicketCount
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="day", type="datetime")
     */
    private $day;

    /**
     * @var int
     *
     * @ORM\Column(name="numbers", type="integer")
     */
    private $numbers;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set day
     *
     * @param \DateTime $day
     *
     * @return TicketCount
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return \DateTime
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set numbers
     *
     * @param integer $numbers
     *
     * @return TicketCount
     */
    public function setNumbers($numbers)
    {
        $this->numbers = $numbers;

        return $this;
    }

    /**
     * Get numbers
     *
     * @return int
     */
    public function getNumbers()
    {
        return $this->numbers;
    }
}
