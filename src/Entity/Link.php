<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\LinkRepository;

/**
 * @ORM\Entity(repositoryClass=LinkRepository::class)
 * @UniqueEntity("url")
 * @ORM\HasLifecycleCallbacks()
 *
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn("type", type="string", length=10)
 * @ORM\DiscriminatorMap({
 *     Link::PICTURE = PictureLink::class,
 *     Link::VIDEO = VideoLink::class,
 * })
 */
class Link
{
    // TYPES
    public const VIDEO = 'video';
    public const PICTURE = 'picture';

    // PROVIDERS
    public const FLICKR = 'flickr';
    public const VIMEO = 'vimeo';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups("api")
     */
    protected $id;

    /**
     * @var ?string
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="2", max="255")
     * @Assert\NotBlank()
     * @Assert\Url()
     *
     * @Groups("api")
     */
    protected $url;

    /**
     * @var ?string
     * @ORM\Column(type="string", nullable=false)
     * @Assert\Choice(callback="getProviders")
     * @Groups("api")
     */
    protected $provider;

    /**
     * @var string
     *
     * @Groups({"api"})
     */
    protected $type;

    /**
     * @var ?string
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="1", max="255")
     * @Assert\NotBlank()
     *
     * @Groups({"api"})
     */
    protected $title;

    /**
     * @var ?string
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="1", max="255")
     * @Assert\NotBlank()
     *
     * @Groups({"api"})
     */
    protected $author;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     * @Groups({"api"})
     */
    protected $addedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedAt;

    public function __construct()
    {
    }

    public static function getTypes()
    {
        return [self::PICTURE, self::VIDEO];
    }

    public static function getProviders()
    {
        return [self::FLICKR, self::VIMEO];
    }

    /**
     * @ORM\PrePersist()
     */
    public function setAddedAtValue()
    {
        $this->addedAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @param ?string $provider
     */
    public function setProvider(?string $provider): void
    {
        $this->provider = $provider;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): void
    {
        $this->author = $author;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getAddedAt(): ?\DateTime
    {
        return $this->addedAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPublishedAt(): ?\DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTime $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }
}