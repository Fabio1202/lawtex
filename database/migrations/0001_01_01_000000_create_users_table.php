<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUuid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('roles', function (Blueprint $table) {
            // Should have uuid
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->uuid('role_id');
            $table->uuid('user_id');
            $table->timestamps();

            $table->primary(['role_id', 'user_id']);

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Create admin role
        DB::table('roles')->insert([
            'id' => Str::uuid(),
            'name' => 'admin',
            'description' => 'Administrator',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $role = Role::where('name', 'admin')->first();

        // Create test user
        if (app()->environment('local')) {
            User::create([
                'email' => 'testy@test.com',
                'name' => 'Testy McTestface',
                'password' => \Illuminate\Support\Facades\Hash::make('test1234'),
                'email_verified_at' => now(),
            ])->roles()->attach($role);


        }

        // Create admin user for production
        if (app()->environment('production')) {

            // Create random password if not set and log it
            if(! $password = config('auth.admin_password')) {
                $password = Str::random(32);
                echo "Admin password: $password\n";
            }

            $user = User::create([
                'email' => config('auth.admin_email'),
                'name' => 'Admin',
                'password' => $password,
                'email_verified_at' => now(),
            ]);

            // Assign admin role to admin user
            $user->roles()->attach($role);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
    }
};
