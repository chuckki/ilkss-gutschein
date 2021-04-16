<?php

namespace App\Entity;

use App\Repository\GutscheinRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GutscheinRepository::class)
 */
class Gutschein
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gs_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $gs_betrag;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $gs_bemerkung;

    /**
     * @ORM\Column(type="date")
     */
    private $gs_date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="gutscheine")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPayed;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $gs_nummer;

    /**
     * @ORM\Column(type="integer")
     */
    private $kurstyp;

    /**
     * Gutschein constructor.
     */
    public function __construct()
    {
        $this->isPayed = false;
        $date = new DateTime();
        $this->hash = hash('md5', $date->format('d.m.Y # H:i'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGsName(): ?string
    {
        return $this->gs_name;
    }

    public function setGsName(string $gs_name): self
    {
        $this->gs_name = $gs_name;

        return $this;
    }

    public function getGsBetrag(): ?int
    {
        return $this->gs_betrag;
    }

    public function setGsBetrag(int $gs_betrag): self
    {
        $this->gs_betrag = $gs_betrag;

        return $this;
    }

    public function getGsBemerkung(): ?string
    {
        return $this->gs_bemerkung;
    }

    public function setGsBemerkung(?string $gs_bemerkung): self
    {
        $this->gs_bemerkung = $gs_bemerkung;

        return $this;
    }

    public function getGsDate(): ?DateTimeInterface
    {
        return $this->gs_date;
    }

    public function setGsDate(DateTimeInterface $gs_date): self
    {
        $this->gs_date = $gs_date;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getIsPayed(): ?bool
    {
        return $this->isPayed;
    }

    public function setIsPayed(bool $isPayed): self
    {
        $this->isPayed = $isPayed;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getGsNummer(): ?string
    {
        return $this->gs_nummer;
    }

    public function setGsNummer(string $gs_nummer): self
    {
        $this->gs_nummer = $gs_nummer;

        return $this;
    }

    public function getKurstyp(): ?int
    {
        return $this->kurstyp;
    }

    public function getKursTypName(): string
    {
        switch ($this->kurstyp){
            case 1:
                return 'Betrag';
            case 2:
                return 'Kitesurf-Grundkurs';
            case 3:
                return 'Kitesurf-Schnupperkurs';
            case 4:
                return 'Kitesurf-Aufsteigerkurs';
            case 6:
                return 'Wingsurf-Aufsteigerkurs';
            case 5:
                return 'Wingsurf-Grundkurs';
            case 7:
                return '3h Kitetraining';
            default:
                return 'null';
        }
    }

    public function setKurstyp(int $kurstyp): self
    {
        $this->kurstyp = $kurstyp;

        return $this;
    }

    public function getUrlString(): string
    {
        switch ($this->getKurstyp()){
            case 2:
            case 3:
            case 4:
                return 'www.ilovekitesurf-sylt.com';
            case 5:
            case 6:
                return 'www.ilovewingsurf-sylt.com';
        }

        return '';
    }
}
