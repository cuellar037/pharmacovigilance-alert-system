<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Models\Customer;

class CustomerController extends Controller
{
  // Customer details, including their order history
  public function show($id): JsonResponse
  {
    $customer = Customer::with(['orders.orderItems.medication'])->findOrFail($id);
    return response()->json($customer);
  }
}
