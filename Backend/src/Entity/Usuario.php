<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;

use App\Validator\Email;
use App\Validator\DniNie;
use App\Validator\Telefono;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['usuario:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Email]
    #[Groups(['usuario:read', 'usuario:write'])]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     * Usuario por defecto con Rol_User
     */
    #[ORM\Column]
    private array $roles = ['ROLE_USER'];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(['usuario:read', 'usuario:write'])]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    #[DniNie]
    #[Groups(['usuario:read', 'usuario:write'])]
    private ?string $dni = null;

    #[ORM\Column]
    #[Telefono]
    #[Groups(['usuario:read', 'usuario:write'])]
    private ?string $telefono = null;

    #[ORM\Column(length: 255)]
    #[Groups(['usuario:read', 'usuario:write'])]
    private ?string $calle = null;

    #[ORM\Column]
    #[Groups(['usuario:read', 'usuario:write'])]
    private ?int $num_calle = null;

    #[ORM\Column]
    #[Groups(['usuario:read', 'usuario:write'])]
    private ?int $cod_postal = null;

    #[ORM\Column(length: 255)]
    #[Groups(['usuario:read', 'usuario:write'])]
    private ?string $ciudad = null;

    /**
     * @var Collection<int, Mascota>
     */
    #[ORM\OneToMany(targetEntity: Mascota::class, mappedBy: 'id_usuario', orphanRemoval: true, cascade: ['persist', 'remove'])]
    #[Groups(['usuario:read', 'usuario:write'])]
    private Collection $mascotas;

    public function __construct()
    {
        $this->mascotas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): static
    {
        $this->dni = $dni;

        return $this;
    }

    public function getTelefono(): ?int
    {
        return $this->telefono;
    }

    public function setTelefono(int $telefono): static
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getCalle(): ?string
    {
        return $this->calle;
    }

    public function setCalle(string $calle): static
    {
        $this->calle = $calle;

        return $this;
    }

    public function getNumCalle(): ?int
    {
        return $this->num_calle;
    }

    public function setNumCalle(int $num_calle): static
    {
        $this->num_calle = $num_calle;

        return $this;
    }

    public function getCodPostal(): ?int
    {
        return $this->cod_postal;
    }

    public function setCodPostal(int $cod_postal): static
    {
        $this->cod_postal = $cod_postal;

        return $this;
    }

    public function getCiudad(): ?string
    {
        return $this->ciudad;
    }

    public function setCiudad(string $ciudad): static
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * @return Collection<int, Mascota>
     */
    public function getMascotas(): Collection
    {
        return $this->mascotas;
    }

    public function addMascota(Mascota $mascota): static
    {
        if (!$this->mascotas->contains($mascota)) {
            $this->mascotas->add($mascota);
            $mascota->setIdUsuario($this);
        }

        return $this;
    }

    public function removeMascota(Mascota $mascota): static
    {
        if ($this->mascotas->removeElement($mascota)) {
            // set the owning side to null (unless already changed)
            if ($mascota->getIdUsuario() === $this) {
                $mascota->setIdUsuario(null);
            }
        }

        return $this;
    }
}
