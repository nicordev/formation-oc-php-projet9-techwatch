<?php

namespace App\Controller;

use App\Entity\RssSource;
use App\Entity\TwitList;
use App\Form\RssSourceType;
use App\Form\TwitListType;
use App\Repository\RssSourceRepository;
use App\Repository\TagRepository;
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
                $feed = $newsFetcher->fetchRssFeed(
                    $rssSource->getUrl(),
                    $maxNewsCount,
                    false,
                    false
                );
            } catch (NotFoundHttpException $e) {
                $this->addFlash("error", "{$rssSource->getUrl()} not found.");
            }
            if (!empty($feed)) {
                $feed["id"] = $rssSource->getId();
                $feed["tags"] = $rssSource->getTags();
                $feed["name"] = $rssSource->getName();
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
        $rssSourceForm = $this->handleRssSourceForm($request, $manager, $newsFetcher);
        $twitListForm = $this->handleTwitListForm($request, $manager);

        return $this->render('news/create.html.twig', [
            "rssSourceForm" => $rssSourceForm->createView(),
            "twitListForm" => $twitListForm->createView()
        ]);
    }

    /**
     * @Route(
     *     "/rss-feed/edit/{id}",
     *     name="rss_source_edit",
     *     requirements={"id": "\d+"}
     * )
     */
    public function editRssSource(
        RssSource $rssSource,
        Request $request,
        EntityManagerInterface $manager,
        NewsFetcher $newsFetcher,
        TagRepository $tagRepository
    ) {
        $rssSourceForm = $this->handleRssSourceForm(
            $request,
            $manager,
            $newsFetcher,
            $rssSource
        );
        $availableTags = $tagRepository->findAll();

        return $this->render('news/edit_rss_source.html.twig', [
            "rssSourceForm" => $rssSourceForm->createView(),
            "availableTags" => $availableTags
        ]);
    }

    /**
     * @Route(
     *     "/twit-list/edit/{id}",
     *     name="twit_list_edit",
     *     requirements={"id": "\d+"}
     * )
     */
    public function editTwitList(
        TwitList $twitList,
        Request $request,
        EntityManagerInterface $manager,
        TagRepository $tagRepository
    ) {
        $twitListForm = $this->handleTwitListForm(
            $request,
            $manager,
            $twitList
        );
        $availableTags = $tagRepository->findAll();

        return $this->render('news/edit_twit_list.html.twig', [
            "twitListForm" => $twitListForm->createView(),
            "availableTags" => $availableTags
        ]);
    }

    /**
     * @Route(
     *     "/rss-feed/delete/{id}",
     *     name="rss_source_delete",
     *     requirements={"id": "\d+"}
     * )
     */
    public function deleteRssSource(RssSource $rssSource, EntityManagerInterface $manager)
    {
        $manager->remove($rssSource);
        $manager->flush();
        $this->addFlash("notice", "The RSS source has been deleted.");

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

    /**
     * Make a form to create or edit an RSS source and handle the request
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param NewsFetcher $newsFetcher
     * @param RssSource|null $rssSource
     * @return \Symfony\Component\Form\FormInterface
     */
    private function handleRssSourceForm(
        Request $request,
        EntityManagerInterface $manager,
        NewsFetcher $newsFetcher,
        ?RssSource $rssSource = null
    ) {
        if (null === $rssSource) {
            $rssSource = new RssSource();
            $successMessage = "A new RSS feed has been created.";
        } else {
            $successMessage = 'The RSS feed "' .
                ($rssSource->getName() ?? $rssSource->getUrl()) .
                '" has been modified.';
        }

        $rssSourceForm = $this->createForm(RssSourceType::class, $rssSource);
        $rssSourceForm->handleRequest($request);

        if ($rssSourceForm->isSubmitted() && $rssSourceForm->isValid()) {
            if (!$newsFetcher->canBeParsedAsXml($rssSource->getUrl())) {
                $this->addFlash("error", "The URL {$rssSource->getUrl()} does not provide valid XML data.");
            } else {
                $manager->persist($rssSource);
                $manager->flush();
                $this->addFlash("success", $successMessage);
            }
        }

        return $rssSourceForm;
    }

    /**
     * Make a form to create or edit a Twit list source and handle the request
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param TwitList|null $twitList
     * @return \Symfony\Component\Form\FormInterface
     */
    private function handleTwitListForm(
        Request $request,
        EntityManagerInterface $manager,
        ?TwitList $twitList = null
    ) {
        if (null === $twitList) {
            $twitList = new TwitList();
            $successMessage = "A new Twit list has been created.";
        } else {
            $successMessage = 'The Twit list "' .
                ($twitList->getName() ?? $twitList->getTarget()) .
                '" has been modified.';
        }

        $twitListForm = $this->createForm(TwitListType::class, $twitList);
        $twitListForm->handleRequest($request);

        if ($twitListForm->isSubmitted() && $twitListForm->isValid()) {
            $manager->persist($twitList);
            $manager->flush();
            $this->addFlash("success", $successMessage);
        }

        return $twitListForm;
    }
}
