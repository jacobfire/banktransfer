<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\Bank\Api\Data;

/**
 * Interface BankInterface
 *
 * @package Hello\Bank\Api\Data
 */
interface BankInterface
{
    /**
     * Account number
     */
    const HELLO_ACCOUNT_NUMBER = 'hello_account_number';

    /**
     * Account name
     */
    const HELLO_ACCOUNT_NAME = 'hello_account_name';

    /**
     * Row ID
     */
    const ROW_ID = 'row_id';

    /**
     * Bank name
     */
    const BANK_NAME = 'bank_name';

    /**
     * Payee name
     */
    const PAYEE_NAME = 'payee_name';

    /**
     * Store ID
     */
    const STORE_ID = 'store_id';

    /**
     * Flag if enabled
     */
    const IS_ACTIVE = 'is_active';

    /**
     * Creation time
     */
    const CREATED_AT = 'created_at';

    /**
     * Time of update
     */
    const UPDATED_AT = 'updated_at';

    /**
     * Set Account Number
     *
     * @param string $accountNumber
     * @return $this
     */
    public function setHelloAccountNumber($accountNumber);

    /**
     * Retrieve account number
     *
     * @return string
     */
    public function getHelloAccountNumber();

    /**
     * @param string $accountName
     * @return $this
     */
    public function setHelloAccountName($accountName);

    /**
     * Retrieve account name
     *
     * @return string
     */
    public function getHelloAccountName();

    /**
     * Set bank name
     *
     * @param string $bankName
     * @return $this
     */
    public function setBankName($bankName);

    /**
     * Retrieve bank name
     *
     * @return string
     */
    public function getBankName();

    /**
     * Set payee name
     *
     * @param string $payeeName
     * @return $this
     */
    public function setPayeeName($payeeName);

    /**
     * Retrieve payee name
     *
     * @return string
     */
    public function getPayeeName();

    /**
     * Set store ID
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId);

    /**
     * Retrieve store ID
     *
     * @return int
     */
    public function getStoreId();

    /**
     * Set flag of enable
     *
     * @param int $isActive
     * @return mixed
     */
    public function setIsActive($isActive);

    /**
     * Retrieve flag of enabled
     *
     * @return int
     */
    public function getIsActive();

    /**
     * Set Created date
     *
     * @param string $createdDate
     * @return $this
     */
    public function setCreatedAt($createdDate);

    /**
     * Retrive created date
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Set Updated date
     *
     * @param string $updatedDate
     * @return $this
     */
    public function setUpdatedAt($updatedDate);

    /**
     * Retrive updated date
     *
     * @return string
     */
    public function getUpdatedAt();

    /**
     * Retrieve row ID
     *
     * @return int
     */
    public function getRowId();

    /**
     * Set row ID
     *
     * @param int $rowId
     * @return $this
     */
    public function setRowId($rowId);
}
