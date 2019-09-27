<?php

use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('language')->delete();
      
        DB::table('language')->insert([
                ['name' => 'Bahasa Indonesia','value' => 'id','default_language' => '0','status' => 'Active'],
                ['name' => 'Bahasa Melayu','value' => 'ms','default_language' => '0','status' => 'Active'],
                ['name' => 'Català','value' => 'ca','default_language' => '0','status' => 'Active'],
                ['name' => 'Dansk','value' => 'da','default_language' => '0','status' => 'Active'],
                ['name' => 'Deutsch','value' => 'de','default_language' => '0','status' => 'Active'],
                ['name' => 'English','value' => 'en','default_language' => '1','status' => 'Active'],
                ['name' => 'Español','value' => 'es','default_language' => '0','status' => 'Active'],
                ['name' => 'Eλληνικά','value' => 'el','default_language' => '0','status' => 'Active'],
                ['name' => 'Français','value' => 'fr','default_language' => '0','status' => 'Active'],
                ['name' => 'Italiano','value' => 'it','default_language' => '0','status' => 'Active'],
                ['name' => 'Magyar','value' => 'hu','default_language' => '0','status' => 'Active'],
                ['name' => 'Nederlands','value' => 'nl','default_language' => '0','status' => 'Active'],
                ['name' => 'Norsk','value' => 'no','default_language' => '0','status' => 'Inactive'],
                ['name' => 'Polski','value' => 'pl','default_language' => '0','status' => 'Active'],
                ['name' => 'Português','value' => 'pt','default_language' => '0','status' => 'Active'],
                ['name' => 'Suomi','value' => 'fi','default_language' => '0','status' => 'Active'],
                ['name' => 'Svenska','value' => 'sv','default_language' => '0','status' => 'Inactive'],
                ['name' => 'Türkçe','value' => 'tr','default_language' => '0','status' => 'Active'],
                ['name' => 'Íslenska','value' => 'is','default_language' => '0','status' => 'Inactive'],
                ['name' => 'Čeština','value' => 'cs','default_language' => '0','status' => 'Inactive'],
                ['name' => 'Русский','value' => 'ru','default_language' => '0','status' => 'Active'],
                ['name' => 'ภาษาไทย','value' => 'th','default_language' => '0','status' => 'Active'],
                ['name' => '中文 (简体)','value' => 'zh','default_language' => '0','status' => 'Active'],
                ['name' => '中文 (繁體)','value' => 'zh-TW','default_language' => '0','status' => 'Inactive'],
                ['name' => '日本語','value' => 'ja','default_language' => '0','status' => 'Inactive'],
                ['name' => '한국어','value' => 'ko','default_language' => '0','status' => 'Inactive'],
                ['name' => 'العربية','value' => 'ar','default_language' => '0','status' => 'Active']
          ]);
    }
}
