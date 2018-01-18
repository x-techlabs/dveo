<?php
class UsersTableSeeder extends Seeder {

public function run()
{
    //delete users table records
    DB::table('users')->delete();
    //insert some dummy records
    DB::table('users')->insert($users = array(
    array('id' => '1','company_id' => '1','channel_id' => '0','username' => 'root','password' => '$2y$10$M.4UtoYhGqVB5Dfc9VfD.OU5ZoVGDQkbi/bcG1ne14p3udB1lGGpq','email' => 'hamlet@x-tech.am','remember_token' => 'edhuZpkWj2dwGG2fjyGDBR8ASxEJtw7OpzUJjrrRaA102lqFkJ4ll9pIepfu','token' => '','type' => '2','created_at' => '0000-00-00 00:00:00','updated_at' => '2015-05-28 13:54:00'),
    array('id' => '4','company_id' => '1','channel_id' => '0','username' => 'company4','password' => '$2y$10$lNAuflLLonQH53mJQSG4Cew2uXH3nUKUKO6EVwhLMHj8DUw//lumi','email' => 'test@test.com','remember_token' => '7eV1CoHb4rEVWeuH2qgN3ZE7A9aB1MtGIMvfuJRnBHCqBXVHfMjWNubVQTrz','token' => '','type' => '4','created_at' => '2015-05-27 18:33:34','updated_at' => '2015-05-28 13:51:24'),
    array('id' => '7','company_id' => '1','channel_id' => '0','username' => 'channel8','password' => '$2y$10$kVWZRRqV6pdaSuecIWOQnO05Hdj1ycM/7bAOZ3Y8TnPc45AnsFzf.','email' => 'channel8@test.com','remember_token' => '8t3tTY1GeihiFlpt5maVCDZIw8V4LuP0r0pnf1f63x92c3wRdIsyeGFIjG5a','token' => '','type' => '8','created_at' => '2015-05-28 13:44:06','updated_at' => '2015-05-29 09:12:56'),
    array('id' => '8','company_id' => '1','channel_id' => '0','username' => 'media16','password' => '$2y$10$1kR6IUKZmforCBke5elvsutuOowK/9a2Nj1Cr6FDWspEPhTSoQ60q','email' => 'media16@test.com','remember_token' => 'WFC14Joer0qZw6IGWjgniz2AS9PpFNHTjH8k4IsNZa36HKY0zwGj3zLIY8gE','token' => '','type' => '16','created_at' => '2015-05-28 13:46:27','updated_at' => '2015-05-28 14:14:36'),
    array('id' => '9','company_id' => '1','channel_id' => '0','username' => 'payment32','password' => '$2y$10$EDzO1D5df4keYhxlniFn4O5DtZcryRXQJTNH5PwmtcSaS8/jV2twC','email' => 'payment32@test.com','remember_token' => '','token' => '','type' => '32','created_at' => '2015-05-28 13:51:18','updated_at' => '2015-05-28 13:52:58')
    ));
    }

}
