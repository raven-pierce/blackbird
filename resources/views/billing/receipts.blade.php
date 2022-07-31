<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-500 leading-tight">Receipts</h2>

            <x-links.tertiary href="{{ route('billing.index') }}">Back To Billing</x-links.tertiary>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-md">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="bg-gray-900 border border-gray-900 text-xs text-gray-400 font-semibold uppercase">
                        <tr>
                            <th scope="col" class="px-12 py-6">
                                Date
                            </th>

                            <th scope="col" class="px-12 py-6">
                                Item
                            </th>

                            <th scope="col" class="px-12 py-6 text-right">
                                Quantity
                            </th>

                            <th scope="col" class="px-12 py-6 text-right">
                                Unit Pricing
                            </th>

                            <th scope="col" class="px-12 py-6 text-right">
                                Total
                            </th>

                            <th scope="col" class="px-12 py-6 text-right">
                                <span class="sr-only">View</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 border border-gray-100 text-gray-700">
                        <!--TODO: Item name-->
                        @foreach ($receipts as $receipt)
                            <tr>
                                <td class="px-12 py-6">{{ $receipt->paid_at->format('d F, Y') }}</td>
                                <td class="px-12 py-6">Item Name</td>
                                <td class="px-12 py-6 text-right">{{ $receipt->quantity }}</td>
                                <td class="px-12 py-6 text-right">
                                    {{ $currencyFormatter->formatCurrency($receipt->amount / $receipt->quantity, 'USD') }}
                                </td>
                                <td class="px-12 py-6 text-right">
                                    {{ $currencyFormatter->formatCurrency($receipt->amount, 'USD') }}</td>
                                <td class="px-12 py-6 text-right">
                                    <x-links.tables.primary href="{{ $receipt->receipt_url }}" target="_blank">View
                                    </x-links.tables.primary>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $receipts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
