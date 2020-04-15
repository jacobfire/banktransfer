<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\Bank\Model;

use Magento\Store\Model\StoreManagerInterface;
use Hello\Bank\Model\ResourceModel\Bank\Collection as BankCollection;

/**
 * Class BankManagement
 *
 * @package Hello\Bank\Model
 */
class BankManagement implements \Hello\Bank\Api\BankManagementInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var BankCollection
     */
    private $bankCollection;

    /**
     * BankManagement constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param BankCollection $bankCollection
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        BankCollection $bankCollection
    ) {
        $this->storeManager = $storeManager;
        $this->bankCollection = $bankCollection;
    }

    /**
     * Retrieve bank requisites for store
     *
     * @return \Hello\Bank\Api\Data\BankInterface[]
     */
    public function getDetails()
    {
        $store = $this->storeManager->getStore();
        $data = $this->bankCollection->collectRequisites($store->getId());

        return ['details' => $data];
    }

    /**
     * Retrieve requisites
     *
     * @param int $storeId
     * @return string
     */
    public function prepareInstructions($storeId)
    {
        $requisites = $this->bankCollection->collectRequisites($storeId);
        $result = '';
        $counter = 1;

        foreach ($requisites as $requisite) {
            $result .= sprintf(__('Requisite #%d: '), $counter);
            $result .= sprintf(__('Bank Account Number %s,'), $requisite['account_number']);
            $result .= sprintf(__('Bank Account Name %s,'), $requisite['account_name']);
            $result .= sprintf(__('Payee Name %s,'), $requisite['payee_name']);
            $result .= sprintf(__('Bank Name %s.'), $requisite['bank_name']);
            $result .= ' ';

            $counter++;
        }
        return $result;
    }
}
