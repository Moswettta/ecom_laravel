<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40)->unique();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 120);
            $table->string('username', 60)->unique();
            $table->string('email', 150)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email_verification_token', 64)->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('password');
            $table->foreignId('role_id')->constrained('roles');
            $table->string('status', 20)->default('active');
            $table->string('profile_image')->nullable();
            $table->boolean('must_change_password')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key', 100)->unique();
            $table->text('setting_value')->nullable();
            $table->timestamp('updated_at')->useCurrent();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->text('description')->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('phone', 40)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('address')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name', 150);
            $table->string('sku', 80)->unique();
            $table->string('barcode', 80)->nullable()->unique();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('department', 20)->default('Shoes');
            $table->string('brand', 100)->nullable();
            $table->string('gender', 40)->nullable();
            $table->string('size', 30)->nullable();
            $table->string('color', 60)->nullable();
            $table->decimal('buying_price', 12, 2)->default(0);
            $table->decimal('selling_price', 12, 2)->default(0);
            $table->integer('quantity')->default(0);
            $table->integer('reorder_level')->default(5);
            $table->string('product_image')->nullable();
            $table->text('description')->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamps();
        });

        Schema::create('product_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('size_label', 40);
            $table->integer('quantity')->default(0);
            $table->timestamps();
            $table->unique(['product_id', 'size_label']);
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->string('name', 120);
            $table->string('phone', 40)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->string('type', 40);
            $table->integer('quantity');
            $table->string('reference', 100)->nullable();
            $table->string('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no', 80)->unique();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('amount_tendered', 12, 2)->default(0);
            $table->decimal('change_due', 12, 2)->default(0);
            $table->string('payment_method', 30);
            $table->string('payment_status', 20)->default('PENDING');
            $table->string('cash_approval_status', 20)->default('NOT_REQUIRED');
            $table->unsignedBigInteger('cash_approved_by')->nullable();
            $table->timestamp('cash_approved_at')->nullable();
            $table->string('transaction_reference', 120)->nullable();
            $table->string('notes')->nullable();
            $table->boolean('delivery_enabled')->default(false);
            $table->string('delivery_area', 120)->nullable();
            $table->string('delivery_address')->nullable();
            $table->decimal('delivery_latitude', 10, 7)->nullable();
            $table->decimal('delivery_longitude', 10, 7)->nullable();
            $table->decimal('delivery_charge', 12, 2)->default(0);
            $table->string('delivery_person', 120)->nullable();
            $table->string('delivery_phone', 40)->nullable();
            $table->unsignedInteger('delivery_zone_id')->nullable();
            $table->timestamp('voided_at')->nullable();
            $table->foreignId('voided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('void_reason')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->string('size_label', 40)->nullable();
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('line_total', 12, 2);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
            $table->string('method', 30);
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('status', 30)->default('PENDING');
            $table->string('transaction_reference', 120)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->string('destination', 120)->unique();
            $table->decimal('charge', 12, 2)->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('delivery_zones');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('inventory_transactions');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('product_sizes');
        Schema::dropIfExists('products');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
};
