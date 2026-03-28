<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$users = App\Models\User::select('id_user', 'nama_lengkap', 'foto')->limit(10)->get();
foreach($users as $u) {
    echo $u->id_user . ' - ' . $u->nama_lengkap . ' : ' . ($u->foto ?? 'NULL') . PHP_EOL;
}
