<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */

class Customer
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le prénom est obligatoire")
     * @Assert\Length(min=3, minMessage="Le prénom doit faire 3 caractères au minimum")
     */
    public string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom de famille est obligatoire")
     * @Assert\Length(min=3, minMessage="Le nom de famille doit faire 3 caractères au minimum")
     */
    public string $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'adresse email est obligatoire")
     * @Assert\Email(message="L'adresse email fournie n'est pas bien formaté")
     */
    public string $email;

    public function getFullName(): string
    {
        return "$this->firstName $this->lastName";
    }
}
