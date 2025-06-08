<?php

namespace App\Filament\Resources\VetResource\Pages;

use App\Filament\Resources\VetResource;
use Filament\Actions;
use App\Models\Vet;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Filament\Resources\Pages\CreateRecord;

class CreateVet extends CreateRecord
{
    protected static string $resource = VetResource::class;
    protected function handleRecordCreation(array $data): Vet {
        $user = User::create([
            
            'name' => $data['user']['name'],
            'email' => $data['user']['email'],
            'password' => Hash::make($data['user']['password']),
        ]);

        $user->assignRole('vet');
        return Vet::create([
            'user_id' => $user->id,
            'phone' => $data['phone'],
            'birthdate' => $data['birthdate'],
            'speciality' => $data['speciality'],
            'license_number' => $data['license_number'],
            'bio' => $data['bio'] ?? null,
        ]);
    }
}
