<!-- Put this script tag to the <head> of your page -->
<script type="text/javascript" src="//vk.com/js/api/openapi.js?152"></script>

<script type="text/javascript">
  VK.init({apiId: 6427982, onlyWidgets: true});
</script>

<!-- Put this div tag to the place, where the Comments block will be -->
<div id="vk_comments"></div>
<script type="text/javascript">
VK.Widgets.Comments("vk_comments", {limit: 10, attach: "*"});
</script>
<br><br>
<script type="text/javascript">
  VK.init({apiId: 6427982});
</script>
<div id="vk_like"></div>
<script type="text/javascript">
VK.Widgets.Like("vk_like", {type: "button"});
VK.Observer.subscribe("widgets.like.liked", function f()
{
alert ("Thank you for your like.");
});
</script>
<br><br>
<div id="vk_comments"></div>
<script type="text/javascript">
window.onload = function () {
 VK.init({apiId: 6427982, onlyWidgets: true});
 VK.Widgets.Comments('vk_comments', {width: 500, limit: 15}, 321);
}
</script>
<br><br>
		<script type="text/javascript">
		VK.init({apiId: 6427982, onlyWidgets: true});
		</script>

		<div id="vk_comments"></div>
		<script type="text/javascript">
		window.onload = function () {
		VK.init({apiId: 6427982, onlyWidgets: true});
		VK.Widgets.CommentsBrowse('vk_comments', {width: 500, limit: 5, mini: 0});
		}
		</script>
<br><br>

