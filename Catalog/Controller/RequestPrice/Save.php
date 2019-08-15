<?php
/**
 * Smile catalog request price save
 */
namespace Smile\Catalog\Controller\RequestPrice;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Smile\Customer\Model\PriceRequestFactory;
use Smile\Customer\Api\PriceRequestRepositoryInterface;

/**
 * Class Save
 *
 * @package Smile\Catalog\Controller\RequestPrice
 */
class Save extends Action
{
    /**
     * @var PriceRequestFactory
     */
    protected $priceRequestFactory;

    /**
     * @var PriceRequestRepositoryInterface
     */
    protected $priceRequestRepository;

    /**
     * Save constructor
     *
     * @param Context $context
     * @param PriceRequestFactory $priceRequestFactory
     * @param PriceRequestRepositoryInterface $priceRequestRepository
     */
    public function __construct(
        Context $context,
        PriceRequestFactory $priceRequestFactory,
        PriceRequestRepositoryInterface $priceRequestRepository
    ) {
        $this->priceRequestFactory = $priceRequestFactory;
        $this->priceRequestRepository = $priceRequestRepository;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return void
     *
     * @throws \Exception
     */
    public function execute()
    {
        try{
            if ($this->getRequest()->isAjax()) {
                $data = $this->getRequest()->getParams();
                $model = $this->priceRequestFactory->create();
                $model->setData($data);
                $this->priceRequestRepository->save($model);
                $this->messageManager->addSuccessMessage(__('Your request price is saved.'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }
    }
}
