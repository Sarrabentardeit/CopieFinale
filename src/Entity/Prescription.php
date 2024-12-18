<?php

namespace App\Entity;

use App\Repository\PrescriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrescriptionRepository::class)]
class Prescription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $medication = null;

    #[ORM\Column(length: 255)]
    private ?string $dosage = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\ManyToOne(inversedBy: 'prescriptions')]
    private ?MedicalRecord $medicalRecord = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMedication(): ?string
    {
        return $this->medication;
    }

    public function setMedication(string $medication): static
    {
        $this->medication = $medication;

        return $this;
    }

    public function getDosage(): ?string
    {
        return $this->dosage;
    }

    public function setDosage(string $dosage): static
    {
        $this->dosage = $dosage;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getMedicalRecord(): ?MedicalRecord
    {
        return $this->medicalRecord;
    }

    public function setMedicalRecord(?MedicalRecord $medicalRecord): static
    {
        $this->medicalRecord = $medicalRecord;

        return $this;
    }
}
