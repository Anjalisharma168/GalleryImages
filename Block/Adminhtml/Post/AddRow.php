<?php 
 /**
     * Retrieve text for header element depending on loaded image.
     *
     * @return \Magento\Framework\Phrase
     */
namespace Dotsquares\Galleryimages\Block\Adminhtml\Post;

class AddRow extends \Magento\Backend\Block\Widget\Form\Container
{
    protected $_coreRegistry = null;
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ){
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }
	
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'dotsquares_galleryimages';
        $this->_controller = 'adminhtml_post';
        parent::_construct();
        if ($this->_isAllowedAction('dotsquares_galleryimages::add_row')) {
        $this->buttonList->update('save', 'label', __('Save'));
        $this->buttonList->add(
        'save-and-continue',
                [
                    'label' => __('Save and Continue'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => [
                                'event' => 'saveAndContinueEdit',
                                'target' => '#add_row'
                            ]
                        ]
                    ]
                ],
                -100
            );
        
        } else {
            $this->buttonList->remove('save');
        }
        $this->buttonList->remove('reset');

    }
    public function getHeaderText()
    {
        return __('Add Row Data');
    }

    /**
     * Check permission for passed action.
     *
     * @param string $resourceId
     *
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Get form action URL.
     *
     * @return string
     */
    public function getFormActionUrl()
    {
        if ($this->hasFormActionUrl()) {
            return $this->getData('form_action_url');
        }

        return $this->getUrl('*/*/save');
    }
}