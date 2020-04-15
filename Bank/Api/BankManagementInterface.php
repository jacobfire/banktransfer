<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\Bank\Api;

/**
 * Interface BankManagementInterface
 *
 * @package Hello\Bank\Api
 */
interface BankManagementInterface
{
    /**
     * Retrieve requisites available for current store
     *
     * @return \Hello\Bank\Api\Data\BankInterface[]
     */
    public function getDetails();

    /**
     * Retrieve requisites
     *
     * @param int $storeId
     * @return string
     */
    public function prepareInstructions($storeId);
}
