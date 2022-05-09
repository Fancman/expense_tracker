@if(count($account_items) > 0)
<div class="shadow-black bg-white border-slate-200 border rounded-sm w-8/12">
    <header class="py-4 px-5 border-b">
        <h2 class="font-semibold text-lg">Akcie</h2>
    </header>
    <div class="p-3">
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead class="uppercase text-xs rounded-sm">
                    <tr>
                        <th class="p-2"> <div class="font-semibold text-left">Name</div> </th>
                        <th class="p-2"> <div class="font-semibold text-left">Quantity</div> </th>
                        <th class="p-2"> <div class="font-semibold text-left">Average Buy Price</div> </th>
                        <th class="p-2"> <div class="font-semibold text-left">Actual Price</div> </th>
                        <th class="p-2"> <div class="font-semibold text-left">Zmena</div> </th>
                        <th class="p-2"> <div class="font-semibold text-left">Mena</div> </th>
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
                                <div class="text-slate-800">{{ round($account_item->total_quantity, 2) }}</div>
                            </div>
                        </td>
                        <td class="p-2">
                            <div class="flex items-center"> 
                                <div class="text-slate-800">{{ round($account_item->average_buy_price, 2) }}</div>
                            </div>
                        </td>
                        <td class="p-2">
                            <div class="flex items-center"> 
                                <div class="text-slate-800">{{ round($account_item->current_price, 2) }}</div>
                            </div>
                        </td>
						<td class="p-2">
                            <div class="flex items-center"> 
								@if (($account_item->current_price / $account_item->average_buy_price * 100) == 100)
									<div class="text-left"></div>
								@elseif (($account_item->current_price / $account_item->average_buy_price * 100) > 100)
									<div class="text-left font-semibold"><span class="text-green-500 ml-2">{{ round(($account_item->current_price / $account_item->average_buy_price * 100) - 100, 2) }} %</span></div>
								@elseif (($account_item->current_price / $account_item->average_buy_price * 100) < 100)
									<div class="text-left font-semibold"><span class="text-red-500 ml-2">{{ round(($account_item->current_price / $account_item->average_buy_price * 100) - 100, 2) }} %</span></div>
								@endif
                            </div>
                        </td>
						<td class="p-2">
                            <div class="flex items-center"> 
                                <div class="text-slate-800">{{ $account_item->currency_name }}</div>
                            </div>
                        </td>
                        <td class="p-2">
                            <div class="flex items-center"> 
                                <div class="text-slate-800">{{ round($account_item->total_value_sum, 2) }}</div>
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