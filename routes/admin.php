<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('web')->namespace('App\Http\Controllers\Admin')->group(function () {
    Route::get('login', 'AuthController@index')->name('login');
    Route::post('post_login', 'AuthController@login')->name('admin.post_login');

    // ******* after login *******
    Route::group(['middleware' => 'admin'], function () {

        Route::get('logout', 'AuthController@logout')->name('admin.logout');

        Route::get('/', 'HomeController@index')->name('/');
        Route::get('home', 'HomeController@index')->name('admin.home');

        // ################################## Profile ##########################################
        Route::get('admin_profile', 'ProfileController@index')->name('admin_profile');
        Route::post('update-profile', 'ProfileController@update')->name('admin_profile.update');

        // ################################## Admins ##########################################
        Route::resource('admins', 'AdminController');
        Route::post('multi_delete_admins', 'AdminController@multiDelete')->name('admins.multiDelete');

        // ################################## Job Categories ##########################################
        Route::resource('job-categories', 'JobCategoryController');
        Route::post('multi_delete_job_categories', 'JobCategoryController@multiDelete')->name('job-categories.multiDelete');

        // ################################## Jobs ##########################################
        Route::resource('jobs', 'JobController');
        Route::post('multi_delete_jobs', 'JobController@multiDelete')->name('jobs.multiDelete');

        // ################################## Job Applications ##########################################
        Route::post('job-applications/{job_application}/convert-to-contract', 'JobApplicationController@convertToContract')->name('job-applications.convertToContract');
        Route::resource('job-applications', 'JobApplicationController');
        Route::post('multi_delete_job_applications', 'JobApplicationController@multiDelete')->name('job-applications.multiDelete');

        // ################################## Contract Trackers ##########################################
        Route::get('contract-trackers/active', 'ContractTrackerController@activeContracts')->name('contract-trackers.active');
        Route::resource('contract-trackers', 'ContractTrackerController');
        Route::post('multi_delete_contract_trackers', 'ContractTrackerController@multiDelete')->name('contract-trackers.multiDelete');

        // ################################## Travelers ##########################################
        Route::get('travelers/search-applicants', 'TravelerController@searchApplicants')->name('travelers.searchApplicants');
        Route::resource('travelers', 'TravelerController');
        Route::post('multi_delete_travelers', 'TravelerController@multiDelete')->name('travelers.multiDelete');

        // ################################## Cities (Egypt Governorates) ##########################################
        Route::resource('cities', 'CityController');
        Route::post('multi_delete_cities', 'CityController@multiDelete')->name('cities.multiDelete');

        // ################################## Italy Cities ##########################################
        Route::resource('italy-cities', 'ItalyCityController');
        Route::post('multi_delete_italy_cities', 'ItalyCityController@multiDelete')->name('italy-cities.multiDelete');

        // ################################## Italy Landmarks ##########################################
        Route::get('italy-landmarks/by-city/{italy_city}', 'ItalyLandmarkController@getByCity')->name('italy-landmarks.byCity');
        Route::resource('italy-landmarks', 'ItalyLandmarkController');
        Route::post('multi_delete_italy_landmarks', 'ItalyLandmarkController@multiDelete')->name('italy-landmarks.multiDelete');

        // ################################## Contact Messages ##########################################
        Route::resource('contact-messages', 'ContactMessageController');
        Route::post('contact-messages/{contact_message}/toggle-read', 'ContactMessageController@toggleRead')->name('contact-messages.toggleRead');
        Route::post('multi_delete_contact_messages', 'ContactMessageController@multiDelete')->name('contact-messages.multiDelete');

        // ################################## Faqs ##########################################
        Route::resource('faqs', 'FaqController');
        Route::post('multi_delete_faqs', 'FaqController@multiDelete')->name('faqs.multiDelete');

        // ################################## Settings ##########################################
        Route::resource('settings', 'SettingController')->only(['index', 'update']);
    });

    Route::get('/clear-cache', function () {
        Artisan::call('optimize:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

        return '<h1> cache cleared</h1>';
    });
    Route::get('/migrate', function () {
        Artisan::call('migrate');

        return '<h1> migrated</h1>';
    });
    Route::get('/seed', function () {
        Artisan::call('db:seed');

        return '<h1> seeded</h1>';
    });
    Route::fallback(function () {
        return redirect('admin/home');
    });
}); // end Prefix
