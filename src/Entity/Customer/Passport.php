<?php

namespace App\Entity\Customer;

use DateTime;
use JsonSerializable;
use Doctrine\ORM\Mapping as ORM;


/**
 * @
 */
class Passport implements JsonSerializable
{
    /**
     * @ORM\Column(type="integer")
     */
    private int $serial;

    /**
     * @ORM\Column(type="integer")
     */
    private int $number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $office;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $home;

    /**
     * @ORM\Column(type="DateTime")
     */
    private DateTime $birthday;

    /**
     * @return int
     */
    public function getSerial(): int
    {
        return $this->serial;
    }

    /**
     * @param int $serial
     */
    public function setSerial(int $serial): self
    {
        $this->serial = $serial;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return string
     */
    public function getOffice(): string
    {
        return $this->office;
    }

    /**
     * @param string $office
     */
    public function setOffice(string $office): self
    {
        $this->office = $office;

        return $this;
    }

    /**
     * @return string
     */
    public function getHome(): string
    {
        return $this->home;
    }

    /**
     * @param string $home
     */
    public function setHome(string $home): self
    {
        $this->home = $home;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getBirthday(): DateTime
    {
        return $this->birthday;
    }

    /**
     * @param DateTime $birthday
     */
    public function setBirthday(DateTime $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'serial' => $this->getSerial(),
            'number' => $this->getNumber(),
            'office' => $this->getOffice(),
            'home'  => $this->getHome(),
            'birthday' => $this->getBirthday(),
        ];
    }

    public function jsonDeserialize(array $data): void
    {
        $this
            ->setSerial($data['serial'] ?? null)
            ->setNumber($data['number'] ?? null)
            ->setOffice($data['office'] ?? null)
            ->setHome($data['home'] ?? null)
            ->setBirthday($data['birthday']
                ? new DateTime($data['birthday']['date'])
                : null
            );
    }
}