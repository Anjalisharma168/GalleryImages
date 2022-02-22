<?php

namespace Dotsquares\Galleryimages\Controller\Adminhtml\Post;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Filesystem;

class Save extends \Magento\Backend\App\Action
{
    var $PostFactory;
    public function __construct
    (
        \Magento\Backend\App\Action\Context $context,
        \Dotsquares\Galleryimages\Model\PostFactory $gridFactory,
        \Magento\Framework\Filesystem $filesystem,
        AdapterFactory $adapterFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\Filesystem\Driver\File $file
    ) 
    {
        parent::__construct($context);
        $this->postFactory = $gridFactory;
         $this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->adapterFactory = $adapterFactory;
        $this->_file = $file;
    }

    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
        try {
            $model = $this->_objectManager->create('Dotsquares\Galleryimages\Model\Post');
            $data = $this->getRequest()->getPostValue();

            if(isset($_FILES['thumbnail_image']['name']) && $_FILES['thumbnail_image']['name'] != '') 
        {
            try{

                    $target = $this->_mediaDirectory->getAbsolutePath('mycustomfolder/');

                    $targetOne = "mycustomfolder/";
                    /** @var $fileUploaderFactory \Magento\MediaStorage\Model\File\Uploader */
                    $fileUploaderFactory = $this->_fileUploaderFactory->create(['fileId' => 'thumbnail_image']);
                    // print_r($fileUploaderFactory);
                    // die('Rrr');
                    /** Allowed extension types */
                    $fileUploaderFactory->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'zip', 'doc']);
                   
                    $imageAdapter = $this->adapterFactory->create();
                    // print_r($imageAdapter);
                    // die('Rrr');
                    $fileUploaderFactory->addValidateCallback('custom_image_upload',$imageAdapter,'validateUploadFile');
                    /** rename file name if already exists */
                    $fileUploaderFactory->setAllowRenameFiles(true);
                    /** upload file in folder "mycustomfolder" */
                    $result = $fileUploaderFactory->save($target);
                    // print_r($target);
                    //  die('Rrr');
                     if (!$result) 
                    {
                        throw new LocalizedException(
                            __('File cannot be saved to path: $1', $target)
                        );
                    }

                    $data['thumbnail_image'] = $targetOne.$result['file'];

                }
            catch (\Exception $e) {

            }
        }
       
        if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
        try{

            $target = $this->_mediaDirectory->getAbsolutePath('mycustomfolder/');

            $targetOne = "mycustomfolder/";
            /** @var $fileUploaderFactory \Magento\MediaStorage\Model\File\Uploader */
            $fileUploaderFactory = $this->_fileUploaderFactory->create(['fileId' => 'image']);
            /** Allowed extension types */
            $fileUploaderFactory->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'zip', 'doc']);

            $imageAdapter = $this->adapterFactory->create();
            $fileUploaderFactory->addValidateCallback('custom_image_upload',$imageAdapter,'validateUploadFile');
            /** rename file name if already exists */
            $fileUploaderFactory->setAllowRenameFiles(true);
            /** upload file in folder "mycustomfolder" */
            $result = $fileUploaderFactory->save($target);

            if (!$result) {
                throw new LocalizedException(
                    __('File cannot be saved to path: $1', $target)
                );
            }

            $data['image'] = $targetOne.$result['file'];

        }
            catch (\Exception $e) {
            }
        }
        

        if(isset($data['thumbnail_image']['delete']) && $data['thumbnail_image']['delete'] == 1) {
                    $_mediaDirectory = $this->filesystem->getDirectoryRead($this->directoryList::MEDIA)->
                    getAbsolutePath();
                    $file = $data['thumbnail_image']['value'];
                    $imgPath = $_mediaDirectory.$file;
                    if ($this->_file->isExists($imgPath))  {
                        $this->_file->deleteFile($imgPath);
                    }
                    $data['thumbnail_image'] = NULL;
                }
                if (isset($data['thumbnail_image']['value'])){
                    $data['thumbnail_image'] = $data['thumbnail_image']['value'];
                }
                if (isset($data['image']['value'])){
                    $data['image'] = $data['image']['value'];
                }
                $inputFilter = new \Zend_Filter_Input(
                    [],
                    [],
                    $data
                );
                $data = $inputFilter->getUnescaped();
                $id = $this->getRequest()->getParam('id');
                if ($id) {
                    $model->load($id);
                    if ($id != $model->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                }
                $model->setData($data);
                $session = $this->_objectManager->get('Magento\Backend\Model\Session');
                $session->setPageData($model->getData());
                $model->save();
                $this->messageManager->addSuccess(__('You saved the item.'));
                $session->setPageData(false);
                if ($this->getRequest()->getParam('back')) 
                {
                    $this->_redirect('dotsquares_galleryimages/post/index', ['id' => $model->getId()]);
                    return;
                }
                $this->_redirect('dotsquares_galleryimages/post/save');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $id = (int)$this->getRequest()->getParam('id');
                if (!empty($id)) {
                    $this->_redirect('dotsquares_galleryimages/post/index', ['id' => $id]);
                } else {
                    $this->_redirect('dotsquares_galleryimages/post/index');
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('Something went wrong while saving the item data. Please review the error log.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                $this->_redirect('dotsquares_galleryimages/post/index', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->_redirect('dotsquares_galleryimages/post/index');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('dotsquares_galleryimages::save');
    }
}