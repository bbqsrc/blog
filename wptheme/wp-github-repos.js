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

function WPGithubRepos(username, target) {
    this.username = username;
    this.target = $(target);
}

WPGithubRepos.prototype.updateContent = function(json) {
    var node;
    
    this.target.empty();

    for (var i = 0, ii = json.data.length; i < ii; ++i) {
        node = $(document.createElement('li'));
        node.addClass('project');
        this.target.append(node);
       
        node.append($("<a href='"+json.data[i].html_url+"'></a>"));
        node = node.find('a');
        if (json.data[i].name) {
            node.append($("<div><strong>"+json.data[i].name+"</strong></div>"));
        }
        
        if (json.data[i].updated_at) {
            var date = new Date(json.data[i].updated_at);
            node.append($("<div><small>Last update: " +  date.getDate() + "/" + date.getMonth() + "/" + date.getFullYear() + "</small></div>"));
        }
        
        if (json.data[i].description) {
            node.append($("<p class='desc'>"+json.data[i].description + "</p>"));
        }
        
        if (json.data[i].fork) {
            node.find("strong").addClass('fork');   
        }
    }

}

WPGithubRepos.prototype.load = function() {
    var json = localStorage.getItem('wpgithubrepos-' + this.username),
        self = this;

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
            url: 'https://api.github.com/users/' + self.username + '/repos?per_page=200&sort=updated',
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
