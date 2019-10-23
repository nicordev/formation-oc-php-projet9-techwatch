<?php

namespace App\Controller;

use App\Entity\Source;
use App\Form\SourceType;
use App\Repository\SourceRepository;
use App\Repository\TwitListRepository;
use App\Service\NewsFetcher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    /**
     * @Route("/news", name="news_list")
     * @Route("/", name="home")
     */
    public function list(
        NewsFetcher $newsFetcher,
        SourceRepository $sourceRepository,
        TwitListRepository $twitListRepository
    ) {
        $maxNewsCount = 3;
        $sources = $sourceRepository->findAll();
        $twitLists = $twitListRepository->findAll();
        $rssFeeds = [];

        foreach ($sources as $source) {
            try {
                $feed = $newsFetcher->fetchRssFeed($source->getUrl(), $maxNewsCount);
            } catch (NotFoundHttpException $e) {
                $this->addFlash("error", "{$source->getUrl()} not found.");
            }
            if (!empty($feed)) {
                $feed["id"] = $source->getId();
                $rssFeeds[] = $feed;
            }
        }

        return $this->render('news/list.html.twig', [
            "rssFeeds" => $rssFeeds,
            "twitLists" => $twitLists
        ]);
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
    public function createRssFeed(Request $request, EntityManagerInterface $manager, NewsFetcher $newsFetcher)
    {
        $source = new Source();

        $form = $this->createForm(SourceType::class, $source);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$newsFetcher->canBeParsedAsXml($source->getUrl())) {
                $this->addFlash("error", "The URL {$source->getUrl()} does not provide valid XML data.");
            } else {
                $manager->persist($source);
                $manager->flush();

                $this->addFlash("success", "A new RSS feed has been created.");
            }
        }

        return $this->render('news/create.html.twig', ["rssFeedForm" => $form->createView()]);
    }

    /**
     * @Route(
     *     "/news/delete/{id}",
     *     name="news_delete",
     *     requirements={"id": "\d+"}
     * )
     */
    public function deleteRssFeed(Source $rssFeed, EntityManagerInterface $manager)
    {
        $manager->remove($rssFeed);
        $manager->flush();
        $this->addFlash("notice", "The RSS feed has been deleted.");

        return $this->redirectToRoute("news_list");
    }
}
