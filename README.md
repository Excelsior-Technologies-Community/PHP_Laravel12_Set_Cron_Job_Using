

✨//Laravel Installation Code

⭐composer create-project laravel/laravel example-app



✨//Migration Code

⭐php artisan make:migration create_categories_table


✨//Controller & Model Code

⭐php artisan make:model Category

⭐php artisan make:model Category



✨//Command Creating Code

⭐php artisan make:command CategoryCron 


✨//Add This Code In CategoryCron


{

    protected $signature = 'category:cron';
    
    protected $description = 'Cron job for category table actions';

    public function handle()
{
    // Delete ALL categories
    
    Category::truncate();

    \Log::info("Category Cron: All categories deleted at " . now());

    return Command::SUCCESS;
}



✨//Add This Code In Console


Artisan::command('inspire', function () {

    $this->comment(Inspiring::quote());

})->purpose('Display an inspiring quote');

// ✅ Category Cron Job Schedule

Schedule::command('category:cron')->everyMinute();




✨//Run This Code In Terminal

⭐php artisan schedule:run



✨// Your windows Task Schedulat Steps

<img width="887" height="573" alt="image" src="https://github.com/user-attachments/assets/40e5a731-a6a1-431f-bb3a-dbf0470c74fc" />

<img width="997" height="644" alt="image" src="https://github.com/user-attachments/assets/2312926a-4fbc-497c-846f-52cfc8bdb388" />

<img width="839" height="647" alt="image" src="https://github.com/user-attachments/assets/c948e147-d09a-4248-bb30-c20b0f3e6c3d" />

<img width="851" height="505" alt="image" src="https://github.com/user-attachments/assets/fdb5fff3-9d58-4d7d-b413-4220f85b03f1" />

<img width="865" height="573" alt="image" src="https://github.com/user-attachments/assets/876dd4f5-9a05-4a10-be3c-43703e05b928" />

<img width="802" height="612" alt="image" src="https://github.com/user-attachments/assets/842a5941-660a-4974-a777-b18a5276bdbb" />

<img width="966" height="571" alt="image" src="https://github.com/user-attachments/assets/c8f19044-80b5-4c99-a534-0428b363078d" />

<img width="975" height="741" alt="image" src="https://github.com/user-attachments/assets/b810cdad-98c0-40d3-9cbb-16c0264599f7" />

<img width="938" height="669" alt="image" src="https://github.com/user-attachments/assets/cb5dbe5f-733d-4647-b621-ba5699737d11" />








