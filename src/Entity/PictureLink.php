<?php

namespace App\Entity;

use App\Entity\Traits\SizeTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class PictureLink extends Link
{
    use SizeTrait;

    public function __construct()
    {
        $this->type = self::PICTURE;
    }
}