<?php

declare(strict_types=1);

namespace BTCPayServer\Result;

class StorePaymentMethodCollection extends AbstractListResult
{
    /**
     * @return \BTCPayServer\Result\AbstractStorePaymentMethodResult[]
     */
    public function getPaymentMethods(): array
    {
        $r = [];
        foreach ($this->getData() as $paymentMethod => $paymentMethodData) {
            // Consistency: Flatten the array to be consistent with the specific
            // payment method endpoints.
            $paymentMethodData += $paymentMethodData['data'];
            unset($paymentMethodData['data']);

            if (strpos($paymentMethod, 'LightningNetwork') !== false) {
                // Consistency: Add back the cryptoCode missing on this endpoint
                // results until it is there.
                if (!isset($paymentMethodData['cryptoCode'])) {
                    $paymentMethodData['cryptoCode'] = str_replace('-LightningNetwork', '', $paymentMethod);
                }
                $r[] = new \BTCPayServer\Result\StorePaymentMethodLightningNetwork($paymentMethodData, $paymentMethod);
            } else {
                // Consistency: Add back the cryptoCode missing on this endpoint
                // results until it is there.
                if (!isset($paymentMethodData['cryptoCode'])) {
                    $paymentMethodData['cryptoCode'] = $paymentMethod;
                }
                $r[] = new \BTCPayServer\Result\StorePaymentMethodOnChain($paymentMethodData, $paymentMethod);
            }
        }
        return $r;
    }
}
