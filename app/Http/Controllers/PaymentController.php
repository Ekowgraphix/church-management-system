<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Donation;
use App\Models\Member;

class PaymentController extends Controller
{
    /**
     * Initialize payment with Paystack
     */
    public function initialize(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'email' => 'required|email',
            'payment_type' => 'required|in:offering,tithe,donation,event',
            'description' => 'nullable|string',
        ]);

        $amount = $request->amount * 100; // Convert to kobo/pesewas
        $email = $request->email;
        $reference = $this->generateReference();

        // Paystack API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.paystack.co/transaction/initialize', [
            'email' => $email,
            'amount' => $amount,
            'reference' => $reference,
            'callback_url' => route('payment.callback'),
            'metadata' => [
                'user_id' => auth()->id(),
                'payment_type' => $request->payment_type,
                'description' => $request->description,
            ],
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            return response()->json([
                'success' => true,
                'authorization_url' => $data['data']['authorization_url'],
                'access_code' => $data['data']['access_code'],
                'reference' => $reference,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to initialize payment',
        ], 400);
    }

    /**
     * Handle payment callback from Paystack
     */
    public function callback(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('portal.index')
                ->with('error', 'Payment reference not found');
        }

        // Verify payment
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
        ])->get("https://api.paystack.co/transaction/verify/{$reference}");

        if ($response->successful()) {
            $data = $response->json();

            if ($data['data']['status'] === 'success') {
                // Save payment record
                $this->savePayment($data['data']);

                return redirect()->route('portal.giving')
                    ->with('success', 'Payment successful! Thank you for your contribution.');
            }
        }

        return redirect()->route('portal.index')
            ->with('error', 'Payment verification failed');
    }

    /**
     * Handle Paystack webhook
     */
    public function webhook(Request $request)
    {
        $signature = $request->header('x-paystack-signature');
        $body = $request->getContent();

        if ($signature !== hash_hmac('sha512', $body, env('PAYSTACK_SECRET_KEY'))) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $event = $request->all();

        if ($event['event'] === 'charge.success') {
            $this->savePayment($event['data']);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Verify payment
     */
    public function verify($reference)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
        ])->get("https://api.paystack.co/transaction/verify/{$reference}");

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Verification failed'], 400);
    }

    /**
     * Save payment to database
     */
    private function savePayment($data)
    {
        $metadata = $data['metadata'] ?? [];
        $userId = $metadata['user_id'] ?? null;
        $member = null;

        if ($userId) {
            $user = \App\Models\User::find($userId);
            if ($user) {
                $member = Member::where('email', $user->email)->first();
            }
        }

        Donation::create([
            'member_id' => $member ? $member->id : null,
            'amount' => $data['amount'] / 100, // Convert from kobo
            'donation_type' => $metadata['payment_type'] ?? 'offering',
            'payment_method' => $data['channel'] ?? 'online',
            'transaction_reference' => $data['reference'],
            'donation_date' => now(),
            'notes' => $metadata['description'] ?? null,
        ]);

        Log::info('Payment saved', [
            'reference' => $data['reference'],
            'amount' => $data['amount'] / 100,
            'member_id' => $member ? $member->id : null,
        ]);
    }

    /**
     * Generate unique payment reference
     */
    private function generateReference()
    {
        return 'CMS-' . time() . '-' . strtoupper(substr(md5(uniqid()), 0, 8));
    }

    /**
     * Mobile Money payment initialization (MTN, Vodafone, AirtelTigo)
     */
    public function initializeMobileMoney(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'phone' => 'required|string',
            'network' => 'required|in:mtn,vodafone,airteltigo',
            'payment_type' => 'required|in:offering,tithe,donation,event',
        ]);

        // This would integrate with specific mobile money APIs
        // Each network has its own API endpoint and authentication

        return response()->json([
            'success' => true,
            'message' => 'Mobile Money payment initialized. Please approve on your phone.',
        ]);
    }
}
