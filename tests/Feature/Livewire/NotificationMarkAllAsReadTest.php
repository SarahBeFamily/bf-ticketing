<?php

namespace Tests\Feature\Livewire;

use App\Livewire\NotificationMarkAllAsRead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class NotificationMarkAllAsReadTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(NotificationMarkAllAsRead::class)
            ->assertStatus(200);
    }
}
