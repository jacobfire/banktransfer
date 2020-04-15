<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\Bank\Model;

use Magento\Framework\Model\AbstractModel;
use Hello\Bank\Api\Data\BankInterface;

/**
 * Class Bank
 *
 * @package Hello\Bank\Model
 */
class Bank extends AbstractModel implements BankInterface
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Hello\Bank\Model\ResourceModel\Bank');
    }

    /**
     * {@inheritdoc}
     */
    public function setHelloAccountNumber($accountNumber)
    {
        $this->setData(self::HELLO_ACCOUNT_NUMBER, $accountNumber);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHelloAccountNumber()
    {
        return $this->getData(self::HELLO_ACCOUNT_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setHelloAccountName($accountName)
    {
        $this->setData(self::HELLO_ACCOUNT_NAME, $accountName);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHelloAccountName()
    {
        return $this->getData(self::HELLO_ACCOUNT_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setBankName($bankName)
    {
        $this->setData(self::BANK_NAME, $bankName);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBankName()
    {
        return $this->getData(self::BANK_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setPayeeName($payeeName)
    {
        $this->setData(self::PAYEE_NAME, $payeeName);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPayeeName()
    {
        return $this->getData(self::PAYEE_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setStoreId($storeId)
    {
        $this->setData(self::STORE_ID, $storeId);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setIsActive($isActive)
    {
        $this->setData(self::IS_ACTIVE, $isActive);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt($createdDate)
    {
        $this->setData(self::CREATED_AT, $createdDate);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt($updatedDate)
    {
        $this->setData(self::UPDATED_AT, $updatedDate);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * {@inheritdoc}
     */
    public function getRowId()
    {
        return $this->getData(self::ROW_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setRowId($rowId)
    {
        $this->setData(self::ROW_ID, $rowId);
        return $this;
    }
}
