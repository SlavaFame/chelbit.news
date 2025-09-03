BX.ready(function () {
	BX.addCustomEvent('BX.Main.Filter:apply', function (filterId, action, filterInstance) {
		let url = new URL(window.location.href);
		BX.ajax.insertToNode(url.href, 'newsListContainer');
	});
});
