adminApp.controller('articleController', function($scope, $sce, $http, $attrs) {
    $scope.ctrlUrl = $attrs.url;
    
    $scope.addTag = function() {
        var dataObj = {
            tag : $scope.article.addTag
        };	
        var res = $http.post($scope.ctrlUrl, dataObj);
        res.success(function(data, status, headers, config) {
            if(data === false) {
                $scope.tagMessages = $sce.trustAsHtml('<p class="text-red">Wprowadzony tag już istnieje!</p>');
            } else {
                var articleTags = document.getElementById("article_tags");
                var option = document.createElement("option");
                option.value = data.id;
                option.text = data.name;
                articleTags.add(option);
                $scope.tagMessages = $sce.trustAsHtml('<p class="text-green">Poprawnie dodano nowy tag.</p>');
                $scope.article.addTag = null;
            }
        });
    };
    
});