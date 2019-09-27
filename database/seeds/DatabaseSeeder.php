<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

         $this->call(CurrencyTableSeeder::class);

         $this->call(LanguageTableSeeder::class);

         $this->call(PropertyTypeTableSeeder::class);

         $this->call(RoomTypeTableSeeder::class);

         $this->call(SiteSettingsTableSeeder::class);

         $this->call(ApiCredentialsTableSeeder::class);

         $this->call(AmenitiesTypeTableSeeder::class);

         $this->call(AmenitiesTableSeeder::class);

         $this->call(BedTypeTableSeeder::class);

         $this->call(CountryTableSeeder::class);

         $this->call(PaymentGatewayTableSeeder::class);

         $this->call(RolesTableSeeder::class);

         $this->call(PermissionsTableSeeder::class);
        /*HostExperiencePHPCommentStart*/
         $this->call(HostExperienceCategoriesTableSeeder::class);
         $this->call(HostExperienceCitiesTableSeeder::class);
         $this->call(HostExperienceProvideItemsTableSeeder::class);
        /*HostExperiencePHPCommentEnd*/         
         $this->call(AdminTableSeeder::class);

         $this->call(TimezoneTableSeeder::class);

         $this->call(EmailSettingsTableSeeder::class);

         $this->call(ThemeSettingsTableSeeder::class);

         $this->call(MessageTypeTableSeeder::class);

         $this->call(PagesTableSeeder::class);

         $this->call(FeesTableSeeder::class);

         $this->call(JoinUsTableSeeder::class);

         $this->call(MetasTableSeeder::class);

         $this->call(HomeCitiesTableSeeder::class);
         
         $this->call(HostBannersTableSeeder::class);
         $this->call(OurCommunityBannersTableSeeder::class);

         $this->call(HelpCategoryTableSeeder::class);
         $this->call(HelpSubCategoryTableSeeder::class);
         $this->call(HelpTableSeeder::class);
         
         $this->call(ReferralSettingsTableSeeder::class);
         $this->call(BottomSliderTableSeeder::class);
         $this->call(SliderTableSeeder::class);

         $this->call(DateformatsTableSeeder::class);

        Model::reguard();
    }
}
