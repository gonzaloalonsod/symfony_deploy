<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $depDirectory;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $deploymentTool;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $script;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepDirectory(): ?string
    {
        return $this->depDirectory;
    }

    public function setDepDirectory(?string $depDirectory): self
    {
        $this->depDirectory = $depDirectory;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDeploymentTool(): ?int
    {
        return $this->deploymentTool;
    }

    public function setDeploymentTool(?int $deploymentTool): self
    {
        $this->deploymentTool = $deploymentTool;

        return $this;
    }

    public function getScript(): ?string
    {
        return $this->script;
    }

    public function setScript(?string $script): self
    {
        $this->script = $script;

        return $this;
    }
}
