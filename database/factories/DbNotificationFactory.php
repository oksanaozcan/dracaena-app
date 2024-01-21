<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\DbNotification;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class DbNotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = DbNotification::class;

    public function definition(): array
    {
        return [
            'id' => Str::uuid(),
            'type' => 'App\Notifications\BulkDeleteTagJobFailedNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => 1,
            'data' => 'message for test',
            'read_at' => null,
        ];
    }
}
