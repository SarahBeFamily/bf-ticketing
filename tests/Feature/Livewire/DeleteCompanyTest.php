<?php

namespace Tests\Feature\Livewire;

use App\Livewire\DeleteCompany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class DeleteCompanyTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(DeleteCompany::class)
            ->assertStatus(200);
    }
}
