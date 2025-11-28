<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorEmailToken;

class VendorGmailController extends Controller
{
    private function getClient()
    {
        $client = new \Google\Client();
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->addScope(\Google\Service\Gmail::GMAIL_SEND);
        $client->setRedirectUri(url('/vendor/gmail/callback'));
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        return $client;
    }

    public function redirect($vendorId)
    {
        $client = $this->getClient();
        session(['vendor_connecting_id' => $vendorId]);
        return redirect()->away($client->createAuthUrl());
    }

    public function callback(Request $request)
    {
        $client = $this->getClient();
        $token = $client->fetchAccessTokenWithAuthCode($request->code);

        $client->setAccessToken($token);

        // Get vendor’s Gmail address
        $gmail = new \Google\Service\Gmail($client);
        $profile = $gmail->users->getProfile('me');
        $email = $profile->emailAddress;

        VendorEmailToken::updateOrCreate(
            ['vendor_id' => session('vendor_connecting_id')],
            [
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'] ?? null,
                'email' => $email
            ]
        );

        return "Vendor Gmail Connected!";
    }
}
