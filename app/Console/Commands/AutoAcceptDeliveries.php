<?php

namespace App\Console\Commands;

use App\Models\Delivery;
use Faker\Factory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class AutoAcceptDeliveries extends Command
{
    /** @var string */
    protected $signature = 'app:auto-accept-deliveries';

    /** @var string */
    protected $description = 'Auto Accept Pending Deliveries';

    public function handle(): int
    {
        if (($autoAcceptAfter = config('allocation.auto_accept_time')) === null) {
            $this->info('Auto Accept is disabled.');
            return self::SUCCESS;
        }

        $faker = Factory::create('fa_IR');

        $deliveries = Delivery::query()
            ->where('status', 'PENDING')
            ->where('updated_at', '<', now()->subSeconds($autoAcceptAfter))
            ->get();

        $acceptedCount = 0;

        foreach ($deliveries as $delivery) {
            DB::beginTransaction();

            try {
                $delivery->update([
                    'bikerId' => $faker->numberBetween(1, 3000),
                    'bikerName' => $faker->name('male'),
                    'bikerPhoneNumber' => $faker->numerify('09#########'),
                    'bikerPhotoUrl' => $faker->imageUrl(),
                    'status' => 'ACCEPTED',
                ]);

                DB::commit();

                $this->info("Delivery $delivery->id accepted.");
                $acceptedCount++;
            } catch (Throwable $e) {
                DB::rollBack();

                throw $e;
            }
        }

        $this->info("$acceptedCount deliveries accepted.");
        return self::SUCCESS;
    }
}
