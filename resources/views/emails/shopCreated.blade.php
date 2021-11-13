@component('mail::message')
Hello,

New Shop has Created!

Shop Name: {{ $namaToko }} <br>
Marketplace: {{ $marketplace }} <br>
Category: {{ $kategoriToko }} <br>
Supplier: {{ $supplier }} <br>
Design Code: {{ $design }} <br>
<br>
Dropshipper: <br>
Name: {{ $name }} <br>
Email: {{ $email }} <br>
Phone: {{ $phone }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
