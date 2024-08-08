<?php

namespace Tests\Feature\Livewire;

use App\Livewire\NotificationActions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class NotificationActionsTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(NotificationActions::class)
            ->assertStatus(200);
    }
}
