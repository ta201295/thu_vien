<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('main');

// Unauthenticated group 
Route::group(array('before' => 'guest'), function() {
 
	// CSRF protection 
	Route::group(array('before' => 'csrf'), function() {

		// Create an account (POST) 
		Route::post('/create', array(
			'as' => 'account-create-post',
			'uses' => 'AccountController@postCreate'
		));

		// Sign in (POST) 
		Route::post('/sign-in', array(
			'as' => 'account-sign-in-post',
			'uses' => 'AccountController@postSignIn'
		));

		// Sign in (POST) 
		Route::post('/student-registration', array(
			'as' => 'student-registration-post',
			'uses' => 'StudentController@postRegistration'
		));		

	});

	// Sign in (GET) 
	Route::get('/login', array(
		'as' 	=> 'account-sign-in',
		'uses'	=> 'AccountController@getSignIn'
	));

	// Create an account (GET) 
	Route::get('/create', array(
		'as' 	=> 'account-create',
		'uses' 	=> 'AccountController@getCreate'
	));
    
    // Render search books panel
    Route::get('/book', array(
        'as' => 'search-book',
        'uses' => 'BooksController@searchBook'
    ));    
	
});

// Main books Controlller left public so that it could be used without logging in too
Route::resource('/books', 'BooksController');

// Authenticated group 
// Route::group(array('before' => 'auth'), function() {
Route::group(['middleware' => ['auth']] , function() {

	// Home Page of Control Panel
	Route::get('/home',array(
		'as' 	=> 'home',
		'uses'	=> 'HomeController@home'
	));	

	// Render Add Books panel
    Route::get('/add-books', array(
        'as' => 'add-books',
        'uses' => 'BooksController@renderAddBooks'
	));

	Route::get('/add-book-category', array(
        'as' => 'add-book-category',
        'uses' => 'BooksController@renderAddBookCategory'
	));
	
	Route::post('/bookcategory', 'BooksController@BookCategoryStore')->name('bookcategory.store');
	

	// Render All Books panel
    Route::get('/all-books', array(
        'as' => 'all-books',
        'uses' => 'BooksController@renderAllBooks'
	));
	
	Route::get('/bookBycategory/{cat_id}', array(
        'as' => 'bookBycategory',
        'uses' => 'BooksController@BookByCategory'
    ));

	// Students
    Route::get('/registered-students', array(
        'as' => 'registered-students',
        'uses' => 'StudentController@renderStudents'
    ));

    // Render students approval panel
    Route::get('/students-for-approval', array(
        'as' => 'students-for-approval',
        'uses' => 'StudentController@renderApprovalStudents'
	));
	
	  // Render students approval panel
	  Route::get('/settings', array(
        'as' => 'settings',
        'uses' => 'StudentController@Setting'
	));
	
	  // Render students approval panel
	  Route::post('/setting', array(
        'as' => 'settings.store',
        'uses' => 'StudentController@StoreSetting'
    ));
	
	Route::post('/studentByattribute', array(
        'as' => 'studentByattribute',
        'uses' => 'StudentController@StudentByAttribute'
    ));

    // Issue Logs
    Route::get('/issue-return', array(
        'as' => 'issue-return',
        'uses' => 'LogController@renderIssueReturn'
    ));

    // Render logs panel
    Route::get('/currently-issued', array(
        'as' => 'currently-issued',
        'uses' => 'LogController@renderLogs'
    ));

    // Main Logs Controlller resource
    Route::resource('/issue-log', 'LogController');
	Route::post('/issue-log/return', 'LogController@return')->name('issue-log.return');

	// Sign out (GET) 
    Route::get('/sign-out', array(
    	'as' => 'account-sign-out',
		'uses' => 'AccountController@getSignOut'
    ));

	Route::get('/book-student/pending', 'BookStudentController@listPending')->name('book-student.pending');
	Route::resource('book-student', 'BookStudentController')->only('update');
	Route::resource('students', 'StudentController');
});

Route::resource('students', 'StudentController')->except(['show']);
Route::prefix('students')->group(function () {
	Route::post('/login', 'StudentController@login')->name('students.post_login');
});


Route::group(['middleware' => ['auth:student']], function () {
	Route::get('student/logout', 'StudentController@logout')->name('students.logout');

	Route::resource('book-student', 'BookStudentController')->except('update');
	Route::get('/book-student/{bookStudent}/extend', 'BookStudentController@extend')->name('book-student.extend');
});
