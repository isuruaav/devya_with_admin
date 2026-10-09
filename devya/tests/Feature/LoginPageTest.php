<?php

namespace Tests\Feature;

use App\Filament\Auth\Login;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Cookie;
use Livewire\Livewire;
use Tests\Concerns\InteractsWithPermissions;
use Tests\TestCase;

class LoginPageTest extends TestCase
{
    use InteractsWithPermissions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpPermissions();
    }

    public function test_guests_can_access_login_fields_without_theme_controls(): void
    {
        $this->get('/admin/login')
            ->assertOk()
            ->assertSeeText('Email address')
            ->assertSeeText('Password')
            ->assertDontSeeText('Remember me')
            ->assertSeeText('Sign In')
            ->assertDontSee('Enable light theme')
            ->assertDontSee('Enable dark theme')
            ->assertDontSee('Enable system theme');
    }

    public function test_login_keeps_required_credential_validation(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Livewire::test(Login::class)
            ->call('authenticate')
            ->assertHasFormErrors(['email' => 'required', 'password' => 'required']);
    }

    public function test_returning_to_login_clears_the_previous_session(): void
    {
        $user = $this->userWithRole('super_admin');
        $this->actingAs($user)->withSession([
            '_token' => 'previous-csrf-token',
            'previous_session_data' => 'old value',
        ]);
        $previousSessionId = session()->getId();

        $this->get('/admin/login')
            ->assertOk()
            ->assertSeeText('Sign In')
            ->assertSessionMissing('previous_session_data');

        $this->assertGuest();
        $this->assertNotSame($previousSessionId, session()->getId());
        $this->assertNotSame('previous-csrf-token', session()->token());
        $this->get('/admin')->assertRedirect(route('filament.admin.auth.login'));
    }

    public function test_fresh_credentials_sign_in_without_a_remember_cookie(): void
    {
        $user = $this->userWithRole('super_admin');

        Livewire::test(Login::class)
            ->fillForm(['email' => $user->email, 'password' => 'password'])
            ->set('data.remember', true)
            ->call('authenticate')
            ->assertHasNoFormErrors()
            ->assertRedirect(Filament::getUrl());

        $this->assertAuthenticatedAs($user);
        $this->assertFalse(Cookie::hasQueued(Filament::auth()->getRecallerName()));
    }

    public function test_returning_to_login_revokes_a_legacy_remember_token(): void
    {
        $user = $this->userWithRole('super_admin');
        Filament::auth()->login($user, true);
        $previousRememberToken = $user->getRememberToken();
        $cookieName = Filament::auth()->getRecallerName();
        $rememberCookie = Cookie::queued($cookieName);
        $this->assertNotNull($rememberCookie);
        session()->invalidate();
        $this->app['auth']->forgetGuards();

        $this->withCookie($cookieName, $rememberCookie->getValue())
            ->get('/admin/login')->assertOk()->assertSeeText('Sign In');

        $this->assertGuest();
        $this->assertNotSame($previousRememberToken, $user->fresh()->getRememberToken());
        $cookie = Cookie::queued(Filament::auth()->getRecallerName());
        $this->assertNotNull($cookie);
        $this->assertLessThan(time(), $cookie->getExpiresTime());
    }

    public function test_login_uses_a_cookie_that_expires_when_the_browser_closes(): void
    {
        $response = $this->get('/admin/login')->assertOk();

        $cookie = collect($response->headers->getCookies())
            ->first(fn ($cookie) => $cookie->getName() === config('session.cookie'));
        $this->assertNotNull($cookie);
        $this->assertSame(0, $cookie->getExpiresTime());
    }
}
