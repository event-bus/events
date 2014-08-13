<?php

namespace Aztech\Events\Core\Publisher;

use Aztech\Events\Publisher;

abstract class AbstractPublisherCollection
{

    /**
     *
     * @var EventPublisher[]
     */
    protected $publishers;

    public function __construct(array $publishers = array())
    {
        $this->publishers = new \SplObjectStorage();

        foreach ($publishers as $publisher) {
            $this->addPublisher($publisher);
        }
    }

    public function addPublisher(Publisher $publisher)
    {
        if (! $this->publishers->contains($publisher)) {
            $this->publishers->attach($publisher);
        }
    }

    public function removePublisher(Publisher $publisher)
    {
        if ($this->publishers->contains($publisher)) {
            $this->publishers->detach($publisher);
        }
    }
}
