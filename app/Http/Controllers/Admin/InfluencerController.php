<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralCommission;
use App\Models\ReferralWithdrawal;
use App\Models\User;
use App\Notifications\InfluencerCredentialsNotification;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class InfluencerController extends Controller
{
    public function __construct(
        private ReferralService $referralService
    ) {}

    public function index(Request $request)
    {
        $query = User::query()
            ->where('role', 'influencer')
            ->withCount('referrals');

        if ($search = $request->string('q')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('referral_code', 'like', "%{$search}%")
                    ->orWhere('pix_key', 'like', "%{$search}%");
            });
        }

        $influencers = $query->latest()->paginate(20)->withQueryString();

        return view('admin.influencers.index', compact('influencers'));
    }

    public function create()
    {
        return view('admin.influencers.form', [
            'influencer' => new User,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $plainPassword = $request->boolean('generate_password') || blank($data['password'] ?? null)
            ? $this->generatePassword()
            : $data['password'];

        $user = User::create([
            'name' => $data['full_name'],
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'password' => Hash::make($plainPassword),
            'role' => 'influencer',
            'referral_code' => $data['referral_code'] ?? null,
            'pix_key' => $data['pix_key'],
            'pix_key_type' => $data['pix_key_type'],
            'is_active' => true,
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'country' => $data['country'] ?? 'Brasil',
        ]);

        $this->referralService->ensureReferralCode($user);

        $user->refresh();
        $referralLink = $this->referralService->getReferralLink($user);

        $this->maybeSendCredentials($user, $plainPassword, $referralLink, $request->boolean('send_credentials', true));

        return redirect()
            ->route('admin.influencers.show', $user)
            ->with('status', 'Influenciador cadastrado com sucesso. Copie os dados abaixo para enviar.')
            ->with('influencer_credentials', [
                'email' => $user->email,
                'password' => $plainPassword,
                'referral_code' => $user->referral_code,
                'referral_link' => $referralLink,
            ]);
    }

    public function show(User $influencer)
    {
        abort_unless($influencer->isInfluencer(), 404);

        $influencer->loadCount(['referrals', 'referralCommissionsEarned', 'referralWithdrawals']);

        $referrals = User::where('referred_by_id', $influencer->id)->latest()->take(20)->get();
        $commissions = ReferralCommission::where('referrer_id', $influencer->id)
            ->with('referred:id,full_name,name,email')
            ->latest()
            ->take(10)
            ->get();
        $withdrawals = ReferralWithdrawal::where('user_id', $influencer->id)->latest()->take(10)->get();
        $referralLink = $this->referralService->getReferralLink($influencer);
        $stats = $this->referralService->getDashboardStats($influencer);

        return view('admin.influencers.show', compact(
            'influencer',
            'referrals',
            'commissions',
            'withdrawals',
            'referralLink',
            'stats'
        ));
    }

    public function edit(User $influencer)
    {
        abort_unless($influencer->isInfluencer(), 404);

        return view('admin.influencers.form', compact('influencer'));
    }

    public function update(Request $request, User $influencer)
    {
        abort_unless($influencer->isInfluencer(), 404);

        $data = $this->validated($request, $influencer);

        $influencer->fill([
            'name' => $data['full_name'],
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'pix_key' => $data['pix_key'],
            'pix_key_type' => $data['pix_key_type'],
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'country' => $data['country'] ?? $influencer->country,
        ]);

        if (! empty($data['referral_code'])) {
            $influencer->referral_code = $data['referral_code'];
        }

        $influencer->save();

        return redirect()
            ->route('admin.influencers.show', $influencer)
            ->with('status', 'Dados do influenciador atualizados.');
    }

    public function resetPassword(Request $request, User $influencer)
    {
        abort_unless($influencer->isInfluencer(), 404);

        $plainPassword = $this->generatePassword();
        $influencer->password = Hash::make($plainPassword);
        $influencer->save();

        $referralLink = $this->referralService->getReferralLink($influencer);

        $this->maybeSendCredentials($influencer, $plainPassword, $referralLink, $request->boolean('send_credentials', true));

        return redirect()
            ->route('admin.influencers.show', $influencer)
            ->with('status', 'Nova senha gerada. Copie os dados abaixo — a senha não será exibida novamente.')
            ->with('influencer_credentials', [
                'email' => $influencer->email,
                'password' => $plainPassword,
                'referral_code' => $influencer->referral_code,
                'referral_link' => $referralLink,
            ]);
    }

    private function validated(Request $request, ?User $influencer = null): array
    {
        if ($request->filled('referral_code')) {
            $request->merge([
                'referral_code' => strtoupper(trim($request->string('referral_code')->toString())),
            ]);
        } else {
            $request->merge(['referral_code' => null]);
        }

        return $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($influencer?->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'generate_password' => ['sometimes', 'boolean'],
            'send_credentials' => ['sometimes', 'boolean'],
            'pix_key_type' => ['required', 'in:'.implode(',', array_keys(config('referrals.pix_key_types')))],
            'pix_key' => ['required', 'string', 'max:255'],
            'referral_code' => [
                'nullable',
                'string',
                'max:16',
                'regex:'.config('referrals.custom_code_pattern'),
                Rule::unique('users', 'referral_code')->ignore($influencer?->id),
            ],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:8'],
            'country' => ['nullable', 'string', 'max:255'],
        ], [
            'referral_code.regex' => 'O código deve começar com FOOT e ter de 6 a 16 caracteres (ex: FOOT23).',
            'referral_code.unique' => 'Este código de indicação já está em uso.',
            'pix_key.required' => 'A chave PIX é obrigatória para o influenciador receber comissões.',
        ]);
    }

    private function generatePassword(): string
    {
        return Str::password(12, symbols: false);
    }

    private function maybeSendCredentials(User $user, string $plainPassword, string $referralLink, bool $send): void
    {
        if (! $send) {
            return;
        }

        try {
            $user->notify(new InfluencerCredentialsNotification(
                $plainPassword,
                $referralLink,
                (string) $user->referral_code
            ));
        } catch (\Throwable $e) {
            Log::warning('Falha ao enviar e-mail de credenciais do influenciador.', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
