<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\BankTransfer\Model;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Hello\BankTransfer\Model\Reports\FinanceReport;
use Hello\BankTransfer\Model\TransportBuilder as BankTransferTransportBuilder;

/**
 * Class Mailer
 *
 * @package Hello\BankTransfer\Model
 */
class Mailer
{
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    private $inlineTranslation;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    private $transportBuilder;

    /**
     * Mailer constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param StateInterface $inlineTranslation
     * @param BankTransferTransportBuilder $transportBuilder
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        StateInterface $inlineTranslation,
        BankTransferTransportBuilder $transportBuilder
    ) {
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
    }

    /**
     * Prepare template and send an email
     *
     * @param array $senderInfo
     * @param array $recieverInfo
     * @param int $templateId
     * @param string $attachment
     */
    public function sendMail($senderInfo, $recieverInfo, $templateId, $attachment)
    {
        $this->inlineTranslation->suspend();
        $this->generateTemplate($senderInfo, $recieverInfo, $templateId, $attachment);
        $transport = $this->transportBuilder->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }

    /**
     * Generate template for an email
     *
     * @param array $senderInfo
     * @param array $recieverInfo
     * @param int $templateId
     * @param string $attachment
     * @return $this
     */
    public function generateTemplate($senderInfo, $recieverInfo, $templateId, $attachment)
    {
        $emailTemplateVariables = [
            "store" => $this->storeManager->getStore(),
            "customer_name" => $recieverInfo['name'],
        ];
        $this->transportBuilder->setTemplateIdentifier($templateId)
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $this->storeManager->getStore()->getId(),
                ]
            )
            ->setTemplateVars($emailTemplateVariables)
            ->setFrom(['name' => $senderInfo['name'], 'email' => $senderInfo['email']])
            ->addTo($recieverInfo['email'], $recieverInfo['name']);

        if (null !== $attachment) {
            $this->transportBuilder->addAttachment(
                $attachment,
                $mimeType    = \Zend_Mime::TYPE_OCTETSTREAM,
                $disposition = \Zend_Mime::DISPOSITION_ATTACHMENT,
                $encoding    = \Zend_Mime::ENCODING_BASE64,
                FinanceReport::FINANCE_REPORT_FILENAME
            );
        }

        return $this;
    }
}
