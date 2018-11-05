<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ColumnRepository")
 * @ORM\Table(name="myColumns")
 *
 */
class Column
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="string")
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(name="capacity", type="integer", nullable=true)
     */
    private $capacity;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Card", mappedBy="inColumn", fetch="EAGER")
     */
    private $cards;

    /**
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(?int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getCards()
    {
        return $this->cards;
    }

    public function setCards(string $cards): self
    {
        $this->cards = $cards;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
}
