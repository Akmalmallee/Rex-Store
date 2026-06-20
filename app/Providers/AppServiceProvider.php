<?php

namespace App\Providers;

use App\Services\AiProviders\FittingAiInterface;
use App\Services\Payment\DummyGateway;
use App\Services\Payment\MidtransGateway;
use App\Services\Payment\PaymentGatewayInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FittingAiInterface::class, function () {
            $provider = config('fitting.ai_provider', 'mock');

            return match ($provider) {
                'openai' => new \App\Services\AiProviders\OpenAiFittingProvider,
                default => new \App\Services\AiProviders\MockFittingAiProvider,
            };
        });

        $this->app->bind(PaymentGatewayInterface::class, function () {
            $driver = config('payment.driver', 'dummy');

            return match ($driver) {
                'midtrans' => new MidtransGateway,
                default => new DummyGateway,
            };
        });
    }

    public function boot(): void
    {
        //
    }
}
