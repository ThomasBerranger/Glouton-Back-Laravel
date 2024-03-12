<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpirationDate\StoreExpirationDateRequest;
use App\Http\Requests\ExpirationDate\UpdateExpirationDateRequest;
use App\Http\Resources\ExpirationDatesResource;
use App\Models\ExpirationDate;
use Illuminate\Http\Response;

class ExpirationDateController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ExpirationDate::class, 'expirationDate');
    }

    public function store(StoreExpirationDateRequest $request): ExpirationDatesResource
    {
        $expirationDate = ExpirationDate::create($request->validated());

        return ExpirationDatesResource::make($expirationDate);
    }

    public function update(UpdateExpirationDateRequest $request, ExpirationDate $expirationDate): ExpirationDatesResource
    {
        $expirationDate->update($request->validated());

        return ExpirationDatesResource::make($expirationDate);
    }

    public function destroy(ExpirationDate $expirationDate): Response
    {
        $expirationDate->delete();

        return response()->noContent();
    }
}
