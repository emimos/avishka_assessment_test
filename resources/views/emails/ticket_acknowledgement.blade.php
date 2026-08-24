<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Support Ticket Acknowledgement</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px; border: 1px solid #e2e8f0; }
        .header { border-bottom: 2px solid #3b82f6; padding-bottom: 15px; margin-bottom: 20px; }
        .reference-box { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; padding: 15px; border-radius: 8px; font-weight: bold; font-size: 18px; text-align: center; margin: 20px 0; }
        .footer { font-size: 12px; color: #64748b; margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Online Support System</h2>
        </div>
        <p>Hello <strong>{{ $ticket->customer_name }}</strong>,</p>
        <p>Thank you for reaching out to our support team. Your support ticket has been successfully received.</p>
        
        <p>Please keep your unique ticket reference number to check the status of your ticket at any time:</p>
        
        <div class="reference-box">
            Reference Number: {{ $ticket->reference_number }}
        </div>
        
        <p><strong>Problem Description:</strong></p>
        <p style="background: #f1f5f9; padding: 12px; border-radius: 6px;">{{ $ticket->problem_description }}</p>
        
        <p>Our support agents will review your request and get back to you shortly.</p>
        
        <div class="footer">
            <p>This is an automated notification from the Online Support System.</p>
        </div>
    </div>
</body>
</html>
