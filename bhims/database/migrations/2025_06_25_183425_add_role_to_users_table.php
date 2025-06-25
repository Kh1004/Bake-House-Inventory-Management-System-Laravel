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
        Schema::table('users', function (Blueprint $table) {
            // First, check if we need to migrate from role_id to role
            if (Schema::hasColumn('users', 'role_id')) {
                // Migrate existing role_id to role
                $users = \DB::table('users')->get();
                $roles = \DB::table('roles')->pluck('name', 'id');
                
                foreach ($users as $user) {
                    if (isset($roles[$user->role_id])) {
                        \DB::table('users')
                            ->where('id', $user->id)
                            ->update(['role' => $roles[$user->role_id]]);
                    } else {
                        // Default to staff if role not found
                        \DB::table('users')
                            ->where('id', $user->id)
                            ->update(['role' => 'staff']);
                    }
                }
                
                // Drop the foreign key and role_id column
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            }
            
            // Add role column if it doesn't exist
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('staff')->after('email');
            }
            
            // Add is_active column if it doesn't exist
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }
            
            // Make phone nullable if it exists
            if (Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active']);
        });
    }
};
