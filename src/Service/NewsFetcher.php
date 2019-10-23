<?php

namespace App\Service;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $response = $this->fetch($url);

        try {
            $xmlFeed = new \SimpleXMLElement($response); // The response content is XML
        } catch (\Exception $e) {
            throw new NotFoundHttpException("The URL {$url} does not send valid xml.");
        }

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

    /**
     * Check if the response from the URL can be parsed as XML
     *
     * @param string $url
     * @return bool
     */
    public function canBeParsedAsXml(string $url)
    {
        $response = $this->fetch($url);

        try {
            new \SimpleXMLElement($response);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    private function fetch(string $url)
    {
        $curlSession = curl_init(); // Initialise curl
        curl_setopt($curlSession, CURLOPT_URL, $url); // Set the requested url
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true); // We only want a string

        return curl_exec($curlSession); // Send the request
    }
}
