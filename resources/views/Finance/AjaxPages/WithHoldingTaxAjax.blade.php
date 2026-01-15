<?php
use App\Helpers\CommonHelper;
$count = 1;
?>

@foreach($npdData as $key => $value)
                                                                       @php
                                                                        $data = DB::connection('mysql2')->table('supplier as s')
                                                                                ->select('s.ntn', 's.cnic', 's.name', 's.city', 'si.address', 's.company_status', 's.company_name')
                                                                                ->leftJoin('supplier_info as si', 's.id', '=', 'si.supp_id')
                                                                                ->where('s.id', $value->supplier_id)
                                                                                ->first();

                                                                        $Taxable_Amount = DB::connection('mysql2')->table('new_pv_data')
                                                                                            ->where('paid_to_id', $value->supplier_id)
                                                                                            ->where('pv_no', $value->pv_no)
                                                                                            ->where('debit_credit', 1)
                                                                                            ->first();

                                                                        $city = CommonHelper::get_all_cities_by_id($data->city);
                                                                        $city = (!empty($city)) ? $city->name : '' ;

                                                                       @endphp 
                                                                        <tr>

                                                                            <td class="text-center">{{ $count++ }}</td>
                                                                            <td class="text-center">{{ $data->ntn }}</td>
                                                                            <td class="text-center">{{ $value->cheque_date}}</td>
                                                                            <td class="text-center">{{ $data->cnic }}</td>
                                                                            <td class="text-center">{{ $data->name }}</td>
                                                                            <td class="text-center">{{ $city }}</td>
                                                                            <td class="text-center">{{ $data->address }}</td>
                                                                            <td class="text-center">{{ $data->company_status }}</td>
                                                                            <td class="text-center">{{ $data->company_name }}</td>
                                                                            <td class="text-center">{{ $Taxable_Amount->amount }}</td>
                                                                            <td class="text-center">{{ $value->amount }}</td>
                                                                        </tr>

                                                                    @endforeach