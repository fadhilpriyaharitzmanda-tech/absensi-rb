<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ClassPin;
use App\Models\Student;
use App\Models\AttendanceSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class SecurityAndPerformanceTest extends TestCase
{
    use RefreshDatabase;
    public function test_pin_plain_column_does_not_exist_in_database(): void
    {
        $this->assertFalse(
            Schema::hasColumn('class_pins', 'pin_plain'),
            'Column pin_plain must not exist in class_pins table.'
        );
    }

    public function test_rate_limiter_throttles_pin_brute_force(): void
    {
        RateLimiter::clear('pin_verify|127.0.0.1');

        for ($i = 0; $i < 5; $i++) {
            $response = $this->from('/pin')->post('/pin', [
                'pin' => '000000',
            ]);
            $response->assertSessionHasErrors('pin');
        }

        // The 6th attempt should be blocked by RateLimiter
        $response = $this->from('/pin')->post('/pin', [
            'pin' => '000000',
        ]);
        $response->assertSessionHasErrors('pin');
        $this->assertStringContainsString('Terlalu banyak percobaan PIN salah', session('errors')->first('pin'));

        RateLimiter::clear('pin_verify|127.0.0.1');
    }

    public function test_rate_limiter_throttles_login_brute_force(): void
    {
        RateLimiter::clear('admin|127.0.0.1');

        for ($i = 0; $i < 5; $i++) {
            $response = $this->from('/login')->post('/login', [
                'username' => 'admin',
                'password' => 'wrongpassword',
            ]);
            $response->assertSessionHasErrors('username');
        }

        // The 6th attempt should be blocked
        $response = $this->from('/login')->post('/login', [
            'username' => 'admin',
            'password' => 'wrongpassword',
        ]);
        $response->assertSessionHasErrors('username');
        $this->assertStringContainsString('Terlalu banyak percobaan masuk', session('errors')->first('username'));

        RateLimiter::clear('admin|127.0.0.1');
    }

    public function test_export_period_handles_out_of_bounds_parameters_safely(): void
    {
        $admin = User::create([
            'name'     => 'Admin Test',
            'username' => 'admintest',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        // Test with extreme month and invalid year
        $response = $this->actingAs($admin)->get('/rekap?bulan=999&tahun=-10&semester=invalid');
        $response->assertStatus(200);
    }

    public function test_harian_store_saves_attendance_in_batch_via_upsert(): void
    {
        $admin = User::create([
            'name'     => 'Admin Presensi',
            'username' => 'adminpresensi',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        $student1 = Student::create([
            'name'          => 'Siswa A',
            'nis'           => '10001',
            'absent_number' => 1,
        ]);
        $student2 = Student::create([
            'name'          => 'Siswa B',
            'nis'           => '10002',
            'absent_number' => 2,
        ]);

        $response = $this->actingAs($admin)->post('/harian', [
            'presensi' => [
                $student1->id => 'hadir',
                $student2->id => 'izin',
                999999        => 'hadir', // Non-existent student should be gracefully skipped
            ],
        ]);

        $response->assertRedirect('/beranda');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('attendances', [
            'student_id' => $student1->id,
            'status'     => 'hadir',
        ]);
        $this->assertDatabaseHas('attendances', [
            'student_id' => $student2->id,
            'status'     => 'izin',
        ]);
        $this->assertDatabaseMissing('attendances', [
            'student_id' => 999999,
        ]);
    }

    public function test_sole_admin_cannot_demote_themself(): void
    {
        $admin = User::create([
            'name'     => 'Sole Admin',
            'username' => 'soleadmin',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        $response = $this->actingAs($admin)->put('/admin/users/' . $admin->id, [
            'name'     => 'Sole Admin Edited',
            'username' => 'soleadmin',
            'role'     => 'guru',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertDatabaseHas('users', [
            'id'   => $admin->id,
            'role' => 'admin',
        ]);
    }

    public function test_sole_admin_cannot_be_deleted(): void
    {
        $admin = User::create([
            'name'     => 'Sole Admin 2',
            'username' => 'soleadmin2',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        // Attempting to delete oneself
        $response = $this->actingAs($admin)->delete('/admin/users/' . $admin->id);
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }
}
