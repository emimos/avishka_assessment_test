<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Support Ticket Reply</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px; border: 1px solid #e2e8f0; }
        .header { border-bottom: 2px solid #10b981; padding-bottom: 15px; margin-bottom: 20px; }
        .reply-box { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .footer { font-size: 12px; color: #64748b; margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Online Support System</h2>
        </div>
        <p>Hello <strong>{{ $ticket->customer_name }}</strong>,</p>
        <p>Our support team has posted a reply to your support ticket <strong>#{{ $ticket->reference_number }}</strong>.</p>
        
        <p><strong>Agent Message:</strong></p>
        <div class="reply-box">
            {{ $reply->message }}
        </div>
        
        <p>You can check the full ticket history at any time on our online portal using your reference number: <strong>{{ $ticket->reference_number }}</strong>.</p>
        
        <div class="footer">
            <p>Thank you for using Online Support System.</p>
        </div>
    </div>
</body>
</html>
