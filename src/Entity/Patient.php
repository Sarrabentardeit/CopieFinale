<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient extends User
{
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dob = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Appointment::class)]
    private Collection $appointments;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: MedicalRecord::class)]
    private Collection $medicalRecords;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Billing::class)]
    private Collection $billings;

    public function __construct()
    {
        $this->appointments = new ArrayCollection();
        $this->medicalRecords = new ArrayCollection();
        $this->billings = new ArrayCollection();
    }

    public function getDob(): ?\DateTimeInterface
    {
        return $this->dob;
    }

    public function setDob(?\DateTimeInterface $dob): self
    {
        $this->dob = $dob;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): static
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments->add($appointment);
            $appointment->setPatient($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): static
    {
        if ($this->appointments->removeElement($appointment)) {
            if ($appointment->getPatient() === $this) {
                $appointment->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MedicalRecord>
     */
    public function getMedicalRecords(): Collection
    {
        return $this->medicalRecords;
    }

    public function addMedicalRecord(MedicalRecord $medicalRecord): static
    {
        if (!$this->medicalRecords->contains($medicalRecord)) {
            $this->medicalRecords->add($medicalRecord);
            $medicalRecord->setPatient($this);
        }

        return $this;
    }

    public function removeMedicalRecord(MedicalRecord $medicalRecord): static
    {
        if ($this->medicalRecords->removeElement($medicalRecord)) {
            if ($medicalRecord->getPatient() === $this) {
                $medicalRecord->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Billing>
     */
    public function getBillings(): Collection
    {
        return $this->billings;
    }

    public function addBilling(Billing $billing): static
    {
        if (!$this->billings->contains($billing)) {
            $this->billings->add($billing);
            $billing->setPatient($this);
        }

        return $this;
    }

    public function removeBilling(Billing $billing): static
    {
        if ($this->billings->removeElement($billing)) {
            if ($billing->getPatient() === $this) {
                $billing->setPatient(null);
            }
        }

        return $this;
    }
}

