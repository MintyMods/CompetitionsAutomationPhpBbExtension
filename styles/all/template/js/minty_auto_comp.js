let sponsors = null;

window.addEventListener("DOMContentLoaded", function(){

    // different configs for different screen sizes
    var compactView = {
        xy: {
            nav_height: 80
        },
        config: {
            header: {
                rows: [
                    { 
                        cols: [
                            "prev",
                            "date",
                            "next",
                        ]
                    },
                    { 
                        cols: [
                            "day",
                            "week",
                            "month",
                            "year",
                            "spacer",
                            "today"
                        ]
                    }
                ]
            }
        },
        templates: {
            month_scale_date: scheduler.date.date_to_str("%D"),
            week_scale_date: scheduler.date.date_to_str("%D, %j"),
            event_bar_date: function(start,end,ev) {
                return "";
            }
            
        }
    };
    var fullView = {
        xy: {
            nav_height: 80
        },
        config: {
            header: [
                "day",
                "week",
                "month",
                "year",
                "date",
                "prev",
                "today",
                "next"
            ]
        },
        templates: {
            month_scale_date: scheduler.date.date_to_str("%l"),
            week_scale_date: scheduler.date.date_to_str("%l, %F %j"),
            event_bar_date: function(start,end,ev) {
                return "• <b>"+scheduler.templates.event_date(start)+"</b> ";
            }
        }
    };

    function getTemplates() {
        
    }

    function resetConfig(){
        var settings;
        if(window.innerWidth < 1000){
            settings = compactView;
        } else {
            settings = fullView;
        }
        scheduler.utils.mixin(scheduler.config, settings.config, true);
        scheduler.utils.mixin(scheduler.templates, settings.templates, true);
        scheduler.utils.mixin(scheduler.xy, settings.xy, true);
        return true;
    }

    // let templates = [];
    // $(topics).each(function(i, topic) {
    //     if (topic.startsWith('SPONSOR - ')) {
    //         var parts  = topic.split("-");
    //         var key = parts[1].trim();
    //         var label = parts[2].trim();
    //         var sponsor = {key,label};
    //         templates.push(sponsor);
    //     }
    // });

    // let rules = ["Select Rule"];
    let rules = [{ "key": "DEFAULT", "label": "10 Posts to Enter" }];
    let templates = [{ "key": "DEFAULT", "label": "£1000 in vouchers + seeds" }];
    let prizes = [{ "key": "DEFAULT", "label": "Denotes a Sponsor Specific Prize" }];
    let created_by = [{ "key": "DEFAULT", "label": "Select who created" }];
    let claimed_by = [{ "key": "DEFAULT", "label": "Select who Won" }];
    let posted_to = [{ "key": "DEFAULT", "label": "Grow Room 420 Competitions" }];
    let status = [{ "key": "DEFAULT", "label": "Automated" }];
    sponsors = loadJSON('/phpbb/ext/minty/competitions/styles/all/template/json/sponsors.json');
    
    scheduler.config.responsive_lightbox = true;
    scheduler.config.multi_day = true;
    scheduler.config.prevent_cache = true;
    scheduler.config.details_on_create = true;
    scheduler.config.details_on_dblclick = true;
    scheduler.config.date_format = "%Y-%m-%d %H:%i";
    
    scheduler.config.responsive_lightbox = true;
    scheduler.config.lightbox.sections = [
        { name: "Sponsor", tag: "SPONSOR", type: "select", map_to: "sponsor", options: sponsors },
        { name: "Template", tag: "TEMPLATE", type: "select", map_to: "template", options: templates },
        { name: "Rules", tag: "RULE", type: "select", map_to: "rules", options: rules },
        { name: "Prize", tag: "PRIZE", type: "select", map_to: "prize", options: prizes },
        { name: "Post To", tag: "TARGET", type: "select", map_to: "post_to", options: posted_to },
        //{ name: "Claimed By", tag: "CLAIMED", type: "select", map_to: "prizes", options: claimed_by },
        { name: "Notes", tag: "COMPTEXT", map_to: "text", type: "textarea" },
        //{ name: "Created By", tag: "CREATED", type: "select", map_to: "created_by", options: created_by },
        { name: "Status", tag: "STATUS", type: "select", map_to: "status", options: status },
        { name: "time", height:72, type:"calendar_time", map_to:"auto" }
    ];

    
    // resetConfig();
    // scheduler.attachEvent("onBeforeViewChange", resetConfig);
    // scheduler.attachEvent("onSchedulerResize", resetConfig);
    scheduler.init('compitition_scheduler', new Date(), "month");
    //scheduler.load("/phpbb/ext/minty/competitions/styles/all/template/json/data.json");
    
    // document.querySelector(".add_event_button").addEventListener("click", function(){
    //     scheduler.addEventNow();
    // });
    
    // scheduler.attachEvent("onEventAdded", function (id, event) {
    //     //event.color = getColour(event);
    //     event.desc = event.start_date + " " + event.end_date + " " +  event;
    // });
    // scheduler.attachEvent("onEventChanged", function (id, event) {
    //     event.color = sponsor.color;
    //     event.desc = event.start_date + " " + event.end_date + " " +  event;
    // });


    // var cfg = scheduler.config;
    // var strToDate = scheduler.date.str_to_date(cfg.date_format, cfg.server_utc);
     
    // scheduler.templates.parse_date = function(date){
    //     return strToDate (date);
    // };


    let url = '/phpbb/app.php/minty/data';
    scheduler.load(url);
    var dp = new dataProcessor(url);
    dp.init(scheduler);
    dp.enableDebug();
    
    dp.attachEvent("onBeforeUpdate", function(id, state, data){
        console.log(this);
        return true;
    });


});