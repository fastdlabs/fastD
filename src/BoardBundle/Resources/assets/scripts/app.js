! function() {
    "use strict";
    angular.module("app", ["ngRoute", "ngAnimate", "ngSanitize", "ui.bootstrap", "ui.select", "textAngular", "easypiechart", "angular-skycons", "angular-loading-bar", "FBAngular", "app.ctrls", "app.directives", "app.services", "app.ui.ctrls", "app.ui.form.ctrls", "app.ui.form.directives", "app.ui.table.ctrls", "app.chart.ctrls", "app.chart.directives", "app.todo", "app.email.ctrls"]).config(["uiSelectConfig", function(uiSelectConfig) {
        uiSelectConfig.theme = "bootstrap"
    }]).config(["cfpLoadingBarProvider", function(cfpLoadingBarProvider) {
        cfpLoadingBarProvider.includeSpinner = !1, cfpLoadingBarProvider.latencyThreshold = 50
    }]).config(["$routeProvider", "$locationProvider", function($routeProvider) {
        function setRoutes(route) {
            var url = "/" + route,
                config = {
                    templateUrl: route
                };
            return $routeProvider.when(url, config), $routeProvider
        }
        var routes = ["dashboard", "widgets", "ui/buttons", "ui/typography", "ui/panels", "ui/grids", "ui/icons", "ui/misc", "ui/tabs", "ui/toasts", "forms/form-elems", "forms/form-validation", "forms/form-wizard", "charts/charts", "pages/404", "pages/login", "pages/register", "pages/invoice", "pages/lock-screen", "pages/forget-pass", "pages/timeline", "pages/profile", "tables/data-table", "tables/static-table", "email/inbox"];
        routes.forEach(function(route) {
            setRoutes(route)
        }), $routeProvider.when("/", {
            redirectTo: "/dashboard"
        }).when("/404", {
            templateUrl: "views/pages/404.html"
        }).otherwise({
            redirectTo: "/404"
        })
    }])
}();


! function() {
    "use strict";
    angular.module("app.chart.ctrls", []).controller("ChartistDemoCtrl", ["$scope", "$interval", function($scope) {
        $scope.linedata = {
            labels: ["W1", "W2", "W3", "W4", "W5", "W6", "W7", "W8", "W9", "W10"],
            series: [
                [1, 2, 4, 8, 6, -2, -1, -4, -6, -2]
            ]
        }, $scope.lineopts = {
            axisY: {
                offset: 25,
                labelOffset: {
                    y: 5
                }
            }
        }, $scope.areadata = {
            labels: [1, 2, 3, 4, 5, 6, 7, 8],
            series: [
                [1, 2, 3, 1, -2, 0, 1, 0],
                [-2, -1, -2, -1, -2.5, -1, -2, -1],
                [0, 0, 0, 1, 2, 2.5, 2, 1],
                [2.5, 2, 1, .5, 1, .5, -1, -2.5]
            ]
        }, $scope.areaopts = angular.extend({
            showArea: !0,
            showLine: !1,
            fullWidth: !0,
            showPoint: !1,
            axisX: {
                showLabel: !1,
                showGrid: !1
            }
        }, $scope.lineopts), $scope.bipolardata = {
            labels: ["W1", "W2", "W3", "W4", "W5", "W6", "W7", "W8", "W9", "W10"],
            series: [
                [1, 2, 4, 8, 6, -2, -1, -4, -6, -2]
            ]
        }, $scope.bardata = {
            labels: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            series: [
                [5, 4, 3, 7, 5, 10, 3],
                [3, 2, 9, 5, 4, 6, 4]
            ]
        }, $scope.stackbardata = {
            labels: ["Q1", "Q2", "Q3", "Q4"],
            series: [
                [8e5, 12e5, 14e5, 13e5],
                [2e5, 4e5, 5e5, 3e5],
                [1e5, 2e5, 4e5, 6e5]
            ]
        }, $scope.stackbaropts = {
            stackBars: !0,
            axisY: {
                labelInterpolationFnc: function(value) {
                    return value / 1e3 + "k"
                }
            }
        }, $scope.stackdraw = function(data) {
            "bar" === data.type && data.element.attr({
                style: "stroke-width: 40px"
            })
        }, $scope.piechartdata = {
            series: [20, 30, 25, 25],
            labels: ["20%", "30%", "25%", "25%"]
        }, $scope.donutdata = {
            series: [48, 17, 19, 16],
            labels: ["Chrome: 48%", "Firefox: 17%", "IE: 19%", "Other: 16%"]
        }, $scope.donutopts = {
            donut: !0,
            donutWidth: 30,
            startAngle: 0,
            total: 0,
            showLabel: !0,
            labelOffset: 25,
            labelDirection: "explode"
        }, $scope.donutdraw = function(data) {
            var colors = ["#6e94ea", "#5bb2ee", "#4bdbaa", "#cce386", "#998f90"];
            "label" == data.type && (data.element._node.style.fill = colors[data.index])
        }, $scope.peekdraw = function(data) {
            "bar" === data.type && (data.group.append(new Chartist.Svg("circle", {
                cx: data.x2,
                cy: data.y2,
                r: 2 * Math.abs(data.value) + 3,
                style: "fill: #4bdbaa"
            }, "ct-slice")), data.element._node.style.stroke = "#4bdbaa")
        }, $scope.epcOpts = {
            size: 180,
            lineWidth: 12,
            lineCap: "square",
            barColor: "#38b4ee"
        }, $scope.epcPercent = 80
    }])
}();


