<?php

use Illuminate\Database\Seeder;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed user as administrator
        $administrator = new \App\User;
        $administrator->username = "administrator";
        $administrator->name = "Site Administrator";
        $administrator->email = "administrator@toko-buku.test";
        $administrator->roles = json_encode(["ADMIN"]);
        $administrator->password = \Hash::make("administrator");
        $administrator->avatar = "saat-ini-tidak-ada.png";
        $administrator->address = "Yogyakarta";
        $administrator->phone = "081228892803";

        $administrator->save();
        $this->command->info("User admin berhasil di insert");
    }
}
