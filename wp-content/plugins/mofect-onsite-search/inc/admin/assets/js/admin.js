
var DEVICE_TYPE_IPHONE = "iPhone";
var DEVICE_TYPE_ANDROID = "Android";
var DEVICE_TYPE_IPAD = "iPad";
var DEVICE_OTHERS = "others";

var KEYWORDS_ITEMS = 20;
var RECENT_KEYWORD_ITEMS = 50;
var POPULAR_KEYWORD_ITEMS = 50;

function MossAdmin(adminData){
    this.adminData = adminData;

    /**
     * global user track dat.
     */
    this.g_trackData = [];

    this.g_keyword_chart_handler = undefined;

    this.keywordsChartData = {
        //keyword
        labels: [],

        //keyword searched times.
        data: []
    };

    this.popularKeywordsData = [];

    this.userLocationsData = [];

    this.deviceChartData = {
        //device typs.
        labels: [
            DEVICE_TYPE_IPHONE,
            DEVICE_TYPE_ANDROID,
            DEVICE_TYPE_IPAD,
            DEVICE_OTHERS
        ],

        //device type data mapping.
        data: [0, 0, 0, 0]
    };

    MossAdmin.prototype.init = function(){
        var that = this;
        jQuery.ajax ({
            url: that.adminData.ajax_url,  
            type: 'GET',
            data: {'action': 'moss_get_tracking_data'},
            success: function(results) {
                that.g_trackData = JSON.parse(results.data);
                if(that.g_trackData && Array.isArray(that.g_trackData)){
                    //sort.
                    that.g_trackData.sort(function(node, nextNode){
                        if(!node.data){
                            return 1;
                        }
            
                        if(!nextNode.data){
                            return -1;
                        }
            
                        return nextNode.data.length - node.data.length;
                    });

                    that.initGlobalChartsData();
                    that.renderPopularKeywordChart();
                    that.renderDeviceChart();
                    that.renderUserLocationMappingEchart();
                }
            }
        });
    }

    MossAdmin.prototype.initGlobalChartsData = function(){
        for(var mofect_i = 1; mofect_i <= this.g_trackData.length; mofect_i++){
            var tmpOneKeyword = this.g_trackData[mofect_i-1];
            var tmpKeywordName = tmpOneKeyword.keyword;
            var tmpKeywordData = tmpOneKeyword.data;
        
            //fill keyword chart data. 
            if(mofect_i <= KEYWORDS_ITEMS){
                this.keywordsChartData.labels.push(tmpKeywordName);
                if(!tmpKeywordData){
                    this.keywordsChartData.data.push(0);
                }else{
                    this.keywordsChartData.data.push(tmpKeywordData.length);
                }
            }
        
            //fill popular chart data
            if(mofect_i <= POPULAR_KEYWORD_ITEMS){
                this.popularKeywordsData.push(tmpOneKeyword);
            }
        
            for(var mofect_d_i = 0; mofect_d_i < tmpKeywordData.length; mofect_d_i++){
                var tmpUserData = tmpKeywordData[mofect_d_i];
        
                //fill device chart data.
                if(!tmpUserData.device){
                    this.deviceChartData.data[3] = this.deviceChartData.data[3] + 1;
                }else{
                    switch(tmpUserData.device.toUpperCase){
                        case DEVICE_TYPE_IPHONE.toUpperCase(): {
                            this.deviceChartData.data[0] = this.deviceChartData.data[0] + 1;
                            break;
                        }
                        case DEVICE_TYPE_ANDROID.toUpperCase(): {
                            this.deviceChartData.data[1] = this.deviceChartData.data[1] + 1;
                            break;
                        }
                        case DEVICE_TYPE_IPAD.toUpperCase(): {
                            this.deviceChartData.data[2] = this.deviceChartData.data[2] + 1;
                            break;
                        }
                        default: {
                            this.deviceChartData.data[3] = this.deviceChartData.data[3] + 1;
                        }
                    }
                }
        
                //fill user location echart data.
                if(tmpUserData.country){
                    var countryNodeIndex = this.userLocationsData.findIndex(function(node){
                        return tmpUserData.country == node.name;
                    });
        
                    if(countryNodeIndex >= 0){
                        this.userLocationsData[countryNodeIndex].value = this.userLocationsData[countryNodeIndex].value + 1;
                    }else{
                        this.userLocationsData.push({name: tmpUserData.country, value: 1});
                    }
                }
            }
        }
    }

    MossAdmin.prototype.renderPopularKeywordChart = function(){
        var that = this;
        if(this.popularKeywordsData.length <= 0){
            jQuery('#popular-keywords-table').append(
                '<div class="keywords-table-no-items">'+ this.adminData.no_records_message +'</div>'
            );
    
            return;
        }
    
        this.popularKeywordsData.forEach(function(tmpKeywordNode){
            var tmpKeywordName = tmpKeywordNode.keyword;
            var tmpKeywordData = tmpKeywordNode.data;
        
            var keywordStatus = false;
            if(tmpKeywordData){
                tmpKeywordData.forEach(function(tmpItem){
                    return tmpItem.status.toUpperCase() == 'TRUE' ? keywordStatus = true : "";
                });
            }
            jQuery('#popular-keywords-table').append(
                '<ul class="table">'+
                    '<li class="td">'+ tmpKeywordName +'</li>' + 
                    '<li class="td">'+ (keywordStatus ? 'Success' : "No result") +'</li>' + 
                    '<li class="td">'+ (tmpKeywordData ? tmpKeywordData.length : 0) +'</li>' + 
                    '<li class="td action"><a href="'+ that.adminData.edit_post_url + '?post='+ tmpKeywordNode.post_id +'&action=edit">View Detail</a></li>' + 
                '</ul>'
            );
        })
    }

    MossAdmin.prototype.renderRecentKeywordsChart = function(){
        var that = this;
        jQuery.ajax({
            url:  that.adminData.ajax_url,  
            type: 'GET',
            data: {'action': 'moss_get_recent_tracking_data'},
            success: function(results) {
                if(!results.data){
                    jQuery('#recent-keywords-table').append(
                        '<div class="keywords-table-no-items">'+ that.adminData.no_records_message +'</div>'
                    );
            
                    return;
                }

                var tmpData = JSON.parse(results.data);
                if(!tmpData || tmpData.length == 0){
                    jQuery('#recent-keywords-table').append(
                        '<div class="keywords-table-no-items">'+ that.adminData.no_records_message +'</div>'
                    );
            
                    return;
                }
            
                for(var mofect_recent_i = 1; mofect_recent_i <= tmpData.length; mofect_recent_i++){
                    var tmpOneKeyword = tmpData[mofect_recent_i-1];
                    var tmpKeywordName = tmpOneKeyword.keyword;
                    var tmpKeywordData = tmpOneKeyword.data;
                
                    //fill keyword chart data. 
                    if(mofect_recent_i <= RECENT_KEYWORD_ITEMS){
                        var keywordStatus = false;
                        if(tmpKeywordData){
                            tmpKeywordData.forEach(function(tmpItem){
                                return tmpItem.status ? keywordStatus = true : "";
                            });
                        }
            
                        jQuery('#recent-keywords-table').append(
                            '<ul class="table">'+
                                '<li class="td">'+ tmpKeywordName +'</li>' + 
                                '<li class="td">'+ (keywordStatus ? 'Success' : "No result") +'</li>' + 
                                '<li class="td">'+ (tmpKeywordData ? tmpKeywordData.length : 0) +'</li>' + 
                                '<li class="td action"><a href="'+ that.adminData.edit_post_url + '?post='+ tmpOneKeyword.post_id +'&action=edit">View Detail</a></li>' + 
                            '</ul>'
                        );
                    }
                }
            }
        })
    }

    MossAdmin.prototype.renderDeviceChart = function(){
        var device_area_element = document.getElementById("device-chart");
        if(!device_area_element){
            return;
        }
    
        var device_area = device_area_element.getContext('2d');
        new Chart(device_area, 
            {
                type: 'pie',
                data: {
                    datasets: [{
                        data: this.deviceChartData.data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                        ],
                        label: 'Dataset 1'
                    }],
                    labels: this.deviceChartData.labels
                },
                options: {
                    responsive: true
                }
            }
        );
    }

    MossAdmin.prototype.renderUserLocationMappingEchart = function(){
        var location_echart_dom_element = document.getElementById("location-echart");
        if(!location_echart_dom_element){
            return;
        }
        var location_echart = echarts.init(location_echart_dom_element, "", {
            height: 300
        });
        
        var echart_options =  {
            title: {
                text: 'Accessed users regions.',
                subtext: '',
                sublink: 'https://mofect.com/mofect-on-site-search',
                left: 'center',
                top: 'top'
            },
            tooltip: {
                trigger: 'item',
                formatter: function (params) {
                    if(params.value){
                        return params.name + '<br/> <div style="text-align: center">' + params.value + ' times' + '</div>';
                    }else{
                        return '<div style="text-align: center">' + 0 + ' times' + '</div>';
                    }
                    
                }
            },
            toolbox: {
                show: true,
                showTitle: true,
                orient: 'vertical',
                left: 'right',
                top: 'center',
                feature: {
                    dataView: {
                        readOnly: true,
                        title: "Data view",
                        lang: ['Data view', 'Turn off', 'refresh']
                    },
                    restore: {
                        title: 'restore'
                    },
                    saveAsImage: {
                        type: 'png',
                        name: "region",
                        title: "save as image",
                    }
                }
            },
            visualMap: {
                min: 0,
                max: 1000000,
                text:['High','Low'],
                realtime: false,
                calculable: true,
                inRange: {
                    color: ['lightskyblue','yellow', 'orangered']
                }
            },
            series: [
                {
                    name: 'current region',
                    type: 'map',
                    mapType: 'world',
                    roam: true,
                    itemStyle:{
                        emphasis:{
                            label:{
                                show:true
                            }
                        }
                    },
                    data: this.userLocationsData
                }
            ]
        };
    
        location_echart.setOption(echart_options, true);
    }

    MossAdmin.prototype.updateKeywordChartByDuration = function(){
        jQuery('.keyword-statistics-filter').click(function(e){
            jQuery('.keyword-statistics-filter').removeClass('active');
            jQuery(this).addClass('active');
            var action = e.currentTarget.dataset.ajaxAction;
            var data = {
                'action' : action
            };
        
            jQuery.ajax ({
                url:  mofect_admin_data.ajax_url,  
                type: 'GET',
                data: data,
        
                success:function(results) {
                    console.log(results);
                    var trackerData = results.data;
                    if(!trackerData){
                        return;
                    }
        
                    trackerData.sort(function(node, nextNode){
                        if(!node.data){
                            return 1;
                        }
            
                        if(!nextNode.data){
                            return -1;
                        }
            
                        return nextNode.data.length - node.data.length;
                    });
        
                    //fill keyword chart data. 
                    keywordsChartData.labels = [];
                    keywordsChartData.data = [];
                    if(g_keyword_chart_handler){
                        for(var tmp_i = 0; tmp_i < trackerData.length; tmp_i++){
                            var tmpOneKeyword = trackerData[tmp_i];
                            var tmpKeywordName = tmpOneKeyword.keyword;
                            var tmpKeywordData = tmpOneKeyword.data;
                
                            if(tmp_i <= KEYWORDS_ITEMS){
                                keywordsChartData.labels.push(tmpKeywordName);
                                if(!tmpKeywordData){
                                    keywordsChartData.data.push(0);
                                }else{
                                    keywordsChartData.data.push(tmpKeywordData.length);
                                }
                            }
                        }
        
                        g_keyword_chart_handler.destroy()
                        var keyword_area = document.getElementById("keywords-chart").getContext('2d');
                        g_keyword_chart_handler = new Chart(keyword_area, {
                            type: 'bar',
                            data: {
                                labels: keywordsChartData.labels,
                                datasets: [{
                                    label: 'search times',
                                    data: keywordsChartData.data,
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.2)',
                                        'rgba(54, 162, 235, 0.2)',
                                        'rgba(255, 206, 86, 0.2)',
                                        'rgba(75, 192, 192, 0.2)',
                                        'rgba(153, 102, 255, 0.2)'
                                    ],
                                    borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)',
                                        'rgba(255, 159, 64, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero:true
                                        }
                                    }]
                                }
                            }
                        });
            
                    }
                }
            });
        });
    }

    MossAdmin.prototype.renderKeywordChart = function(){
        var keyword_area_element = document.getElementById("keywords-chart");
        if(!keyword_area_element){
            return;
        }

        var keyword_area = keyword_area_element.getContext('2d');
        this.g_keyword_chart_handler = new Chart(keyword_area, {
            type: 'bar',
            data: {
                labels: this.keywordsChartData.labels,
                datasets: [{
                    label: 'search times',
                    data: this.keywordsChartData.data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
    }
}

if(jQuery('.mofect-admin-page').length){ //detect wether in admin page or not.
    var gMossAdmin = new MossAdmin(mofect_admin_data);
    gMossAdmin.init();
    gMossAdmin.renderRecentKeywordsChart();
}

