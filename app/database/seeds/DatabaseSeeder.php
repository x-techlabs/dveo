<?php
/**
 * Created by PhpStorm.
 * User: ls
 * Date: 6/3/15
 * Time: 5:53 PM
 */

//app/database/seeds/DatabaseSeeder.php
class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $this->call(TvappPlatformsSeeder::class);
        //call uses table seeder class
//        $this->call('UsersTableSeeder');
        //this message shown in your terminal after running db:seed command
//        $this->command->info("Users table seeded :)");
    }

}
