<?php

namespace Database\Seeders;

use App\Managers\UserMessageManager;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RedisSeeder extends Seeder
{

    protected $messageManager;

    public function __construct(UserMessageManager $messageManager)
    {
        $this->messageManager = $messageManager;
    }


    public function run(): void
    {
        $faker = Faker::create();

        foreach(range(1, 15) as $iteration) {
            $randomUser = User::inRandomOrder()->first();
            $data = [
                'message' => $faker->paragraph,
                'timeStamp' => $faker->unixTime(),
                'userName' => $randomUser->name,
            ];
            $this->messageManager->addMessage($randomUser->id, json_encode($data));
        }
    }
}
