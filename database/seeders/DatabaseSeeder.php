<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\AdminUser;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Therapy;
use App\Models\TherapyPhoto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        AdminUser::updateOrCreate(['email' => 'demo1@demo.com'], ['name' => 'Naeim Jahanifar', 'avatarUrl' => '', 'password' => 'demo']);

        // Clear Tables
        DB::table('doctors')->truncate();
        DB::table('patients')->truncate();
        DB::table('therapies')->truncate();
        DB::table('therapy_photos')->truncate();
        DB::table('appointments')->truncate();


        echo " --- Doctor --- \n";
        Doctor::factory(20)->create();
        echo " --- Patient --- \n";
        Patient::factory(100)->create();
        echo " --- Therapy --- \n";
        Therapy::factory(40)->create();
        TherapyPhoto::factory(140)->create();
        echo " --- Appointment --- \n";
        Appointment::factory(140)->create();


        $n = count( Appointment::all() );
        echo " --- {$n}\n";
    }

}
