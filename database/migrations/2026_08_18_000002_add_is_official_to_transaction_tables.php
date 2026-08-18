<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsOfficialToTransactionTables extends Migration
{
    /**
     * List of all transaction tables across Finance, Purchase, Sale, Production & Stock.
     */
    protected $tables = [
        // Finance
        'jvs',
        'jv_data',
        'new_jvs',
        'new_jv_data',
        'pvs',
        'pvs_data',
        'new_pv',
        'new_pv_data',
        'rvs',
        'rvs_data',
        'new_rvs',
        'new_rv_data',
        'contra',
        'contra_data',
        'transactions',
        'purchase_voucher',
        'purchase_voucher_data',
        'new_purchase_voucher',
        'new_purchase_voucher_data',
        'new_purchase_voucher_payment',
        'bank_reconciliations',

        // Purchase
        'purchase_request',
        'purchase_request_data',
        'quotation',
        'quotation_data',
        'goods_receipt_note',
        'grn_data',
        'demand',
        'demand_data',

        // Sale
        'sale_quotation',
        'sale_quotation_data',
        'sales_order',
        'sales_order_data',
        'delivery_note',
        'delivery_note_data',
        'invoice',
        'invoice_data',
        'sales_tax_invoice',
        'sales_tax_invoice_data',
        'dispatches',
        'dispatch_datas',
        'credit_note',
        'credit_note_data',

        // Production & Stock
        'production_order',
        'production_order_data',
        'production_bom',
        'production_bom_data',
        'production_work_order',
        'production_work_order_data',
        'job_order',
        'job_order_data',
        'production_plane',
        'production_plane_data',
        'material_requisitions',
        'material_requisition_datas',
        'stock',
        'issuance',
        'issuance_data',
        'store_challan',
        'store_challan_data',
        'store_challan_return',
        'packings',
        'packing_datas',
        'gate_pass',
        'gate_pass_data',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connections = array_filter(['mysql', 'mysql2'], function($conn) {
            return config("database.connections.{$conn}") !== null;
        });

        foreach ($connections as $conn) {
            foreach ($this->tables as $table) {
                if (Schema::connection($conn)->hasTable($table)) {
                    if (!Schema::connection($conn)->hasColumn($table, 'is_official')) {
                        Schema::connection($conn)->table($table, function (Blueprint $t) use ($table) {
                            $t->tinyInteger('is_official')->default(1)->nullable()->index('idx_' . substr($table, 0, 20) . '_is_official');
                        });
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $connections = array_filter(['mysql', 'mysql2'], function($conn) {
            return config("database.connections.{$conn}") !== null;
        });

        foreach ($connections as $conn) {
            foreach ($this->tables as $table) {
                if (Schema::connection($conn)->hasTable($table)) {
                    if (Schema::connection($conn)->hasColumn($table, 'is_official')) {
                        Schema::connection($conn)->table($table, function (Blueprint $t) {
                            $t->dropColumn('is_official');
                        });
                    }
                }
            }
        }
    }
}
