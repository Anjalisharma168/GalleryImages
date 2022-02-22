<?php
namespace Dotsquares\Galleryimages\Block\Adminhtml\Post\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry             $registry
     * @param \Magento\Framework\Data\FormFactory     $formFactory
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    ) 
    {
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $model = $this->_coreRegistry->registry('row_data');
		$form = $this->_formFactory->create(
            ['data' => [
                    'id' => 'edit_form', 
                    'enctype' => 'multipart/form-data', 
                    'action' => $this->getData('action'), 
                    'method' => 'post'
                ]
            ]
        );
        if ($model->getId()) 
        {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Edit Row Data'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        } else 
        {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Add Image Details'), 'class' => 'fieldset-wide']
            );
        }
		$fieldset->addField
        (
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Title'),
                'id' => 'title',
                'title' => __('Title'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

             $fieldset->addField
        (
            'description',
            'text',
            [
                'name' => 'description',
                'label' => __('Description'),
                'id' => 'title',
                'title' => __('Description'),
                'required' => true,
            ]
        );

            $fieldset->addField
        (
            'short_description',
            'text',
            [
                'name' => 'short_description',
                'label' => __('Short Description'),
                'id' => 'title',
                'title' => __('Short Description'),
                'required' => true,
            ]
        );  
           $fieldset->addField
        (
            'url',
            'text',
            [
                'name' => 'url',
                'label' => __('URL'),
                'id' => 'title',
                'title' => __('URL'),
                'required' => true,
            ]
        ); 
    $fieldset->addField
    (
        'thumbnail_image',
        'image',
           [
            'title' => __('Thumbnail Image'),
            'label' => __('Thumbnail Image'),
            'name' => 'thumbnail_image',
            'class' => '\Magento\Ui\Component\Form\Element\AbstractElement',
            'note' => 'Allow image type: jpg, jpeg, gif, png',
           ]
    );  
    $fieldset->addField(
        'image',
        'image',
           [
            'title' => __('Image'),
            'label' => __('Image'),
            'name' => 'image',
            'class' => '\Magento\Ui\Component\Form\Element\AbstractElement',
            'note' => 'Allow image type: jpg, jpeg, gif, png',
           ]
        );           
    
        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}