#!/usr/bin/env bash

sudo rm -r "public/images/host_experiences" "resources/views/host_experiences" "resources/views/admin/host_experiences" "resources/views/admin/host_experience_reviews" "resources/views/admin/host_experience_reservation"
sudo rm -rf app/Models/HostExperienceTranslations.php app/Models/HostExperienceProvideTranslations.php app/Models/HostExperienceProvides.php app/Models/HostExperienceProvideItems.php app/Models/HostExperiencePhotos.php app/Models/HostExperiencePackingListTranslations.php app/Models/HostExperiencePackingLists.php app/Models/HostExperienceLocation.php app/Models/HostExperienceGuestRequirements.php app/Models/HostExperienceCities.php app/Models/HostExperienceCategories.php app/Models/HostExperienceCalendar.php
sudo rm -rf database/migrations/2017_10_23_112436_create_host_experience_cities_table.php database/migrations/2017_10_23_113018_create_host_experience_categories_table.php database/migrations/2017_10_23_113019_create_host_experience_provide_items_table.php database/migrations/2017_10_23_114017_create_host_experience_photos_table.php database/migrations/2017_10_23_114039_create_host_experience_location_table.php database/migrations/2017_10_23_114050_create_host_experience_guest_requirements_table.php database/migrations/2017_10_23_114101_create_host_experience_provides_table.php database/migrations/2017_10_23_114112_create_host_experience_packing_lists_table.php database/migrations/2017_10_23_114130_create_host_experience_translations_table.php database/migrations/2017_10_23_114134_create_host_experience_provide_translations_table.php database/migrations/2017_10_23_114145_create_host_experience_packing_list_translations_table.php database/migrations/2017_10_25_064226_create_host_experience_calendar_table.php 
sudo rm -rf app/DataTables/HostExperienceCategoriesDataTable.php app/DataTables/HostExperienceCitiesDataTable.php app/DataTables/HostExperienceProvideItemsDataTable.php app/DataTables/HostExperienceReservationsDataTable.php app/DataTables/HostExperienceReviewsDataTable.php app/DataTables/HostExperiencesDataTable.php 
sudo rm -rf app/Http/Controllers/Admin/HostExperiencesController.php app/Http/Controllers/Admin/HostExperienceProvideItemsController.php app/Http/Controllers/Admin/HostExperienceCitiesController.php app/Http/Controllers/Admin/HostExperienceCategoriesController.php 
sudo rm -rf app/Http/Controllers/HostExperiencesController.php app/Http/Controllers/HostExperiencePaymentController.php
sudo rm -rf app/Models/HostExperienceTranslations.php app/Models/HostExperienceProvideTranslations.php app/Models/HostExperienceProvides.php app/Models/HostExperienceProvideItems.php app/Models/HostExperiencePhotos.php app/Models/HostExperiencePackingListTranslations.php app/Models/HostExperiencePackingLists.php app/Models/HostExperienceLocation.php app/Models/HostExperienceGuestRequirements.php app/Models/HostExperienceCities.php app/Models/HostExperienceCategories.php app/Models/HostExperienceCalendar.php 
sudo rm -rf database/seeds_package/HostExperienceCategoriesTableSeeder.php database/seeds_package/HostExperienceCitiesTableSeeder.php database/seeds_package/HostExperienceProvideItemsTableSeeder.php 

FirstArray=(
"\/\*HostExperiencePHPCommentStart\*\/"
"\/\*HostExperiencePHPCommentEnd\*\/"
"{{--HostExperienceBladeCommentStart--}}"
"{{--HostExperienceBladeCommentEnd--}}"
"\/\*HostExperiencePHPUnCommentStart"
"HostExperiencePHPUnCommentEnd\*\/"
"{{--HostExperienceBladeUnCommentStart"
"HostExperienceBladeUnCommentEnd--}}"
)
SecondArray=(
"\/\*HostExperiencePHPCommentStart"
"HostExperiencePHPCommentEnd\*\/"
"{{--HostExperienceBladeCommentStart"
"HostExperienceBladeCommentEnd--}}"
"\/\*HostExperiencePHPUnCommentStart\*\/"
"\/\*HostExperiencePHPUnCommentEnd\*\/"
"{{--HostExperienceBladeUnCommentStart--}}"
"{{--HostExperienceBladeUnCommentEnd--}}"
)

tag=0
for i in "${FirstArray[@]}"
do
	find . -name "*.php" -exec sed -i "s@${FirstArray[$tag]}@${SecondArray[$tag]}@g" '{}' \;
	find . -name "*.js" -exec sed -i "s@${FirstArray[$tag]}@${SecondArray[$tag]}@g" '{}' \;
	tag=$((tag+1))
done