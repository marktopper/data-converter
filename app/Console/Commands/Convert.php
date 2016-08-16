<?php

namespace App\Console\Commands;

use App\Converters;
use Illuminate\Console\Command;

class Convert extends Command
{
    protected $converters = [
        Converters\LanguageConverter::class,
        Converters\CountryConverter::class,
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the converters';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->converters as $converter) {
            $instance = new $converter;
            $instance->handle();
        }

        $this->comment("Converting done!");
    }
}
