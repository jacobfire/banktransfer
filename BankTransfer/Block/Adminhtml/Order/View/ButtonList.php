<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\BankTransfer\Block\Adminhtml\Order\View;

use Magento\Backend\Block\Widget\Button\ItemFactory;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\RequestInterface as Request;
use Magento\Sales\Model\OrderRepository;
use Hello\BankTransfer\Services\ConfigurationService;

/**
 * Class ButtonList
 *
 * @package Hello\BankTransfer\Block\Adminhtml\Order\View
 */
class ButtonList extends \Magento\Backend\Block\Widget\Button\ButtonList
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * ButtonList constructor.
     *
     * @param ItemFactory $itemFactory
     * @param UrlInterface $urlBuilder
     * @param Request $request
     * @param OrderRepository $orderRepository
     */
    public function __construct(
        ItemFactory $itemFactory,
        UrlInterface $urlBuilder,
        Request $request,
        OrderRepository $orderRepository
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->request = $request;
        $this->orderRepository = $orderRepository;
        parent::__construct($itemFactory);
    }

    /**
     * Add a new button for adding of a transaction
     *
     * @return array
     */
    public function getItems()
    {
        $order = null;
        if ($this->request->getParam('order_id')) {
            $order = $this->orderRepository->get($this->request->getParam('order_id'));
        }

        if (is_object($order->getPayment())
            && $order->getPayment()->getMethod() === ConfigurationService::METHOD_CODE) {
            $path = $this->urlBuilder->getUrl(
                'banktransfer/transaction/add',
                ['order_id' => $this->request->getParam('order_id')]
            );

            $this->add('transaction_button', [
                'label' => __('Add Transaction ID'),
                'onclick' => "setLocation('{$path}')",
                'class' => 'ship'
            ]);
        }
        return parent::getItems();
    }
}
