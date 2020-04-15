<?php
/**
 * Copyright © 2018 Hello, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Hello\BankTransfer\Model;

use Magento\Framework\Mail\Template\TransportBuilder as CoreTransportBuilder;

/**
 * Class TransportBuilder
 *
 * @package Hello\BankTransfer\Magento\Mail\Template
 */
class TransportBuilder extends CoreTransportBuilder
{
    /**
     * Create custom attachment
     *
     * @param string $body
     * @param string $mimeType
     * @param string $disposition
     * @param string $encoding
     * @param string $filename
     * @return $this
     */
    public function addAttachment(
        $body,
        $mimeType = \Zend_Mime::TYPE_OCTETSTREAM,
        $disposition = \Zend_Mime::DISPOSITION_ATTACHMENT,
        $encoding = \Zend_Mime::ENCODING_BASE64,
        $filename = null
    ) {
        $this->message->createAttachment($body, $mimeType, $disposition, $encoding, $filename);
        return $this;
    }
}