! function() {
    "use strict";
    angular.module("app.chart.directives", []).directive("chartist", [function() {
        return {
            restrict: "EA",
            transclude: !0,
            scope: {
                type: "@",
                opts: "=",
                data: "=",
                resOpts: "=",
                tip: "=",
                ondraw: "&",
                oncreated: "&"
            },
            replace: !0,
            template: "<div class='ct-chart ng-transclude'></div>",
            link: function(scope, el) {
                var chartist = new Chartist[scope.type](el[0], scope.data, scope.opts, scope.resOpts);
                scope.$on("chartist.update", function(e, data) {
                    chartist.update(data)
                }), scope.$watch(scope.data, function(newData, oldData) {
                    angular.equals(newData, oldData) || chartist.update(newData)
                }), chartist.on("draw", function(data) {
                    scope.ondraw({
                        data: data
                    })
                }), chartist.on("created", function(data) {
                    if (scope.oncreated({
                            data: data
                        }), scope.tip) {
                        var type;
                        "Line" == scope.type ? type = el.find(".ct-point") : "Bar" == scope.type ? type = el.find(".ct-bar") : "Pie" == scope.type && (type = el.find(".ct-slice")), el.find(".tooltip").remove();
                        var tooltip = el.append("<div class='tooltip in'></div>").find(".tooltip");
                        type.on("mouseenter", function() {
                            var value, seriesName, self = $(this),
                                parent = self.parent();
                            "Pie" === scope.type ? (seriesName = parent.find(".ct-label").html(), value = "") : (scope.type = "Line") && (value = self.attr("ct:value"), seriesName = parent.attr("ct:series-name") || "", seriesName && (seriesName += " : ")), tooltip.html("<div class='tooltip-inner'>" + seriesName + value + "</div>").show()
                        }), type.on("mouseleave", function() {
                            tooltip.hide()
                        }), el.on("mousemove", function(e) {
                            tooltip.css({
                                left: (e.offsetX || e.originalEvent.layerX) - tooltip.width() / 2 - 10,
                                top: (e.offsetY || e.originalEvent.layerY) - tooltip.height() - 10
                            })
                        })
                    }
                })
            }
        }
    }])
}();


