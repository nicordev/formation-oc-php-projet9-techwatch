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
     * @ORM\ManyToMany(targetEntity="App\Entity\RssSource", mappedBy="tags")
     */
    private $rssSources;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\TwitList", mappedBy="tags")
     */
    private $twitLists;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function __construct()
    {
        $this->rssSources = new ArrayCollection();
        $this->twitLists = new ArrayCollection();
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
        return $this->rssSources;
    }

    public function addRssSource(RssSource $rssSource): self
    {
        if (!$this->rssSources->contains($rssSource)) {
            $this->rssSources[] = $rssSource;
        }

        return $this;
    }

    public function removeRssSource(RssSource $rssSource): self
    {
        if ($this->rssSources->contains($rssSource)) {
            $this->rssSources->removeElement($rssSource);
        }

        return $this;
    }

    /**
     * @return Collection|TwitList[]
     */
    public function getTwitLists(): Collection
    {
        return $this->twitLists;
    }

    public function addTwitList(TwitList $twitList): self
    {
        if (!$this->twitLists->contains($twitList)) {
            $this->twitLists[] = $twitList;
        }

        return $this;
    }

    public function removeTwitList(TwitList $twitList): self
    {
        if ($this->twitLists->contains($twitList)) {
            $this->twitLists->removeElement($twitList);
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
