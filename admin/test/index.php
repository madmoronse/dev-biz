<!doctype html>
<html lang="ru">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
</head>
<body>
TEST1

<div class="wg-search-box-container">
<h2 class="wg-search__title">Купить билет на поезд</h2>
<div id="ufs-railway-app"></div>
<script>
var UfsRailwayAppConfig = {
"isHashUrl":true,
"appDomain": "bizoutmax.ru",
"apiEndpoint": "https://api.ufs-online.ru/api/v1",
"trainUrls": {
"ru": "https://www.bizoutmax.ru/admin/test/#"
},
"trainRequestParams": {
"carrier": "ФПК" //пример
},
"lang":"ru"
};

</script>
<script src="https://spa.ufs-online.ru/kupit-zhd-bilety/widget.js"></script>
</div>
</body>
</html>
