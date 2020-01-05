<?php

use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i <= 150000; $i++) {
            try {
                factory(App\User::class, 1)->create();
            } catch (\Exception $e) {

            }
        }
    }
}
