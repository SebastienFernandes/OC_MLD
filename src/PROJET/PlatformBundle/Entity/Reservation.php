<?php

namespace PROJET\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use PROJET\PlatformBundle\Validator\Constraints as ReservationAssert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="PROJET\PlatformBundle\Repository\ReservationRepository") 
 * @ReservationAssert\ReservationDemiJournee
 */
class Reservation
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
     * @Assert\GreaterThan("yesterday")
     *
     * @ReservationAssert\ReservationViolationDay
     * @ReservationAssert\ReservationExeptDate
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var \stdClass
     *
     * @Assert\Valid(traverse=true)
     *
     * @Assert\NotNull()
     * @ORM\ManyToMany(targetEntity="PROJET\PlatformBundle\Entity\Ticket", cascade={"persist"})
     */
    private $tickets;

    /**
     * @var bool
     *
     * @ORM\Column(name="type", type="boolean")
     */
    private $type = true;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->date    = new \Datetime();
    }


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Reservation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Reservation
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set reservation
     *
     * @param \stdClass $reservation
     *
     * @return Reservation
     */
    public function setTickets($tickets)
    {
        $this->tickets = $tickets;

        return $this;
    }

    /**
     * Get tickets
     *
     * @return \stdClass
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket)
    {
        $this->tickets->add($ticket);
    }

    public function removeTicket(Ticket $ticket)
    {
        // ...
    }

    /**
     * Set type
     *
     * @param boolean $type
     *
     * @return Reservation
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return boolean
     */
    public function getType()
    {
        return $this->type;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
