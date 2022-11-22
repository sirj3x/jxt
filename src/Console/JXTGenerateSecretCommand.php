<?php

namespace Sirj3x\Jxt\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class JXTGenerateSecretCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'jxt:secret';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the JXT secret key used to sign the tokens';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $key = Str::random(64);

        if (file_exists($path = $this->envPath()) === false) {
            $this->displayKey($key);
        }

        if (Str::contains(file_get_contents($path), 'JXT_SECRET') === false) {

            // create new entry
            file_put_contents($path, PHP_EOL . "JXT_SECRET=$key" . PHP_EOL, FILE_APPEND);

        } else {

            // update existing entry
            file_put_contents($path, str_replace(
                'JXT_SECRET=' . $this->laravel['config']['jxt.passphrase'],
                'JXT_SECRET=' . $key, file_get_contents($path)
            ));

        }

        $this->displayKey($key);
    }

    /**
     * Display the key.
     *
     * @param string $key
     * @return void
     */
    protected function displayKey(string $key): void
    {
        $this->laravel['config']['jxt.passphrase'] = $key;

        $this->info("jxt secret [$key] set successfully.");
    }

    /**
     * Get the .env file path.
     *
     * @return string
     */
    protected function envPath(): string
    {
        if (method_exists($this->laravel, 'environmentFilePath')) {
            return $this->laravel->environmentFilePath();
        }

        // check if laravel version Less than 5.4.17
        if (version_compare($this->laravel->version(), '5.4.17', '<')) {
            return $this->laravel->basePath() . DIRECTORY_SEPARATOR . '.env';
        }

        return $this->laravel->basePath('.env');
    }
}
