<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\RssSource", inversedBy="tags")
     */
    private $RssSources;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\TwitList", inversedBy="tags")
     */
    private $TwitLists;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function __construct()
    {
        $this->RssSources = new ArrayCollection();
        $this->TwitLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|RssSource[]
     */
    public function getRssSources(): Collection
    {
        return $this->RssSources;
    }

    public function addRssSource(RssSource $rssSource): self
    {
        if (!$this->RssSources->contains($rssSource)) {
            $this->RssSources[] = $rssSource;
        }

        return $this;
    }

    public function removeRssSource(RssSource $rssSource): self
    {
        if ($this->RssSources->contains($rssSource)) {
            $this->RssSources->removeElement($rssSource);
        }

        return $this;
    }

    /**
     * @return Collection|TwitList[]
     */
    public function getTwitLists(): Collection
    {
        return $this->TwitLists;
    }

    public function addTwitList(TwitList $twitList): self
    {
        if (!$this->TwitLists->contains($twitList)) {
            $this->TwitLists[] = $twitList;
        }

        return $this;
    }

    public function removeTwitList(TwitList $twitList): self
    {
        if ($this->TwitLists->contains($twitList)) {
            $this->TwitLists->removeElement($twitList);
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
