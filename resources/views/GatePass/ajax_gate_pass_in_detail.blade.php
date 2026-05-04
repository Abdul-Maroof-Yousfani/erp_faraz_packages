@include('GatePass.ajax_gate_pass_detail', ['gatePass' => $gatePass, 'items' => $items, 'sourceTypeLabel' => 'Manual', 'partyName' => '', 'm' => $m])
<div style="padding: 15px 25px; border-top: 1px solid #ddd;">
    <strong>Gate Pass IN Description:</strong>
    <div style="margin-top: 6px;">{{ $gatePass->gate_pass_in_description ?: '-' }}</div>
</div>
