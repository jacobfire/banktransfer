<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\Bank\Ui\DataProvider;

use Hello\Bank\Model\ResourceModel\Bank\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;

/**
 * Class EditForm
 *
 * @package Hello\Bank\Ui\DataProvider
 */
class EditForm extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var array
     */
    private $loadedData;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * EditForm constructor.
     *
     * @param RequestInterface $request
     * @param string $name
     * @param string $primaryFieldName
     * @param array $requestFieldName
     * @param CollectionFactory $bankCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        RequestInterface $request,
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $bankCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collectionFactory = $bankCollectionFactory;
        $this->dataPersistor = $dataPersistor;
        $this->request = $request;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $bank = $this->getCurrentItem();
        if ($bank) {
            $this->loadedData[$bank->getId()] = $bank->getData();
            $data = $this->dataPersistor->get('bank');
            if (!empty($data)) {
                $bank = $this->collection->getNewEmptyItem();
                $bank->setData($data);
                $this->loadedData[$bank->getId()] = $bank->getData();
                $this->dataPersistor->clear('bank');
            }
        }

        return $this->loadedData;
    }

    /**
     * Retrieve current item for editing
     *
     * @return \Hello\Bank\Model\Bank|null|\Magento\Framework\DataObject
     */
    public function getCurrentItem()
    {
        $rowId = $this->request->getParam('row_id');
        if ($rowId) {
            return $this->getCollection()->addFieldToFilter('row_id', ['eq' => $rowId])->getFirstItem();
        }
        return null;
    }

    /**
     * Retrieve actual collection
     *
     * @return \Hello\Bank\Model\ResourceModel\Bank\Collection
     */
    public function getCollection()
    {
        if (null === $this->collection) {
            $this->collection = $this->collectionFactory->create();
        }
        return $this->collection;
    }
}
