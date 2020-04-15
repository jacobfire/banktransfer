<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\Bank\Api;

/**
 * Interface BankRepositoryInterface
 *
 * @package Hello\Bank\Api
 * @api
 */
interface BankRepositoryInterface
{
    /**
     * Retrieve bank requisites by id
     *
     * @param int $id
     * @return \Hello\Bank\Api\Data\BankInterface
     */
    public function getById($id);

    /**
     * Save model to DB
     *
     * @param \Hello\Bank\Api\Data\BankInterface $bank
     * @return \Hello\Bank\Api\Data\BankInterface
     */
    public function save($bank);

    /**
     * Remove the bank transfer requisites
     *
     * @param \Hello\Bank\Api\Data\BankInterface $bank
     * @return bool
     */
    public function delete($bank);

    /**
     * Delete requisite by id
     *
     * @param int $id
     * @return bool
     */
    public function deleteById($id);
}
