<?php

namespace Isneezy\Celeiro;


use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Isneezy\Celeiro\Contracts\Filterable as FilterableContract;
use Isneezy\Celeiro\Filterable\Filterable;

class CeleiroServiceProvider extends ServiceProvider {

	public function boot() {
		$resourcesDir = __DIR__ . '/../resources';
		$this->publishes( [ "$resourcesDir/config/celeiro.php" => config_path( 'celeiro.php' ) ] );

		$this->mergeConfigFrom( "$resourcesDir/config/celeiro.php", 'celeiro' );
	}

	public function register() {
		$this->app->bind( FilterableContract::class, function () {
			$request = Container::getInstance()->make( Request::class );

			return Filterable::builder()
			                 ->paged( ! $request->has( 'unpaged') )
			                 ->page( $request->get( 'page', 1 ) )
			                 ->limit($request->get('limit', config('celeiro.limit')) )
			                 ->search( $request->get( 'q', '' ) )
			                 ->toFilterable();
		} );
	}
}