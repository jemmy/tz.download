<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <style>

    </style>
</head>
<body>

<form action="{{ route('download.add') }}" method="POST">

    {{ csrf_field() }}

    @if (session('info'))
        <div style="margin: 4px 0; background-color: rgb(212,255,212); padding: 8px">
            {{ session('info') }}
        </div>
    @endif

    @if (session('errors'))
        <div style="margin: 4px 0; padding: 8px">
            <strong style="color: red">{{ session('errors') }}</strong>
        </div>
    @endif

    <div style="padding: 8px">
        <label>
            Download url:
            <input name="url" type="text" required value="{{ old('url') }}" style="width: 25%"/>
        </label>
    </div>

    <div style="margin-bottom: 24px">
        <input type="submit" value="Add to query">
    </div>
</form>

<table width="100%" border="1">
    <thead>
    <tr>
        <td width="64">Id</td>
        <td width="100">Status</td>
        <td>Url</td>
        <td width="100">Download</td>
    </tr>
    </thead>
    <tbody>
    @foreach($downloads as $download)
        <tr>
            <td>{{ $download->id }}</td>
            <td>{{ $download->getStatusName() }}</td>
            <td>{{ $download->url }}</td>
            @if ($download->status === \App\Models\Download::DOWNLOAD_STATUS_COMPLETE)
                <td><a href="/download/{{ $download->id }}">GET</a></td>
            @else
                <td></td>
            @endif
        </tr>
    @endforeach
    </tbody>

</table>

</body>
</html>
