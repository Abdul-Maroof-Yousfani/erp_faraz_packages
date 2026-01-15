<!DOCTYPE html>
<html>
<head>
    </head>
<body>
<?php
        use App\Helpers\CommonHelper;

 $gi_date=$sales_tax_invoice->gi_date;
$gi_date= date("jS F Y", strtotime($gi_date));

        $data=CommonHelper::byers_name($sales_tax_invoice->buyers_id);
?>

<b>{{$gi_date}}</b>
</br></br>
<p><h4><b>{{ucwords($data->name)}}</b></h4></p>
<p>Address: {{ucwords($data->address)}}</p>
<p>NTN: {{$data->cnic_ntn}}</p>
</br>

<h4 style="text-align: center">UNDERTAKING</h4>

<p style="font-size: large">
This is to certify that we, M/s. Gudia (Private) Limited, Karachi do hereby state on solemn affirmation  that the goods supplied by us against Invoice No. <b>{{$sales_tax_invoice->gi_no}}</b> dated <b>{{$gi_date}}</b> has been  imported by us and that the income tax u/s 148 of the Income Tax Ordinance, 2001 has been duly paid by us.
</br></br>

Hence, in pursuance of clause 47(A) of Part IV of Second Schedule of Income Tax Ordinance, 2001 you shall not deduct withholding tax u/s 153 of the Income Tax Ordinance, 2001 for goods supplied against above mentioned Invoice.
</br> </br>
Thanking You,

    </br> </br>
Yours faithfully,
    </br> </br>

For <b>Gudia (Private) Limited</b>
</p>


</br>






________________________
</body>
<script></script>
</html>