<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\StudentProfile;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('password');

        // ==========================================
        // 1. DATA DUMMY GURU (RELAVAN PENGAJAR)
        // ==========================================
        $dummyTeachers = [
            [
                'name' => 'Siti Nurhaliza, S.Pd.',
                'email' => 'guru.siti@sevima.test',
                'subject' => 'Matematika',
                'origin_location' => 'Bandung, Jawa Barat',
                'institution_origin' => 'Institut Teknologi Bandung (ITB)',
                'bio' => 'Lulusan Pendidikan Matematika ITB dengan pengalaman 4 tahun mengajar konsep dasar aljabar, kalkulus, dan logika numerik dengan metode interaktif yang menyenangkan.',
                'rating' => 4.95,
                'total_reviews' => 18,
                'schedules' => [
                    ['day_offset' => 1, 'start' => '09:00', 'end' => '10:30'],
                    ['day_offset' => 2, 'start' => '13:30', 'end' => '15:00'],
                    ['day_offset' => 3, 'start' => '15:30', 'end' => '17:00'],
                ],
            ],
            [
                'name' => 'Rahmat Hidayat, M.Ed.',
                'email' => 'guru.rahmat@sevima.test',
                'subject' => 'Bahasa Inggris',
                'origin_location' => 'Yogyakarta, DI Yogyakarta',
                'institution_origin' => 'Universitas Gadjah Mada (UGM)',
                'bio' => 'Relawan edukasi bahasa asing dengan fokus pada English speaking, basic grammar, dan persiapan literasi global untuk adik-adik di daerah pelosok.',
                'rating' => 4.90,
                'total_reviews' => 14,
                'schedules' => [
                    ['day_offset' => 1, 'start' => '10:00', 'end' => '11:30'],
                    ['day_offset' => 3, 'start' => '08:30', 'end' => '10:00'],
                    ['day_offset' => 4, 'start' => '14:00', 'end' => '15:30'],
                ],
            ],
            [
                'name' => 'Dewi Lestari, S.Si.',
                'email' => 'guru.dewi@sevima.test',
                'subject' => 'Ilmu Pengetahuan Alam (IPA)',
                'origin_location' => 'Surabaya, Jawa Timur',
                'institution_origin' => 'Universitas Airlangga (UNAIR)',
                'bio' => 'Pengajar biologi dan sains terapan yang gemar mengajak siswa bereksplorasi dengan fenomena alam sekitar dan membangun rasa ingin tahu anak terhadap sains.',
                'rating' => 4.88,
                'total_reviews' => 10,
                'schedules' => [
                    ['day_offset' => 2, 'start' => '09:00', 'end' => '10:30'],
                    ['day_offset' => 4, 'start' => '10:00', 'end' => '11:30'],
                    ['day_offset' => 5, 'start' => '13:00', 'end' => '14:30'],
                ],
            ],
            [
                'name' => 'Fajar Pratama, S.T.',
                'email' => 'guru.fajar@sevima.test',
                'subject' => 'Fisika & Teknologi',
                'origin_location' => 'Jakarta Selatan, DKI Jakarta',
                'institution_origin' => 'Universitas Indonesia (UI)',
                'bio' => 'Alumni Teknik Elektro UI yang berdedikasi mengajarkan fisika dasar, logika teknologi komputer, dan pemrograman dasar bagi siswa jenjang SMP dan SMA.',
                'rating' => 4.92,
                'total_reviews' => 12,
                'schedules' => [
                    ['day_offset' => 1, 'start' => '14:00', 'end' => '15:30'],
                    ['day_offset' => 3, 'start' => '16:00', 'end' => '17:30'],
                ],
            ],
            [
                'name' => 'Maya Anggraini, S.Pd.',
                'email' => 'guru.maya@sevima.test',
                'subject' => 'Bahasa Indonesia',
                'origin_location' => 'Malang, Jawa Timur',
                'institution_origin' => 'Universitas Negeri Malang (UM)',
                'bio' => 'Spesialis literasi membaca, menulis kreatif, dan pemahaman tata bahasa Indonesia yang aktif membimbing siswa mengasah kepercayaan diri bertutur kata.',
                'rating' => 4.85,
                'total_reviews' => 8,
                'schedules' => [
                    ['day_offset' => 2, 'start' => '10:00', 'end' => '11:30'],
                    ['day_offset' => 4, 'start' => '15:00', 'end' => '16:30'],
                ],
            ],
        ];

        foreach ($dummyTeachers as $t) {
            $user = User::updateOrCreate(
                ['email' => $t['email']],
                [
                    'name' => $t['name'],
                    'password' => $defaultPassword,
                    'role' => 'guru',
                    'email_verified_at' => now(),
                ]
            );

            $teacher = Teacher::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'subject' => $t['subject'],
                    'origin_location' => $t['origin_location'],
                    'institution_origin' => $t['institution_origin'],
                    'bio' => $t['bio'],
                    'cv_path' => $t['cv_path'] ?? 'https://drive.google.com/file/d/dummy-cv-relawan-' . $user->id . '/view',
                    'rating' => $t['rating'],
                    'total_reviews' => $t['total_reviews'],
                    'verification_status' => 'approved',
                    'is_verified' => true,
                    'verified_at' => now(),
                ]
            );

            // Tambahkan jadwal tersedia jika belum ada jadwal di masa depan
            foreach ($t['schedules'] as $sch) {
                $scheduleDate = Carbon::today()->addDays($sch['day_offset']);
                $startTime = Carbon::parse($scheduleDate->format('Y-m-d') . ' ' . $sch['start']);
                $endTime = Carbon::parse($scheduleDate->format('Y-m-d') . ' ' . $sch['end']);

                Schedule::firstOrCreate(
                    [
                        'teacher_id' => $teacher->id,
                        'start_time' => $startTime,
                    ],
                    [
                        'end_time' => $endTime,
                        'is_available' => true,
                    ]
                );
            }
        }

        // ==========================================
        // 2. DATA DUMMY SISWA (WILAYAH 3T & TERPENCIL)
        // ==========================================
        $dummyStudents = [
            [
                'name' => 'Maria Magdalena Silaban',
                'email' => 'siswa.maria@sevima.test',
                'school_name' => 'SMP Negeri 1 Asmat',
                'grade_level' => 'Kelas 8 SMP',
                'region_location' => 'Kabupaten Asmat, Papua Selatan',
                'guardian_phone' => '081234567801',
                'learning_goals' => 'Ingin memperdalam pemahaman materi matematika aljabar dan persiapan mengikuti olimpiade sains daerah.',
            ],
            [
                'name' => 'Yohanes Alor',
                'email' => 'siswa.yohanes@sevima.test',
                'school_name' => 'SD Inpres Kalabahi 2',
                'grade_level' => 'Kelas 5 SD',
                'region_location' => 'Kabupaten Alor, Nusa Tenggara Timur',
                'guardian_phone' => '081234567802',
                'learning_goals' => 'Ingin belajar dasar berhitung cepat dan meningkatkan kemampuan percakapan bahasa Inggris sederhana.',
            ],
            [
                'name' => 'Aisyah Putri Malinau',
                'email' => 'siswa.aisyah@sevima.test',
                'school_name' => 'SMA Negeri 1 Malinau',
                'grade_level' => 'Kelas 11 SMA',
                'region_location' => 'Kabupaten Malinau, Kalimantan Utara',
                'guardian_phone' => '081234567803',
                'learning_goals' => 'Persiapan ujian masuk perguruan tinggi negeri (SNBT) bidang fisika dan matematika saintek.',
            ],
            [
                'name' => 'Karel Wenda',
                'email' => 'siswa.karel@sevima.test',
                'school_name' => 'SMP Satap Wamena',
                'grade_level' => 'Kelas 9 SMP',
                'region_location' => 'Kabupaten Jayawijaya, Papua Pegunungan',
                'guardian_phone' => '081234567804',
                'learning_goals' => 'Ingin bimbingan belajar intensif menjelang kelulusan sekolah dan penguatan literasi sains.',
            ],
            [
                'name' => 'Nurul Hidayah Morotai',
                'email' => 'siswa.nurul@sevima.test',
                'school_name' => 'SD Negeri Unggulan Pulau Morotai',
                'grade_level' => 'Kelas 6 SD',
                'region_location' => 'Kabupaten Pulau Morotai, Maluku Utara',
                'guardian_phone' => '081234567805',
                'learning_goals' => 'Mempersiapkan diri masuk ke jenjang SMP favorit dan memperlancar kemampuan membaca serta menulis kritis.',
            ],
        ];

        foreach ($dummyStudents as $s) {
            $user = User::updateOrCreate(
                ['email' => $s['email']],
                [
                    'name' => $s['name'],
                    'password' => $defaultPassword,
                    'role' => 'siswa',
                    'email_verified_at' => now(),
                ]
            );

            StudentProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'school_name' => $s['school_name'],
                    'grade_level' => $s['grade_level'],
                    'region_location' => $s['region_location'],
                    'guardian_phone' => $s['guardian_phone'],
                    'learning_goals' => $s['learning_goals'],
                ]
            );
        }
    }
}
