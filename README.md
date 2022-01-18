
- add lumen's withFacades
- add lumen's withEloquent
- register the following service providers
```
$app->register(Fshangala\Auth2Ation\Auth2AtionServiceProvider::class);
$app->register(Fshangala\Auth2Ation\Auth\FAuthServiceProvider::class);
```
- add lumen's auth middlware
- run migrations