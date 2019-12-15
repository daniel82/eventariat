<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Location;
use App\User;
//use Faker\Generator as Faker;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->createAppointments();
    }

    public function createAppointments()
    {
        $locations = Location::all();
        $users = User::all();


        //$faker = Factory::create();

        foreach ( range(1,200) as $key => $index)
        {
            $random_date = $this->randomDateInRange(new DateTime("2020-01-01 00:00:00"), new DateTime("2020-03-31 00:00:00") );

            $random_date = $random_date->format("Y-m-d H:i:s");

            DB::table('appointments')->insert(
            [
                'type'                => rand(1,4),
                'date_from'           => $random_date,
                'date_to'             => $random_date,
                'description'          => null,
                'note'                 => null,
                'location_id'          => $locations->random()->id,
                'user_id'              => $users->random()->id,
                'created_by'           => 1,
                'edited_by'            => 1,
            ]);
        }
    }


    public function randomDateInRange(DateTime $start, DateTime $end)
    {
        $randomTimestamp = mt_rand($start->getTimestamp(), $end->getTimestamp());
        $randomDate = new DateTime();
        $randomDate->setTimestamp($randomTimestamp);
        return $randomDate;
    }
}
