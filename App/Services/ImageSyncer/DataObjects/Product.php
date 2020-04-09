<?php

namespace App\Services\ImageSyncer\DataObjects;

use App\Traits\Validatable;

class Product
{
    use Validatable;

    public const LINKS_TO_IMAGES_CODE = 'PROPERTY_LINKS_TO_IMAGES_VALUE';

    public const MORE_PHOTO_CODE = 'PROPERTY_MORE_PHOTO_VALUE';

    public const DETAIL_PICTURE = 'DETAIL_PICTURE';

    /**
     * @var		int	$id
     */
    protected $id;

    /**
     * @var		array	$linksToImages
     */
    protected $linksToImages = [];

    /**
     * @var		int|null	$detailPictureId
     */
    protected $detailPictureId;

    /**
     * @var		array	$morePhotos
     */
    protected $morePhotos = [];

    public function __construct(array $data)
    {
        $this->rules = [
            'ID' => 'required|numeric',
            static::DETAIL_PICTURE => 'present|nullable|numeric',
            static::LINKS_TO_IMAGES_CODE => 'present|nullable|array',
            static::LINKS_TO_IMAGES_CODE . '.*' => 'nullable|string',
            static::MORE_PHOTO_CODE => 'present|nullable|array',
            static::MORE_PHOTO_CODE . '.*' => 'nullable|string',
        ];

        $this->validate($data);

        $this->id = (int) $data['ID'];
        $this->detailPictureId = $data[static::DETAIL_PICTURE] ? (int) $data[static::DETAIL_PICTURE] : null;
        $this->linksToImages = $data[static::LINKS_TO_IMAGES_CODE] ?? [];
        $this->morePhotos = $data[static::MORE_PHOTO_CODE] ?? [];
    }


    /**
     * Get the value of id
     */ 
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the value of linksToImages
     */ 
    public function getLinksToImages(): array
    {
        return $this->linksToImages;
    }

    /**
     * Get the value of detailPictureId
     */ 
    public function getDetailPictureId(): ?int
    {
        return $this->detailPictureId;
    }

    /**
     * Get the value of morePhotos
     */ 
    public function getMorePhotos(): array
    {
        return $this->morePhotos;
    }
}
