<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase; // <-- 1. Import cái này
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase; // <-- 2. Khai báo sử dụng ở đây

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}