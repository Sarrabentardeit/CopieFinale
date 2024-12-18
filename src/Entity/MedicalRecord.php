<?php

namespace App\Entity;

use App\Repository\MedicalRecordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedicalRecordRepository::class)]
class MedicalRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $diagnosis = null;

    #[ORM\Column(length: 255)]
    private ?string $treatmentPlan = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createdDate = null;

    #[ORM\ManyToOne(inversedBy: 'medicalRecords')]
    private ?Patient $patient = null;

    /**
     * @var Collection<int, Prescription>
     */
    #[ORM\OneToMany(targetEntity: Prescription::class, mappedBy: 'medicalRecord')]
    private Collection $prescriptions;

    #[ORM\OneToOne(mappedBy: 'medicalRecord', cascade: ['persist', 'remove'])]
    private ?Appointment $appointment = null;

    public function __construct()
    {
        $this->prescriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiagnosis(): ?string
    {
        return $this->diagnosis;
    }

    public function setDiagnosis(string $diagnosis): static
    {
        $this->diagnosis = $diagnosis;

        return $this;
    }

    public function getTreatmentPlan(): ?string
    {
        return $this->treatmentPlan;
    }

    public function setTreatmentPlan(string $treatmentPlan): static
    {
        $this->treatmentPlan = $treatmentPlan;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): static
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * @return Collection<int, Prescription>
     */
    public function getPrescriptions(): Collection
    {
        return $this->prescriptions;
    }

    public function addPrescription(Prescription $prescription): static
    {
        if (!$this->prescriptions->contains($prescription)) {
            $this->prescriptions->add($prescription);
            $prescription->setMedicalRecord($this);
        }

        return $this;
    }

    public function removePrescription(Prescription $prescription): static
    {
        if ($this->prescriptions->removeElement($prescription)) {
            // set the owning side to null (unless already changed)
            if ($prescription->getMedicalRecord() === $this) {
                $prescription->setMedicalRecord(null);
            }
        }

        return $this;
    }

    public function getAppointment(): ?Appointment
    {
        return $this->appointment;
    }

    public function setAppointment(?Appointment $appointment): static
    {
        // unset the owning side of the relation if necessary
        if ($appointment === null && $this->appointment !== null) {
            $this->appointment->setMedicalRecord(null);
        }

        // set the owning side of the relation if necessary
        if ($appointment !== null && $appointment->getMedicalRecord() !== $this) {
            $appointment->setMedicalRecord($this);
        }

        $this->appointment = $appointment;

        return $this;
    }
}
