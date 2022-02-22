<?php
namespace Dotsquares\Galleryimages\Model\ResourceModel\Post;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'id';
	protected $_eventPrefix = 'dotsquares_galleryimages_post_collection';
	protected $_eventObject = 'post_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Dotsquares\Galleryimages\Model\Post', 'Dotsquares\Galleryimages\Model\ResourceModel\Post');
	}

}

