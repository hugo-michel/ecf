<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity('codeIsbn')]
#[Assert\Cascade]
#[Gedmo\SoftDeleteable(fieldName: "deletedAt", timeAware: false, hardDelete: false)]
#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 190)]
    #[ORM\Column(length: 190)]
    private ?string $titre = null;

    #[Assert\Type(
        type: 'integer',
        message: 'The value {{ value }} is not a valid {{ type }}.',
    )]
    #[ORM\Column(nullable: true)]
    private ?int $anneeEdition = null;

    #[Assert\Type(
        type: 'integer',
        message: 'The value {{ value }} is not a valid {{ type }}.',
    )]
    #[Assert\NotBlank]
    #[ORM\Column]
    private ?int $nombrePages = null;

    #[Assert\Isbn(
        type: Assert\Isbn::ISBN_13,
        message: 'This value is not valid.',
    )]
    #[ORM\Column(length: 190, nullable: true)]
    private ?string $codeIsbn = null;

    #[ORM\ManyToOne(inversedBy: 'livres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Auteur $auteur = null;

    #[ORM\OneToMany(mappedBy: 'livre', targetEntity: Emprunt::class, cascade: ['persist', 'remove'])]
    private Collection $emprunts;

    #[Assert\Count(min: 1)]
    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'livres')]
    private Collection $genres;

    public function __construct()
    {
        $this->emprunts = new ArrayCollection();
        $this->genres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAnneeEdition(): ?int
    {
        return $this->anneeEdition;
    }

    public function setAnneeEdition(?int $anneeEdition): static
    {
        $this->anneeEdition = $anneeEdition;

        return $this;
    }

    public function getNombrePages(): ?int
    {
        return $this->nombrePages;
    }

    public function setNombrePages(int $nombrePages): static
    {
        $this->nombrePages = $nombrePages;

        return $this;
    }

    public function getCodeIsbn(): ?string
    {
        return $this->codeIsbn;
    }

    public function setCodeIsbn(?string $codeIsbn): static
    {
        $this->codeIsbn = $codeIsbn;

        return $this;
    }

    public function getAuteur(): ?Auteur
    {
        return $this->auteur;
    }

    public function setAuteur(?Auteur $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * @return Collection<int, Emprunt>
     */
    public function getEmprunts(): Collection
    {
        return $this->emprunts;
    }

    public function addEmprunt(Emprunt $emprunt): static
    {
        if (!$this->emprunts->contains($emprunt)) {
            $this->emprunts->add($emprunt);
            $emprunt->setLivre($this);
        }

        return $this;
    }

    public function removeEmprunt(Emprunt $emprunt): static
    {
        if ($this->emprunts->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getLivre() === $this) {
                $emprunt->setLivre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    public function __toString()
    {
        return "{$this->getTitre()} id {$this->getId()}";
    }
}
