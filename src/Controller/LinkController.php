<?php

namespace App\Controller;

use App\Entity\Link;
use App\Entity\PictureLink;
use App\Entity\VideoLink;
use App\Repository\LinkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Embed\Embed;
use Embed\Http\NetworkException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class LinkController.
 *
 * @Route(path="/links")
 *
 * @package App\Controller
 *
 * Todo add authentication and voters to manage write and read permissions to have a totally secure api
 * Todo refactor link creation in a factory
 * Todo Add phpunit tests
 */
class LinkController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var LinkRepository
     */
    private $linkRepository;

    /**
     * CraftsmanCompanyController constructor.
     *
     * @param EntityManagerInterface $em
     * @param SerializerInterface     $serializer
     */
    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer, ValidatorInterface $validator, LinkRepository $linkRepository)
    {
        $this->em         = $em;
        $this->serializer = $serializer;
        $this->validator  = $validator;
        $this->linkRepository = $linkRepository;
    }

    /**
     * @Route("/", name="link_list")
     */
    public function listAction(): JsonResponse
    {
        return $this->json($this->linkRepository->findAll(), Response::HTTP_OK, [], ['groups' => 'api']);
    }

    /**
     * @Route("/add", name="link_add")
     */
    public function addAction(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $url = $data['url'];

        if (empty($url)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $embed = new Embed();

        try {
            $info = $embed->get($url);
            $oembed = $info->getOEmbed();

            if (empty($oembed->all())) {
                return new JsonResponse('Media not found', Response::HTTP_NOT_FOUND);
            }
        } catch (NetworkException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        switch ($info->getUri()->getHost()) {
            case 'www.flickr.com':
            case 'flickr.com':
                $link = new PictureLink();
                $link->setProvider(Link::FLICKR);
                break;

            case 'www.vimeo.com':
            case 'vimeo.com':
                $link = new VideoLink();
                $link->setProvider(Link::VIMEO);
                $info = $embed->get($url);
                $link->setDuration($info->getOEmbed()->get('duration'));
                break;

            default:
                return new JsonResponse('Only flickr and vimeo providers are currently supported.', Response::HTTP_NOT_IMPLEMENTED);
        }

        $link->setTitle($info->title);

        if ($info->code) {
            $link->setHeight($info->code->height);
            $link->setWidth($info->code->width);
        }

        $link->setAuthor($info->authorName);
        $link->setUrl($url);
        $link->setPublishedAt($info->publishedTime);

        $errors = $this->validator->validate($link);

        if ($errors->count() > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->em->persist($link);
        $this->em->flush();

        return $this->json($link, Response::HTTP_CREATED, [], ['groups' => 'api']);;
    }

    /**
     * @Route(path = "/{id}", methods = {"DELETE"})
     */
    public function deleteLink(Link $link): JsonResponse
    {
        $this->em->remove($link);
        $this->em->flush();

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}