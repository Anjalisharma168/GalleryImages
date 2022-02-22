<?php
namespace Dotsquares\Galleryimages\Ui\Listing\Columns;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Escaper;

class Edit extends Column
 {
  	const URL_PATH_EDIT = 'dotsquares_galleryimages/post/addrow';
   protected $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
      }
        public function prepareDataSource(array $dataSource)
        {
        	 if (isset($dataSource['data']['items']))
        	 {
        	 	foreach ($dataSource['data']['items'] as & $item)
    	 		   {
    	 		   	$item[$this->getData('name')] = [

                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                [
                                    'block_id' => $item['id'],
                                ]
                            ),
                            'label' => __('Edit'),
                        ],


    	 		   	];
    	 		   }
        	 }
        	return $dataSource;
        }
 } 
 