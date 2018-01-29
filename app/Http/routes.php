<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'web'], function () {
    //homepage
    Route::get('/', ['as' => 'web.home', 'uses' => 'PagesController@home']);
    Route::get('blog', ['as' => 'web.blog', 'uses' => 'PagesController@home']);
    Route::get('/blog/{posts}', ['as' => 'web.post', 'uses' => 'PagesController@post']);
    Route::get('/category/{categories}', ['as' => 'web.category', 'uses' => 'PagesController@category']);

});

/*
Route::get('laravel-version', function () {
    $laravel = app();
    return "Your Laravel version is " . $laravel::VERSION;
});
*/

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'api', 'middleware' => ['api', 'auth', 'timeout', 'web'], 'namespace' => 'Api'], function () {
    // posts
    Route::post('posts/{posts}/publish', ['as' => 'api.posts.publish', 'uses' => 'PostsController@publish']);
    Route::post('posts/{posts}/img', ['as' => 'api.posts.updateImage', 'uses' => 'PostsController@updateImage']);
    Route::resource('posts', 'PostsController', ['except' => ['create', 'edit']]);

    // categories
    Route::resource('categories', 'CategoriesController', ['except' => ['create', 'edit']]);

    // posts categories
    Route::patch('posts/{posts}/categories', ['as' => 'api.posts.categories.sync', 'uses' => 'PostsCategoriesController@sync']);
    Route::resource('posts.categories', 'PostsCategoriesController', ['only' => ['index', 'store', 'destroy']]);

    // categories posts
    Route::get('categories/{categories}/posts', ['as' => 'api.categories.posts.index', 'uses' => 'CategoriesPostsController@index']);

    // users
    Route::get('users/export_excel', ['as' => 'api.users.export_excel', 'uses' => 'UsersController@xlsUsers']);
    Route::get('users/convert_image', ['as' => 'api.users.convert_image', 'uses' => 'UsersController@convertImage']);
    Route::get('users/all', ['as' => 'api.users.all', 'uses' => 'UsersController@allUsers']);
    Route::get('users/showpage/{page?}', ['as' => 'api.users.showpage', 'uses' => 'UsersController@showPage']);
    Route::resource('users', 'UsersController');
    // Route::resource('users', 'UsersController', ['only' => ['index', 'show', 'store', 'update', 'destroy', 'xlsUsers', 'edit']]);

    Route::get('me', ['as' => 'api.me.show', 'uses' => 'MeController@show']);
    Route::patch('me', ['as' => 'api.me.update', 'uses' => 'MeController@update']);
    Route::put('me', ['as' => 'api.me.update', 'uses' => 'MeController@update']);

    // menus
    Route::get('menus', ['as' => 'api.menus.show', 'uses' => 'MenusController@show']);
    Route::get('menus/showmenu', ['as' => 'api.menus.showmenu', 'uses' => 'MenusController@showMenu']);
    Route::get('menus/fetch', ['as' => 'api.menus.fetch', 'uses' => 'MenusController@fetch']);
    Route::patch('menus/update', ['as' => 'api.menus.update', 'uses' => 'MenusController@update']);

    // log
    Route::get('logs/all', ['as' => 'api.logs.all', 'uses' => 'LogsController@allLogs']);
    Route::resource('logs', 'LogsController');

    // basis
    Route::get('basis/showpid/{pid?}', ['as' => 'api.basis.showpid', 'uses' => 'BasisController@showPid']);
    Route::get('basis/showpid1/{pid?}', ['as' => 'api.basis.showpid1', 'uses' => 'BasisController@showPid1']);
    Route::get('basis/listcase/{pid?}', ['as' => 'api.basis.listcase', 'uses' => 'BasisController@listCase']);
    Route::get('basis/listdata/{pid?}', ['as' => 'api.basis.listdata', 'uses' => 'BasisController@listData']);
    Route::get('basis/importExport', ['as' => 'api.basis.importexport', 'uses' => 'BasisController@importExport']);
    Route::get('basis/downloadExcel/{type}', 'BasisController@downloadExcel');
    Route::post('basis/importExcel', ['as' => 'api.basis.importexcel', 'uses' => 'BasisController@importExcel']);
    Route::get('basis/showpage/{page?}', ['as' => 'api.basis.showpage', 'uses' => 'BasisController@showPage']);
    Route::patch('basis/showby/{page?}', ['as' => 'api.basis.showby', 'uses' => 'BasisController@showBy']);
    Route::resource('basis', 'BasisController');

    Route::resource('basiscache', 'BasisCacheController');

    // T2DM
    Route::get('basist2dm/showpid/{pid?}', ['as' => 'api.basist2dm.showpid', 'uses' => 'BasisT2dmController@showPid']);
    Route::get('basist2dm/showpid1/{pid?}', ['as' => 'api.basist2dm.showpid1', 'uses' => 'BasisT2dmController@showPid1']);
    Route::resource('basist2dm', 'BasisT2dmController');
    // T1DM
    Route::get('basist1dm/showpid/{pid?}', ['as' => 'api.basist1dm.showpid', 'uses' => 'BasisT1dmController@showPid']);
    Route::get('basist1dm/showpid1/{pid?}', ['as' => 'api.basist1dm.showpid1', 'uses' => 'BasisT1dmController@showPid1']);
    Route::resource('basist1dm', 'BasisT1dmController');
    // GDM
    Route::get('basisgdm/showpid/{pid?}', ['as' => 'api.basisgdm.showpid', 'uses' => 'BasisGdmController@showPid']);
    Route::get('basisgdm/showpid1/{pid?}', ['as' => 'api.basisgdm.showpid1', 'uses' => 'BasisGdmController@showPid1']);
    Route::resource('basisgdm', 'BasisGdmController');
    // IGTIFG
    Route::get('basisigtifg/showpid/{pid?}', ['as' => 'api.basisigtifg.showpid', 'uses' => 'BasisIgtifgController@showPid']);
    Route::get('basisigtifg/showpid1/{pid?}', ['as' => 'api.basisigtifg.showpid1', 'uses' => 'BasisIgtifgController@showPid1']);
    Route::resource('basisigtifg', 'BasisIgtifgController');

    // cases
    Route::get('cases/showpage/{page?}', ['as' => 'api.cases.showpage', 'uses' => 'CasesController@showPage']);
    Route::get('cases/showall', ['as' => 'api.cases.showall', 'uses' => 'CasesController@showAll']);
    Route::get('cases/showid1/{id?}', ['as' => 'api.cases.showid1', 'uses' => 'CasesController@showId1']);
    Route::patch('cases/checkpid/{pid?}', ['as' => 'api.cases.checkpid', 'uses' => 'CasesController@checkPid']);
    Route::post('cases/closecase', ['as' => 'api.cases.closecase', 'uses' => 'CasesController@closeCase']);
    Route::get('cases/listdmcase/{pid?}', ['as' => 'api.cases.listdmcase', 'uses' => 'CasesController@listDMCase']);
    Route::get('cases/listckdcase/{pid?}', ['as' => 'api.cases.listckdcase', 'uses' => 'CasesController@listCKDCase']);
    Route::get('cases/listnondmcase/{pid?}', ['as' => 'api.cases.listnondmcase', 'uses' => 'CasesController@listNONDMCase']);
    Route::post('cases/importCSV', ['as' => 'api.cases.importcsv', 'uses' => 'CasesController@importCSV']);
    Route::post('cases/importexcel/{type?}', ['as' => 'api.cases.importexcel', 'uses' => 'CasesController@importExcel']);
    Route::delete('cases/destroyexcel', ['as' => 'api.cases.destroyexcel', 'uses' => 'CasesController@destroyExcel']);
    Route::delete('cases/destroychecks', ['as' => 'api.cases.destroychecks', 'uses' => 'CasesController@destroyChecks']);
    Route::post('cases/insertcheckhistory', ['as' => 'api.cases.insertcheckhistory', 'uses' => 'CasesController@insertCheckhistory']);
    Route::post('cases/insertvpnhistory', ['as' => 'api.cases.insertvpnhistory', 'uses' => 'CasesController@insertVpnhistory']);
    Route::post('cases/refreshCheck', ['as' => 'api.cases.refreshcheck', 'uses' => 'CasesController@refreshCheck']);
    Route::get('cases/downloadtxt', ['as' => 'api.cases.downloadtxt', 'uses' => 'CasesController@downloadTxt']);
    Route::delete('cases/destroyrawdatavpn', ['as' => 'api.cases.destroyrawdatavpn', 'uses' => 'CasesController@destroyRawdatavpn']);
    Route::post('cases/updatea1c', ['as' => 'api.cases.updatea1c', 'uses' => 'CasesController@updateA1C']);
    Route::post('cases/updatecho', ['as' => 'api.cases.updatecho', 'uses' => 'CasesController@updateCHO']);
    Route::post('cases/updatetg', ['as' => 'api.cases.updatetg', 'uses' => 'CasesController@updateTG']);
    Route::post('cases/updateldl', ['as' => 'api.cases.updateldl', 'uses' => 'CasesController@updateLDL']);
    Route::post('cases/updatehdlc', ['as' => 'api.cases.updatehdlc', 'uses' => 'CasesController@updateHDLC']);
    Route::post('cases/updategpt', ['as' => 'api.cases.updategpt', 'uses' => 'CasesController@updateGPT']);
    Route::post('cases/updatecre', ['as' => 'api.cases.updatecre', 'uses' => 'CasesController@updateCRE']);
    Route::post('cases/updateua', ['as' => 'api.cases.updateua', 'uses' => 'CasesController@updateUA']);
    Route::post('cases/updatemic', ['as' => 'api.cases.updatemic', 'uses' => 'CasesController@updateMIC']);
    Route::post('cases/updatepcr', ['as' => 'api.cases.updatepcr', 'uses' => 'CasesController@updatePCR']);
    Route::post('cases/updateupro', ['as' => 'api.cases.updateupro', 'uses' => 'CasesController@updateUPRO']);
    Route::post('cases/updategfr', ['as' => 'api.cases.updategfr', 'uses' => 'CasesController@updateGFR']);
    Route::get('cases/checkcsv', ['as' => 'api.cases.checkcsv', 'uses' => 'CasesController@checkCSV']);
    Route::get('cases/checkcsv1', ['as' => 'api.cases.checkcsv1', 'uses' => 'CasesController@checkCSV1']);
    Route::get('cases/comparecases', ['as' => 'api.cases.comparecases', 'uses' => 'CasesController@compareCases']);
    Route::post('cases/checkcorrect', ['as' => 'api.cases.checkcorrect', 'uses' => 'CasesController@checkCorrect']);
    Route::get('cases/duplicatecases', ['as' => 'api.cases.duplicatecases', 'uses' => 'CasesController@duplicateCases']);
    Route::resource('cases', 'CasesController');

    // 檢驗歷史
    Route::get('checkhistory/showpage/{page?}', ['as' => 'api.checkhistory.showpage', 'uses' => 'CheckhistoryController@showPage']);
    Route::get('checkhistory/all', ['as' => 'api.checkhistory.all', 'uses' => 'CheckhistoryController@allCheckhistory']);
    Route::get('checks/all', ['as' => 'api.checks.all', 'uses' => 'ChecksController@allChecks']);

    // HIS
    Route::get('realsun/all', ['as' => 'api.realsun.all', 'uses' => 'RealsunController@allRealsun']);

    // VPNHistory
    Route::get('vpnhistory/showpage/{page?}', ['as' => 'api.vpnhistory.showpage', 'uses' => 'VpnhistoryController@showPage']);
    Route::get('vpnhistory/all', ['as' => 'api.vpnhistory.all', 'uses' => 'VpnhistoryController@allVpnhistory']);
    Route::get('rawdatavpn/all', ['as' => 'api.rawdatavpn.all', 'uses' => 'VpnhistoryController@allRawdatavpn']);

    // doctors
    Route::get('doctors/showpage/{page?}', ['as' => 'api.doctors.showpage', 'uses' => 'DoctorsController@showPage']);
    Route::get('doctors/alldoctors', ['as' => 'api.doctors.alldoctors', 'uses' => 'DoctorsController@allDoctors']);
    Route::resource('doctors', 'DoctorsController');

    // telegram-laravel
    // Route::get('/', 'TelegramController@getHome');
    Route::get('get-updates', ['as' => 'api.getupdates', 'uses' => 'TelegramController@getUpdates']);
    Route::get('telegram-send', ['as' => 'api.telegramsend', 'uses' => 'TelegramController@getSendMessage']);
    Route::post('telegram-send', ['as' => 'api.telegramsend', 'uses' => 'TelegramController@postSendMessage']);

    // email message
    Route::post('mailbyqueue', ['as' => 'api.mailbyqueue', 'uses' => 'MailController@mailByQueue']);

    // mresults
    Route::patch('mresults/savenote/{pid?}', ['as' => 'api.mresults.savenote', 'uses' => 'MresultsController@saveNote']);
    Route::patch('mresults/saveblood/{pid?}', ['as' => 'api.mresults.saveblood', 'uses' => 'MresultsController@saveBlood']);
    Route::patch('mresults/savebsrange/{pid?}', ['as' => 'api.mresults.savebsrange', 'uses' => 'MresultsController@saveBsrange']);
    Route::patch('mresults/savebatch/{pid?}', ['as' => 'api.mresults.savebatch', 'uses' => 'MresultsController@saveBatch']);
    Route::get('mresults/showbsrange/{pid?}', ['as' => 'api.mresults.showbsrange', 'uses' => 'MresultsController@showBsrange']);
    Route::get('mresults/showmr/{pid?}/{fdate?}/{tdate?}', ['as' => 'api.mresults.showmr', 'uses' => 'MresultsController@showMR']);
    Route::get('mresults/shownote/{pid?}/{fdate?}/{tdate?}', ['as' => 'api.mresults.showpid', 'uses' => 'MresultsController@showNote']);
    Route::get('mresults/showpid/{pid?}/{mrdate?}', ['as' => 'api.mresults.showpid', 'uses' => 'MresultsController@showPid']);
    Route::get('mresults/showblood/{pid?}/{mrdate?}/{mid?}', ['as' => 'api.mresults.showblood', 'uses' => 'MresultsController@showBlood']);
    Route::get('mresults/showbgbydate/{pid?}/{sdate?}/{edate?}', ['as' => 'api.mresults.showbgbydate', 'uses' => 'MresultsController@showBGbyDate']);
    Route::get('mresults/showbgbyadd/{pid?}/{sdate?}/{edate?}', ['as' => 'api.mresults.showbgbyadd', 'uses' => 'MresultsController@showBGbyAdd']);
    Route::delete('mresults/destroybatch/{id?}', ['as' => 'api.mresults.destroybatch', 'uses' => 'MresultsController@destroyBatch']);
    //Route::delete('mresults/destroybatch/{id?}', function () { echo 'hi';});
    //Route::resource('mresults', 'MresultsController');

});


/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
Route::group(['prefix' => 'dashboard', 'middleware' => 'authorized:view-dashboard'], function () {
*/
Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'timeout']], function () {
    Route::get('/{vue_capture?}', function () {
        return view('admin.index');
    })->where('vue_capture', '[\/\w\.-]*');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('auth/logout', 'Auth\AuthController@logout');
Route::auth();
