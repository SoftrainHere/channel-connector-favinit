<?php declare(strict_types=1);

namespace Mxncommerce\ChannelConnector\Traits;

use App\Exceptions\Api\WrongPayloadException;
use App\Helpers\ChannelConnectorFacade;
use App\Models\Features\Product;
use App\Models\Override;
use Mxncommerce\ChannelConnector\Helpers\ChannelConnectorHelper;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

trait ProductTrait
{
    /**
     * @param Product $product
     * @return $this
     * @throws Throwable
     */
    public function buildCreatePayload(Product $product): static
    {
        $productOverride = null;
        if ($product->override instanceof Override) {
            if ($product->override->fields_overrided) {
                $productOverride = json_decode($product->override->fields_overrided);
            }
        }
        $this->payload = [];

        $this->payload['input']['vendor_id'] = ChannelConnectorFacade::configuration()->meta->vendor_id;

        $this->payload['input']['prodinc'] = (string)$product->id;
        $this->payload['input']['modelcode'] = $product->variants[0]->mpn;

        if ($product->descriptionSets) {
            $overrided = ChannelConnectorFacade::getFieldsOverrided($product->descriptionSets[0]);
            $this->payload['input']['pname'] = $overrided['title'] ?? $product->descriptionSets[0]->title;
            $this->payload['input']['story'] =
                $overrided['description'] ?? $product->descriptionSets[0]->description;
        }

        $this->payload['input']['shortage_yn'] = $this->convertProductStatus($product->status);

        if(empty($product->categories[0]->channelCategories[0]->code)) {
            $error_namespace = 'mxncommerce.channel-connector::channel_connector.errors.no_category_id_mapped_exist';
            $error = trans($error_namespace, [
                'category_id' => $product->categories[0]->id,
                'product_id' => $product->id,
            ]);
            throw new WrongPayloadException($error, Response::HTTP_BAD_REQUEST);
        }

        $this->payload['input']['category_id'] = $product->categories[0]->channelCategories[0]->code ?? 'NA';

        $this->payload['input']['nation'] = $product->variants[0]->countryOrigin->code;
        $this->payload['input']['currency_unit'] = $product->variants[0]->currency->code;

        $this->payload['input']['supplyprice'] = (string)$product->representative_supply_price;

        $this->payload['input']['saleprice'] = $product->priceSets[0]->sales_price;
        $this->payload['input']['customerprice'] = (string)$product->priceSets[0]->msrp;
        $this->payload['input']['prodimg'] = stripslashes($product->media[0]->src);
        $this->payload['input']['fabric'] = $productOverride->materials ?? $product->materials;
        $this->payload['input']['brand_nm'] = $product->brand->translations[0]->name;
        $this->payload['input']['weight'] =
            (string)$this->getKGWeight($product->variants[0]->weight_unit, $product->variants[0]->weight);
        $this->payload['input']['hs_code'] = $product->variants[0]->hs_code;
        $this->payload['input']['euyn'] = 'Y';

        $this->payload['input']['sizetype'] = $product->variants[0]->dimension_unit;
        $this->payload['input']['addimginfo'] = $product->media->map(function ($item) {
            return [
                'addUrl' => stripslashes($item->src),
            ];
        });

        $this->payload['input']['optioninfo'] = $product->variants->map(function ($item) {
            $options = json_decode(
                app(ChannelConnectorHelper::class)->buildValidJson($item->options),
                true
            );
            $item_color = 'ONE COLOR';
            $item_size = 'ONE SIZE';

            foreach ($options as $option) {
                if (strtolower($option['name']) === 'color') {
                    $item_color = $option['value'];
                }

                if (strtolower($option['name']) === 'size') {
                    $item_size = $option['value'];
                }
            }

            return [
                'item_color' => $item_color,
                'item_size' => $item_size,
                'bar_code' => $item->id, // This is not actually barcode or sku It must be pk
                'order_lmt_cnt' => (string)$item->inventorySet->available_stock_qty,
            ];
        });

        return $this;
    }

    public function buildUpdatePayload(Product $product): static
    {
        $productOverride = null;
        if ($product->override instanceof Override) {
            $productOverride = json_decode($product->override->fields_overrided);
        }
        $this->payload = [];
        $this->payload['input']['vendor_id'] = ChannelConnectorFacade::configuration()->meta->vendor_id;
        $this->payload['input']['prodinc'] = (string)$product->id;
        $this->payload['input']['modelcode'] = $product->variants[0]->mpn;

        if ($product->descriptionSets) {
            $overrided = ChannelConnectorFacade::getFieldsOverrided($product->descriptionSets[0]);
            $this->payload['input']['pname'] = $overrided['title'] ?? $product->descriptionSets[0]->title;
            $this->payload['input']['story'] =
                $overrided['description'] ?? $product->descriptionSets[0]->description;
        }

        $this->payload['input']['shortage_yn'] = $this->convertProductStatus($product->status);

        // todo: category...
        $this->payload['input']['category_id'] = 'M14830624|7';

        $this->payload['input']['nation'] = $product->variants[0]->countryOrigin->code;
        $this->payload['input']['currency_unit'] = $product->variants[0]->currency->code;
        $this->payload['input']['supplyprice'] = (string)$product->priceSets[0]->final_supply_price;
        $this->payload['input']['saleprice'] = (string)$product->priceSets[0]->sales_price;
        $this->payload['input']['customerprice'] = (string)$product->priceSets[0]->msrp;
        $this->payload['input']['prodimg'] = stripslashes($product->media[0]->src);
        $this->payload['input']['fabric'] = $productOverride->materials ?? $product->materials;
        $this->payload['input']['brand_nm'] = $product->brand->translations[0]->name;
        $this->payload['input']['weight'] =
            (string)$this->getKGWeight($product->variants[0]->weight_unit, $product->variants[0]->weight);
        $this->payload['input']['hs_code'] = $product->variants[0]->hs_code;
        $this->payload['input']['euyn'] =
            ChannelConnectorFacade::checkProductFromEurope($product->variants[0]->countryOrigin->code) ? 'Y' : 'N';

        $this->payload['input']['sizetype'] = $product->variants[0]->dimension_unit;
        $this->payload['input']['addimginfo'] = $product->media->map(function ($item) {
            return [
                'addUrl' => stripslashes($item->src),
            ];
        });

        $this->payload['input']['optioninfo'] = $product->variants->map(function ($item) {
            $options = json_decode(
                app(ChannelConnectorHelper::class)->buildValidJson($item->options),
                true
            );
            $item_color = 'ONE COLOR';
            $item_size = 'ONE SIZE';

            foreach ($options as $option) {
                if (strtolower($option['name']) === 'color') {
                    $item_color = $option['value'];
                }

                if (strtolower($option['name']) === 'size') {
                    $item_size = $option['value'];
                }
            }

            return [
                'item_color' => $item_color,
                'item_size' => $item_size,
                'bar_code' => $item->barcode ?? '123412345',
                'order_lmt_cnt' => (string)$item->inventorySet->available_stock_qty,
            ];
        });

        return $this;
    }

    protected static function convertProductStatus(string $status): string
    {
        return match (strtolower($status)) {
            'active' => '01',
            default => '04'
        };
    }

    protected static function getKGWeight($weight_unit, $weight): float
    {
        return match ($weight_unit) {
            'G' => round($weight / 1000),
            'OZ' => (float)number_format($weight * 0.0283495, 3),
            'LB' => (float)number_format($weight * 0.453592, 3),
            default => (float)$weight
        };
    }
}
