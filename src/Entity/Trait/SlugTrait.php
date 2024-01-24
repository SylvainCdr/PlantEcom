<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;


// Création du trait SlugTrait pour générer un slug
// On ajoute la propriété unique: true pour que le slug soit unique (comme un email)

trait SlugTrait
{
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $slug = null;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}

