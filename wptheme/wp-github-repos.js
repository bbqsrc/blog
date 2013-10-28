(function($) {

if (!window.localStorage) {
  window.localStorage = {
    getItem: function (sKey) {
      if (!sKey || !this.hasOwnProperty(sKey)) { return null; }
      return unescape(document.cookie.replace(new RegExp("(?:^|.*;\\s*)" + escape(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=\\s*((?:[^;](?!;))*[^;]?).*"), "$1"));
    },
    key: function (nKeyId) {
      return unescape(document.cookie.replace(/\s*\=(?:.(?!;))*$/, "").split(/\s*\=(?:[^;](?!;))*[^;]?;\s*/)[nKeyId]);
    },
    setItem: function (sKey, sValue) {
      if(!sKey) { return; }
      document.cookie = escape(sKey) + "=" + escape(sValue) + "; expires=Tue, 19 Jan 2038 03:14:07 GMT; path=/";
      this.length = document.cookie.match(/\=/g).length;
    },
    length: 0,
    removeItem: function (sKey) {
      if (!sKey || !this.hasOwnProperty(sKey)) { return; }
      document.cookie = escape(sKey) + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
      this.length--;
    },
    hasOwnProperty: function (sKey) {
      return (new RegExp("(?:^|;\\s*)" + escape(sKey).replace(/[\-\.\+\*]/g, "\\$&") + "\\s*\\=")).test(document.cookie);
    }
  };
  window.localStorage.length = (document.cookie.match(/\=/g) || window.localStorage).length;
}

function WPGithubRepos(username, target, loadingText) {
    this.username = username;
    this.target = $(target);
    this.loadingText = loadingText || "Loading...";
}

WPGithubRepos.prototype.updateContent = function(json) {
    var node, anchorNode;
    
    this.target.empty();

    for (var i = 0, ii = json.data.length; i < ii; ++i) {
        node = $(document.createElement('li'));
        node.addClass('project');
        this.target.append(node);
       
        node.append($("<a href='"+json.data[i].html_url+"'></a>"));
        anchorNode = node.find('a');
        
        if (json.data[i].name) {
            anchorNode.append($("<div><strong class='title'>"+json.data[i].name+"</strong></div>"));
        }
        
        if (json.data[i].language) {
            node.addClass('language-' + json.data[i].language.toLowerCase());
        }

        if (json.data[i].pushed_at) {
            var date = new Date(json.data[i].pushed_at);
            anchorNode.append($("<div><small>Last push: " +  date.getDate() + "/" + (date.getMonth()+1) + "/" + date.getFullYear() + "</small></div>"));
        }
        
        if (json.data[i].description) {
            anchorNode.append($("<p class='desc'>"+json.data[i].description + "</p>"));
        }
        
        if (json.data[i].fork) {
            anchorNode.find("strong").addClass('fork');   
        }
    }

}

WPGithubRepos.prototype.load = function() {
    var json = localStorage.getItem('wpgithubrepos-' + this.username),
        self = this;
    
    this.target.empty().append(this.loadingText);

    if (json != null) {
        json = JSON.parse(json);
        if (new Date(json.expiry) < new Date()) {
            ajaxLookup();
        } else {
            this.updateContent(json);
        }

    } else {
        ajaxLookup();
    }

    function ajaxLookup() {
        $.ajax({
            type: 'GET',
            url: 'https://api.github.com/users/' + self.username + '/repos?per_page=200&sort=pushed',
            dataType: 'jsonp',
            jsonpCallback: 'callback',
            success: function(json) {
                json.expiry = +new Date() + 3600000;
                localStorage.setItem('wpgithubrepos-' + self.username, JSON.stringify(json));
                self.updateContent(json);
            }
        });
    }
}

this.WPGithubRepos = WPGithubRepos;

}).call(this, jQuery);
