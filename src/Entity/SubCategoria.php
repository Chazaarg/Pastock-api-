<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubCategoriaRepository")
 */
class SubCategoria
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Este campo es requerido.")
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categoria", inversedBy="subCategoria")
     * @Assert\NotBlank(message="Este campo es requerido.")
     */
    private $categoria;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Producto", mappedBy="subCategoria")
     */
    private $productos;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="subCategorias")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct($user)
    {
        $this->productos = new ArrayCollection();
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }
    public function __toString()
    {
        return $this->nombre;
    }

    /**
     * @return Collection|Producto[]
     */
    public function getProductos(): Collection
    {
        return $this->productos;
    }

    public function addProducto(Producto $producto): self
    {
        if (!$this->productos->contains($producto)) {
            $this->productos[] = $producto;
            $producto->setSubCategoria($this);
        }

        return $this;
    }

    public function removeProducto(Producto $producto): self
    {
        if ($this->productos->contains($producto)) {
            $this->productos->removeElement($producto);
            // set the owning side to null (unless already changed)
            if ($producto->getSubCategoria() === $this) {
                $producto->setSubCategoria(null);
            }
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'           => $this->id,
            'nombre'        => $this->nombre,
            'categoria' => $this->categoria->getId()
        ];
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        return $this;
    }
}
