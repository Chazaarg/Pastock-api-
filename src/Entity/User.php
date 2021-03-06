<?php

namespace App\Entity;

use App\Validator as AcmeAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username", message ="El nombre de usuario ya está en uso.")
 * @UniqueEntity("email", message="El email ingresado ya está en uso.")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Este campo es requerido.")
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     * @AcmeAssert\Password
     * @Assert\NotBlank(message="Este campo es requerido.")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Este campo es requerido.")
     * @Assert\Email(
     *     message = "El email '{{ value }}' no es válido.",
     *     checkMX = true,
     *     checkHost = true
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $googleId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Producto", mappedBy="user")
     */
    private $productos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Marca", mappedBy="user")
     */
    private $marcas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Categoria", mappedBy="user")
     */
    private $categorias;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SubCategoria", mappedBy="user")
     */
    private $subCategorias;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Variante", mappedBy="user")
     */
    private $variantes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\VarianteTipo", mappedBy="user")
     */
    private $varianteTipos;

    public function __construct()
    {
        $this->productos = new ArrayCollection();
        $this->marcas = new ArrayCollection();
        $this->categorias = new ArrayCollection();
        $this->subCategorias = new ArrayCollection();
        $this->variantes = new ArrayCollection();
        $this->varianteTipos = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
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
            $producto->setUser($this);
        }

        return $this;
    }

    public function removeProducto(Producto $producto): self
    {
        if ($this->productos->contains($producto)) {
            $this->productos->removeElement($producto);
            // set the owning side to null (unless already changed)
            if ($producto->getUser() === $this) {
                $producto->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Marca[]
     */
    public function getMarcas(): Collection
    {
        return $this->marcas;
    }

    public function addMarca(Marca $marca): self
    {
        if (!$this->marcas->contains($marca)) {
            $this->marcas[] = $marca;
            $marca->setUser($this);
        }

        return $this;
    }

    public function removeMarca(Marca $marca): self
    {
        if ($this->marcas->contains($marca)) {
            $this->marcas->removeElement($marca);
            // set the owning side to null (unless already changed)
            if ($marca->getUser() === $this) {
                $marca->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Categoria[]
     */
    public function getCategorias(): Collection
    {
        return $this->categorias;
    }

    public function addCategoria(Categoria $categoria): self
    {
        if (!$this->categorias->contains($categoria)) {
            $this->categorias[] = $categoria;
            $categoria->setUser($this);
        }

        return $this;
    }

    public function removeCategoria(Categoria $categoria): self
    {
        if ($this->categorias->contains($categoria)) {
            $this->categorias->removeElement($categoria);
            // set the owning side to null (unless already changed)
            if ($categoria->getUser() === $this) {
                $categoria->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SubCategoria[]
     */
    public function getSubCategorias(): Collection
    {
        return $this->subCategorias;
    }

    public function addSubCategoria(SubCategoria $subCategoria): self
    {
        if (!$this->subCategorias->contains($subCategoria)) {
            $this->subCategorias[] = $subCategoria;
            $subCategoria->setUser($this);
        }

        return $this;
    }

    public function removeSubCategoria(SubCategoria $subCategoria): self
    {
        if ($this->subCategorias->contains($subCategoria)) {
            $this->subCategorias->removeElement($subCategoria);
            // set the owning side to null (unless already changed)
            if ($subCategoria->getUser() === $this) {
                $subCategoria->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Variante[]
     */
    public function getVariantes(): Collection
    {
        return $this->variantes;
    }

    public function addVariante(Variante $variante): self
    {
        if (!$this->variantes->contains($variante)) {
            $this->variantes[] = $variante;
            $variante->setUser($this);
        }

        return $this;
    }

    public function removeVariante(Variante $variante): self
    {
        if ($this->variantes->contains($variante)) {
            $this->variantes->removeElement($variante);
            // set the owning side to null (unless already changed)
            if ($variante->getUser() === $this) {
                $variante->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|VarianteTipo[]
     */
    public function getVarianteTipos(): Collection
    {
        return $this->varianteTipos;
    }

    public function addVarianteTipo(VarianteTipo $varianteTipo): self
    {
        if (!$this->varianteTipos->contains($varianteTipo)) {
            $this->varianteTipos[] = $varianteTipo;
            $varianteTipo->setUser($this);
        }

        return $this;
    }

    public function removeVarianteTipo(VarianteTipo $varianteTipo): self
    {
        if ($this->varianteTipos->contains($varianteTipo)) {
            $this->varianteTipos->removeElement($varianteTipo);
            // set the owning side to null (unless already changed)
            if ($varianteTipo->getUser() === $this) {
                $varianteTipo->setUser(null);
            }
        }

        return $this;
    }
}
