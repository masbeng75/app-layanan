<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AdminAppearanceTest extends TestCase
{
    public function test_admin_dashboard_can_be_rendered(): void
    {
        $user = User::first();
        if (! $user) {
            $this->seed();
            $user = User::first();
        }
        $response = $this->actingAs($user)->get('/admin');
        $response->assertSuccessful();
        $response->assertSee('SAPA SOSIAL');
        $response->assertSee('Dinsos Kab. Blitar');
        $response->assertSee('fi-custom-brand-logo');
        $response->assertSee('fi-brand-icon-box');
        $response->assertSee('plus-jakarta-sans');
    }

    public function test_admin_login_can_be_rendered(): void
    {
        $response = $this->get('/admin/login');
        $response->assertSuccessful();
        $response->assertSee('SAPA SOSIAL');
        $response->assertSee('Sign in');
        $response->assertSee('Email address');
        $response->assertSee('Password');
        $response->assertSee('--theme-gradient-primary');
    }
}
