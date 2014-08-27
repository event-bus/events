<?php

namespace Aztech\Events\Bus\Publisher;

use Aztech\Events\Bus\Publisher;

abstract class AbstractPublisherCollection
{

    /**
     *
     * @var \SplObjectStorage
     */
    protected $publishers;

    /**
     *
     * @param \Aztech\Events\Bus\Publisher[] $publishers
     */
    public function __construct(array $publishers = array())
    {
        $this->publishers = new \SplObjectStorage();

        foreach ($publishers as $publisher) {
            $this->addPublisher($publisher);
        }
    }

    /**
     *
     * @param Publisher $publisher
     */
    public function addPublisher(Publisher $publisher)
    {
        if (! $this->publishers->contains($publisher)) {
            $this->publishers->attach($publisher);
        }
    }

    /**
     *
     * @param Publisher $publisher
     */
    public function removePublisher(Publisher $publisher)
    {
        if ($this->publishers->contains($publisher)) {
            $this->publishers->detach($publisher);
        }
    }
}
