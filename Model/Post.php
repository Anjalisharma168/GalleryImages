<?php
namespace Dotsquares\Galleryimages\Model;
class Post extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'dotsquares_galleryimages_post';

	protected $_cacheTag = 'dotsquares_galleryimages_post';

	protected $_eventPrefix = 'dotsquares_galleryimages_post';

	protected function _construct()
	{
		$this->_init('Dotsquares\Galleryimages\Model\ResourceModel\Post');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];

		return $values;
	}
}
