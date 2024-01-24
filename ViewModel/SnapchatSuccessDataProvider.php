<?php
declare(strict_types=1);

namespace Kadoco\Snapchat\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class SnapchatSuccessDataProvider implements ArgumentInterface
{
    private \Magento\Checkout\Model\Session $success;

    public function __construct(
        \Magento\Checkout\Model\Session $success
    ) {
        $this->success = $success;
    }

    public function getPurchaseData()
    {
        $order = $this->success->getLastRealOrder();
        if (!$order) {
            return false;
        }

        $items = $this->getItemIds($order->getAllVisibleItems());

        $order = [
            'currency'                  => $order->getOrderCurrencyCode(),
            'price'                     => $order->getGrandTotal(),
            'transaction_id'            => $order->getIncrementId(),
            'client_deduplication_id'   => $order->getIncrementId(),
            'item_ids'                  => $items
        ];

        return json_encode($order);
    }

    public function getUserEmail()
    {
        $order = $this->success->getLastRealOrder();
        if (!$order) {
            return false;
        }

        return $order->getCustomerEmail();
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderItemInterface[] $items
     */
    private function getItemIds(array $items)
    {
        $result = [];
        foreach ($items as $item) {
            $result[] = $item->getSku();
        }

        return $result;
    }
}
