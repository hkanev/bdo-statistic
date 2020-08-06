<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * ItemsPerDay
 *
 * @ORM\Table(name="items_per_day", indexes={@ORM\Index(name="items_per_day_fk", columns={"item"}), @ORM\Index(name="items_per_day_fk_1", columns={"date"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ItemsPerDayRepository")
 */
class ItemsPerDay
{

    /**
     * @var integer
     *
     * @Serializer\Groups({"items_per_day"})
     * @ORM\Column(name="quantity", type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @var integer
     *
     * @Serializer\Groups({"items_per_day"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Items
     * @Serializer\Groups({"items_per_day"})
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Items")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="item", referencedColumnName="id")
     * })
     */
    private $item;

    /**
     * @var \AppBundle\Entity\Dates
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Dates")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="date", referencedColumnName="id")
     * })
     */
    private $date;

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return ItemsPerDay
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return ItemsPerDay
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Items
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param Items $item
     * @return ItemsPerDay
     */
    public function setItem($item)
    {
        $this->item = $item;
        return $this;
    }

    /**
     * @return Dates
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param Dates $date
     * @return ItemsPerDay
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }




}
