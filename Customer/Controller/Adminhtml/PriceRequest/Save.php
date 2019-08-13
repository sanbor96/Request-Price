<?php
/**
 * Smile customer price request save
 */
namespace Smile\Customer\Controller\Adminhtml\PriceRequest;

use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Smile\Customer\Api\PriceRequestRepositoryInterface;
use Smile\Customer\Model\PriceRequestFactory;
use Smile\Customer\Model\PriceRequest;

/**
 * Class Index
 *
 * @package Smile\Customer\Controller\Adminhtml\PriceRequest
 */
class Save extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Smile_Customer::price_request_save';

    /**
     * Data persistor interface
     *
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * Price request repository interface
     *
     * @var PriceRequestRepositoryInterface
     */
    private $priceRequestRepository;

    /**
     * Price request factory
     *
     * @var PriceRequestFactory
     */
    private $priceRequestFactory;

    /**
     * Save constructor
     *
     * @param Action\Context          $context
     * @param DataPersistorInterface  $dataPersistor
     * @param PriceRequestRepositoryInterface $priceRequestRepository
     * @param PriceRequestFactory             $priceRequestFactory
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        PriceRequestRepositoryInterface $priceRequestRepository,
        PriceRequestFactory $priceRequestFactory
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->priceRequestRepository = $priceRequestRepository;
        $this->priceRequestFactory = $priceRequestFactory;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $priceRequestObject = new DataObject();
            $priceRequestObject->setData($data);

            $id = $this->getRequest()->getParam('id');

            try {
                if (!$id) {
                    $data['id']= null;
                    $model = $this->priceRequestFactory->create();
                } else {
                    $model = $this->priceRequestRepository->getById($id);
                }

                $model->setData($data);
                if($data['answer'])
                    $model->setStatus(PriceRequest::STATUS_CLOSED);
                $this->priceRequestRepository->save($model);
                $this->messageManager->addSuccessMessage(__('Price request is saved.'));
                $this->dataPersistor->clear('smile_customer_price_request');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the price request.'));
            }

            $this->dataPersistor->set('smile_customer_price_request', $data);

            return $resultRedirect->setPath(
                '*/*/edit',
                ['id' => $this->getRequest()->getParam('id')]
            );
        }

        return $resultRedirect->setPath('*/*/');
    }
}
