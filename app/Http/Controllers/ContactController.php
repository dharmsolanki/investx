<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:100',
            'email'        => 'required|email',
            'phone'        => 'nullable|digits:10',
            'issue_type'   => 'required|in:withdrawal,kyc,payment,other',
            'message'      => 'required|string|min:10|max:1000',
            'reference_id' => 'nullable|string|max:100',
        ]);

        $issueLabels = [
            'withdrawal' => 'Withdrawal Issue',
            'kyc'        => 'KYC Issue',
            'payment'    => 'Payment Issue',
            'other'      => 'Other',
        ];

        $issueLabel   = $issueLabels[$request->issue_type];
        $adminEmail   = config('mail.from.address');
        $name         = $request->name;
        $email        = $request->email;
        $phone        = $request->phone ?: '—';
        $refId        = $request->reference_id ?: '—';
        $message      = nl2br(e($request->message));
        $messagePlain = e($request->message);

        $adminHtml = "
            <div style='font-family:sans-serif;max-width:600px;margin:0 auto;padding:20px'>
                <h2 style='color:#C9A84C;border-bottom:2px solid #C9A84C;padding-bottom:10px'>
                    DailyWealth — New Support Request
                </h2>
                <table style='width:100%;border-collapse:collapse;margin-top:20px'>
                    <tr style='background:#f8f9fa'>
                        <td style='padding:10px;font-weight:bold;width:35%'>Issue Type</td>
                        <td style='padding:10px'>{$issueLabel}</td>
                    </tr>
                    <tr>
                        <td style='padding:10px;font-weight:bold'>Name</td>
                        <td style='padding:10px'>{$name}</td>
                    </tr>
                    <tr style='background:#f8f9fa'>
                        <td style='padding:10px;font-weight:bold'>Email</td>
                        <td style='padding:10px'>{$email}</td>
                    </tr>
                    <tr>
                        <td style='padding:10px;font-weight:bold'>Phone</td>
                        <td style='padding:10px'>{$phone}</td>
                    </tr>
                    <tr style='background:#f8f9fa'>
                        <td style='padding:10px;font-weight:bold'>Reference ID</td>
                        <td style='padding:10px'>{$refId}</td>
                    </tr>
                    <tr>
                        <td style='padding:10px;font-weight:bold;vertical-align:top'>Message</td>
                        <td style='padding:10px'>{$message}</td>
                    </tr>
                </table>
                <div style='margin-top:20px;padding:15px;background:#fff3cd;border-radius:8px;font-size:0.9rem'>
                    Reply directly to this email to respond to the user.
                </div>
            </div>
        ";

        $userHtml = "
            <div style='font-family:sans-serif;max-width:500px;margin:0 auto;padding:20px'>
                <h2 style='color:#C9A84C'>DailyWealth Support</h2>
                <p>Namaste <strong>{$name}</strong>,</p>
                <p>Aapki support request receive ho gayi hai. Hum 24 ghante mein aapse contact karenge.</p>
                <div style='background:#f8f9fa;padding:15px;border-radius:8px;margin:20px 0'>
                    <strong>Issue Type:</strong> {$issueLabel}<br>
                    <strong>Message:</strong> {$messagePlain}
                </div>
                <p style='color:#666;font-size:0.85rem'>Agar urgent hai toh WhatsApp karein: <strong>+971524185587</strong></p>
            </div>
        ";

        // Admin email
        Mail::html($adminHtml, function ($msg) use ($adminEmail, $issueLabel, $name, $email) {
            $msg->to($adminEmail)
                ->subject("DailyWealth Support — {$issueLabel} — {$name}")
                ->replyTo($email, $name);
        });

        // User acknowledgement
        Mail::html($userHtml, function ($msg) use ($email, $name) {
            $msg->to($email, $name)
                ->subject('DailyWealth — Aapki Request Receive Ho Gayi');
        });

        return back()->with('success', 'Aapki request submit ho gayi! 24 ghante mein aapse contact karenge. Aapke email par confirmation bhi bheja gaya hai.');
    }
}