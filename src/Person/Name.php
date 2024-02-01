<?php

namespace Blog\Defox\Person;

class Name
{
    public function __construct(
        private string $firstName,
        private string $lastName,
        private string $patronymic,
    )
    {
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getPatronymic(): string
    {
        return $this->patronymic;
    }

    public function setPatronymic(string $patronymic): void
    {
        $this->patronymic = $patronymic;
    }

    public function __toString(): string
    {
        return $this->firstName . ' ' . $this->lastName . ' ' . $this->patronymic;
    }
}