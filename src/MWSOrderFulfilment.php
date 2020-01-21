<?php

namespace Autumndev\MWS;

class MWSOrderFulfilment
{
    const DATE_FORMAT                           = "Y-m-d";
    const CARRIER_CODE_BLUE_PACKAGE             = 'Blue Package';
    const CARRIER_CODE_USPS                     = 'USPS';
    const CARRIER_CODE_UPS                      = 'UPS';
    const CARRIER_CODE_UPSMI                    = 'UPSMI';
    const CARRIER_CODE_FEDEX                    = 'FedEx';
    const CARRIER_CODE_DHL                      = 'DHL';
    const CARRIER_CODE_DHL_GLOBAL_MAIL          = 'DHL Global Mail';
    const CARRIER_CODE_FASTWAY                  = 'Fastway';
    const CARRIER_CODE_UPS_MAIL_INNOVATIONS     = 'UPS Mail Innovations';
    const CARRIER_CODE_LASERSHIP                = 'Lasership';
    const CARRIER_CODE_ROYAL_MAIL               = 'Royal Mail';
    const CARRIER_CODE_FEDEX_SMARTPOST          = 'FedEx SmartPost';
    const CARRIER_CODE_OSM                      = 'OSM';
    const CARRIER_CODE_ONTRAC                   = 'OnTrac';
    const CARRIER_CODE_STREAMLITE               = 'Streamlite';
    const CARRIER_CODE_NEWGISTICS               = 'Newgistics';
    const CARRIER_CODE_CANADA_POST              = 'Canada Post';
    const CARRIER_CODE_CITY_LINK                = 'City Link';
    const CARRIER_CODE_GLS                      = 'GLS';
    const CARRIER_CODE_GO                       = 'GO!';
    const CARRIER_CODE_HERMES_LOGISTIK_GRUPPE   = 'Hermes Logistik Gruppe';
    const CARRIER_CODE_PARCELFORCE              = 'Parcelforce';
    const CARRIER_CODE_TNT                      = 'TNT';
    const CARRIER_CODE_TARGET                   = 'Target';
    const CARRIER_CODE_SAGAWAEXPRESSS           = 'SagawaExpress';
    const CARRIER_CODE_NIPPONEXPRESS            = 'NipponExpress';
    const CARRIER_CODE_YAMATOTRANSPORT          = 'YamatoTransport';
    const CARRIER_CODE_OTHER                    = 'Other';

    public $orderId         = null;
    public $orderItemId     = null;
    public $quantity        = null;
    public $shipDate        = null;
    public $carrierCode     = null;
    public $carrierName     = null;
    public $trackingNumber  = null;
    public $shipMethod      = null;

    private $validation_errors = [];

    public function __construct(array $array = [])
    {
        foreach ($array as $property => $value) {
            $this->{$property} = $value;
        }
    }
    /**
     * returns an array of validation errors
     *
     * @return array
     */
    public function getValidationErrors(): array
    {
        return $this->validation_errors;
    }

    /**
     * converts the product object to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'order-id'          => $this->orderId,
            'order-item-id'     => $this->orderItemId,
            'quantity'          => $this->quantity,
            'ship-date'         => $this->shipDate,
            'carrier-code'      => $this->carrierCode,
            'carrier-name'      => $this->carrierName,
            'tracking-name'     => $this->trackingNumber,
            'ship-method'       => $this->shipMethod,
        ];
    }

    /**
     * validates a product
     *
     * @return bool
     */
    public function validate(): bool
    {
        // required fields
        preg_match('/^\d{3}-\d{7}-\d{7}$/', $this->orderId, $orderIdMatch);
        if (\count($orderIdMatch) < 1) {
            $this->validation_errors['order-id'] = 'Alphanumeric text with 17 numbers, formatted as ###-#######-#######.';
        }

        if (!is_int($this->quantity) && $this->quantity < 1) {
            $this->validation_errors['quantity'] = 'Must be a positive whole number.';
        }

        if (is_null($this->shipDate) || !is_a($this->shipDate, 'DateTime')) {
            $this->validation_errors['ship-date'] = 'ship-date should be a DateTime object';
        }

        $carrierCodes = $this->getCarrierCodes();
        if (!in_array($this->carrierCode, $carrierCodes)) {
            $this->validation_errors['carrier-code'] = 'Not a valid carrier code. Accepted values: ' . implode(', ', $carrierCodes);
        }

        if (null === $this->trackingNumber) {
            $this->validation_errors['tracking-number'] = "You must provide a tracking number";
        }

        if (null === $this->shipMethod) {
            $this->validation_errors['ship-method'] = "You must provide a shipping method";
        }

        // optional fields
        if (null !== $this->orderItemId) {
            preg_match('/^\d{14}$/', $this->orderItemId, $orderItemIdMatch);
            if (\count($orderIdMatch) < 1) {
                $this->validation_errors['order-item-id'] = 'A positive Integer of 14 numbers in length.';
            }
        }

        if (null !== $this->carrierName) {
            if (self::CARRIER_CODE_OTHER !== $this->carrierCode) {
                $this->validation_errors['carrier-name'] = "You can only provide a carrier-name if carrier-code = 'Other'";
            }
        }

        if (\count($this->validation_errors) > 0) {
            return false;
        }

        return true;
    }
    /**
     * retutns a list of valid carrier coes
     * 
     * @return array 
     */
    public function getCarrierCodes(): array
    {
        return [
            self::CARRIER_CODE_BLUE_PACKAGE,
            self::CARRIER_CODE_USPS,
            self::CARRIER_CODE_UPS,
            self::CARRIER_CODE_UPSMI,
            self::CARRIER_CODE_FEDEX,
            self::CARRIER_CODE_DHL,
            self::CARRIER_CODE_DHL_GLOBAL_MAIL,
            self::CARRIER_CODE_FASTWAY,
            self::CARRIER_CODE_UPS_MAIL_INNOVATIONS,
            self::CARRIER_CODE_LASERSHIP,
            self::CARRIER_CODE_ROYAL_MAIL,
            self::CARRIER_CODE_FEDEX_SMARTPOST,
            self::CARRIER_CODE_OSM,
            self::CARRIER_CODE_ONTRAC,
            self::CARRIER_CODE_STREAMLITE,
            self::CARRIER_CODE_NEWGISTICS,
            self::CARRIER_CODE_CANADA_POST,
            self::CARRIER_CODE_CITY_LINK,
            self::CARRIER_CODE_GLS,
            self::CARRIER_CODE_GO,
            self::CARRIER_CODE_HERMES_LOGISTIK_GRUPPE,
            self::CARRIER_CODE_PARCELFORCE,
            self::CARRIER_CODE_TNT,
            self::CARRIER_CODE_TARGET,
            self::CARRIER_CODE_SAGAWAEXPRESSS,
            self::CARRIER_CODE_NIPPONEXPRESS,
            self::CARRIER_CODE_YAMATOTRANSPORT,
            self::CARRIER_CODE_OTHER,
        ];
    }
}
