<?php

use App\Http\Controllers\Dashboard\RewardsSystemController;
use Illuminate\Support\Facades\Route;

Route::get( '/rewards-system', [ RewardsSystemController::class, 'list' ] )->name( ns()->routeName( 'ns.dashboard.rewards-list' ) ); // @todo update
Route::get( '/rewards-system/create', [ RewardsSystemController::class, 'create' ] )->name( ns()->routeName( 'ns.dashboard.rewards-create' ) );
Route::get( '/rewards-system/edit/{reward}', [ RewardsSystemController::class, 'edit' ] )->name( ns()->routeName( 'ns.dashboard.rewards-edit' ) );
