function deliciousArticle(id) {
	try {
		var query = "?op=pluginhandler&plugin=delicious&method=getInfo&id=" + param_escape(id);
		var d = new Date();
		var ts = d.getTime();
		var w = window.open('backend.php?op=backend&method=loading', 'ttrss_tweet',
							"status=0,toolbar=0,location=0,width=500,height=400,scrollbars=1,menubar=0");
		new Ajax.Request("backend.php",	{
			parameters: query,
			onComplete: function(transport) {
				var ti = JSON.parse(transport.responseText);
				var share_url = 
					"https://delicious.com/save?v=5&provider=TinyTinyRSS&noui&jump=close&url=" + 
					encodeURIComponent( ti.link ) + 
					'&title=' + 
					encodeURIComponent( ti.title ) + 
					'&note=' + 
					encodeURIComponent( ( typeof ti.content != 'undefined' ? ti.content : "" ) )
					;
				w.location.href = share_url;
			} 
		});
	} catch (e) {
		exception_error("tweetArticle", e);
	}
}

