<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $siswa = [
            ['name' => 'Affan Huda Adnan Salim', 'nis' => '0012345601'],
            ['name' => 'Aldy Nurdiansyah', 'nis' => '0012345602'],
            ['name' => 'Alfian Rohma Dhona', 'nis' => '0012345603'],
            ['name' => 'Andinta Fadila Sari', 'nis' => '0012345604'],
            ['name' => 'Arum Kusumahati', 'nis' => '0012345605'],
            ['name' => 'Asfahri Yahya Jiharon', 'nis' => '0012345606'],
            ['name' => 'Aziz Mustofa Alim', 'nis' => '0012345607'],
            ['name' => 'Dafa Candra Kirana', 'nis' => '0012345608'],
            ['name' => 'Deco Setiawan', 'nis' => '0012345609'],
            ['name' => 'Dinda Putri Tegarsari', 'nis' => '0012345610'],
            ['name' => 'Essa Astachoir Lazuardi', 'nis' => '0012345611'],
            ['name' => 'Eva Noviaza Anggara', 'nis' => '0012345612'],
            ['name' => 'Fadhil Priya Hartizmanda', 'nis' => '0012345613'],
            ['name' => 'Giofano Iqbal Arfiansya', 'nis' => '0012345614'],
            ['name' => 'Haidar Raffa Azidiq', 'nis' => '0012345615'],
            ['name' => 'Intan Ayu Dwi Pawestri', 'nis' => '0012345616'],
            ['name' => 'Juni Santoso', 'nis' => '0012345617'],
            ['name' => 'Kartika Dwi Wulandari', 'nis' => '0012345618'],
            ['name' => 'Keanu Atala Ramadhan', 'nis' => '0012345619'],
            ['name' => 'Lintang Cahya Khinanti', 'nis' => '0012345620'],
            ['name' => 'Muhammad Zulfikar Zainurrohman', 'nis' => '0012345621'],
            ['name' => 'Muthiah Nurul Khasanah', 'nis' => '0012345622'],
            ['name' => 'Naswa Abelita', 'nis' => '0012345623'],
            ['name' => 'Nasywa Zharifah', 'nis' => '0012345624'],
            ['name' => 'Naufal Dzaky Dhaifulloh', 'nis' => '0012345625'],
            ['name' => 'Radithya Andhika Wirasena', 'nis' => '0012345626'],
            ['name' => 'Rafi Arsyad Ardiansyah', 'nis' => '0012345627'],
            ['name' => 'Rizqi Fajar Sri Utami', 'nis' => '0012345628'],
            ['name' => 'Satria Agil Saputra', 'nis' => '0012345629'],
            ['name' => 'Syafa Addin Muhammad', 'nis' => '0012345630'],
            ['name' => 'Syarif Riski Arullah', 'nis' => '0012345631'],
            ['name' => 'Tanaya Nazwa Muflihah Pramesti', 'nis' => '0012345632'],
            ['name' => 'Uqasha Ahmad Musafir', 'nis' => '0012345633'],
            ['name' => 'Yevia Prastiwi', 'nis' => '0012345634'],
            ['name' => 'Yoga Fajar Sidiq', 'nis' => '0012345635'],
        ];

        foreach ($siswa as $index => $s) {
            $absentNumber = $index + 1;

            $user = User::where('name', $s['name'])->first();

            Student::create([
                'user_id'        => $user?->id,
                'name'           => $s['name'],
                'nis'            => $s['nis'],
                'absent_number'  => $absentNumber,
                'photo'          => null,
            ]);
        }
    }
}
