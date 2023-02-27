<?php
    
    namespace App\Repositories;
    
    use App\Models\User;
    
    class UserRepository {
        
        public function findByEmail($email): User {
            /** @var User $user */
            $user = User::query()
                        ->where('email', 'ILIKE', $email)
                        ->firstOrFail();
            return $user;
        }
        
        
        public function findById($id) {
            $user = User::query()->where('id', $id)->first();
            return $user;
        }
    }
