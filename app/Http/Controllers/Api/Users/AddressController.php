<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use App\Services\AddressService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class AddressController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly AddressService $addressService
    ) {
    }

    public function index(): JsonResponse
    {
        try {
            $user = auth()->user();

            $addresses = $this->addressService
                ->getAddresses($user->id);

            return $this->successResponse(
                'Addresses fetched successfully',
                [
                    'addresses' => $addresses,
                    'total' => $addresses->count(),
                ]
            );
        } catch (Throwable $exception) {
            $this->logError('Get Address', $exception);

            return $this->errorResponse();
        }
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validateAddress($request);

        try {
            $address = $this->addressService->createAddress(
                $request->user()->id,
                $data
            );

            return $this->successResponse(
                'Address added successfully',
                [
                    'address' => $this->addressService
                        ->formatAddress($address),
                ],
                201
            );
        } catch (Throwable $exception) {
            $this->logError(
                'Add Address',
                $exception,
                $request->user()?->id
            );

            return $this->errorResponse();
        }
    }

    public function show(int $addressId): JsonResponse
    {
        try {
            $address = $this->addressService->getAddress(
                $addressId,
                auth()->user()->id
            );

            return $this->successResponse(
                'Address fetched successfully',
                $this->addressService->formatAddress($address)
            );
        } catch (Throwable $exception) {
            $this->logError(
                'Get Address',
                $exception,
                auth()->id(),
                $addressId
            );

            return $this->errorResponse();
        }
    }

    public function update(
        Request $request,
        int $addressId
    ): JsonResponse {
        $data = $this->validateAddress($request);

        try {
            $address = $this->addressService->updateAddress(
                $addressId,
                $request->user()->id,
                $data
            );

            return $this->successResponse(
                'Address updated successfully',
                [
                    'address' => $this->addressService
                        ->formatAddress($address),
                ]
            );
        } catch (Throwable $exception) {
            $this->logError(
                'Update Address',
                $exception,
                $request->user()?->id,
                $addressId
            );

            return $this->errorResponse();
        }
    }

    public function destroy(int $addressId): JsonResponse
    {
        try {
            $this->addressService->deleteAddress(
                $addressId,
                auth()->user()->id
            );

            return $this->successResponse(
                'Address deleted successfully'
            );
        } catch (Throwable $exception) {
            $this->logError(
                'Delete Address',
                $exception,
                auth()->id(),
                $addressId
            );

            return $this->errorResponse();
        }
    }

    public function setDefault($addressId): JsonResponse
    {
        try {
            $address = $this->addressService->setDefaultAddress(
                (int) $addressId,
                auth()->user()->id
            );

            if (!$address) {
                return $this->errorResponse(
                    'Address not found',
                    404
                );
            }

            return $this->successResponse(
                'Default address updated successfully',
                [
                    'address' => $this->addressService
                        ->formatAddress($address),
                ]
            );
        } catch (Throwable $exception) {
            $this->logError(
                'Set Default Address',
                $exception,
                auth()->id(),
                $addressId
            );

            return $this->errorResponse();
        }
    }

    private function validateAddress(Request $request): array
    {
        return $request->validate([
            'type' => 'required|in:shipping,billing',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'boolean',
        ]);
    }

    private function logError(
        string $context,
        Throwable $exception,
        $userId = null,
        $addressId = null
    ): void {
        Log::error("{$context} API Error", [
            'exception' => $exception,
            'user_id' => $userId,
            'address_id' => $addressId,
        ]);
    }
}
