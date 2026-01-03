<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<style>
.content-wrapper {
       margin-left: 260px;      
    margin-top: 60px;        
    padding: 25px;
    min-height: calc(100vh - 60px);
    background: #f3f4f6;
}</style>

<body class="bg-gray-100">

@include('dashboard.partials.header')

<div class="flex">

    @include('dashboard.partials.sidebar')

 



</div>

</body>
</html>
