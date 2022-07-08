<?php declare(strict_types=1);

namespace Mxncommerce\ChannelConnector\Console\Commands;

use App\Models\ChannelCategory;
use Illuminate\Console\Command;

class SetupChannelResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:setup-channel-resources';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup channel resources';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $categoriesFromChannel = config('channel_connector_for_remote.channel_categories');
        foreach ($categoriesFromChannel as $key => $item) {
            $resource = explode('|', $item);
            ChannelCategory::updateOrCreate(['code' => $key], [
                'large_category' => $resource[0] ,
                'medium_category' => $resource[1] ?? null ,
                'small_category' => $resource[2] ?? null ,
            ]);
        }
    }
}
