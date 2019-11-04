<?php

namespace App\Controller;

use App\Entity\RssSource;
use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    /**
     * @Route("/tag", name="tag")
     * @Route("/tag/create", name="tag_create")
     */
    public function index(
        Request $request,
        EntityManagerInterface $manager,
        TagRepository $repository
    ) {
        $tagCreationForm = $this->handleTagCreationForm($request, $manager);
        $existingTags = $repository->findAll();

        return $this->render('tag/index.html.twig', [
            'tags' => $existingTags,
            'tagForm' => $tagCreationForm->createView()
        ]);
    }

    /**
     * @Route(
     *     "/tag/delete/{id}",
     *     name="tag_delete",
     *     requirements={"id": "\d+"}
     * )
     */
    public function delete(Tag $tag, EntityManagerInterface $manager)
    {
        $manager->remove($tag);
        $manager->flush();

        $this->addFlash("notice", "The tag {$tag->getName()} has been deleted.");

        return $this->redirectToRoute("tag");
    }

    /**
     * @Route(
     *     "/tag/add-tag-to-rss-source/{tagId}/{rssSourceId}",
     *     name="tag_add_rss_source",
     *     requirements={"tagId": "\d+", "rssSourceId": "\d+"}
     * )
     */
    public function addTagToRssSource(Tag $tag, RssSource $rssSource, EntityManagerInterface $manager)
    {
        $rssSource->addTag($tag);
        $manager->flush();

        $this->addFlash("success", "The tag {$tag->getName()} has been added to a source.");

        return new JsonResponse(\json_encode($rssSource->getTags()), 200);
    }

    /**
     * @Route(
     *     "/tag/remove-tag-from-rss-source/{tagId}/{rssSourceId}",
     *     name="tag_remove_rss_source",
     *     requirements={"tagId": "\d+", "rssSourceId": "\d+"}
     * )
     */
    public function removeTagFromRssSource(Tag $tag, RssSource $rssSource, EntityManagerInterface $manager)
    {
        $rssSource->removeTag($tag);
        $manager->flush();

        $this->addFlash("notice", "The tag {$tag->getName()} has been removed from a source.");

        return new JsonResponse(\json_encode($rssSource->getTags()), 200);
    }

    /**
     * Create a form to create a tag and handle its submission
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\Form\FormInterface
     */
    private function handleTagCreationForm(
        Request $request,
        EntityManagerInterface $manager
    ) {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($tag);
            $manager->flush();
            $this->addFlash("success", "The tag {$tag->getName()} has been created.");
        }

        return $form;
    }
}
