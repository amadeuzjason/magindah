# ParseError - Internal Server Error

syntax error, unexpected token "public", expecting end of file

PHP 8.3.30
Laravel 12.53.0
127.0.0.1:8000

## Stack Trace

0 - app\Http\Controllers\DashboardController.php:42
1 - vendor\composer\ClassLoader.php:427
2 - vendor\laravel\framework\src\Illuminate\Routing\Route.php:1125
3 - vendor\laravel\framework\src\Illuminate\Routing\Route.php:1062
4 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:834
5 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:816
6 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:800
7 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:764
8 - vendor\laravel\framework\src\Illuminate\Routing\Router.php:753
9 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:200
10 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:180
11 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
12 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull.php:31
13 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
14 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TransformsRequest.php:21
15 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\TrimStrings.php:51
16 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
17 - vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePostSize.php:27
18 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
19 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance.php:109
20 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
21 - vendor\laravel\framework\src\Illuminate\Http\Middleware\HandleCors.php:61
22 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
23 - vendor\laravel\framework\src\Illuminate\Http\Middleware\TrustProxies.php:58
24 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
25 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Middleware\InvokeDeferredCallbacks.php:22
26 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
27 - vendor\laravel\framework\src\Illuminate\Http\Middleware\ValidatePathEncoding.php:26
28 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:219
29 - vendor\laravel\framework\src\Illuminate\Pipeline\Pipeline.php:137
30 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:175
31 - vendor\laravel\framework\src\Illuminate\Foundation\Http\Kernel.php:144
32 - vendor\laravel\framework\src\Illuminate\Foundation\Application.php:1220
33 - public\index.php:20
34 - vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php:23

## Request

GET /approvals

## Headers

* **host**: 127.0.0.1:8000
* **connection**: keep-alive
* **cache-control**: max-age=0
* **sec-ch-ua**: "Brave";v="147", "Not.A/Brand";v="8", "Chromium";v="147"
* **sec-ch-ua-mobile**: ?0
* **sec-ch-ua-platform**: "Windows"
* **upgrade-insecure-requests**: 1
* **user-agent**: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36
* **accept**: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8
* **sec-gpc**: 1
* **accept-language**: en-US,en;q=0.6
* **sec-fetch-site**: same-origin
* **sec-fetch-mode**: navigate
* **sec-fetch-user**: ?1
* **sec-fetch-dest**: document
* **referer**: http://127.0.0.1:8000/approvals/240
* **accept-encoding**: gzip, deflate, br, zstd
* **cookie**: remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d=eyJpdiI6IlREWDgzYkJQZnpJcld4dTd5NUR6TVE9PSIsInZhbHVlIjoiUDF0WHJWMVdOVjA1b2JPb0c1ZlA5Ny83K1VNWnJYRTQrT0VRVmo1VWcvYmF6MkZLbFZsRGNKblN2cHNXUStEbVRmYnZERHFDV2RpanJoKzVwYTZVSFF3eXJLbmNUZU8vV3lYZWZoVHhOZGlpaFBKMGJLTzVTVGxuR2VHSG1CSU4raEZOMllGbTlNTk1IaFpacENRRTJXNVZ3WnNLc3p4cE0yU3E1bmJPL3JaKzVUc0xwbVFJNWtRMDR4RzBKU2JvWWp1THBwQ0l2VTl4clZiVGJjUlNUdmN1VTgvTVA1ZzVoWUtGVE93U2ZvQT0iLCJtYWMiOiI0YzM4OWQ3M2I4MWUzMjA3ZDY3ZjE1ZDU3NmI2YTRiNjljZGU0Y2IzOTIxZGRmYWFlYTk4ZDUzY2Q4Y2FiZWFlIiwidGFnIjoiIn0%3D; XSRF-TOKEN=eyJpdiI6IkRIN0NxZTI2ZnNyUVRUdkhhK0Y5ZHc9PSIsInZhbHVlIjoiMExnWlp0NnJScllyVHROQTRpL3VWZXJ1bm1hMEt6OXdkZHNTRVVkb3cvenI5c1E1SllkTEYzRWJwVlRSZ1B1RG1YaFRjRlRyNEhLc2pTeTBuYnRvK1RMeC9Fejhrb1B5NVlSUWZUUFJCZW9YZm5ST25vekZ6NTZmQTAyZFRVYWwiLCJtYWMiOiIwNjllZGU4NTUyYTliNTU2MDUyYWI3YTIyMWZlMDE3YmJhNThjNDRiM2NkMmFjNTA1YzFkNTUwMmVjNGUyOGFlIiwidGFnIjoiIn0%3D; laravel-session=eyJpdiI6IjBlWjJGcTdKS1hjckRpVGk4S2ttNEE9PSIsInZhbHVlIjoia3dDNUU3cW56NHZwdHdocEtCUkloaGN3MFhVdmhBeTJpdEZTTkdoQXFFSy84eWJVb1RTZzhpRlYvcFNiUDlxSXNiUldrZHVybGpZUXNPakJwemo1STRFN0czNmRiL2JBZkhrdUNObm1haXBzb3lKMmFoWlpQR2pZNmw2N2VXWHciLCJtYWMiOiIxYmY2MzgzMTQzOGFiNGZlZDA2MDNmZDM1YjhhNzkwZTM3OThjYmVkNzE2YTNhYWE1MDMwZTg1MDRkMjNkYTVmIiwidGFnIjoiIn0%3D

## Route Context

controller: App\Http\Controllers\DashboardController@approvals
route name: approvals

## Route Parameters

No route parameter data available.

## Database Queries

No database queries detected.
