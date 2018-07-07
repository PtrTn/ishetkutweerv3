var app = angular.module('ishetkutweer', ['yaru22.angular-timeago']);

var mainCtrl = ['$scope', function ($scope) {

    $scope.toggleMenu = function () {
        jQuery('.overlay').toggle();
        jQuery('.menu').toggle();
    };

}];
app.controller('mainCtrl', mainCtrl);

var forecastCtrl = ['$scope', function ($scope) {

    $scope.toggleMore = function () {
        jQuery('.forecast .extra').fadeToggle();
        $scope.toggleIcon(jQuery('.forecast .more i'));
        $scope.toggleText(jQuery('.forecast .more span'));
        return false;
    };

    $scope.toggleSummaryMsg = function () {
        jQuery('.text-summary .short-msg').slideToggle();
        jQuery('.text-summary .long-msg').slideToggle();
        $scope.toggleText(jQuery('.text-summary .more span'));
        $scope.toggleIcon(jQuery('.text-summary .more i'));
        return false;
    };

    $scope.toggleIcon = function (element) {
        element.toggleClass('fa-angle-right');
        element.toggleClass('fa-angle-up');
    };

    $scope.toggleText = function (element) {
        var text = element.text();
        element.text(text == "Meer" ? "Minder" : "Meer");
    };
}];
app.controller('forecastCtrl', forecastCtrl);

var menuCtrl = ['$scope', '$window', function ($scope, $window) {
    $scope.hasGeo = navigator.geolocation;

    $scope.useLocation = function (position) {
        if (typeof position.coords.latitude !== "undefined" && typeof position.coords.longitude !== "undefined") {
            $window.location.href = '/' + position.coords.latitude + '/' + position.coords.longitude;
        }
    };

    $scope.promptLocation = function ($event) {
        navigator.geolocation.getCurrentPosition($scope.useLocation);
        $event.stopPropagation();
    };
}];
app.controller('menuCtrl', menuCtrl);