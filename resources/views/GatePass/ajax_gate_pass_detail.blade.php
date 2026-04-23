<?php
use App\Helpers\CommonHelper;
?>

<div class="gate-pass-container" style=" background: white; border: 2px solid #333; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
    
    <!-- Print Button -->
    <div style="text-align: right; padding: 15px 25px 0 25px;">
        <button onclick="printGatePass()" 
                style="background: #d00; color: white; border: none; padding: 10px 20px; font-size: 16px; font-weight: bold; border-radius: 5px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
            <span>🖨️</span> Print Gate Pass
        </button>
    </div>

    <!-- Header -->
    <div class="gateprint" style="display: flex; justify-content: space-between; align-items: center; padding: 20px 25px; border-bottom: 2px solid #333; position: relative;">
        
        <!-- Logo + Company Info -->
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 80px; height: 80px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 8px; overflow: hidden;">
                <img src="{{ asset('logoo.png') }}" alt="Logo" style="max-width: 100%; max-height: 100%;">
            </div>
        </div>

        <div style="text-align: center; flex: 1;">
            <h2 style="margin: 0; font-size: 26px; font-weight: bold;">Gate Pass</h2>
            <p style="margin: 5px 0 0 0; font-size: 15px;">
                Plot No. F-188-E, Near D-1 Bus Stop SITE Area, Karachi<br>
                Ph: 021-32544444 • Cell: 0321 3254444<br>
                Email: farazpackages@gmail.com
            </p>
        </div>

        <div style="text-align: right;">
            <strong style="font-size: 18px;">Outward</strong><br>
            <span style="font-size: 22px; font-weight: bold; color: #d00;">{{ $gatePass->gate_pass_no ?? '11151' }}</span>
        </div>
    </div>

    <!-- Details Row -->
    <div style="padding: 20px 25px; border-bottom: 1px solid #ddd; font-size: 15px;">
        <div style="display: flex; flex-wrap: wrap; gap: 25px;">
            <div style="flex: 1;">
                <strong>Time:</strong> 
                <span>{{ !empty($gatePass->gate_pass_time) ? date('h:i A', strtotime($gatePass->gate_pass_time)) : '__________' }}</span>
            </div>
            
            <div style="flex: 1;">
                <strong>Date:</strong> 
                <span>{{ !empty($gatePass->gate_pass_date) ? CommonHelper::changeDateFormat($gatePass->gate_pass_date) : '__________' }}</span>
            </div>
            
            <div style="flex: 1;">
                <strong>Vehicle No:</strong> 
                <span>{{ $gatePass->vehicle_no ?: '__________' }}</span>
            </div>

            <div style="flex: 1;" class="hide">
                <strong>Source No:</strong>
                <span>{{ $gatePass->source_no ?: '__________' }}</span>
            </div>

            @if(!empty($partyName))
            <div style="flex: 1;">
                <strong>Customer / Party:</strong>
                <span>{{ $partyName }}</span>
            </div>
            @endif

            @if(!empty($gatePass->driver_name))
            <div style="flex: 1;">
                <strong>Driver Name:</strong>
                <span>{{ $gatePass->driver_name }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Main Table -->
    <div style="padding: 0 25px 25px;">
        <table style="white-space: nowrap; width: 100%;">
            <thead>
                <tr style="background: #f8f8f8;">
                    <th style="border: 1px solid #333; padding: 12px; text-align: left; width: 45%;">DESCRIPTION</th>
                    <th style="border: 1px solid #333; padding: 12px; text-align: center; width: 15%;">QUANTITY</th>
                    <th style="border: 1px solid #333; padding: 12px; text-align: center; width: 15%;">UOM</th>
                    <th style="border: 1px solid #333; padding: 12px; text-align: left; width: 25%;">PURPOSE</th>
                    <th style="border: 1px solid #333; padding: 12px; text-align: left; width: 25%;">CUSTOMER / PARTY</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <?php
                    $partyName = '';
                    if (property_exists($item, 'party_id') && !empty($item->party_id)) {
                        $partyName = DB::connection('mysql2')
                            ->table('customers')
                            ->where('id', $item->party_id)
                            ->value('name') ?? '';
                    } elseif (!empty($gatePass->party_id)) {
                        $partyName = DB::connection('mysql2')
                            ->table('customers')
                            ->where('id', $gatePass->party_id)
                            ->value('name') ?? '';
                    }
                ?>
                
                <tr>
                    <td style="border: 1px solid #333; padding: 12px; font-weight: 500;">
                        {{ $item->item_name ?: 'The Names' }}
                    </td>
                    <td style="border: 1px solid #333; padding: 12px; text-align: center;">
                        {{ number_format((float) $item->qty, 2) }}
                    </td>
                    <td style="border: 1px solid #333; padding: 12px; text-align: center;">
                        {{ $item->uom ?? '' }}
                    </td>
                    <td style="border: 1px solid #333; padding: 12px;">
                        {{ $item->purpose ?? '' }}
                    </td>
                    <td style="border: 1px solid #333; padding: 12px;">
                        {{ $partyName ?: '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td style="border: 1px solid #333; padding: 12px; font-weight: 500;">
                        {{ $gatePass->description ?: 'The Names' }}
                    </td>
                    <td style="border: 1px solid #333; padding: 12px;"></td>
                    <td style="border: 1px solid #333; padding: 12px;"></td>
                    <td style="border: 1px solid #333; padding: 12px;"></td>
                    <td style="border: 1px solid #333; padding: 12px;"></td>
                </tr>
                @endforelse

                <!-- Empty rows for consistent look -->
                @for($i = count($items); $i < 6; $i++)
                <tr>
                    <td style="border: 1px solid #333; padding: 12px; height: 38px;"></td>
                    <td style="border: 1px solid #333; padding: 12px;"></td>
                    <td style="border: 1px solid #333; padding: 12px;"></td>
                    <td style="border: 1px solid #333; padding: 12px;"></td>
                    <td style="border: 1px solid #333; padding: 12px;"></td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>

    <!-- Signature Section -->
    <div style="display: flex; justify-content: space-between; padding: 25px; border-top: 2px solid #333; margin-top: 10px; font-size: 14px;">
        <div style="text-align: center; width: 18%;">
            <div style="border-top: 1px solid #333; margin-bottom: 8px; padding-top: ;"></div>
            Issued by
        </div>
        <div style="text-align: center; width: 18%;">
            <div style="border-top: 1px solid #333; margin-bottom: 8px; padding-top: ;"></div>
            Production Manager
        </div>
        <div style="text-align: center; width: 18%;">
            <div style="border-top: 1px solid #333; margin-bottom: 8px; padding-top: ;"></div>
            Verify Manager
        </div>
        <div style="text-align: center; width: 18%;">
            <div style="border-top: 1px solid #333; margin-bottom: 8px; padding-top: ;"></div>
            Receiving Signature
        </div>
        <div style="text-align: center; width: 18%;">
            <div style="border-top: 1px solid #333; margin-bottom: 8px; padding-top: ;"></div>
            Approved CEO
        </div>
    </div>

</div>

<style>
    .gate-pass-container {
        font-family: Arial, sans-serif;
        color: #222;
    }
    .gate-pass-container table th {
        font-weight: bold;
        font-size: 15px;
    }

    /* Print Styles - Hide button and optimize for printing */
    @media print {

        @page{size:A4;margin:0.5mm 0.5mm 0.5mm 0.5mm !important;}
        .gate-pass-container button {
            display: none !important;
        }
        body * {
            visibility: hidden;
            width: 100% !important;
        }

    .gateprint{
        padding: 5px 5px !important;
     
    }

    .gate-pass-container{
   margin: -75px 0px !important;
    }
        .gate-pass-container,
        .gate-pass-container * {
            visibility: visible;
        }
        .gate-pass-container {
            /* position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none;
            margin: 0;
            padding: 0; */
        }
    }
</style>

<script>
function printGatePass() {
    window.print();
}
</script>
