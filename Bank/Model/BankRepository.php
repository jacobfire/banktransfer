<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\Bank\Model;

use Hello\Bank\Api\BankRepositoryInterface;
use Hello\Bank\Api\Data\BankInterfaceFactory;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class BankRepository
 *
 * @package Hello\Bank\Model
 */
class BankRepository implements BankRepositoryInterface
{
    /**
     * @var BankInterfaceFactory
     */
    private $bankFactory;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * BankRepository constructor.
     *
     * @param BankInterfaceFactory $bankFactory
     * @param EntityManager $entityManager
     */
    public function __construct(
        BankInterfaceFactory $bankFactory,
        EntityManager $entityManager
    ) {
        $this->bankFactory = $bankFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * Retrieve model by ID
     *
     * @param int $id
     * @return \Hello\Bank\Api\Data\BankInterface
     * @throws NoSuchEntityException
     */
    public function getById($id)
    {
        $bank = $this->bankFactory->create();
        $this->entityManager->load($bank, $id);
        if (!$bank->getRowId()) {
            throw new NoSuchEntityException(__('Bank requisite with id "%1" does not exist.', $id));
        }
        return $bank;
    }

    /**
     * Save requisites
     *
     * @param \Hello\Bank\Api\Data\BankInterface $bank
     * @return \Hello\Bank\Api\Data\BankInterface
     * @throws CouldNotSaveException
     */
    public function save($bank)
    {
        try {
            $this->entityManager->save($bank);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $bank;
    }

    /**
     * Delete item from entity table
     *
     * @param \Hello\Bank\Api\Data\BankInterface $model
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete($model)
    {
        try {
            $this->entityManager->delete($model);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete by id
     *
     * @param int $id
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }
}
