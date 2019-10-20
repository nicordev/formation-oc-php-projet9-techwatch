<?php

namespace App\Controller;

use App\Service\NewsFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    /**
     * @Route("/news", name="news_index")
     * @Route("/", name="home")
     */
    public function index(NewsFetcher $newsFetcher)
    {
        $maxNewsCount = 3;
        $rssFeeds = [
            $newsFetcher->fetchRssFeed("https://afup.org/pages/site/rss.php", $maxNewsCount),
            $newsFetcher->fetchRssFeed("https://www.alsacreations.com/rss/actualites.xml", $maxNewsCount),
            $newsFetcher->fetchRssFeed("https://putaindecode.io/feed.xml", $maxNewsCount)
        ];

        return $this->render('news/index.html.twig', ["rssFeeds" => $rssFeeds]);
    }
}
