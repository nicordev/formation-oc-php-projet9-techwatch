<?php

namespace App\Controller;

use App\Entity\RssSource;
use App\Entity\TwitList;
use App\Form\RssSourceType;
use App\Form\TwitListType;
use App\Repository\RssSourceRepository;
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
        RssSourceRepository $rssSourceRepository,
        TwitListRepository $twitListRepository
    ) {
        $maxNewsCount = 3;
        $rssSources = $rssSourceRepository->findAll();
        $twitLists = $twitListRepository->findAll();
        $rssFeeds = [];

        foreach ($rssSources as $rssSource) {
            try {
                $feed = $newsFetcher->fetchRssFeed($rssSource->getUrl(), $maxNewsCount);
            } catch (NotFoundHttpException $e) {
                $this->addFlash("error", "{$rssSource->getUrl()} not found.");
            }
            if (!empty($feed)) {
                $feed["id"] = $rssSource->getId();
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
     *     "/rss-feed/{id}",
     *     name="rss_feed_show",
     *     requirements={"id": "\d+"}
     * )
     */
    public function showRssFeed(NewsFetcher $newsFetcher, RssSource $rssSource)
    {
        $rssFeed = $newsFetcher->fetchRssFeed($rssSource->getUrl());

        return $this->render('news/show.html.twig', ["rssFeed" => $rssFeed]);
    }

    /**
     * @Route("/news/create", name="news_create")
     */
    public function create(
        Request $request,
        EntityManagerInterface $manager,
        NewsFetcher $newsFetcher
    ) {
        $rssSourceForm = $this->handleRssFeedCreation($request, $manager, $newsFetcher);
        $twitListForm = $this->handleTwitListCreation($request, $manager);

        return $this->render('news/create.html.twig', [
            "rssSourceForm" => $rssSourceForm->createView(),
            "twitListForm" => $twitListForm->createView()
        ]);
    }

    /**
     * @Route(
     *     "/rss-feed/delete/{id}",
     *     name="rss_feed_delete",
     *     requirements={"id": "\d+"}
     * )
     */
    public function deleteRssFeed(RssSource $rssSource, EntityManagerInterface $manager)
    {
        $manager->remove($rssSource);
        $manager->flush();
        $this->addFlash("notice", "The RSS feed has been deleted.");

        return $this->redirectToRoute("news_list");
    }

    /**
     * @Route(
     *     "/twit-list/delete/{id}",
     *     name="twit_list_delete",
     *     requirements={"id": "\d+"}
     * )
     */
    public function deleteTwitList(TwitList $twitList, EntityManagerInterface $manager)
    {
        $target = $twitList->getTarget();
        $manager->remove($twitList);
        $manager->flush();
        $this->addFlash("notice", "The twit list {$target} has been deleted.");

        return $this->redirectToRoute("news_list");
    }

    private function handleRssFeedCreation(
        Request $request,
        EntityManagerInterface $manager,
        NewsFetcher $newsFetcher
    ) {
        $rssSource = new RssSource();
        $rssSourceForm = $this->createForm(RssSourceType::class, $rssSource);
        $rssSourceForm->handleRequest($request);

        if ($rssSourceForm->isSubmitted() && $rssSourceForm->isValid()) {
            if (!$newsFetcher->canBeParsedAsXml($rssSource->getUrl())) {
                $this->addFlash("error", "The URL {$rssSource->getUrl()} does not provide valid XML data.");
            } else {
                $manager->persist($rssSource);
                $manager->flush();

                $this->addFlash("success", "A new RSS feed has been created.");
            }
        }

        return $rssSourceForm;
    }

    private function handleTwitListCreation(
        Request $request,
        EntityManagerInterface $manager
    ) {
        $twitList = new TwitList();
        $twitListForm = $this->createForm(TwitListType::class, $twitList);
        $twitListForm->handleRequest($request);

        if ($twitListForm->isSubmitted() && $twitListForm->isValid()) {
            $manager->persist($twitList);
            $manager->flush();

            $this->addFlash("success", "The twit list {$twitList->getTarget()} has been created.");
        }

        return $twitListForm;
    }
}
