<table class="table-auto w-full">
    <thead class="uppercase text-xs rounded-sm">
        <tr>
            <th class="p-2"> <div class="font-semibold text-left">Name</div> </th>
            <th class="p-2"> <div class="font-semibold text-left">Quantity</div> </th>
            <th class="p-2"> <div class="font-semibold text-left">Average Buy Price</div> </th>
            <th class="p-2"> <div class="font-semibold text-left">Actual Price</div> </th>
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
                    <div class="text-slate-800">{{ $account_item->quantity }}</div>
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
        </tr>
        @endforeach
    </tbody>
</table>