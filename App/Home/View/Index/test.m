<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
{!like.js!}
{$data},{$person}
<ul>

</ul>

<?php echo $pai * 2; ?>

{if $data == 'abc'}
我是abc
{elseif $data == 'def'}
我是def
{else}
我就是我,{$data}
{/if}

{#注释不会出现#}
12345-------
</body>
</html>