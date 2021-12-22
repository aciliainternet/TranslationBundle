<?php

namespace Acilia\Bundle\TranslationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="translation_attribute", uniqueConstraints={@ORM\UniqueConstraint(name="unq_translation_attribute", columns={"attrib_node", "attrib_name"})}, options={"collate"="utf8_unicode_ci", "charset"="utf8", "engine"="InnoDB"})
 */
class TranslationAttribute
{
    /**
     * @ORM\Column(name="attrib_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="TranslationNode")
     * @ORM\JoinColumn(name="attrib_node", referencedColumnName="node_id", nullable=false)
     */
    protected $node;

    /**
     * @ORM\Column(name="attrib_name", type="string", length=64)
     */
    protected $name;

    /**
     * @ORM\Column(name="attrib_original", type="text")
     */
    protected $original;

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setOriginal(string $original): self
    {
        $this->original = $original;

        return $this;
    }

    public function getOriginal(): string
    {
        return $this->original;
    }

    public function setNode(?TranslationNode $node = null): self
    {
        $this->node = $node;

        return $this;
    }

    public function getNode(): TranslationNode
    {
        return $this->node;
    }
}
