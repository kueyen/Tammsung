@php $config = [ 'appName' => config('app.name'), 'locale' => $locale = app()->getLocale(),
'locales' => config('app.locales'), 'githubAuth' => config('services.github.client_id'), ]; @endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ mix('dist/css/app.css') }}" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Mitr:wght@200;300;400;500&display=swap"
      rel="stylesheet"
    />
  </head>
  <body>
    <div id="app"></div>

    {{-- Global configuration object --}}
    <script>
      window.config = @json($config);
    </script>

    {{-- Load the application scripts --}}
    <script src="{{ mix('dist/js/app.js') }}"></script>
    <style>
      body,
      html,
      input,
      textarea,
      select,
      option {
        font-family: 'Mitr', sans-serif;
      }
    </style>
  </body>
</html>
