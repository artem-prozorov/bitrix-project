<?php

namespace App\Services\ImageSyncer\DataObjects;

use App\Traits\Validatable;

class RecordToProcess
{
    use Validatable;

    /**
     * @var		int	$elementId
     */
    protected int $elementId;

    /**
     * @var		string	$address
     */
    protected string $address;

    /**
     * @var		bool	$isMain
     */
    protected bool $isMain = false;

    /**
     * @var		bool	$isProcessed
     */
    protected bool $isProcessed = false;

    /**
     * @var		int	$currentImageValueId
     */
    protected ?int $currentImageValueId;

    /**
     * @var		array	$rules
     */
    protected $rules = [
        'element_id' => 'required|integer',
        'address' => 'required|string',
        'is_main' => 'required|boolean',
        'is_processed' => 'required|boolean',
        'current_image_value_id' => 'present|nullable|integer',
    ];

    public function __construct(array $data)
    {
        $this->validate($data);

        $this->elementId = (int) $data['element_id'];
        $this->address = $data['address'];
        $this->isMain = (bool) $data['is_main'];
        $this->isProcessed = (int) $data['is_processed'];
        $this->currentImageValueId = (bool) $data['current_image_value_id'];
    }

    /**
     * Get the value of elementId
     */ 
    public function getElementId(): int
    {
        return $this->elementId;
    }

    /**
     * Get the value of address
     */ 
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * Get the value of isMain
     */ 
    public function isMain(): bool
    {
        return $this->isMain;
    }

    /**
     * Get the value of isProcessed
     */ 
    public function isProcessed(): bool
    {
        return $this->isProcessed;
    }

    /**
     * Get the value of currentImageValueId
     */ 
    public function getCurrentImageValueId(): ?int
    {
        return $this->currentImageValueId;
    }
}
