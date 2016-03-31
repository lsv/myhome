<?php
namespace HomeBundle\Service;

use PicoFeed\Reader\Reader;
use Symfony\Component\Yaml\Parser as YamlParser;

class FeedService
{

    /**
     * @var string
     */
    private $feedpath;

    /**
     * FeedService constructor.
     * @param string $feedpath
     */
    public function __construct($feedpath)
    {
        $this->feedpath = $feedpath;
    }

    /**
     * @return array
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    public function getFeedRender()
    {
        $parser = new YamlParser();
        return $parser->parse(file_get_contents($this->feedpath));
    }

    /**
     * @param string $feed
     * @return array
     * @throws \Exception
     */
    public function getItems($feed)
    {
        try {
            $reader = new Reader;
            $resource = $reader->download($feed);
            $parser = $reader->getParser(
                $resource->getUrl(),
                $resource->getContent(),
                $resource->getEncoding()
            );
            $feed = $parser->execute();
            $items = $feed->getItems();

            /** @var \PicoFeed\Parser\Item $item */
            foreach ($items as &$item) {
                /** @var \DateTime $date */
                $date = $item->getDate();
                $item->date = $date->format(\DateTime::ATOM);
            }

            return $items;
        } catch (\Exception $e) {
            throw $e;
        }
    }

}
