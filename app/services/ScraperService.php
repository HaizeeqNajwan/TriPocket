<?php
namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class ScraperService
{
    public static function getLatestBlogs($destination)
    {
        $client = HttpClient::create();
        $url = 'https://www.thecrazytourist.com/search/' . urlencode($destination);

        try {
            $response = $client->request('GET', $url);
            $html = $response->getContent();

            $crawler = new Crawler($html);

            $titles = $crawler->filter('.post-title a')->each(function (Crawler $node) {
                return trim($node->text());
            });

            return array_slice($titles, 0, 5); // return top 5 titles
        } catch (\Exception $e) {
            return []; // fail gracefully
        }
    }
}
