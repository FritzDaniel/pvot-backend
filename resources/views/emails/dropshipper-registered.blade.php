@component('mail::message')
Hello,

New Dropshipper has Registered to our Apps!

Name: {{ $data->name }} <br>
Email: {{ $data->email }} <br>
Phone: {{ $data->phone }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
