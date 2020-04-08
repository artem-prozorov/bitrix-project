<?php

namespace App\Services\ImageSyncer\DataObjects;

class Product
{
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
        $this->validate($data);
    }

    /**
     * Проверяет валидность массива продукта
     *
     * @access	protected
     * @param	array	$data	
     * @return	void
     */
    protected function validate(array $data): void
    {
        if (! array_key_exists('ID', $data)) {
            throw new \InvalidArgumentException(
                'Массив $data должен содержать идентификатор'
            );
        }

        $this->id = (int) $data['ID'];

        if (! array_key_exists(static::LINKS_TO_IMAGES_CODE, $data)) {
            throw new \InvalidArgumentException(
                'Массив $data должен содержать поле ' . static::LINKS_TO_IMAGES_CODE
            );
        }

        $this->linksToImages = $data[static::LINKS_TO_IMAGES_CODE] ?? [];

        if (! array_key_exists(static::DETAIL_PICTURE, $data)) {
            throw new \InvalidArgumentException(
                'Массив $data должен содержать поле ' . static::DETAIL_PICTURE
            );
        }

        $this->detailPictureId = $data[static::DETAIL_PICTURE] ? (int) $data[static::DETAIL_PICTURE] : null;

        if (! array_key_exists(static::MORE_PHOTO_CODE, $data)) {
            throw new \InvalidArgumentException(
                'Массив $data должен содержать поле ' . static::MORE_PHOTO_CODE
            );
        }

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
