<?php
    
    use Illuminate\Database\Seeder;
    use App\Models\User;
    
    class UserSeeder extends Seeder {
        
        public function run(): void {
            factory(User::class, 100)->create();
        }
    }