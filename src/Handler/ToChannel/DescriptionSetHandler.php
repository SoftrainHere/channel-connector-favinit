<?php declare(strict_types=1);

namespace Mxncommerce\ChannelConnector\Handler\ToChannel;

use App\Exceptions\Api\SaveToCentralException;
use App\Helpers\ChannelConnectorFacade;
use App\Libraries\Dynamo\SendExceptionToCentralLog;
use App\Models\Features\Brand;
use App\Models\Features\Product;
use App\Traits\WaitUntil;
use Exception;
use Mxncommerce\ChannelConnector\Handler\FavinitApiBase;
use Mxncommerce\ChannelConnector\Traits\ProductTrait;
use Mxncommerce\ChannelConnector\Traits\SetOverrideDataFromRemote;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class DescriptionSetHandler extends FavinitApiBase
{
    use ProductTrait;
    use SetOverrideDataFromRemote;

    public function updated(Product $product): bool
    {
        $res = $this->buildCreatePayload($product)
            ->requestMutation(config('channel_connector_for_remote.api_create_product'));
        try {
            $response = json_decode($res->getData());
            if ($response->result != '01') {
                app(SendExceptionToCentralLog::class)(
                    ['Favinit product-updated error', 'got wrong response from favinit'],
                    Response::HTTP_FORBIDDEN
                );
            }
        } catch (Exception $exception) {
            app(SendExceptionToCentralLog::class)(
                ['Favinit product sync error', $exception->getMessage()],
                $exception->getCode()
            );
        }
        return true;
    }
}
