<?php

namespace Tests\Feature\Livewire;

use App\Livewire\NotificationMarkAsRead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class NotificationMarkAsReadTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(NotificationMarkAsRead::class)
            ->assertStatus(200);
    }
}
