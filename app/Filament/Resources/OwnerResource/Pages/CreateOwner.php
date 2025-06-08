<?php

namespace App\Filament\Resources\OwnerResource\Pages;

use App\Filament\Resources\OwnerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateOwner extends CreateRecord
{
    protected static string $resource = OwnerResource::class;

    protected function handleRecordCreation(array $data): Owner {
        $user = User::create([
            'name' => $data['user']['name'],
            'email' => $data['user']['email'],
            'password' => Hash::make($data['user']['password']),
        ]);

        $user->assignRole('owner');

        return Owner::create([
            'user_id' => $user->id,
            'phone' => $data['phone'],
            'address' => $data['address'],
        ]);
    }
}
