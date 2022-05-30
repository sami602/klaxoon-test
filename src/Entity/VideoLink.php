<?php

namespace App\Entity;

use App\Entity\Traits\SizeTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class VideoLink extends Link
{
    use SizeTrait;

    /**
     * @var ?float
     * @ORM\Column(type="float")
     * @Groups("api")
     */
    private $duration;

    public function __construct()
    {
        $this->type = self::VIDEO;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration(?float $duration): void
    {
        $this->duration = $duration;
    }
}