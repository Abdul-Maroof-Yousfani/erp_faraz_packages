<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div>
            <table class="table table-bordered table-striped table-condensed tableMargin">
                <tbody class="text-center">
                    <tr>
                        <th>Product</th>
                        <td colspan="2">{{ $makeProduct->recipe->subItem->sub_ic }}</td>
                        <th>Cost</th>
                        <td colspan="2">{{ $makeProduct->average_cost }}</td>
                    </tr>
                    <tr>
                        <th>Quantity</th>
                        <td colspan="2">{{ $makeProduct->quantity }}</td>
                        <th>Electricity Expense</th>
                        <td colspan="2">{{ $makeProduct->electricity_expense }}</td>
                    </tr>
                    <tr>
                        <th>Labour Expense</th>
                        <td colspan="2">{{ $makeProduct->electricity_expense }}</td>
                        <th>Expense</th>
                        <td colspan="2">{{ $makeProduct->expense }}</td>
                    </tr>
                    <tr>
                        <th colspan="6">Product Details</th>
                    </tr>
                    <tr>
                        <th>Items</th>
                        <th>UOM</th>
                        <th>Product Per Qty</th>
                        <th>Rate Per Qty</th>
                        <th>Total Qty</th>
                        <th>Total Qty Amount</th>
                    </tr>
                    @foreach ($makeProduct->productDatas as $makeProductData)
                        <tr>
                            <td>{{ $makeProductData->sub_item_name }}</td>
                            <td>{{ $makeProductData->uom }}</td>
                            <td>{{ $makeProductData->actual_qty }}</td>
                            <td>{{ $makeProductData->rate_per_qty }}</td>
                            <td>{{ $makeProductData->total_qty }}</td>
                            <td>{{ $makeProductData->rate_per_qty * $makeProductData->total_qty }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
