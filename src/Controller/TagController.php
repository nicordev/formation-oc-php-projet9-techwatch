<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    /**
     * @Route("/tag", name="tag")
     */
    public function index(TagRepository $repository)
    {
        $existingTags = $repository->findAll();

        return $this->render('tag/index.html.twig', [
            'tags' => $existingTags
        ]);
    }

    /**
     * @Route("/tag/create", name="tag_create")
     */
    public function create(
        Request $request,
        EntityManagerInterface $manager,
        TagRepository $repository
    ) {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($tag);
            $manager->flush();
            $this->addFlash("success", "The tag {$tag->getName()} has been created.");
        }

        $existingTags = $repository->findAll();

        return $this->render('tag/create.html.twig', [
            'tags' => $existingTags,
            'tagForm' => $form->createView()
        ]);
    }
}
