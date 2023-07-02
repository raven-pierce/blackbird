<div class="grid grid-cols-4">
    <div class="mt-24 col-span-2 flex flex-col gap-y-4 self-end">
        <div class="w-fit inline-flex items-center text-xs text-gray-200 font-semibold uppercase tracking-widest rounded-md overflow-hidden">
            @if($this->record->status === 'Paid')
                <span class="px-4 py-2 bg-success-500">
                    {{ $this->record->status }}
                </span>
            @elseif($this->record->status === 'Unpaid')
                <span class="px-4 py-2 bg-danger-500">
                    {{ $this->record->status }}
                </span>
            @elseif($this->record->status === 'Void')
                <span class="px-4 py-2 bg-gray-500">
                    {{ $this->record->status }}
                </span>
            @endif
        </div>

        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-200">Invoice #{{ $this->record->external_id }}</h1>

        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $this->record->updated_at->format('l, d F Y') }}</span>
    </div>

    <div class="mt-24 col-span-2 flex flex-col text-sm text-gray-500 dark:text-gray-400 text-right self-end">
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Recipient</h2>

        <span class="mt-4 font-medium">{{ $this->record->user->name }}</span>
        <span class="mt-2">{{ $this->record->user->email }}</span>
    </div>

    @if($this->record->status === 'Paid')
        <div class="mt-16 col-span-4 flex flex-col text-sm text-gray-500 dark:text-gray-400">
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Payment Method</h3>

            <div class="mt-4 flex items-center">
                <x-heroicon-s-credit-card class="h-5 w-5 text-primary-500" />

                <x-heroicon-s-minus class="h-5 w-5" />

                <span class="ml-4 font-medium tracking-widest">{{ \Illuminate\Support\Str::afterLast($gatewayInvoice->focusTransaction->CardNumber, 'x') }}</span>
            </div>
        </div>
    @endif

    <div class="mt-16 col-span-4 flex flex-col">
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Invoice Details</h2>

        <div class="mt-4 overflow-hidden rounded">
            <table class="w-full text-left text-sm text-gray-500">
                <thead class="border border-gray-200 dark:border-gray-800 bg-gray-200 dark:bg-gray-800 text-xs font-medium uppercase text-gray-700 dark:text-gray-200">
                <tr>
                    <th scope="col" class="px-8 py-4">
                        Item
                    </th>

                    <th scope="col" class="px-8 py-4">
                        Quantity
                    </th>

                    <th scope="col" class="px-8 py-4">
                        Unit Pricing
                    </th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-800 border border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400">
                    @foreach($gatewayInvoice->InvoiceItems as $item)
                        <tr>
                            <td class="px-8 py-6">{{ $item->ItemName }}</td>

                            <td class="px-8 py-6">{{ $item->Quantity }}</td>

                            <td class="px-8 py-6">{{ $currencyFormatter->formatCurrency($item->UnitPrice, config('payment.display_currency')) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8 flex flex-col text-right">
            <h2 class="text-sm font-medium text-gray-700 dark:text-gray-200 uppercase">Total</h2>

            <span class="tracking-tight text-gray-500 dark:text-gray-400">{{ $currencyFormatter->formatCurrency($this->record->amount, config('payment.display_currency')) }}</span>
        </div>
    </div>
</div>
