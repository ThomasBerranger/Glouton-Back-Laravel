<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpirationDate\StoreExpirationDateRequest;
use App\Http\Resources\ExpirationDatesResource;
use App\Models\ExpirationDate;
use Illuminate\Http\Request;

class ExpirationDateController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ExpirationDate::class, 'product');
    }

    //    public function index(): void
    //    {
    //    }

    public function store(StoreExpirationDateRequest $request): ExpirationDatesResource
    {
        $expirationDate = ExpirationDate::create($request->validated());

        return ExpirationDatesResource::make($expirationDate);
    }

    //    public function show(ExpirationDatePolicy $expirationDate): void
    //    {
    //    }

    //    public function edit(ExpirationDatePolicy $expirationDate): void
    //    {
    //    }

    //    public function update(Request $request, ExpirationDatePolicy $expirationDate): void
    //    {
    //    }

    //    public function destroy(ExpirationDatePolicy $expirationDate): void
    //    {
    //    }
}
