<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Blackbird') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Bitter:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-white bg-gray-900 p-16">
    <div class="bg-white rounded-md mx-auto grid grid-cols-4 place-content-center max-w-screen-md justify-center p-16">
        <a href="{{ route('filament.auth.login') }}" class="col-span-3">
            <img src="{{ asset('images/logos/banner.png') }}" alt="{{ config('app.name') }}'s Logo" class="h-16 w-auto">
        </a>

        <div class="mt-24 col-span-2 flex flex-col gap-y-4 self-end">
            @if($invoice->status === 'Paid')
                <span class="w-fit inline-flex items-center px-4 py-2 bg-emerald-500 rounded-md font-semibold text-xs uppercase tracking-widest">
                {{ $invoice->status }}
            </span>
            @elseif($invoice->status === 'Unpaid')
                <span class="w-fit inline-flex items-center px-4 py-2 bg-red-500 rounded-md font-semibold text-xs uppercase tracking-widest">
                {{ $invoice->status }}
            </span>
            @elseif($invoice->status === 'Void')
                <span class="w-fit inline-flex items-center px-4 py-2 bg-gray-500 rounded-md font-semibold text-xs uppercase tracking-widest">
                {{ $invoice->status }}
            </span>
            @endif

            <h1 class="text-3xl font-black text-gray-900">Invoice #{{ $invoice->external_id }}</h1>

            <span class="text-base font-light text-gray-500">{{ $invoice->updated_at->format('l, d F Y') }}</span>
        </div>

        <div class="mt-24 col-span-2 flex flex-col text-right self-end">
            <h2 class="text-xl font-bold text-gray-700">Recipient</h2>

            <span class="mt-4 text-sm font-extrabold text-gray-500">{{ $invoice->user->name }}</span>
            <span class="mt-2 text-sm font-bold text-gray-500">{{ $invoice->user->email }}</span>
        </div>

        @if($invoice->status == 'Paid')
            <div class="mt-16 col-span-4 flex flex-col">
                <h3 class="text-lg font-bold text-gray-500">Payment Method</h3>

                <div class="mt-4 flex items-center">
                    @if($gatewayInvoice->focusTransaction->PaymentGateway === 'VISA/MASTER')
                        @if(\Illuminate\Support\Str::startsWith($gatewayInvoice->focusTransaction->CardNumber, '4'))
                            {{--TODO: FAS Icons--}}
                            <x-fab-cc-visa class="h-8 w-auto text-blue-500" />

                            <span class="ml-4 inline-flex space-x-1 text-sm font-bold text-gray-500 tracking-widest">
                                <x-assets.cards.redacted class="h-1.5 w-auto" />
                                <x-assets.cards.redacted class="h-1.5 w-auto" />
                                <x-assets.cards.redacted class="h-1.5 w-auto" />
                                <x-assets.cards.redacted class="h-1.5 w-auto" />
                            </span>

                            <span class="ml-4 text-sm font-bold text-gray-500 tracking-widest">{{ \Illuminate\Support\Str::afterLast($gatewayInvoice->focusTransaction->CardNumber, 'x') }}</span>
                        @elseif(\Illuminate\Support\Str::startsWith($gatewayInvoice->focusTransaction->CardNumber, '5'))
                            <x-assets.cards.mastercard class="h-8 w-auto text-red-500" />

                            <span class="ml-4 inline-flex space-x-1 text-sm font-bold text-gray-500 tracking-widest">
                                <x-assets.cards.redacted class="h-1.5 w-auto" />
                                <x-assets.cards.redacted class="h-1.5 w-auto" />
                                <x-assets.cards.redacted class="h-1.5 w-auto" />
                                <x-assets.cards.redacted class="h-1.5 w-auto" />
                            </span>

                            <span class="ml-4 text-sm font-bold text-gray-500 tracking-widest">{{ \Illuminate\Support\Str::afterLast($gatewayInvoice->focusTransaction->CardNumber, 'x') }}</span>
                        @endif
                    @elseif($gatewayInvoice->focusTransaction->PaymentGateway === 'AMEX')
                        <x-assets.cards.amex class="h-8 w-auto text-sky-500" />

                        <span class="ml-4 inline-flex space-x-1 text-sm font-bold text-gray-500 tracking-widest">
                                <x-assets.cards.redacted class="h-1.5 w-auto" />
                                <x-assets.cards.redacted class="h-1.5 w-auto" />
                                <x-assets.cards.redacted class="h-1.5 w-auto" />
                                <x-assets.cards.redacted class="h-1.5 w-auto" />
                            </span>

                        <span class="ml-4 text-sm font-bold text-gray-500 tracking-widest">{{ \Illuminate\Support\Str::afterLast($gatewayInvoice->focusTransaction->CardNumber, 'x') }}</span>
                    @else
                        <x-assets.cards.generic class="h-8 w-auto text-indigo-500" />

                        <span class="ml-4 inline-flex space-x-1 text-sm font-bold text-gray-500 tracking-widest">
                                <x-assets.cards.redacted class="h-1.5 w-auto" />
                                <x-assets.cards.redacted class="h-1.5 w-auto" />
                                <x-assets.cards.redacted class="h-1.5 w-auto" />
                                <x-assets.cards.redacted class="h-1.5 w-auto" />
                            </span>

                        <span class="ml-4 text-sm font-bold text-gray-500 tracking-widest">{{ \Illuminate\Support\Str::afterLast($gatewayInvoice->focusTransaction->CardNumber, 'x') }}</span>
                    @endif
                </div>
            </div>
        @endif

        <div class="mt-16 col-span-4 flex flex-col">
            <h2 class="text-xl font-bold text-gray-700"></h2>

            <div class="overflow-hidden rounded">
                <table class="w-full text-left text-sm text-gray-500">
                    <thead class="border border-gray-100 bg-gray-100 text-xs font-semibold uppercase text-gray-700">
                        <tr>
                            <th scope="col" class="px-8 py-4">
                                Item
                            </th>

                            <th scope="col" class="px-8 py-4 text-right">
                                Quantity
                            </th>

                            <th scope="col" class="px-8 py-4 text-right">
                                Unit Pricing
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 border border-gray-100 text-gray-500">
                        @foreach ($gatewayInvoice->InvoiceItems as $item)
                            <tr>
                                <td class="px-8 py-6">{{ $item->ItemName }}</td>
                                <td class="px-8 py-6 text-right">{{ $item->Quantity }}</td>
                                <td class="px-8 py-6 text-right">{{ $currencyFormatter->formatCurrency($item->UnitPrice, config('payment.display_currency')) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8 text-right flex flex-col">
                <span class="text-base font-bold text-gray-700 uppercase">Total</span>

                <span class="text-base font-semibold text-gray-500">{{ $currencyFormatter->formatCurrency($invoice->amount, config('payment.display_currency')) }}</span>
            </div>
        </div>
    </div>
</body>

</html>