! function() {
    "use strict";
    angular.module("app.email.ctrls", []).controller("EmailCtrl", ["$scope", "$modal", function($scope, $modal) {
        $scope.labelColors = ["#5974d9", "#19c395", "#fc3644", "#232429", "#f1d44b"], $scope.labels = [{
            type: "Work",
            color: $scope.labelColors[0]
        }, {
            type: "Reciept",
            color: $scope.labelColors[1]
        }, {
            type: "My Data",
            color: $scope.labelColors[2]
        }], $scope.newlabel = "", $scope.emails = [{
            subject: "Your order has been shipped. Order No - 1343",
            content: "Please collect the item from your mentioned address",
            unread: !0,
            sender: "Flipkart.com",
            date: "30 Sep",
            attachment: !0
        }, {
            subject: "Meetup at C.P, New Delhi",
            content: "Lorem ipsum dolar sit amet...",
            unread: !1,
            sender: "Organizer.com",
            date: "25 Nov",
            attachment: !1
        }, {
            subject: "Calling all android developers to join me",
            content: "Pellentesque habitant morbi tristique senectus et netus...",
            unread: !0,
            sender: "android.io",
            date: "30 Dec",
            attachment: !1
        }, {
            subject: "Meetup at C.P, New Delhi",
            content: "Lorem ipsum dolar sit amet...",
            unread: !1,
            sender: "Organizer.com",
            date: "25 Nov",
            attachment: !1
        }, {
            subject: "RE: Question about account information V334RE99e: s3ss",
            content: "Hi, Thanks for the reply, I want to know something....",
            unread: !1,
            sender: "trigger.io",
            date: "29 Dec",
            attachment: !1
        }], $scope.addLabel = function() {
            var l = $scope.labelColors.length,
                c = $scope.labelColors[Math.floor(Math.random() * l)];
            $scope.newlabel && $scope.labels.push({
                type: $scope.newlabel,
                color: c
            }), $scope.newlabel = ""
        }, $scope.compose = function() {
            $modal.open({
                templateUrl: "views/email/compose.html",
                size: "lg",
                controller: "EmailCtrl",
                resolve: function() {}
            })
        }, $scope.composeClose = function() {
            $scope.$close()
        }
    }])
}();


