<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TestMail extends Command
{
    protected $signature = 'mail:test {email}';
    protected $description = 'Test email sending configuration';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info('Testing email configuration...');
        $this->line('');
        
        // Check configuration
        $this->info('Mail Configuration:');
        $this->line('  MAIL_MAILER: ' . config('mail.default'));
        $this->line('  MAIL_HOST: ' . config('mail.mailers.smtp.host'));
        $this->line('  MAIL_PORT: ' . config('mail.mailers.smtp.port'));
        $this->line('  MAIL_USERNAME: ' . (config('mail.mailers.smtp.username') ? 'SET' : 'NOT SET'));
        $this->line('  MAIL_PASSWORD: ' . (config('mail.mailers.smtp.password') ? 'SET' : 'NOT SET'));
        $this->line('  MAIL_ENCRYPTION: ' . config('mail.mailers.smtp.encryption'));
        $this->line('  MAIL_FROM_ADDRESS: ' . config('mail.from.address'));
        $this->line('  MAIL_FROM_NAME: ' . config('mail.from.name'));
        $this->line('');
        
        // Try to send test email
        $this->info('Attempting to send test email to: ' . $email);
        
        try {
            Mail::raw('This is a test email from Hijab Journey. If you receive this, your email configuration is working correctly!', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email - Hijab Journey');
            });
            
            $this->info('✓ Email sent successfully!');
            $this->line('Please check the inbox (and spam folder) of: ' . $email);
            
        } catch (\Exception $e) {
            $this->error('✗ Failed to send email!');
            $this->error('Error: ' . $e->getMessage());
            $this->line('');
            $this->warn('Common issues:');
            $this->line('  1. Check MAIL_USERNAME and MAIL_PASSWORD in .env');
            $this->line('  2. For Gmail, use an App Password (not your regular password)');
            $this->line('  3. Check MAIL_HOST and MAIL_PORT settings');
            $this->line('  4. Ensure MAIL_ENCRYPTION is set correctly (tls or ssl)');
            $this->line('  5. Check firewall/network restrictions');
            
            Log::error('Test email failed: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
