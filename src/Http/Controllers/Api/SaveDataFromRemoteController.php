<?php declare(strict_types=1);

namespace Mxncommerce\ChannelConnector\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\PostEventFromChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class SaveDataFromRemoteController extends Controller
{
    /**
     * @param Request $request
     * @throws Throwable
     */
    public function save(Request $request): void
    {
        $value = explode('/', $request->header('x-shopify-topic'));
        if (count($value) < 2) {
            return;
        }

        $message = [
            'change_type' => $value[0],
            'action_type' => $value[1],
            "payload" => $request->toArray(),
        ];

        if (config('app.debug') && config('app.env') !== Str::lower('production')) {
            Log::info($value[0] . ' -> ' . $value[1]);
            Log::info(json_encode($request->toArray()));
        }

        PostEventFromChannel::dispatch($message);
    }
}
