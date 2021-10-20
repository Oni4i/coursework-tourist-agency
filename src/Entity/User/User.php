<?php

namespace App\Entity\User;

use App\Entity\Customer\Customer;
use App\Entity\Order\Order;
use App\Entity\Point\Point;
use App\Model\CRUD\CRUDShowFieldsInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface, CRUDShowFieldsInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [UserRolesInterface::ROLE_USER];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\ManyToOne(targetEntity=Point::class, inversedBy="users")
     * @ORM\JoinColumn(name="point_id", referencedColumnName="id",  onDelete="SET NULL")
     */
    private $point;

    /**
     * @ORM\OneToMany(targetEntity=Customer::class, mappedBy="createdByUser")
     */
    private $customers;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="user")
     */
    private $orders;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = UserRolesInterface::ROLE_USER;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $roles[] = UserRolesInterface::ROLE_USER;
        $this->roles = array_unique($roles);

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials() {}

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPoint(): ?Point
    {
        return $this->point;
    }

    public function setPoint(?Point $point): self
    {
        $this->point = $point;

        return $this;
    }

    /**
     * @return Collection|Customer[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->setCreatedByUser($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->removeElement($customer)) {
            if ($customer->getCreatedByUser() === $this) {
                $customer->setCreatedByUser(null);
            }
        }

        return $this;
    }

    public function getFullName(): string
    {
        return \sprintf('%s %s', $this->getFirstName(), $this->getLastName());
    }

    public function getTableFields(): array
    {
        return [
            'id'        => $this->getId(),
            'username'  => $this->getUserIdentifier(),
            'full name' => $this->getFullName(),
            'point'     => $this->getPoint() ? $this->getPoint()->getFullAddress() : 'Is not related',
            'customers' => \count($this->getCustomers()),
        ];
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }

    /**
     * get highest role of the user
     */
    public function getSupremeRole(): string
    {
        foreach (UserRolesInterface::ROLES_BY_HIERARCHY as $role) {
            if (\in_array($role, $this->getRoles())) {
                return $role;
            }
        }

        return UserRolesInterface::ROLE_USER;
    }
}
