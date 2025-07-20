<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            // Basic Customer Information from image_6d3c9a.png
            $table->string('name')->comment('Business or person name');
            $table->string('email')->nullable()->unique()->comment('Customer email address');
            $table->string('phone')->nullable()->comment('Customer phone number');
            $table->string('first_name')->nullable()->comment('First name if customer is a person');
            $table->string('last_name')->nullable()->comment('Last name if customer is a person');
            $table->string('profile_image')->nullable()->comment('');
            $table->decimal('opening_balance', 15, 2)->default(0.00)->comment('Initial balance for the customer');
            $table->date('opening_balance_as_of')->nullable()->comment('Date as of which the opening balance is recorded');

            // Address Information from image_6d3d31.png
            $table->text('address')->nullable()->comment('Full street address');
            $table->string('city')->nullable()->comment('City of the customer');
            $table->string('state')->nullable()->comment('State/Province of the customer');
            $table->string('zip_code')->nullable()->comment('Postal or Zip code');
            $table->string('country')->nullable()->comment('Country of the customer');

            $table->string('fax')->nullable()->comment('Fax number');
            $table->string('website')->nullable()->comment('Customer website URL');
            $table->text('notes')->nullable()->comment('General notes about the customer');

            // Additional fields for accounting software
            $table->enum('customer_type', ['business', 'person'])->default('business')->comment('Type of customer: business or individual');
            $table->boolean('status')->default(1)->comment('Customer status (e.g., active, inactive)');
            $table->foreignId('user_id')->nullable()->constrained('admins')->onDelete('set null')->comment('ID of the user who created this customer record');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
