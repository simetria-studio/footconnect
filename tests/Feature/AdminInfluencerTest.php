<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\InfluencerCredentialsNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AdminInfluencerTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'is_admin' => true,
            'email' => 'admin@footconnect.test',
        ]);
    }

    public function test_admin_can_create_influencer_with_pix_and_referral_link(): void
    {
        Notification::fake();

        $admin = $this->admin();

        $response = $this->actingAs($admin)->post(route('admin.influencers.store'), [
            'full_name' => 'Ana Influencer',
            'email' => 'ana@influencers.test',
            'generate_password' => '1',
            'send_credentials' => '1',
            'pix_key_type' => 'cpf',
            'pix_key' => '12345678901',
            'referral_code' => 'footana',
            'country' => 'Brasil',
        ]);

        $influencer = User::where('email', 'ana@influencers.test')->first();

        $this->assertNotNull($influencer);
        $this->assertTrue($influencer->isInfluencer());
        $this->assertSame('FOOTANA', $influencer->referral_code);
        $this->assertSame('12345678901', $influencer->pix_key);
        $this->assertNull($influencer->subscription_status);

        $response->assertRedirect(route('admin.influencers.show', $influencer));
        $response->assertSessionHas('influencer_credentials.email', 'ana@influencers.test');
        $response->assertSessionHas('influencer_credentials.referral_code', 'FOOTANA');
        $this->assertNotEmpty(session('influencer_credentials.password'));
        $this->assertStringContainsString('/FOOTANA', session('influencer_credentials.referral_link'));
        $this->assertStringNotContainsString('/indicacao/', session('influencer_credentials.referral_link'));

        Notification::assertSentTo($influencer, InfluencerCredentialsNotification::class);
    }

    public function test_guest_cannot_access_influencer_admin(): void
    {
        $this->get(route('admin.influencers.index'))->assertRedirect(route('login'));
        $this->post(route('admin.influencers.store'), [])->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_create_influencer(): void
    {
        $user = User::factory()->create([
            'role' => 'player',
            'subscription_status' => 'active',
            'current_period_end' => now()->addMonth(),
        ]);

        $this->actingAs($user)
            ->get(route('admin.influencers.create'))
            ->assertForbidden();
    }

    public function test_influencer_can_login_without_subscription_and_see_referral_dashboard(): void
    {
        $influencer = User::factory()->create([
            'role' => 'influencer',
            'email' => 'inf@footconnect.test',
            'password' => 'secret123',
            'pix_key' => 'chave@pix.test',
            'pix_key_type' => 'email',
            'referral_code' => 'FOOTINF1',
            'subscription_status' => null,
        ]);

        $this->post(route('login.post'), [
            'email' => 'inf@footconnect.test',
            'password' => 'secret123',
        ])->assertRedirect(route('referrals.index'));

        $this->actingAs($influencer)
            ->get(route('referrals.index'))
            ->assertOk()
            ->assertSee('FOOTINF1');

        $this->actingAs($influencer)
            ->get(route('home'))
            ->assertRedirect(route('referrals.index'));

        $this->actingAs($influencer)
            ->get(route('onboarding.plans'))
            ->assertRedirect(route('home'));
    }

    public function test_pix_is_required_when_creating_influencer(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.influencers.store'), [
                'full_name' => 'Sem PIX',
                'email' => 'sempix@test.com',
                'generate_password' => '1',
            ])
            ->assertSessionHasErrors(['pix_key', 'pix_key_type']);
    }
}
