<?php

declare(strict_types=1);

namespace Autumndev\MWS;

class MWSProduct
{
    public const   COND_NEW                = 'New';
    public const   COND_REFURBISHED        = 'Refurbished';
    public const   COND_USED_LIKE_NEW      = 'UsedLikeNew';
    public const   COND_USED_VERY_GOOD     = 'UsedVeryGood';
    public const   COND_USED_GOOD          = 'UsedGood';
    public const   COND_USED_ACCEPTABLE    = 'UsedAcceptable';

    /**
     * @var string
     */
    public $sku;
    public $product_id_type;
    public $condition_type;
    public $condition_note;
    public $product_id;
    /**
     * @var int|string
     */
    public $price;
    public $quantity;

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
            'sku'               => $this->sku,
            'price'             => $this->price,
            'quantity'          => $this->quantity,
            'product_id'        => $this->product_id,
            'product_id_type'   => $this->product_id_type,
            'condition_type'    => $this->condition_type,
            'condition_note'    => $this->condition_note,
        ];
    }
    /**
     * validates a product
     *
     * @return bool
     */
    public function validate(): bool
    {
        if (\mb_strlen($this->sku) < 1 or \strlen($this->sku) > 40) {
            $this->validation_errors['sku'] = 'Should be longer then 1 character and shorter then 40 characters';
        }

        $this->price = \str_replace(',', '.', (string) $this->price);
        $exploded_price = \explode('.', $this->price);

        if (\count($exploded_price) == 2) {
            if (\mb_strlen($exploded_price[0]) > 18) {
                $this->validation_errors['price'] = 'Too high';
            } elseif (\mb_strlen($exploded_price[1]) > 2) {
                $this->validation_errors['price'] = 'Too many decimals';
            }
        } else {
            $this->validation_errors['price'] = 'Looks wrong';
        }

        $this->quantity = (int) $this->quantity;
        $this->product_id = (string) $this->product_id;

        $product_id_length = \mb_strlen($this->product_id);

        switch ($this->product_id_type) {
            case 'ASIN':
                if ($product_id_length != 10) {
                    $this->validation_errors['product_id'] = 'ASIN should be 10 characters long';
                }
                break;
            case 'UPC':
                if ($product_id_length != 12) {
                    $this->validation_errors['product_id'] = 'UPC should be 12 characters long';
                }
                break;
            case 'EAN':
                if ($product_id_length != 13) {
                    $this->validation_errors['product_id'] = 'EAN should be 13 characters long';
                }
                break;
            default:
                $this->validation_errors['product_id_type'] = 'Not one of: ASIN,UPC,EAN';
        }
        $conditions = $this->getConditions();
        if (!\in_array($this->condition_type, $conditions)) {
            $this->validation_errors['condition_type'] = 'Not one of: ' . \implode(',', $conditions);
        }

        if ($this->condition_type != 'New') {
            $length = \mb_strlen($this->condition_note);
            if ($length < 1) {
                $this->validation_errors['condition_note'] = 'Required if condition_type not is New';
            } elseif ($length > 1000) {
                $this->validation_errors['condition_note'] = 'Should not exceed 1000 characters';
            }
        }

        if (\count($this->validation_errors) > 0) {
            return false;
        }

        return true;
    }
    /**
     * wrapps all the conditions into an array
     *
     * @return array
     */
    public function getConditions(): array
    {
        return [
            self::COND_NEW,
            self::COND_REFURBISHED,
            self::COND_USED_ACCEPTABLE,
            self::COND_USED_GOOD,
            self::COND_USED_LIKE_NEW,
            self::COND_USED_VERY_GOOD,
        ];
    }
}
