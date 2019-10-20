<?php

namespace App\Controller;

use App\Entity\Source;
use App\Form\SourceType;
use App\Repository\SourceRepository;
use App\Service\NewsFetcher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    /**
     * @Route("/news", name="news_list")
     * @Route("/", name="home")
     */
    public function list(NewsFetcher $newsFetcher, SourceRepository $repository)
    {
        $maxNewsCount = 3;
        $sources = $repository->findAll();
        $rssFeeds = [];

        foreach ($sources as $source) {
            $feed = $newsFetcher->fetchRssFeed($source->getUrl(), $maxNewsCount);
            $feed["id"] = $source->getId();
            $rssFeeds[] = $feed;
        }

        return $this->render('news/list.html.twig', ["rssFeeds" => $rssFeeds]);
    }

    /**
     * @Route(
     *     "/news/{id}",
     *     name="news_show",
     *     requirements={"id": "\d+"}
     * )
     */
    public function showRssFeed(NewsFetcher $newsFetcher, Source $rssFeed)
    {
        $rssFeed = $newsFetcher->fetchRssFeed($rssFeed->getUrl());

        return $this->render('news/show.html.twig', ["rssFeed" => $rssFeed]);
    }

    /**
     * @Route("/news/create", name="news_create")
     */
    public function createRssFeed(Request $request, EntityManagerInterface $manager)
    {
        $source = new Source();

        $form = $this->createForm(SourceType::class, $source);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($source);
            $manager->flush();

            return $this->redirectToRoute("news_list");
        }

        return $this->render('news/create.html.twig', ["rssFeedForm" => $form->createView()]);
    }
}
