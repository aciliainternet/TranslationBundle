<?php

namespace Acilia\Bundle\TranslationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="translation_value", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="unq_translation_value", columns={"value_resource", "value_attribute"})}
 * )
 * @ORM\HasLifecycleCallbacks()
 */

class TranslationValue
{
    /**
     * @ORM\Column(name="value_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="TranslationAttribute")
     * @ORM\JoinColumn(name="value_attribute", referencedColumnName="attrib_id", nullable=false)
     */
    protected $attribute;

    /**
     * @ORM\Column(name="value_resource", type="string", length=16)
     */
    protected $resource;

    /**
     * @ORM\Column(name="value_translation", type="text")
     */
    protected $translation;

    /**
     * @ORM\Column(name="value_created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="value_modified_at", type="datetime", nullable=false)
     */
    protected $modifiedAt;

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTime();
        $this->modifiedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setModifiedAtValue(): void
    {
        $this->modifiedAt = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setTranslation(string $translation): self
    {
        $this->translation = $translation;

        return $this;
    }

    public function getTranslation(): string
    {
        return $this->translation;
    }

    public function setResource(string $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function setAttribute(?TranslationAttribute $attribute = null): self
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getAttribute(): TranslationAttribute
    {
        return $this->attribute;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setModifiedAt(\DateTimeInterface $modifiedAt): self
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function getModifiedAt(): \DateTimeInterface
    {
        return $this->modifiedAt;
    }
}
