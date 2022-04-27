@if(count($account_items) > 0)
<div class="shadow-black bg-white border-slate-200 border rounded-sm">
    <header class="py-4 px-5 border-b">
        <h2 class="font-semibold text-lg">Kryptomeny</h2>
    </header>
    <div class="p-3">
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead class="uppercase text-xs rounded-sm">
                    <tr>
                        <th class="p-2"> <div class="font-semibold text-left">Nazov</div> </th>
                        <th class="p-2"> <div class="font-semibold text-left">Quantity</div> </th>
                        <th class="p-2"> <div class="font-semibold text-left">Average Buy Price</div> </th>
                        <th class="p-2"> <div class="font-semibold text-left">Actual Price</div> </th>
                        <th class="p-2"> <div class="font-semibold text-left">Spolu</div> </th>
                    </tr>
                </thead>
                <tbody class="font-medium text-sm" >
                    @foreach($account_items as $account_item)
                    <tr>
						<td class="p-2">
                            <div class="flex items-center"> 
                                <div class="text-slate-800">{{ $account_item->name }}</div>
                            </div>
                        </td>
                        <td class="p-2">
                            <div class="flex items-center"> 
                                <div class="text-slate-800">{{ $account_item->total_quantity }}</div>
                            </div>
                        </td>
                        <td class="p-2">
                            <div class="flex items-center"> 
                                <div class="text-slate-800">{{ $account_item->average_buy_price }}</div>
                            </div>
                        </td>
                        <td class="p-2">
                            <div class="flex items-center"> 
                                <div class="text-slate-800">{{ $account_item->current_price }}</div>
                            </div>
                        </td>
						<td class="p-2">
                            <div class="flex items-center"> 
                                <div class="text-slate-800">{{ $account_item->currency_name }}</div>
                            </div>
                        </td>
                        <td class="p-2">
                            <div class="flex items-center"> 
                                <div class="text-slate-800">{{ $account_item->total_value_sum }}</div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
