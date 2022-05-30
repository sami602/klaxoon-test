<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait SizeTrait
{
    /**
     * @ORM\Column(type="float")
     * @Groups("api")
     */
    private $width;

    /**
     * @ORM\Column(type="float")
     * @Groups("api")
     */
    private $height = null;

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(?float $width): void
    {
        $this->width = $width;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(?float $height): void
    {
        $this->height = $height;
    }
}