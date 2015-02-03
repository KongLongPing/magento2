<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\App\Config\Helper;

/**
 * App config data helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Store cache context
     */
    const CONTEXT_STORE = 'store';

    /**#@+
     * Paths for various config settings
     */
    const XML_PATH_DEV_ALLOW_IPS = 'dev/restrict/allow_ips';
    /**#@- */

    /**
     * @param null $storeId
     * @return bool
     */
    public function isDevAllowed($storeId = null)
    {
        $allow = true;

        $allowedIps = $this->scopeConfig->getValue(
            self::XML_PATH_DEV_ALLOW_IPS,
            \Magento\Framework\Store\ScopeInterface::SCOPE_STORE,
            $storeId
        );
        $remoteAddr = $this->_remoteAddress->getRemoteAddress();
        if (!empty($allowedIps) && !empty($remoteAddr)) {
            $allowedIps = preg_split('#\s*,\s*#', $allowedIps, null, PREG_SPLIT_NO_EMPTY);
            if (array_search($remoteAddr, $allowedIps) === false
                && array_search($this->_httpHeader->getHttpHost(), $allowedIps) === false
            ) {
                $allow = false;
            }
        }

        return $allow;
    }
}
