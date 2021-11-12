@component('mail::message')
Hello,

New Ticket has send from contact page

Name: {{ $data->name }} <br>
Email: {{ $data->email }} <br>
Message: {{ $data->pesan }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
