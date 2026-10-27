<?php

namespace App\Services;

use App\Models\UserAddress;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AddressService
{
    public function getAddresses(int $userId): Collection
    {
        return UserAddress::query()
            ->where('user_id', $userId)
            ->orderByDesc('is_default')
            ->latest()
            ->get()
            ->map(fn ($address) => $this->formatAddress($address));
    }

    public function createAddress(int $userId, array $data): UserAddress
    {
        return DB::transaction(function () use ($userId, $data) {
            if ($data['is_default']) {
                $this->unsetDefaultAddress($userId, $data['type']);
            }

            return UserAddress::create([
                'user_id' => $userId,
                ...$data,
            ]);
        });
    }

    public function getAddress(int $addressId, int $userId): UserAddress
    {
        return $this->findUserAddress($addressId, $userId);
    }

    public function updateAddress(
        int $addressId,
        int $userId,
        array $data
    ): UserAddress {
        return DB::transaction(function () use ($addressId, $userId, $data) {
            $address = $this->findUserAddress($addressId, $userId);

            if ($data['is_default']) {
                $this->unsetDefaultAddress(
                    $userId,
                    $data['type'],
                    $address->id
                );
            }

            $address->update($data);

            return $address;
        });
    }

    public function deleteAddress(int $addressId, int $userId): void
    {
        DB::transaction(function () use ($addressId, $userId) {
            $address = $this->findUserAddress($addressId, $userId);

            $wasDefault = $address->is_default;
            $type = $address->type;

            $address->delete();

            if ($wasDefault) {
                UserAddress::query()
                    ->where('user_id', $userId)
                    ->where('type', $type)
                    ->orderBy('id')
                    ->limit(1)
                    ->update(['is_default' => true]);
            }
        });
    }

    public function setDefaultAddress(
        int $addressId,
        int $userId
    ): ?UserAddress {
        return DB::transaction(function () use ($addressId, $userId) {
            $address = UserAddress::query()
                ->where('id', $addressId)
                ->where('user_id', $userId)
                ->first();

            if (!$address) {
                return null;
            }

            UserAddress::query()
                ->where('user_id', $userId)
                ->where('type', $address->type)
                ->update(['is_default' => false]);

            $address->update(['is_default' => true]);

            return $address;
        });
    }

    private function findUserAddress(
        int $addressId,
        int $userId
    ): UserAddress {
        $address = UserAddress::query()
            ->where('id', $addressId)
            ->where('user_id', $userId)
            ->first();

        if (!$address) {
            abort(response()->json([
                'success' => false,
                'message' => 'Address not found',
            ], 404));
        }

        return $address;
    }

    private function unsetDefaultAddress(
        int $userId,
        string $type,
        ?int $exceptId = null
    ): void {
        UserAddress::query()
            ->where('user_id', $userId)
            ->where('type', $type)
            ->when(
                $exceptId,
                fn ($query) => $query->where('id', '!=', $exceptId)
            )
            ->update(['is_default' => false]);
    }

    public function formatAddress(UserAddress $address): array
    {
        return [
            'id' => $address->id,
            'type' => $address->type,
            'full_name' => $address->full_name,
            'phone' => $address->phone,
            'address_line_1' => $address->address_line_1,
            'address_line_2' => $address->address_line_2,
            'city' => $address->city,
            'state' => $address->state,
            'postal_code' => $address->postal_code,
            'country' => $address->country,
            'is_default' => $address->is_default,
        ];
    }
}
