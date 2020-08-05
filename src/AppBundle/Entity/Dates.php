<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dates
 *
 * @ORM\Table(name="dates", uniqueConstraints={@ORM\UniqueConstraint(name="dates_un", columns={"date"})})
 * @ORM\Entity
 */
class Dates
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Dates
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


}

