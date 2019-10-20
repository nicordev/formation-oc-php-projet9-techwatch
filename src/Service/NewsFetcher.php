<?php

namespace App\Service;

class NewsFetcher
{
    /**
     * Interrogate an RSS feed and fetch its content
     *
     * @param string $url
     * @param int|null $limit
     * @return array
     */
    public function fetchRssFeed(string $url, ?int $limit = null)
    {
        $curlSession = curl_init(); // Initialise curl
        curl_setopt($curlSession, CURLOPT_URL, $url); // Set the requested url
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true); // We only want a string
        $response = curl_exec($curlSession); // Send the request
        $xmlFeed = new \SimpleXMLElement($response); // The response content is XML
        $rssFeed = [];

        $rssFeed["title"] = $xmlFeed->channel->title;
        $rssFeed["description"] = $xmlFeed->channel->description;

        if ($limit) {
            $i = 0;
            foreach ($xmlFeed->channel->item as $element) {
                $rssFeed["items"][] = $element;
                $i++;
                if ($i >= $limit) {
                    break;
                }
            }
        } else {
            foreach ($xmlFeed->channel->item as $element) {
                $rssFeed["items"][] = $element;
            }
        }

        return $rssFeed;
    }
}