! function() {
    "use strict";
    angular.module("app.ui.form.ctrls", []).controller("UISelectDemoCtrl", ["$scope", function($s) {
        $s.person = {}, $s.people = [{
            name: "Adam",
            email: "adam@mail.com"
        }, {
            name: "Amalie",
            email: "amalie@mail.com"
        }, {
            name: "Nicolás",
            email: "nicolas@mail.com"
        }, {
            name: "Wladimir",
            email: "wladimir@mail.com"
        }, {
            name: "Samantha",
            email: "samantha@mail.com"
        }, {
            name: "Estefanía",
            email: "estefanía@mail.com"
        }, {
            name: "Natasha",
            email: "natasha@mail.com"
        }, {
            name: "Nicole",
            email: "nicole@mail.com"
        }, {
            name: "Adrian",
            email: "adrian@mail.com"
        }], $s.state = {}, $s.timezone = [{
            tag: 1,
            name: "Alaska"
        }, {
            tag: 1,
            name: "Hawaii"
        }, {
            tag: 2,
            name: "California"
        }, {
            tag: 2,
            name: "Nevada"
        }, {
            tag: 2,
            name: "Oregon"
        }, {
            tag: 2,
            name: "Washington"
        }, {
            tag: 3,
            name: "Arizona"
        }, {
            tag: 3,
            name: "Colorado"
        }, {
            tag: 3,
            name: "Idaho"
        }, {
            tag: 3,
            name: "Montana"
        }, {
            tag: 3,
            name: "Nebraska"
        }, {
            tag: 3,
            name: "New Mexico"
        }, {
            tag: 3,
            name: "North Dakota"
        }, {
            tag: 3,
            name: "Utah"
        }, {
            tag: 3,
            name: "Wyoming"
        }, {
            tag: 4,
            name: "Alabama"
        }, {
            tag: 4,
            name: "Arkansas"
        }, {
            tag: 4,
            name: "Illinois"
        }, {
            tag: 4,
            name: "Iowa"
        }, {
            tag: 4,
            name: "Kansas"
        }, {
            tag: 4,
            name: "Kentucky"
        }, {
            tag: 4,
            name: "Louisiana"
        }, {
            tag: 4,
            name: "Minnesota"
        }, {
            tag: 4,
            name: "Mississippi"
        }, {
            tag: 4,
            name: "Missouri"
        }], $s.timezoneFn = function(item) {
            switch (item.tag) {
                case 1:
                    return "Alaskan/Hawaiian Time Zone";
                case 2:
                    return "Pacific Time Zone";
                case 3:
                    return "Moutain Time Zone";
                case 4:
                    return "Central Time Zone"
            }
        }, $s.availableColors = ["Red", "Green", "Blue", "Yellow", "Magenta", "Maroon", "Umbra", "Turquoise", "Array of Strings"], $s.multipleDemo = {}, $s.multipleDemo.colors = ["Blue", "Red", "Array of Strings"], $s.multipleDemo.selectedPeopleWithGroupBy = [$s.people[8], $s.people[0]], $s.someGroupFn = function(item) {
            return item.name[0] >= "A" && item.name[0] <= "M" ? "From A - M" : item.name[0] >= "N" && item.name[0] <= "Z" ? "From N - Z" : void 0
        }
    }]).controller("DatepickerDemoCtrl", ["$scope", function($scope) {
        $scope.open = function($event) {
            $event.preventDefault(), $event.stopPropagation(), $scope.opened = !0
        }
    }]).controller("TypeaheadDemoCtrl", ["$scope", function($scope) {
        $scope.selected = void 0, $scope.states = ["Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Dakota", "North Carolina", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"]
    }]).controller("FormWizardCtrl", ["$scope", function($scope) {
        $scope.steps = [!0, !1, !1], $scope.stepNext = function(index) {
            for (var i = 0; i < $scope.steps.length; i++) $scope.steps[i] = !1;
            $scope.steps[index] = !0
        }, $scope.stepReset = function() {
            $scope.steps = [!0, !1, !1]
        }
    }])
}();


! function() {
    "use strict";
    angular.module("app.ui.form.directives", []).directive("uiRangeSlider", [function() {
        return {
            restrict: "A",
            link: function(scope, elem) {
                elem.slider()
            }
        }
    }])
}();


! function() {
    "use strict";
    angular.module("app.ctrls", []).controller("AppCtrl", ["$rootScope", "$scope", function($rs, $scope) {
        var mm = window.matchMedia("(max-width: 767px)");
        $rs.isMobile = mm.matches ? !0 : !1, $rs.safeApply = function(fn) {
            var phase = this.$root.$$phase;
            "$apply" == phase || "$digest" == phase ? fn && "function" == typeof fn && fn() : this.$apply(fn)
        }, mm.addListener(function(m) {
            $rs.safeApply(function() {
                $rs.isMobile = m.matches ? !0 : !1
            })
        }), $scope.themeActive = "theme-one", $scope.onThemeChange = function(theme) {
            $scope.themeActive = theme
        }
    }]).controller("HeadCtrl", ["$scope", "Fullscreen", function($scope, Fullscreen) {
        $scope.toggleSidebar = function() {
            $scope.sidebarOpen = $scope.sidebarOpen ? !1 : !0
        }, $scope.fullScreen = function() {
            Fullscreen.isEnabled() ? Fullscreen.cancel() : Fullscreen.all()
        }
    }]).controller("NavCtrl", ["$scope", "$rootScope", function($scope) {
        $scope.isCollapsed = !1
    }]).controller("DashboardCtrl", ["$scope", "$rootScope", function($scope) {
        $scope.linedata = {
            labels: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            series: [{
                name: "Earnings",
                data: [100, 150, 90, 40, 30, 50, 40]
            }, {
                name: "Downloads",
                data: [50, 100, 40, 25, 20, 35, 30]
            }]
        }, $scope.lineopts = {
            axisY: {
                offset: 25,
                labelOffset: {
                    y: 5
                }
            },
            axisX: {
                showGrid: !1
            },
            showArea: !0,
            showLine: !1,
            showPoint: !0,
            fullWidth: !0
        }, $scope.serverpieoptions = {
            barColor: "#5974d9"
        }, $scope.serverpiepercent = 80, $scope.bouncepiepercent = 40, $scope.weathertoday = {
            icon: Skycons.PARTLY_CLOUDY_DAY,
            size: 48,
            color: "#38B4EE"
        }, $scope.forecastDetails = [{
            type: "Wind:",
            value: "7mph"
        }, {
            type: "Humidity:",
            value: "46%"
        }, {
            type: "Dew Pt:",
            value: "44"
        }, {
            type: "Visibility:",
            value: "1mi"
        }, {
            type: "Pressure:",
            value: "1015 mb"
        }, {
            type: "Precipitation",
            value: "55%"
        }], $scope.weatherweeks = [{
            icon: Skycons.PARTLY_CLOUDY_DAY,
            size: 32,
            color: "#fff",
            day: "Tue",
            temp: "34"
        }, {
            icon: Skycons.RAIN,
            size: 32,
            color: "#fff",
            day: "Wed",
            temp: "28"
        }, {
            icon: Skycons.SNOW,
            size: 32,
            color: "#fff",
            day: "Thu",
            temp: "4"
        }, {
            icon: Skycons.CLEAR_NIGHT,
            size: 32,
            color: "#fff",
            day: "Fri",
            temp: "40"
        }, {
            icon: Skycons.FOG,
            size: 28,
            color: "#fff",
            day: "Sat",
            temp: "-3"
        }, {
            icon: Skycons.SLEET,
            size: 28,
            color: "#fff",
            day: "Sun",
            temp: "18"
        }]
    }]).controller("PageProfileCtrl", ["$scope", function($scope) {
        $scope.linedata = {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Nov", "Dec"],
            series: [{
                data: [50, 80, 100, 90, 120, 50, 80, 56, 135, 75, 148]
            }]
        }, $scope.lineopts = {
            axisY: {
                offset: 25,
                labelOffset: {
                    y: 5
                }
            },
            axisX: {
                showGrid: !1,
                labelOffset: {
                    x: 10
                }
            }
        }
    }])
}();


! function() {
    "use strict";
    angular.module("app.directives", []).directive("toggleNavMin", ["$rootScope", function($rs) {
        return function(scope, el) {
            var app = $("#app");
            $rs.$watch("isMobile", function() {
                $rs.isMobile && app.removeClass("nav-min")
            }), el.on("click", function(e) {
                $rs.isMobile || (app.toggleClass("nav-min"), $rs.$broadcast("nav.reset"), $rs.$broadcast("chartist.update")), e.preventDefault()
            })
        }
    }]).directive("collapseNavAccordion", [function() {
        return {
            restrict: "A",
            link: function(scope, el) {
                var lists = el.find("ul").parent("li"),
                    a = lists.children("a"),
                    listsRest = el.children("ul").children("li").not(lists),
                    aRest = listsRest.children("a"),
                    app = $("#app"),
                    stopClick = 0;
                a.on("click", function(e) {
                    if (e.timeStamp - stopClick > 300) {
                        if (app.hasClass("nav-min") && window.innerWidth > 767) return;
                        var self = $(this),
                            parent = self.parent("li");
                        a.not(self).next("ul").slideUp(), self.next("ul").slideToggle(), lists.not(parent).removeClass("open"), parent.toggleClass("open"), stopClick = e.timeStamp
                    }
                    e.preventDefault()
                }), aRest.on("click", function() {
                    var parent = aRest.parent("li");
                    lists.not(parent).removeClass("open").find("ul").slideUp()
                }), scope.$on("nav.reset", function(e) {
                    a.next("ul").removeAttr("style"), lists.removeClass("open"), e.preventDefault()
                })
            }
        }
    }]).directive("toggleOffCanvas", ["$rootScope", function() {
        return {
            restrict: "A",
            link: function(scope, el) {
                el.on("click", function() {
                    $("#app").toggleClass("on-canvas")
                })
            }
        }
    }]).directive("highlightActive", ["$location", function($location) {
        return {
            restrict: "A",
            link: function(scope, el) {
                var links = el.find("a"),
                    path = function() {
                        return $location.path()
                    },
                    highlightActive = function(links, path) {
                        var path = "#" + path;
                        angular.forEach(links, function(link) {
                            var link = angular.element(link),
                                li = link.parent("li"),
                                href = link.attr("href");
                            li.hasClass("active") && li.removeClass("active"), 0 == path.indexOf(href) && li.addClass("active")
                        })
                    };
                highlightActive(links, $location.path()), scope.$watch(path, function(newVal, oldVal) {
                    newVal != oldVal && highlightActive(links, $location.path())
                })
            }
        }
    }]).directive("uiCheckbox", [function() {
        return {
            restrict: "A",
            link: function(scope, el) {
                el.children().on("click", function(e) {
                    el.hasClass("checked") ? (el.removeClass("checked"), el.children().removeAttr("checked")) : (el.addClass("checked"), el.children().attr("checked", !0)), e.stopPropagation()
                })
            }
        }
    }]).directive("customScrollbar", ["$interval", function($interval) {
        return {
            restrict: "A",
            link: function(scope, el) {
                scope.$isMobile || (el.perfectScrollbar({
                    suppressScrollX: !0
                }), $interval(function() {
                    el[0].scrollHeight >= el[0].clientHeight && el.perfectScrollbar("update")
                }, 60))
            }
        }
    }]).directive("customPage", [function() {
        return {
            restrict: "A",
            controller: ["$scope", "$element", "$location", function($scope, $element, $location) {
                var path = function() {
                        return $location.path()
                    },
                    addBg = function(path) {
                        switch ($element.removeClass("body-full"), path) {
                            case "/404":
                            case "/pages/404":
                            case "/pages/login":
                            case "/pages/register":
                            case "/pages/forget-pass":
                            case "/pages/lock-screen":
                                $element.addClass("body-full")
                        }
                    };
                addBg($location.path()), $scope.$watch(path, function(newVal, oldVal) {
                    angular.equals(newVal, oldVal) || addBg($location.path())
                })
            }]
        }
    }])
}();


! function() {
    "use strict";
    angular.module("app.services", [])
}();


! function() {
    "use strict";
    angular.module("app.ui.table.ctrls", []).controller("ResponsiveTableDemoCtrl", ["$scope", function($scope) {
        $scope.responsiveData = [{
            post: "My First Blog",
            author: "Johnny",
            categories: "WebDesign",
            tags: ["wordpress", "blog"],
            date: "20-3-2004",
            tagColor: "default"
        }, {
            post: "How to Design",
            author: "Jenifer",
            categories: "design",
            tags: ["photoshop", "illustrator"],
            date: "2-4-2012",
            tagColor: "primary"
        }, {
            post: "Something is missing",
            author: "Joe",
            categories: "uncategorized",
            tags: ["abc", "def", "ghi"],
            date: "20-5-2013",
            tagColor: "success"
        }, {
            post: "Learn a new language",
            author: "Rinky",
            categories: "language",
            tags: ["C++", "Java", "PHP"],
            date: "10-5-2014",
            tagColor: "danger"
        }, {
            post: "I love singing. Do you?",
            author: "AJ",
            categories: "singing",
            tags: ["music"],
            date: "2-10-2014",
            tagColor: "info"
        }]
    }]).controller("DataTableCtrl", ["$scope", "$filter", function($scope, $filter) {
        $scope.datas = [{
            engine: "Gecko",
            browser: "Firefox 3.0",
            platform: "Win 98+/OSX.2+",
            version: 1.7,
            grade: "A"
        }, {
            engine: "Gecko",
            browser: "Firefox 5.0",
            platform: "Win 98+/OSX.2+",
            version: 1.8,
            grade: "A"
        }, {
            engine: "KHTML",
            browser: "Konqureror 3.5",
            platform: "KDE 3.5",
            version: 3.5,
            grade: "A"
        }, {
            engine: "Presto",
            browser: "Opera 8.0",
            platform: "Win 95+/OS.2+",
            version: "-",
            grade: "A"
        }, {
            engine: "Misc",
            browser: "IE Mobile",
            platform: "Windows Mobile 6",
            version: "-",
            grade: "C"
        }, {
            engine: "Trident",
            browser: "IE 5.5",
            platform: "Win 95+",
            version: 5,
            grade: "A"
        }, {
            engine: "Trident",
            browser: "IE 6",
            platform: "Win 98+",
            version: 7,
            grade: "A"
        }, {
            engine: "Webkit",
            browser: "Safari 3.0",
            platform: "OSX.4+",
            version: 419.3,
            grade: "A"
        }, {
            engine: "Webkit",
            browser: "iPod Touch / iPhone",
            platform: "OSX.4+",
            version: 420,
            grade: "B"
        }];
        for (var prelength = $scope.datas.length, i = prelength; 100 > i; i++) {
            var rand = Math.floor(Math.random() * prelength);
            $scope.datas.push($scope.datas[rand])
        }
        $scope.searchKeywords = "", $scope.filteredData = [], $scope.row = "", $scope.numPerPageOpts = [5, 7, 10, 25, 50, 100], $scope.numPerPage = $scope.numPerPageOpts[1], $scope.currentPage = 1, $scope.currentPageStores = [], $scope.select = function(page) {
            var start = (page - 1) * $scope.numPerPage,
                end = start + $scope.numPerPage;
            $scope.currentPageStores = $scope.filteredData.slice(start, end)
        }, $scope.onFilterChange = function() {
            $scope.select(1), $scope.currentPage = 1, $scope.row = ""
        }, $scope.onNumPerPageChange = function() {
            $scope.select(1), $scope.currentPage = 1
        }, $scope.onOrderChange = function() {
            $scope.select(1), $scope.currentPage = 1
        }, $scope.search = function() {
            $scope.filteredData = $filter("filter")($scope.datas, $scope.searchKeywords), $scope.onFilterChange()
        }, $scope.order = function(rowName) {
            $scope.row != rowName && ($scope.row = rowName, $scope.filteredData = $filter("orderBy")($scope.datas, rowName), $scope.onOrderChange())
        }, $scope.search(), $scope.select($scope.currentPage)
    }])
}();


! function() {
    "use strict";
    angular.module("app.todo", []).factory("todoStorage", [function() {
        var STORAGE_ID = "_todo-task",
            store = {
                todos: [],
                get: function() {
                    return JSON.parse(localStorage.getItem(STORAGE_ID))
                },
                put: function(todos) {
                    localStorage.setItem(STORAGE_ID, JSON.stringify(todos))
                }
            };
        return store
    }]).controller("TodoCtrl", ["$scope", "todoStorage", "$filter", function($s, store, $filter) {
        var demoTodos = [{
                title: "Eat healthy, Eat fresh",
                completed: !1
            }, {
                title: "Donate some money",
                completed: !0
            }, {
                title: "Wake up at 5:00 A.M",
                completed: !1
            }, {
                title: "Hangout with friends at 12:00",
                completed: !1
            }, {
                title: "Another todo on the list. Add as many you want.",
                completed: !1
            }],
            todos = $s.todos = store.get() || demoTodos;
        $s.newTodo = "", $s.remainingCount = $filter("filter")(todos, {
            completed: !1
        }).length, $s.editedTodo = null, $s.edited = !1, $s.todoshow = "all", $s.$watch("remainingCount == 0", function(newVal) {
            $s.allChecked = newVal
        }), $s.filter = function(filter) {
            switch (filter) {
                case "all":
                    $s.statusFilter = "";
                    break;
                case "active":
                    $s.statusFilter = {
                        completed: !1
                    }
            }
        }, $s.addTodo = function() {
            var newTodo = {
                title: $s.newTodo.trim(),
                completed: !1
            };
            0 !== newTodo.length && (todos.push(newTodo), store.put(todos), $s.newTodo = "", $s.remainingCount++)
        }, $s.editTodo = function(todo) {
            $s.editedTodo = todo, $s.edited = !0, $s.originalTodo = angular.extend({}, todo)
        }, $s.removeTodo = function(todo) {
            $s.remainingCount -= todo.completed ? 0 : 1, todos.splice(todos.indexOf(todo), 1), store.put(todos)
        }, $s.doneEditing = function(todo) {
            $s.editedTodo = null, $s.edited = !1, todo.title = todo.title.trim(), todo.title || $s.removeTodo(todo), store.put(todos)
        }, $s.revertEditing = function(todo) {
            todos[todos.indexOf(todo)] = $scope.originalTodo, $s.doneEditing($s.originalTodo)
        }, $s.toggleCompleted = function(todo) {
            $s.remainingCount += todo.completed ? -1 : 1, store.put(todos)
        }, $s.clearCompleted = function() {
            $s.todos = todos = todos.filter(function(val) {
                return !val.completed
            }), store.put(todos)
        }, $s.markAll = function(completed) {
            todos.forEach(function(todo) {
                todo.completed = !completed
            }), $s.remainingCount = completed ? todos.length : 0, store.put(todos)
        }
    }])
}();


! function() {
    "use strict";
    angular.module("app.ui.ctrls", []).controller("ToastDemoCtrl", ["$scope", "$interval", function($scope) {
        $scope.noti = {
            selected: "Danger"
        }, $scope.notifications = ["Warning", "Success", "Info", "Danger"], $scope.positionModel = "topRight", $scope.animModel = "fade";
        var MSGS = ["<strong>Error:</strong> Try submitting content again.", "a toast message...", "another toast message...", "<strong>Title:</strong> Toast message with <a href='#na' class='alert-link'>link</a>"],
            cntr = 0;
        $scope.toasts = [], $scope.closeAlert = function(index) {
            $scope.toasts.splice(index, 1)
        }, $scope.createToast = function() {
            $scope.toasts.push({
                anim: $scope.animModel,
                type: angular.lowercase($scope.noti.selected),
                msg: MSGS[cntr]
            }), cntr++, cntr > 3 && (cntr = 0)
        }
    }]).controller("AlertDemoCtrl", ["$scope", function($scope) {
        $scope.alerts = [{
            type: "warning",
            msg: "<strong>Warning:</strong> Backup all your drive."
        }, {
            type: "danger",
            msg: "Oh snap! Change a few things up and try submitting again."
        }, {
            type: "success",
            msg: "Well done! You successfully read this important alert message."
        }, {
            type: "info",
            msg: "<strong>Info:</strong> You have got mail."
        }], $scope.addAlert = function() {
            $scope.alerts.push({
                msg: "Another alert!"
            })
        }, $scope.closeAlert = function(index) {
            $scope.alerts.splice(index, 1)
        }
    }]).controller("TooltipDemoCtrl", ["$scope", function($scope) {
        $scope.dynamicTooltip = "Hello, World!", $scope.dynamicTooltipText = "dynamic", $scope.htmlTooltip = "I've been made <b>bold</b>!"
    }]).controller("PaginationDemoCtrl", ["$scope", function($scope) {
        $scope.totalItems = 64, $scope.currentPage = 4, $scope.setPage = function(pageNo) {
            $scope.currentPage = pageNo
        }, $scope.maxSize = 5, $scope.bigTotalItems = 175, $scope.bigCurrentPage = 1
    }]).controller("ProgressDemoCtrl", ["$scope", function($scope) {
        $scope.max = 200, $scope.random = function() {
            var type, value = Math.floor(100 * Math.random() + 1);
            type = 25 > value ? "success" : 50 > value ? "info" : 75 > value ? "warning" : "danger", $scope.showWarning = "danger" === type || "warning" === type, $scope.dynamic = value, $scope.type = type
        }, $scope.random(), $scope.randomStacked = function() {
            $scope.stacked = [];
            for (var types = ["success", "info", "warning", "danger"], i = 0, n = Math.floor(4 * Math.random() + 1); n > i; i++) {
                var index = Math.floor(4 * Math.random());
                $scope.stacked.push({
                    value: Math.floor(30 * Math.random() + 1),
                    type: types[index]
                })
            }
        }, $scope.randomStacked()
    }]).controller("RatingDemoCtrl", ["$scope", function($scope) {
        $scope.rate = 7, $scope.max = 10, $scope.isReadonly = !1, $scope.hoveringOver = function(value) {
            $scope.overStar = value, $scope.percent = 100 * (value / $scope.max)
        }
    }])
}();