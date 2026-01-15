
<?php use App\Helpers\CommonHelper; ?>

<?php $count=1; ?>
@foreach ($data->get() as $row)
<tr class="text-center">
    <td>{{ $count++ }}</td>
    <td>{{ strtoupper($row->demand_no) }}</td>
    <td>{{ CommonHelper::changeDateFormat($row->demand_date) }}</td>
    <td>{{ $row->slip_no }}</td>
    <td>{{ CommonHelper::get_sub_dept_name($row->sub_department_id) }}</td>
    <td><a style="padding: 7px; font-size: 12px;"href="{{ url('quotation/quotation_form/'.$row->id) }}" type="button" class="btn btn-sm btn-success">Create Quotation</a></td>
</tr>

@endforeach