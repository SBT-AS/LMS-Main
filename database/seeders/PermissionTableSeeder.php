<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions = [
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete', 'roles.permissions',
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'categories.view', 'categories.create', 'categories.edit', 'categories.delete',
            'courses.view', 'courses.create', 'courses.edit', 'courses.delete',
            'course-materials.view', 'course-materials.create', 'course-materials.edit', 'course-materials.delete',
            'quizzes.view', 'quizzes.create', 'quizzes.edit', 'quizzes.delete',
            'quiz-questions.view', 'quiz-questions.create', 'quiz-questions.edit', 'quiz-questions.delete',
        ];


        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }


        $admin = Role::firstOrCreate(['name' => 'admin']);
        $student = Role::firstOrCreate(['name' => 'student']);



        $user = User::updateOrCreate(
            ['email' => 'super.admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );


        $user->syncRoles($admin);
        $admin->givePermissionTo(Permission::all());
    }
}
