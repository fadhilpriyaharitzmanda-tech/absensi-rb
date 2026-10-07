<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat user guru (wali kelas)
        User::create([
            'name'     => 'Guru Pengajar',
            'username' => 'pengurus',
            'password' => bcrypt('password'),
            'role'     => 'guru',
        ]);

        User::create([
            'name'     => 'Admin Sistem',
            'username' => 'admin',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Pengurus Kelas',
            'username' => 'pengurus_kelas',
            'password' => bcrypt('password'),
            'role'     => 'pengurus_kelas',
        ]);

        $siswaData = [
            ['nis' => 9894, 'name' => 'AFFAN HUDA ADNAN SALIM'],
            ['nis' => 9895, 'name' => 'ALDY NURDIANSYAH'],
            ['nis' => 9896, 'name' => 'ALVIAN ROHMA DHONA'],
            ['nis' => 9897, 'name' => 'ANDINTA FADILA SARI'],
            ['nis' => 9898, 'name' => 'ARUM KUSUMAHATI'],
            ['nis' => 9899, 'name' => 'ASFAHRI YAHYA JIHARON'],
            ['nis' => 9900, 'name' => 'AZIZ MUSTHOFA ALIM'],
            ['nis' => 9901, 'name' => 'DAFA CANDRA KIRANA'],
            ['nis' => 9902, 'name' => 'DECO SETIAWAN'],
            ['nis' => 9903, 'name' => 'DINDA PUTRI TEGARSARI'],
            ['nis' => 9904, 'name' => 'ESSA ASTACHOIR LAZUARDI'],
            ['nis' => 9905, 'name' => 'EVA NOVIAZA ANGGARA'],
            ['nis' => 9906, 'name' => 'FADHIL PRIYA HARITZMANDA'],
            ['nis' => 9907, 'name' => 'GIOFANO IQBAL ARFIANSYA'],
            ['nis' => 9908, 'name' => 'HAIDHAR RAFFA AZIDIQ'],
            ['nis' => 9909, 'name' => 'INTAN AYU DWI PAWESTRI'],
            ['nis' => 9910, 'name' => 'JUNI SANTOSO'],
            ['nis' => 9911, 'name' => 'KARTIKA DWI WULANDARI'],
            ['nis' => 9912, 'name' => 'KEANU ATALA RAMADHAN'],
            ['nis' => 9913, 'name' => 'LINTANG CAHYA KHINANTI'],
            ['nis' => 9914, 'name' => 'MUHAMMAD ZULFIKAR ZAINURROHMAN'],
            ['nis' => 9915, 'name' => "MUTHI'AH NURUL KHASANAH"],
            ['nis' => 9916, 'name' => 'NASWA ABELITA'],
            ['nis' => 9917, 'name' => 'NASYWA ZHARIFAH'],
            ['nis' => 9918, 'name' => 'NAUFAL DZAKY DHAIFULLOH'],
            ['nis' => 9919, 'name' => 'RADITHYA ANDHIKA WIRASENA'],
            ['nis' => 9920, 'name' => 'RAFI ARSYAD ARDIANSYAH'],
            ['nis' => 9921, 'name' => 'RIZQI FAJAR SRI UTAMI'],
            ['nis' => 9922, 'name' => 'SATRIA AGIL SAPUTRA'],
            ['nis' => 9923, 'name' => 'SYAFA ADDIN MUHAMMAD'],
            ['nis' => 9924, 'name' => 'SYARIF RISKI ARULLAH'],
            ['nis' => 9925, 'name' => 'TANAYA NAZWA MUFLIHAH PRAMEST'],
            ['nis' => 9927, 'name' => 'UQASHA AHMAD MUZAFIR'],
            ['nis' => 9928, 'name' => 'YEVIA PRASTIWI'],
            ['nis' => 9929, 'name' => 'YOGA FAJAR SIDIQ'],
        ];

        $students = [];
        foreach ($siswaData as $i => $s) {
            $students[] = Student::create([
                'name'          => $s['name'],
                'nis'           => (string) $s['nis'],
                'absent_number' => $i + 1,
            ]);
        }

        // Buat sample presensi 10 hari terakhir
        $guru = User::where('username', 'pengurus')->first();

        for ($hari = 10; $hari >= 1; $hari--) {
            $date = now()->subDays($hari);

            $session = AttendanceSession::create([
                'created_by'     => $guru->id,
                'date'           => $date,
                'meeting_number' => 11 - $hari,
                'subject'        => 'Presensi Harian',
                'is_open'        => false,
            ]);

            foreach ($students as $student) {
                $random = rand(1, 100);
                if ($random <= 80) {
                    $status = 'hadir';
                } elseif ($random <= 88) {
                    $status = 'sakit';
                } elseif ($random <= 95) {
                    $status = 'izin';
                } else {
                    $status = 'alpha';
                }

                Attendance::create([
                    'session_id'  => $session->id,
                    'student_id'  => $student->id,
                    'status'      => $status,
                    'note'        => $status === 'sakit' ? 'Izin Sakit' : ($status === 'izin' ? 'Izin' : null),
                ]);
            }
        }

        echo "Seeding selesai!\n";
        echo "- 3 user (guru / admin / pengurus_kelas)\n";
        echo "- " . count($students) . " siswa\n";
        echo "- 10 hari sample presensi\n";
    }
}
