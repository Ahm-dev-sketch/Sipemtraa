<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class OptimizeImagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'optimize-images:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update image references from PNG to WebP format';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('========================================');
        $this->info(' Updating Image References to WebP');
        $this->info('========================================');
        $this->newLine();

        // Check if WebP images exist
        $logoWebP = public_path('asset/logo.webp');
        $homeWebP = public_path('asset/home.webp');

        if (!File::exists($logoWebP)) {
            $this->error('❌ logo.webp not found in public/asset/');
            $this->warn('Please convert logo.png to logo.webp first!');
            $this->newLine();
            return 1;
        }

        if (!File::exists($homeWebP)) {
            $this->error('❌ home.webp not found in public/asset/');
            $this->warn('Please convert home.png to home.webp first!');
            $this->newLine();
            return 1;
        }

        $this->info('✓ WebP images found!');
        $this->newLine();

        // Files to update
        $files = [
            resource_path('views/layouts/app.blade.php'),
            resource_path('views/user/home.blade.php'),
        ];

        $totalUpdates = 0;

        foreach ($files as $file) {
            if (!File::exists($file)) {
                $this->warn("⚠ File not found: $file");
                continue;
            }

            $content = File::get($file);
            $originalContent = $content;

            // Replace logo.png with logo.webp (but keep favicon as PNG for compatibility)
            $content = preg_replace(
                '/(<img[^>]+src=")[^"]*logo\.png([^"]*")/',
                '$1{{ asset(\'asset/logo.webp\') }}$2',
                $content
            );

            // Replace home.png with home.webp
            $content = preg_replace(
                '/(<img[^>]+src=")[^"]*home\.png([^"]*")/',
                '$1{{ asset(\'asset/home.webp\') }}$2',
                $content
            );

            // Update preload links
            $content = str_replace(
                "asset('asset/logo.png')",
                "asset('asset/logo.webp')",
                $content
            );
            $content = str_replace(
                "asset('asset/home.png')",
                "asset('asset/home.webp')",
                $content
            );

            // Add lazy loading and dimensions if not exist
            $content = preg_replace(
                '/(<img[^>]+src="[^"]*logo\.webp[^"]*"[^>]*)((?!loading=)[^>]*>)/',
                '$1 loading="lazy" width="48" height="48"$2',
                $content
            );

            $content = preg_replace(
                '/(<img[^>]+src="[^"]*home\.webp[^"]*"[^>]*)((?!loading=)[^>]*>)/',
                '$1 loading="lazy" width="400" height="300"$2',
                $content
            );

            if ($content !== $originalContent) {
                File::put($file, $content);
                $this->info("✓ Updated: " . basename($file));
                $totalUpdates++;
            }
        }

        $this->newLine();
        $this->info("========================================");
        $this->info(" Optimization Complete!");
        $this->info("========================================");
        $this->info("✓ Total files updated: $totalUpdates");
        $this->info("✓ Images now using WebP format");
        $this->info("✓ Lazy loading enabled");
        $this->info("✓ Dimensions added for better CLS");
        $this->newLine();
        $this->warn("⚠ Note: Favicon still uses PNG for browser compatibility");
        $this->newLine();

        return 0;
    }
}
